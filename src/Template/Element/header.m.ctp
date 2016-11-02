<div class="row header">
	<div class="col-xs-12 logo">
		<a href="<?= $this->Url->build(['controller'=>'mobile','action'=>'index']) ?>">
			<img src="<?= $this->Url->build('/img/logo.png') ?>" class="img-responsive">
		</a>
	</div>
	<div class="col-xs-12 user">
		<?php
		if ($this->getLoginUser()) {
			echo $this->Element('Navigation/headerMobile');
		}
		?>
	</div>
</div>