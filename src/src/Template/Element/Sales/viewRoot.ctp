<?php
$panel_class = \Cake\Core\Configure::read('Flags.Class')[$item->flags];
?>
<div class="panel panel-<?= $panel_class ?> vier view-sales-root">
	<div class="panel-heading">
		<h2 class="panel-title">基本情報</h2>
	</div>
	<table class="table table-striped">
		<tbody>
			<tr>
				<th class="col-lg-4 col-xs-8">件名</th><td class="col-lg-20 col-xs-16" colspan="3"><?= h($item->title) ?></td>
			</tr>
			<tr>
				<th class="col-lg-4 col-xs-8">営業担当</th><td class="col-lg-20 col-xs-16" colspan="3"><?= h($item->user->name) ?></td>
			</tr>
			<tr class="hidden-lg">
				<th class="col-xs-4">得意先</th><td class="col-xs-20"><?= h($item->client_name) ?></td>
			</tr>
			<tr class="hidden-lg">
				<th class="col-xs-4 ">得意先担当</th><td class="col-xs-20"><?= h($item->charge_person) ?></td>
			</tr>
			<tr class="hidden-lg">
				<th class="col-xs-4">Do</th><td class="col-xs-20"><?= h($item->project_do) ?></td>
			</tr>
			<tr class="hidden-lg">
				<th class="col-xs-4">Action</th><td class="col-xs-20"><?= h($item->project_act) ?></td>
			</tr>
			<tr class="hidden-xs hidden-sm hidden-md">
				<th class="col-lg-4 col-xs-8">得意先</th><td class="col-lg-8"><?= h($item->client_name) ?></td>
				<th class="col-lg-4 col-xs-8 ">得意先担当</th><td class="col-lg-8"><?= h($item->charge_person) ?></td>
			</tr>
			<tr class="hidden-xs hidden-sm hidden-md">
				<th class="col-lg-4 col-xs-8">Do</th><td class="col-lg-8"><?= h($item->project_do) ?></td>
				<th class="col-lg-4 col-xs-8">Action</th><td class="col-lg-8"><?= h($item->project_act) ?></td>
			</tr>
		</tbody>
	</table>
</div>			
