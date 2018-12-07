<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class NavMakerComponent extends Component {

	protected $_order;
	protected $_query;
	protected $_entity;

	public function initialize(array $config = []) {
		parent::initialize($config);
	}

	/**
	 * 該当案件の前後の案件をセット
	 * @param type $entity
	 * @param type $type
	 * @return type
	 */
	public function setId($entity, $type) {
		$controller = $this->_registry->getController();
		$this->_entity = $entity;

		$controller->set('nav_type', $type);

		if (empty($type)) {
			return;
		}

		switch ($type) {
			case 'i':
				$this->_getOrderIndex();
				break;
			
			case 'm':
				$this->_getOrderMypage();
				break;
			
			case 'f':
				$this->_getOrderFollowing();
				break;
			
			case 's':
				$this->_getOrderSchedule();
				break;

			case 'h':
				$this->_getOrderHome();
				break;

			case 'd':
				$this->_getOrderDirectMail();
				break;

			default:
				return;
		}

		$controller->set('next_id', $this->_getNextId());
		$controller->set('prev_id', $this->_getPrevId());
	}

	/**
	 * 次へ　のターゲットを設定
	 * @return type
	 */
	protected function _getNextId() {
		$query = $this->_query->cleanCopy();

		$entity = $query->where($this->_getWhereOption(true))
				->order(self::getReverseOrder($this->_order))
				->select('id')
				->first();

		if ($entity) {
			return $entity->id;
		}
		return NULL;
	}

	
	/**
	 * 前へ　のターゲットを設定
	 * @return type
	 */
	protected function _getPrevId() {
		$query = $this->_query->cleanCopy();

		$entity = $query->where($this->_getWhereOption())
				->order($this->_order)
				->select('id')
				->first();

		if ($entity) {
			return $entity->id;
		}
		return NULL;
	}

	/**
	 * asc/desc を不等号に変換
	 * @param type $dir
	 * @return string
	 */
	static function dir2sign($dir) {
		if (strtolower($dir) == 'asc') {
			return ' >';
		} else {
			return ' <';
		}
	}

	/**
	 * order指定配列の　asc/descを裏返す
	 * @param type $order
	 * @return type
	 */
	static function getReverseOrder($order) {
		$result = [];
		foreach ($order as $key => $dir) {
			$result[$key] = (strtolower($dir) == 'asc') ? 'desc' : 'asc';
		}
		return $result;
	}

	/**
	 * 特定の並び順で　指定案件より前　を示すwhere条件を返す
	 * @param type $reverse
	 * @return type
	 */
	protected function _getWhereOption($reverse = false) {
		$reslut = [];

		$order = $reverse ? self::getReverseOrder($this->_order) : $this->_order;

		for ($d = 0; $d < count($order); $d++) {
			$result_sub = [];
			foreach ($order as $sort => $dir) {
				if (count($result_sub) > $d) {
					break;
				}
				if (count($result_sub) == $d) {
					$result_sub += [
						$sort . self::dir2sign($dir) => $this->_getValue($sort)
					];
				} else {
					$result_sub += [
						$sort => $this->_getValue($sort)
					];
				}
			}

			$result[] = $result_sub;
		}

		return ['or' => $result];
	}

	/**
	 * 案件のパラメータを取得
	 * @param type $key
	 * @return type
	 */
	protected function _getValue($key) {
		$name = $this->_registry->getController()->name;
		return Hash::get($this->_entity, $key, Hash::get([$name => $this->_entity], $key));
	}

	/**
	 * mypage-directmailから詳細に移動した時のナビゲーション条件を設定
	 */
	protected function _getOrderDirectMail() {
		$user_id = $this->_entity->user_id;
		
		$this->_query = TableRegistry::get('Sales')
				->find('flags', ['flags' => 'normal'])
				->where(['root_id is' => NULL])
				->where(['user_id' => $user_id])
				->where(['project_do' => Defines::SALES_DO_DIRECTMAIL ]);
		
		$this->_order = [
			'date' => 'desc',
			'time' => 'desc',
			'id' => 'asc'
		];
	}
	
	/**
	 * mypage-followingから詳細に移動した時のナビゲーション条件を設定
	 */
	protected function _getOrderFollowing() {
		$user_id = $this->_entity->user_id;
		
		$this->_query = TableRegistry::get('Sales')
				->find('flags', ['flags' => 'normal'])
				->where(['user_id' => $user_id])
				->where(['child_id is' => NULL])
				->where(['result' => Defines::SALES_RESULT_FOLLOWING]);
		
		$this->_order = [
			'date' => 'desc',
			'time' => 'desc',
			'id' => 'asc'
		];
	}
	
	/**
	 * mypage-scheduleから詳細に移動した時のナビゲーション条件を設定
	 */
	protected function _getOrderSchedule() {
		$user_id = $this->_entity->user_id;
		
		$this->_query = TableRegistry::get('Sales')
				->find('flags', ['flags' => 'normal'])
				->where(['user_id' => $user_id])
				->where(['child_id is' => NULL])
				->where(['next_date is not' => NULL]);
		
		$this->_order = [
			'next_date' => 'desc',
			'id' => 'asc'
		];
	}
	
	
	/**
	 * mypageから詳細に移動した時のナビゲーション条件を設定
	 */
	protected function _getOrderMypage() {
		$user_id = $this->_entity->user_id;
		
		$this->_query = TableRegistry::get('Sales')
				->find('flags', ['flags' => 'normal'])
				->where(['child_id is' => NULL])
				->where(['user_id' => $user_id]);
		
		$this->_order = [
			'date' => 'desc',
			'time' => 'desc',
			'id' => 'asc'
		];
	}
	
	/**
	 * homeから詳細に移動した時のナビゲーション条件を設定
	 */
	protected function _getOrderHome(){		
		$this->_query = TableRegistry::get('Sales')
				->find('flags', ['flags' => 'normal'])
				->find('read',['read'=>false])
				->where(['child_id is' => NULL]);
		
		$this->_order = [
			'date' => 'desc',
			'time' => 'desc',
			'id' => 'asc'
		];
	}

	/**
	 * indexから詳細に移動した時のナビゲーション条件を設定
	 */
	protected function _getOrderIndex() {
		$session = $this->request->session()->read('search.sales.index');

		$sort = Hash::get($session, 'sort', 'date');
		$direction = Hash::get($session, 'direction', 'desc');

		$session = Hash::remove($session, 'limit');
		$session = Hash::remove($session, 'page');
		$session = Hash::remove($session, 'sort');
		$session = Hash::remove($session, 'direction');

		if ($sort == 'date') {
			$order = [
				'date' => $direction,
				'time' => $direction,
			];
		} else {
			$order = [
				$sort => $direction,
			];
		}

		$order += ['id' => 'asc'];

		$this->_order = $order;

		$this->_query = TableRegistry::get('Sales')
				->find('search', ['search' => $session])
				->find('flags', ['flags' => 'normal'])
				->where(['child_id is' => NULL]);
	}

}
