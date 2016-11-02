<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use App\Controller\Traits\deleteAllTrait;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController {

	use \App\Controller\Traits\importTrait2;

use \App\Controller\Traits\setDataTrait;

use deleteAllTrait {
		delete_all as trait_delete_all;
	}
	
	public $export_columns = [
		'id','name','section','position','tel','email'
	];
	public $import_columns = [
		'id','name','section','position','tel','email','password'
	];

	public function initialize() {
		parent::initialize();
		$this->loadComponent('AutoLogin');
	}

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('users', $this->paginate($this->Users));
		$this->set('_serialize', ['users']);
	}
/*
	public function beforeFilter(\Cake\Event\Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow(['add', 'edit','debug']);
	}
*/
	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);
		if (!in_array($this->request->action, ['setData', 'login', 'getFace','debug'])) {
			$this->viewBuilder()->layout('management');
		}
	}

	/**
	 * Add method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->data);

			if ($this->Users->save($user)) {
				$this->Flash->success(__('ユーザーデータは正常に保存されました'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('ユーザーデータの保存に失敗しました'));
			}
		}

		$this->set(compact('user'));
		$this->set('_serialize', ['user']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$user = $this->Users->get($id);
		if ($this->request->is(['patch', 'post', 'put'])) {

			$data = $this->request->data;

			if (empty($data['password'])) {
				unset($data['password']);
			}

			if (isset($data['password']) && $data['password'] !== $data['password2']) {
				$this->Flash->error(__('パスワードが違います'));
				return $this->redirect(['action' => 'index']);
			}

			$user = $this->Users->patchEntity($user, $data);

			if (empty($data['password'])) {
				unset($user->password);
			}

			if ($this->Users->save($user)) {
				$this->Flash->success(__('ユーザーデータは正常に保存されした'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('ユーザーデータの保存に失敗しました'));
			}
		}

		unset($user['password']);

		$this->set(compact('user'));
		$this->set('_serialize', ['user']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null) {

		$login_id = $this->Auth->user('id');

		if ($id == $login_id) {
			$this->Flash->error('ログイン中のユーザーは削除できません');
			$this->redirect(['action' => 'index']);
		}

		$user = $this->Users->get($id);

		if (!$this->request->is('delete')) {
			$this->set('user', $user);
			return;
		}

		/*
		 * 消去予定ユーザーの作成した営業報告データを
		 * 管理者名義に書き換える
		 */
		$table_sales = TableRegistry::get('Sales');
		$sales = $table_sales->find()
				->where(['user_id' => $id]);

		foreach ($sales as $sale) {
			$sale['user_id'] = $login_id;
			$table_sales->save($sale);
		}

		/*
		 * 消去予定ユーザーの作成した顧客の声データを
		 * 管理者名義に書き換える
		 */
		$table_demands = TableRegistry::get('Demands');
		$demands = $table_demands->find()
				->where(['user_id' => $id]);

		foreach ($demands as $demand) {
			$demand['user_id'] = $login_id;
			$table_demands->save($demand);
		}

		/*
		 * 消去予定ユーザーの回答した顧客の声データを
		 * 管理者名義に書き換える
		 */
		$demands2 = $table_demands->find()
				->where(['answer_id' => $id]);
		foreach ($demands2 as $demand) {
			$demand['answer_id'] = $login_id;
			$table_demands->save($demand);
		}

		/*
		 * 消去予定ユーザーの担当する製品情報データを
		 * 管理者名義に書き換える
		 */
		$table_products = TableRegistry::get('Products');
		$products = $table_products->find()
				->where(['user_id' => $id]);
		foreach ($products as $product) {
			$product['user_id'] = $login_id;
			$table_products->save($product);
		}

		$this->Users->delete($user);

		$this->Flash->success('ユーザーデータは正常に削除されました');
		$this->redirect(['action' => 'index']);
	}

	public function login() {
		//	ログイン中だったらログアウト
		if ($this->Auth->user()) {
			$this->Auth->logout();
		}

		//	自動ログイン判定
		$loginUser = $this->AutoLogin->identify();
		//	手動ログイン判定
		if (!$loginUser && $this->request->is('post')) {
			$loginUser = $this->Auth->identify();

			//	手動ログイン成功時、自動ログインにチェックが入っていた場合
			// 	自動ログイン用トークンを登録
			if ($loginUser && $this->request->data['auto_login']) {
				$this->AutoLogin->setToken($loginUser['id']);
			}

			//	ログイン失敗時処理
			if (!$loginUser) {
				$this->Flash->error(__('ログインに失敗しました'));
			}
		}

		//	ログイン成功時処理
		if ($loginUser) {
			$table_users = $this->Users;
			$user2 = $table_users->get($loginUser["id"]);
			$user2["last_login"] = date("Y-m-d G:i:s ");
			$table_users->save($user2);

			$this->Auth->setUser($loginUser);
			if( $this->RequestHandler->isMobile() ){
				return $this->redirect(['controller' => 'mobile', 'action' => 'index']);
			}else{
				return $this->redirect(['controller' => 'home', 'action' => 'index']);
			}
		}

		$this->viewBuilder()->layout('single');
	}

	public function logout() {
		$this->AutoLogin->clearToken();
		return $this->redirect($this->Auth->logout());
	}

	public function delete_all() {
		$table_sales = TableRegistry::get('Sales');
		$table_demands = TableRegistry::get('Demands');
		$table_products = TableRegistry::get('Products');

		$count_sales = $table_sales->find()->count();
		$count_demands = $table_demands->find()->count();
		$count_products = $table_products->find()->count();

		if ($count_sales > 0 || $count_demands > 0 || $count_products) {
			$this->set(compact('count_sales', 'count_demands'));
			return;
		}
		return $this->trait_delete_all();
	}

	public function getFace($id = NULL) {
		if ($id != NULL) {
			$user = $this->Users->get($id);
		} else {
			$user = NULL;
		}

		$this->autoRender = false;

		if (isset($user->face)) {
			$img = stream_get_contents($user->face);
		} else {
			$img = file_get_contents('img/noneface.png');
		}

		$this->loadComponent('ImageType');
		$type = $this->ImageType->getTypeFromByte($img);
		$this->response->type($type);

		$this->response->body($img);
	}

	public function copyFace() {
		$users = $this->Users->find()
				->all();

		foreach ($users as $user) {
			$this->_copyFace($user);
		}

		$this->redirect(['action' => 'index']);
	}

	protected function _copyFace($user) {
		$data = stream_get_contents($user->face);

		$filename = './upload/face/' . sprintf('%04d', $user->id);

		$fh = fopen($filename, 'wb'); // windowsならbが要る
		fwrite($fh, $data, strlen($data));
		fclose($fh);
	}

	public function export(){
		$query = $this->Users->find();
		$data = $this->Users->getCSV($query, $this->export_columns);
		$this->_downloadCSVFile( $data );
	}
}
