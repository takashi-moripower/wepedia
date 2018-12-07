<?php
if (isset($list_unread)) {
	$read = !(in_array($item->id, $list_unread));
} else {
	$read = $item->read;
}

$edit_text = '';

$edit_text .= $this->Html->link('<i class="fa fa-newspaper-o fa-fw"></i>', ['controller' => 'sales', 'action' => 'view', $item->sale_id], ['escape' => false , 'class'=>'btn btn-default','title'=>'閲覧']);
$edit_text .= " ";


if ($item->user_id == $this->getLoginUser()['id'] || $this->isAdmin()) {
	$edit_text .= $this->Html->link('<i class="fa fa-pencil fa-fw"></i>', ['controller' => 'demands', 'action' => 'edit', $item->id], ['escape' => false, 'class' => 'btn btn-default','title'=>'編集']);
	$edit_text .= " ";
	if ($item->flags == 'deleted') {
		$edit_text .= $this->Html->link('<i class="fa fa-trash fa-fw"></i>', ['controller' => 'demands', 'action' => 'deleteComplete', $item->id], ['escape' => false, 'class' => 'btn btn-default','title'=>'完全削除']);
	} else {
		$edit_text .= $this->Html->link('<i class="fa fa-trash-o fa-fw"></i>', ['controller' => 'demands', 'action' => 'delete', $item->id], ['escape' => false, 'class' => 'btn btn-default','title'=>'ゴミ箱へ移動']);
	}
}

$cells = [
	[
		'key' => 'read',
		'text' => $read ? '' : '<i class="fa fa-exclamation fa-fw text-info"></i>',
		'value' => $read,
		'class' => 'flag text-center',
	],
	[
		'key' => 'user_name',
		'text' => "<a title='{$item->user_name}'>".$this->Element('face', ['id' => $item->user_id]).'</a>',
		'class' => 'face face36 text-center',
	],
	[
		'key' => 'date',
		'text' => !empty($item->date) ? $item->date->Format('y-m-d') : '',
		'class' => 'data text-center',
	],
	[
		'key' => 'type',
		'text' => h($item->type),
		'class' => 'data',
	],
	[
		'key' => 'client_name',
		'text' => h($item->client_name),
		'class' => 'data trim8',
	],
	[
		'key' => 'product_category',
		'text' => h($item->product_category),
		'class' => 'data trim16',
	],
	[
		'key' => 'product_name',
		'text' => h($item->product_name),
		'class' => 'data trim16',
	],
	[
		'key' => 'demand',
		'text' => h($item->demand),
		'class' => 'data trim16',
	],
	[
		'key' => 'edit',
		'text' => $edit_text,
		'class' => 'link',
	],
];
?>

<tr item_id="<?= $item->id ?>">
	<?php
	foreach ($cells as $cell):
		if ($cell['class'] == 'flag') {
			$value = isset($cell['value']) ? $cell['value'] : $item->{$cell['key']};
			$attr_value = "value={$value}";
		} else {
			$attr_value = NULL;
		}
		?>
		<td key="<?= $cell['key'] ?>" class="<?= $cell['class'] ?>" <?= h($attr_value) ?>>
			<div class="text">
				<?= $cell['text'] ?>
			</div>
			<?php
			if (strpos($cell['class'], 'data') !== false) {
				echo $this->Element('Sales/popupEdit', ['key' => $cell['key'], 'item' => $item]);
			}
			?>
		</td>
	<?php endforeach ?>
</tr>
