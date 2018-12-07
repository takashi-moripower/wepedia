<?php
$columns = [];

foreach (['total','exist', 'new', ] as $word2) {
	foreach (['target' => '目標', 'previous' => '前年', 'forecast' => '見込', 'result' => '実績' , 'rate'=>'達成率'] as $word1 => $label) {
		$columns[] = [
			'key' => "sum_{$word1}_{$word2}",
			'label' => $label,
		];
	}
}
?>
<div class="panel panel-primary">
	<div class="panel-heading clearfix">
		<h2 class="pull-left"><?= \Cake\Core\Configure::read("result.type.{$type_id}") ?></h2>
		<div class="pull-right"><br>(単位：千円)</div>
	</div>
	<table class="table table-striped table-hover table-bordered table-index table-index-results">
		<thead>
			<tr>
				<th rowspan="2" width="10%"><?= $this->Paginator->sort('user_id', '担当') ?><br><br></th>
				<th colspan="5">合計</th>
				<th colspan="5">既存</th>
				<th colspan="5">新規</th>
			</tr>
			<tr>
				<?php foreach ($columns as $column): ?>
					<th width="6%">
						<?php
						if ($this->Paginator->sortKey() == $column['key']) {
							if ($this->Paginator->sortDir() == 'asc' ) {
								$column['label'] = '&nbsp;'.$column['label'].' <i class="fa fa-caret-down"></i>';
							} else {
								$column['label'] = '&nbsp;'.$column['label'].' <i class="fa fa-caret-up"></i>';
							}
						}
						echo $this->Paginator->sort($column['key'], $column['label'], ['escape' => false]);
						?>

					</th>
				<?php endforeach ?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($items as $item) {
				echo $this->Element('Collections/resultsRow', ['item' => $item]);
			}
			?>
		</tbody>
		<tfoot>
			<?php 
			foreach ($results_total as $rt) {
				if( $rt->type == $type_id ){
					$rt->user = new stdClass();
					$rt->user->name = '合計';
					echo $this->Element('Collections/resultsRow', ['item' => $rt]);
				}
			}
			?>
		</tfoot>
	</table>
</div>
