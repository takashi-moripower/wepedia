<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Defines;

class Defines{
	const SALES_FOLLOW_NO_FOLLOW = 0;
	const SALES_FOLLOW_FOLLOWING = 1;
	const SALES_FOLLOW_FINISHED = 2;
	
	const SALES_FOLLOW_NAME = [
		self::SALES_FOLLOW_NO_FOLLOW => '発送のみ',
		self::SALES_FOLLOW_FOLLOWING => 'フォロー中',
		self::SALES_FOLLOW_FINISHED => '終了',
	];
	
	const SALES_RESULT_FOLLOWING = 'フォロー継続';
	
	const SALES_DO_DIRECTMAIL = 'DM発送';
	
	
	const WEEK_DAYS = ['日','月', '火', '水', '木', '金', '土', ];
	
	
	const MOBILE_NODES_PER_PAGE = 10;
}