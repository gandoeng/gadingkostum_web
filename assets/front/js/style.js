var base_url 					= $('base').attr('href');
var page_loading 				= $('.dimmer');
var this_is_item_list_product 	= $('#item-list-product');
var this_is_pagination_product 	= $('#pagination-product');
var classname 					= [];
var _uri_init					= new URI();
var _uri_segment_1 				= _uri_init.segment(0);
var _uri_page 					= _uri_init.segment(1);
var icheck_cat 					= $('.categories-check');
window.stateChangeIsLocal 		= false;
var initialURL 					= '';
var popped 						= ('state' in window.history && window.history.state !== null);
var initialURL 					= location.href;
var size_select2 				= $('.size-select2');
var gender_select2 				= $('.gender-select2');
var store_select2 				= $('.store-select2');
var dateToday 					= new Date();

function history_push(option,title,url){
	History.pushState(option,title,url);
}

if(_uri_segment_1 == 'categories' || _uri_segment_1 == 'product' || _uri_segment_1 == 'product-category' || _uri_segment_1 == 'demo_product'){
	History.Adapter.bind(window,'statechange',function(){

		if (!window.stateChangeIsLocal) {
			var State = History.getState();
			var data = State.data;
			var initialPop = !popped && location.href == initialURL;
			popped = true;
			if (initialPop) {
				return;
			}

			var classname 	= data.result.classname; 
			var start_date  = data.result.start_date;
			var end_date 	= data.result.end_date;

			if(start_date === undefined){
				$('.datepicker-start').val('');
			} else {
				$('.datepicker-start').val(start_date);
			}

			if(end_date === undefined){
				$('.datepicker-end').val('');
			} else {
				$('.datepicker-end').val(end_date);
			}

			if(classname === undefined){
				$(document).find('input[type="checkbox"]').prop('checked',false);
			} else {
				$(document).find('input[type="checkbox"]').each(function() {
					var get_classname   = $(this).data('index');
					var el_classname    = $('.'+get_classname)[0];
					var checked_value 	= false;
					$.each(classname,function(key,value){
						if($(el_classname).hasClass(value)){
							checked_value = true;
						}
					});
					$(el_classname).prop('checked',checked_value);
				});
			}

			var newurl = getUrlVars(data.newurl),
			this_is_item_list_product 		= $('#item-list-product'),
			this_is_pagination_product 		= $('#pagination-product'),
			_this_location_href 			= window.location.href,
			_result_url 					= _this_location_href,
			_current_url 					= current_url();

			var data_options = {
				url 		: newurl,
				current_url : _current_url,
				classname   : classname
			};

			var result 		= data.result;
			var data_prod 	= data.result.data;
			var data_pag  	= data.result.pagination;

			if(result !== ''){
				newurl = result.url;
				if(_current_url !== ''){
					newurl = '?'+newurl;
				} else if(newurl !== '') {
					newurl = '?' +newurl;
				} else {
					newurl = _this_location_href;
				}
			}

			var data_return = {page:newurl,result};

			_this_loader_show('#loader-list-product');
			$(this_is_item_list_product).hide();
			$(this_is_pagination_product).hide();

			$(this_is_item_list_product).empty();
			$(this_is_pagination_product).empty();
			setTimeout(function() {
				if(!$.trim($(this_is_item_list_product).html()).length) {
					$(this_is_item_list_product).append(data_prod);

					setTimeout(function(){
						$(document).find('.product-items').matchHeight();
						$('html, body').animate({
							scrollTop: $(this_is_item_list_product).offset().top-15
						});
					});
				} 

				if(!$.trim($(this_is_pagination_product).html()).length) {
					$(this_is_pagination_product).append(data_pag);
				} 
			},10);

			setTimeout(function() {
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				_this_loader_disabled('#loader-list-product');
			},20);

		} else {
			window.stateChangeIsLocal = false;
		}

	});

}

if(_uri_segment_1 == 'search' || _uri_segment_1 == 'demo_search'){
	History.Adapter.bind(window,'statechange',function(){

		if (!window.stateChangeIsLocal) {
			var State = History.getState();
			var data = State.data;
			var initialPop = !popped && location.href == initialURL;
			popped = true;
			if (initialPop) {
				return;
			}

			var newurl = getUrlVars(data.newurl),
			this_is_item_list_product 		= $('#search-list'),
			this_is_pagination_product 		= $('#pagination-search-product'),
			_this_location_href 			= window.location.href,
			_result_url 					= _this_location_href,
			_current_url 					= current_url();

			var data_options = {
				url 		: newurl,
				current_url : _current_url,
				classname   : classname
			};

			var result 		= data.result;
			var data_prod 	= data.result.data;
			var data_pag  	= data.result.pagination;

			if(result !== ''){
				newurl = result.url;
				if(_current_url !== ''){
					newurl = '?'+newurl;
				} else if(newurl !== '') {
					newurl = '?' +newurl;
				} else {
					newurl = _this_location_href;
				}
			}

			var data_return = {page:newurl,result};

			_this_loader_show('#loader-list-product');
			$(this_is_item_list_product).hide();
			$(this_is_pagination_product).hide();

			$(this_is_item_list_product).empty();
			$(this_is_pagination_product).empty();
			setTimeout(function() {
				if(!$.trim($(this_is_item_list_product).html()).length) {
					$(this_is_item_list_product).append(data_prod);

					setTimeout(function(){
						var size_select2 				= $(document).find('.size-select2');
						var gender_select2 				= $(document).find('.gender-select2');
						var store_select2 				= $(document).find('.store-select2');
						var datepicker_start 			= $(document).find('.datepicker-search-start');
						var datepicker_end 				= $(document).find('.datepicker-search-end');
						
						search_select2(size_select2,gender_select2,store_select2);
						search_datepicker(datepicker_start);
						search_datepicker(datepicker_end);

						$(document).find('.product-items').matchHeight();
						$('html, body').animate({
							scrollTop: $('.form-search-product').offset().top-15
						});
					});
				} 

				if(!$.trim($(this_is_pagination_product).html()).length) {
					$(this_is_pagination_product).append(data_pag);
				} 
			},10);

			setTimeout(function() {
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				_this_loader_disabled('#loader-list-product');
			},20);

		} else {
			window.stateChangeIsLocal = false;
		}

	});
}	

function _this_loader_show(selector){
	$(selector).find('.segment').dimmer('show');
	setTimeout(function(){
		var _this_element = $(selector).find('.segment').find('.dimmer');
		var _this_element_text = $(selector).find('.segment').find('.loader');
		$(selector).find('.segment').removeClass('dimmed');
		if($(_this_element).hasClass('disabled')){
			$(_this_element).removeClass('disabled');
			$(_this_element).removeClass('dimmer');
		}
		if($(_this_element_text).hasClass('disabled')){
			$(_this_element_text).removeClass('disabled');
		}
		$(_this_element).addClass('active');
		$(_this_element_text).addClass('active');
	});
}
function _this_loader_hide(){
	$('.segment').dimmer('hide');
}
function _this_loader_disabled(selector){
	$(selector).find('.segment').dimmer('hide');
	var _this_element = $(selector).find('.segment').find('.dimmer');
	var _this_element_text = $(selector).find('.segment').find('.loader');
	$(selector).find('.segment').addClass('dimmed');
	if($(_this_element).hasClass('active')){
		$(_this_element).removeClass('active');
		$(_this_element).addClass('dimmer');
	}
	if($(_this_element_text).hasClass('active')){
		$(_this_element_text).removeClass('active');
	}
	$(_this_element).addClass('disabled');
	$(_this_element_text).addClass('disabled');
}

function getUrlVars()
{
	var vars = {}, hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		var key_slug = hash[0];
		var slug 	 = hash[1];
		//vars.push(hash[0]);
		if(slug !== null){
			vars[key_slug] = slug;
		}
	}
	return vars;
}

function getUrlVariable(data){
	var vars = {}, hash;
	var hashes = data.split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		var key_slug = hash[0];
		var slug 	 = hash[1];
		//vars.push(hash[0]);
		if(slug !== null){
			vars[key_slug] = slug;
		}
	}
	return vars;
}

function getUrlParameter(sParam) {
	var sPageURL = window.location.search.substring(1),
	sURLVariables = sPageURL.split('&'),
	sParameterName,
	i;

	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		}
	}
};

function removeArrayParam(key, value, sourceURL) {
	var rtn = sourceURL.split("?")[0],
	param,
	params_arr = [],
	queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
	if (queryString !== "") {
		params_arr = queryString.split("&");
		for (var i = params_arr.length - 1; i >= 0; i -= 1) {
			param = params_arr[i].split("[]=")[0];
			paramValue = params_arr[i].split("[]=")[1];
			if (param === key && paramValue === value) {
				params_arr.splice(i, 1);
			}
		}
		if(params_arr.length) {
			rtn = rtn + "?" + params_arr.join("&");
		} 
	}
	return rtn;
}

function current_url(){
	var current_url = window.location.href,
	a = current_url.indexOf("?"),
	b =  current_url.substring(a),
	c = current_url.replace(b,"");
	current_url = c;
	return current_url;
}

function get_checkbox_classname(){
	var data = {};
	var classname = [];

	$('input[type="checkbox"]').each(function() {
		if (this.checked) {
			if (data[this.name] === undefined) data[this.name] = [];
			data[this.name].push(this.value);
			classname.push($(this).data('index'));
		}
	});
	return classname;
}

function search_select2(size_select2,gender_select2,store_select2){
	if ($.fn.select2) {
		$(size_select2).select2({multiple:true,placeholder: 'Size'});
		$(gender_select2).select2({multiple:true,placeholder: 'Gender'});
		$(store_select2).select2({multiple:true,placeholder: 'Store Location'});
	}
}

function clear_datepicker(selector){
	$(selector).val("").datepicker("update");
}
function search_datepicker(selector){
	$(selector).datepicker({
		changeMonth: false,
		changeYear: false,
		format: 'd MM yyyy',
		autoclose: true,
		duration: 'fast',
		startDate: dateToday,
		clearBtn: true,
	}).focus(function() {
		$('.datepicker').find('.datepicker-switch').click(function(event) {
			event.preventDefault();
			event.stopPropagation();
		});
	}).on('changeDate',function(e){
		if(_uri_segment_1 == 'categories' || _uri_segment_1 == 'product' || _uri_segment_1 == 'product-category' || _uri_segment_1 == 'demo_product'){

			var data = {},
			dataStrings = [],
			_this_location_href 	= window.location.href,
			_url 					= getUrlVars(_this_location_href),
			_uri_page 				= 1,
			start 					= $('.datepicker-start').val(),
			end   					= $('.datepicker-end').val();

			var classname = [];

			$('input[type="checkbox"]').each(function() {
				if (this.checked) {
					classname.push($(this).data('index'));
				}
			});

			if(start != '' && end != ''){

				if(start != ''){
					if (_url['start'] === undefined) _url['start'] = '';
					_url['start'] = start;
				}
				if(end != ''){
					if (_url['end'] === undefined) _url['end'] = '';
					_url['end'] = end;
				}
				var data_options = {url : _url,page: _uri_page,classname:classname};
				filterProductsByTag(_url,data_options);
			}

			if(start == '' && end == ''){
				if (_url['start'] !== undefined) delete _url['start'];
				if (_url['end'] !== undefined) delete _url['end'];
				var data_options = {url : _url,page: _uri_page,classname:classname};
				filterProductsByTag(_url,data_options);
			}
		}
	});
}

function getPagination(selector,prefix,data){

	$(selector).pagination({
		items: data.data_total_rows,
		itemsOnPage: data.data_limit,
		cssStyle: 'light-theme',
		displayedPages: 3,
		hrefTextPrefix: prefix,
		hrefTextSuffix: data.data_url,
		currentPage: data.data_page_number,
		edges: 1,
		ellipsePageSet: false,
		selectOnClick: false,
		prevText: '<i class="fa fa-angle-left">',
		nextText: '<i class="fa fa-angle-right">'
	});
}

function searchProduct(url,data_options){

	if(data_options === undefined){
		data_options = {};
	}

	var tagList = [];
	var tagUrl = '';

	var ajaxLoadPage = function () {

		var this_is_item_list_product 	= $('#search-list'),
		this_is_pagination_product 		= $('#pagination-search-product'),
		_this_location_href 			= window.location.href,
		_result_url 					= _this_location_href,
		_current_url 					= current_url();

		$.ajax({
			url: base_url + _uri_segment_1 + '/filteringsearch/' + data_options.page,
			dataType: "json",
			type: "POST",
			data: data_options,
			beforeSend: function(data){
				loader_show(".loading-mask");
				$(this_is_item_list_product).hide();
				$(this_is_pagination_product).hide();
				$(size_select2).prop('disabled',true);
				$(gender_select2).prop('disabled',true);
				$(store_select2).prop('disabled',true);
				$('button[type=submit], input, .reset-filter').prop('disabled',true);
				setTimeout(function(){
					var offsetscroll = $(this_is_item_list_product).offset();
					$('html, body').scrollTop(offsetscroll);
				});
			},
			success: function (result) {
				var data_prod 	= result.data;
				var data_pag  	= result.pagination;
				var data_page_number = result.page;
				var data_total_rows 	= result.total_rows;
				var data_limit 			= result.limit;
				var newurl 				= _this_location_href;
				var data_url 			= result.url;
				
				if(result !== ''){
					newurl = result.url;
					if(_current_url !== ''){
						if(newurl == ''){
							newurl = _uri_segment_1 + '/' +data_page_number;
						} else {
							newurl = _uri_segment_1 + '/' +data_page_number +'?'+newurl;
						}
					} else if(newurl !== '') {
						newurl = _uri_segment_1 + '/' +data_page_number+'?'+newurl;
					} else {
						newurl = _uri_segment_1 + '/' +data_page_number;
					}
				}

				var data_return = {page:newurl,result};
				history_push(data_return,'Search - Gading Kostum',newurl);
				$(this_is_item_list_product).empty();
				setTimeout(function() {
					if(!$.trim($(this_is_item_list_product).html()).length) {
						$(this_is_item_list_product).append(data_prod);

						setTimeout(function(){
							var size_select2 				= $(document).find('.size-select2');
							var gender_select2 				= $(document).find('.gender-select2');
							var store_select2 				= $(document).find('.store-select2');
							var datepicker_start 			= $(document).find('.datepicker-search-start');
							var datepicker_end 				= $(document).find('.datepicker-search-end');

							search_select2(size_select2,gender_select2,store_select2);
							search_datepicker(datepicker_start);
							search_datepicker(datepicker_end);

							$(document).find('.product-items').matchHeight();
							$('html, body').animate({
								scrollTop: $('.form-search-product').offset().top-15
							});
						});
					} 

					if(!$.trim($(this_is_pagination_product).html()).length) {

						if(data_url !== ''){
							data_url = '?'+data_url;
						}

						var dataConfig = {
							data_page_number	: data_page_number,
							data_total_rows		: data_total_rows,
							data_limit			: data_limit,
							data_url			: data_url
						};

						getPagination(this_is_pagination_product,_uri_segment_1 + '/',dataConfig);

					} else {

						if(data_total_rows == 0){
							$(this_is_pagination_product).pagination('destroy');
						} else {
							$(this_is_pagination_product).pagination('drawPage', data_page_number);
						}
					} 
				});
			},
			complete: function(){
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				loader_hide(".loading-mask");
				$(size_select2).prop('disabled',false);
				$(gender_select2).prop('disabled',false);
				$(store_select2).prop('disabled',false);
				$('button[type=submit], input, .reset-filter').prop('disabled',false);
			},
			fail: function(){
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				loader_hide(".loading-mask");
				$(size_select2).prop('disabled',false);
				$(gender_select2).prop('disabled',false);
				$(store_select2).prop('disabled',false);
				$('button[type=submit], input, .reset-filter').prop('disabled',false);
			},
			error: function(xhr, ajaxOptions, thrownError){
				alert(thrownError);
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				loader_hide(".loading-mask");
				$(size_select2).prop('disabled',false);
				$(gender_select2).prop('disabled',false);
				$(store_select2).prop('disabled',false);
				$('button[type=submit], input, .reset-filter').prop('disabled',false);
			}
		});
	};
	ajaxLoadPage();

	return false;
}

function filterProductsByTag(url,data_options){

	var ctrl = '';
	var title_page = '';
	var ctrl_page = 'categories/';

	if(_uri_segment_1 == 'categories'){
		title_page = 'Categories - Gading Kostum';
	}

	if(_uri_segment_1 == 'categories' || _uri_segment_1 == 'product-category'){
		ctrl = 'categories/filteringselect/';
	}

	if(_uri_segment_1 == 'product-category'){
		title_page = 'Product Category Archives - Gading Kostum';
	}

	if(_uri_segment_1 == 'product'){
		title_page = 'Product - Gading Kostum';
		ctrl = 'product/filteringselectproduct/';
		ctrl_page = 'product/';
	}

	if(_uri_segment_1 == 'demo_product'){
		title_page = 'Product - Gading Kostum';
		ctrl = 'demo_product/filteringselectproduct/';
		ctrl_page = 'demo_product/';
	}

	if(data_options === undefined){
		data_options = {};
	}

	var tagList = [];
	var tagUrl = '';

	var count_checked = 0;

	$('input[type="checkbox"]').each( function(){
		var tagName = $(this).data('slug');
		if ($(this).is(':checked')){
			tagList.push(tagName);
			count_checked+=$(this).length;
		}
	});


	tagUrl = "/" + tagList.join('||');

	var ajaxLoadPage = function () {

		var this_is_item_list_product 	= $('#item-list-product'),
		this_is_pagination_product 		= $('#pagination-product'),
		_this_location_href 			= window.location.href,
		_result_url 					= _this_location_href,
		_current_url 					= current_url();

		$.ajax({
			url: base_url + ctrl + data_options.page,
			dataType: "json",
			type: "POST",
			data: data_options,
			beforeSend: function(data){
				$('input[type="checkbox"]').prop('disabled',true);
				$('.datepicker-start').prop('disabled',true);
				$('.datepicker-end').prop('disabled',true);
				$('.reset-filter').prop('disabled',true);
				$(this_is_item_list_product).hide();
				$(this_is_pagination_product).hide();
				loader_show(".loader","loading");
				setTimeout(function(){
					var offsetscroll = $(this_is_item_list_product).offset();
					$('html, body').scrollTop(offsetscroll);
				});
			},
			success: function (result) {
				$('input[type="checkbox"]').prop('disabled',false);
				$('.datepicker-start').prop('disabled',false);
				$('.datepicker-end').prop('disabled',false);
				$('.reset-filter').prop('disabled',false);
				var data_prod 	= result.data;
				var data_pag  	= result.pagination;
				var data_page_number = result.page;
				var data_total_rows 	= result.total_rows;
				var data_limit 			= result.limit;
				var newurl 				= _this_location_href;
				var data_url 			= result.url;

				if(result !== ''){
					newurl = result.url;
					if(_current_url !== ''){
						if(newurl == ''){
							newurl = ctrl_page + data_page_number;
						} else {
							newurl = ctrl_page + data_page_number +'?'+newurl;
						}
					} else if(newurl !== '') {
						newurl = ctrl_page + data_page_number+'?'+newurl;
					} else {
						newurl = ctrl_page + result.page;
					}
				}

				if(_uri_segment_1 == 'product-category'){

					var getUrl = window.location;
					var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

					if(count_checked >= 2){
						window.location.href = baseUrl+'/'+newurl;
					} else {
						$(this_is_item_list_product).empty();
						$(this_is_pagination_product).empty();
						setTimeout(function() {
							if(!$.trim($(this_is_item_list_product).html()).length) {
								$(this_is_item_list_product).append(data_prod);

								setTimeout(function(){
									$(document).find('.product-items').matchHeight();
									$('html, body').animate({
										scrollTop: $(this_is_item_list_product).offset().top-15
									});
								});
							} 
						});
					}
				} else {

					var data_return = {page:newurl,result};
					history_push(data_return,title_page,newurl);
					$(this_is_item_list_product).empty();
					$(this_is_pagination_product).empty();
					setTimeout(function() {
						if(!$.trim($(this_is_item_list_product).html()).length) {
							$(this_is_item_list_product).append(data_prod);

							setTimeout(function(){
								$(document).find('.product-items').matchHeight();
								$('html, body').animate({
									scrollTop: $(this_is_item_list_product).offset().top-15
								});
							});
						} 
					});
				}

				if(!$.trim($(this_is_pagination_product).html()).length) {

					if(data_url !== ''){
						data_url = '?'+data_url;
					}

					var dataConfig = {
						data_page_number	: data_page_number,
						data_total_rows		: data_total_rows,
						data_limit			: data_limit,
						data_url			: data_url
					};
					getPagination(this_is_pagination_product,"search/",dataConfig);
				} else {
					if(data_total_rows == 0){
						$(this_is_pagination_product).pagination('destroy');
					} else {
						$(this_is_pagination_product).pagination('drawPage', data_page_number);
					}
				}
			},
			complete: function(){
				$('input[type="checkbox"]').prop('disabled',false);
				$('.datepicker-start').prop('disabled',false);
				$('.datepicker-end').prop('disabled',false);
				$('.reset-filter').prop('disabled',false);
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				loader_hide(".loader");
			},
			fail: function(){
				$('input[type="checkbox"]').prop('disabled',false);
				$('.datepicker-start').prop('disabled',false);
				$('.datepicker-end').prop('disabled',false);
				$('.reset-filter').prop('disabled',false);
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				loader_hide(".loader");
			},
			error: function(xhr, ajaxOptions, thrownError){
				alert(thrownError);
				$(this_is_item_list_product).show();
				$(this_is_pagination_product).show();
				loader_hide(".loader");
				$('input[type="checkbox"]').prop('disabled',false);
				$('.datepicker-start').prop('disabled',false);
				$('.datepicker-end').prop('disabled',false);
				$('.reset-filter').prop('disabled',false);
			}
		});
};
ajaxLoadPage();

return false;
}

var sidebar_categories = $('.ui.accordion').accordion();

$(sidebar_categories).accordion({
selector: {
	trigger: '.icon'
},
exclusive: false,
onOpening: function (item) {
	var title = $(this).prev();
	var icon  = $(title).find('i');
	if($(icon).hasClass('plus')){
		$(icon).removeClass('plus');
		$(icon).addClass('minus');
	}
},
onClosing: function (item) {
	var title = $(this).prev();
	var icon  = $(title).find('i');
	if($(icon).hasClass('minus')){
		$(icon).removeClass('minus');
		$(icon).addClass('plus');
	}
}
});

var _uri_cat_or_prod = _uri_init.segment(0);
var _uri_page 		 = _uri_init.segment(1);

if(_uri_segment_1 == 'categories' || _uri_segment_1 == 'product' || _uri_segment_1 == 'product-category' || _uri_segment_1 == 'demo_product'){

if(_uri_page === undefined || _uri_page == '' || $.isNumeric(_uri_page) == true ||  _uri_segment_1 == 'product-category' && !$.isNumeric(_uri_page)){

	var _this_location_href = window.location.href,
	_url 					= getUrlVars(_this_location_href);
	if(_uri_page === undefined || _uri_page == ''){
		_uri_page = 1;
	}
	var classname = [];

	$('input[type="checkbox"]').each(function() {
		if (this.checked) {
			classname.push($(this).data('index'));
		}
	});

	if(_uri_segment_1 == 'product-category'){
		_uri_page = 1;

		var _get_flag = $('input[type="checkbox"]:checked').data('categories');
		var _get_slug = $('input[type="checkbox"]:checked').val();
		var _url 	  		= {};
		_url[_get_flag] = _get_slug; 

	}

	var data_options = {url : _url,page: _uri_page,classname: classname};
	var start_date   = $('.datepicker-start');
	var end_date     = $('.datepicker-end');

	search_datepicker(start_date);
	search_datepicker(end_date);
	filterProductsByTag(_url,data_options);

	$('input[type="checkbox"]').on('click', function(e) {

		$('.drawer-toggle-sidebar').trigger('click');

		var _this_classname = $(this).data('index');
		var _this_el 		= $(this);
		var _id 			= $(_this_el).val();
		var _url 			= getUrlVars(_id);

		var get_parent_accordion = $(this).parent().parent();
		var children_accordion   = $(get_parent_accordion).find('i');
		var children_flag        = false;

		if(children_accordion.length > 0){

			$(children_accordion).trigger('click');

			var get_checked = $(this).prop('checked');
			var content_children_accordion  = $(get_parent_accordion).prop('id');
			var checkbox_children_accordion = $('.'+content_children_accordion).find('input[type="checkbox"]');

			if(get_checked == true){

				if(checkbox_children_accordion.length > 0){
					$.each(checkbox_children_accordion,function(i,v){
						$(v).prop('checked',true);
					});
				}
			} else if(get_checked == false) {
				if(checkbox_children_accordion.length > 0){
					$.each(checkbox_children_accordion,function(i,v){
						$(v).prop('checked',false);
					});
				}
			}

			children_flag = true;
		}


		setTimeout(function(){

			var data 		= {};
			var dataStrings = [];
			var classname 	= [];

			$('input[type="checkbox"]').each(function() {

				if (this.checked) {
					if (data[this.name] === undefined) data[this.name] = [];
					data[this.name].push(this.value);
					classname.push($(this).data('index'));
				}
			});

			$.each(data, function(key, value)
			{
				dataStrings.push(key + "=" + value.join('||'));
			});

			var result = dataStrings.join('&');

			var _this_id			= $(_this_el).val(),
			_this_location_href 	= window.location.href,
			_slug 					= $(_this_el).data('slug'),
			_categories 			= $(_this_el).data('categories'),
			_result_url 			= _this_location_href,
			_current_url 			= current_url();

			var _uri_cat_or_prod = _uri_init.segment(0);
			var _uri_slug 		 = _uri_init.segment(1);
			_uri_page 		 	 = 1;


			if(children_flag == true){
				_url 	= getUrlVariable(result);
				_slug 	= ''; 
			} else {
				_url = getUrlVars(result);
			}

			_data_options = {
				url 		: _url,
				slug 		: _slug,
				categories 	: _categories,
				page 		: _uri_page,
				classname   : classname,
				current_classname : _this_classname,
				uri_cat     : _uri_cat_or_prod,
				uri_slug    : _uri_slug,
				current_url : _current_url,
				checked 	: data
			};

			filterProductsByTag(result,_data_options);

		},10);

	});

	$(document).find('#pagination-product').on('click','a',function(e){
		e.preventDefault();

		var this_is_pagination_product = $('#pagination-product');

		var page 			= $(this_is_pagination_product).pagination('getCurrentPage'),
		url 				= $(this).prop('href'),
		url 				= getUrlVars(url),
		classname 			= get_checkbox_classname(),
		data_options 		= {url : url,page: page,classname:classname};

		filterProductsByTag(url,data_options);
	});

	$(document).find('#item-list-product').on('change','.show',function(e){
		e.preventDefault();
		var url 			= window.location.href,
		url 				= getUrlVars(url),
		classname 			= get_checkbox_classname(),
		show_display 		= $(this).val();
		if (url['show'] === undefined){
			url['show'] = show_display;
		} else if(show_display == 'default'){
			delete url['show'];
		} else {
			delete url['show'];
			url['show'] = show_display;
		}

		if(_uri_page === undefined){
			_uri_page = 1;
		}
		var data_options 		= {url : url,page:_uri_page,classname:classname};
		filterProductsByTag(url,data_options);

	});

	$(document).find('#item-list-product').on('change','.sort-by',function(e){
		e.preventDefault();
		var url 			= window.location.href,
		url 				= getUrlVars(url),
		classname 			= get_checkbox_classname(),
		sort_value 			= $(this).val(),
		sort_name			= $(this).find(':selected').data('name');

		if (url[sort_name] === undefined){
			url[sort_name] = sort_value;
		} else {
			delete url[sort_name];
			url[sort_name] = sort_value;
		}
		if(_uri_page === undefined){
			_uri_page = 1;
		}
		var data_options 		= {url : url,page:_uri_page,classname:classname};

		filterProductsByTag(url,data_options);

	});

	$(document).on('click','.reset-filter',function(e){
		e.preventDefault();

		$('.drawer-toggle-sidebar').trigger('click');

		var url = window.location.href;
		var a = url.indexOf("?");
		var b =  url.substring(a);
		var c = url.replace(b,"");
		url = c;

		_uri_page = 1;

		$('.datepicker-start').val('');
		$('.datepicker-end').val('');
		
		var data_options 			= {url : url,page: _uri_page};
		filterProductsByTag(_url,data_options);
	});
}
}


if(_uri_segment_1 == 'search' || _uri_segment_1 == 'demo_search'){
var _this_location_href = window.location.href,
_url 					= getUrlVars(_this_location_href);
if(_uri_page === undefined){
	_uri_page = 1;
}
var data_options 			= {url : _url,page: _uri_page};
searchProduct(_url,data_options);

$(document).on('click','.reset-filter',function(e){
	e.preventDefault();

	var url = window.location.href;
	var a = url.indexOf("?");
	var b =  url.substring(a);
	var c = url.replace(b,"");
	url = c;

	_uri_page = 1;

	var data_options 			= {url : url,page: _uri_page};
	searchProduct(_url,data_options);

	$('#search-header').val('');
	$('.form-advanced-search').find('strong').html('');
	$("input[name='k']").val('');
	$(".gender-select2").val(null).trigger("change");
	$(".size-select2").val(null).trigger("change");
	$(".store-select2").val(null).trigger("change");
	clear_datepicker('.datepicker-search-start');
	clear_datepicker('.datepicker-search-end');
});

$(document).find('#search-list').on('change','.show',function(e){
	e.preventDefault();
	var url 			= window.location.href,
	url 				= getUrlVars(url),
	classname 			= get_checkbox_classname(),
	show_display 		= $(this).val();
	if (url['show'] === undefined){
		url['show'] = show_display;
	} else if(show_display == 'default'){
		delete url['show'];
	} else {
		delete url['show'];
		url['show'] = show_display;
	}

	if(_uri_page === undefined){
		_uri_page = 1;
	}
	var data_options 		= {url : url,page:_uri_page,classname:classname};
	searchProduct(url,data_options);

});

$(document).find('#search-list').on('change','.sort-by',function(e){
	e.preventDefault();
	var url 			= window.location.href,
	url 				= getUrlVars(url),
	classname 			= get_checkbox_classname(),
	sort_value 			= $(this).val(),
	sort_name			= $(this).find(':selected').data('name');

	if (url[sort_name] === undefined){
		url[sort_name] = sort_value;
	} else {
		delete url[sort_name];
		url[sort_name] = sort_value;
	}

	if(_uri_page === undefined){
		_uri_page = 1;
	}
	var data_options 		= {url : url,page:_uri_page,classname:classname};
	searchProduct(url,data_options);

});

$('.form-search-product').on('submit',function(e){
	e.preventDefault();
	var _this_location_href = window.location.href,
	value_data = $(this).serializeArray(),
	value_arr = $(this).serialize(),
	_uri_page = 1;

	var data 		= {};
	var dataStrings = [];

	var keyword = $(this).find("input[name='k']").val();
	var start   = $(this).find("input[name='start']").val(); 
	var end   	= $(this).find("input[name='end']").val(); 

	if(keyword != ''){
		if (data['k'] === undefined) data['k'] = [];
		data['k'].push(keyword);
	}
	if(start != ''){
		if (data['start'] === undefined) data['start'] = [];
		data['start'].push(start);
	}
	if(end != ''){
		if (data['end'] === undefined) data['end'] = [];
		data['end'].push(end);
	}
	var size_select2 				= $('.size-select2');
	size_select2					= $(size_select2).select2('val');
	if(size_select2.length > 0){
		if (data['size'] === undefined) data['size'] = [];
		data['size'].push(size_select2);
	}
	var gender_select2 				= $('.gender-select2');
	gender_select2				= $(gender_select2).select2('val');
	if(gender_select2.length > 0){
		if (data['gender'] === undefined) data['gender'] = [];
		data['gender'].push(gender_select2);
	}
	var store_select2 				= $('.store-select2');
	store_select2				= $(store_select2).select2('val');
	if(store_select2.length > 0){
		if (data['store_location'] === undefined) data['store_location'] = [];
		data['store_location'].push(store_select2);
	}
	$.each(data, function(key, value)
	{
		dataStrings.push(key + "=" + value.join('||'));
	});
	var result 	= dataStrings.join('&');
	result 		= result.replace(/,/g,'||'),
	_url 		= getUrlVariable(result);
	var data_options 			= {url : _url,page: _uri_page};
	searchProduct(result,data_options);
});

$(document).find('#pagination-search-product').on('click','a',function(e){
	e.preventDefault();

	var this_is_pagination_product = $('#pagination-search-product');

	var page 			= $(this_is_pagination_product).pagination('getCurrentPage'),
	url 				= $(this).prop('href'),
	url 				= getUrlVars(url),
	classname 			= get_checkbox_classname(),
	data_options 		= {url : url,page: page,classname:classname};

	searchProduct(url,data_options);
});

}


var slideWrapper = $(".slideshow-main"),
iframes = slideWrapper.find('.embed-player'),
lazyImages = slideWrapper.find('.slideshow-items'),
lazyCounter = 0;
function postMessageToPlayer(player, command){
if (player == null || command == null) return;
player.contentWindow.postMessage(JSON.stringify(command), "*");
}

function playPauseVideo(slick, control){
var currentSlide, slideType, startTime, player, video;

currentSlide = slick.find(".slick-current");

slideType = currentSlide.attr("class").split(" ")[1];
player = currentSlide.find("iframe").get(0);
startTime = currentSlide.data("video-start");

if (slideType === "vimeo") {
	switch (control) {
		case "play":
		if ((startTime != null && startTime > 0 ) && !currentSlide.hasClass('started')) {
			currentSlide.addClass('started');
			postMessageToPlayer(player, {
				"method": "setCurrentTime",
				"value" : startTime
			});
		}
		postMessageToPlayer(player, {
			"method": "play",
			"value" : 1
		});
		break;
		case "pause":
		postMessageToPlayer(player, {
			"method": "pause",
			"value": 1
		});
		break;
	}
} else if (slideType === "youtube") {
	switch (control) {
		case "play":
		postMessageToPlayer(player, {
			"event": "command",
		});
		postMessageToPlayer(player, {
			"event": "command",
			"func": "playVideo"
		});
		break;
		case "pause":
		postMessageToPlayer(player, {
			"event": "command",
			"func": "pauseVideo"
		});
		break;
	}
} else if (slideType === "video") {
	video = currentSlide.children("video").get(0);
	if (video != null) {
		if (control === "play"){
			video.play();
		} else {
			video.pause();
		}
	}
}
}

function resizePlayer(iframes, ratio) {
if (!iframes[0]) return;
var win = $(".main-slider"),
width = win.width(),
playerWidth,
height = win.height(),
playerHeight,
ratio = ratio || 1110/623;

iframes.each(function(){
	var current = $(this);
	if (width / ratio < height) {
		playerWidth = Math.ceil(height * ratio);
		current.width(playerWidth).height(height).css({
			left: (width - playerWidth) / 2,
			top: 0
		});
	} else {
		playerHeight = Math.ceil(width / ratio);
		current.width(width).height(playerHeight).css({
			left: 0,
			top: (height - playerHeight) / 2
		});
	}
});
}

function loader_show(element,textload = ''){
if(textload == ''){
	textload = false;
}
$(element).busyLoad("show", {
	text: textload,
	color: "#707070",
	background: "rgba(255,255,255,1)"
});
}

function loader_hide(element){
$(element).busyLoad("hide");
}

$(function(){

$(document).on('bl.shown', function (event, $container, $targetNode) {
	$($targetNode).css('height','200px');
});
$(document).on('bl.hidden', function (event, $container, $targetNode) {
	$($targetNode).css('height','0px');
});


var window_el 			= $(window);
var drawer_el			= $('.drawer');
var drawer_sidebar      = $('.drawer-sidebar');

var drawer_nav_opt 		= {
	class: {
		nav: 'drawer-nav',
		toggle: 'drawer-toggle',
		overlay: 'drawer-overlay',
		open: 'drawer-open',
		close: 'drawer-close',
		dropdown: 'drawer-dropdown'
	},
	iscroll: {
		mouseWheel: false,
		preventDefault: false
	},
	showOverlay: true
};

var drawer_sidebar_opt 	= {
	class: {
		nav: 'drawer-menu-sidebar',
		toggle: 'drawer-hamburger-sidebar',
		overlay: 'drawer-overlay-sidebar',
		open: 'drawer-open',
		close: 'drawer-close',
		dropdown: 'drawer-dropdown'
	},
	iscroll: {
		mouseWheel: false,
		preventDefault: false
	},
	showOverlay: true
};
if(drawer_el.length > 0){
	$(drawer_el).drawer(drawer_nav_opt);
}

$('.box-info-product').matchHeight();
setTimeout(function(){
	if(drawer_sidebar.length > 0){
		$(drawer_sidebar).drawer(drawer_sidebar_opt);
	}
},10);
function checkWidth() {
	var windowsize = $(window_el).width();
	var zoom_img_contactus = $('#ex1');
	if (windowsize > 991) {
		setTimeout(function(){
			if(drawer_el.length > 0){
				$(drawer_el).drawer('destroy');
			}
		});
		setTimeout(function(){
			if(drawer_sidebar.length > 0){
				$(drawer_sidebar).drawer('destroy');
			}
		},10);

		if(zoom_img_contactus.length > 0){
			$(zoom_img_contactus).trigger('zoom.destroy');

			$(zoom_img_contactus).zoom({
				magnify: 1,
				on: 'mouseover',
				touch: true
			});
		}

	} else {

		if(zoom_img_contactus.length > 0){
			$(zoom_img_contactus).zoom();
			$(zoom_img_contactus).trigger('zoom.destroy');
			$(zoom_img_contactus).on('click',function(e){
				e.preventDefault();
				var image = $(this).find('img').attr('src');

				var win = window.open(image, '_blank');
				if (win) {
					win.focus();
				}

			});
		}

		setTimeout(function(){
			if(drawer_el.length > 0){
				$(drawer_el).drawer(drawer_nav_opt);
			}
		});
		setTimeout(function(){
			if(drawer_sidebar.length > 0){
				$(drawer_sidebar).drawer(drawer_sidebar_opt);
			}
		},10);
	}
}

checkWidth();

$(window).resize(function() {
	var window_w = $(this).width();

	checkWidth();	

});
$('.product-items').matchHeight();

$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {

	if (!$(this).next().hasClass('show')) {
		$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
	}
	var $subMenu = $(this).next(".dropdown-menu");
	$subMenu.toggleClass('show');


	$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
		$('.dropdown-submenu .show').removeClass("show");
	});

	return false;
});
var originalLeave = $.fn.popover.Constructor.prototype.leave;

$.fn.popover.Constructor.prototype.leave = function(obj){

	var self = obj instanceof this.constructor ?

	obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)

	var container, timeout;

	originalLeave.call(this, obj);

	if(obj.currentTarget) {

		container = $(obj.currentTarget).siblings('.popover')

		timeout = self.timeout;

		container.one('mouseenter', function(){

			clearTimeout(timeout);

			container.one('mouseleave', function(){

				$.fn.popover.Constructor.prototype.leave.call(self, self);

			});

		})

	}

};

var base_url 		= $('base').attr('href');
var page_loading 	= $('.dimmer');

var icheck_cat 		= $('.categories-check');

var _uri_init = new URI();

function current_url(){
	var current_url = window.location.href,
	a = current_url.indexOf("?"),
	b =  current_url.substring(a),
	c = current_url.replace(b,"");
	current_url = c;
	return current_url;
}

var _current_url = current_url();
var options = {
	url 			: '',
	slug 			: '',
	categories 		: '',
	checked 		: '',
	current_url    	: '',
	selector		: '',
	indexes 		: ''
}

var icheck_cat 		= $('.categories-check');
var _history 		= [];
var _result_url     = '';
var _uri_init	    = new URI();
var icheck_cat 		= $('.categories-check');
var _current_url 	= current_url();

var this_default_history_push = [];

var history_push 	= function(option){
	history.pushState(option, null,option.result_url);
}

var this_is_item_list_product = $('#item-list-product');

function _this_loader_show(selector){
	$(selector).find('.segment').dimmer('show');
	var _this_element = $(selector).find('.segment').find('.dimmer');
	var _this_element_text = $(selector).find('.segment').find('.loader');
	$(selector).find('.segment').removeClass('dimmed');
	if($(_this_element).hasClass('disabled')){
		$(_this_element).removeClass('disabled');
	}
	if($(_this_element_text).hasClass('disabled')){
		$(_this_element_text).removeClass('disabled');
	}
	$(_this_element).addClass('active');
	$(_this_element_text).addClass('active');
}
function _this_loader_hide(){
	$('.segment').dimmer('hide');
}
function _this_loader_disabled(selector){
	$(selector).find('.segment').dimmer('hide');
	var _this_element = $(selector).find('.segment').find('.dimmer');
	var _this_element_text = $(selector).find('.segment').find('.loader');
	$(selector).find('.segment').addClass('dimmed');
	if($(_this_element).hasClass('active')){
		$(_this_element).removeClass('active');
	}
	if($(_this_element_text).hasClass('active')){
		$(_this_element_text).removeClass('active');
	}
	$(_this_element).addClass('disabled');
	$(_this_element_text).addClass('disabled');
}
function ajax_product(action,options){
	var this_uri = new URI(),
	base_url 	 = $('base').attr('href'),
	this_get_path_category 	= this_uri.segment(1),
	this_get_path_slug 		= this_uri.segment(2),
	this_get_query 			= this_uri.query(),
	data_options = {}

	if(this_get_path_category == 'categories'){

		if(action === undefined || action == ''){
			data_options = {
				uri 	: this_get_path_slug,
				query   : this_get_query
			}

			$.ajax({
				url: base_url + 'filteringselect',
				data: data_options,
				dataType: "json",
				type: "POST",
				beforeSend: function(data){
					$('.segment').dimmer('show');
				},
				success: function(result) {

				},
				complete: function(){
					$('.segment').dimmer('hide');
				}
			});

		} else if(action == 'click-categories') {
			data_options = options;
		}
	}
}

slideWrapper.on("init", function(slick){
	slick = $(slick.currentTarget);
	setTimeout(function(){
		playPauseVideo(slick,"play");
	}, 1000);
	resizePlayer(iframes, 1110/623);
});
slideWrapper.on("beforeChange", function(event, slick) {
	slick = $(slick.$slider);
	playPauseVideo(slick,"pause");
});
slideWrapper.on("afterChange", function(event, slick) {
	slick = $(slick.$slider);
	playPauseVideo(slick,"play");
});
slideWrapper.on("lazyLoaded", function(event, slick, image, imageSource) {
	lazyCounter++;
	if (lazyCounter === lazyImages.length){
		lazyImages.addClass('show');
	}
});

$(slideWrapper).slick({
	arrow: true,
	dots: true,
	infinite: true,
	slidesToShow: 1,
	slidesToScroll: 1,
	autoplay: true,
	autoplaySpeed: 4000,
});

$('.product-home-slide').slick({
	arrow: true,
	dots: false,
	infinite: true,
	slidesToShow: 3,
	slidesToScroll: 1,
	autoplay: true,
	autoplaySpeed: 4000,
	responsive: [
	{
		breakpoint: 992,
		settings: {
			slidesToShow: 2,
			slidesToScroll: 1,
		}
	},
	{
		breakpoint: 450,
		settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
		}
	}
	]
});

var availableTags = [
"ActionScript",
"AppleScript",
"Asp",
"BASIC",
"C",
"C++",
"Clojure",
"COBOL",
"ColdFusion",
"Erlang",
"Fortran",
"Groovy",
"Haskell",
"Java",
"JavaScript",
"Lisp",
"Perl",
"PHP",
"Python",
"Ruby",
"Scala",
"Scheme"
];

var search_el = $('#search-header');
var size_select2 	= $('.size-select2');
var gender_select2 	= $('.gender-select2');
var store_select2 	= $('.store-select2');

if ($.fn.select2) {
	$(size_select2).select2({placeholder: 'Size'});
	$(gender_select2).select2({placeholder: 'Gender'});
	$(store_select2).select2({placeholder: 'Store Location'});
}

function _this_slick(selector,options){
	$(selector).slick(options);
}
function _this_slick_destroy(selector){
	$(selector).slick('destroy');
}

$('.datepicker').datepicker({
	format: 'd MM yyyy',
	autoclose: true
});

$('.product-slide').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	fade: true,
	asNavFor: '.product-slide-nav'
});
$('.product-slide-nav').slick({
	slidesToShow: 4,
	slidesToScroll: 1,
	asNavFor: '.product-slide',
	dots:false,
	focusOnSelect: true,
	arrows:false
});

var options_related = {
	arrow: true,
	dots: false,
	infinite: true,
	slidesToShow: 4,
	slidesToScroll: 1,
	autoplay: true,
	autoplaySpeed: 4000,
	responsive: [
	{
		breakpoint: 1199,
		settings: {
			slidesToShow: 3,
			slidesToScroll: 1,
		}
	},
	{
		breakpoint: 992,
		settings: {
			slidesToShow: 2,
			slidesToScroll: 1,
		}
	},
	{
		breakpoint: 450,
		settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
		}
	}
	]
}

function calendar_product(options){

	$('.popover-content').remove();
	$('.popover').remove();

	var day = options.data;

	var calendar_init 	= $(document).find('#calendar');
	if(calendar_init.length > 0){
		var glob_date 		= new Date();
		var today 			= new Date(glob_date.getFullYear(), glob_date.getMonth(), glob_date.getDate());
		var tooltip_event 	= [];
		var dates 	  		= [];
		var push_stock     	= [];
		var push_rental     = [];
		var _data_stock     = [];
		var _data_rental 	= [];
		var class_day 		= $(calendar_init).data('class');
		var datetime  		= $(calendar_init).data('date');
		var cal_stock 		= $(calendar_init).data('stock');
		var cal_rental		= $(calendar_init).data('rental');

		class_day 	  = class_day.split(",");
		datetime 	  = datetime.split(",");
		cal_stock 	  = cal_stock.split(",");
		cal_rental 	  = cal_rental.split(",");

		if(datetime.length > 0){
			for(var index=0; index<datetime.length; index++){
				dates[datetime[index]] 		 = class_day[index];
				push_stock[datetime[index]]  = cal_stock[index];
				push_rental[datetime[index]] = cal_rental[index];
			}
		}

		$(calendar_init).datepicker({	
			templates: {
				leftArrow: '<i class="arrow left icon"></i>',
				rightArrow: '<i class="arrow right icon"></i>'
			},
			defaultViewDate: 'month',
			stepMonths: 0,
			changeMonth: false,
			changeYear: false,
			startDate: '-0d',
			autoclose: true,
			beforeShowDay: function(date) {
				var search = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

				if (search in push_stock) {
					var _this_data = push_stock[search].split(',');
					_data_stock.push(_this_data);
				}

				if (search in push_rental) {
					var _this_data = push_rental[search].split(',');
					_data_rental.push(_this_data);
				}

				if (search in dates) {

					var split_date = search.split('/');
				var split_data = dates[search].split(','); //class

				tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

				return {
					classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
				}
			}
		},
	}).on('changeDate', function (ev) {
		$('.popover-content').remove();
		$('.popover').remove();

		var dayLive = ev.format();
		var content = '0/0';
		if(day === undefined){
			$('.popover-content').remove();
			$('.popover').remove();
		} else {
			content = day['default'].available+'/'+day['default'].stock;
			$.each(day,function(i,v){
				if(dayLive == i && i !== 'default'){
					content = v.available+'/'+v.stock;
				}
			});
			$('.popover-content').remove();
			$('.popover').remove();
		}
		var target = $(ev.target);
		var _this_element = $(this).find('.datepicker-days').find('.day');

		$(_this_element).each(function(i,v){

			if($(v).hasClass('active')){

				$(v).popover({
					container: 'body',
					trigger: 'click',
					title: 'Availability',
					content: content,
					html: true,
					placement: 'top',
				});

				$(v).popover('show');
			} else {
				$(v).popover('hide');
			}

		});

		$(_this_element).trigger('mouseenter touchstart');

	}).on('changeMonth',function(ev){
		$('.popover-content').remove();
		$('.popover').remove();
	});

	$(calendar_init).find('.datepicker-switch').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
	});
}

$(calendar_init).on('mouseleave touchend touchcancel',function() {
	$('.popover-content').remove();
	$('.popover').remove();
});

}

var cat_slug = $("input[name='cat_slug[]']");
var cat_slug_category = {};

if(cat_slug.length > 0){
$.each(cat_slug,function(i,v){
	var cat_slug_value = $(this).val();
	var cat_slug_flag  = $(this).data('categories');
	if (cat_slug_category[cat_slug_flag] === undefined) cat_slug_category[cat_slug_flag] = [];
	var split_cat = cat_slug_value.split(',');
	cat_slug_category[cat_slug_flag].push(cat_slug_value);
});
}

var cat_product = $("input[name='cat[]']");
var prod_id 	= $("input[name='prod']");
var cat_id 		= [];
var subtitle 	= $('.subtitle-product-detail');

if(cat_product.length > 0 && prod_id.length > 0){
prod_id 	= $(prod_id).val();
$.each(cat_product,function(i,v){
	var value = $(v).val();
	cat_id.push(value);
});
$.ajax({
	url: base_url + 'getrelated',
	data: {'product_id':prod_id,'category_id':cat_id,'category_slug':cat_slug_category},
	type: 'POST',
	dataType: 'json',
	beforeSend: function(data){
		_this_loader_show('.wrapper-box-related-product');
	},
	success: function(result){
		var _wrapper_related_slide = $('.wrapper-related-slide');
		var _related_slide 		   = $(document).find('.related-slide');

		$(_wrapper_related_slide).empty();

		if(result.flag == true){
			$.each(subtitle,function(i,v){
				if($(v).hasClass('related') && $(v).hasClass('hide')){
					$(v).removeClass('hide');
					$(v).addClass('show');
				}
			});
			setTimeout(function(){
				if(!$.trim($(_wrapper_related_slide).html()).length){
					$(_wrapper_related_slide).append(result.template);
					_this_slick('.related-slide',options_related);
					setTimeout(function(){
						$(document).find('.product-items').matchHeight();
					});
				}
				_this_loader_disabled('.wrapper-box-related-product');
			});
		} else {
			$.each(subtitle,function(i,v){
				if($(v).hasClass('related')){
					$(v).parent().parent().remove();
				}
			});
			_this_loader_disabled('.wrapper-box-related-product');
		}
	},
	fail: function(){
		_this_loader_disabled('.wrapper-box-related-product');
	}
});
} else {
$.each(subtitle,function(i,v){
	if($(v).hasClass('related')){
		$(v).parent().parent().remove();
	}
});
}

prod_id 	= $("input[name='prod']");
subtitle 	= $('.subtitle-product-detail');

if(prod_id.length > 0){
prod_id 	= $(prod_id).val();
$.ajax({
	url: base_url + 'getsugges',
	data: {'product_id':prod_id},
	type: 'POST',
	dataType: 'json',
	beforeSend: function(){
		_this_loader_show('.wrapper-box-sugges-product');
	},
	success: function(result){
		var _wrapper_sugges_slide = $('.wrapper-sugges-slide');
		var _sugges_slide 		   = $(document).find('.sugges-slide');

		$(_wrapper_sugges_slide).empty();

		if(result.flag == true){
			$.each(subtitle,function(i,v){
				if($(v).hasClass('sugges') && $(v).hasClass('hide')){
					$(v).removeClass('hide');
					$(v).addClass('show');
				}
			});
			setTimeout(function(){
				if(!$.trim($(_wrapper_sugges_slide).html()).length){
					$(_wrapper_sugges_slide).append(result.template);
					_this_slick('.sugges-slide',options_related);
					setTimeout(function(){
						$(document).find('.product-items').matchHeight();
					});
				}
				_this_loader_disabled('.wrapper-box-sugges-product');
			});
		} else {
			$.each(subtitle,function(i,v){
				if($(v).hasClass('sugges')){
					$(v).parent().parent().remove();
				}
			});
			_this_loader_disabled('.wrapper-box-sugges-product');
		}
	},
	fail: function(){
		_this_loader_disabled('.wrapper-box-sugges-product');
	}
});
} else {
$.each(subtitle,function(i,v){
	if($(v).hasClass('sugges')){
		$(v).parent().parent().remove();
	}
});
}
var
History = window.History,
$log 	= $('#log');

function docHistory(){
var uri = new URI();
}

docHistory();

var radio_size 	= $("input[name='radio_size']");
var radio_size_product = $("input[name='radio_size_product']");
var calendar 	= $('.main-calendar');

if(radio_size.length == 1){
var value = $(radio_size).val();
_this_loader_show('.wrapper-datepicker');
if(calendar.length > 0){
	$(calendar).css('opacity',0);
}
$.ajax({
	url: base_url + 'getcalendar',
	data: {'product_id':prod_id,'product_sizestock_id':value},
	type: 'POST',
	dataType: 'json',
	beforeSend: function(data){
		_this_loader_show('.wrapper-datepicker');
	},
	success: function(result){
		if(result.flag == true){

			var _this_main_calendar = $('.main-calendar');
			$(_this_main_calendar).empty();
			var options = result;
			setTimeout(function() {
				if(!$.trim($(_this_main_calendar).html()).length) {
					$(_this_main_calendar).append(result.template);

					setTimeout(function(){
						calendar_product(options);
						_this_loader_disabled('.wrapper-datepicker');
						if(calendar.length > 0){
							$(calendar).css('opacity',1);
						}
					});

				} 
			});
		}
	},
	fail: function(){
		if(calendar.length > 0){
			$(calendar).css('opacity',1);
		}
		_this_loader_disabled('.wrapper-datepicker');
	}
});
}

$(radio_size_product).on('change',function(e){
e.preventDefault();
_this_loader_show('.wrapper-datepicker');
var value = $(this).val();
if(calendar.length > 0){
	$(calendar).css('opacity',0);
}
$.ajax({
	url: base_url + 'getcalendar',
	data: {'product_id':prod_id,'product_sizestock_id':value},
	type: 'POST',
	dataType: 'json',
	beforeSend: function(data){
		_this_loader_show('.wrapper-datepicker');
		$('.popover-content').remove();
		$('.popover').remove();
	},
	success: function(result){
		if(result.flag == true){

			var _this_product_sizestock_name = result.product_sizestock;
			if(_this_product_sizestock_name !== undefined || _this_product_sizestock_name.length > 0){
				$('.subtitle-datepicker').html(_this_product_sizestock_name[0].product_size);
			}
			
			var _this_main_calendar = $('.main-calendar');
			$(_this_main_calendar).empty();
			var options = result;
			setTimeout(function() {
				if(!$.trim($(_this_main_calendar).html()).length) {
					$(_this_main_calendar).append(result.template);

					setTimeout(function(){
						calendar_product(options);
						_this_loader_disabled('.wrapper-datepicker');
						if(calendar.length > 0){
							$(calendar).css('opacity',1);
						}
						$('html, body').animate({
							scrollTop: $(_this_main_calendar).offset().top-15
						});
					});

				} 
			});
		}
	},
	fail: function(){
		if(calendar.length > 0){
			$(calendar).css('opacity',1);
		}
		_this_loader_disabled('.wrapper-datepicker');
	}
});
});
});	