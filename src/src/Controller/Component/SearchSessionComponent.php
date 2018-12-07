<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Utils\AppUtility;

class SearchSessionException extends \Exception {

	protected $_new_request;
	
	public function __construct($new_request) {
		parent::__construct( '', 0, NULL);
		$this->_new_request = $new_request;
	}

	public function getNewRequest() {
		return $this->_new_request;
	}

}

class SearchSessionComponent extends Component {

	protected $_actions = ['index'];

	public function initialize(array $config) {
		parent::initialize($config);
		$this->_actions = isset($config['actions']) ? $config['actions'] : $this->_actions;
	}

	/**
	 * indexアクションの前に割り込む
	 * @param \Cake\Event\Event $event
	 */
	public function startup(\Cake\Event\Event $event) {
		if (in_array($this->request->action, $this->_actions)) {
			return $this->queryToSession();
		}
	}

	public function queryToSession() {
		$request = $this->request->data + $this->request->query;
		$session = $this->readSession();
		$controller = $this->_registry->getController();

		//session情報とrequestを統合　（競合した場合はrequestが優先）
		$new_request = $request + $session;
		try {
			//	resetフラグが設定されていた場合
			//	セッション全情報をクリアしてリダイレクト
			if (!empty($request['clear'])) {
				throw new SearchSessionException([]);
			}

			//	limitが変更されていた場合
			//	セッションのページ数をリセットしてリダイレクト
			if (isset($request['limit'])) {
				if (!isset($session['limit']) || $session['limit'] != $request['limit']) {
					$new_request['page'] = 1;
					throw new SearchSessionException($new_request);
				}
			}

			//	searchフラグが設定されていた = 検索条件変更直後の場合
			//	セッションのページ数をリセットしてリダイレクト
			//	searchフラグは後には残さない
			if (!empty($request['search'])) {				
				unset($new_request['search']);
				$new_request['page'] = 1;
				throw new SearchSessionException($new_request);
			}
			
		} catch (SearchSessionException $ex) {
			$this->writeSession($ex->getNewRequest());
			return $controller->redirect([]);
		}

		//	処理済みの検索情報をセッションに保存		
		$this->writeSession($new_request);

		//	controller->indexにも処理済みの検索条件を渡す
		$this->request->data = $new_request;

		//	ページネーターに情報を渡す
		if (isset($new_request['limit'])) {
			$controller->paginate['limit'] = $new_request['limit'];
		}
	}

	/**
	 * コントローラー名、アクション名を含む　セッション識別名を返す
	 * @return type
	 */
	protected function _getSessionName() {
		$controller = $this->_registry->getController();
		$controller_name = AppUtility::snake( $controller->name );
		$action_name = AppUtility::snake( $this->request->action );
		
		return "search.{$controller_name}.{$action_name}";
	}

	/**
	 * セッション読み込み　存在しない場合空配列を返す
	 * @return type
	 */
	public function readSession() {
		if (!$this->request->session()->check($this->_getSessionName())) {
			return [];
		}
		return $this->request->session()->read($this->_getSessionName());
	}

	/**
	 * セッション書き込み
	 * @param type $data
	 */
	public function writeSession($data) {
		$this->request->session()->write($this->_getSessionName(), $data);
	}
}
