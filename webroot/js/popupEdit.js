$(function () {


	//popup表示処理
	$('table tbody').on('dblclick', 'td.data', function (e) {
		$('table tbody td.data').removeClass('editing bg-info');
		$(this).addClass('editing bg-info');
	});

	//popup非表示処理
	$('table tbody').on('click', '.edit-popup button[value="cancel"]', function (e) {
		$('table tbody td.data').removeClass('editing bg-info');
	});

	//popupからの送信処理
	$('table tbody').on('click', '.edit-popup button[value="edit"]', function (e) {
		data = {
			id: $(this).parents('tr').attr('item_id')
		};
		$(this).parents('.edit-popup').find('input,textArea,select').each(function (id, obj) {
			data[$(obj).attr('name')] = $(obj).val();
		});

		post(data);
	});

	//flag関連はクリックしたら反転
	$('table tbody').on('click', 'td.flag', function (e) {
		data = {
			id: $(this).parents('tr').attr('item_id')
		};

		key = $(this).attr('key');
		old_value = parseInt( $(this).attr('value') );
		new_value = (old_value != 0) ? 0 : 1;

		console.log(old_value, new_value);

		data[key] = new_value;

		post(data);
	});


	function post(data) {
		url = $('.table-index').attr('url');

		console.log(data);

		$.post(url, data).done(function (id) {
			return function (data) {
				swapRow(id, data);
			};
		}(data['id']));
	}


	$(window).trigger('updateAutoComplete');

	function swapRow(id, data) {
		row_old = $('table tbody tr[item_id=' + id + ']');
		row_old.before(data);
		row_old.remove();
		$(window).trigger('updateAutoComplete');
	}
});
