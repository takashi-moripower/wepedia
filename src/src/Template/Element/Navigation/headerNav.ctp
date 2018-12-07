<ul class="nav nav-pills nav-header-desktop">
	<li role="presentation" class="text-center <?= $this->isMatch('mypage') ? 'active' : ''?>">
		<a href="<?= $this->Url->build(['controller' => 'mypage', 'action' => 'index']) ?>">
			<i class="fa fa-user fa-3x"></i><br>
			マイページ
		</a>
	</li>
	<li role="presentation" class="text-center <?= $this->isMatch('sales',['index','draft','trashbox']) ? 'active' : ''?>">
		<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'index', 'clear' => true]) ?>">
			<i class="fa fa-check-square-o fa-3x"></i><br>
			報告一覧
		</a>
	</li>
	<li role="presentation" class="text-center <?= $this->isMatch('sales',['add','edit']) ? 'active' : ''?>">
		<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'add']) ?>">
			<i class="fa fa-pencil-square-o fa-3x"></i><br>
			新規スレッド
		</a>
	</li>
	<li role="presentation" class="text-center <?= $this->isMatch('demands',['index']) ? 'active' : ''?>">
		<a href="<?= $this->Url->build(['controller' => 'demands', 'action' => 'index']) ?>">
			<i class="fa fa-comment-o fa-3x"></i><br>
			顧客の声
		</a>
	</li>
	<li role="presentation" class="text-center <?= $this->isMatch('collections') ? 'active' : ''?>">
		<a href="<?= $this->Url->build(['controller' => 'collections', 'action' => 'index']) ?>">
			<i class="fa fa-list-alt fa-3x"></i><br>
			集計
		</a>
	</li>
	<?php if( $this->isAdmin() ): ?>
	<li role="presentation" class="dropdown text-center">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-cogs fa-3x"></i><br>
			管理　<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'index']) ?>">
					ユーザー
				</a>
			</li>
			<li>
				<a href="<?= $this->Url->build(['controller' => 'results', 'action' => 'index']) ?>">
					売り上げ
				</a>
			</li>
			<li>
				<a href="<?= $this->Url->build(['controller' => 'products', 'action' => 'index']) ?>">
					商品
				</a>
			</li>
			<li>
				<a href="<?= $this->Url->build(['controller' => 'informations', 'action' => 'index']) ?>">
					お知らせ
				</a>
			</li>
			<li>
				<a href="<?= $this->Url->build(['controller' => 'sales', 'action' => 'import']) ?>">
					営業情報
				</a>
			</li>
			<li>
				<a href="<?= $this->Url->build(['controller' => 'demands', 'action' => 'import']) ?>">
					顧客の声
				</a>
			</li>
			<li>
				<a href="<?= $this->Url->build(['controller' => 'config', 'action' => 'setProjectDo']) ?>">
					その他設定
				</a>
			</li>
		</ul>
	</li>
	<?php endif ?>
</ul>