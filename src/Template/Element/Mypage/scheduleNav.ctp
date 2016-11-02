<?php

use Cake\I18n\Date;
use App\Defines\Defines;

$week_days = Defines::WEEK_DAYS;

$date_previous = new Date($date);
$date_previous->subDay(14);

$date_next = new Date($date);
$date_next->addDay(14);

function getWeekLabel($date_start) {
	$date_end = new Date($date_start);
	$date_end->addDays(13);
	return $date_start->format('Y-m-d') . ' ～ ' . $date_end->format('Y-m-d');
}

$date_options = ['' => NULL];
for ($i = -70; $i < 70; $i+=14) {
	$d = new Date($date);
	$d->addDays($i);
	$date_options[$d->format('Y-m-d')] = getWeekLabel($d);
}
?>
<div class="row">
	<div class="col-xs-8">
		<?php
		echo $this->Html->link(
				'<i class="fa fa-angle-double-left"></i> ' . getWeekLabel($date_previous), ['controller' => 'mypage', 'action' => 'schedule', $user_id, $date_previous->format('Y-m-d')], ['class' => 'btn btn-default', 'escape' => false]
		);
		?>
	</div>
	<div class="col-xs-8 text-center">
		<?= $this->Form->select('date', $date_options, ['value' => $date, 'style' => 'width:auto', 'class' => 'center-block']) ?>
	</div>
	<div class="col-xs-8 text-right">
		<?php
		echo $this->Html->link(
				getWeekLabel($date_next) . ' <i class="fa fa-angle-double-right"></i> ', ['controller' => 'mypage', 'action' => 'schedule', $user_id, $date_next->format('Y-m-d')], ['class' => 'btn btn-default', 'escape' => false]
		);
		?>
	</div>
</div>
<br>
<?php $this->append('script') ?>
<script>
	$(function () {
		base_url = '<?= $this->url->build(['controller' => 'mypage', 'action' => 'schedule', $user_id]) ?>';
		$('select[name="date"').on({
			change: function () {
				date = $(this).val();
				url = base_url + '/' + date;
				console.log(url);
				$(location).attr('href', url);
			}
		});
	});
</script>
<?php $this->end() ?>