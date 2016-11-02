<?php

namespace App\Controller\Traits;

use Cake\Core\Configure;

trait setDataTrait {

	public function setData() {
		if (!$this->request->is('ajax')) {
			throw new \Cake\Network\Exception\BadRequestException;
		}

		$table = $this->{$this->name};
		$data = $this->request->data;

		$option = \Cake\Core\Configure::read('set_data_option.' . $this->name);

		$entity = $table->get($data['id'], $option);
		$table->patchEntity($entity, $data);
		
		$table->save($entity);

		$this->set('item', $entity);

		$this->viewBuilder()->layout(false);
		$this->render('../Element/' . $this->name . '/row');
	}
}
