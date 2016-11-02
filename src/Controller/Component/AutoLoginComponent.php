<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class AutoLoginComponent extends Component {

	public $components = [
		'Auth',
		'Cookie'
	];
	public $cookie_key = 'AutoLogin';
	public $token_key = 'AutoLogin.token';
	public $expires = '14 days';
	public $table_name = 'AutoLogin';
	public $user_table_name = 'Users';
	protected $_table;

	public function initialize(array $config) {
		parent::initialize($config);

		$this->Cookie->configKey($this->cookie_key, [
			'expires' => '14 days',
			'httpOnly' => true
		]);
	}

	protected function _getTable() {
		if (!$this->_table) {
			$this->_table = TableRegistry::get($this->table_name);
		}
		return $this->_table;
	}

	/**
	 * 自動ログイン対象のユーザーを取得
	 * 成功したらuser,失敗したらNULL
	 * @return type
	 */
	public function identify() {
		$token = $this->Cookie->read($this->token_key);

		/* 	cookieがなかったら自動ログインしない	 */
		if (empty($token)) {
			return NULL;
		}

		$table_al = $this->_getTable();

		/* 	テーブルから古いtokenを削除 */
		$time = new \Cake\I18n\Time("-{$this->expires}");
		$a = $table_al->query()
				->delete()
				->where(['date <' => $time])
				->execute();

		/* 	テーブルにtokenが登録されていない場合	　自動ログイン失敗	 */
		$al = $table_al
				->find()
				->where(['token' => $token])
				->first();

		if (empty($al)) {
			return NULL;
		}

		/* 	token登録済みの場合、ユーザーを検索	 */
		$table_users = TableRegistry::get($this->user_table_name);
		$loginUser = $table_users->get($al->user_id);

		/* 	ユーザーが存在しない場合　自動ログイン失敗	 */
		if (!$loginUser) {
			return NULL;
		}

		/* 	使用済みのtoken破棄	 */
		$this->clearToken($al);

		/* 	新規token登録	 */
		$this->setToken($loginUser->id);

		return $loginUser->toArray();
	}
	
	/**
	 * データベースおよびローカルのtokenを破棄
	 * @return type
	 */
	public function clearToken() {
		$token = $this->Cookie->read($this->token_key);

		if (!$token) {
			return;
		}

		$table_al = $this->_getTable();
		$table_al->query()
				->delete()
				->where(['token' => $token])
				->execute();

		$this->Cookie->delete($this->token_key);
	}

	/**
	 * 新規にtokenを生成し
	 * データベースおよびローカルに保存
	 * @param type $user_id
	 */
	public function setToken($user_id) {
		$table_al = TableRegistry::get($this->table_name);

		$token = $this->_getNewToken();

		$data = $table_al->newEntity();
		$data->token = $token;
		$data->user_id = $user_id;
		$data->date = date("Y-m-d G:i:s ");
		$table_al->save($data);

		$this->Cookie->write($this->token_key, $token);
	}

	protected function _getNewToken() {
		$TOKEN_LENGTH = 16; //16*2=32桁
		$bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
		return bin2hex($bytes);
	}

}
