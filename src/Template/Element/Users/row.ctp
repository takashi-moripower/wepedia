<?php
$cells = [
	[
		'key' => 'face',
		'text' => $this->Element('face',['id'=>$item->id]),
		'class' => 'face face48',
	],
	[
		'key' => 'name',
		'text' => h($item->name),
		'class' => 'data text-center',
	],
	[
		'key' => 'section',
		'text' => h($item->section),
		'class' => 'data text-center',
	],
	[
		'key' => 'position',
		'text' => h($item->position),
		'class' => 'data text-center',
	],
	[
		'key' => 'tel',
		'text' => h($item->tel),
		'class' => 'data text-center',
	],
	[
		'key' => 'email',
		'text' => h($item->email),
		'class' => 'data text-center',
	],
	[
		'key' => 'last_login',
		'text' => !empty($item->last_login) ? $item->last_login->Format('y-m-d H:i') : '',
		'class' => 'data text-center',
	],
	[
		'key' => 'action',
		'text' => $this->Html->link('<i class="fa fa-pencil"></i>',['controller'=>'users','action'=>'edit',$item->id],['escape'=>false]),
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
			if( strpos( $cell['class'] , 'data' )!==false ){
				echo $this->Element('Sales/popupEdit', ['key' => $cell['key'], 'item' => $item]);
			}
			?>
		</td>
	<?php endforeach ?>
</tr>
