<?php

use \App\Utils\AppUtility;

$menu_items = [
	[
		'action' => 'result',
		'label' => '売上'
	],
	[
		'action' => 'schedule',
		'label' => '2週間スケジュール'
	],
	[
		'action' => 'directMail',
		'label' => 'DMフォロー'
	],
	[
		'action' => 'follow',
		'label' => '継続中案件'. ( ( $user->hasPlans() ) ? '<span class="badge has-plan">予定あり</span>':'' )
	],
];
?>
<div class="panel panel-primary mypage-nav">
	<div class="panel-heading face48">
		<?= $this->Element('face', ['id' => $user['id']]) ?>
		<?= $user['name'] ?>
	</div>
	<div class="panel-body">
		<ul class="nav nav-pills nav-stacked">
			<?php
			foreach ($menu_items as $item):
				$url = ['controller' => 'mypage', 'action' => $item['action']];
				if ($this->getLoginUser()['id'] != $user->id) {
					$url += [$user->id];
				}
				?>
				<li role="presentation" class="<?= $this->isMatch('mypage', \App\Utils\AppUtility::snake($item['action'])) ? 'active' : '' ?>">
					<?= $this->Html->link($item['label'], $url,['escape'=>false]) ?>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>
<?php $this->append('css') ?>
<style>
	.badge.has-plan{
		background-color:#f15a24;
	}
</style>
<?php $this->end() ?>