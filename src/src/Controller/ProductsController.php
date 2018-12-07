<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController {

	use Traits\importTrait2;

	use Traits\setDataTrait;
	public $paginate  = [
		'order'=>['category_order'=>'ASC' , 'product_order'=>'ASC']
	];
	
	public $columns = [
		'id',
		'name',
		'product_order',
		'category',
		'category_order',
		'user_name',
		'url'
	];

	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);

		if (!in_array($this->request->action, ['setData'])) {
			$this->viewBuilder()->layout('management');
		}
	}

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index() {
		if ($this->RequestHandler->isMobile()) {
			return $this->_mobileIndex();
		} else {
			return $this->_desktopIndex();
		}
	}

	protected function _desktopIndex() {
		$this->paginate += [
			'contain' => ['Users']
		];

		$products = $this->paginate($this->Products);

		$this->set(compact('products'));
		$this->set('_serialize', ['products']);

		$this->SetList->add('Users');
	}

	/**
	 * View method
	 *
	 * @param string|null $id Product id.
	 * @return void
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function view($id = null) {
		$product = $this->Products->get($id, [
			'contain' => []
		]);
		$this->set('product', $product);
		$this->set('_serialize', ['product']);
	}

	/**
	 * Add method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$product = $this->Products->newEntity();
		if ($this->request->is('post')) {
			$product = $this->Products->patchEntity($product, $this->request->data);
			if ($this->Products->save($product)) {
				$this->Flash->success(__('商品データが正常に保存されました'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('商品データの保存に失敗しました'));
			}
		}
		$this->set(compact('product'));
		$this->set('_serialize', ['product']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Product id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$product = $this->Products->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$product = $this->Products->patchEntity($product, $this->request->data);
			if ($this->Products->save($product)) {
				$this->Flash->success(__('商品データは正常に保存されました'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('商品データの保存に失敗しました'));
			}
		}
		$this->set(compact('product'));
		$this->set('_serialize', ['product']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Product id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$product = $this->Products->get($id);
		if ($this->Products->delete($product)) {
			$this->Flash->success(__('商品データは正常に削除されました'));
		} else {
			$this->Flash->error(__('商品データの削除に失敗しました'));
		}
		return $this->redirect(['action' => 'index']);
	}

	public function setProductOrder($category = NULL){
		if ($this->request->is('post') && !empty($this->request->data['product_order'])) {
			$orders = json_decode($this->request->data['product_order']);
			
			foreach ($orders as $order => $name) {
				$product = $this->Products->find()
						->where([ 'category' => $this->request->data['category'] , 'name'=>$name ])
						->first();
				if( $product ){
					$product->product_order = $order;
					$this->Products->save($product);
				}else{
					debug('not found');
				}
			}
		}
		
		if( $category == NULL ){
			$category = $this->Products->find()->first()->category;
		}
		
		$products = $this->Products->find()
				->where(['category'=>$category])
				->order(['product_order'=>'ASC']);
		
		$this->set(compact('category','products'));
	}
	
	public function setCategoryOrder() {
		if ($this->request->is('post') && !empty($this->request->data['category_order'])) {
			$orders = json_decode($this->request->data['category_order']);

			foreach ($orders as $order => $category) {
				$products = $this->Products->find()
						->where(['category' => $category]);
				foreach ($products as $product) {
					$product->category_order = $order;
					$this->Products->save($product);
				}
			}
		}

		$categories = $this->Products->find()
				->group(['category'])
				->order(['category_order' => 'ASC']);

		$this->set(compact('categories'));
	}
	
	public function export(){
		$query = $this->Products->find()
				->contain('Users');
		$data = $this->Products->getCSV($query, $this->columns );
		$this->_downloadCSVFile($data);
	}
}
