<?php
$do0 = \Cake\Core\Configure::read('sale.project_do');
$do = array_combine($do0, $do0);

$action0 = \Cake\Core\Configure::read('sale.project_act');
$action = array_combine($action0, $action0);

$panel_class = \Cake\Core\Configure::read('Flags.Class')[$sale->flags];
?>

<div class="panel panel-<?= $panel_class ?> edit edit-sales-root">
	<div class="panel-heading">
		<h2 class="panel-title">基本情報</h2>
	</div>
	<div class="panel-body form-horizontal">
		<div class="form-group">
			<label for="title" class="col-xs-8 col-lg-4 control-label">件名</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->text('title') ?>
			</div>
		</div>		
		<div class="form-group">
			<label for="user_id" class="col-xs-8 col-lg-4 control-label">担当者名</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->select('user_id', $name_users) ?>
			</div>
		</div>
		<div class="form-group">
			<label for="client_name" class="col-xs-8 col-lg-4 control-label">得意先</label>
			<div class="col-xs-16 col-lg-8">
				<div class="input-group">
					<?= $this->Form->text('client_name') ?>
					<div class="input-group-addon" name="client_name">...</div>
				</div>
			</div>
			<div class="separator hidden-lg col-xs-24"></div>
			<label for="charge_person" class="col-xs-8 col-lg-4 control-label">得意先担当</label>
			<div class="col-xs-16 col-lg-8">
				<div class="input-group">
					<?= $this->Form->text('charge_person') ?>
					<div class="input-group-addon" name="charge_person">...</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="project_do" class="col-xs-8 col-lg-4 control-label">Do</label>
			<div class="col-xs-16 col-lg-8">
				<?= $this->Form->select('project_do', $do) ?>
			</div>
			<div class="separator hidden-lg col-xs-24"></div>
			<label for="project_act" class="col-xs-8 col-lg-4 control-label">Action</label>
			<div class="col-xs-16 col-lg-8">
				<?= $this->Form->select('project_act', $action) ?>
			</div>
		</div>
	</div>
</div>
<?php $this->append('script') ?>
<script>
	var MY_CLIENT_NAME = <?= $this->Json->safeEncode(array_values($name_my_clients)) ?>;
	var MY_CHARGE_NAME = <?= $this->Json->safeEncode(array_values($name_my_charges)) ?>;
	$(function () {
		setAutoComplete('client_name', MY_CLIENT_NAME);
		setAutoComplete('charge_person', MY_CHARGE_NAME);
	});

	function setAutoComplete(name, source) {
		obj = $('input[name="' + name + '"]');
		obj.autocomplete({
			source: source,
			autoFocus: true,
			delay: 500,
			minLength: 0
		});

		$('.input-group-addon[name="' + name + '"]').on('click',
				function (obj) {
					return function () {
						obj.autocomplete('search', '');
						obj.focus();
					};
				}(obj));
		return;

	}
</script>
<?php $this->end() ?>
