<?php
use Cake\I18n\Time;

Time::setToStringFormat('yyyy-MM-dd HH:mm');

$cells = [
	[
		'key' => 'user_name',
		'text' => h($item->user_name),
		'class' => 'data',
	],
	[
		'key' => 'text',
		'text' => h($item->text),
		'class' => 'data',
	],
	[
		'key' => 'url',
		'text' => h($item->url),
		'class' => 'data',
	],
	[
		'key' => 'modified',
		'text' => h($item->modified),
		'class' => 'fixed',
	],
	[
		'key'=>'action',
		'text' => $this->Html->link('<i class="fa fa-trash-o"></i>', ['controller'=>'informations','action'=>'delete',$item->id],['escape'=>false]),
		'class'=>'link'
	]
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
