<?php
use Cake\Core\Configure;

$cells = [
	[
		'key' => 'user_name',
		'text' => h($item->user_name),
		'class' => 'data',
	],
	[
		'key' => 'date',
		'text' =>  $item->date,
		'class' => 'data text-center',
	],
	[
		'key' => 'type',
		'text' => Configure::read('result.type')[$item->type],
		'class' => 'data text-center',
	],
];
$titles = ['target','previous','forecast','result'];
$types = ['new','exist'];
foreach( $titles as $title ){
	foreach( $types as $type ){
		$key = $title . '_' . $type;
		$cells[] = [
			'key' => $key,
			'text' => $item->{$key},
			'class'=>'data text-right'
		];
	}
}
?>

<tr item_id="<?= $item->id ?>">
	<?php
	foreach ($cells as $cell):
		if( $cell['class'] == 'flag'){
			$value = isset( $cell['value'] ) ? $cell['value'] : $item->{$cell['key']};
			$attr_value = "value={$value}";
		}else{
			$attr_value = NULL;
		}
		?>
		<td key="<?= $cell['key'] ?>" class="<?= $cell['class'] ?>" <?= h($attr_value)?>>
			<div class="text">
				<?= $cell['text'] ?>
			</div>
			<?php
			switch ($cell['class']) {
				case'data':
					echo $this->Element('Sales/popupEdit', ['key' => $cell['key'], 'item' => $item]);
					break;
			}
			?>
		</td>
	<?php endforeach ?>
</tr>
