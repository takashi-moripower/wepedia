<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Controller\Traits\setDataTrait;

/**
 * Demands Controller
 *
 * @property \App\Model\Table\DemandsTable $Demands
 */
class DemandsController extends AppController {

	use setDataTrait;

	/**
	 * Index method
	 *
	 * @return void
	 */
	public $paginate = [
		'sortWhitelist' => [
			'read',
			'boss_check',
			'date',
			'user_id',
			'type',
			'client_name',
			'product_category',
			'product_name',
			'answer_state',
			'modified',
			'created',
			'Products.user_id',
			'UnreadDemands.user_id'
		],
		'order' => [
			'modified' => 'desc'
		]
	];
	public $export_columns = [
		'id',
		'date',
		'time_text',
		'type',
		'user_id',
		'client_name',
		'sale_id',
		'product_category',
		'product_name',
		'demand',
		'answer',
		'answer_id',
		'answer_date',
		'answer_state',
		'boss_check',
		'boss_check2',
	];
	public $import_columns;

	public function initialize() {
		parent::initialize();
		$this->_loadSearchComponents(['index', 'draft', 'trashbox']);
		$this->import_columns = $this->export_columns;
	}

	public function index() {
		$search_default = [
			'flags' => 'normal',
			'limit' => 20
		];

		return $this->desktopIndex($search_default);
	}

	public function draft() {
		$search_default = [
			'flags' => 'unpublished',
			'limit' => 20
		];

		if (!$this->isAdmin()) {
			$search_default['user_id'] = json_encode([ $this->getLoginUser()['id']]);
		}

		return $this->desktopIndex($search_default);
	}

	public function trashbox() {
		$search_default = [
			'flags' => 'deleted',
			'limit' => 20
		];

		return $this->desktopIndex($search_default);
	}

	protected function desktopIndex($search_default) {

		/* エクスポートボタンを押した結果なら、csvファイルとして出力　 */
		if (!empty($this->request->data['action'])) {
			if ($this->request->data['action'] == 'export') {
				return $this->_export($search_default);
			}
		}

		$loginuser_id = $this->Auth->user('id');

		/* 	各種パラメータによる絞り込み	 */

		$search_param = $this->request->data + $search_default;
		$query = $this->Demands->find('search', $this->Demands->filterParams($search_param));

		/* 	取得するフィールドの定義		 */
		$query->group('Demands.id')
				->contain([
					'Users' => ['fields' => ['name', 'face']],
//					'AnswerUsers' => ['fields' => ['name', 'face']],
//					'Products' => ['fields' => ['name', 'category', 'user_id']],
//					'Products.Users' => ['fields' => ['name', 'face', 'id']],
					'UnreadDemands' => ['fields' => ['user_id'], 'conditions' => ['UnreadDemands.user_id' => $loginuser_id]],
		]);

		/*  表示件数設定　 */
		if (isset($search['limit'])) {
			$this->paginate['limit'] = $search['limit'];
		}

		/* 	表示順設定	 */
		if (isset($search_param['sort']) && isset($search_param['direction'])) {
			$this->paginate['order'] = [
				$search_param['sort'] => $search_param['direction']
			];

			$query->order([
				$search_param['sort'] => $search_param['direction']
			]);
		}

		$demands = $this->paginate($query);
		$this->set('demands', $demands);
		$this->set('search', $search_param);
		$this->set('_serialize', ['demands']);

		/* 既読判定をまとめて取得　 */
		foreach ($demands as $demand) {
			$list_id[] = $demand['id'];
		}

		if (!empty($list_id)) {
			$table_links = TableRegistry::get('DemandsUsers');
			$unread = $table_links->find('list')
					->where(['user_id' => $loginuser_id, 'demand_id IN' => $list_id])
					->toArray();
		} else {
			$unread = [];
		}

		$this->set('list_unread', $unread);

		/* autocomplete用情報　 */
		$this->SetList->add(['Clients', 'Users']);
		$this->viewBuilder()->layout('searchUsers');
		return $this->render('index');
	}

	protected function _export($search_default) {
		/* 	各種パラメータによる絞り込み	 */

		$search_param = $this->request->data + $search_default;
		$query = $this->Demands->find('search', $this->Demands->filterParams($search_param))
				->contain(['Users']);

		$this->loadComponent('Export');

		return $this->Export->export($query, ['columns' => $this->export_columns]);
	}

	protected function export0() {

		$search_param = $this->request->data + ['flags' => 'normal'];
		$query = $this->Demands->find('search', $this->Demands->filterParams($search_param));

		$query->group('Demands.id')
				->contain(['Users', 'AnswerUsers', 'Products', 'Products.Users', 'UnreadDemands']);

		$data = $query->limit(NULL)->toArray();

		$columns = $this->getExportColumns();

		return $this->writeCsv($data, $columns);
	}

	public function add($root_id) {
		$demand = $this->Demands->getEntityToAdd($root_id);

		return $this->_edit($demand);
	}

	public function edit($demand_id) {
		$demand = $this->Demands->get($demand_id);

		return $this->_edit($demand);
	}

	protected function _edit($demand) {
		if ($this->request->is(['post', 'patch', 'put'])) {
			$this->Demands->patchEntity($demand, $this->request->data);
			$result = $this->Demands->save($demand);

			if ($result) {
				$this->Flash->success('顧客の声データは正常に保存されました');

				return $this->redirect(['controller' => 'sales', 'action' => 'view', $demand->sale_id]);
			} else {
				$this->Flash->success('顧客の声データの保存に失敗しました');
			}
		}
		$this->set(compact('demand'));
		$this->render('edit');
	}

	public function import() {
		$this->viewBuilder()->layout('management');
		$this->loadComponent('Import');
		$this->Import->import($this->import_columns);
	}

	public function deleteComplete($id) {
		$entity = $this->Demands->get($id);

		if ($entity->flags != 'deleted') {
			$this->Flash->error('不正な入力');
			return $this->redirect(['controller' => 'demands', 'action' => 'trashbox', 'clear' => true]);
		}

		$this->Demands->delete($entity);
		$this->Flash->success('顧客の声データを完全に削除しました');
		return $this->redirect(['controller' => 'demands', 'action' => 'trashbox', 'clear' => true]);
	}

	public function emptyTrash() {
		$this->viewBuilder()->layout('management');

		if (!$this->request->is('post')) {
			return $this->render('/Common/emptyTrash');
		}

		if (!$this->request->data['code'] != !$this->request->data['code2']) {
			$this->Flash->error('不正なコード');
			return $this->render('/Common/emptyTrash');
		}

		$demands = $this->Demands->find('flags', ['flags' => 'deleted']);
		foreach ($demands as $demand) {
			$this->Demands->delete($demand);
		}

		$this->Flash->success('ゴミ箱の中のデータをすべて削除しました');
		return $this->render('/Common/emptyTrash');
	}

	public function deleteNodesComplete($json_ids) {
		$ids = json_decode($json_ids);
		$table = $this->Demands;
		foreach ($ids as $id) {
			$entity = $table->get($id);
			$table->delete($entity);
		}
		$this->Flash->success('顧客の声データを完全に削除しました');
		return $this->redirect(['controller' => 'demands', 'action' => 'trashbox', 'clear' => true]);
	}

	public function deleteNodes($json_ids) {
		$ids = json_decode($json_ids);
		$table = $this->Demands;
		foreach ($ids as $id) {
			$entity = $table->get($id);
			$entity->flags = 'deleted';
			$table->save($entity);
		}
		$this->Flash->success('顧客の声データをゴミ箱へ移動しました');
		return $this->redirect(['controller' => 'demands', 'action' => 'draft', 'clear' => true]);
	}
	
	
	public function debug(){
				
		$data = $this->Demands->find('Produce',['user_id'=>[7]])
				->toArray();
		
		$this->set('data',$data);
		return $this->render('/Common/debug');
	}

}
