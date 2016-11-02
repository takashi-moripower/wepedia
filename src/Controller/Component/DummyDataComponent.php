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
use Cake\I18n\Time;
use App\Defines\Defines;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class DummyDataComponent extends Component {

	public $Users;
	public $Sales;
	protected $_controller;

	public function initialize(array $config = []) {
		parent::initialize($config);

		$this->Users = TableRegistry::get('Users');
		$this->Sales = TableRegistry::get('Sales');
		$this->Schedules = TableRegistry::get('Schedules');
		$this->Results = TableRegistry::get('Results');

		$this->_controller = $this->_registry->getController();
	}

	public function setDM() {
		$list_DM = [
			[
				'date' => new Date('2016-03-01'),
				'title' => '春の挨拶'
			],
			[
				'date' => new Date('2016-06-01'),
				'title' => '夏の挨拶'
			],
		];

		$clients = $this->Sales->find()
				->where(['client_name is not' => NULL])
				->where(['client_name is not' => ''])
				->group(['client_name', 'user_id']);

		$data = [];
		foreach ($clients as $client) {
			foreach ($list_DM as $dm) {
				$newData = $this->Sales->newEntity([
					'user_id' => $client->user_id,
					'client_name' => $client->client_name,
					'charge_person' => $client->charge_person,
					'title' => $dm['title'],
					'date' => $dm['date'],
					'time' => '00:00:00',
					'report' => '発送',
					'result' => Defines::SALES_RESULT_FOLLOWING,
					'project_do' => Defines::SALES_DO_DIRECTMAIL,
					'project_act' => 'その他',
					'published' => true,
				]);

				$this->Sales->save($newData);
			}
		}

		return $this->_debugOut($data);
	}

	protected function _debugOut($data = NULL) {
		$this->_controller->set('data', $data);
		return $this->_controller->render('/Common/debug');
	}

	public function setResults() {
		$users = $this->Users->find()
				->where(['or' => ['section' => '営業推進部', 'id' => 6]]);

		$dates = [
			new Date('2015-10-01'),
			new Date('2015-11-01'),
			new Date('2015-12-01'),
			new Date('2016-01-01'),
			new Date('2016-02-01'),
			new Date('2016-03-01'),
			new Date('2016-04-01'),
			new Date('2016-05-01'),
			new Date('2016-06-01'),
			new Date('2016-07-01'),
			new Date('2016-08-01'),
			new Date('2016-09-01'),
		];

		$types = \Cake\Core\Configure::read('result.type');

		$count = 0;

		foreach ($users as $user) {
			foreach ($dates as $date) {
				foreach ($types as $type_value => $type_label) {

					$new_entity = $this->Results->newEntity([
						'user_id' => $user->id,
						'date' => $date,
						'type'=>$type_value,
					]);

					foreach (['target', 'previous', 'forecast', 'result'] as $word1) {
						foreach (['new', 'exist'] as $word2) {

							$key = "{$word1}_{$word2}";
							$new_entity->{$key} = rand(0, 99);
						}
					}
					if ($this->Results->save($new_entity)) {
						$count ++;
					}
				}
			}
		}

		return $this->_debugOut($count);
	}

	public function setSchedules() {

		$users = $this->Users->find()
				->where(['or' => ['section' => '営業推進部', 'id' => 6]]);

		$dates = [
			new Date('2016-06-12'),
			new Date('2016-06-26'),
			new Date('2016-07-10'),
			new Date('2016-07-24'),
			new Date('2016-08-07'),
			new Date('2016-08-21'),
			new Date('2016-09-04'),
			new Date('2016-09-18'),
		];

		$count = 0;

		foreach ($users as $user) {
			foreach ($dates as $date) {
				$new_entity = $this->Schedules->newEntity([
					'user_id' => $user->id,
					'date' => $date,
					'work' => $this->getWork(),
					'target' => '腹八分'
				]);
				for ($i = 1; $i <= 14; $i++) {
					$key = sprintf('plan%02d', $i);
					$new_entity->{$key} = $this->getPlan();
				}
				$this->Schedules->save($new_entity);
				$count++;
			}
		}
		return $count;
	}

	public function getPlan() {

		if (rand(0, 3) == 0) {
			return '';
		}

		$word1 = [
			'弁当を',
			'ハンカチを',
			'契約書を',
			'PCを',
			'財布を',
			'携帯電話を',
			'免許証を',
			'上司を',
		];
		$word2 = [
			'美味しく',
			'感動的に',
			'華麗に',
			'あっさり',
			'命がけで',
			'徹底的に',
			'手早く',
			'金の力で',
			'暴力的に',
			'効率的に',
		];
		$word3 = [
			'食べる',
			'買う',
			'燃やす',
			'投げ捨てる',
			'交換する',
			'売り飛ばす',
			'埋める',
			'救出する',
			'褒め称える',
		];

		return $word1[rand(0, count($word1) - 1)] . ' ' . $word2[rand(0, count($word2) - 1)] . ' ' . $word3[rand(0, count($word3) - 1)];
	}

	public function getWork() {
		$word1 = [
			'人類の',
			'世界の',
			'新潟県民の',
			'わが社の',
			'競合他社の',
			'自宅の',
			'実家の',
		];
		$word2 = [
			'平和を',
			'時給を',
			'健康を',
			'食事を',
			'残業時間を',
			'睡眠時間を',
		];
		$word3 = [
			'守る',
			'破壊する',
			'無くす',
			'増やす',
			'減らす',
		];

		return $word1[rand(0, count($word1) - 1)] . ' ' . $word2[rand(0, count($word2) - 1)] . ' ' . $word3[rand(0, count($word3) - 1)];
	}

}
