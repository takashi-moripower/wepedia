<?php
if (empty($title)) {
	//タイトル
	$title = getTitle($sale);
}

if (empty($submit)) {
	//	保存ボタン
	$submit = [
		'報告' => 'normal',
		'下書き' => 'unpublished'
	];
	if (!$sale->isNew()) {
		$submit['削除'] = 'deleted';
	}
}
?>
<?= $this->Form->create($sale) ?>
<div class="row">
	<div class="col-lg-12 col-lg-offset-6 col-md-18 col-md-offset-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2><?= h($title['main']) ?>　<small class="bg-primary"><?= h($title['sub']) ?></small></h2>
			</div>
			<div class="panel-body panel-body-padding2">
				<?= $this->Element($sale->isRoot() ? 'Sales/editRoot' : 'Sales/viewRoot', ['item' => $sale->root]) ?>

				<?= $this->Element('Sales/editNode') ?>
				<div class="text-center">
					<div class="btn-group" role="group">
						<?php foreach ($submit as $label => $flag): ?>
							<?= $this->Form->button($label, ['name' => 'flags', 'value' => $flag, 'class' => 'btn btn-default']) ?>
						<?php endforeach ?>
					</div>
					<div class="btn-group" role="group">
						<?php foreach ($returnTo as $label => $url): ?>
							<?= $this->Html->link($label . 'に戻る', $url, ['class' => 'btn btn-default']) ?>
						<?php endforeach ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?= $this->Form->end() ?>

<?php

//	タイトル、サブタイトル
function getTitle($sale) {
	if ($sale->isNew()) {
		if ($sale->isRoot()) {
			$title = [
				'main' => '新規スレッド',
				'sub' => NULL,
			];
		} else {
			$title = [
				'main' => '追加報告',
				'sub' => $sale->title
			];
		}
	} else {
		$title = [
			'main' => '編集',
			'sub' => $sale->title
		];

		switch ($sale->flags) {
			case 'unpublished':
				$title['sub'] .= '(下書き)';
				break;
			case 'deleted':
				$title['sub'] .= '(削除済み)';
				break;
		}
	}
	return $title;
}
