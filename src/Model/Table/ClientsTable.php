<?php

namespace App\Model\Table;

use App\Model\Entity\Client;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\Traits\ImportCSVTrait;

/**
 * Clients Model
 *
 * @property \Cake\ORM\Association\HasMany $Demands
 * @property \Cake\ORM\Association\HasMany $Sales
 */
class ClientsTable extends Table {

	use ImportCSVTrait;

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('clients');
		$this->displayField('name');
		$this->primaryKey('id');
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator) {
		$validator
				->add('id', 'valid', ['rule' => 'numeric'])
				->allowEmpty('id', 'create');

		$validator
				->allowEmpty('name');

		$validator
				->allowEmpty('address');

		$validator
				->allowEmpty('tel');

		$validator
				->add('email', 'valid', ['rule' => 'email'])
				->remove('email' ,'unique')
				->allowEmpty('email');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	/*
	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->isUnique(['email']));
		return $rules;
	}
*/
}
