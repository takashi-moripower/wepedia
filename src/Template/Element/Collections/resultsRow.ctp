<?php
$url = [
	'controller' => 'mypage',
	'action' => 'result',
	$item->user_id,
	is_string($search['date']['start']) ? $search['date']['start'] : $search['date']['start']->format('Y-m-d'),
	is_string($search['date']['end']) ? $search['date']['end'] : $search['date']['end']->format('Y-m-d'),
];

$cells = [
	[
		'key' => 'user_name',
		'text' => $this->Html->link($item->user->name, $url),
		'class' => 'data',
	],
	'sum_target_total',
	'sum_previous_total',
	'sum_forecast_total',
	'sum_result_total',
	[
		'key' => 'sum_rate_total',
		'text' => sprintf('%0.1f', $item->sum_rate_total * 100) . '%',
		'class' => 'data text-right'
	],
	'sum_target_exist',
	'sum_previous_exist',
	'sum_forecast_exist',
	'sum_result_exist',
	[
		'key' => 'sum_rate_exist',
		'text' => sprintf('%0.1f', $item->sum_rate_exist * 100) . '%',
		'class' => 'data text-right'
	],
	'sum_target_new',
	'sum_previous_new',
	'sum_forecast_new',
	'sum_result_new',
	[
		'key' => 'sum_rate_new',
		'text' => sprintf('%0.1f', $item->sum_rate_new * 100) . '%',
		'class' => 'data text-right'
	],
];
?>

<tr item_id="<?= $item->id ?>">
	<?php
	foreach ($cells as $cell):
		if (is_string($cell)) {
			$cell = [
				'key' => $cell,
				'text' => h(number_format($item->{$cell})),
				'class' => 'data text-right',
			];
		}
		?>
		<td key="<?= $cell['key'] ?>" class="<?= $cell['class'] ?>">
			<div class="text">
	<?= $cell['text'] ?>
			</div>
		</td>
<?php endforeach ?>
</tr>
