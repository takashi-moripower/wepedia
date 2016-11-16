<div class=" col-lg-offset-6 col-lg-12">
	<h2><?= $label ?></h2>
	<div class="panel panel-default">
		<div class="panel-body">
			<?= $this->Form->create() ?>
			<div class="form-group">
				<?= $this->Form->TextArea('value',['value'=>$value]) ?>
			</div>
			<div class="form-group clearfix">
				<?= $this->Form->submit('保存', ['class' => 'pull-right']) ?>
			</div>
			<?= $this->Form->end() ?>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">
			Hint
		</div>
		<div class="panel-body">
			リストを　,　で区切って入力してください
		</div>
	</div>
</div>