<ul class="comment-tree">
	<?php foreach ($comments as $comment): ?>
		<li>
			<div class="pop-wrap">
				<?= $this->Form->checkbox('response[' . $comment->id . ']', ['comment_id' => $comment->id, 'hiddenField' => false]) ?>
				<div class="popover left"> 
					<div class=arrow></div> 
					<div class=popover-content>
						<?= $comment->text ?>
					</div>
				</div>
				<a title="<?= $comment->user->name ?>" class="face48">
					<?= $this->Element('face', ['id' => $comment->user_id]) ?>
				</a>
			</div>
			<?php if ($comment->user_id == $this->getLoginUser()['id']): ?>
				<?= $this->Element('Sales/commentEdit', ['comment' => $comment]) ?>
			<?php else: ?>
				<?= $this->Element('Sales/commentPost', ['comment_id' => $comment->id]) ?>
			<?php endif ?>
			<?php if (!empty($comment->children)): ?>
				<div class="children">
					<?= $this->Element('Sales/commentTree', ['comments' => $comment->children]) ?>
				</div>
			<?php endif ?>
		</li>
	<?php endforeach; ?>
</ul>
