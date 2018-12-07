<?php
//戻るボタン
$returnTo = [
	'一覧' => ['controller' => 'mobile', 'action' => 'sales']
];

echo $this->Element('Demands/edit',compact('returnTo'));
?>