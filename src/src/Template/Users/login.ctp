
<br>
<div class="row">
	<div class="panel panel-default panel-body col-md-8 col-md-offset-8 col-sm-16 col-sm-offset-4 col-xs-24">
		<div class="panel-body">

			<?= $this->Flash->render('auth') ?>
			<?= $this->Form->create() ?>
			<fieldset>
				<?= $this->Form->input('email', ['label' => 'E メール']) ?>
				<?= $this->Form->input('password', ['label' => 'パスワード']) ?>
			</fieldset>
			<div class="text-center">
				<?= $this->Form->button(__('ログイン'), ['class' => 'btn-primary']); ?>
			</div>
			<div class="text-right">
				<label for="auto_login">ログイン状態を保持</label>
				<?= $this->Form->checkbox('auto_login', ['id' => 'auto_login']); ?>
			</div>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<br>
<?= $this->Element('Navigation/links'); ?>