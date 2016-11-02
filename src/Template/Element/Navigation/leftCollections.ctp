<?php
$menus = [
	[
		'label' => '売上',
		'url'=> [
			'controller'=>'collections',
			'action'=>'results',
			'clear'=>true
		],
	],
	[
		'label' => 'スケジュール',
		'url'=> [
			'controller'=>'collections',
			'action'=>'schedules',
		],
	],
	[
		'label' => 'DMフォロー',
		'url'=> [
			'controller'=>'collections',
			'action'=>'directMails',
		],
	],
	[
		'label' => '継続中案件',
		'url'=> [
			'controller'=>'collections',
			'action'=>'follows',
		],
	],
];
?>
<div class="panel panel-primary management-nav">
	<div class="panel-heading clearfix">
		<h3 class="panel-title pull-left">集計</h3>
	</div>
	<div class="panel-body" id="left-nav-collections">
		<ul class="nav nav-pills nav-stacked">
			<?php
			foreach ($menus as $menu):
				$class = $this->isMatch('collections',$menu['url']['action']) ? 'active' : '';
				?>
				<li role="presentation" class="<?= $class ?>">
					<?= $this->Html->link( $menu['label'] , $menu['url'] ) ?>
				</li>
				<?php
			endforeach;
			?>
		</ul>
	</div>
</div>
