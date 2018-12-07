<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class MobileDemandsComponent extends Component {

//	public $Users;
//	public $Sales;
	public $Demands;
	protected $_controller;

	public function initialize(array $config = []) {
		parent::initialize($config);

//		$this->Users = TableRegistry::get('Users');
//		$this->Sales = TableRegistry::get('Sales');
		$this->Demands = TableRegistry::get('Demands');

		$this->_controller = $this->_registry->getController();
	}
	
	public function add(){
		$root_id = $this->request->pass[1];
		$demand = $this->Demands->getEntityToAdd( $root_id );
		
		$this->_edit( $demand );
	}
	
	public function edit(){
		$demand_id = $this->request->pass[1];
		$demand = $this->Demands->get( $demand_id );
		
		$this->_edit( $demand );
	}
	
	protected function _edit( $demand ){

		$this->_controller->SetList->add(['Users', 'Categories']);

		if ($this->request->is(['post', 'patch', 'put'])) {
			$this->Demands->patchEntity($demand, $this->request->data);
			$result = $this->Demands->save($demand);
			if ($result) {
				$this->_controller->Flash->success('顧客の声データは正常に保存されました');

				return $this->_controller->redirect(['controller' => 'mobile', 'action' => 'sales', 'index']);
			} else {
				$this->_controller->Flash->success('顧客の声データの保存に失敗しました');
			}
		}

		$this->_controller->set(compact('demand'));
		$this->_controller->render('Demands/edit');	}

}
