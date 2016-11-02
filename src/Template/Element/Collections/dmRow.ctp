<?php
?>

<tr class="">
	<td class='col-xs-4 vertical-middle dm-label'>
		<?= $label ?>
	</td>
	<td class='text-right col-xs-2 vertical-middle'><?= $collection->total ?></td>
	<td class='text-right col-xs-2 vertical-middle'><?= $collection->no_follow ?></td>
	<td class='text-right col-xs-2 vertical-middle'><?= $collection->following ?></td>
	<td class='text-right col-xs-2 vertical-middle'><?= $collection->finished ?></td>
	<td class="col-xs-12 chart vertical-middle">
		<?= $this->Element('Mypage/dmChart', ['collection' => $collection]) ?>
	</td>
</tr>
