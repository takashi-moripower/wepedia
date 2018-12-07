<?php
use App\Defines\Defines;

//戻るボタン
$returnTo = [
	'一覧' => ['controller' => 'sales', 'action' => 'index']
];
if (!$sale->isNew()) {
	$returnTo += [
		"「{$sale->title}」詳細" => ['controller' => 'sales', 'action' => 'view', $sale->root_id]
	];
}
if ($sale->project_do == Defines::SALES_DO_DIRECTMAIL ) {
	$submit['DMフォロー'] = ['controller' => 'mypage', 'action' => 'directMail', $sale->root->user_id, $sale->root->date->format('Y-m-d')];
}

echo $this->Element('Sales/edit',compact('returnTo'));
?>