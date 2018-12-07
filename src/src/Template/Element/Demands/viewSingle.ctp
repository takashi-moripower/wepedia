<table class="table table-striped table-view-node table-view-demands-node">
	<thead>
		<tr class="bg-gray">
			<th class="col-xs-4">日時</th><td class="col-xs-12" colspan="2"><?= !empty($item->date) ? $item->date->Format('y年m月d日') : '' ?> <?= !empty($item->time) ? $item->time->Format('H時i分') : '' ?></td>
			<td class="col-xs-8 edit">
				<a class="btn btn-default pull-right" href="<?= $this->Url->build(['controller' => 'demands', 'action' => 'delete', $item->id]) ?>" title="削除"> <i class="fa fa-trash-o"></i> </a>
				<a class="btn btn-default pull-right" href="<?= $this->Url->build(['controller' => 'demands', 'action' => 'edit', $item->id]) ?>" title="編集"> <i class="fa fa-pencil"></i> </a>
			</td>
		</tr>
	</thead>
	<tbody>

		<tr>
			<th class="col-xs-4">要望区分</th><td class="col-xs-20" colspan="3"><?= h($item->type) ?></td>
		</tr>
		<tr>
			<th class="col-xs-4">商品区分</th><td class="col-xs-20" colspan="3"><?= h($item->product_category) ?></td>
		</tr>
		<tr>
			<th class="col-xs-4">商品名</th><td class="col-xs-20" colspan="3"><?= h($item->product_name) ?></td>
		</tr>
		<tr>
			<th class="col-xs-4">要望</th><td class="col-xs-20" colspan="3"><?= h($item->demand) ?></td>
		</tr>
	</tbody>
</table>
