<?php
$NAME_FLAGS = \Cake\Core\Configure::read('Flags.Name');
$panel_class = \Cake\Core\Configure::read('Flags.Class')[$sale->flags];
?>

<div class="row">
	<div class="col-lg-12 col-lg-offset-6">
		<h2>営業報告 <small><?= $sale->title ?> (<?= $NAME_FLAGS[$sale->flags] ?>)</small></h2>
		<?= $this->Element('Sales/viewRoot', ['item' => $sale]) ?>
		<div class="panel panel-<?= $panel_class ?>">
			<div class="panel-heading">
				<h3 class="panel-title">報告</h3>
			</div>
			<div class="">
				<?= $this->Element('Sales/viewNode', ['item' => $sale]) ?>
			</div>
		</div>
		<div class="text-center">
			<div class="btn-group" role="group">
				<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'edit', $sale->id]) ?>" class="btn btn-default">
					編集
				</a>
				<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'index', 'clear' => 1]) ?>" class="btn btn-default">
					一覧に戻る
				</a>
			</div>
		</div>
	</div>
</div>
