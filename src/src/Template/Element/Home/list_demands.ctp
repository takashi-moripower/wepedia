<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>新着　顧客の声</h2>
	</div>

	<table class="table table-striped table-bordered table-home table-home-sales face48">
		<thead>
			<tr>
				<th>詳細</th>
				<th class="text-center">担当</th>
				<th class="text-center">報告日</th>
				<th class="text-center">得意先名</th>
				<th class="text-center">要望区分</th>
				<th class="text-center">商品名</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($list_demands)): ?>
				<tr>
					<td class="text-center" colspan="7">未読の要望はありません</td>
				</tr>
			<?php endif ?>
			<?php foreach ($list_demands as $demand): ?>
				<tr>
					<td class="link"><?= $this->Html->link('<i class="fa fa-newspaper-o fa-fw"></i>', ['controller' => 'sales', 'action' => 'view', $demand->sale_id], ['escape' => false,'class'=>'btn btn-default']) ?></td>
					<td class="face"><?= $this->Element('face', ['id' => $demand->user_id]) ?></td>
					<td><?= $demand->date ?></td>
					<td class="trim12"><?= $demand->client_name ?></td>
					<td class="trim8"><?= $demand->type ?></td>
					<td class="trim8"><?= $demand->product_name ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
