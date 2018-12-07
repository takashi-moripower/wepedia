
<div class="panel panel-primary panel-informations">
	<div class="panel-heading clearfix">
		<h2 class="pull-left">お知らせ</h2>
		<button class="btn btn-default pull-right" type="button" data-toggle="collapse" data-target="#add-info" aria-expanded="false" aria-controls="add-info">お知らせを追加</button>
	</div>
	<div class="panel-body collapse" id="add-info">
		<?= $this->Form->create() ?>
		<?= $this->Form->input('text', ['type' => 'text', 'label' => false, 'placeHolder' => '本文']); ?>
		<?= $this->Form->input('url', ['type' => 'text', 'label' => false, 'placeHolder' => 'url']); ?>
		<?= $this->Form->submit('保存	', ['class' => 'pull-right']) ?>
		<?= $this->Form->end() ?>
	</div>

	<table class="table table-striped">
		<tbody>
			<?php foreach ($informations as $info): ?>
				<tr>
					<td>
						<?= h($info->text) ?>
						<?php if ($info->url): ?>
							<a href="<?= $info->url ?>">
								<i class="fa fa-external-link"></i>
							</a>
						<?php endif ?>
					</td>
					<td class="text-right">
						<?= h($info->user->name) ?>
						:
						(<?= $info->modified ?>)
						&nbsp;
						<a href="javascript:void(0)" info-id="<?= $info->id ?>" class="delete-info" > <i class="fa fa-close"></i> </a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php $this->append('script') ?>
<script>
	URL_INFO_DELETE = '<?= $this->Url->build(['controller' => 'informations', 'action' => 'delete']) ?>';
	$(function () {
		$('.panel-informations a.delete-info').on('click', function () {
			info_id = $(this).attr('info-id');
			if (confirm('このお知らせを削除しますか？')) {
				url = URL_INFO_DELETE + '/' + info_id + '/1';
				window.location.href = url;
			}
		});
	});
</script>
<?php $this->end() ?>
