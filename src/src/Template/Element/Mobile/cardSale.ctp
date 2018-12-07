<?php

use Cake\I18n\Date;
use Cake\I18n\Time;

Date::setToStringFormat('yyyy-MM-dd');
Time::setToStringFormat('HH:mm');

$columns = [
	'相手担当' => h($item->charge_person),
	'手ごたえ' => h($item->state),
	'Action' => h($item->project_act),
	'Do' => h($item->project_do),
	'営業結果' => h($item->result),
	'対応' => h($item->treatment),
	'報告' => h($item->report),
];

if (!empty($item->next_date)) {
	$columns['次回予定'] = $item->next_date->format('Y-m-d');
}

if (empty($open)) {
	$open = false;
}
?>

<div class="panel panel-primary view-sale-card view-sale-card-<?= $item->id ?>" sale_id="<?= $item->id ?>">
	<div class="panel-heading face32 clearfix">
		<div class="row">
			<div class="col-xs-12">
				<?= $this->Element('face', ['id' => $item->user_id]) ?>
				<?= h($item->user->name) ?>
			</div>
			<div class="col-xs-12" style="line-height:32px">
				<?= h($item->date) ?>
				<?= h($item->time) ?>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<table class="table table-condensed">
			<tbody>
				<tr>
					<th class="col-xs-12 trim16"><?= h($item->title) ?></th>
					<th class="col-xs-12 trim16"><?= h($item->client_name) ?></th>
				</tr>
			</tbody>
		</table>
	</div>
	<div class='data-long collapse<?= $open ? ' in' : '' ?>'>
		<table class="table table-bordered table-striped table-condensed">
			<tbody>
				<?php foreach ($columns as $label => $value): ?>
					<tr>
						<th class="col-xs-6"><?= $label ?></th>
						<td class="col-xs-18"><?= $value ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?= $this->Element('Mobile/cardSale/evaluation', ['item' => $item]); ?>

		<div class="data-ex clearfix">
			<?php
			$buttons = [
				['target' => 'previous', 'icon' => 'newspaper-o', 'caption' => '過去の報告', 'count' => count($item->previous)],
				['target' => 'demands', 'icon' => 'comment-o', 'caption' => '顧客の声', 'count' => count($item->demands)],
				['target' => 'comments', 'icon' => 'user', 'caption' => 'コメント', 'count' => $item->count_comments],
			];
			?>
			<div>
				<?php
				foreach ($buttons as $button):
					extract($button);
					?>
					<?php if ($count || $target == 'comments' ): ?>
						<button class="btn btn-default btn-lg col-xs-8" target="<?= $target ?>">
							<i class="fa fa-<?= $icon ?>"></i> <span class="label label-info"><?= (int) ( $count ) ?></span>
							<div><?= $caption ?></div>
						</button>
					<?php else: ?>
						<div class="col-xs-8"></div>
					<?php endif ?>
				<?php endforeach ?>
			</div>
		</div>
		<?= $this->Element('Mobile/cardSale/previous', ['item' => $item]) ?>
		<?= $this->Element('Mobile/cardSale/demands', ['item' => $item]) ?>
		<?= $this->Element('Mobile/cardSale/comments', ['item' => $item]) ?>

	</div>
	<div class="panel-footer text-center">
		<div class="clearfix">
			<div class="col-xs-8 text-center">
				<?php if (!$item->read): ?>
					<span class="label label-info" style="font-size:100%;display:inline-block;margin-top:1rem">New!!</span>
				<?php endif ?>
			</div>
			<div>
				<button class="btn btn-default btn-lg col-xs-8 " type="button" data-toggle="collapse" data-target=".view-sale-card[sale_id='<?= $item->id ?>'] .data-long" aria-expanded="<?= $open ? 'true' : 'false' ?>" aria-controls="sale-card-long-<?= $item->id ?>">
					<i class="fa fa-search fa-lg"></i>
				</button>
			</div>
			<?= $this->Element('Mobile/cardSale/menu', ['item' => $item]) ?>
		</div>
	</div>
</div>