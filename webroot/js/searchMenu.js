/*
 * 検索オプションの開閉
 */
$(function () {

 	if ( getCookie() == 'true' ) {
		$.fx.off = true;
		
		$('.search-form button[data-toggle="collapse"]').attr('aria-expanded','true');
		$('.search-form .collapse').addClass('in');
		
		$.fx.off = false;
	}
	
	$('.search-form .collapse').on({
		'hidden.bs.collapse' : function(){
			setCookie( false );
		},
		'shown.bs.collapse':function(){
			setCookie( true );
		}
	});

	function getCookie() {
		key = $('.search-form').attr('session_key');
		value = $.cookie(key);
		return value;
	}

	function setCookie(value) {
		key = $('.search-form').attr('session_key');
		$.cookie(key, value, {"path": "/"});
	}

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