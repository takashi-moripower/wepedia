<?php
$selected_products = explode(',', $demand->product_name);
?>


<div class="product-selector">
	<div class="col-xs-8">
		<div class="category-name active selected-item">選択済み</div>
		<?php foreach ($name_categories as $category): ?>
			<div class="category-name" category_name="<?= $category ?>"><?= $category ?></div>
		<?php endforeach ?>
	</div>
	<div class="col-xs-16">
		<?php
		foreach ($list_products as $product):
			$is_selected = in_array($product->name, $selected_products);
			?>
			<div class="product-name" category_name="<?= $product->category ?>" product_name="<?= $product->name ?>"><label for="product[<?= $product->id ?>]"><input id="product[<?= $product->id ?>]" type="checkbox" <?= $is_selected ? 'checked="checked"' : '' ?> /> <?= $product->name ?></label></div>
		<?php endforeach ?>
	</div>
</div>

<?php $this->append('css') ?>
<style>
	.product-selector .category-name{
		cursor:pointer;
	}
	.product-selector .category-name:hover{
		background-color:#eee;
	}
	.product-selector .category-name.active{
		background-color:#cee;
	}

	.product-selector .category-name:before{
		font-family:'FontAwesome';
		content:'\f114';
		padding-right:.5rem;
	}
	.product-selector .category-name.active:before{
		font-family:'FontAwesome';
		content:'\f115';
	}

	.product-selector .product-name{
		display:none;
	}
</style>
<?php $this->end() ?>
<?php $this->append('script') ?>
<script>
	$(function () {
		updateCategory();

		$('.product-selector .category-name').on('click', function () {
			$('.product-selector .category-name').removeClass('active');
			$(this).addClass('active');
			updateCategory();
		});

		$('.product-selector .product-name input[type="checkbox"]').on('change', function () {
			names = "";
			$('.product-selector .product-name input[type="checkbox"]').each(function (i, e) {
				if ($(e).prop('checked')) {
					n = $(e).parents('.product-name').attr('product_name');
					if (names !== "") {
						names += ',';
					}
					names += n;
				}
			});
			$('input[name="product_name"]').val(names);
		});
	});

	function updateCategory() {
		cat = getCategory();
		if (cat === '選択済み') {
			showChecked();
		} else {
			showCategory(cat);
		}
	}

	function getCategory() {
		return $('.product-selector .category-name.active').text();
	}

	function showChecked() {
		$('.product-selector .product-name').hide();
		$('.product-selector .product-name input[type="checkbox"]').each(function (i,e) {
			if ($(e).prop('checked')) {
				$(e).parents('.product-name').show();
			}
		});
	}

	function showCategory(cat) {
		$('.product-selector .product-name').hide();
		$('.product-selector .product-name[category_name="' + cat + '"]').show();
	}
</script>
<?php $this->end() ?>