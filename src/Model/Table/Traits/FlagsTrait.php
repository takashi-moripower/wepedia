<?php

namespace App\Model\Table\Traits;

use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

trait FlagsTrait {

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

			case 'normal':
			default:
				$query = $query->where(['deleted' => 0, 'published' => 1]);
				break;
		}

		return $query;
	}

	protected function _getUnreadList() {
		$table_link_name = $this->_alias . 'Users';

		$table_link = TableRegistry::get($table_link_name);
		
		return array_values($table_link->find('list')->where(['user_id' => $this->_getLoginUserId()])->toArray());
	}

	public function findRead(Query $query, array $options) {
		$unread_list = $this->_getUnreadList();
		$id = $this->_alias . '.id';
		if ($options['read']) {
			if (empty($unread_list)) {
				return $query;
			}
			$exp = $query->newExpr()->notIn($id, $unread_list);
		} else {
			$exp = $query->newExpr()->In($id, $unread_list);
		}

		return $query->where($exp);
	}
	
	public function findDate(Query $query, array $options) {

		$date = date("Y/m/d", strtotime($options['date']));
		$exp = $query->newExpr()->gt('date', $date);
		$query = $query->where($exp);
		
		return $query;
	}
}
