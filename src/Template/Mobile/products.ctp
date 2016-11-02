<div class="panel-group" id="categories" role="tablist" aria-multiselectable="true">
	<?php foreach ($name_categories as $cat_id => $category): ?>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="cat<?= $cat_id ?>">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#categories" href="#prd<?= $cat_id ?>" aria-expanded="true" aria-controls="prd<?= $cat_id ?>">
						<?= $category ?>
					</a>
				</h4>
			</div>
			<div id="prd<?= $cat_id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cat<?= $cat_id ?>">
				<div class="panel-body">
					<ul class="nav nav-pills nav-stacked">
						<?php foreach ($list_products as $product): ?>
							<?php
							if ($product->category != $category) {
								continue;
							}
							?>
						<li><a href="<?= $product->url ?>" class="btn btn-default" style="text-align:left;white-space: normal"><?= h($product->name) ?></a></li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>

