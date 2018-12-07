
<div class="btn-group btn-group-justified" role="group">
	<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'good', $root->id]) ?>" class="btn btn-default">
		いいね！<span class="label label-info"><?= $root->good ?></span>
	</a>
	<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'cheer', $root->id]) ?>" class="btn btn-default">
		がんばれ！<span class="label label-info"><?= $root->cheer ?></span>
	</a>
	<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'boss_check', $latest->id]) ?>" class="btn btn-default<?= $latest->boss_check ? ' active' : '' ?>">
		部長認
	</a>
	<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'boss_check2', $latest->id]) ?>" class="btn btn-default<?= $latest->boss_check2 ? ' active' : '' ?>">
		マネージャー認
	</a>
</div>

<br>
