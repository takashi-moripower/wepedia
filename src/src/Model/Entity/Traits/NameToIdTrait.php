<?php

namespace App\Model\Entity\Traits;

use Cake\ORM\TableRegistry;

trait NameToIdTrait {

	protected function _getUserName($value) {
		if( $value ){
			return $value;
		}
		
		return isset($this->user["name"]) ? $this->user["name"] : NULL;
	}

	protected function _setUserName($user_name) {
		$Users = TableRegistry::get("Users");
		$user = $Users->find()
				->select(['id', 'name'])
				->where(['name' => $user_name])
				->first();

		if (empty($user)) {
			return FALSE;
		}

		$this->user_id = $user['id'];
		return $user['name'];
	}
	
	
	protected function _setTimeText( $value ){
		$this->time = new \Cake\I18n\Time( $value );
	}
	
	protected function _getTimeText( $value ){
		if( empty( $this->time )){
			return '00:00';
		}
		return $this->time->format('H:i');
	}

}
