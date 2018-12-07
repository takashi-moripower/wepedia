<?php

namespace App\Controller;

use App\Controller\AppController;
use \IntlDateFormatter;

/**
 * Results Controller
 *
 * @property \App\Model\Table\ResultsTable $Results
 */
class ResultsController extends AppController {

	use \App\Controller\Traits\importTrait2;
	use \App\Controller\Traits\setDataTrait;
	
	public $columns = [
		'id',
		'user_name',
		'date',
		'type',
		'target_new',
		'target_exist',
		'previous_new',
		'previous_exist',
		'forecast_new',
		'forecast_exist',
		'result_new',
		'result_exist'
	];

	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);
		
		if(!in_array( $this->request->action ,['setData'] )){
			$this->viewBuilder()->layout('management');
		}
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['Users']
		];
		$results = $this->paginate($this->Results);
		
		$this->set(compact('results'));
		$this->set('_serialize', ['results']);
		
		$this->SetList->add('Users');
	}
	
	public function export(){
		
		$form = new \App\Form\ResultExportForm();
		
		$form->start = $this->Results->find()
				->order(['date'=>'ASC'])
				->first()
				->date;
		$form->end = $this->Results->find()
				->order(['date'=>'DESC'])
				->first()
				->date;
		

		if( $this->request->is('post')){
			
			$form->execute( $this->request->data );

			$query = $this->Results->find()
					->contain('Users')
					->where(['date >=' => $form->start , 'date <=' => $form->end ]);

			$data = $this->Results->getCSV( $query , $this->columns );
			return $this->_downloadCSVFile($data);
		}
		
		
		$this->set('form',$form);
	}
	


}
