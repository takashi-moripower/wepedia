<?php

namespace App\Model\Table;

use App\Model\Table\Traits\FlagsTrait;
use App\Model\Table\Traits\ImportCSVTrait;
use App\Model\Table\Traits\LoginUserTrait;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Search\Manager;
use Cake\Network\Session;
use App\Defines\Defines;

/**
 * Sales Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Clients
 */
class SalesTable extends Table {

	use ImportCSVTrait,
	 FlagsTrait,
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

		$this->table('sales');
		$this->displayField('title');
		$this->primaryKey('id');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id'
		]);

		$this->belongsTo('Agents', [
			'className' => 'Users',
			'foreignKey' => 'agent_id',
			'bindingKey' => 'id'
		]);

		$this->belongsTo('Roots', [
			'className' => 'Sales',
			'foreignKey' => 'root_id',
			'bindingKey' => 'id'
		]);

		$this->hasMany('Demands');

		$this->hasOne('UnreadSales', [
			'foreignKey' => 'sale_id',
			'condition' => ['user_id' => $this->_getLoginUserId()],
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
			->allowEmpty('id', 'create')
			->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

		$validator
			->allowEmpty('*');

		$validator
			->allowEmpty('charge_person');

		$validator
			->allowEmpty('title');

		$validator
			->allowEmpty('report');

		$validator
			->allowEmpty('result');

		$validator
			->allowEmpty('treatment');

		$validator
			->allowEmpty('state');

		$validator
			->allowEmpty('next_date');

		$validator
			->add('boss_check', 'valid', ['rule' => 'numeric'])
			->allowEmpty('boss_check');

		$validator
			->add('published', 'valid', ['rule' => 'numeric'])
			->allowEmpty('published');

		$validator
			->add('deleted', 'valid', ['rule' => 'numeric'])
			->allowEmpty('deleted');

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

	public function unreadAll($entity) {
		$table_users = TableRegistry::get('Users');
		$list_users = $table_users->find()
			->extract("id")
			->toArray();
		$table_SU = TableRegistry::get('SalesUsers');

		foreach ($list_users as $id_user) {
			$newSU = $table_SU->newEntity([
				'sale_id' => $entity->id,
				'user_id' => $id_user
			]);
			$table_SU->save($newSU);
		}
	}

	public function beforeSave($event, $entity, $options) {
		$t = $this->getTreatmentValue($entity);
		if ($t !== NULL) {
			$entity['treatment'] = $t;
		}
	}

	public function afterSave(\Cake\Event\Event $event, \Cake\Datasource\EntityInterface $entity, \ArrayObject $options) {
		if ($entity->isNew()) {
			$this->unreadAll($entity);
		}
	}

	public function getTreatmentValue($entity) {
		$config = Configure::read('sale');

		$value = "";
		$flag = false;

		foreach ($config['treatment'] as $key => $act) {

			$entity_key = 'treat-' . $key;

			if (isset($entity[$entity_key])) {
				if ($entity[$entity_key]) {
					$flag = true;
					if (!empty($value)) {
						$value .= ',';
					}
					$value .= $act;
				}
			}
		}

		if ($flag) {
			return $value;
		}
		return NULL;
	}

	public function Truncate() {
		if (!$this->connection()->query("TRUNCATE sales")) {
			return false;
		}
		if (!$this->connection()->query("TRUNCATE sales_users")) {
			return false;
		}
		return true;
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
			->where(['Sales.user_id IN' => $user_id]);
		return $query;
	}

	/**
	 * 検索コンポーネント用
	 * @return Manager
	 */
	public function searchConfiguration() {
		$search = new Manager($this);
		$search
			->finder('flags', ['finder' => 'Flags'])
			->finder('read', ['finder' => 'Read'])
			->finder('date', ['finder' => 'Date'])
			->finder('user_id', ['finder' => 'UsersJson'])
			->like('freeword', ['before' => true, 'after' => true, 'field' => [$this->aliasField('title'), $this->aliasField('report'), $this->aliasField('charge_person'), $this->aliasField('client_name'),]])
			->like('client_name', ['before' => true, 'after' => true, 'field' => [$this->aliasField('client_name')]])
			->value('boss_check', ['field' => 'boss_check'])
			->value('boss_check2', ['field' => 'boss_check2'])
			->value('result', ['field' => 'result'])
			->value('date_direct', ['field' => 'date'])
		;
		return $search;
	}

	/**
	 * ダイレクトメールの発送日をリストにして返す
	 * @param Query $query
	 * @param array $options
	 * @return type
	 */
	public function findDmDate(Query $query, array $options) {
		$query
			->where(['root_id IS' => NULL, 'project_do' => Defines::SALES_DO_DIRECTMAIL])
			->select(['label' => $query->func()->concat(['Sales.title' => 'literal', '  [', 'Sales.date' => 'literal', ' ]'])])
			->select('date')
			->group(['date'])
			->order(['date' => 'DESC']);
		return $query;
	}

	/**
	 * あるNodeが所属するスレッドの正規化
	 * @param type $entity
	 */
	public function setTree($entity) {
		$root_id = $entity->root_id;

		$children = $this->find()
			->where(['root_id' => $root_id])
			->order(['created' => 'DESC']);

		if ($children->count() == 0) {
			return;
		}

		$next = null;
		foreach ($children as $child) {
			$child = $this->patchEntity($child, ['child_id' => $next]);
			$this->save($child);
			$next = $child->id;
		}

		$root = $this->get($root_id);
		$root = $this->patchEntity($root, ['child_id' => $next]);
		$this->save($root);
	}

	/**
	 * 新規作成用entityを用意する
	 * @param type $root_id
	 * @return type
	 */
	public function getEntityToAdd($root_id = NULL) {
		$copy_key = ['title', 'user_id', 'client_name', 'charge_person', /*'project_do',*/ 'project_act'];

		if ($root_id) {
			$root = $this->get($root_id, ['contain' => 'Users']);
			$source = [
				'root_id' => $root_id,
				'flags' => 'normal',
				'project_do' => Defines::SALES_DO_REPORT,	//181115改修　返信は常に報告扱いとする
			];
			foreach ($copy_key as $key) {
				$source[$key] = $root[$key];
			}
		} else {
			$session = new Session();
			$loginuser_id = $session->read('Auth.User.id');
			$loginuser_name = $session->read('Auth.User.name');

			$source = [
				'title' => '新規報告',
				'user_id' => $loginuser_id,
				'user_name' => $loginuser_name,
				'flags' => 'normal',
				'root_id' => NULL
			];
		}

		return $this->newEntity($source);
	}

	/**
	 * あるスレッドに属する下書きデータ
	 * @param Query $query
	 * @param array $options
	 * @return type
	 */
	public function findDraft(Query $query, array $options) {
		$root_id = $options['root_id'];

		return $query->find('flags', ['flags' => 'unpublished'])
				->where(['root_id' => $root_id])
				->order(['date' => 'DESC', 'time' => 'DESC']);
	}

	/**
	 * あるスレッドに属する報告すべて　Rootを含む、日付の新しい順
	 * @param Query $query
	 * @param array $options
	 */
	public function findNodes(Query $query, array $options) {
		$root_id = $options['root_id'];

		$query
			->where(['deleted' => 0, 'published' => 1])
			->andWhere([
				'or' => [
					[$this->aliasField('root_id') => $root_id],
					[$this->aliasField('id') => $root_id],
				]
			])
			->order(['date' => 'DESC', 'time' => 'DESC']);

		return $query;
	}

	/**
	 * あるスレッドに属する最新の報告
	 * 最新の報告は child_id == NULL なので、それを基準に検索
	 * @param Query $query
	 * @param array $options
	 */
	public function findLatest(Query $query, array $options) {
		$root_id = $options['root_id'];
		$query->find('Nodes', ['root_id' => $root_id])
			->andWhere(['child_id is' => NULL]);
		return $query;
	}

	/**
	 * あるスレッドに属する最新以外の報告
	 * @param Query $query
	 * @param array $options
	 */
	public function findPrevious(Query $query, array $options) {
		$root_id = $options['root_id'];
		$query->find('Nodes', ['root_id' => $root_id])
			->andWhere(['child_id is not' => NULL]);
		return $query;
	}

	/**
	 * 名前付きデータ取得
	 * @param type $id
	 * @return type
	 */
	public function getWithName($id) {
		return $this->get($id, [
				'contain' => [
					'Users' => ['fields' => ['id', 'name']],
					'Agents' => ['fields' => ['id', 'name']]
				]
		]);
	}

	public function beforeImport($event) {
		$sale = $event->subject();

		if (!isset($sale->published)) {
			$sale->published = true;
			$sale->deleted = false;
		}
	}

	public function findFlags(Query $query, array $options) {
		if (empty($options['flags'])) {
			return $query;
		}

		switch ($options['flags']) {
			case 'unpublished':
				$query = $query->where(['deleted' => 0, 'published' => 0]);
				break;

			case 'deleted':
				$query = $query->where(['deleted' => 1]);
				break;

			case 'direct_mail':
				$query = $query->where(['deleted' => 0, 'published' => 1, 'project_do' => Defines::SALES_DO_DIRECTMAIL]);
				break;

			case 'normal':
			default:
				$query = $query->where(['deleted' => 0, 'published' => 1, 'project_do <>' => Defines::SALES_DO_DIRECTMAIL]);
				break;
		}

		return $query;
	}

}
