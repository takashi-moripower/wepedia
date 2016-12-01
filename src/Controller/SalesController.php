<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use App\Controller\Traits\setDataTrait;
use Cake\View\View;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\Time;

/**
 * Sales Controller
 *
 * @property \App\Model\Table\SalesTable $Sales
 */
class SalesController extends AppController {

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
			'boss_check2',
			'date',
			'time',
			'user_id',
			'client_name',
			'charge_person',
			'title',
			'result',
			'state',
			'report',
			'treatment',
			'modified',
			'created',
			'UnreadSales.user_id'
		],
		'order' => [
			'date' => 'desc',
			'time' => 'desc'
		]
	];
	public $common_key = [
		'user_id',
		'client_name',
		'date',
		'time',
		'published',
		'deleted',
		'boss_check',
		'boss_check2'
	];
	public $list_return_action = [
		'normal' => 'index',
		'unpublished' => 'draft',
		'deleted' => 'trashbox'
	];
	public $export_columns = [
		'id',
		'user_name',
		'client_name',
		'date',
		'time_text',
		'charge_person',
		'title',
		'report',
		'result',
		'state',
		'treatment',
		'next_date',
		'project_do',
		'project_act',
		'agent_name',
	];
	public $import_columns;

	public function initialize() {
		parent::initialize();

		$this->_loadSearchComponents(['index', 'draft', 'trashbox']);

		$this->import_columns = $this->export_columns;
	}

	public function clear() {
		$this->Search->clear();
		return $this->redirect(['action' => 'index']);
	}

	public function index() {
		return $this->_desktopIndex([
					'flags' => 'normal',
					'limit' => 20
		]);
	}

	public function draft() {
		$search_default = [
			'flags' => 'unpublished',
			'limit' => 20
		];

		if (!$this->isAdmin()) {
			$search_default['user_id'] = json_encode([$this->getLoginUser()['id']]);
		}

		return $this->_desktopIndex($search_default);
	}

	public function trashbox() {
		$search_default = [
			'flags' => 'deleted',
			'limit' => 20
		];

		if (!$this->isAdmin()) {
			$search_default['user_id'] = json_encode([$this->getLoginUser()['id']]);
		}

		return $this->_desktopIndex($search_default);
	}

	protected function _desktopIndex($search_default) {

		/* エクスポートボタンを押した結果なら、csvファイルとして出力　 */
		if (!empty($this->request->data['action']) && $this->request->data['action'] == 'export') {
			unset($this->request->data['action']);
			$this->SearchSession->writeSession($this->request->data); //セッションからエクスポートフラグを除去

			return $this->_export($search_default);
		}

		$loginuser_id = $this->Auth->user('id');


		/* 	各種パラメータによる絞り込み	 */

		$search_param = $this->request->data + $search_default;
		$query = $this->Sales->find('search', $this->Sales->filterParams($search_param))
				->contain(['Users']);

		/* 	通常報告一覧の場合、最終報告のみ表示	 */
		if ($search_param['flags'] == 'normal') {
			$query->where(['child_id is' => NULL]);
		}

		/*  表示件数設定　 */
		if (isset($search_param['limit'])) {
			$this->paginate['limit'] = $search_param['limit'];
		}

		/* 	取得するフィールドの定義		 */
		$query->group('Sales.id')
				->contain([
					'Users' => ['fields' => ['name', 'face']],
					'UnreadSales' => ['fields' => ['user_id'], 'conditions' => ['UnreadSales.user_id' => $loginuser_id]]
		]);

		/* 	表示順設定	 */
		if (isset($search_param['sort']) && isset($search_param['direction'])) {
			$this->paginate['order'] = [
				$search_param['sort'] => $search_param['direction']
			];

			$query->order([
				$search_param['sort'] => $search_param['direction']
			]);
		}

		$sales = $this->paginate($query);

		$this->set('sales', $sales);
		$this->set('search', $search_param);
		$this->set('_serialize', ['sales']);

		/* 既読判定をまとめて取得　 */
		foreach ($sales as $sale) {
			$list_id[] = $sale['id'];
		}

		$table_links = TableRegistry::get('SalesUsers');
		if (!empty($list_id)) {
			$lsit_unread = $table_links->find('list')
					->where(['user_id' => $loginuser_id, 'sale_id IN' => $list_id])
					->toArray();
		} else {
			$lsit_unread = [];
		}

		$this->set('list_unread', $lsit_unread);

		/* autocomplete用情報　 */

		$this->SetList->add(['Clients', 'Users']);
		$this->viewBuilder()->layout('searchUsers');
		$this->render('index');
	}

	public function view($id) {

		$sale = $this->Sales->getWithName($id);

		if ($sale->flags != 'normal') {
			$this->set('sale', $sale);
			$this->render('viewSingle');
			return;
		}

		if (!$sale->isRoot()) {
			return $this->redirect(['controller' => 'sales', 'action' => 'view', $sale->root_id]);
		}

		$root_id = $sale->root_id;

		$root = $this->Sales->getWithName($root_id);
		$root->read = true;

		$latest = $root->latest;
		$latest->read = true;
		foreach ($root->previous as $node) {
			$node->read = true;
		}
		
		$next_id = $root->next_id;
		$prev_id = $root->prev_id;

		$this->set(compact(['root' , 'next_id' , 'prev_id' ]));
	}

	public function add($root_id = NULL) {
		$sale = $this->Sales->getEntityToAdd($root_id);
		return $this->_edit($sale);
	}

	public function edit($id) {
		$sale = $this->Sales->get($id);
		return $this->_edit($sale);
	}

	protected function _edit($sale) {
		$this->SetList->add(['MyClients', 'MyCharges', 'Users']);

		if ($this->request->is(['post', 'patch', 'put'])) {
			$this->Sales->patchEntity($sale, $this->request->data);

			$result = $this->Sales->save($sale);
			if ($result) {
				$this->Sales->setTree($sale);
				$this->Flash->success('営業報告データは正常に保存されました');

				return $this->redirect(['controller' => 'sales', 'action' => 'view', $sale->id]);
			} else {
				$this->Flash->success('営業報告データの保存に失敗しました');
			}
		}

		$this->set(compact('sale'));
		$this->render('edit');
	}

	protected function setDemandFlags(&$sale) {
		if (empty($sale['demands'])) {
			return;
		}

		foreach ($sale['demands'] as &$demand) {
			$key_list = $this->common_key;

			foreach ($key_list as $key) {
				if (isset($sale[$key])) {
					$demand[$key] = $sale[$key];
				}
			}
		}
		$sale->dirty('demands', true);
	}

	protected function _export($search_default) {
		/* 	各種パラメータによる絞り込み	 */

		$search_param = $this->request->data + $search_default;
		$query = $this->Sales->find('search', $this->Sales->filterParams($search_param))
				->contain(['Users']);

		$this->loadComponent('Export');

		return $this->Export->export($query, ['columns' => $this->export_columns]);
	}

	public function delete_all() {
		$table_demands = TableRegistry::get('Demands');

		$count_demands = $table_demands->find()->count();

		if ($count_demands > 0) {
			$this->set(compact('count_sales', 'count_demands'));
			return;
		}
		return $this->trait_delete_all();
	}

	public function good($id) {
		$sale = $this->Sales->get($id);
		$sale->good ++;
		$this->Sales->save($sale);
		$this->redirect(['controller' => 'sales', 'action' => 'view', $id]);
	}

	public function cheer($id) {
		$sale = $this->Sales->get($id);
		$sale->cheer ++;
		$this->Sales->save($sale);
		$this->redirect(['controller' => 'sales', 'action' => 'view', $id]);
	}

	public function boss_check($id) {
		$sale = $this->Sales->get($id);
		$sale->boss_check = !($sale->boss_check);
		$this->Sales->save($sale);
		$this->redirect(['controller' => 'sales', 'action' => 'view', $id]);
	}

	public function boss_check2($id) {
		$sale = $this->Sales->get($id);
		$sale->boss_check2 = !($sale->boss_check2);
		$this->Sales->save($sale);
		$this->redirect(['controller' => 'sales', 'action' => 'view', $id]);
	}

	/**
	 * 一件削除
	 * @param type $id
	 * @return type
	 */
	public function deleteNode($id) {
		$sale = $this->Sales->get($id);
		if ($sale->isRoot()) {
			return $this->redirect(['controller' => 'sales', 'action' => 'deleteRoot', $id]);
		}

		$sale->flags = 'deleted';
		$result = $this->Sales->save($sale);
		if ($result) {
			$this->Flash->success('営業報告データはゴミ箱に移動しました');
		} else {
			$this->Flash->error('営業報告データの削除に失敗');
		}

		return $this->redirect(['controller' => 'sales', 'action' => 'view', $sale->root_id]);
	}

	public function deleteRoot($root_id) {
		$nodes = $this->Sales->find()
				->where(['root_id' => $root_id]);
		foreach ($nodes as $node) {
			$node->flags = 'deleted';
			$this->Sales->save($node);
		}

		$demands = $this->Sales->Demands->find()
				->where(['sale_id' => $root_id]);
		foreach ($demands as $demand) {
			$demand->flags = 'deleted';
			$this->Sales->Demands->save($demand);
		}

		$root = $this->Sales->get($root_id);
		$root->flags = 'deleted';
		$result = $this->Sales->save($root);

		if ($result) {
			$this->Flash->success('営業報告データはゴミ箱に移動しました');
		} else {
			$this->Flash->error('営業報告データの削除に失敗');
		}
		return $this->redirect(['controller' => 'sales', 'action' => 'index', 'clear' => true]);
	}

	public function import() {
		$this->viewBuilder()->layout('management');
		$this->loadComponent('Import');
		$this->Import->import($this->import_columns);
	}

	public function deleteComplete($id) {
		$entity = $this->Sales->get($id);

		if ($entity->flags != 'deleted') {
			$this->Flash->error('不正な入力');
			return $this->redirect(['controller' => 'sales', 'action' => 'trashbox', 'clear' => true]);
		}

		$this->Sales->delete($entity);
		$this->Flash->success('営業報告データを完全に削除しました');
		return $this->redirect(['controller' => 'sales', 'action' => 'trashbox', 'clear' => true]);
	}

	public function deleteNodesComplete($json_ids) {
		$ids = json_decode($json_ids);
		$table = $this->Sales;
		foreach ($ids as $id) {
			$entity = $table->get($id);
			$table->delete($entity);
		}
		$this->Flash->success('営業報告データを完全に削除しました');
		return $this->redirect(['controller' => 'sales', 'action' => 'trashbox', 'clear' => true]);
	}

	public function deleteNodes($json_ids) {
		$ids = json_decode($json_ids);
		$table = $this->Sales;
		foreach ($ids as $id) {
			$entity = $table->get($id);
			$entity->flags = 'deleted';
			$table->save($entity);
		}
		$this->Flash->success('営業報告データをゴミ箱へ移動しました');
		return $this->redirect(['controller' => 'sales', 'action' => 'draft', 'clear' => true]);
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

		$items = $this->Sales->find('flags', ['flags' => 'deleted']);
		foreach ($items as $item) {
			$this->Sales->delete($item);
		}

		$this->Flash->success('ゴミ箱の中のデータをすべて削除しました');
		return $this->render('/Common/emptyTrash');
	}

}
