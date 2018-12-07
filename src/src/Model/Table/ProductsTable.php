<?php

namespace App\Model\Table;

use App\Model\Entity\Product;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\Traits\ImportCSVTrait;

/**
 * Products Model
 *
 */
class ProductsTable extends Table {

	use ImportCSVTrait;

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('products');
		$this->displayField('name');
		$this->primaryKey('id');
		
		$this->belongsTo('Users', [
			'foreignKey' => 'user_id'
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
				->add('id', 'valid', ['rule' => 'numeric'])
				->allowEmpty('id', 'create');

		$validator
				->allowEmpty('name');

		$validator
				->allowEmpty('category');

		$validator
				->allowEmpty('karte');

		return $validator;
	}

}
