<?php
$control_id = "card-demands-" . $item->id;
?>
<div id="<?= $control_id ?>" class=" collapse" role="tabpanel">
	<table class="table table-bordered table-striped table-condensed view-sale-card-previous">
		<tbody>
			<?php foreach ($item->demands as $d_item): ?>
				<tr>
					<th class="bg-primary col-xs-6">日時</th>
					<td class="bg-primary col-xs-18">
						<div class="pull-left">
						<?= $d_item->date ?>
						</div>
						<div class="pull-right">
						<?= $this->Html->link('<i class="fa fa-lg fa-pencil"></i> ',['controller'=>'mobile','action'=>'demands','edit',$d_item->id],['escape'=>false,'style'=>'color:white;']);?>
						</div>
					</td>
				</tr>
				<tr>
					<th>要望区分</th>
					<td><?= h($d_item->type) ?></td>
				</tr>
				<tr>
					<th>商品区分</th>
					<td><?= h($d_item->product_category) ?></td>
				</tr>
				<tr>
					<th>商品名</th>
					<td><?= h($d_item->product_name) ?></td>
				</tr>
				<tr>
					<th>要望</th>
					<td><?= h($d_item->demand) ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
<table class="table table-bordered table-striped table-condensed view-sale-card-previous">
	<tr>
		<th>
			<button class="btn btn-default btn-lg col-xs-24" type="button" data-toggle="collapse" data-target="#<?= $control_id ?>" aria-expanded="false" aria-controls="<?= $control_id ?>" data-parent=".view-sale-card[sale_id=\'<?= $item->id ?>\']">
				顧客の声 (<?= count($item->demands) ?>)
			</button>
		</th>
	</tr>
</table>
