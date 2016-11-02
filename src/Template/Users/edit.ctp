<?= $this->Form->create($user, ['type' => 'file']) ?>
<div class="row">
	<div class="col-lg-12 col-lg-offset-6">
		<h2>ユーザー情報　編集</h2>
		<?= $this->Element('Users/edit') ?>
		<div class="text-right">
			<a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'index']) ?>">
				<?= $this->Form->button('一覧に戻る', ['type' => 'button']) ?>
			</a>
			<?= $this->Form->button('保存') ?>
		</div>
	</div>
</div>
<?= $this->Form->end() ?>
