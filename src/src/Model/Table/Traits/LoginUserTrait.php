<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\Model\Table\Traits;

use Cake\Network\Session;

trait LoginUserTrait {
	
	protected $_session;
	
	protected function _getSession(){
		if( $this->_session ){
			return $this->_session;
		}
		
		$this->_session = new Session();
		return $this->_session;
	}
	
	protected function _getLoginUser(){
		return $this->_getSession()->read('Auth.User');
	}
	
	protected function _getLoginUserId(){
		return $this->_getSession()->read('Auth.User.id');
	}
	
	protected function _getLoginUserName(){
		return $this->_getSession()->read('Auth.User.name');
	}
}