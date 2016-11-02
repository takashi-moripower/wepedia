<?php
$template_date = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
$template_time = '<ul class="list-inline"><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';
?>

<div class="panel panel-default edit-popup popover bottom" key="<?= $key ?>">
	<div class="arrow"></div>
	<div class="panel-body">
		<div class="form-group">

			<?php
			switch ($key) {
				case 'report':
					echo $this->Form->textArea($key, [ 'value' => $item->{$key}]);
					break;

				case 'date':
					echo $this->Form->input($key, ['type' => 'date', 'value' => $item->{$key}, 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => $template_date]]);
					break;
				
				case 'result':
					$result = \Cake\Core\Configure::read('sale.result');
					echo $this->Form->select($key, array_combine( $result, $result));
					break;

				case 'state':
					$state = \Cake\Core\Configure::read('sale.state');
					echo $this->Form->select($key, array_combine( $state, $state));
					break;
				
				case 'time':
					echo $this->Form->input($key, ['type' => 'time', 'value' => $item->{$key}, 'label' => false, 'templates' => ['dateWidget' => $template_time]]);
					break;
				
				case 'user_name':
					echo $this->Form->text($key, [ 'value' => $item->{$key} , 'class'=>'autocomplete-users']);
					break;
				
				case 'client_name':
					echo $this->Form->text($key, [ 'value' => $item->{$key} , 'class'=>'autocomplete-clients']);
					break;
				
				default:
					echo $this->Form->text($key, [ 'value' => $item->{$key}]);
					break;
			}
			?>
		</div>
		<div class="text-right">
			<?= $this->Form->button('修正', ['type' => 'button','value'=>'edit']) ?>
			<?= $this->Form->button('キャンセル', ['type' => 'button','value'=>'cancel']) ?>
		</div>
	</div>
</div>