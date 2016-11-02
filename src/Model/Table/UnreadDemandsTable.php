<?php

namespace App\Model\Table;


use Cake\ORM\Table;


/**
 * UnreadDemands Model
 */
class UnreadDemandsTable extends Table {


	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('demands_users');
		$this->displayField('demand_id');
		$this->primaryKey('demand_id');
	}


}
