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

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class ExportComponent extends Component {
	
	public $options_default = [
		'header'=>true
	];

	public function initialize(array $config = []) {
		parent::initialize($config);
		$this->_controller = $this->_registry->getController();
		$this->_table = $this->_controller->{ $this->_controller->name };
	}

	public function export( $query , $options = [] ){

		
		//	日付の出力フォーマットを　年　月　日にする
		\Cake\I18n\Date::setToStringFormat('yyyy-MM-dd');
		\Cake\I18n\Time::setToStringFormat('yyyy-MM-dd');
		
		
		$options += $this->options_default;
		
		//	columnsオプションがない場合　テーブルの全列を出力
		if( isset($options['columns'])){
			$columns = $options['columns'];
		}else{
			$columns = $this->_table->schema()->columns();
		}
		
		//	csv本体取得
		$data = $this->_getBody( $query , $columns );
		
		//	headerオプションがある場合　ヘッダー取得
		if( !empty( $options['header']) ){
			$data = $this->_getHeader( $columns ) . $data;
		}
		
		return $this->_downloadCSVFile( $data );
		
	}
	
	protected function _getHeader( $columns ){
		$columns[0] = '#'.$columns[0];
		$fp = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'a');
		fputcsv($fp, $columns);
		rewind($fp);
		$csv = stream_get_contents($fp);
		fclose($fp);
		
		//csvはexcelで開くので　文字コードはsjisにする
		$csv_sjis = mb_convert_encoding($csv,'SJIS-win','utf8');
		
		return $csv_sjis;
		
	}
	
	protected function _getBody( $query , $columns ){
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
		
		return $csv;
	}
	
	protected function _downloadCSVFile( $data ){
		return $this->_downloadFile( $data , $this->_controller->name . date('-ymd-His') . '.csv' , 'csv');
	}

	protected function _downloadFile( $data , $filename, $type ) {
		//Content-Typeを指定
		$this->_controller->response->type($type);
		//download()内ではheader("Content-Disposition: attachment; filename=hoge.csv")を行っている
		$this->_controller->response->download($filename);

		$this->_controller->autoRender = false;

		$this->_controller->response->body($data);
	}
}
