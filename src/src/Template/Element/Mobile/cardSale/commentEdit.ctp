<?php
$form_id = 'form-' . $sale_id . '-' . $comment_id;
?>
<tr class='comment-form' sale_id='<?= $sale_id ?>' comment_id='<?= $comment_id ?>' user_id='<?= $this->getLoginUser()['id']?>'>
	<td colspan="2">
		<?php
//		echo $this->Form->create(NULL, ['class' => 'form-inline', 'id' => $form_id , 'url' => ['controller'=>'mobile' , 'action'=>'comments' , 'edit' ]]);
//		echo $this->Form->hidden('sale_id',['value'=>$sale_id]);
//		echo $this->Form->hidden('id',['value'=>$comment_id]);
		echo $this->Form->text('text', ['class' => 'form-control','value'=>$text]);
//		echo $this->Form->end();
		?>
	</td>
	<td>
		<?php
		echo $this->Form->button('<i class="fa fa-pencil"></i>', ['class' => 'btn btn-default btn-sm btn-comment btn-comment-edit', 'escape' => false,'type'=>'button']);
//		echo $this->Form->button('<i class="fa fa-pencil"></i>', ['class' => 'btn btn-default btn-sm btn-comment btn-comment-edit', 'escape' => false , 'form'=>$form_id ]);
		?>
	</td>
</tr>