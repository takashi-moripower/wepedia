<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Comments Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Sales
 * @property \Cake\ORM\Association\BelongsTo $ParentComments
 * @property \Cake\ORM\Association\HasMany $ChildComments
 *
 * @method \App\Model\Entity\Comment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Comment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Comment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Comment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Comment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Comment findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommentsTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('comments');
		$this->displayField('id');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'INNER'
		]);
		$this->belongsTo('Sales', [
			'foreignKey' => 'sale_id',
			'joinType' => 'INNER'
		]);
		$this->belongsTo('ParentComments', [
			'className' => 'Comments',
			'foreignKey' => 'parent_id'
		]);
		$this->hasMany('ChildComments', [
			'className' => 'Comments',
			'foreignKey' => 'parent_id'
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
				->allowEmpty('text');

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
		$rules->add($rules->existsIn(['sale_id'], 'Sales'));
		$rules->add($rules->existsIn(['parent_id'], 'ParentComments'));
		return $rules;
	}

	
	/**
	 * 構造化したコメントを返す
	 * @param type $sale_id
	 * @return type
	 */
	public function getTree($sale_id) {
		$roots = $this->find()
				->where(['sale_id' => $sale_id, 'parent_id is' => NULL])
				->contain(['Users'=>['fields'=>['id','name']]]);
		$table = $this;
		$setChildren = function( &$item ) use( &$table, &$setChildren ) {
			$children = $this->find()
					->where(['sale_id' => $item->sale_id, 'parent_id' => $item->id])
					->contain(['Users'=>['fields'=>['id','name']]]);

			if (empty($children)) {
				$item->children = NULL;
			} else {
				$item->children = $children;
				foreach ($item->children as $child) {
					$setChildren( $child );
				}
			}
		};
		
		foreach( $roots as $item ){
			$setChildren($item);
		}
		
		return $roots;
	}

}
