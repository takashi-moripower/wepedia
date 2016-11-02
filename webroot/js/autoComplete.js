/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function setAutoComplete( target_selector , source) {

	var update = function () {
		obj = $(target_selector);

		if (obj.data('autocomplete')) {
			obj.autocomplete("destroy");
			obj.removeData('autocomplete');
		}
		
		obj.autocomplete({
			source: source,
			autoFocus: true,
			delay: 500,
			minLength: 1
		});
	};

	$(window).on('updateAutoComplete', update);
	
	update();
}