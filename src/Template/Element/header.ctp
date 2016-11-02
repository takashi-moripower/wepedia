<div class="row header">
	<div class="col-xs-3 logo text-center">
		<a href="<?= $this->Url->build('/') ?>">
			<img src="<?= $this->Url->build('/img/logo.png') ?>">
		</a>
	</div>
	<div class="col-xs-16 col-xs-offset-1">
		<?= $this->getLoginUser() ? $this->Element('Navigation/headerNav') : '' ?>
	</div>
	<div class="col-xs-4 user">
		<?= $this->getLoginUser() ? $this->Element('Navigation/headerLoginUser') : '' ?>
	</div>
</div>