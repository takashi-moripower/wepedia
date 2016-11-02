<?php
$templates = [
	'sales' => [
		'label' => '営業報告',
		'url' => '/download/sale-template.csv'
	],
	'results' => [
		'label' => '売り上げ',
		'url' => '/download/result-template.csv'
	],
];
?>
<div class=" col-lg-offset-6 col-lg-12">
	<h2>インポート</h2>
	<?= $this->Form->create(NULL, ['type' => 'file']) ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label>読み込みファイル</label>
				<div>
					<?= $this->Form->file('filename') ?>
				</div>
			</div>
			<div class="form-group">
				<label for="character-code">文字コード</label>
				<div>
					<?= $this->Form->radio('character-code', [ 'sjis-win' => 'sjis', 'UTF8' => 'UTF8'], ['default' => 'sjis-win', 'class' => 'form-control']) ?>
				</div>
			</div>
			<div class="text-right">		
				<?= $this->Form->submit('処理開始', ['class' => '']) ?>
			</div>
		</div>
	</div>
	<?= $this->Form->end() ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			Hint
		</div>
		<div class="panel-body">
			<ul>
				<li>ファイルの一行目からデータとして読み込みます</li>
				<li>「#」から始まる行は無視されます</li>
				<li>文字コードはsjisとUTF8に対応しています</li>
				<li>2016年6月updateより　日付の入力形式が　yyyy-MM-DD 形式に変更されました<br>
					例：2016-07-10</li>
				<?php if (!empty($templates[$this->getController()])): ?>
					<li>
						フォーマットに関しては以下のファイルを参考にしてください<br>
						「<?= $this->Html->link($templates[$this->getController()]['label'] . 'データテンプレート', $templates[$this->getController()]['url']) ?>」
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
</div>
