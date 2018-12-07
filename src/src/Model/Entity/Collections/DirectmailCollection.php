<?php

namespace App\Model\Entity\Collections;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use Cake\Utility\Hash;

class DirectmailCollection {

	public $total = 0;
	public $no_follow = 0;
	public $following = 0;
	public $finished = 0;
	public $date;
	public $title;
	public $user = NULL;
	
	protected $_query;

	public function __construct($query) {
		$this->_query = $query;

		foreach ($query as $sale) {
			switch ($sale->follow) {
				case Defines::SALES_FOLLOW_NO_FOLLOW:
					$this->no_follow ++;
					break;

				case Defines::SALES_FOLLOW_FOLLOWING:
					$this->following ++;
					break;

				case Defines::SALES_FOLLOW_FINISHED:
					$this->finished ++;
					break;
			}
			$this->total ++;
		}

		if (!empty($sale)) {
			$this->date = Hash::get($sale, 'date');
			$this->title = Hash::get($sale, 'title');
		}
	}

	public function getUsers() {
		$q = $this->_query->cleanCopy();
		return $q->find('list', ['valueField' => 'user_id'])
						->group('user_id')
						->toArray();
	}
	
	public function divideByUsers(){
		$users = $this->getUsers();
		$table_s = TableRegistry::get('sales');
		$table_u = TableRegistry::get('users');
		
		$result = [];
		
		foreach( $users as $user_id ){
			$q = $this->_query->cleanCopy();
			$q->where(['user_id'=>$user_id]);
			$c = new DirectmailCollection( $q );
			
			$c->user = $table_u->get($user_id);
			
			$result[] = $c;
		}
		
		return $result;
	}
}
