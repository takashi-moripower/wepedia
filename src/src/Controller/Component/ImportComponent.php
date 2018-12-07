<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use App\Defines\Defines;
use Cake\Event\Event;
/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class ImportComponent extends Component {

	public $options_default = [
		'code' => 'sjis-win'
	];
	public $result = [
		'success' => 0,
		'failed' => 0,
		'pass' => 0,
		'failed_lines' => []
	];
	public $columns;
	public $line_count = 0;

	public function initialize(array $config = []) {
		parent::initialize($config);
		$this->_controller = $this->_registry->getController();
		$this->_table = $this->_controller->{ $this->_controller->name };
	}
	
	public function import( $columns = NULL ){
		
		if( $this->_controller->request->is('post')){
			$filename = $this->_controller->request->data['filename']['tmp_name'];
			$code = $this->_controller->request->data['character-code'];
			
			$result = $this->_import( $filename , ['code'=>$code , 'columns'=> $columns ] );
			
			$this->_controller->set('result',$result);
			$this->_controller->render('/Common/import_result');
			
			return;
		}
		
		$this->_controller->render('/Common/import');
	}

	public function _import($filename, $options) {
		$options += $this->options_default;
		
		//	columnsオプションがない場合　テーブルの全列を順に読み込む
		if (isset($options['columns'])) {
			$this->columns = $options['columns'];
		} else {
			$this->columns = $this->_table->schema()->columns();
		}

		$file = $this->_getFile($filename, $options['code']);


		while (true) {
			$line = fgetcsv($file, 1000, ',');
			if ($line == false) {
				break;
			}
			$this->_read($line);
		}

		fclose($file);
		
		return $this->result;
	}

	protected function _getFile($filename, $code) {
		$data_org = file_get_contents($filename);
		$data_utf = mb_convert_encoding($data_org, 'UTF-8', $code);
		$temp = tmpfile();

		fwrite($temp, $data_utf);
		rewind($temp);
		
		return $temp;
	}

	protected function _read($line) {
		$this->line_count ++;

		if (strncmp($line[0], '#', 1) == 0) { //	#で始まる行はパス
			$this->result['pass'] ++;
			return;
		}

		$data = [];
		foreach ($this->columns as $i => $label) {
			if (isset($line[$i]) && $line[$i] != '') {
				$data[$label] = $line[$i];
			}
		}

		if (isset($data['id'])) {
			$entity = $this->_table->get($data['id']);
		} else {
			$entity = NULL;
		}

		if (empty($entity)) {
			$entity = $this->_table->newEntity();
		}

		$this->_table->patchEntity($entity, $data);

		$event = new Event('Model.Import.beforeImport', $entity );
		$result = $this->_table->eventManager()->dispatch( $event );

		if ($this->_table->save($entity)) {
			$this->result['success'] ++;
		} else {
			$this->result['failed'] ++;

			$this->result['failed_lines'][$this->line_count] = $line;
		}
	}

}
