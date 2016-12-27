<?php
$plans = $user->getPlans($date)
		->toArray();
if( empty( $plans )){
	return;
}


foreach( $plans as $sale ){
	echo $this->Html->link( '<i class="fa fa-newspaper-o"></i> '. h($sale->client_name) , ['controller'=>'sales','action'=>'view',$sale->id,'s'],['escape'=>false]);
	echo "<br>";
}