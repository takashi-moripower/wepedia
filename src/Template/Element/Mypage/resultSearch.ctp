<?php
$date_options = [''=>NULL];
foreach ($list_date as $d) {
	$date_options[$d->format('Y-m-d')] = $d->format('Y年m月');
}
?>
<form class="search-form form-inline text-right">
	<div class="popover top">
		<div class="arrow"></div>
		<div class="popover-content">
			検索条件が変更されました<br>
			リストに反映させるには<br>
			検索ボタンを押してください
		</div>
	</div>
	<button type="button" class="btn btn-default" style="position:relative" name="search">
		<i class="fa fa-search"></i>
	</button>
	[
	<?= $this->Form->select('date[start]', $date_options, ['value' => $date['start']]) ?>
	～
	<?= $this->Form->select('date[end]', $date_options, ['value' => $date['end']]) ?>
	]
	<?= $this->Html->link('当月', ['controller' => 'mypage', 'action' => 'result', $user->id], ['class' => 'btn btn-default']) ?>
	<?= $this->Html->link('条件クリア', ['controller' => 'mypage', 'action' => 'result', $user->id], ['class' => 'btn btn-default']) ?>
</form>
<br>

<?php $this->append('script'); ?>
<script>

	url_base = "<?= $this->Url->build(['controller' => 'mypage', 'action' => 'result', $user->id]) ?>";
	$(function () {
		$('.search-form').on({
			change: function () {
				b = $('.search-form button[name="search"]');
				l = b.position()['left'] - $('.search-form .popover').outerWidth() / 2 + b.outerWidth() / 2;
				$('.search-form .popover').css({right: 'auto', left: l});
				$('.search-form .popover').show();

				b.addClass('btn-info');
				b.removeClass('btn-default');
			}
		}, 'input,select');


		$('.search-form button').on({
			click: function () {

				start = $('select[name="date[start]"]').val();
				end = $('select[name="date[end]"]').val();

				url = url_base + '/' + start + '/' + end;
				$(location).attr("href", url);
			}
		});
	});
</script>
<?php $this->end(); ?>
