<?php
$count = 0;
foreach ($sales as $sale) {
	echo $this->Element('Mobile/cardSale', ['item' => $sale]);
	$count ++;
}

if( $count ):
?>
<div class="loader text-center panel panel-default" loading="0" offset="<?= $newOffset ?>" url="<?= $this->Url->build(['controller' => 'mobile', 'action' => 'sales', 'load', 'offset' => $newOffset]) ?>">
	<div class="panel-body">
		<i class="fa fa-refresh fa-spin fa-2x"></i> Now Loading
	</div>
</div>
<?php else:
?>
<div class="panel panel-default">
	<div class="panel-body text-center">
		End of Data
	</div>
</div>
<?php
endif;
