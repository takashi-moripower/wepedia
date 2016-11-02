<?php

use Cake\I18n\Date;
use Cake\I18n\Time;

Date::setToStringFormat('yyyy-MM-dd');
Time::setToStringFormat('yyyy-MM-dd HH:mm');
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2><?= Date::now() ?>の予定</h2>
	</div>
	<div class="panel-body">	
		<?php if (!empty($schedule)): ?>
			<div>
				<?= $schedule ?>
			</div>
		<br>
		<?php endif ?>

		
		<?php
		$s = false;
		foreach ($next_sales as $sale): 
			if( !$s ){
				$s = true;
			}else{
				echo ',';
			}
			?>
			<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'view', $sale->id]) ?>">
				<i class="fa fa-newspaper-o"></i> <?= h($sale->client_name) ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
