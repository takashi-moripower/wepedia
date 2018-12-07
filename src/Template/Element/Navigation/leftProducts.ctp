<?php $category = NULL ?>

<div class="panel panel-primary product-nav">
	<div class="panel-heading">製品情報</div>
	<div class="panel-body">
		<ul class="nav nav-pills nav-stacked">
			<?php
			foreach ((array)$list_products as $product) :
				if ($category != $product->category) {
					if ($category != NULL) {
						echo '</ul>';
						echo '</li>';
					}
					$category = $product->category;
					echo '<li role="presentation" class="dropdown">';
					echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">';
					echo $category;
					echo '</a>';
					echo '<ul class="dropdown-menu dropdown-left">';
				}
				echo '<li>';
				echo '<a href="' . $product->url . '">';
				echo $product->name;
				echo '</a>';
				echo '</li>';
			endforeach;
			?>
		</ul>
		</li>　
		</ul>		


	</div>
</div>
