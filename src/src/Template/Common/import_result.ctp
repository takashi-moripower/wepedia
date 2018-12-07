<hr>
<div class="message">
	<h4>インポート処理が終了しました</h4>
	<br>
	成功：　<?= $result['success'] ?>　件<br>
	失敗：　<?= $result['failed'] ?>　件<br>
	無視：　<?= $result['pass'] ?>　件<br>
</div>

<?php if( $result['failed'] > 0 ): ?>
<div class="message">
	<h4>読み込みに失敗したのは以下の行です</h4>
	<br>
	<ul class="failed_lines">
		<?php foreach ($result['failed_lines'] as $id_line =>$line): ?>
			<li>
				<span class="line_id"><?= $id_line ?> : </span>
				<?php
				foreach ((array) $line as $id => $data) {
					echo ($id > 0) ? ',' : '';
					echo $data;
				}
				?>
			</li>
		<?php endforeach ?>
	</ul>	
</div>
<?php endif ?>