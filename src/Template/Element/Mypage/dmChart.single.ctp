<?php
use App\Defines\Defines;

$follow_name = Defines::SALES_FOLLOW_NAME;

$charts = [
	[
		'key' => 'no_follow',
		'color' => 'danger',
		'label' => $follow_name[Defines::SALES_FOLLOW_NO_FOLLOW],
		'rate' => $collection->no_follow / $collection->total * 100,
	],
	[
		'key' => 'following',
		'color' => 'warning',
		'label' => $follow_name[Defines::SALES_FOLLOW_FOLLOWING],
		'rate' => $collection->following / $collection->total * 100,
	],
	[
		'key' => 'finished',
		'color' => 'success',
		'label' => $follow_name[Defines::SALES_FOLLOW_FINISHED],
		'rate' => $collection->finished / $collection->total * 100,
	],
		]
?>

<div class="progress">
	<?php
	foreach ($charts as $chart):
		if ($chart['rate'] == 0) {
			continue;
		}
		?>
		<div class="progress-bar progress-bar-<?= $chart['color'] ?>" style="width: <?= $chart['rate'] ?>%">
			<?= $chart['label'] ?><br>
			<?= sprintf( '%2.2f'  , $chart['rate'] )?> %
		</div>
	<?php endforeach ?>
</div>

<?php /*$this->append('css') ?>
<style>
	.progress {
		height:4rem;
		margin-bottom:0;
	}
</style>
<?php $this->end() */?>