<?php foreach ($comments as $comment): ?>
	<tr sale_id='<?= $comment->sale_id ?>' comment_id='<?= $comment->id ?>'>
		<td class="check">
			<?= $this->Form->checkbox('response[' . $comment->id . ']', ['hiddenField' => false]) ?>
		</td>
		<td>
			<?php
			if ($depth >= 1) {
				echo str_repeat('&nbsp;', $depth - 1);
				echo "<i class='fa fa-reply fa-rotate-180'></i>";
			}
			?>
			<?= $comment->text ?>
		</td>
		<td class="face32 face">
			<?= $this->Element('face', ['id' => $comment->user_id]) ?>
		</td>
	</tr>
	<?php if ($this->getLoginUser()['id'] == $comment->user_id): ?>
		<?= $this->Element('Mobile/cardSale/commentEdit', ['sale_id' => $comment->sale_id, 'comment_id' => $comment->id, 'text' => $comment->text]) ?>
	<?php else: ?>
		<?= $this->Element('Mobile/cardSale/commentPost', ['sale_id' => $comment->sale_id, 'comment_id' => $comment->id]) ?>
	<?php endif ?>

	<?php
	if ($comment->children) {
		echo $this->Element('Mobile/cardSale/commentsTree', ['comments' => $comment->children, 'depth' => $depth + 1]);
	}
	?>
<?php endforeach ?>