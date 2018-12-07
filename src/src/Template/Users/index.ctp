<?php
$this->append('script');
echo $this->Html->script(['popupEdit','jquery.fixedTableHeader']);
?>
<script>
	$(function () {
		$('.table-index-results').fixedTableHeader();
	});
</script>
<?php
$this->end();
?>
<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-index table-index-users" url="<?= $this->Url->build(['controller' => 'users', 'action' => 'setData']) ?>">
		<thead>
			<tr>
				<th></th>
				<th><?= $this->Paginator->sort('name', '名前') ?></th>
				<th><?= $this->Paginator->sort('section', '所属') ?></th>
				<th><?= $this->Paginator->sort('position', '役職') ?></th>
				<th><?= $this->Paginator->sort('tel', 'TEL') ?></th>
				<th><?= $this->Paginator->sort('email', 'E-mail') ?></th>
				<th><?= $this->Paginator->sort('last_login', '最終ログイン') ?></th>
				<th>Action</th>
			</tr>
		</thead>
	<tbody>
		<?php
		foreach ($users as $item) {
			echo $this->Element('Users/row', ['item' => $item]);
		}
		?>
	</tbody>
	</table>
</div>
<?= $this->Element('paginator') ?>