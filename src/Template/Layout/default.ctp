<?php
$this->start('content');
?>
	<div class="col-xs-24 col-md-18 col-md-push-3">
		<?= $this->fetch('content'); ?>
	</div>
	<div class="col-xs-12 col-md-3 col-md-pull-18">
		<?= $this->element('Navigation/leftProducts') ?>
	</div>
	<div class="col-xs-12 col-md-3">
		<?= $this->element('Navigation/rightUsersLink') ?>
	</div>
<?php
$this->end();

echo $this->Element("../Layout/base");
?>