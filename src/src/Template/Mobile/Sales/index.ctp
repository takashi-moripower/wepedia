<?php
use App\Defines\Defines;

echo $this->Element('Mobile/search');
echo $this->Element('Mobile/load',['newOffset'=>Defines::MOBILE_NODES_PER_PAGE]);

if(false):
foreach ($sales as $sale) {
	echo $this->Element('Mobile/cardSale', ['item' => $sale]);
}
?>

<div class="loader text-center panel panel-default" loading="0" offset="10" url="<?= $this->Url->build(['controller' => 'mobile', 'action' => 'sales', 'load', 'offset' => 10]) ?>">
	<div class="panel-body">
		<i class="fa fa-refresh fa-spin fa-2x"></i> Now Loading
	</div>
</div>
<?php endif ?>
<?php $this->append('script') ?>
<script>
	//スクロールによる自動読み込み
	$(function () {
		$(window).on('scroll',onScroll);

		function onScroll() {
			l = $('.loader')[0]
			if (!l) {
				$(window).off('scroll', onScroll);
				return;
			}


			if ($('.loader').attr('loading') == 1) {
				return;
			}

			top_window = $(window).scrollTop();
			height_window = $(window).height();
			bottom_window = top_window + height_window;

			top_loader = $('.loader').offset().top;

			if (top_loader < bottom_window) {
				post();
			}
		}

		function post() {
			if ($('.loader').attr('loading') == 1) {
				return;
			}

			$('.loader').attr('loading', 1);

			url = $('.loader').attr('url');
			data = {
				offset: $('.loader').attr('offset')
			};

			console.log(data);

			$.post(url, data)
					.done(onResult)
					.fail(onFail);
		}

		function onResult(value) {
			$('.loader').replaceWith(value);
			$('.card-comments input[type="checkbox"]').trigger('change');
		}

		function onFail() {
			$('.loader').attr('loading', 0);
			$(window).trigger('scroll');
		}
	});

	//	評価ボタンによる情報更新
	$(function () {
		$('body').on({
			click: function () {
				data = {
					id: $(this).parents('.view-sale-card').attr('sale_id'),
					key: $(this).attr('key'),
					value: $(this).attr('value')
				};
				post(data);
			}
		}, '.btn-set');

		function post(data) {
			url = "<?= $this->Url->build(['controller' => 'mobile', 'action' => 'sales', 'setData']) ?>";
			sale_id = data['id'];

			$.post(url, data)
					.done(function (id) {
						return function (data) {
							return onResult(id, data);
						};
					}(sale_id))
					.fail(onFail);
		}

		function onResult(id, data) {
			card = $('.view-sale-card[sale_id="' + id + '"]');
			card.replaceWith(data);

			$('.card-comments input[type="checkbox"]').trigger('change');
		}

		function onFail() {

		}
	});

	//	コメント
	$(function () {
		$('body').on({
			click: function () {
				text = $(this).parents('tr.comment-form').find('input[type="text"][name="text"]').val();
				if (text.length == 0) {
					confirm('コメントを入力してからボタンを押してください');
					return false;
				}
			}
		}, '.btn-comment');

		//	チェックボックス操作による一行フォームの表示・非表示
		$('body').on({
			change: function () {
				check = $(this).prop('checked');
				comment_id = $(this).parents('tr').attr('comment_id');
				sale_id = $(this).parents('tr').attr('sale_id');

				$(this).parents('.card-comments').find('tr.comment-form').hide();
				if (check) {
					$(this).parents('.card-comments').find('tr.comment-form[comment_id="' + comment_id + '"]').show();
					$(this).parents('.card-comments').find('tr[comment_id!=' + comment_id + '] input[type="checkbox"]').prop('checked', false);
				} else {
					$(this).parents('.card-comments').find('tr.comment-form[comment_id=""]').show();
				}
			}
		}, '.card-comments input[type="checkbox"]');

		$('.card-comments input[type="checkbox"]').trigger('change');

		$('body').on({
			click: function () {
				form = $(this).parents('.comment-form');
				data = {
					sale_id: form.attr('sale_id'),
					comment_id: form.attr('comment_id'),
					method: form.attr('method'),
					text: form.find('input[type="text"][name="text"]').val()
				};

				post(data);

			}
		}, '.comment-form button.btn-comment');

		function post(data) {
			url = '<?= $this->Url->build(['controller' => 'mobile', 'action' => 'comments']) ?>';
			sale_id = data['sale_id'];

			$.post(url, data)
					.done(function (id) {
						return function (data) {
							return onResult(id, data);
						};
					}(sale_id))
					.fail(onFail);
		}

		function onResult(id, data) {
			card = $('.card-comments.card-ex[root_id="' + id + '"]');
			card.replaceWith(data);
			$('.card-comments input[type="checkbox"]').trigger('change');
		}

		function onFail() {

		}
	});

	//	過去の報告、顧客の声、コメント　の表示/非表示
	$(function () {
		$('body').on({
			click: function () {
				card = $(this).parents('.view-sale-card');
				target = $(this).attr('target');
				console.log(target);

				card.find('.card-ex[ex-source="' + target + '"]').slideToggle();
				card.find('.card-ex[ex-source!="' + target + '"]').slideUp();
			}
		}, '.data-ex button');
	});
</script>
<?php $this->end() ?>

