<?php
$limit = \Cake\Core\Configure::read('Index.Limit');

$list_selectors = [
	'read' => [
		'' => '読了状況',
		0 => '未読',
		1 => '既読'
	],
	'boss_check' => [
		'' => '部長確認',
		0 => '未確認',
		1 => '確認済'
	],
	'boss_check2' => [
		'' => 'マネ確認',
		0 => '未確認',
		1 => '確認済'
	],
	'date' => \Cake\Core\Configure::read('Index.Date.Select'),
	'product_category' => ['' => '商品区分'] + array_combine($name_categories->toArray(), $name_categories->toArray()),
];
?>
<?= $this->Form->create(NULL, ['class' => 'search-form search-form-demands form-inline text-right', 'session_key' => 'WEPEDIA_SEARCH_SALES']) ?>
<?= $this->Form->hidden('search', ['value' => true]) ?>
<?= $this->Form->hidden('user_id', ['value' => isset($search['user_id']) ? $search['user_id'] : NULL]) ?>
<div class="line">
	<div class="popover top">
		<div class="arrow"></div>
		<div class="popover-content">
			検索条件が変更されました<br>
			リストに反映させるには<br>
			検索ボタンを押してください
		</div>
	</div>
	<?= $this->Form->button('<i class="fa fa-search"></i>', ['type' => 'submit', 'escape' => false,'class'=>'search']) ?>
	<?= $this->Form->select('limit', $limit['Select'], ['default' => isset($search['limit']) ? $search['limit'] : $limit['Default']]) ?>
	<?= $this->Form->text('client_name', ['placeHolder' => '得意先名', 'class' => 'autocomplete-clients']) ?>
	<a href="<?= $this->Url->build(['clear' => true]) ?>">
		<button type="button" class="btn btn-default">条件クリア</button>
	</a>
	<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#search-extends" aria-expanded="false" aria-controls="search-extends">
		<i class="fa fa-caret-down"></i>
	</button>
</div>
<div class="collapse" id="search-extends">

	<div class="line">
		<?php foreach ($list_selectors as $key => $options): ?>
			<?= $this->Form->select($key, $options, ['default' => isset($search[$key]) ? $search[$key] : '']) ?>
		<?php endforeach ?>
	</div>
	<div class="line">
		<?= $this->Form->text('freeword', ['placeHolder' => 'フリーワード検索', 'class' => '']) ?>
		<button type="submit" class="btn btn-default" name="action" value="export">エクスポート</button>
	</div>
</div>
<?= $this->Form->end() ?>

<?php
$this->append('script');
echo $this->Html->script(['jquery.cookie', 'searchMenu']);
$this->end();
?>
 