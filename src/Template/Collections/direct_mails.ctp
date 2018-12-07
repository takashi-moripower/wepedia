<?php

use App\Defines\Defines;
use App\Controller\Component\DirectMailComponent as Collection;

$FOLLOW_NAME = Defines::SALES_FOLLOW_NAME;
?>

<div class="panel panel-primary panel-dm">
	<div class="panel-heading clearfix">
		<h2 class="pull-left">DMフォロー状況</h2>
	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="col-xs-4"></th>
				<th class="col-xs-2 text-center">件数</th>
				<th class="col-xs-2 text-center"><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_NO_FOLLOW] ?></th>
				<th class="col-xs-2 text-center"><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_FOLLOWING] ?></th>
				<th class="col-xs-2 text-center"><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_FINISHED] ?></th>
				<th></th>
			</tr>
		</thead>
	</table>
</div>
<?php foreach ($collections as $col_id => $collection): ?>
	<div class="panel panel-primary panel-dm">
		<table class="table table-bordered ">
			<tbody>
				<?php
				$date = isset( $collection->date ) ? $collection->date->Format('Y-m-d') : null;
				
				$label_text = $collection->title . "[" . $date . '] <i class="fa fa-caret-down"></i>';
				$url = "#collapse{$col_id}";
				$label = $this->Html->link($label_text, $url, ['escape' => false, 'data-toggle' => 'collapse',]);
				echo $this->Element('Collections/dmRow', ['collection' => $collection, 'label' => $label]);
				?>
			</tbody>
		</table>
			<div class="collapse" id="collapse<?= $col_id ?>">
				<table class="table table-bordered ">
					<tbody>
						<?php
						$collections_users = $collection->divideByUsers();
						foreach ($collections_users as $c) {
							echo $this->Element('Collections/dmRowUser', ['collection' => $c]);
						}
						?>
					</tbody>
				</table>
			</div>
		<?php if (false): ?>
			<tbody class="collapse" id="collapse<?= $col_id ?>">
				<?php
				$collections_users = $collection->divideByUsers();
				foreach ($collections_users as $c) {
					echo $this->Element('Collections/dmRowUser', ['collection' => $c]);
				}
				?>
			</tbody>
			<div class="collapse" id="collapse<?= $col_id ?>">
				<table class="table table-bordered ">
					<tbody>
						<?php
						$collections_users = $collection->divideByUsers();
						foreach ($collections_users as $c) {
							echo $this->Element('Collections/dmRowUser', ['collection' => $c]);
						}
						?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
<?php endforeach ?>
