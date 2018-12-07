<?php $this->append('css') ?>
<style>
	.main-nav a:last-child{
		position:relative;
		top:-3rem;
	}
</style>
<?php $this->end() ?>
<div class="main-nav">
		<a href="<?= $this->Url->build(['controller' => 'mobile', 'action' => 'sales']) ?>">
			<img src="<?= $this->Url->build('/img/banner01.png') ?>" class="img-responsive center-block"/>
		</a>
		<a href="<?= $this->Url->build(['controller' => 'mobile', 'action' => 'products']) ?>">
			<img src="<?= $this->Url->build('/img/banner02.png') ?>" class="img-responsive center-block"/>
		</a>
</div>
<?= $this->Element('Navigation/links') ?>