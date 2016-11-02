<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use RuntimeException;
use App\Model\Table\Traits\ImportCSVTrait;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Demands
 * @property \Cake\ORM\Association\HasMany $Sales
 * @property \Cake\ORM\Association\BelongsToMany $Demands
 * @property \Cake\ORM\Association\BelongsToMany $Sales
 */
class UsersTable extends Table {

	use ImportCSVTrait;

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('users');
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
				->requirePresence('name', 'create')
				->notEmpty('name');

		$validator
				->add('email', 'valid', ['rule' => 'email'])
				->requirePresence('email', 'create')
				->notEmpty('email');

		$validator
				->requirePresence('password', 'create')
//                ->notEmpty('password');
				->allowEmpty('password');

		$validator
				->allowEmpty('last_login');

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
		$rules->add($rules->isUnique(['email']));
		return $rules;
	}

	public function getUnreadSales($id_user) {
		$table_su = TableRegistry::get('SalesUsers');
		$query = $table_su->find()->where(['user_id' => $id_user]);

		$result = [];
		foreach ($query as $su) {
			$result[] = $su['sale_id'];
		}

		return $result;
	}

	public function getUnreadDemands($id_user) {
		$table_du = TableRegistry::get('DemandsUsers');
		$query = $table_du->find()->where(['user_id' => $id_user]);

		$result = [];
		foreach ($query as $du) {
			$result[] = $du['demand_id'];
		}

		return $result;
	}

	public function setUnreadSales($id_user, $id_sales, $flag = TRUE) {
		$this->belongsToMany('Sales', [
			'foreignKey' => 'user_id',
			'targetForeignKey' => 'sale_id',
			'joinTable' => 'sales_users'
		]);

		$table_sales = TableRegistry::get('Sales');
		$sales = $table_sales->find()
				->where(['id IN' => $id_sales])
				->toArray();

		$user = $this->get($id_user);

		if ($flag) {
			$this->Sales->link($user, $sales);
		} else {
			$this->Sales->unlink($user, $sales);
		}
	}

	public function setUnreadDemands($id_user, $id_demands, $flag = TRUE) {
		$this->belongsToMany('Demand', [
			'foreignKey' => 'user_id',
			'targetForeignKey' => 'demand_id',
			'joinTable' => 'demands_users'
		]);

		$table_sales = TableRegistry::get('Demands');
		$demands = $table_sales->find()
				->where(['id IN' => $id_demands])
				->toArray();

		$user = $this->get($id_user);

		if ($flag) {
			$this->Demands->link($user, $demands);
		} else {
			$this->Demands->unlink($user, $demands);
		}
	}

	public function beforeSave($event, $entity, $options) {
		if ($entity->face['error'] === UPLOAD_ERR_OK) {
			$entity->face = $this->_buildFace($entity->face);
		} else {
			unset($entity->face);
		}
	}

	protected function _buildFace($face) {
		$ret = file_get_contents($face['tmp_name']);
		if ($ret === false) {
			throw new RuntimeException('Can not get face image');
		}
		return $ret;
	}

	public function afterSave($event, $entity, $options) {
		if (!$entity->dirty('face')) {
			return;
		}

		$this->_copyFace($entity->id);
	}

	protected function _copyFace($user_id) {
		$user = $this->get($user_id);
		
		$filename = './upload/face/' . sprintf('%04d', $user->id);

		if (empty($user->face)) {
			unlink($filename);
		} else {
			$data = stream_get_contents($user->face);
			$fh = fopen($filename, 'wb'); // windowsならbが要る
			fwrite($fh, $data, strlen($data));
			fclose($fh);
		}
	}

}
