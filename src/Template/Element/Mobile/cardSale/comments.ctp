
<div class="card-comments card-ex<?= empty( $active ) ? '' : ' active'?>" ex-source="comments" root_id="<?= $item->root_id ?>">
	<table class="table table-bordered table-striped table-condensed view-sale-card-comments">
		<tbody>
			<?= $this->Element('Mobile/cardSale/commentsTree', ['comments' => $item->comments, 'depth' => 0]) ?>
		</tbody>
		<tbody>
			<tr class='comment-form ' sale_id='<?= $item->root_id ?>' comment_id='' user_id='<?= $this->getLoginUser()['id'] ?>' method='post'>
				<td colspan="2">
					<?php
					echo $this->Form->text('text', ['class' => 'form-control', 'value' => '']);
					?>
				</td>
				<td>
					<?php
					echo $this->Form->button('<i class="fa fa-share fa-rotate-270"></i>', ['class' => 'btn btn-default btn-sm btn-comment', 'escape' => false, 'type' => 'button']);
					?>
				</td>
			</tr>		
		</tbody>
	</table>
</div>
