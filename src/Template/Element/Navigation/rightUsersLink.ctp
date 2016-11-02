
<div class="panel panel-primary section-nav" url="<?= $this->Url->build(['controller' => 'sales', 'action' => 'index']) ?>">
	<div class="panel-heading">営業報告を見る</div>
	<div class="panel-body">
		<?= $this->Element('Navigation/Section') ?>
	</div>
</div>
<?php $this->append('script'); ?>
<script>
	$(function () {
		$('.section-nav button.btn').on({
			click: function () {
				id = $(this).parents('li').attr('member_id');
				url = $(this).parents('.section-nav').attr('url') + '?user_id=' + JSON.stringify([id]);
				window.location.href = url;
			}
		});
	});
</script>
<?php $this->end(); ?>