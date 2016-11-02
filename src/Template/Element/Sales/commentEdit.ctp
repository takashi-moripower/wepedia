<div class="panel panel-default" comment_id="<?= $comment->id ?>">
	<div class="panel-body">	
		<?=
		$this->Form->create(NULL, [
			'url' => $this->Url->build(['controller' => 'comments', 'action' => 'edit', $comment->id], true),
			'class' => 'form-comment',
			'comment_id' => $comment->id])
		?>
		<?= $this->Form->input('text', ['type' => 'text', 'label' => false,'value'=>$comment->text]) ?>
		<?= $this->Form->submit( 'コメントを修正する', ['class' => 'pull-right']) ?>
		<?= $this->Form->end() ?>
	</div>
</div>
