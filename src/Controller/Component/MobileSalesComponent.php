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
use App\Defines\Defines;
use Cake\Utility\Inflector;
use Cake\Utility\Hash;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class MobileSalesComponent extends Component {

	public $Users;
	public $Sales;
	protected $_controller;
	protected $_sales;

	public function initialize(array $config = []) {
		parent::initialize($config);

		$this->Users = TableRegistry::get('Users');
		$this->Sales = TableRegistry::get('Sales');

		$this->_controller = $this->_registry->getController();
	}

	public function index() {
		if ($this->request->is('post')) {
			$this->_setSession();
		}
		

		$this->_setSales();
		$sales = $this->_sales;

		$this->_controller->set(compact('sales'));
		$this->_controller->SetList->add('Users');
		$this->_controller->render('Sales/index');
	}

	/**
	 * 検索条件をセッションに格納
	 * @return type
	 */
	protected function _setSession() {
		$session = $this->request->session();

		$request = $this->request->data;

		if (Hash::get($request, 'clear', false)) {
			$session->write($this->_getSessionName(), []);
			$this->_controller->set(['clear'=>true]);
			return;
		}
		
		if (Hash::get($request, 'self', false)) {
			$session->write($this->_getSessionName(), ['user_id'=>json_encode( $this->_controller->getLoginUser()['id'] )]);
			$this->_controller->set(['self'=>true]);
			return;
		}

		$session->write($this->_getSessionName(), $request );
	}

	/**
	 * セッションより検索条件を取得
	 * @return type
	 */
	protected function _getSession() {
		$session = $this->request->session();
		$data = $session->read($this->_getSessionName());
		if( $data == NULL ){
			return [];
		}
		return $data;
	}

	protected function _setSales() {
		$search_param = $this->_getSession();
		if( empty( $search_param )){
			$this->_controller->set(['clear'=>true]);
		}

		$this->_sales = $this->Sales
				->find('search', $this->Sales->filterParams($search_param))
				->find('flags', ['flags' => 'normal'])
				->where(['child_id is' => NULL])
				->contain(['Users' => ['fields' => ['name']]])
				->order(['date' => 'DESC', 'time' => 'DESC'])
				->limit(Defines::MOBILE_NODES_PER_PAGE);
	}

	public function load() {
		$offset = $this->request->data['offset'];

		$this->_setSales();
		$this->_sales->offset($offset);

		$newOffset = $offset + Defines::MOBILE_NODES_PER_PAGE;
		$sales = $this->_sales;

		$this->_controller->set(compact('sales', 'newOffset'));
		$this->_controller->viewBuilder()->autoLayout(false);
		$this->_controller->render('Sales/load');
	}

	public function setData() {
		$sale = $this->Sales->get($this->request->data['id'], ['contain' => ['Users' => ['fields' => ['name']]]]);
		$key = $this->request->data['key'];
		$value = $this->request->data['value'];

		$sale->{$key} = $value;

		$this->Sales->save($sale);

		$this->_controller->set(['item' => $sale, 'open' => true]);
		$this->_controller->viewBuilder()->autoLayout(false);
		$this->_controller->render('/Element/Mobile/cardSale');
	}

	public function add() {
		if (isset($this->request->pass[1])) {
			$root_id = $this->request->pass[1];
		} else {
			$root_id = NULL;
		}

		$sale = $this->Sales->getEntityToAdd($root_id);
		$this->_controller->SetList->add(['MyCharges', 'MyClients']);
		return $this->_edit($sale);
	}

	public function edit() {
		$id = $this->request->pass[1];
		$sale = $this->Sales->get($id);
		return $this->_edit($sale);
	}

	protected function _edit($sale) {

		$this->_controller->SetList->add(['Users', 'Clients']);

		if ($this->request->is(['post', 'patch', 'put'])) {
			$this->Sales->patchEntity($sale, $this->request->data);
			$result = $this->Sales->save($sale);
			if ($result) {
				$this->Sales->setTree($sale);
				$this->_controller->Flash->success('営業報告データは正常に保存されました');

				return $this->_controller->redirect(['controller' => 'mobile', 'action' => 'sales']);
			} else {
				$this->_controller->Flash->success('営業報告データの保存に失敗しました');
			}
		}

		$this->_controller->set(compact('sale'));
		$this->_controller->render('Sales/edit');
	}

	protected function _getSessionName() {
		$controller = $this->_registry->getController();
		$controller_name = Inflector::underscore($controller->name);
		$action_name = Inflector::underscore($this->request->action);

		return "search.{$controller_name}.{$action_name}.index";
	}

}
