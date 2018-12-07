<div class="col-lg-offset-2 col-lg-4">
	<h2></h2>
	<div class="panel panel-default">
		<div class="panel-heading">
			カテゴリ選択
		</div>
		<ul class="nav nav-pills nav-stacked">
			<?php foreach ($name_categories as $category_name): ?>
				<li>
					<?= $this->Html->link($category_name, ['controller' => 'products', 'action' => 'setProductOrder', $category_name]) ?>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>
<div class="col-lg-12">
	<h2>商品順序設定　（<?= $category ?>）</h2>
	<div class="panel panel-default">
		<div class="panel-body">
			ドラッグアンドドロップで順序を入れ替えた後<br>
			保存ボタンを押してください
			<?= $this->Form->create(NULL, ['class' => 'text-right']) ?>
			<?= $this->Form->hidden('product_order') ?>
			<?= $this->Form->hidden('category', ['value' => $category]) ?>
			<?= $this->Form->submit('保存') ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
	<div class="panel panel-default">
		<table class="table table-striped table-products">
			<tbody>
				<?php foreach ($products as $product): ?>
					<tr>
						<td key="name"><?= $product->name ?></td>
						<td key="product_order"><?= $product->product_order ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<?php $this->append('script') ?>
<script>
	$(function () {
		$('table.table-products tbody').sortable();
		$('table.table-products tbody').on("sortupdate", function (event, ui) {
			data = [];
			$('table.table-products tbody tr').each(function (i, e) {
				data[i] = $(this).find('td[key="name"]').text();
			});

			$('input[name="product_order"]').val(JSON.stringify(data));
		});
	});
</script>
<?php $this->end() ?>