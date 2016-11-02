<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

/**
 * User Entity.
 */
class User extends Entity {

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 * Note that '*' is set to true, which allows all unspecified fields to be
	 * mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];

	public function _setPassword($password) {
		$hasher = new DefaultPasswordHasher();
		$_password = $hasher->hash($password);
		return $_password;
	}

	public function hasPlans($date_start = NULL, $date_end = NULL) {
		if (empty($date_start)) {
			$date_start = Date::now();
		} else {
			$date_start = Date($date_start);
		}

		if (empty($date_end)) {
			$date_end = Date::now()->addDay(3);
		} else {
			$date_end = Date($date_end);
		}

		$table_s = TableRegistry::get('Sales');

		return $table_s->exists([
					'published' => true,
					'deleted' => false,
					'user_id' => $this->id,
					'child_id is' => NULL,
					'result' => Defines::SALES_RESULT_FOLLOWING,
					'next_date <' => $date_end,
					'next_date >=' => $date_start
		]);
	}

	public function getPlans($date) {
		$table_s = TableRegistry::get('Sales');
		return $table_s->find('flags', ['flags' => 'normal'])
						->where([
							'user_id' => $this->id,
							'child_id is' => NULL,
							'result' => Defines::SALES_RESULT_FOLLOWING,
							'next_date' => $date,
		]);
	}

}
