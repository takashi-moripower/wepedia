<?= $this->Element('Mypage/followNav', ['collection' => $collection, 'user_id' => $user->id]) ?>

<div class="panel panel-primary">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text-center">件名</th>
				<th class="text-center">顧客名</th>
				<th class="text-center">報告日</th>
				<th class="text-center">次回予定</th>
				<th class="text-center">詳細/編集/追加報告</th>
			</tr>
		</thead>	
		<tbody>
			<?php foreach( $sales as $sale ): ?>
			<tr>
				<td><?= h($sale->title) ?></td>
				<td><?= h($sale->client_name) ?></td>
				<td class="text-center"><?= h($sale->date) ?></td>
				<td class="text-center"><?= h($sale->next_date ?  $sale->next_date->format('Y-m-d') : '' ) ?></td>
				<td class="text-center link">
					<?= $this->Html->link('<i class="fa fa-newspaper-o fa-lg fa-fw"></i>',['controller'=>'sales','action'=>'view',$sale->id,'f'],['escape'=>false, 'class'=>'btn btn-default']) ?>
					<?= $this->Html->link('<i class="fa fa-pencil fa-lg fa-fw"></i>',['controller'=>'sales','action'=>'edit',$sale->id],['escape'=>false, 'class'=>'btn btn-default']) ?>
					<?= $this->Html->link('<i class="fa fa-reply fa-lg fa-fw"></i>',['controller'=>'sales','action'=>'add',$sale->root_id],['escape'=>false, 'class'=>'btn btn-default']) ?>
				</td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>	
</div>
