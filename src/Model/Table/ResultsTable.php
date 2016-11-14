<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Search\Manager;
use Cake\I18n\Date;
use Cake\Utility\Hash;

/**
 * Results Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Result get($primaryKey, $options = [])
 * @method \App\Model\Entity\Result newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Result[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Result|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Result patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Result[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Result findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResultsTable extends Table {

	use \App\Model\Table\Traits\ImportCSVTrait;

use \App\Model\Table\Traits\DateAroundTrait;

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('results');
		$this->displayField('id');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'INNER'
		]);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator) {
		$validator
				->integer('id')
				->allowEmpty('id', 'create');

		$validator
				->date('date')
				->requirePresence('date', 'create')
				->notEmpty('date');

		$validator
				->integer('target_new')
				->allowEmpty('target_new');

		$validator
				->integer('target_exist')
				->allowEmpty('target_exist');

		$validator
				->integer('previous_new')
				->allowEmpty('previous_new');

		$validator
				->integer('previous_exist')
				->allowEmpty('previous_exist');

		$validator
				->integer('forecast_new')
				->allowEmpty('forecast_new');

		$validator
				->integer('forecast_exist')
				->allowEmpty('forecast_exist');

		$validator
				->integer('result_new')
				->allowEmpty('result_new');

		$validator
				->integer('result_exist')
				->allowEmpty('result_exist');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->existsIn(['user_id'], 'Users'));
		return $rules;
	}

	protected function _getImportColumns() {
		$columns = $this->schema()->columns();
		/* 	user_id ではなく user_name を出力する		 */
		$i = array_search('user_id', $columns);
		if ($i != FALSE) {
			$columns[$i] = 'user_name';
		}
		return $columns;
	}

	/**
	 * 検索コンポーネント用
	 * @return Manager
	 */
	public function searchConfiguration() {
		$search = new Manager($this);
		$search
				->finder('user_id', [ 'finder' => 'UsersJson'])
				->finder('date', [ 'finder' => 'Date'])
		;
		return $search;
	}

	/**
	 * user_id配列をjson形式で受け取り、検索
	 * @param Query $query
	 * @param array $options
	 * @return Query
	 */
	public function findUsersJson(Query $query, array $options) {
		$user_id = json_decode($options['user_id']);

		if (empty($user_id)) {
			return $query;
		}

		$query
				->where(['user_id IN' => $user_id]);
		return $query;
	}

	public function findDate(Query $query, array $options) {

		$date = self::filterDate(Hash::get($options, 'date'));
		$query->where([
			'date >=' => $date['start'],
			'date <=' => $date['end'],
		]);

		return $query;
	}

	/**
	 * 年、月を指定した配列で範囲指定された場合、
	 * それぞれ月初、月末のオブジェクトにして返す
	 * @param type $date
	 * @return type
	 */
	static function filterDate($date) {
		$date_start = Hash::get($date, 'start');
		$date_end = Hash::get($date, 'end');

		if (is_array($date_start)) {
			$data = $date_start;
			$date_start = new Date("{$data['year']}-{$data['month']}-01");
		}
		if (is_array($date_end)) {
			$data = $date_end;
			$date_end = new Date("{$data['year']}-{$data['month']}-01");
			$date_end->addMonth(1);
			$date_end->subDay(1);
		}

		$date = Hash::insert($date, 'start', $date_start);
		$date = Hash::insert($date, 'end', $date_end);

		return $date;
	}

	/**
	 * 	合計値を格納
	 */
	public function findTotal(Query $query, array $options) {
		$query
				->autoFields(true)
				->select([
					'target_total' => 'target_new + target_exist',
					'previous_total' => 'previous_new + previous_exist',
					'forecast_total' => 'forecast_new + forecast_exist',
					'result_total' => 'result_new + result_exist',
		]);

		return $query;
	}

	/**
	 * 期間合計値を取得
	 */
	public function findSum(Query $query, array $options) {
		foreach (['target', 'previous', 'forecast', 'result'] as $word1) {
			foreach (['exist', 'new'] as $word2) {
				$query->select(["sum_{$word1}_{$word2}" => $query->func()->sum("{$word1}_{$word2}")]);
			}

			$query->select(["sum_{$word1}_total" => $query->func()->sum("{$word1}_new + {$word1}_exist")]);
		}

		foreach (['exist', 'new'] as $word2) {
			$query->select(["sum_rate_{$word2}" => "sum( result_{$word2} ) / sum( target_{$word2})"]);
		}

		$query->select(["sum_rate_total" => "(sum( result_new ) + sum( result_exist )) / (sum( target_new ) + sum( target_exist ))"]);

		return $query;
	}

}
