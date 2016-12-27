<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>新着　営業報告</h2>
	</div>
	<table class="table table-striped table-bordered table-home table-home-sales face48">
		<thead>
			<tr>
				<th>詳細</th>
				<th class="text-center">担当</th>
				<th class="text-center">報告日</th>
				<th class="text-center">得意先名</th>
				<th class="text-center">相手担当者</th>
				<th class="text-center">件名</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($list_sales)): ?>
				<tr>
					<td class="text-center" colspan="7">未読の報告はありません</td>
				</tr>
			<?php endif ?>

			<?php foreach ($list_sales as $sale): ?>
				<tr>
					<td class="link"><?= $this->Html->link('<i class="fa fa-newspaper-o fa-fw"></i>', ['controller' => 'sales', 'action' => 'view', $sale->id,'h'], ['escape' => false,'class'=>'btn btn-default']) ?></td>
					<td class="face face48">
						<?= $this->Element('face', ['id' => $sale->user_id]) ?>
					</td>
					<td><?= $sale->date ?></td>
					<td class="trim12"><?= $sale->client_name ?></td>
					<td class="trim8"><?= $sale->charge_person ?></td>
					<td class="trim8"><?= $sale->title ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
