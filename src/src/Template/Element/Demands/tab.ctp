<?php

$tabs = [
	[
		'label' => '顧客の声',
		'action' => 'index',
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
	<li role="presentation" <?= $this->isMatch('demands',$tab['action']) ? 'class="active"' : NULL ?> >
		<?= $this->Html->link($tab['label'],['controller'=>'demands','action'=>$tab['action'],'clear'=>true]) ?>
	</li>
	<?php endforeach ?>
</ul>
