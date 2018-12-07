<?php
$template_date = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><div class="separator hidden-lg"></div><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
$template_time = '<ul class="list-inline"><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';

$panel_class = \Cake\Core\Configure::read('Flags.Class')[$sale->flags];
if ($sale->flags != 'normal') {
	$flag_name = '(' . \Cake\Core\Configure::read('Flags.Name')[$sale->flags] . ')';
} else {
	$flag_name = NULL;
}

$config = \Cake\Core\Configure::read('sale');

$result = array_combine($config['result'], $config['result']);
$state = array_combine($config['state'], $config['state']);
?>
<div class="panel panel-<?= $panel_class ?> edit edit-node">
	<div class="panel-heading">
		<h2 class="panel-title">報告 <?= $flag_name ?></h2>
	</div>
	<div class="panel-body form-horizontal">
		<div class="form-group">
			<label for="time" class="col-xs-8 col-lg-4 control-label">日時</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->input('date', ['type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => $template_date]]); ?>
				<div class="separator hidden-lg col-xs-24"></div>
				<?= $this->Form->input('time', ['type' => 'time', 'label' => false, 'templates' => ['dateWidget' => $template_time]]); ?>
			</div>
		</div>		
		<div class="form-group">
			<label for="agent_id" class="col-xs-8 col-lg-4 control-label">代理報告者</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->select('agent_id', ['' => NULL] + $name_users) ?>
			</div>
		</div>
		<div class="form-group">
			<label for="result" class="col-xs-8 col-lg-4 control-label">結果</label>
			<div class="col-xs-16 col-lg-8">
				<?= $this->Form->select('result', $result) ?>
			</div>
			<div class="separator hidden-lg col-xs-24"></div>
			<label for="state" class="col-xs-8 col-lg-4 control-label">手ごたえ</label>
			<div class="col-xs-16 col-lg-8">
				<?= $this->Form->select('state', $state) ?>
				<?= $this->Form->input('state', ['type' => 'hidden', 'value' => '']); ?>
			</div>
		</div>

		<div class="form-group">
			<label for="next_date" class="col-xs-8 col-lg-4 control-label">次回予定</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->input('next_date', ['type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => $template_date]]); ?>
				<?= $this->Form->input('next_date', ['type' => 'hidden', 'value' => 'NULL']); ?>
			</div>
		</div>		
		<div class="form-group">
			<label for="treatment" class="col-xs-8 col-lg-4 control-label">対応</label>
			<div class="col-xs-16 col-lg-20">
				<?php
				foreach ($config['treatment'] as $key => $value) {
					$selected = ( strpos($sale['treatment'], $value) !== false );
					echo $this->Form->input('treat-' . $key, ['type' => 'checkbox', 'value' => 1, 'label' => $value, 'default' => $selected]);
				}
				?>				
			</div>
		</div>		
		<div class="form-group">
			<label for="report" class="col-xs-8 col-lg-4 control-label">報告</label>
			<div class="col-xs-24 col-lg-20">
				<?= $this->Form->textArea('report') ?>
			</div>
		</div>		
	</div>
</div>
<?= $this->append('script') ?>
<script>
	$(function () {
		updateNext();
		$('select[name="result"]').change(updateNext);
	});

	function updateNext() {
		result = $('select[name="result"]').val();
		if (result.match(/継続/)) {
			$('select[name^="next_date"]').removeAttr('disabled');
			$('input[name^="next_date"]').attr('disabled', 'disabled');
			
			$('select[name="state"]').removeAttr('disabled');
			$('input[name="state"]').attr('disabled','disabled');			
		} else {
			$('select[name^="next_date"]').attr('disabled', 'disabled');
			$('input[name^="next_date"]').removeAttr('disabled');
			
			$('input[name="state"]').removeAttr('disabled');
			$('select[name="state"]').attr('disabled','disabled');			
		}
		console.log(result);
	}
</script>
<?= $this->end() ?>