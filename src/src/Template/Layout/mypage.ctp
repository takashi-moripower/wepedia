<?php
$this->start('content');
?>
	<div class="col-xs-24 col-md-21 col-md-push-3">
		<?= $this->fetch('content'); ?>
	</div>
	<div class="col-xs-12 col-md-3 col-md-pull-21">
		<?= $this->element('Navigation/leftMypage') ?>
		<?= $this->element('Navigation/leftProducts') ?>
	</div>
<?php
$this->end();

echo $this->Element("../Layout/base");
?>