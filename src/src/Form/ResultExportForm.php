<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\I18n\Date;

class ResultExportForm extends Form{
	protected function _buildSchema( Schema $schema){
		return $schema
				->addField('start','date')
				->addField('end','date');
	}
	
	protected function _buildValidator(Validator $validator){
		return $validator;
	}
	
	protected function _execute( array $data ){
		$this->start = $this->_getDate( $data['start']);
		$this->end = $this->_getDate( $data['end']);
		return true;
	}
	
	protected function _getDate( $data ){
		$date = new Date( $data['year'] . '-' . $data['month'] . '-' . $data['day']);
		return $date;
	}	
}
