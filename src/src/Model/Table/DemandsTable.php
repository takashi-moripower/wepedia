<?php

namespace App\Model\Table;

use App\Model\Table\Traits\ImportCSVTrait;
use App\Model\Table\Traits\FlagsTrait;
use App\Model\Table\Traits\LoginUserTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Search\Manager;
use Cake\Network\Session;

/**
 * Demands Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Clients
 * @property \Cake\ORM\Association\BelongsTo $Products
 */
class DemandsTable extends Table {

	use FlagsTrait,
	 LoginUserTrait;

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);
		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');

		$this->table('demands');
		$this->displayField('id');
		$this->primaryKey('id');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id'
		]);
		/*
		  $this->belongsTo('AnswerUsers', [
		  'className' => 'Users',
		  'foreignKey' => 'answer_id',
		  'bindingKey' => 'id',
		  'propertyName' => 'answer_user',
		  ]);

		  $this->belongsTo('Products', [
		  'className' => 'Products',
		  'foreignKey' => 'product_name',
		  'bindingKey' => 'name',
		  'propertyName' => 'product',
		  ]);
		 */
		$this->belongsTo('Sales', [
			'foreignKey' => 'sale_id',
		]);

		$this->hasOne('UnreadDemands', [
			'foreignKey' => 'demand_id',
			'condition' => ['user_id' => $this->_getLoginUserId()],
			'joinType' => 'LEFT',
		]);

		$this->eventManager()->on('Model.Import.beforeImport', [$this, 'beforeImport']);
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
				->allowEmpty('date');

		$validator
				->allowEmpty('type');

		$validator
				->allowEmpty('product_type');

		$validator
				->allowEmpty('product_name');

		$validator
				->allowEmpty('client_name');

		$validator
				->allowEmpty('demand');

		$validator
				->allowEmpty('deal');

		$validator
				->allowEmpty('answer');

		$validator
				->allowEmpty('answer_date');

		$validator
				->add('boss_check', 'valid', ['rule' => 'numeric'])
				->allowEmpty('boss_check');

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

	public function findCase(Query $query, array $options) {
		if ($options['case'] == 'demand') {
			$query = $query->where(['answer_state IS' => 0]);
		}

		if ($options['case'] == 'answer') {
			$query = $query->where(['answer_state IS NOT' => 0]);
		}

		return $query;
	}

	public function Truncate() {
		if (!$this->connection()->query("TRUNCATE demands")) {
			return false;
		}
		if (!$this->connection()->query("TRUNCATE demands_users")) {
			return false;
		}
		return true;
	}

	public function unreadAll($entity) {
		$table_users = TableRegistry::get('Users');
		$list_users = $table_users->find()
				->extract("id")
				->toArray();
		$table_DU = TableRegistry::get('DemandsUsers');

		foreach ($list_users as $id_user) {
			$newDU = $table_DU->newEntity([
				'demand_id' => $entity->id,
				'user_id' => $id_user
			]);
			$table_DU->save($newDU);
		}
	}

	public function afterSave($event, $entity, $options) {
		if ($entity->isNew()) {
			$this->unreadAll($entity);
		}
		return;
	}

	public function findUsersJson(Query $query, array $options) {
		$user_id = json_decode($options['user_id']);

		if (empty($user_id)) {
			return $query;
		}

		$produce = $query->cleanCopy()
				->find('Produce', ['user_id' => $user_id])
				->select('id');

		$query
				->where(['or' => ['Demands.id IN' => $produce, 'Demands.user_id IN' => $user_id]]);
		
		return $query;
	}

	/**
	 * usre_idの配列を受け取り　開発担当した商品に関する要望を返す
	 * @param Query $query
	 * @param array $options
	 * @return Query
	 */
	public function findProduce(Query $query, array $options) {
		$user_id = $options['user_id'];
		$table_products = TableRegistry::get('Products');
		$products = $table_products->find()
				->where(['Products.user_id in' => $user_id])
				->select('name');

		if ($products->isEmpty()) {
			return $query->where('false');
		}

		$n = [];
		foreach ($products as $product) {
			$n[] = ['product_name like'=> "%{$product->name}%"];
		}
		
		$query->where([ 'or'=> $n] );

		return $query;
	}

	public function searchConfiguration() {
		$search = new Manager($this);
		$search
				->finder('flags', ['finder' => 'Flags'])
				->finder('read', ['finder' => 'Read'])
				->finder('date', ['finder' => 'Date'])
				->finder('user_id', ['finder' => 'UsersJson'])
				->like('freeword', ['before' => true, 'after' => true, 'field' => [$this->aliasField('client_name'), $this->aliasField('product_category'), $this->aliasField('product_name'), $this->aliasField('demand'),]])
				->like('client_name', ['before' => true, 'after' => true, 'field' => [$this->aliasField('client_name')]])
				->like('product_category', ['before' => true, 'after' => true, 'field' => [$this->aliasField('product_category')]])
				->value('boss_check', ['field' => 'boss_check'])
				->value('boss_check2', ['field' => 'boss_check2'])
				->value('result', ['field' => 'result'])
				->value('type', ['field' => 'type'])
		;
		return $search;
	}

	/**
	 * 
	 */
	public function getEntityToAdd($root_id) {

		$table_s = TableRegistry::get('Sales');
		$root = $table_s->get($root_id);

		$latest = $root->latest;

		$demand = $this->newEntity([
			'user_id' => $this->_getLoginUserId(),
			'flags' => 'normal',
			'client_name' => $root->client_name,
			'sale_id' => $root->id,
			'date' => $latest->date,
			'time' => $latest->time,
		]);

		return $demand;
	}

	public function beforeImport($event) {
		$demand = $event->subject();

		if (!isset($demand->published)) {
			$demand->published = true;
			$demand->deleted = false;
		}
	}

}
