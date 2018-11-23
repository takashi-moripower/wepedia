<?php

$tabs = [
	[
		'label' => '営業報告',
		'action' => 'index',
	],
	[
		'label' => 'DM',
		'action' => 'directMail',
	],
	[
		'label' => '下書き',
		'action' => 'draft',
	],
	[
		'label' => 'ゴミ箱',
		'action' => 'trashbox',
	]
];
?>
<ul class="nav nav-tabs">
	<?php foreach ($tabs as $tab ): ?>
	<li role="presentation" <?= $this->isMatch('sales',$tab['action']) ? 'class="active"' : NULL ?> >
		<?= $this->Html->link($tab['label'],['controller'=>'sales','action'=>$tab['action'],'clear'=>true]) ?>
	</li>
	<?php endforeach ?>
</ul>
