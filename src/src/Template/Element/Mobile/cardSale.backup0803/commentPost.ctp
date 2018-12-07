<?php
$form_id = 'form-' . $sale_id . '-' . $comment_id;
?>
<tr class='comment-form' sale_id='<?= $sale_id ?>' comment_id='<?= $comment_id ?>'>
	<td colspan="2">
		<?php
		echo $this->Form->create(NULL, ['class' => 'form-inline', 'id' => $form_id, 'url' => ['controller' => 'mobile', 'action' => 'comments', 'add']]);
		echo $this->Form->hidden('sale_id', ['value' => $sale_id]);
		echo $this->Form->hidden('parent_id', ['value' => $comment_id]);
		echo $this->Form->hidden('user_id', ['value' => $this->getLoginUser()['id']]);
		echo $this->Form->text('text', ['class' => 'form-control']);
		echo $this->Form->end();
		?>
	</td>
	<td>
		<?php
		echo $this->Form->button('<i class="fa fa-share fa-rotate-270"></i>', ['class' => 'btn btn-default btn-sm btn-comment btn-comment-post', 'escape' => false, 'form' => $form_id]);
		?>
	</td>
</tr>