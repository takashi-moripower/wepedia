<?php
$control_id = "card-comments-" . $item->id;
?>
<div id="<?= $control_id ?>" class="comments collapse" role="tabpanel">
	<table class="table table-bordered table-striped table-condensed view-sale-card view-sale-card-comments">
		<tbody>
			<?= $this->Element('Mobile/cardSale/commentsTree',['comments'=>$item->comments,'depth'=>0]) ?>
		</tbody>
		<tbody>
			<?= $this->Element('Mobile/cardSale/commentPost',['sale_id'=>$item->root_id,'comment_id'=>NULL]) ?>
		</tbody>
	</table>
</div>
<table class="table table-bordered table-striped table-condensed view-sale-card view-sale-card-comments">
	<tr>
		<th>
			<button class="btn btn-default btn-lg col-xs-24" type="button" data-toggle="collapse" data-target="#<?= $control_id ?>" aria-expanded="false" aria-controls="<?= $control_id ?>" data-parent=".view-sale-card[sale_id=\'<?= $item->id ?>\']">
				コメント (<?= $item->count_comments ?>)
			</button>
		</th>
	</tr>
</table>
