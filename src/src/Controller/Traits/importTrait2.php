<?php

namespace App\Controller\Traits;

trait importTrait2 {
	
	protected function _import($columns){
		$table = $this->{ $this->name };

		if ($this->request->is('post')) {
			$filename = $this->request->data['filename']['tmp_name'];
			$character_code = $this->request->data['character-code'];

			$result = $table->importCSV($filename, $character_code, $columns);

			$this->set('result', $result);

			return $this->render('../Common/import_result');
		}

		$this->render('../Common/import');
	}

	public function import() {
		return $this->_import( $this->_getImportColumns() );
	}

	protected function _getImportColumns() {
		if( property_exists($this,'import_columns') ){
			return $this->import_columns;
		}

		if( property_exists($this,'columns') ){
			return $this->columns;
		}
		
		$table = $this->{ $this->name };
		$columns = $table->schema()->columns();
		return $columns;
	}

	public function deleteAll() {
		if ($this->request->is('post') && $this->request->data['code'] == $this->request->data['code2']) {
			$table = $this->{ $this->name };
			$table->Truncate();
			$this->Flash->success('すべてのデータが消去されました');
			return $this->redirect(['action' => 'index']);
		}
		$this->render('../Common/delete_all');
	}
	
	protected function _downloadCSVFile( $data ){
		return $this->_downloadFile( $data , $this->name . date('-ymd-His') . '.csv' , 'csv');
	}

	protected function _downloadFile( $data , $filename, $type ) {
		//Content-Typeを指定
		$this->response->type($type);
		//download()内ではheader("Content-Disposition: attachment; filename=hoge.csv")を行っている
		$this->response->download($filename);

		$this->autoRender = false;

		$this->response->body($data);
	}

}
