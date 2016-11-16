<?php
use Cake\Core\Configure;

$template_date = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><div class="separator hidden-lg"></div><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
$template_time = '<ul class="list-inline"><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';

$option_types = array_combine(Configure::read('demand.type'), Configure::read('demand.type'));
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
			<label for="type" class="col-xs-8 col-lg-4 control-label">要望区分</label>
			<div class="col-xs-16 col-lg-20">
				<?= $this->Form->select('type', $option_types) ?>
			</div>
		</div>
		<div class="form-group">
			<label for="demand" class="col-xs-8 col-lg-4 control-label">要望</label>
			<div class="col-xs-24 col-lg-20">
				<?= $this->Form->textArea('demand') ?>
			</div>
		</div>		
		<div class="form-group">
			<label for="product_name" class="col-xs-8 col-lg-4 control-label">商品名</label>
			<div class="col-xs-16 col-lg-20">
				<div class="input-group" style="width:100%">
					<?= $this->Form->text('product_name', ['readonly' => 'readonly', 'style' => 'background-color:transparent;']) ?>
					<div class="input-group-addon" name="product_name">編集</div>
				</div>			
			</div>
		</div>
		<div class="form-group collapse" id="product_selector">
			<?= $this->Element('Demands/productSelector') ?>
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

		$('.input-group-addon[name="product_name"]').on({
			click: function () {
				$('#product_selector').collapse('toggle');
			}
		});
	});
</script>
<?= $this->end() ?>