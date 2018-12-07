<div class="panel panel-primary view">
	<div class="panel-heading">
		<h3 class="panel-title">最新の報告</h3>
	</div>
	<?= $this->Element('Sales/viewNode', ['item' => $root->latest]) ?>
</div>

<?php if (!empty($root->previous)): ?>
	<div class="panel panel-primary view">
		<div class="panel-heading clearfix">
			<h3 class="panel-title pull-left">過去の報告　<?= count($root->previous) ?>件</h3>
			<button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="collapse" data-target="#collapse-nodes" aria-expanded="false" aria-controls="collapse-nodes">
				<i class="fa fa-caret-down"></i>
			</button>		
		</div>
		<div class="collapse" id="collapse-nodes">
			<?php foreach ($root->previous as $node): ?>
				<?= $this->Element('Sales/viewNode', ['item' => $node]) ?>
			<?php endforeach ?>
		</div>
	</div>
<?php endif; ?>
