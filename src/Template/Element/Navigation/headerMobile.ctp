<div class="clearfix nav-header-mobile">
	<div class="dropdown pull-right">
		<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<i class="fa fa-bars fa-2x"></i>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li>
				<?= $this->Html->link('<i class="fa fa-check-square-o"></i> 報告一覧',['controller' => 'mobile', 'action' => 'sales'],['escape'=>false]) ?>
			</li>
			<li>
				<?= $this->Html->link('<i class="fa fa-pencil-square-o"></i> 新規スレッドの作成',['controller' => 'mobile', 'action' => 'sales' , 'add'],['escape'=>false]) ?>
			</li>
			<li role="separator" class="divider"></li>
			<li>
				<?= $this->Html->link('<i class="fa fa-list-alt"></i> 教材カルテ',['controller' => 'mobile', 'action' => 'products'],['escape'=>false]) ?>
			</li>
			<li role="separator" class="divider"></li>
			<li>
				<?= $this->Html->link('<i class="fa fa-sign-out"></i> log out',['controller' => 'users', 'action' => 'logout'],['escape'=>false]) ?>
			</li>
		</ul>
	</div>
</div>