<?php
use App\Defines\Defines;

$FOLLOW_NAME = Defines::SALES_FOLLOW_NAME;

if (empty($sales->toArray())) {
	echo 'no data';
	return;
}
?>
<div class="panel panel-primary">
	<div class="panel-heading clearfix">
		<h2 class="pull-left">DMフォロー状況</h2>
		<form class="form-inline pull-right">
			<br>
			<div class="form-group">
				<label for="exampleInputName2">件名 [発送日] </label>
				<?= $this->Form->select('date', $date_options, ['value' => $date->format('Y-m-d')]) ?>
			</div>
		</form>

	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<td colspan='4'>
					<?= $this->Element('Mypage/dmChart', ['collection' => $collection]) ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class='col-xs-6 text-center'>対象件数</td>
				<td class='col-xs-6 text-center'><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_NO_FOLLOW] ?></td>
				<td class='col-xs-6 text-center'><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_FOLLOWING] ?></td>
				<td class='col-xs-6 text-center'><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_FINISHED] ?></td>
			</tr>
			<tr>
				<td class='text-center'><?= $collection->total ?></td>
				<td class='text-center'><?= $collection->no_follow ?></td>
				<td class='text-center'><?= $collection->following ?></td>
				<td class='text-center'><?= $collection->finished ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="panel panel-primary">
	<table class="table table-bordered table-striped table-directmail">
		<thead>
			<tr>
				<th class="text-center">件名</th>
				<th class="text-center">送付先</th>
				<th class="text-center">最終報告</th>
				<th class="text-center">結果</th>
				<th class="text-center">詳細 / 追加報告</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sales as $sale): ?>
				<tr>
					<td><?= h($sale->title) ?></td>
					<td><?= h($sale->client_name) ?></td>
					<td><?= $sale->latest->date->format('Y-m-d') ?></td>
					<td><?= $FOLLOW_NAME[$sale->follow] ?></td>
					<td class="link text-center">
						<?= $this->Html->link('<i class="fa fa-newspaper-o fa-fw fa-lg"></i>', ['controller' => 'sales', 'action' => 'view', $sale->id,'d'], ['escape' => false , 'class'=>'btn btn-default']) ?>
						<?= $this->Html->link('<i class="fa fa-pencil fa-fw fa-lg"></i>', ['controller' => 'sales', 'action' => 'add', $sale->root_id], ['escape' => false , 'class'=>'btn btn-default']) ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>



<?php $this->append('script') ?>
<script>
	$(function () {
		url = "<?= $this->Url->build(['controller' => 'mypage', 'action' => 'directMail', $user->id]) ?>";
		$('select[name="date"]').on('change', function () {
			window.location.href = url + "/" + $(this).val();
		});
	});
</script>
<?php $this->end() ?>