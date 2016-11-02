<?php
$this->append('script');
echo $this->Html->script(['autoComplete', 'popupEdit', 'jquery.fixedTableHeader']);
?>
<script>
	var USER_NAME = <?= $this->Json->safeEncode(array_values($name_users)) ?>;

	$(function () {
		setAutoComplete('.autocomplete-users', USER_NAME);
		$('.table-index-informations').fixedTableHeader();
	});
</script>
<?php
$this->end();
?>
<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-index table-index-informations" url="<?= $this->Url->build(['controller' => 'informations', 'action' => 'setData']) ?>">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('user_id', '投稿者') ?></th>
				<th><?= $this->Paginator->sort('text', '本文') ?></th>
				<th><?= $this->Paginator->sort('url', 'リンク') ?></th>
				<th><?= $this->Paginator->sort('modified', '更新日') ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($informations as $item) {
				echo $this->Element('Informations/row', ['item' => $item]);
			}
			?>
		</tbody>
	</table>
</div>
<?= $this->Element('paginator') ?>
