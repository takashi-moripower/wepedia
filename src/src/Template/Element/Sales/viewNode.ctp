<table class="table table-striped table-view-node table-view-sales-node">
	<thead>
		<tr class="bg-gray">
			<th class="col-xs-4 vertical-middle">日時</th>
			<td class="col-xs-12 vertical-middle" colspan="2"><?= !empty($item->date) ? $item->date->Format('y年m月d日') : '' ?> <?= !empty($item->time) ? $item->time->Format('H時i分') : '' ?></td>
			<td class="col-xs-8 edit">
				<a class="btn btn-default pull-right" href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'deleteNode', $item->id]) ?>" title="一件削除"> <i class="fa fa-trash-o fa-lg fa-fw"></i> </a>
				<a class="btn btn-default pull-right" href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'edit', $item->id]) ?>" title="編集"> <i class="fa fa-pencil fa-lg fa-fw"></i> </a>
			</td>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($item->agent)): ?>
			<tr>
				<th class="col-xs-4">代理報告者</th><td class="col-xs-20" colspan="3"><?= h($item->agent->name) ?></td>
			</tr>
		<?php endif ?>
		<tr>
			<th class="col-xs-4">結果</th><td class="col-xs-8"><?= h($item->result) ?></td>
			<th class="col-xs-4">手ごたえ</th><td class="col-xs-8"><?= h($item->state) ?></td>
		</tr>
		<tr>
			<th class="col-xs-4">対応</th><td class="col-xs-20" colspan="3"><?= h($item->treatment) ?></td>
		</tr>
		<?php if (!empty($item->next_date)): ?>
			<tr>
				<th class="col-xs-4">次回予定</th><td class="col-xs-20" colspan="3"><?= $item->next_date->Format('y年m月d日') ?></td>
			</tr>
		<?php endif ?>
		<tr>
			<th class="col-xs-4">報告</th><td class="col-xs-20" colspan="3"><?= h($item->report) ?></td>
		</tr>
	</tbody>
</table>
