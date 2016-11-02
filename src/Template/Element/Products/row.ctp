<?php
$cells = [
	[
		'key' => 'name',
		'text' => h($item->name),
		'class' => 'data',
	],
	[
		'key' => 'product_order',
		'text' => h($item->product_order),
		'class' => 'data',
	],
	[
		'key' => 'category',
		'text' => h($item->category),
		'class' => 'data',
	],
	[
		'key' => 'category_order',
		'text' => h($item->category_order),
		'class' => 'data',
	],
	[
		'key' => 'user_name',
		'text' => h($item->user_name),
		'class' => 'data',
	],
	[
		'key' => 'url',
		'text' => h($item->url),
		'class' => 'data',
	],
];
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
