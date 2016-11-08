<?php
//読了状況　
//まとめてあればそれを参照、まとめリストが存在しなければテーブルに問い合わせ
if (isset($list_unread)) {
	$read = !(in_array($item->id, $list_unread));
} else {
	$read = $item->read;
}

//編集メニュー
$edit_text = '';

$edit_items = [
	[
		'label' => '<i class="fa fa-newspaper-o fa-fw"></i> ',
		'title' => '詳細閲覧',
		'url' => ['controller' => 'sales', 'action' => 'view', $item->id],
	],
];

if ($this->isAdmin() || $item->user_id == $this->getLoginUser()['id']) {

	$edit_items[] = [
		'label' => '<i class="fa fa-pencil fa-fw"></i> ',
		'title' => '最終報告を編集',
		'url' => ['controller' => 'sales', 'action' => 'edit', $item->id],
	];

	if ($item->flags == 'normal') {
		$draft_id = $item->getDraftId();
		if ($draft_id) {
			$edit_items[] = [
				'label' => '<i class="fa fa-reply fa-fw"></i> ',
				'title' => '下書きを編集',
				'url' => ['controller' => 'sales', 'action' => 'edit', $draft_id],
			];
		} else {
			$edit_items[] = [
				'label' => '<i class="fa fa-reply fa-fw"></i> ',
				'title' => '報告を追加',
				'url' => ['controller' => 'sales', 'action' => 'add', $item->root_id],
			];
		}
	}

	if ($item->flags == 'deleted') {
		$edit_items[] = [
			'label' => '<i class="fa fa-trash fa-fw"></i> ',
			'title' => '完全削除',
			'url' => ['controller' => 'sales', 'action' => 'deleteComplete', $item->root_id],
		];
	}else{
		$edit_items[] = [
			'label' => '<i class="fa fa-trash-o fa-fw"></i> ',
			'title' => 'ゴミ箱へ移動',
			'url' => ['controller' => 'sales', 'action' => 'deleteRoot', $item->root_id],
		];
	}
}
foreach ($edit_items as $edit) {
	$edit_text .= $this->Html->link($edit['label'], $edit['url'], ['escape' => false, 'title' => $edit['title'], 'class' => 'btn btn-default']) . ' ';
}

$cells = [
	[
		'key' => 'read',
		'text' => $read ? '' : '<i class="fa fa-exclamation fa-fw text-info"></i>',
		'value' => $read,
		'class' => 'flag text-center',
	],
	/*
	  [
	  'key' => 'view',
	  'text' => $this->Html->link('<i class="fa fa-newspaper-o fa-fw"></i>', ['controller' => 'sales', 'action' => 'view', $item->id], ['escape' => false , 'class'=>'btn btn-default']),
	  'class' => 'link text-center',
	  ],
	  [
	  'key' => 'boss_check',
	  'text' => $item->boss_check ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o fa-fw"></i>',
	  'class' => 'flag text-center',
	  ],
	  [
	  'key' => 'boss_check2',
	  'text' => $item->boss_check2 ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o fa-fw"></i>',
	  'class' => 'flag text-center',
	  ],
	  [
	  'key' => 'time',
	  'text' => !empty($item->time) ? $item->time->Format('H:i') : '',
	  'class' => 'data',
	  ],
	 */
	[
		'key' => 'user_name',
		'text' => "<a title='{$item->user_name}'>".$this->Element('face', ['id' => $item->user_id]).'</a>',
		'class' => 'face face36 text-center',
	],
	[
		'key' => 'date',
		'text' => !empty($item->date) ? $item->date->Format('y-m-d') : '',
		'class' => 'data',
	],
	[
		'key' => 'client_name',
		'text' => h($item->client_name),
		'class' => 'data trim16',
	],
	[
		'key' => 'charge_person',
		'text' => h($item->charge_person),
		'class' => 'data trim8',
	],
	[
		'key' => 'title',
		'text' => h($item->title),
		'class' => 'data trim16',
	],
	[
		'key' => 'result',
		'text' => h($item->result),
		'class' => 'data',
	],
	[
		'key' => 'state',
		'text' => h($item->state),
		'class' => 'data',
	],
	[
		'key' => 'report',
		'text' => h($item->report),
		'class' => 'data trim8',
	],
	[
		'key' => 'treatment',
		'text' => empty($item->treatment) ? '' : '<i class="fa fa-thumbs-o-up"></i>',
		'class' => 'flag uneditable text-center',
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
		if ( strpos( $cell['class'] , 'flag' ) !== false ) {
			$value = empty( $cell['value']) ? 0 : 1 ;
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
