<?php
$cells = [
	'date',
	'target_total',
	'previous_total',
	'forecast_total',
	'result_total',
	'rate_total',
	'target_exist',
	'previous_exist',
	'forecast_exist',
	'result_exist',
	'rate_exist',
	'target_new',
	'previous_new',
	'forecast_new',
	'result_new',
	'rate_new',
];

foreach( ['total','exist','new'] as $key ){
	if( $item->{ 'target_'.$key } ){
		$item->{ 'rate_'.$key } = $item->{ 'result_'.$key } / $item->{ 'target_'.$key };
	}
}

?>

<tr item_id="<?= $item->id ?>">
	<?php
	foreach ($cells as $cell):
		if (is_string($cell)) {
			$cell = [
				'key' => $cell,
				'class' => 'data text-right',
			];

			if (!isset($item->{$cell['key']})) {
				$cell['key'] = 'sum_' . $cell['key'];
			}
			
			if( strpos( $cell['key'] , 'rate' ) === false ){
				if( is_numeric(  $cell['key']) ){
					$cell['text'] = number_format( $item->{$cell['key']} );
				}else{
					$cell['text'] = $item->{$cell['key']};
				}
			}else{
				$cell['text'] = sprintf('%0.1f', $item->{$cell['key']} * 100) . '%';
			}
		}
		?>
		<td key="<?= $cell['key'] ?>" class="<?= $cell['class'] ?>">
			<div class="text">
				<?= $cell['text'] ?>
			</div>
		</td>
	<?php endforeach ?>
</tr>
