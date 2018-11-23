<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View;

use Cake\View\View;
use Cake\Core\Configure;
use App\Utils\AppUtility;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View {

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading helpers.
	 *
	 * e.g. `$this->loadHelper('Html');`
	 *
	 * @return void
	 */
	public function initialize(array $options = []) {

		$this->loadHelper('Html', ['className' => 'BootstrapUI.Html']);
		$this->loadHelper('Form', ['className' => 'BootstrapUI.Form']);
		$this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
		$this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);
	}

	public function getAction() {
		$action = AppUtility::snake($this->request->action);
		return $action;
	}

	public function getController() {
		$controller = AppUtility::snake($this->name);
		return $controller;
	}

	public function getLoginUser() {
		if (!isset($this->request->Session()->read('Auth')['User'])) {
			return NULL;
		}
		return $this->request->Session()->read('Auth')['User'];
	}

	public function isAdmin() {
		$loginUser = $this->getLoginuser();
		$list_admin = Configure::read('user.admin');
		return in_array($loginUser['name'], $list_admin);
	}

	public function isMobile() {
		return $this->request->is('mobile');
	}

	/**
	 * snake
	 * @param type $controller
	 * @param type $action
	 * @return boolean
	 */
	public function isMatch($controller, $action = NULL) {
		if (!empty($controller) && !in_array($this->getController(), (array) $controller)) {
			return false;
		}

		if (empty($action)) {
			return true;
		}

		foreach ((array) $action as $a) {
			if ($this->getAction() == AppUtility::snake($a)) {
				return true;
			}
		}
		
		return false;
	}

}
