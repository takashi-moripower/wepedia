<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use App\Defines\Defines;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class DirectMailComponent extends Component {

	public $Users;
	public $Sales;
	protected $_user;
	protected $_controller;

	public function initialize(array $config = []) {
		parent::initialize($config);

		$this->Users = TableRegistry::get('Users');
		$this->Sales = TableRegistry::get('Sales');

		$this->_controller = $this->_registry->getController();
	}

	public function view($user_id, $date_string) {
		if (empty($user_id)) {
			$user_id = $this->_controller->getLoginUser()['id'];
		}

		$this->_user = $this->Users->get($user_id);

		if (empty($date_string)) {
			$first = $this->Sales
					->find('Flags', ['flags' => 'normal'])
					->where(['project_do' => Defines::SALES_DO_DIRECTMAIL])
					->where(['user_id' => $user_id])
					->order(['date' => 'DESC'])
					->select(['date'])
					->first();
			
			$date = empty($first) ? '' : $first->date();
		} else {
			$date = new Date($date_string);
		}

		$sales = $this->Sales->find('Flags', ['flags' => 'normal'])
				->contain(['Users' => ['fields' => ['name']]])
				->where([
			'user_id' => $user_id,
			'project_do' => Defines::SALES_DO_DIRECTMAIL,
			'date' => $date,
				])
		;

		$collection = new \App\Model\Entity\Collections\DirectmailCollection($sales);

		//	日付選択肢
		$dm_date = $this->Sales->find('DmDate')
				->where(['user_id' => $user_id])
				->toArray();

		$date_options = [];
		foreach ($dm_date as $d) {
			$date_options[$d->date->format('Y-m-d')] = $d->label;
		}

		$this->_controller->set('date_options', $date_options);

		$this->_controller->set('user', $this->_user);
		$this->_controller->set(compact('sales', 'date', 'collection'));
	}

	//DMの最終発送日
	protected function _getLatestSendDate() {
		$sale = $this->Sales->find('DmDate');

		return $sale->first();
	}

}
