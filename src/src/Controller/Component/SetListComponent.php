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
class SetListComponent extends Component {

	protected $_controller;
	protected $_dataToSet = [];

	public function initialize(array $config = []) {
		parent::initialize($config);
		$this->_controller = $this->_registry->getController();
		$this->_dataToSet = $config;
	}

	protected function _getLoginUserId() {
		$session = $this->request->session();
		return $session->read('Auth.User.id');
	}

	public function beforeRender(\Cake\Event\Event $event) {
		foreach ($this->_dataToSet as $label) {
			$this->{$label}();
		}
	}

	public function add($value) {
		$this->_dataToSet = array_unique(
				array_merge($this->_dataToSet, (array) $value)
		);
	}

	public function remove($value) {
		if (is_array($value)) {
			foreach ($value as $v) {
				$this->remove($v);
			}
			return;
		}

		if (($key = array_search($value, $this->_dataToSet)) !== false) {
			unset($this->_dataToSet[$key]);
		}
		$this->_dataToSet = array_values($this->_dataToSet);
	}

	/**
	 * カテゴリデータ格納
	 * 主に左ナビで使用
	 */
	public function Categories() {
		$table_products = TableRegistry::get('Products');

		$name_categories = $table_products->find('list', ['keyField' => 'id', 'valueField' => 'category'])
				->order(['category_order' => 'ASC', 'category' => 'ASC'])
				->group('category');

		$name_products = $table_products->find('list', ['keyField' => 'id', 'valueField' => 'name'])
				->order(['category_order' => 'ASC', 'category' => 'ASC', 'product_order' => 'ASC', 'name' => 'ASC']);

		$list_products = $table_products->find()
				->order(['category_order' => 'ASC', 'category' => 'ASC', 'product_order' => 'ASC', 'name' => 'ASC']);

		$this->_controller->set(compact('name_categories', 'name_products', 'list_products'));
	}

	/**
	 * 所属別人名リスト
	 * 主に右ナビで使用
	 */
	public function Sections() {
		$table_users = TableRegistry::get('Users');
		$section_names = \Cake\Core\Configure::read('user.section');
		$sections = [];
		foreach ($section_names as $section_name) {
			$sections[$section_name] = $table_users->find('list', ['keyField' => 'id', 'valueField' => 'name'])
					->order(['position' => 'DESC'])
					->where(['section' => $section_name]);
		}

		$this->_controller->set(compact('sections'));
	}

	/**
	 * 人名リスト　入力Autocompleteで利用
	 */
	public function Users() {
		$table_users = TableRegistry::get('Users');
		$name_users = $table_users->find('list', ['keyField' => 'id', 'valueField' => 'name'])
				->toArray();
		$this->_controller->set(compact('name_users'));
	}

	/**
	 * 顧客名リスト　入力Autocompleteで利用
	 */
	public function Clients() {
		$table_sales = TableRegistry::get('Sales');

		$name_clients = $table_sales->find('list', ['keyField' => 'id', 'valueField' => 'client_name'])
				->where(['client_name is not' => NULL])
				->group('client_name')
				->toArray();
		$this->_controller->set(compact('name_clients'));
	}

	/**
	 * 自分が担当した営業先リスト
	 */
	public function MyClients() {
		$table_sales = TableRegistry::get('Sales');
		$user_id = $this->_getLoginUserId();

		$name_my_clients = $table_sales->find('list', ['keyField' => 'id', 'valueField' => 'client_name'])
				->where(['client_name is not' => NULL])
				->where(['user_id' => $user_id])
				->group('client_name')
				->toArray();

		$this->_controller->set(compact('name_my_clients'));
	}

	/**
	 * 営業先担当者リスト　AutoCompleteで利用
	 */
	public function MyCharges() {
		$table_sales = TableRegistry::get('Sales');
		$user_id = $this->_getLoginUserId();

		$name_my_charges = $table_sales->find('list', ['keyField' => 'id', 'valueField' => 'charge_person'])
				->where(['charge_person is not' => NULL])
				->where(['user_id' => $user_id])
				->group('charge_person')
				->toArray();

		$this->_controller->set(compact('name_my_charges'));
	}

}
