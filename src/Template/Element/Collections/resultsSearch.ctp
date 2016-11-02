<?php

use Cake\I18n\Date;
use Cake\Utility\Hash;
use Cake\Core\Configure;

$date_options = ['' => NULL];
foreach ($list_date as $d) {
	$date_options[$d->format('Y-m-d')] = $d->format('Y年m月');
}
?>
<?= $this->Form->create(NULL, ['class' => 'search-form form-inline text-right']) ?>
<?= $this->Form->hidden('search', ['value' => true]) ?>
<?= $this->Form->hidden('user_id', ['value' => isset($search['user_id']) ? $search['user_id'] : NULL]) ?>
<div class="popover top">
	<div class="arrow"></div>
	<div class="popover-content">
		検索条件が変更されました<br>
		リストに反映させるには<br>
		検索ボタンを押してください
	</div>
</div>
<?= $this->Form->button('<i class="fa fa-search"></i>', ['type' => 'submit', 'escape' => false, 'class' => 'search']) ?>
[
<?= $this->Form->select('date[start]', $date_options, ['value' => $search['date']['start']]) ?>
～
<?= $this->Form->select('date[end]', $date_options, ['value' => $search['date']['end']]) ?>
]
<?= $this->Html->link('当月', ['controller' => 'collections', 'action' => 'results', 'clear' => true], ['class' => 'btn btn-default']) ?>
 <?= $this->Html->link('条件クリア', ['controller' => 'collections', 'action' => 'results', 'clear' => true], ['class' => 'btn btn-default']) ?>
<?= $this->Form->end() ?>
<br>
<?php $this->append('script'); ?>
<script>
	$(function () {
		$('.search-form').on({
			change: function () {
				b = $('.search-form button.search');
				p = $('.search-form .popover');
				l = b.position()['left'] - p.outerWidth() / 2 + b.outerWidth() / 2;
				p.css({right: 'auto', left: l});
				p.show();

				b.addClass('btn-info');
				b.removeClass('btn-default');
			}
		}, 'input,select');
	});
</script>
<?php $this->end(); ?>
 