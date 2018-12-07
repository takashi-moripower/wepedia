<?php
//戻るボタン
$returnTo = [
	'一覧' => ['controller' => 'mobile', 'action' => 'sales'],
	'「'.h($demand->sale->title).'」詳細' => ['controller' => 'sales', 'action' => 'view', $demand->sale_id],
];

echo $this->Element('Demands/edit',compact('returnTo'));
?>
