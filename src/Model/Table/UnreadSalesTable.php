<?php

namespace App\Model\Table;


use Cake\ORM\Table;


/**
 * UnreadDemands Model
 */
class UnreadSalesTable extends Table {


	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('sales_users');
		$this->displayField('sale_id');
		$this->primaryKey('sale_id');
	}
}
