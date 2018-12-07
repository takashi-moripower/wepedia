<?php
//	管理ページ用レイアウト
//	controller in [ Users , Products , Informations , Results , Config ]
//	左　管理
//	右　ユーザー絞込
$this->start('content');
?>
	<div class="col-xs-3">
		<?= $this->element('Navigation/leftManagement') ?>
	</div>
	<div class="col-xs-21">
		<?= $this->fetch('content'); ?>
	</div>
<?php
$this->end();

echo $this->Element("../Layout/base");
?>