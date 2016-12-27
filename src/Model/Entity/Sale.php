<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\I18n\Time;
use App\Model\Entity\Traits\SoftDeleteTrait;
use App\Model\Entity\Traits\ReadCheckTrait;
use App\Model\Entity\Traits\NameToIdTrait;
use App\Model\Entity\Traits\FlagsTrait;
use App\Defines\Defines;
use App\Model\Entity\Collections\DirectmailCollection;
use App\Model\Entity\Collections\FollowsCollection;

/**
 * Sale Entity.
 */
class Sale extends Entity {

	use SoftDeleteTrait,
	 ReadCheckTrait,
	 NameToIdTrait,
	 FlagsTrait;

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

	protected function _setAgentName($value) {
		$Users = TableRegistry::get("Users");
		$user = $Users->find()
				->select(['id', 'name'])
				->where(['name' => $value])
				->first();

		if (empty($user)) {
			return FALSE;
		}

		$this->agent_id = $user['id'];
		return $user['name'];
	}

	protected function _getAgentName() {
		return isset($this->agent["name"]) ? $this->agent["name"] : NULL;
	}

	public function isEditor($user_id) {
		if ($user_id == $this->user_id || $user_id == $this->agent_id) {
			return true;
		}
		return false;
	}

	protected function _getRootId($value) {

		if ($value == NULL) {
			$this->root_id = $this->id;
			return $this->id;
		}
		return $value;
	}

	public function isRoot() {
		if ($this->isNew()) {
			return empty($this->root_id);
		}
		return ( $this->root_id == $this->id );
	}

	protected function _getRoot($value) {
		if (!empty($value)) {
			return $value;
		}

		if ($this->isRoot()) {
			return $this;
		}

		$root = TableRegistry::get('Sales')
				->get($this->root_id, [
			'contain' => [
				'Users' => ['fields' => ['id', 'name']],
				'Agents' => ['fields' => ['id', 'name']]
			]
		]);
		$this->root = $root;
		return $root;
	}

	/**
	 * 報告の下書きのID　存在しなければNULL
	 * @return type
	 */
	public function getDraftId() {
		$draft = TableRegistry::get('Sales')
				->find('draft', ['root_id' => $this->root_id])
				->order(['date' => 'DESC', 'time' => 'DESC'])
				->select('id')
				->first();

		return empty($draft) ? NULL : $draft->id;
	}

	protected function _getGood($value) {
		if ($this->isRoot()) {
			return $value;
		} else {
			return $this->root->good;
		}
	}

	protected function _setGood($value) {
		if (!$this->isRoot()) {
			$this->root->good = $value;
			$this->dirty('root', true);
		}
		return $value;
	}

	protected function _getCheer($value) {
		if ($this->isRoot()) {
			return $value;
		} else {
			return $this->root->cheer;
		}
	}

	protected function _setCheer($value) {
		if (!$this->isRoot()) {
			$this->root->cheer = $value;
			$this->dirty('root', true);
		}
		return $value;
	}

	/**
	 * 過去の報告
	 * @param type $value
	 * @return type
	 */
	protected function _getPrevious($value) {
		if (!empty($value)) {
			return $value;
		}

		$table_s = TableRegistry::get('Sales');

		$previous = $table_s
				->find('Previous', ['root_id' => $this->root_id])
				->contain([
					'Users' => ['fields' => ['id', 'name']],
					'Agents' => ['fields' => ['id', 'name']]
				])
				->toArray();
		$this->previous = $previous;
		return $previous;
	}

	/**
	 * 最新の報告
	 * @param type $value
	 */
	protected function _getLatest($value) {
		if (!empty($value)) {
			return $value;
		}

		$table_s = TableRegistry::get('Sales');

		$latest = $table_s
				->find('Latest', ['root_id' => $this->root_id])
				->contain([
					'Users' => ['fields' => ['id', 'name']],
					'Agents' => ['fields' => ['id', 'name']]
				])
				->first();

		$this->latest = $latest;
		return $latest;
	}

	/**
	 * 顧客の声
	 * @param type $value
	 * @return type
	 */
	protected function _getDemands($value) {
		if (!empty($value)) {
			return $value;
		}

		$table_d = TableRegistry::get('Demands');

		$demands = $table_d
				->find('Flags', ['flags' => 'normal'])
				->where(['sale_id' => $this->root_id])
				->toArray();

		$this->demands = $demands;
		return $demands;
	}

	/**
	 * 顧客の声の下書きのID　存在しなければNULL
	 * @return type
	 */
	public function getDemandDraftId() {
		$DD = TableRegistry::get('Demands')
				->find('Flags', ['flags' => 'unpublished'])
				->where(['sale_id' => $this->root_id])
				->order(['date' => 'DESC'])
				->select('id')
				->first();

		return empty($DD) ? NULL : $DD->id;
	}

	/**
	 * 構造化したコメントを取得
	 */
	protected function _getComments($value) {
		if (!empty($value)) {
			return $value;
		}

		$comments = TableRegistry::get('Comments')
				->getTree($this->root_id);
		$this->comments = $comments;

		return $comments;
	}

	protected function _getCountComments($value) {
		if (!empty($value)) {
			return $value;
		}

		if (!$this->isRoot()) {
			return $this->root->count_comments;
		}

		$count = TableRegistry::get('Comments')
				->find()
				->where(['sale_id' => $this->id])
				->count();

		$this->count_comments = $count;
		return $count;
	}

	/**
	 * フォロー状況の取得
	 * @param type $value
	 * @return SALES_FOLLOW_NO_FOLLOW / SALES_FOLLOW_FOLLOWING / SALES_FOLLOW_FINISHED
	 */
	protected function _getFollow($value) {
		if (!empty($value)) {
			return $value;
		}

		if (!$this->isRoot()) {
			return $this->root->follow;
		}

		if ($this->child_id == NULL) {
			$follow = Defines::SALES_FOLLOW_NO_FOLLOW;
		} else if (!empty($this->latest) && $this->latest->result == Defines::SALES_RESULT_FOLLOWING) {
			$follow = Defines::SALES_FOLLOW_FOLLOWING;
		} else {
			$follow = Defines::SALES_FOLLOW_FINISHED;
		}

		$this->follow = $follow;

		return $follow;
	}

	/**
	 * 自身が集合データの時のみ利用可能、統計を返す
	 * @param type $value
	 * @return FollowsCollection
	 */
	protected function _getFollowsCollection($value) {
		if (!empty($value)) {
			return $value;
		}

		if (!$this->isRoot()) {
			return $this->root->follows_collection;
		}

		$collection = new FollowsCollection($this);
		$this->follows_collection = $collection;

		return $collection;
	}
}
