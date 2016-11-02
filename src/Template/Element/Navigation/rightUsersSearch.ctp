<?php
if ( !empty($search['user_id']) ) {
	$list_users = json_decode($search['user_id']);
} else {
	$list_users = [];
}
?>
<div class="panel panel-primary section-nav" url="<?= $this->Url->build(['controller' => 'sales', 'action' => 'index']) ?>">
	<div class="panel-heading">ユーザー絞込検索</div>
	<div class="panel-body">
		<?php foreach ($sections as $section_name => $section_members): ?>
			<?= h($section_name) ?>
			<ul class="members">
				<?php foreach ($section_members as $member_id => $member_name): ?>
					<li member_id="<?= $member_id ?>">
						<?php
						if (in_array($member_id, (array) $list_users)) {
							$class = 'btn btn-info face32';
						} else {
							$class = 'btn btn-default face32';
						}
						?>
						<button class=" <?= $class ?>">
							<?= $this->Element('face', ['id' => $member_id]) ?>
							<?= h($member_name) ?>
						</button>
					</li>
				<?php endforeach ?>
			</ul>		
		<?php endforeach ?>
	</div>
</div>
<?php $this->append('script') ?>
<?= $this->Html->script('searchUsers') ?>
<?php $this->end()?>