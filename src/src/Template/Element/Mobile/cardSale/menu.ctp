<?php
$menu = [];

$draft_id = $item->getDraftId();
if ($draft_id) {
	$menu += [
		'<i class="fa fa-reply"></i> 下書きを編集' => ['controller' => 'mobile', 'action' => 'sales', 'edit', $draft_id],
	];
} else {
	$menu += [
		'<i class="fa fa-reply"></i> 報告を追加' => ['controller' => 'mobile', 'action' => 'sales', 'add', $item->root_id],
	];
}

$menu += [
	'<i class="fa fa-pencil"></i> 最終報告を編集' => ['controller' => 'mobile', 'action' => 'sales', 'edit', $item->id],
];

$dd_id = $item->root->getDemandDraftId();
if ($dd_id) {
	$menu += [
		'<i class="fa fa-comment-o"></i> 顧客の声(下書き)を編集' => ['controller' => 'mobile', 'action' => 'demands', 'edit', $dd_id],
	];
} else {
	$menu += [
		'<i class="fa fa-comment-o"></i> 顧客の声を追加' => ['controller' => 'mobile', 'action' => 'demands', 'add', $item->root_id],
	];
}
?>

<div class="dropup">
	<button class="btn btn-default btn-lg dropdown-toggle col-xs-8" type="button" id="dropdownMenu<?= $item->id ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fa fa-pencil fa-lg"></i>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $item->id ?>">
		<?php
		foreach ($menu as $label => $url) {
			echo '<li>' . $this->Html->link($label, $url, ['escape' => false]) . '</li>';
		}
		?>
	</ul>
</div>			
