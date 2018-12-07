<?php
$all = [];

$this->append('script');
echo $this->Html->script(['autoComplete', 'popupEdit', 'jquery.fixedTableHeader']);
?>
<script>
	var USER_NAME = <?= $this->Json->safeEncode(array_values($name_users)) ?>;
	var CLIENT_NAME = <?= $this->Json->safeEncode(array_values($name_clients)) ?>;

	$(function () {
		setAutoComplete('.autocomplete-users', USER_NAME);
		setAutoComplete('.autocomplete-clients', CLIENT_NAME);

		$('.table-index-sales').fixedTableHeader();
	});
</script>
<?php
$this->end();

echo $this->Element('Sales/search');
echo $this->Element('Sales/tab');
?>
<div class="panel panel-default under-tab">
	<table class="table table-striped table-hover table-bordered table-index table-index-sales" url="<?= $this->Url->build(['controller' => 'sales', 'action' => 'setData']) ?>">
		<thead>
			<tr>
				<?php if (false): ?>
					<th><?= $this->Paginator->sort('boss_check', '部長') ?></th>
					<th><?= $this->Paginator->sort('boss_check2', 'マネ') ?></th>
					<th><?= $this->Paginator->sort('time', '時間') ?></th>
				<?php endif ?>
				<th><?= $this->Paginator->sort('UnreadSales.user_id', '読') ?></th>
				<th><?= $this->Paginator->sort('Sales.user_id', '担当') ?></th>
				<th><?= $this->Paginator->sort('Sales.date', '日にち') ?></th>
				<th><?= $this->Paginator->sort('Sales.client_name', '得意先') ?></th>
				<th><?= $this->Paginator->sort('Sales.charge_person', '相手担当') ?></th>
				<th><?= $this->Paginator->sort('Sales.title', '件名') ?></th>
				<th><?= $this->Paginator->sort('Sales.result', '結果') ?></th>
				<th><?= $this->Paginator->sort('Sales.state', '手ごたえ') ?></th>
				<th><?= $this->Paginator->sort('Sales.report', '報告') ?></th>
				<th><?= $this->Paginator->sort('Sales.treatment', '対応') ?></th>
				<th>詳細/編集</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($sales as $item) {
				echo $this->Element('Sales/row', ['item' => $item]);
				$all[] = $item->id;
			}
			?>
		</tbody>
	</table>
</div>

<?= $this->Element('Sales/deleteNodes', ['all' => $all]) ?>
<?= $this->Element('paginator') ?>