<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Controller\Traits\setDataTrait;

/**
 * Informations Controller
 *
 * @property \App\Model\Table\InformationsTable $Informations
 */
class InformationsController extends AppController {

	use setDataTrait;
	
	public function initialize($config = []) {
		parent::initialize($config);
		$this->viewBuilder()->layout('management');
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['Users'],
			'order'=>['modified'=>'DESC']
		];
		$informations = $this->paginate($this->Informations);

		$this->SetList->add('Users');
		$this->set(compact('informations'));
		$this->set('_serialize', ['informations']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Information id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null) {
		$information = $this->Informations->get($id, [
			'contain' => ['Users']
		]);

		$this->set('information', $information);
		$this->set('_serialize', ['information']);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$information = $this->Informations->newEntity();
		if ($this->request->is('post')) {
			$information = $this->Informations->patchEntity($information, $this->request->data);
			if ($this->Informations->save($information)) {
				$this->Flash->success(__('The information has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The information could not be saved. Please, try again.'));
			}
		}
		$users = $this->Informations->Users->find('list', ['limit' => 200]);
		$this->set(compact('information', 'users'));
		$this->set('_serialize', ['information']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Information id.
	 * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$information = $this->Informations->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$information = $this->Informations->patchEntity($information, $this->request->data);
			if ($this->Informations->save($information)) {
				$this->Flash->success(__('The information has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The information could not be saved. Please, try again.'));
			}
		}
		$users = $this->Informations->Users->find('list', ['limit' => 200]);
		$this->set(compact('information', 'users'));
		$this->set('_serialize', ['information']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Information id.
	 * @return \Cake\Network\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null , $home = false ) {
//		$this->request->allowMethod(['post', 'delete']);
		$information = $this->Informations->get($id);
		if ($this->Informations->delete($information)) {
			$this->Flash->success(__('お知らせ情報が削除されました'));
		} else {
			$this->Flash->error(__('お知らせ情報の削除に失敗'));
		}

		if( $home ){
			return $this->redirect(['controller'=>'home','action' => 'index']);
		}else{
			return $this->redirect(['action' => 'index']);
		}
	}
}
