<?php

namespace App\Model\Entity\Collections;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Cake\I18n\Date;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use Cake\Utility\Hash;

class FollowsCollection {

	public $ranges;

	public function __construct( $query = NULL ) {
		$this->ranges = [
			[
				'label' => '１カ月以内',
				'start' => new Date('1 month ago'),
				'end' => NULL,
			],
			[
				'label' => '1～2カ月',
				'start' => new Date('2 month ago'),
				'end' => new Date('1 month ago'),
			],
			[
				'label' => '2～3カ月',
				'start' => new Date('3 month ago'),
				'end' => new Date('2 month ago'),
			],
			[
				'label' => '3～6カ月',
				'start' => new Date('6 month ago'),
				'end' => new Date('3 month ago'),
			],
			[
				'label' => '6カ月以上前',
				'start' => new Date('1900-01-01'),
				'end' => new Date('6 month ago'),
			],
			[
				'label' => '全件',
				'start' => NULL,
				'end' => NULL,
			],
		];
		
		if( $query ){
			$this->setData( $query );
		}
	}
	
	public function setData(\Cake\ORM\Query $query ){
		for( $i=0 ; $i<count($this->ranges) ; $i++ ){
			$subQuery = $query->cleanCopy();
			
			$start = Hash::get($this->ranges , "{$i}.start" );
			$end = Hash::get($this->ranges , "{$i}.end" );
			
			if( $start ){
				$subQuery->where(['date >=' => $start ]);
			}
			if( $end ){
				$subQuery->where(['date <=' => $end ]);
			}
			
			$count = $subQuery->count();
			
			$this->ranges = Hash::insert( $this->ranges , "{$i}.count" , $count );
		}
	}

}
