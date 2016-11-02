<?php

namespace App\Model\Entity\Traits;

use Cake\ORM\TableRegistry;
use Cake\Network\Session;

trait ReadCheckTrait {

	static $_alias = NULL;
	protected $_session;

	private function getAlias() {
		if ($this->_alias) {
			return $this->_alias;
		}

		$match = NULL;
		preg_match('/(\w+)s$/', mb_strtolower($this->_registryAlias), $match);
		$this->_alias = $match[1];

		return $this->_alias;
	}
	
	protected function _getSession(){
		if( $this->_session ){
			return $this->_session;
		}
		
		$this->_session = new Session();
		return $this->_session;
	}
	
	protected function _getLoginUserId(){
		return $this->_getSession()->read('Auth.User.id');
	}

	private function getTable() {
		return TableRegistry::get($this->_registryAlias . 'Users');
	}

	protected function _getRead() {
		$loginuser_id = $this->_getLoginUserId();
		$table = $this->getTable();
		$alias = $this->getAlias();

		$link = $table->find()
				->where([ $alias . '_id' => $this->id, 'user_id' => $loginuser_id])
				->first();

		return empty($link);
	}

	protected function _setRead($read) {
		$alias = $this->getAlias();

		$loginuser_id = $this->_getLoginUserId();
		$Users = TableRegistry::get('Users');
		$loginuser = $Users->get($loginuser_id);


		$Table = TableRegistry::get($this->_registryAlias);

		$Table->belongsToMany('UnreadUsers', [
			'className' => 'Users',
			'foreignKey' => $alias . '_id',
			'targetForeignKey' => 'user_id',
			'joinTable' => $alias . 's_users',
		]);

		if ($read) {
			$Table->UnreadUsers->unlink($this, [$loginuser]);
		} else {
			$Table->UnreadUsers->link($this, [$loginuser]);
		}
		return $read;
	}
/*
	protected function _getNewPost() {
		if (!$this->date) {
			return false;
		}
		$last_login = $_SESSION['Auth']['User']['last_login'];
		return ( $this->date > $last_login );
	}
*/
}
