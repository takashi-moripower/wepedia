<div class="row">
	<div class="container center-block">
		<div class="links col-md-offset-3 col-md-18 col-lg-offset-4 col-lg-16">
			<div class="row">
				<?php
				$links = \Cake\Core\Configure::read('Links');
				foreach ($links as $id => $link):
					?>
					<div class="col-xs-6 col-sm-4 text-center">
						<a href="<?= $link['url'][0] ?>">
							<img src='<?= $this->Url->build(sprintf("/img/icon%02d.png", $id * 10 + 11)) ?>' class="img-responsive center-block"/>
							<p class="text-center">
								<?= $link['label'] ?><br>
								学習者
							</p>
						</a>		
					</div>
					<div class="col-xs-6 col-sm-4 text-center">
						<a href="<?= $link['url'][1] ?>" class="">
							<img src='<?= $this->Url->build(sprintf("/img/icon%02d.png", $id * 10 + 12)) ?>' class="img-responsive center-block"/>
							<p class="text-center">
								<?= $link['label'] ?><br>
								管理者
							</p>
						</a>		
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
