<?php
if (empty($search['user_id'])) {
	$list_users = [];
} else {
	$list_users = json_decode($search['user_id']);
}
?>
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
