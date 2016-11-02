<?php

namespace App\Model\Table\Traits;

use Cake\I18n\Date;


trait DateAroundTrait {
	/*
	 * 該当日前後の日付を取得
	 */

	public function getDateAround($date = NULL, $user_id = NULL) {
		if (empty($date)) {
			$date = Date::now();
		}

		$previous = $this->find('list', ['valueField' => 'date']);
		$next = $this->find('list', ['valueField' => 'date']);

		if ($user_id) {
			$previous->where(['user_id' => $user_id]);
			$next->where(['user_id' => $user_id]);
		}

		$previous
				->where(['date >=' => $date])
				->order(['date' => 'DESC'])
				->group('date')
				->limit(12);

		$next
				->where(['date <' => $date])
				->order(['date' => 'ASC'])
				->group('date')
				->limit(12);


		$date = $previous->toArray() + $next->toArray();
		sort($date);
		return $date;
	}

}
