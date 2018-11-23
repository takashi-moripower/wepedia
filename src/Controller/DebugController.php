<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

class DebugController extends AppController {

	public function index() {
		$Sales = $this->loadModel('Sales');
		$sale = $Sales->get(3960);
		$Sales->setTree($sale);

		$root_id = $sale->root_id;

		$children = $Sales->find()
			->where(['root_id' => $root_id])
			->order(['id' => 'DESC']);

		$next = null;
		$result = [];
		foreach ($children as $child) {
			$child = $Sales->patchEntity($child, ['child_id' => $next]);
			$result[] = ($Sales->save($child) ? '1' : '0') . ':' . $next;
			$next = $child->id;
		}

		$this->set('data', $result);

		$this->render('/Common/debug');
	}

}
