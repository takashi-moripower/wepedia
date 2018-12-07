<?php
$this->start('content');
?>
<div class="col-xs-24">
	<?= $this->fetch('content'); ?>
</div>

<?php
$this->end();

echo $this->Element("../Layout/base");
?>