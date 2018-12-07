<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Traits\SoftDeleteTrait;
use App\Model\Entity\Traits\ReadCheckTrait;
use App\Model\Entity\Traits\NameToIdTrait;
use App\Model\Entity\Traits\FlagsTrait;

/**
 * Demand Entity.
 */
class Demand extends Entity {

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

	protected function _getAnswerName() {
		return isset($this->answer_user["name"]) ? $this->answer_user["name"] : "";
	}

	protected function _setAnswerName($user_name) {
		$Users = TableRegistry::get("Users");
		$user = $Users->find()
				->select(['id', 'name'])
				->where(['name' => $user_name])
				->first();

		if (empty($user)) {
			return FALSE;
		}

		$this->answer_id = $user['id'];

		return $user['name'];
	}

	protected function _getProduceName() {
		if (!$this->product) {
			return NULL;
		}

		if (!$this->product->user) {
			return NULL;
		}

		return $this->product->user->name;
	}

	protected function _getAnswerStateText() {
		
		if( !isset( $this->answer_state )){
			return NULL;
		}

		$list_state = Configure::read('demand')['answer_state'];
		return $list_state[$this->answer_state];
	}

	protected function _setAnswerStateText($text) {
		$list_state = Configure::read('demand')['answer_state'];

		$state = array_search($text, $list_state);
		if( $state === FALSE ){
			return NULL;
		}

		$this->answer_state = $state;
		return $list_state[$state];
	}
	
	protected function _getSale(){
		if( !empty( $this->sale )){
			return $this->sale;
		}
		
		$table_s = TableRegistry::get('Sales');
		$sale = $table_s->getWithName( $this->sale_id );
		$this->sale = $sale;
		return $sale;
	}
	
	protected function _setProductName( $value ){
		
		if( empty( $value )){
			return $value;
		}
		
		$table_products = TableRegistry::get('Products');
		$names = explode(',',$value);
		
		$products = $table_products->find('list',['valueField'=>'category'])
				->where(['name in' => $names])
				->group(['category'])
				->toArray();
				
		$this->product_category =  implode(',',$products);
		
		return $value;
	}
}
