<?php

use Cake\I18n\Date;
use Cake\I18n\Time;

Date::setToStringFormat('yyyy-MM-dd');
Time::setToStringFormat('yyyy-MM-dd HH:mm');
?>
<div class="row">
	<div class="col-xs-24">
		<?= $this->Element('Home/schedule') ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-24">
		<?= $this->Element('Home/information') ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $this->Element('Home/list_sales') ?>
	</div>
	<div class="col-md-12">
		<?= $this->Element('Home/list_demands') ?>
	</div>
</div>
