<?php

namespace App\Controller\Traits;

trait importTrait {

    public function import() {
        $table = $this->{ $this->name };
        
		$columns = $this->getImportColumns();
		
        if ($this->request->is('post')) {
            $filename = $this->request->data['filename'];
			$character_code = $this->request->data['character-code'];

            $result = $table->importCSV($filename , $character_code , $columns );

			$this->set('result',$result);
			
			$this->render('../Common/import_result');
        }

        $this->set( 'columns' , $columns );
        $this->render('../Common/import');
    }
	
	protected function getImportColumns(){
		$table = $this->{ $this->name };
		$columns = $table->schema()->columns();
		return $columns;		
	}
}
