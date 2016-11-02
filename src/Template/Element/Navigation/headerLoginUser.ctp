<?php
$user = $this->getLoginUser();
?>
<div class="btn-group">
	<button type="button" class="btn btn-primary dropdown-toggle face48" data-toggle="dropdown" aria-expanded="false">
		<i class="fa fa-caret-down"></i>
		<?= $this->Element('face', ['id' => $user['id']]) ?>
		<?= $user['name'] ?>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li>
			<a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'logout']) ?>" class="logout">
				<i class="fa fa-sign-out"></i> log out
			</a>
		</li>
	</ul>
</div>

