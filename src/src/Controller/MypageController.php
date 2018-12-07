<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\Core\Configure;

/**
 * CakePHP Config
 * @author MoripoweDT
 */
class MypageController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Schedule');
		$this->loadComponent('DirectMail');
		$this->loadComponent('Follow');
	}

	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);

		$this->viewBuilder()->layout('mypage');
	}

	public function index() {
		$this->redirect(['action' => 'result']);
	}

	public function result($user_id = NULL, $date_start_string = NULL, $date_end_string = NULL) {
		$table_u = TableRegistry::get('Users');
		$table_r = TableRegistry::get('Results');

		//user取得
		if (empty($user_id)) {
			$user_id = $this->getLoginUser()['id'];
		}

		$user = $table_u->get($user_id);

		//開始日、終了日取得
		$date = [];
		if ($date_start_string == NULL) {
			$date['start'] = new Date('now');
			$date['start']->day(1);
		} else {
			$date['start'] = new Date($date_start_string);
		}

		if ($date_end_string == NULL) {
			$date['end'] = new Date('now');
			$date['end']->day(1);
		} else {
			$date['end'] = new Date($date_end_string);
		}

		//タイプ別データ取得
		$results = [];
		foreach (Configure::read('result.type') as $type_id => $type_name) {
			$results[$type_id] = $table_r->find('total')
					->where([
				'date >=' => $date['start'],
				'date <=' => $date['end'],
				'user_id' => $user_id,
				'type' => $type_id,
					])
			;
		}
		
		//合計値
		$results_sum = $table_r->find()
					->where([
				'date >=' => $date['start'],
				'date <=' => $date['end'],
				'user_id' => $user_id,
					])
				->group('type')
				->select('type')
				->find('sum')
				->toArray();
		
		//データの存在する日付をリストアップ
		$list_date = $table_r->find('list',['valueField'=>'date'])
				->where(['user_id'=>$user_id])
				->order(['date'])
				->group('date');
		

		$this->set(compact('date', 'user', 'results','results_sum','list_date'));
	}

	public function schedule($user_id = NULL, $date_string = NULL) {
		return $this->Schedule->edit($user_id, $date_string);
	}

	public function directMail($user_id = NULL, $date_string = NULL) {
		return $this->DirectMail->view($user_id, $date_string);
	}

	public function follow($user_id = NULL, $date_string_start = NULL , $date_string_end = NULL) {
		return $this->Follow->view($user_id, $date_string_start , $date_string_end);
	}

	public function debug() {
		$table_s = TableRegistry::get('Sales');
		$r = $table_s->find('DmDate')->toArray();

		$this->set('data', $r);
		$this->render('../Common/debug');
	}
}
