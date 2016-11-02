$(function () {
	$('.section-nav button.btn').on({
		click: function () {
			if ($(this).hasClass('btn-info')) {
				$(this).removeClass('btn-info');
				$(this).addClass('btn-default');
			} else {
				$(this).removeClass('btn-default');
				$(this).addClass('btn-info');
			}

			list_users = $('.section-nav button.btn-info').map(function () {
				return $(this).parents('li').attr('member_id');
			}).get();

			$('.search-form input[name="user_id"]').val(JSON.stringify(list_users));
			$('.search-form input[name="user_id"]').trigger('change');
		}
	});
});
