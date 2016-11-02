<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Controller\Traits\deleteAllTrait;
use App\Controller\Traits\importTrait;
use App\Controller\Traits\exportTrait;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class ClientsController extends AppController {

	use deleteAllTrait,
	 importTrait,
	 exportTrait;

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('clients', $this->paginate($this->Clients));
		$this->set('_serialize', ['clients']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Client id.
	 * @return void
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function view($id = null) {
		$client = $this->Clients->get($id, [
			'contain' => []
		]);
		$this->set('client', $client);
		$this->set('_serialize', ['client']);
	}

	/**
	 * Add method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$client = $this->Clients->newEntity();
		if ($this->request->is('post')) {
			$client = $this->Clients->patchEntity($client, $this->request->data);
			if ($this->Clients->save($client)) {
				$this->Flash->success(__('顧客データは正常に保存されました'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('顧客データの保存に失敗しました'));
			}
		}
		$this->set(compact('client'));
		$this->set('_serialize', ['client']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Client id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$client = $this->Clients->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$client = $this->Clients->patchEntity($client, $this->request->data);
			if ($this->Clients->save($client)) {
				$this->Flash->success(__('顧客データは正常に保存されました'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('顧客データの保存に失敗しました'));
			}
		}
		$this->set(compact('client'));
		$this->set('_serialize', ['client']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Client id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$client = $this->Clients->get($id);
		if ($this->Clients->delete($client)) {
			$this->Flash->success(__('顧客データは正常に削除されました'));
		} else {
			$this->Flash->error(__('顧客データの削除に失敗しました'));
		}
		return $this->redirect(['action' => 'index']);
	}

	public function debug() {
		$this->set('debug', $this->Clients->alias());
		$this->render('../Debug/debug');
	}

}
