<?php

use App\Defines\Defines;

$dd_id = $root->getDemandDraftId();
?>

<div class="row">
	<div class="col-lg-12">
		<h2>営業報告 <small><?= $root->title ?></small></h2>
		<?= $this->Element('Sales/viewRoot', ['item' => $root]) ?>
		<?= $this->Element('Sales/viewNav') ?>
		<?= $this->Element('Sales/viewNodes') ?>
	</div>
	<div class="col-lg-12">
		<h2>顧客の声</h2>
		<?= $this->Element('Demands/viewList', ['demands' => $root->demands]) ?>
		<div class="btn-group" role="group">
			<?php if ($dd_id): ?>
				<a href="<?= $this->Url->build(['controller' => 'demands', 'action' => 'edit', $dd_id]) ?>" class="btn btn-default">
					顧客の声(下書き)を編集
				</a>
			<?php else: ?>
				<a href="<?= $this->Url->build(['controller' => 'demands', 'action' => 'add', $root->id]) ?>" class="btn btn-default">
					顧客の声を追加
				</a>
			<?php endif ?>
		</div>
		<h2>評価</h2>
		<?= $this->Element('Sales/evaluation', ['root' => $root, 'latest' => $root->latest]) ?>
		<?= $this->Element('Sales/comments', ['comments' => $root->comments]) ?>
	</div>
</div>
<div class="text-center">
	<div class="btn-group" role="group">
		<?php if (isset($prev_id)): ?>
			<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'view', $prev_id]) ?>" class="btn btn-default">
				前の報告
			</a>
		<?php endif ?>
		<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'index',]) ?>" class="btn btn-default">
			一覧に戻る
		</a>
		<?php if ($root->project_do == Defines::SALES_DO_DIRECTMAIL): ?>
			<a href="<?= $this->Url->build(['controller' => 'mypage', 'action' => 'directMail', $root->user_id, $root->date->format('Y-m-d')]) ?>" class="btn btn-default">
				マイページに戻る
			</a>
		<?php endif; ?>
		<?php if (isset($next_id)): ?>
			<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'view', $next_id]) ?>" class="btn btn-default">
				次の報告
			</a>
		<?php endif ?>
	</div>
</div>
