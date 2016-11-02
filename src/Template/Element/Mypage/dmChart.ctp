<?php

use App\Defines\Defines;

$follow_name = Defines::SALES_FOLLOW_NAME;

$charts1 = [
	[
		'key' => 'following',
		'color' => 'warning',
		'rate' => ($collection->following + $collection->finished) / $collection->total * 100,
	],
/*
	[
		'key' => 'no_follow',
		'color' => 'danger',
		'rate' => $collection->no_follow / $collection->total * 100,
	],
 */
];
$charts2 = [
	[
		'key' => 'finished',
		'color' => 'success',
		'rate' => $collection->finished / $collection->total * 100,
	],
/*	
	[
		'key' => 'no_follow',
		'color' => 'danger',
		'rate' => ($collection->no_follow + $collection->following ) / $collection->total * 100,
	],
 * 
 */
];
?>

<div class="col-xs-3">
	フォロー率
</div>
<div class="col-xs-9">
	<div class="progress">
		<?php
		foreach ($charts1 as $chart):
			if ($chart['rate'] == 0) {
				continue;
			}
			?>
			<div class="progress-bar progress-bar-<?= $chart['color'] ?>" style="width: <?= $chart['rate'] ?>%">
				<?= sprintf('%2.2f', $chart['rate']) ?> %
			</div>
		<?php endforeach ?>
	</div>
</div>
<div class="col-xs-3">
	達成率
</div>
<div class="col-xs-9">
	<div class="progress">
		<?php
		foreach ($charts2 as $chart):
			if ($chart['rate'] == 0) {
				continue;
			}
			?>
			<div class="progress-bar progress-bar-<?= $chart['color'] ?>" style="width: <?= $chart['rate'] ?>%">
				<?= sprintf('%2.2f', $chart['rate']) ?> %
			</div>
		<?php endforeach ?>
	</div>
</div>
