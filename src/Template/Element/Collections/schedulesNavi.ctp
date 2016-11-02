<?php

use Cake\I18n\Date;
use App\Defines\Defines;

$week_days = Defines::WEEK_DAYS;

$date_previous = new Date($date);
$date_previous->subDay(1);

$date_previous_start = new Date($date);
$date_previous_start->subDay(7);

$date_next = new Date($date);
$date_next->addDay(7);

function getWeekLabel($date_start) {
	$date_end = new Date($date_start);
	$date_end->addDays(6);
	return $date_start->format('Y-m-d') . ' ～ ' . $date_end->format('Y-m-d');
}

$date_options = [''=>NULL];
for( $i = -35 ; $i< 35 ; $i+=7 ){
	$d = new Date( $date );
	$d->addDays( $i );
	$date_options[ $d->format('Y-m-d') ] = getWeekLabel( $d ) ; 
}
?>
<div class="row">
	<div class="col-xs-8">
		<?php
		echo $this->Html->link(
				'<i class="fa fa-angle-double-left"></i> ' . $date_previous->format('Y-m-d'), ['controller' => 'collections', 'action' => 'schedules', $date_previous_start->format('Y-m-d')], ['class' => 'btn btn-default', 'escape' => false]
		);
		?>
	</div>
	<div class="col-xs-8 text-center">
		<?= $this->Form->select( 'date' , $date_options , ['value'=>$date , 'style'=>'width:auto' , 'class'=>'center-block']) ?>
	</div>
	<div class="col-xs-8 text-right">
		<?php
		echo $this->Html->link(
				$date_next->format('Y-m-d').' <i class="fa fa-angle-double-right"></i> ' , ['controller' => 'collections', 'action' => 'schedules', $date_next->format('Y-m-d')], ['class' => 'btn btn-default', 'escape' => false]
		);
		?>
	</div>
</div>
<br>
<?php $this->append('script') ?>
<script>
$(function(){
	base_url = '<?= $this->url->build(['controller'=>'collections','action'=>'schedules'])?>';
	$('select[name="date"').on({
		change:function(){
			date = $(this).val();
			url = base_url + '/' + date;
			console.log( url );
			$(location).attr('href', url );
		}
	});
});
</script>
<?php $this->end() ?>