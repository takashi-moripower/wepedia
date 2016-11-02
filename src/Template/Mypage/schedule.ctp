<?php

use Cake\I18n\Date;
?>

<?= $this->Element('Mypage/scheduleNav', ['date' => $schedule->date, 'user_id' => $schedule->user_id]) ?>


<?= $this->Form->create($schedule) ?>
<?= $this->Form->hidden('id') ?>
<?= $this->Form->hidden('user_id') ?>
<?= $this->Form->hidden('date') ?>
<div class="panel panel-primary edit edit-schedule">
	<div class="panel-heading">
		<h2>2週間スケジュール</h2>
	</div>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th class="col-xs-3">当月主要業務</th>
				<td class="col-xs-21"><?= $this->Form->textArea('work') ?></td>
			</tr>
			<tr>
				<th>2週間の目標</th>
				<td><?= $this->Form->textArea('target') ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="panel panel-primary edit edit-schedule">
	<table class="table table-bordered">
		<tbody>
			<?php
			for ($i = 1; $i <= 13; $i++):
				$d = new Date($schedule->date);
				$d->addDays($i - 1);
				$key = sprintf('plan%02d', $i);
				?>
				<tr>
					<th class="col-xs-3">
						<?= $d->format('n月j日') ?>
			<div>
				<?= $this->Element('Mypage/plans', ['date' => $d]) ?>
			</div>
			</th>
			<td class="col-xs-21">
						<?= $this->Form->textArea($key) ?>
			</td>
			</tr>
		<?php endfor ?>
		<tr>
			<td colspan="2">
				<?= $this->Form->button('保存') ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>

<?= $this->Form->end() ?>
<?php $this->append('css') ?>
<style>
	.panel.edit textarea.form-control{
		min-height:6rem;
		height:6rem;
	}
</style>
<?php $this->end() ?>