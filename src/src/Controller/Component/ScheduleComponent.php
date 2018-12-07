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

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class ScheduleComponent extends Component {

	public $Users;
	public $Schedules;
	protected $_user;
	protected $_schedule;
	protected $_controller;

	public function initialize(array $config = []) {
		parent::initialize($config);

		$this->Users = TableRegistry::get('Users');
		$this->Schedules = TableRegistry::get('Schedules');

		$this->_controller = $this->_registry->getController();
	}

	public function edit($user_id, $date_string) {
		//user_idの指定がない場合はログインユーザー
		if (empty($user_id)) {
			$user_id = $this->_controller->getLoginUser()['id'];
		}
		$this->_user = $this->Users->get($user_id);

		//日の指定がない場合は今日
		if (empty($date_string)) {
			$date = Date::now();
		} else {
			$date = new Date($date_string);
		}
		
		$this->_controller->set('date',$date);
		
		$date_start = new Date($date);
		$date_start->subDays(13);

		//該当日の日付を含むデータを検索
		$this->_schedule = $this->Schedules->find()
				->where(['user_id' => $user_id])
				->where(['date >=' => $date_start , 'date <=' => $date])
				->order(['date'=>'DESC'])
				->first();
		
		//存在しない場合　該当日の週頭を起点としたデータを新規作成
		if( empty( $this->_schedule )){
			$sunday = $this->_getSunday($date);
			$this->_schedule = $this->Schedules->newEntity([
				'date'=>$sunday,
				'user_id'=>$user_id
			]);
		}
		
		return $this->_processRequest();
	}

	//該当日を含む週の日曜日を返す
	protected function _getSunday($date = NULL) {
		if ($date == NULL) {
			$date = Date::now();
		}

		$w = $date->format('w');
		return $date->subday($w);
	}

	protected function _processRequest() {
		$controller = $this->_controller;

		if ($this->request->is(['post', 'put', 'patch'])) {

			$this->Schedules->patchEntity($this->_schedule, $this->request->data);
			$result = $this->Schedules->save($this->_schedule);
			if ($result) {
				$controller->Flash->success('スケジュールデータは正常に保存されました');
			} else {
				$controller->Flash->error('スケジュールデータの保存に失敗しました');
			}
		}
		$controller->set([
			'user' => $this->_user,
			'schedule' => $this->_schedule,
		]);
	}
}
