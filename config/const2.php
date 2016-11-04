<?php

//	ユーザーが変更しない設定
return[
	'controller' => [
		'label' => [
			'sales' => '営業報告',
			'demands' => '顧客の声',
			'users' => 'ユーザー',
			'clients' => '顧客情報',
			'products' => '製品情報',
			'config' => '設定',
		]
	],
	'set_data_option' => [
		'Sales' => ['contain' => ['Users']],
		'Demands' => ['contain' => ['Users']],
		'Products' => ['contain' => ['Users']],
		'Results' => ['contain' => ['Users']],
		'Users' => [],
		'Informations' => ['contain' => ['Users']]
	],
	'user_config_item' => [
			[
			'label' => '営業報告、Do',
			'type' => 'list',
			'key' => 'sale.project_do'
		],
			[
			'label' => '営業報告、Action',
			'type' => 'list',
			'key' => 'sale.project_act'
		],
			[
			'label' => '営業報告、結果',
			'type' => 'list',
			'key' => 'sale.result'
		],
			[
			'label' => '営業報告、手ごたえ',
			'type' => 'list',
			'key' => 'sale.state'
		],
			[
			'label' => '営業報告、対応',
			'type' => 'list',
			'key' => 'sale.treatment'
		],
			[
			'label' => '顧客の声、要望区分',
			'type' => 'list',
			'key' => 'demand.type'
		],
			[
			'label' => 'ユーザー、管理者',
			'type' => 'list',
			'key' => 'user.admin'
		],
			[
			'label' => '外観',
			'type' => 'select',
			'options' => [
				'wenet00.css',
				'wenet01.css',
				'wenet02.css',
				'wenet03.css',
				'wenet04.css',
			],
			'key' => 'appearance.style'
		],
	],
	'Index' => [
		'Limit' => [
			'Default' => 20,
			'Select' => [
				10 => "10件",
				20 => "20件",
				50 => "50件",
				100 => "100件"
			]
		],
		'Date' => [
			'Select' => [
				'' => '日にち',
				'-1 day' => '過去1日',
				'-3 day' => '過去3日',
				'-7 dat' => '過去7日',
				'-1 month' => '過去1か月',
				'-1 year' => '過去1年'
			],
		]
	],
	'Links' =>
		[
			[
			'label' => 'myトレ',
			'url' => [
				'https://page-hiraku.com/mytraining/login.php?scid=2ja2fkce',
				'https://page-hiraku.com/mytraining/manager/groupuser/login.php',
			]
		],
			[
			'label' => '就職筆記',
			'url' => [
				'https://page-hiraku.com/syusyokumondai/login1.php',
				'https://page-hiraku.com/syusyokumondai/group/login.php',
			]
		],
			[
			'label' => 'IPWebトレ',
			'url' => [
				'https://page-hiraku.com/itpassport_webtraining/training/',
				'https://page-hiraku.com/itpassport_webtraining/manager/class/login.php',
			]
		],
			[
			'label' => 'FE午前対策',
			'url' => [
				'https://page-hiraku.com/fe-am/web/login1.php',
				'https://page-hiraku.com/fe-am/class_kanri/',
			]
		],
			[
			'label' => '美容師',
			'url' => [
				'https://page-hiraku.com/biyo/web/login1.php',
				'https://page-hiraku.com/biyo/class_kanri/login.php',
			]
		],
			[
			'label' => '理容師',
			'url' => [
				'https://page-hiraku.com/riyo/web/login1.php',
				'https://page-hiraku.com/riyo/class_kanri/login.php',
			]
		],
			[
			'label' => '公務員',
			'url' => [
				'https://www.google.com',
				'https://www.google.com',
			]
		],
			[
			'label' => '理学療法士',
			'url' => [
				'https://www.google.com',
				'https://www.google.com',
			]
		],
			[
			'label' => '作業療法士',
			'url' => [
				'https://www.google.com',
				'https://www.google.com',
			]
		],
	],
	'Flags' => [
		'Name' => [
			'normal' => '通常報告',
			'unpublished' => '下書き',
			'deleted' => '削除済み'
		],
		'Class' => [
			'normal' => 'primary',
			'unpublished' => 'info',
			'deleted' => 'danger'
		]
	]
];
