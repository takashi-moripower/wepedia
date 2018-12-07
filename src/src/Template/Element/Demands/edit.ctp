<?php
if ($demand->isNew()) {
	$title = '顧客の声　追加';
} else {
	$title = '顧客の声　編集';
}
?>

<?= $this->Form->create($demand) ?>
<div class="row">

	<div class="col-lg-12 col-lg-offset-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2><?= h($title) ?></h2>
			</div>
			<div class="panel-body panel-body-padding2">

				<?= $this->Element('Sales/viewRoot', ['item' => $demand->sale]) ?>
				<?= $this->Element('Demands/editDemand') ?>

				<div class="text-center">
					<div class="btn-group" role="group">
						<?= $this->Form->button('報告', ['name' => 'flags', 'value' => 'normal', 'class' => 'btn btn-default']) ?>
						<?= $this->Form->button('下書き', ['name' => 'flags', 'value' => 'unpublished', 'class' => 'btn btn-default']) ?>
						<?php if (!$demand->isNew()): ?>
							<?= $this->Form->button('削除', ['name' => 'flags', 'value' => 'deleted', 'class' => 'btn btn-default']) ?>
						<?php endif ?>
					</div>
					<div class="btn-group" role="group">
						<?php
						foreach ($returnTo as $label => $url) {
							echo $this->Html->link($label . ' に戻る', $url, ['escape' => false, 'class' => 'btn btn-default']);
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->Form->end() ?>
