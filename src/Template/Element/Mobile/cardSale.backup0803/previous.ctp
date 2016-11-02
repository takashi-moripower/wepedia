<?php
$control_id = "card-previous-" . $item->id;
?>
<div id="<?= $control_id ?>" class=" collapse" role="tabpanel">
	<table class="table table-bordered table-striped table-condensed view-sale-card-previous">
		<tbody>
			<?php foreach ($item->previous as $p_item): ?>
				<tr>
					<th class="bg-primary col-xs-6">日時</th>
					<td class="bg-primary col-xs-18">
						<div class="pull-left">
							<?= $p_item->date ?><?= $p_item->time ?>
						</div>
						<div class="pull-right">
							<?= $this->Html->link('<i class="fa fa-lg fa-pencil"></i> ', ['controller' => 'mobile', 'action' => 'sales', 'edit', $p_item->id], ['escape' => false, 'style' => 'color:white;']); ?>
						</div>
					</td>
				</tr>
				<tr>
					<th>営業結果</th>
					<td><?= h($p_item->result) ?></td>
				</tr>
				<tr>
					<th>手ごたえ</th>
					<td><?= h($p_item->state) ?></td>
				</tr>
				<tr>
					<th>対応</th>
					<td><?= h($p_item->treatment) ?></td>
				</tr>
				<?php if (!empty($p_item->next_date)): ?>
					<tr>
						<th>次回予定</th>
						<td><?= h($p_item->next_date->format('Y-m-d H:i')) ?></td>
					</tr>
				<?php endif ?>
				<tr>
					<th>報告</th>
					<td><?= h($p_item->report) ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
<table class="table table-bordered table-striped table-condensed view-sale-card-previous">
	<tr>
		<th>
			<button class="btn btn-default btn-lg col-xs-24" type="button" data-toggle="collapse" data-target="#<?= $control_id ?>" aria-expanded="false" aria-controls="<?= $control_id ?>" data-parent=".view-sale-card[sale_id=\'<?= $item->id ?>\']" >
				過去の報告 (<?= count($item->previous) ?>)
			</button>
		</th>
	</tr>
</table>
