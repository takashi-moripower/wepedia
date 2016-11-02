<?php 
$draft_id = $root->getDraftId();
?>

<div>
	<div class="btn-group" role="group">
		<?php if ( $draft_id): ?>
			<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'edit', $draft_id]) ?>" class="btn btn-default">
				下書きを編集
			</a>
		<?php else: ?>
			<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'add', $root->id]) ?>" class="btn btn-default">
				報告を追加
			</a>
		<?php endif ?>
		<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'deleteRoot', $root->id]) ?>" class="btn btn-default">
			スレッドを削除
		</a>
	</div>
</div>
<br>
