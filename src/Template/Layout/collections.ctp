<?php
//	集計ページ用レイアウト
//	controller in [ Collections ]
//	左　集計メニュー
//	右　ユーザー絞込
$this->start('content');
?>
	<div class="col-xs-24 col-md-18 col-md-push-3">
		<?= $this->fetch('content'); ?>
	</div>
	<div class="col-xs-12 col-md-3 col-md-pull-18">
		<?= $this->element('Navigation/leftCollections') ?>
		<?= $this->element('Navigation/leftProducts') ?>
	</div>
	<div class="col-xs-12 col-md-3">
		<?= $this->element('Navigation/rightUsersSearch') ?>
	</div>
<?php
$this->end();

echo $this->Element("../Layout/base");
?>