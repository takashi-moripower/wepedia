<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use Cake\I18n\Date;
use Cake\Utility\Hash;
use App\Model\Entity\Collections\DirectmailCollection;

/**
 * CakePHP Config
 * @author MoripoweDT
 */
class CollectionsController extends AppController {

	public $paginateResults = [
		'sortWhitelist' => [
			'user_id',
			'sum_target_new',
			'sum_target_exist',
			'sum_target_total',
			'sum_previous_new',
			'sum_previous_exist',
			'sum_previous_total',
			'sum_forecast_new',
			'sum_forecast_exist',
			'sum_forecast_total',
			'sum_result_new',
			'sum_result_exist',
			'sum_result_total',
			'sum_rate_new',
			'sum_rate_exist',
			'sum_rate_total',
		],
		'order' => [
			'type' => 'asc',
			'date' => 'desc',
			'user_id' => 'asc',
		],
		'limit' => 100,
	];
	public $paginateSchedules = [
		'order' => [
			'date' => 'desc',
			'user_id' => 'asc',
		]
	];
	public $paginateDM = [
		'order' => [
			'date' => 'desc',
			'user_id' => 'asc',
		]
	];
	public $paginateFollow = [
		'order' => [
			'date' => 'desc',
			'user_id' => 'asc',
		]
	];

	public function initialize() {
		parent::initialize();

		$this->viewBuilder()->layout('collections');
		$this->_loadSearchComponents(['results', 'schedules', 'directMails']);
		$this->loadComponent('DummyData');
	}

	public function index() {
		return $this->redirect(['action' => 'results', 'clear' => true]);
	}

	public function results() {
		$table_r = TableRegistry::get('Results');

		$this->paginate = $this->paginateResults;


		//	日付初期値　今月初日
		$date_default = new Date();
		$date_default->day(1);

		$search_default = [
			'date' => [
				'start' => $date_default,
				'end' => $date_default,
			],
		];
		//	各種パラメータによる絞り込み
		$search_param = $this->request->data + $search_default;

		//年・月データから　日単位の範囲に修正
		$query = $table_r->find('search', $table_r->filterParams($search_param));


		//タイプ別集計
		$result_type = [];
		foreach (\Cake\Core\Configure::read('result.type') as $type_id => $type_name) {
			$query_type = $query->cleanCopy();
			$query_type
					->where(['type' => $type_id])
					->contain([
						'Users' => ['fields' => ['name']],
					])
					->group('user_id')
					->select('user_id')
					->find('Sum');

			$result_type[$type_id] = $this->paginate($query_type);
		}
		$this->set('result_type', $result_type);

		//合計
		$query_total = $query->cleanCopy();
		$query_total
				->contain([
					'Users' => ['fields' => ['name']],
				])
				->group('type')
				->select('type')
				->find('Sum');
		$results_total = $this->paginate($query_total);
		$this->set('results_total', $results_total);

		//データの存在する日付をリストアップ
		$list_date = $table_r->find('list', ['valueField' => 'date'])
				->order(['date'])
				->group('date');

		$this->set('list_date', $list_date);



		$this->set('search', $search_param);
		$this->set('_serialize', ['sales']);
	}

	public function schedules($date_string = NULL) {
		$table_s = TableRegistry::get('Schedules');

		if ($date_string == NULL) {
			$date = Date::now();
			$date->subDay($date->format('w'));
		} else {
			$date = new Date($date_string);
		}

		$date_start = new Date($date);
		$date_start->subDay(7);

		$schedules = $table_s->find()
				->where(['date >=' => $date_start])
				->where(['date <=' => $date])
				->contain([ 'Users' => ['fields' => ['name']]]);

		$this->set(compact('date', 'schedules'));
		$this->viewBuilder()->layout('collections2');
	}

	public function directMails() {

		$table_s = TableRegistry::get('Sales');

		$date_list = $table_s
				->find('list' , ['valueField'=>'date'])
				->find('Flags', ['flags' => 'normal'])
				->where(['project_do' => Defines::SALES_DO_DIRECTMAIL])
				->where(['root_id is' => NULL])
				->order(['date' => 'DESC'])
				->select('date')
				->group('date');

		$collections = [];


		foreach ($date_list as $date) {
			$sales = $table_s->find('Flags', ['flags' => 'normal'])
					->where(['project_do' => Defines::SALES_DO_DIRECTMAIL, 'date' => $date]);

			$collections[] = new \App\Model\Entity\Collections\DirectmailCollection($sales);
		}

		$this->set('collections', $collections);
		$this->viewBuilder()->layout('collections2');
	}

	public function follows() {
		$table_s = TableRegistry::get('Sales');
		$table_u = TableRegistry::get('Users');

		$users = $table_s->find('list', ['valueField' => 'user_id'])
				->group('user_id');

		$collections = [];
		foreach ($users as $user_id) {
			if (empty($user_id)) {
				continue;
			}
			$cq = $table_s->find('Flags', ['flags' => 'normal'])
					->where([
				'user_id' => $user_id,
				'result' => Defines::SALES_RESULT_FOLLOWING,
				'child_id is' => NULL,
			]);
			$collections[$user_id] = new \App\Model\Entity\Collections\FollowsCollection($cq);

			$user = $table_u->get($user_id);
			$collections[$user_id]->user_name = $user->name;
		}

		$this->set('collections', $collections);

		$this->viewBuilder()->layout('collections2');
	}

	public function follows0() {
		$table_s = TableRegistry::get('Sales');

		$this->paginate = $this->paginateFollow;

		$search_default = [
			'date' => NULL,
			'limit' => 50
		];

		/* 	各種パラメータによる絞り込み	 */

		$search_param = $this->request->data + $search_default;
		$query = $table_s->find('search', $table_s->filterParams($search_param))
				->where([
					'child_id is' => NULL,
					'result' => Defines::SALES_RESULT_FOLLOWING,
				])
				->order(['user_id' => 'ASC']);

		/*  表示件数設定　 */
		if (isset($search_param['limit'])) {
			$this->paginate['limit'] = $search_param['limit'];
		}
		/* 	取得するフィールドの定義		 */
		$query->group(['Sales.user_id'])
				->contain([ 'Users' => ['fields' => ['name']]]);

		$sales = $this->paginate($query);

		$this->set('sales', $sales);
		$this->set('search', $search_param);
		$this->set('_serialize', ['sales']);

		//絞り込み用選択肢の用意

		$d0 = $table_s->find('dmDate')->toArray();
		$options_date = ['' => '日付'] + array_combine($d0, $d0);


		$this->set(compact('options_date'));
	}

	public function debug() {
		return $this->DummyData->setResults();
	}

}
