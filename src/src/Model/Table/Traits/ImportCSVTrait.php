<?php

namespace App\Model\Table\Traits;

trait ImportCSVTrait {

	protected function _getImportColumns() {
		$columns = $this->schema()->columns();
		return $columns;
	}

	protected function _getExportColumns() {
		return $this->_getImportColumns();
	}

	public function importCSV($filename, $code = 'sjis-win', $columns = NULL) {
		$result = [
			'success' => 0,
			'failed' => 0,
			'pass' => 0,
			'failed_lines' => []
		];

		//	日付の読み込みフォーマットを　年　月　日にする
		\Cake\I18n\Date::$wordFormat = 'yyyy-MM-dd';

		if (empty($columns)) {
			$columns = $this->_getImportColumns();
		}

		$data_org = file_get_contents($filename);
		$data_utf = mb_convert_encoding($data_org, 'UTF-8', $code);
		$temp = tmpfile();

		fwrite($temp, $data_utf);
		rewind($temp);

		$id_line = 0;

		while (( $line = fgetcsv($temp, 1000, ",")) !== false) {
			$id_line ++;
			if (strncmp($line[0], '#', 1) == 0) { //	#で始まる行はパス
				$result['pass'] ++;
				continue;
			}

			$i = 0;
			$data = [];
			foreach ($columns as $column) {
				if (isset($line[$i])) {
					if ($line[$i] != '') {
						$data[$column] = $line[$i];
					}
				}
				$i ++;
			}

			if (isset($data['id'])) {
				$entity = $this->findById($data['id'])->first();
			} else {
				$entity = NULL;
			}

			if (empty($entity)) {
				$entity = $this->newEntity();
			}

			$this->patchEntity($entity, $data);

			if (method_exists($entity, 'beforeImport')) {
				$entity->beforeImport();
			}
			if ($this->save($entity)) {
				$result['success'] ++;
			} else {
				$result['failed'] ++;

				$result['failed_lines'][$id_line] = $line;
			}
		}

		fclose($temp);

		return $result;
	}

	public function Truncate() {
		$alias = mb_strtolower($this->alias());
		return $this->connection()->query("TRUNCATE " . $alias);
	}

	public function getCSVHeader( $columns = NULL ) {
		if (empty($columns)) {
			$columns = $this->_getExportColumns();
		}
		
		$columns[0] = '#'.$columns[0];
		$fp = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'a');
		fputcsv($fp, $columns);
		rewind($fp);
		$csv = stream_get_contents($fp);
		fclose($fp);
		
		//CSVをエクセルで開くことを想定して文字コードをSJIS-win
		$csv_sjis = mb_convert_encoding($csv,'SJIS-win','utf8');
		
		return $csv_sjis;
	}
	
	public function getCSV($query, $columns = NULL , $options = [] ) {

		$options += [
			'header'=>true
		];

		if (empty($columns)) {
			$columns = $this->_getExportColumns();
		}
		

		$fp = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'a');

		foreach ($query as $entity) {
			$data = [];
			foreach ($columns as $key) {
				//csvはexcelで開くので　文字コードはsjisにする
				$value_utf = $entity->{$key};
				$value_sjis = mb_convert_encoding($value_utf,'SJIS-win','utf8');
				$data[$key] = $value_sjis;
			}
			fputcsv($fp, $data);
		}
		rewind($fp);

		//リソースを読み込み文字列を取得する
		$csv = stream_get_contents($fp);
		//ファイルクローズ
		fclose($fp);
		
		//ヘッダを付与
		if( $options['header'] ){
			$csv = $this->getCSVHeader($columns).$csv;
		}
		
		return $csv;
	}

}
