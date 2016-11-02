<?php
$template_date = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><div class="separator hidden-lg"></div><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
$template_time = '<ul class="list-inline"><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';

$TYPE = \Cake\Core\Configure::read('demand.type');
$option_types = array_combine($TYPE, $TYPE);
$cat = $name_categories->toArray() + ['複合'];
$option_categories = array_combine($cat, $cat);

$panel_class = \Cake\Core\Configure::read('Flags.Class')[$demand->flags];
if ($demand->flags != 'normal') {
	$flag_name = '(' . \Cake\Core\Configure::read('Flags.Name')[$demand->flags] . ')';
} else {
	$flag_name = NULL;
}
?>

<?= $this->Form->create($demand) ?>
<div class="panel panel-<?= $panel_class ?> edit form-edit form-edit-node">
	<div class="panel-heading">
		<h2 class="panel-title"> 顧客の声　<?= h($flag_name) ?></h2>
	</div>
	<div class="panel-body form-horizontal">
		<div class="form-group">
			<label for="time" class="col-xs-8 col-lg-4 control-label">日時</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->input('date', ['type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => $template_date]]); ?>
				<div class="separator hidden-lg col-xs-24"></div>
				<?= $this->Form->input('time', ['type' => 'time', 'label' => false, 'templates' => ['dateWidget' => $template_time]]); ?>
			</div>
		</div>		
		<div class="form-group">
			<label for="agent_id" class="col-xs-8 col-lg-4 control-label">要望区分</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->select('type', $option_types) ?>
			</div>
		</div>
		<div class="form-group">
			<label for="agent_id" class="col-xs-8 col-lg-4 control-label">商品区分</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->select('product_category', $option_categories) ?>
			</div>
		</div>
		<div class="form-group">
			<label for="agent_id" class="col-xs-8 col-lg-4 control-label">商品名</label>
			<div class="col-xs-16 col-lg-20">
				<div class="input-group" style="width:100%">
					<?= $this->Form->text('product_name') ?>
					<div class="input-group-addon" name="product_name">...</div>
				</div>			
			</div>
		</div>
		<div class="form-group">
			<label for="demand" class="col-xs-8 col-lg-4 control-label">要望</label>
			<div class="col-xs-24 col-lg-20">
				<?= $this->Form->textArea('demand') ?>
			</div>
		</div>		
	</div>
</div>
<?= $this->Form->end() ?>

<?= $this->append('script') ?>
<?php
$categories = [];
foreach ($list_products as $product) {
	$categories[$product->category][] = $product->name;
	$categories['all'][] = $product->name;
}
?>
<script>
	var CATEGORIES = <?= $this->Json->safeEncode($categories) ?>;

	$(function () {
		$('input[name="product_name"]').autocomplete({
			source: CATEGORIES['all'],
			autoFocus: true,
			delay: 500,
			minLength: 0
		});

		$('select[name="product_category"]').on({
			change: function () {
				cat = $(this).val();
				console.log(cat);
				source = CATEGORIES['all'];
				for (key in CATEGORIES) {
					if (key == cat) {
						source = CATEGORIES[key];
					}
				}
				$('input[name="product_name"]').autocomplete("option", {source: source});
			}
		});

		$('.input-group-addon[name="product_name"]').on({
			click: function () {
				obj = $('input[name="product_name"]');
				obj.autocomplete('search', '');
				obj.focus();
			}
		});
	});
</script>
<?= $this->end() ?>