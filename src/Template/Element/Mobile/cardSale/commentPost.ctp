<?php
if( $comment->user_id == $this->getLoginUser()['id']){
	$method = 'edit';
	$text = $comment->text;
	$icon = '<i class="fa fa-pencil"></i>';
}else{
	$method = 'response';
	$text = '';
	$icon = '<i class="fa fa-share fa-rotate-270"></i>';
}
?>
<tr class='comment-form ' sale_id='<?= $comment->sale_id ?>' comment_id='<?=$comment->id?>' method='<?=$method ?>'>
	<td colspan="2">
		<?php
		echo $this->Form->text('text', ['class' => 'form-control','value'=>$text]);
		?>
	</td>
	<td>
		<?php
		echo $this->Form->button($icon, ['class' => 'btn btn-default btn-sm btn-comment', 'escape' => false,'type'=>'button']);
		?>
	</td>
</tr>