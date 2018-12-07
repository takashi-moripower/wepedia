<div class="panel panel-default" comment_id="<?= $comment_id ?>">
	<div class="panel-body">	
		<?=
		$this->Form->create(NULL, [
			'url' => $this->Url->build(['controller' => 'comments', 'action' => 'add', $root->id, $comment_id], true),
			'class' => 'form-comment',
			'comment_id' => $comment_id])
		?>
		<?= $this->Form->input('text', ['type' => 'text', 'label' => false]) ?>
		<?= $this->Form->submit(($comment_id == 'root') ? 'コメントを追加' : 'コメントに返信する', ['class' => 'pull-right']) ?>
		<?= $this->Form->end() ?>
	</div>
</div>
