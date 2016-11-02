
<div class="clearfix">
	<button class="btn btn-default btn-lg col-xs-12 btn-set" key="good" value="<?= $item->good + 1 ?>">
		いいね！<span class="label label-info"><?= (int) ( $item->good ) ?></span>
	</button>

	<button class="btn btn-default btn-lg col-xs-12 btn-set" key="cheer" value="<?= $item->cheer + 1 ?>">
		がんばれ！<span class="label label-info"><?= (int) ( $item->cheer ) ?></span>
	</button>
	<button class="btn btn-lg col-xs-12 btn-set <?= $item->boss_check ? 'btn-info' : 'btn-default' ?>" key="boss_check" value="<?= $item->boss_check ? 0 : 1 ?>">
		部長認
	</button>

	<button class="btn btn-lg col-xs-12 btn-set <?= $item->boss_check2 ? 'btn-info' : 'btn-default' ?>" key="boss_check2" value="<?= $item->boss_check2 ? 0 : 1 ?>">
		マネ認
	</button>
</div>
