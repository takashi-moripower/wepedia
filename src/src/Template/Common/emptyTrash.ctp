<?php
$code = sprintf("%04d", rand(0, 9999));
?>

<div class=" col-lg-offset-6 col-lg-12">
	<h2>ゴミ箱を空にする</h2>
	<?= $this->Form->create(NULL, ['type' => 'file']) ?>
	<?= $this->Form->hidden('code2', ['value' => $code]) ?>
	<div class="panel panel-danger">
		<div class="panel-body">

			<p class="bg-danger text-danger panel-body">
				削除コード「<?= $code ?>」を入力してください
			</p>

			<div class="panel-body">
				<div class="form-group">
					<label>削除コード</label>
					<div>
						<?= $this->Form->text('code') ?>
					</div>
				</div>
				<div class="text-right">		
					<?= $this->Form->submit('処理開始', ['class' => '']) ?>
				</div>
			</div>
		</div>
	</div>
	<?= $this->Form->end() ?>
</div>
