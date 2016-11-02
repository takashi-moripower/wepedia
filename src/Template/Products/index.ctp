<?php
$this->append('script');
echo $this->Html->script(['autoComplete', 'popupEdit', 'jquery.fixedTableHeader']);
?>
<script>
	var USER_NAME = <?= $this->Json->safeEncode(array_values($name_users)) ?>;

	$(function () {
		setAutoComplete('.autocomplete-users', USER_NAME);

		$('.table-index-sales').fixedTableHeader();

	});
</script>
<?php
$this->end();
?>
<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-index table-index-products" url="<?= $this->Url->build(['controller' => 'products', 'action' => 'setData']) ?>">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('name', '名称') ?></th>
				<th><?= $this->Paginator->sort('product_order', '商品・順序') ?></th>
				<th><?= $this->Paginator->sort('category', '区分') ?></th>
				<th><?= $this->Paginator->sort('caategory_order', '区分・順序') ?></th>
				<th><?= $this->Paginator->sort('user_id', '制作担当') ?></th>
				<th><?= $this->Paginator->sort('url', 'カルテ') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($products as $item) {
				echo $this->Element('Products/row', ['item' => $item]);
			}
			?>
		</tbody>
	</table>
</div>
<?= $this->Element('paginator') ?>