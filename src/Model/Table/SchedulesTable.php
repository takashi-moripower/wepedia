<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Date;
use Search\Manager;

/**
 * Schedules Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Schedule get($primaryKey, $options = [])
 * @method \App\Model\Entity\Schedule newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Schedule[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Schedule|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Schedule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Schedule[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Schedule findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchedulesTable extends Table {

	use \App\Model\Table\Traits\DateAroundTrait;

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('schedules');
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
				->allowEmpty('work');

		$validator
				->allowEmpty('target');

		$validator
				->allowEmpty('plan01');

		$validator
				->allowEmpty('plan02');

		$validator
				->allowEmpty('plan03');

		$validator
				->allowEmpty('plan04');

		$validator
				->allowEmpty('plan05');

		$validator
				->allowEmpty('plan06');

		$validator
				->allowEmpty('plan07');

		$validator
				->allowEmpty('plan08');

		$validator
				->allowEmpty('plan09');

		$validator
				->allowEmpty('plan10');

		$validator
				->allowEmpty('plan11');

		$validator
				->allowEmpty('plan12');

		$validator
				->allowEmpty('plan13');

		$validator
				->allowEmpty('plan14');

		$validator
				->integer('boss_check_before')
				->allowEmpty('boss_check_before');

		$validator
				->integer('boss_check_after')
				->allowEmpty('boss_check_after');

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

	public function getByDay($user_id, $date) {

		for ($i = 0; $i < 14; $i++) {
			$dateI = new Date($date);
			$dateI->subDay($i - 1);
			$key = sprintf('plan%02d', $i);

			$entity = $this->find()
					->where(['user_id' => $user_id, 'date' => $dateI])
					->first();

			if ($entity) {
				return $entity->{$key};
			}
		}
		return NULL;
	}

	/**
	 * 検索コンポーネント用
	 * @return Manager
	 */
	public function searchConfiguration() {
		$search = new Manager($this);
		$search
				->finder('user_id', [ 'finder' => 'UsersJson'])
				->value('date', [ 'field' => 'date'])
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

}
