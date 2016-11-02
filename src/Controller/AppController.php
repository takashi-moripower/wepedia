<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('Security');`
	 *
	 * @return void
	 */
	
	public function initialize() {
		parent::initialize();
		$this->loadComponent('Flash');
		$this->loadComponent('Paginator');
		
		$this->loadComponent('SetList',['Categories','Sections']);

		$this->loadComponent('Auth', [
			'loginAction' => [
				'controller' => 'Users',
				'action' => 'login',
			],
			'loginRedirect' => [
				'controller' => 'Home',
				'action' => 'index'
			],
			'logoutRedirect' => [
				'controller' => 'Users',
				'action' => 'login'
			],
			'authenticate' => [
				'Form' => [
					'fields' => [
						'username' => 'email',
						'password' => 'password'
					],
					'passwordHasher' => ['className' => 'Default']
				],
			],
			'authError'=>'ログインしてください'
		]);

		$this->loadComponent('RequestHandler');
		
		//	日付の読み込みフォーマットを　年　月　日にする
		\Cake\I18n\Date::$wordFormat = 'yyyy-MM-dd';
		//	日付の出力フォーマットを　年　月　日にする
		\Cake\I18n\Date::setToStringFormat('yyyy-MM-dd');
		
		
	}

	public function getLoginUser() {
		return $this->Auth->user();
	}

	public function isAdmin() {
		$loginUser = $this->getLoginuser();
		$list_admin = Configure::read('user.admin');
		return in_array($loginUser['name'], $list_admin);
	}

	/**
	 * 検索用コンポーネント詰め合わせ
	 * @param type $actions
	 * 　検索機能を使うアクション、Camel記法で
	 */
	protected function _loadSearchComponents($actions = ['index']) {
		if (in_array($this->request->action, $actions)) {
			$this->loadComponent('QtPrg'); //	検索用Search.Prg　をカスタマイズ
			$this->loadComponent('SearchSession', [ 'actions' => $actions]);  //	検索データをセッションに格納するコンポーネント
			$this->loadComponent('Paginator');

			if (!isset($this->paginate)) {
				$this->paginate = [];
			}
			$this->paginate['limit'] = Configure::read('Index.Limit.Default');
		}
	}

}
