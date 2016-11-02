<?php

use \App\Utils\AppUtility;

$menus = [
	[
		'controller' => 'users',
		'label' => 'ユーザー',
		'actions' => [
			'一覧' => 'index',
			'新規追加' => 'add',
			'インポート' => 'import',
			'エクスポート' => 'export',
			'全件消去' => 'deleteAll'
		]
	],
	[
		'controller' => 'results',
		'label' => '売り上げ',
		'actions' => [
			'一覧' => 'index',
			'インポート' => 'import',
			'エクスポート' => 'export',
			'全件消去' => 'deleteAll'
		]
	],
	[
		'controller' => 'products',
		'label' => '商品',
		'actions' => [
			'一覧' => 'index',
			'カテゴリ順序' => 'setCategoryOrder',
			'商品順序' => 'setProductOrder',
			'インポート' => 'import',
			'エクスポート' => 'export',
			'全件消去' => 'deleteAll'
		]
	],
	[
		'controller' => 'informations',
		'label' => 'お知らせ',
		'actions' => [
			'一覧' => 'index',
		]
	],
	[
		'controller' => 'sales',
		'label' => '営業情報',
		'actions' => [
			'インポート' => 'import',
			'ゴミ箱を空に' => 'emptyTrash',
		]
	],
	[
		'controller' => 'demands',
		'label' => '顧客の声',
		'actions' => [
			'インポート' => 'import',
			'ゴミ箱を空に' => 'emptyTrash',
		]
	],
	[
		'controller' => 'config',
		'label' => 'その他設定',
		'actions' => [
			'営業報告　Do' => 'setProjectDo',
			'営業報告　Act' => 'setProjectAct',
			'営業報告　結果' => 'setResult',
			'営業報告　手ごたえ' => 'setState',
			'営業報告　対応' => 'setTreatment',
			'顧客の声　要望区分' => 'setDemandType',
			'売り上げ　区分' => 'setResultType',
			'右メニュー　所属' => 'setSection',
			'ユーザー　管理者' => 'setAdmin',
			'画面　配色' => 'setStyle',
		]
	],
];

foreach ($menus as $menu):
	$expand = $this->isMatch($menu['controller'], NULL);
	?>
	<div class="panel panel-primary management-nav">
		<div class="panel-heading clearfix">
			<h3 class="panel-title pull-left"><?= $menu['label'] ?></h3>
			<button class="btn btn-default btn-xs pull-right" type="button" data-toggle="collapse" data-target="#left-nav-<?= $menu['controller'] ?>" aria-expanded="<?= $expand ? 'true' : 'false' ?>" aria-controls="collapse-nodes">
				<i class="fa fa-caret-down"></i>
			</button>		
		</div>
		<div class="panel-body collapse <?= $expand ? 'in' : '' ?>" id="left-nav-<?= $menu['controller'] ?>">
			<ul class="nav nav-pills nav-stacked">
				<?php
				foreach ($menu['actions'] as $label => $action):
					if ($this->isMatch($menu['controller'], AppUtility::snake($action))) {
						$class = "active";
					} else {
						$class = "";
					}
					?>
					<li role="presentation" class="<?= $class ?>"><a href="<?= $this->Url->build(['controller' => $menu['controller'], 'action' => $action]) ?>"><?= $label ?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
<?php endforeach ?>
