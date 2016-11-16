<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class HomeController extends AppController {

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index() {
		if( $this->request->is('post')){
			return $this->_postInformation();
		}
		$this->_setSales();
		$this->_setDemands();
		$this->_setInformations();
		$this->_setSchedule();
	}
	
	protected function _setSales() {
		/*
		 * 営業
		 * 未読、公開済み、未削除
		 * 報告日順
		 */
		$table_sales = TableRegistry::get('Sales');

		$query_sales = $table_sales->find('read', ['read' => 0])
				->where(['deleted' => 0, 'published' => 1])
				->contain(['Users' => ['fields' => ['name']]])
				->order(['date' => 'DESC']);

		$count_sales = $query_sales->count();
		$list_sales = $query_sales->limit(5);

		$this->set(compact('list_sales', 'count_sales'));
	}

	protected function _setDemands() {
		$table_demands = TableRegistry::get('Demands');
		/*
		 * 要望
		 * 未読、公開済み、未削除
		 * 報告日順
		 */
		$query_demands = $table_demands->find('read', ['read' => 0])
				->find('flags', ['flags' => 'normal'])
				->contain(['Users' => ['fields' => ['name']], ])
				->order(['date' => 'DESC']);

		$count_demands = $query_demands->count();
		$list_demands = $query_demands->limit(5);

		$this->set(compact('list_demands', 'count_demands'));
	}
	
	protected function _postInformation(){
		$table_i = TableRegistry::get('Informations');
		$info = $table_i->newEntity([
			'user_id'=>$this->getLoginUser()['id']
		]);
				
		$table_i->patchEntity( $info , $this->request->data );
		
		$result = $table_i->save( $info );
		if( $result ){
			$this->Flash->success('お知らせ情報が正常に追加されました');
		}else{
			$this->Flash->error('お知らせ情報の保存に失敗しました');
		}
		return $this->redirect(['controller'=>'home','action'=>'index']);
	}
	
	protected function _setInformations(){
		$table_i = TableRegistry::get('Informations');
		$informations = $table_i->find()
				->contain(['Users'=>['fields'=>['name']]])
				->order(['modified'=>'DESC'])
				->limit(5);
		
		$this->set(compact('informations'));
	}
	
	protected function _setSchedule(){
		$date = Date::now();
		
		$table_sa = TableRegistry::get('Sales');
		$table_sc = TableRegistry::get('Schedules');

		$schedule = $table_sc->getByDay( $this->getLoginUser()['id'] , $date );
		
		$next_sales = $table_sa->find('Flags',['flags'=>'normal'])
				->where([
					'child_id is'=>NULL,
					'next_date'=>$date
				]);
		
		$this->set(compact('schedule','next_sales'));
	}
}
