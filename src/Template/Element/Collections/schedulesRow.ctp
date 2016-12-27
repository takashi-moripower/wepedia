<?php
use Cake\I18n\Date;

$diff = ( $date->toUnixString() - $item->date->toUnixString() ) / DAY;
?>

<tr>
	<td class="face face48 col-xs-3">
		<a href="<?= $this->Url->build(['controller' => 'mypage', 'action' => 'schedule', $item->user_id, $item->date->format('Y-m-d')]) ?>" title="<?= $item->user->name ?>">
			<?= $this->Element('face', ['id' => $item->user_id]) ?>
			<br>
			<?= h($item->user->name) ?>
		</a>
	</td>
	<?php
	for ($i = 1; $i <= 7; $i++):
		$key = sprintf('plan%02d', $diff + $i);
		$d = new Date($date);
		$d->addDays($i - 1);
		?>
		<td class="col-xs-3">
			<div>
				<?= nl2br(h($item->{$key})) ?>
			</div>
			<div>
				<?= $this->Element('Mypage/plans', ['date' => $d , 'user'=>$item->user]) ?>
			</div>
		</td>
	<?php endfor ?>
</tr>

