<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class MobileController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('MobileSales');
		$this->loadComponent('MobileDemands');

		$this->SetList->remove(['Categories', 'Sections']);
	}

	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);
		$this->viewBuilder()->layout('single');
	}

	public function index() {
		
	}

	public function sales($subAction = 'index') {
		return $this->MobileSales->{$subAction}();
	}

	public function demands($subAction = 'index') {
		return $this->MobileDemands->{$subAction}();
	}
	
	public function comments(){
		$table_c = TableRegistry::get('Comments');
		$table_s = TableRegistry::get('Sales');

		$data = $this->request->data;
		
		switch( $data['method']){
			case 'post':
				$comment = $table_c->newEntity([
					'sale_id' => $data['sale_id'],
					'user_id'=> $this->getLoginUser()['id'],
					'text'=>$data['text'],
					'parent_id'=>NULL
				]);
				break;

			case 'edit':
				$comment = $table_c->get( $data['comment_id']);
				$comment = $table_c->patchEntity( $comment , [
					'text'=>$data['text'],
				]);
				break;
			
			case 'response':
				$comment = $table_c->newEntity([
					'sale_id' => $data['sale_id'],
					'user_id'=> $this->getLoginUser()['id'],
					'text'=>$data['text'],
					'parent_id'=>$data['comment_id']
				]);
		}
		
		$r = $table_c->save( $comment );
		
		$sale = $table_s->get( $data['sale_id'] , ['contain'=>['Users' => ['fields' => ['name']]]]);
		
		$this->set([
			'item' => $sale,
			'active'=>true,
		]);
		$this->viewBuilder()->autoLayout(false);
		$this->render('/Element/Mobile/cardSale/comments');
	}

	public function products() {
		$this->SetList->add('Categories');
	}
	
	public function debug(){
		
	}

}
