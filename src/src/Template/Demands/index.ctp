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

echo $this->Element('Demands/search');
echo $this->Element('Demands/tab');
?>
<div class="panel panel-default under-tab">
	<table class="table table-striped table-hover table-bordered table-index table-index-demands" url="<?= $this->Url->build(['controller' => 'demands', 'action' => 'setData']) ?>">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('UnreadSales.user_id', '読') ?></th>
				<th><?= $this->Paginator->sort('user_name', '営業担当') ?></th>
				<th><?= $this->Paginator->sort('date', '日にち') ?></th>
				<th><?= $this->Paginator->sort('type', '要望区分') ?></th>
				<th><?= $this->Paginator->sort('client_name', '顧客名') ?></th>
				<th><?= $this->Paginator->sort('product_category', '商品区分') ?></th>
				<th><?= $this->Paginator->sort('product_name', '商品名') ?></th>
				<th><?= $this->Paginator->sort('demand', '要望内容') ?></th>
				<th>詳細/編集</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($demands as $item) {
				echo $this->Element('Demands/row', ['item' => $item]);
				$all[] = $item->id;
			}
			?>
		</tbody>
	</table>
</div>
<?= $this->Element('Demands/deleteNodes',['all'=>$all]) ?>
<?= $this->Element('paginator') ?>