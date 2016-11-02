<?php

namespace App\Controller\Traits;

trait exportTrait {

	public function export() {
		$columns = $this->getExportColumns();

		if ($this->request->is('post')) {
			$data = $this->getExportData();

			return $this->writeCsv($data, $columns);
		}

		$this->set('columns', $columns);
		$this->render('../Common/export0');
	}

	protected function getExportData() {
		$table = $this->{ $this->name };
		$data = $table->find()->toArray();
		return $data;
	}
	
	protected function getExportColumns(){
		$table = $this->{ $this->name };
		$columns = $table->schema()->columns();
		return $columns;
	}

	protected function writeCsv($data, $columns) {
		$this->set(compact('data', 'columns'));

		$this->response->type('csv');
		$this->response->download($this->name . date('-ymd-His') . '.csv');

		$this->viewBuilder()->layout(false);
		return $this->render('../Common/export1');
	}
}
