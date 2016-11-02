<?php
$template_date = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
?>
<div class=" col-lg-offset-6 col-lg-12">
	<h2>エクスポート</h2>
	<?= $this->Form->create(NULL,['type' => 'file']) ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label>出力開始日</label>
				<div>
					<?= $this->Form->input('start', ['value'=>$start, 'type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => $template_date]]); ?>
				</div>
			</div>
			<div class="form-group">
				<label>出力終了日</label>
				<div>
					<?= $this->Form->input('end', ['value'=>$end, 'type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => $template_date]]); ?>
				</div>
			</div>
			<div class="text-right">		
				<?= $this->Form->submit('処理開始', ['class' => '']) ?>
			</div>
		</div>
	</div>
	<?= $this->Form->end() ?>
</div>
