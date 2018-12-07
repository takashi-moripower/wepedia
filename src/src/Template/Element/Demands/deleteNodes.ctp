<?php
if ($this->getAction() == 'index') {
	return;
}

if( empty( $all )){
	return;
}

if ($this->getAction() == 'draft') {
	$label = 'ゴミ箱へ移動';
	$url = $this->Url->build(['action' => 'deleteNodes', json_encode($all)]);
} else {
	$label = '完全削除';
	$url = $this->Url->build(['action' => 'deleteNodesComplete', json_encode($all)]);
}
?>
<div class="text-right">
	<button class="btn btn-default delete-nodes" url="<?= $url ?>">
		上記<?= count($all) ?>件を<?= $label ?>
	</button>
</div>

<?php $this->append('script'); ?>
<script>
	$(function () {
		btn = $('button.delete-nodes');
		btn.on('click', function () {
			if (confirm('<?= $label ?>してもよろしいですか？')) {
				$(location).attr('href', btn.attr('url') );
			} else {
			}
		});
	});
</script>
<?php $this->end(); ?>
	