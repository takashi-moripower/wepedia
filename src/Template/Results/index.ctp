<?php
$this->append('script');
echo $this->Html->script(['autoComplete', 'popupEdit','jquery.fixedTableHeader']);
?>
<script>
	var USER_NAME = <?= $this->Json->safeEncode(array_values($name_users)) ?>;

	$(function () {
		setAutoComplete('.autocomplete-users', USER_NAME);

		$('.table-index-results').fixedTableHeader();
	});
</script>
<?php
$this->end();
?>
<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-index table-index-results" url="<?= $this->Url->build(['controller' => 'results', 'action' => 'setData']) ?>">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('name', '名前') ?></th>
				<th><?= $this->Paginator->sort('date', '開始日') ?></th>
				<th><?= $this->Paginator->sort('type', '区分') ?></th>
				<th><?= $this->Paginator->sort('target_new', '目標・新規') ?></th>
				<th><?= $this->Paginator->sort('target_exist', '目標・既存') ?></th>
				<th><?= $this->Paginator->sort('previous_new', '前年・新規') ?></th>
				<th><?= $this->Paginator->sort('previous_exist', '前年・既存') ?></th>
				<th><?= $this->Paginator->sort('forecast_new', '見込・新規') ?></th>
				<th><?= $this->Paginator->sort('forecast_exist', '見込・既存') ?></th>
				<th><?= $this->Paginator->sort('result_new', '実績・新規') ?></th>
				<th><?= $this->Paginator->sort('result_exist', '実績・既存') ?></th>
			</tr>
		</thead>
	<tbody>
		<?php
		foreach ($results as $item) {
			echo $this->Element('Results/row', ['item' => $item]);
		}
		?>
	</tbody>
	</table>
</div>
<?= $this->Element('paginator') ?>