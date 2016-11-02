<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

/**
 * CakePHP Config
 * @author MoripoweDT
 */
class ConfigController extends AppController {

	public $config_list = ['sale', 'demand', 'product', 'user', 'result', 'style'];

	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);

		$this->viewBuilder()->layout('management');
	}

	public function index() {
		
	}

	public function setProjectDo() {
		return $this->_setList('sale.project_do', '営業報告　Do');
	}

	public function setProjectAct() {
		return $this->_setList('sale.project_act', '営業報告　Action');
	}

	public function setResult() {
		return $this->_setList('sale.result', '営業報告　結果');
	}

	public function setState() {
		return $this->_setList('sale.state', '営業報告　手ごたえ');
	}

	public function setTreatment() {
		return $this->_setList('sale.treatment', '営業報告　対応');
	}

	public function setDemandType() {
		return $this->_setList('demand.type', '顧客の声　要望区分');
	}

	public function setAdmin() {
		return $this->_setList('user.admin', 'ユーザー　管理者');
	}

	public function setSection() {
		return $this->_setList('user.section', '右メニュー　表示部署');
	}

	public function setResultType() {
		return $this->_setList('result.type', '売り上げ　区分');
	}

	protected function _setList($key, $label) {
		if ($this->request->is('post')) {
			$value = explode(',', $this->request->data['value']);
			Configure::write($key, $value);
			$this->_updateConfig();
		}

		$value = implode(',', Configure::read($key));

		$this->set(compact('key', 'label', 'value'));
		$this->render('set_list');
	}

	protected function _updateConfig() {
		Configure::dump('const', 'default', $this->config_list);
	}

	public function setStyle() {

		$options = [
			'style'=>'空',
			'style.color01'=>'柿',
			'style.color02'=>'桃',
			'style.color03'=>'瓜',
			'style.color04'=>'竹',
		];

		return $this->_setSelect('style.css', '配色', $options);
	}

	protected function _setSelect($key, $label, $options) {
		if ($this->request->is('post')) {
			$value = $this->request->data['value'];
			Configure::write($key, $value);
			$this->_updateConfig();
		}

		$value = Configure::read($key);

		$this->set(compact('key', 'label', 'value', 'options'));
		$this->render('set_select');
	}

}
