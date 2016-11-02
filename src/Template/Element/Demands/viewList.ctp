<div class="panel panel-primary">
	<div class="panel-heading clearfix">
		<h3 class="panel-title pull-left">顧客の声　<?= count($demands) ?>件</h3>
		<button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="collapse" data-target="#collapse-demands" aria-expanded="false" aria-controls="collapse-demands">
			<i class="fa fa-caret-down"></i>
		</button>		
	</div>
	<div class="collapse" id="collapse-demands">
		<?php foreach ($demands as $demand): ?>
			<?= $this->Element('Demands/viewSingle', ['item' => $demand]) ?>
		<?php endforeach ?>
	</div>
</div>
