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
use App\Defines\Defines;
use Cake\Utility\Hash;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class FollowComponent extends Component {

	public $Users;
	public $Sales;
	protected $_user;
	protected $_controller;

	public function initialize(array $config = []) {
		parent::initialize($config);

		$this->Users = TableRegistry::get('Users');
		$this->Sales = TableRegistry::get('Sales');

		$this->_controller = $this->_registry->getController();
	}

	public function view($user_id, $date_string_start, $date_string_end) {
		if (empty($user_id)) {
			$user_id = $this->_controller->getLoginUser()['id'];
		}
		$this->_user = $this->Users->get($user_id);
		$this->_controller->set('user', $this->_user);


		$cq = $this->Sales->find('Flags', ['flags' => 'normal'])
				->where([
			'user_id' => $user_id,
			'result' => Defines::SALES_RESULT_FOLLOWING,
			'child_id is' => NULL,
		]);
		$collection = new \App\Model\Entity\Collections\FollowsCollection($cq);
		
		$this->_controller->set('collection',$collection);

		if ($date_string_start) {
			$date_start = new Date($date_string_start);
		} else {
			$date_start = NULL;
		}

		if ($date_string_end) {
			$date_end = new Date($date_string_end);
		} else {
			$date_end = NULL;
		}

		$this->_controller->set(compact('date_start', 'date_end'));

		$query = $this->Sales->find('Flags', ['flags' => 'normal'])
				->where([
			'user_id' => $user_id,
			'result' => Defines::SALES_RESULT_FOLLOWING,
			'child_id is' => NULL,
		]);
		if ($date_start) {
			$query->where([
				'date >=' => $date_start
			]);
		}

		if ($date_end) {
			$query->where([
				'date <=' => $date_end
			]);
		}

		$sales = $query;

		$this->_controller->set('sales', $sales);
	}

	protected function _getQuery($user_id, $start, $end) {
		$query = $this->Sales->find('Flags', ['flags' => 'normal'])
				->where([
			'user_id' => $user_id,
			'result' => Defines::SALES_RESULT_FOLLOWING,
			'child_id is' => NULL,
		]);
		if ($start) {
			$query->where([
				'date >=' => $start
			]);
		}

		if ($end) {
			$query->where([
				'date <=' => $end
			]);
		}

		return $query;
	}

	public function view0($user_id, $date_string_start, $date_string_end) {
		if (empty($user_id)) {
			$user_id = $this->_controller->getLoginUser()['id'];
		}
		$this->_user = $this->Users->get($user_id);

		if (empty($date_string)) {
			$date = new Date('6 Month ago');
		} else {
			$date = new Date($date_string);
		}


		$sales = $this->Sales->find('Flags', ['flags' => 'normal'])
				->where([
					'user_id' => $user_id,
					'result' => Defines::SALES_RESULT_FOLLOWING,
					'child_id is' => NULL,
					'date >=' => $date,
				])
				->order(['date' => 'DESC', 'time' => 'DESC']);

		$date_options = [
			(new Date('1 month ago'))->format('Y-m-d') => '過去1カ月',
			(new Date('6 month ago'))->format('Y-m-d') => '過去6カ月',
			(new Date('12 month ago'))->format('Y-m-d') => '過去1年'
		];

		$this->_controller->set([
			'user' => $this->_user,
			'sales' => $sales,
			'date' => $date,
			'date_options' => $date_options,
		]);
	}

}
