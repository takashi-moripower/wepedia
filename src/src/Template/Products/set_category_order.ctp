<div class=" col-lg-offset-6 col-lg-12">
	<h2>カテゴリ順序設定</h2>
	<div class="panel panel-default">
		<div class="panel-body">
			ドラッグアンドドロップで順序を入れ替えた後<br>
			保存ボタンを押してください
			<?= $this->Form->create(NULL,['class'=>'text-right']) ?>
			<?= $this->Form->hidden('category_order') ?>
			<?= $this->Form->submit('保存') ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
	<div class="panel panel-default">
		<table class="table table-striped table-categories">
			<tbody>
				<?php foreach ($categories as $category): ?>
					<tr>
						<td key="name"><?= $category->category ?></td>
						<td key="category_order"><?= $category->category_order ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<?php $this->append('script') ?>
<script>
	$(function () {
		$('table.table-categories tbody').sortable();
		$('table.table-categories tbody').on("sortupdate", function (event, ui) {
			data = [];
			$('table.table-categories tbody tr').each(function(i,e){
				data[i] = $(this).find('td[key="name"]').text();
			});
			
			$('input[name="category_order"]').val( JSON.stringify( data ));
		});
	});
</script>
<?php $this->end() ?>