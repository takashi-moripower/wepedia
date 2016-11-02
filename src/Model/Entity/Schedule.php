<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Date;

/**
 * Schedule Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\Time $date
 * @property string $work
 * @property string $target
 * @property string $plan01
 * @property string $plan02
 * @property string $plan03
 * @property string $plan04
 * @property string $plan05
 * @property string $plan06
 * @property string $plan07
 * @property string $plan08
 * @property string $plan09
 * @property string $plan10
 * @property string $plan11
 * @property string $plan12
 * @property string $plan13
 * @property string $plan14
 * @property int $boss_check_before
 * @property int $boss_check_after
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Schedule extends Entity {

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'*' => true,
		'id' => false
	];

	protected function _getSale01($value) {
		
		return $this->_getSale(1);
	}

	protected function _getSale02($value) {
		return $this->_getSale(2);
	}

	protected function _getSale03($value) {
		return $this->_getSale(3);
	}

	protected function _getSale04($value) {
		return $this->_getSale(4);
	}

	protected function _getSale05($value) {
		return $this->_getSale(5);
	}

	protected function _getSale06($value) {
		return $this->_getSale(6);
	}

	protected function _getSale07($value) {
		return $this->_getSale(7);
	}

	protected function _getSale08($value) {
		return $this->_getSale(8);
	}

	protected function _getSale09($value) {
		return $this->_getSale(9);
	}

	protected function _getSale10($value) {
		return $this->_getSale(10);
	}

	protected function _getSale11($value) {
		return $this->_getSale(11);
	}

	protected function _getSale12($value) {
		return $this->_getSale(12);
	}

	protected function _getSale13($value) {
		return $this->_getSale(13);
	}

	protected function _getSale14($value) {
		return $this->_getSale(14);
	}

	protected function _getSale($i) {
		$table_s = \Cake\ORM\TableRegistry::get('Sales');
		$date = $this->date->addDay($i - 1);
		$sale = $table_s->find()
				->where(['next_date' => $date, 'user_id' => $this->user_id])
				->first();
		return $sale;
	}

}
