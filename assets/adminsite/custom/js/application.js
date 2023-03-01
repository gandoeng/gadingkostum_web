$.fn.clear = function()
{
	var $form = $(this);

	$form.find('input:text, input:password, input:file, input:hidden, textarea').val('');
	$form.find('img').attr('src','assets/images/no-image.png');
	if($form.find('.colorpicker') !== undefined){
		$form.find('.colorpicker').spectrum("set", '#000');
	}
	$form.find('select option:selected').removeAttr('selected');
	$form.find('input:checkbox, input:radio').removeAttr('checked');

	return this;
}; 

$.fn.swapWith = function(to) {
	return this.each(function() {
		var copy_to = $(to).clone(true);
		var copy_from = $(this).clone(true);
		$(to).replaceWith(copy_from);
		$(this).replaceWith(copy_to);
	});
};

Date.prototype.addDays = function(days) {
	var date = new Date(this.valueOf());
	date.setDate(date.getDate() + days);
	return date;
}

function getDatesArr(startDate, stopDate) {
	var dateArray = new Array();
	var currentDate = startDate;
	while (currentDate <= stopDate) {
		dateArray.push(new Date (currentDate));
		currentDate = currentDate.addDays(1);
	}
	return dateArray;
}

function responsive_filemanager_callback(field_id){ 
	var id = $('#' + field_id);
	var url = $('#' + field_id).val();

	$('.' + field_id).attr('src',url);
	$.fancybox.close();
}

function OnMessage(e){

	var event = e.originalEvent;
	if(event.data.sender === 'RESPONSIVEfilemanager'){
		if(event.data.field_id){
			var fieldID=event.data.field_id;
			var url=event.data.url;

			$('#'+fieldID).val(url).trigger('change');
			$.fancybox.close();
			$(window).off('message', OnMessage);
		}
	}
}

function open_popup(url)
{
	var w = 880;
	var h = 570;
	var l = Math.floor((screen.width-w)/2);
	var t = Math.floor((screen.height-h)/2);
	var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
}

function moreupload(base_url,id){

	var this_element = "'image"+id+"'";
	var template     = '<div style="margin-bottom:15px; margin-top:15px;" class="item-more-'+id+'">'

	+ '<div class="input-group">'
	+ '<input type="hidden" name="product_image_id[]">'
	+ '<input id="image'+id+'" type="text" class="form-control" name="product_image[]" data-more="image">'
	+ '<a class="input-group-addon btn btn-default btn-flat input-group-addon" type="button" onclick="openKCFinder('+this_element+')">Browse</a>'
	+ '</div>'
	+ '<br>'

	+ '<div class="form-group">'
	+ '<div class="col-lg-6">'
	+ '<img id="preview-image'+id+'" class="image'+id+' img-responsive img-thumbnail preview-more" src="'+base_url+'assets/images/no-image.png">'
	+ '</div>'

	+ '<div class="col-lg-6 text-right">'
	+ '<a class="btn btn-danger btn-cancel-more btn-flat" data-more="image" id="item-more-'+id+'"> Remove <i class="fa fa-arrow-up"></i></a>'
	+ '</div>'

	+ '</div>'
	+ '</div>';
	return template;

}

function moresizestock(){

	var template = '<tr>'
	+ '<td><i class="fa fa-arrows-alt" aria-hidden="true"></i></td>'
	+ '<td style="width: 180px;"><input type="hidden" name="product_sizestock_id[]"><input style="margin: 0 auto;" type="text" name="product_size[]" class="form-control"></td>'
	+ '<td style="width: 65px;"><input type="text" style="margin: 0 auto;" name="product_stock[]" class="stockmask form-control"></td>'
	+ '<td><textarea wrap="hard" style="height: 100px;" name="product_estimasiukuran[]" class="form-control"></textarea></td>'
	+ '<td><button class="remove-item-table circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button></td>'
	+ '</tr>';
	return template;

}

function moreextrapayment(){

	var template = '<tr>'
	+ '<td style="padding: 8px;width: 150px;">'
	+ '<select class="form-control" name="rental_extrapayment_flag[]">'
	+ '<option value="sewa">Harga Sewa</option>'
	+ '<option value="deposit">Harga Deposit</option>'
	+ '</select>'
	+ '</td>'
	+ '<td colspan="3" style="padding: 8px;"><textarea placeholder="Notes payment:" wrap="hard" style="text-align:left;" name="rental_extranote[]" class="form-control"></textarea></td>'
	+ '<td>'
	+ '<input type="hidden">'
	+ '</td>'
	+ '<td class="extrapayment-row extrapayment-sewa" style="padding: 8px;">'
	+ '<input type="hidden" name="rental_extrapayment_id[]">'
	+ '<input type="text" style="margin: 0 auto; text-align:center;display:inline-block;" name="rental_extrapayment[]" class="pricemask pricepayment form-control" value="0">'
	+ '</td>'
	+ '<td class="extrapayment-row extrapayment-deposit" style="padding: 8px;"></td>'
	+ '<td>'
	+ '<button style="display: inline-block;margin-left: 5px;vertical-align: top;" class="remove-extrapayment circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>'
	+ '</td>'
	+ '</tr>';
	return template;

}

function moredenda(){

	var template ='<tr class="item-biaya">'
	+ '<td>'
	+ '<textarea placeholder="Notes:" wrap="hard" style="text-align:left;" name="rental_dendanote[]" class="form-control"></textarea>'
	+ '</td>'
	+ '<td>'
	+ '<input type="hidden" name="rental_dendapayment_flag[]" value="denda">'
	+ '<input type="hidden" name="rental_dendapayment_id[]">'
	+ '<input type="text" style="margin: 0 auto; text-align:center;display:inline-block;" name="rental_dendapayment[]" class="pricemask pricepayment form-control" value="0">'
	+ '</td>'
	+ '<td style="width: 1px;text-align: center;padding-left: 0;padding-right: 0;margin-right: 0;margin-left: 0;">'
	+ '<button style="display: inline-block;vertical-align: top;" class="remove-denda circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>'
	+ '</td>'
	+ '</tr>';
	return template;

}

function totalmoreextrapayment(){

	var template = '<tfoot>'
	+ '<tr>'
	+ '<td colspan="1" style="text-align: right;background-color: #D3D3D3;font-weight: bold; padding: 8px;border:1px solid #f4f4f4;">Extra Payment:</td>'
	+ '<td colspan="3" class="total_extrapayment" style="text-align: center;background-color: #D3D3D3;font-weight: bold; padding: 8px;border:1px solid #f4f4f4;"><strong>0</strong></td>'
	+ '</tr>'
	+ '<tr>'
	+ '<td colspan="1" style="text-align: right;background-color: #D3D3D3;font-weight: bold; padding: 8px; border:1px solid #f4f4f4;">Total Order + Extra Payment:</td>'
	+ '<td colspan="3" class="total_with_extrapayment" style="text-align: center;background-color: #D3D3D3;font-weight: bold; padding: 8px;border:1px solid #f4f4f4;"><strong>0</strong></td>'
	+ '</tr>'
	+ '</tfoot>';
	return template;

}

function moresetcustom(data){

	var template = '<tr>'
	+ '<td>'
	+ '<input type="hidden" name="set_custom_detail_id[]">'
	+ '<input type="hidden" name="product_id[]" value="'+data[0].product_id+'">'
	+ '<input type="hidden" name="product_nama[]" value="'+data[0].product_nama+'">'
	+ data[0].product_nama 
	+ '</td>'
	+ '<td>'
	+ '<input type="hidden" name="product_sizestock_id[]" value="'+data[0].product_sizestock_id+'">'
	+ '<input type="hidden" name="product_size[]" value="'+data[0].product_size+'">'
	+ data[0].product_size
	+ '</td>'
	+ '<td>'
	+ '<textarea wrap="hard" style="height: 150px;" name="note[]" class="form-control">'+data[0].note+'</textarea>'
	+ '</td>'
	+ '<td>'
	+ '<button class="remove-item-set-custom circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>'
	+ '</td>'
	+ '</tr>';
	return template;

}

function swap(a, b) {
	a = $(a); b = $(b);
	var tmp = $('<span>').hide();
	a.before(tmp);
	b.before(a);
	tmp.replaceWith(b);
}

function emptyTerimaJenisTransaksi(){
	var template = '<tr class="empty-table">'
	+ '<td colspan="5"></td>'
	+ '</tr>';
	return template;
}

function terimaJenisTransaksi(jenis,value){
	if(jenis == 'sewa'){
		label    = 'Terima Uang Sewa';
	};
	if(jenis == 'deposit'){
		label    = 'Terima Uang Deposit';
	}
	var template = '<tr class="item-terima-uang">'
	+ '<td>'
	+ '<input type="hidden" name="jenis_transaksi_id[]">'
	+ '<input type="hidden" name="jenis_transaksi[]" value="'+jenis+'">'
	+ label
	+ '</td>'
	+ '<td style="width: 150px;">'
	+ '<select class="form-control" name="jenis_transaksi_flag[]">'
	+ '<option value="cash">Cash</option>'
	+ '<option value="transfer">Transfer</option>'
	+ '<option value="debit">Debit</option>'
	+ '</select>'
	+ '</td>'
	+ '<td>'
	+ '<input type="text" style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="jenis_transaksi_nominal[]" class="pricemask pricepayment form-control" value="'+value+'">'
	+ '</td>'
	+ '<td>'
	+ '<textarea placeholder="Notes:" wrap="hard" style="text-align:left;" name="jenis_transaksi_note[]" class="form-control"></textarea>'
	+ '</td>'
	+ '<td>'
	+ '<button class="remove-terima-uang circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>'
	+ '</td>'
	+ '</tr>';
	return template;
}

function kembaliJenisTransaksi(nominal){
	var getNominal = 0;
	if(nominal > 0){
		getNominal = nominal;
	}
	var template = '<tr class="item-kembali-uang">'
	+ '<td>'
	+ '<input type="hidden" name="jenis_transaksi_id[]">'
	+ '<input type="hidden" name="jenis_transaksi[]" value="return">'
	+ 'Kembali Uang Deposit'
	+ '</td>'
	+ '<td style="width: 150px;">'
	+ '<select class="form-control" name="jenis_transaksi_flag[]">'
	+ '<option value="cash">Cash</option>'
	+ '<option value="transfer">Transfer</option>'
	+ '</select>'
	+ '</td>'
	+ '<td>'
	+ '<input type="text" style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="jenis_transaksi_nominal[]" class="pricemask pricepayment form-control" value="'+getNominal+'">'
	+ '</td>'
	+ '<td>'
	+ '<textarea placeholder="Notes:" wrap="hard" style="text-align:left;" name="jenis_transaksi_note[]" class="form-control"></textarea>'
	+ '</td>'
	+ '<td>'
	+ '<button class="remove-kembali-uang circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>'
	+ '</td>'
	+ '</tr>';
	return template;
}

function selectJenisTransaksi(selectValue,value = ''){
	var template = '';
	template += '<div class="form-group">';
	template += '<label>Jenis Transaksi</label>';
	template += '<select class="form-control" name="jenis_transaksi_flag">';
	$.each(selectValue,function(i,v){
		var selected = '';
		if(v == value){
			selected = 'selected';
		}
		template += '<option value="'+v+'" '+selected+'>'+v.toUpperCase()+'</option>';
	});
	template += '</select>';
	template += '</div>';
	return template;
}

function selectDateAdjustment(date = ''){
	var template = '';
	template += '<div class="form-group">';
	template += '<label>Tanggal</label>';
	template += '<input type="text" class="form-control adjustment-datepicker" value="'+date+'" name="transaksi_created">';
	template += '</div>';
	return template;
}

function selectMetodePembayaran(selectValue,value = ''){

	var template = '';
	var label    = 'KREDIT';
	template += '<div class="form-group">';
	template += '<label>Debit/Kredit</label>';
	template += '<select class="form-control" name="jenis_transaksi">';
	$.each(selectValue,function(i,v){
		/*console.log(v);
		console.log(value);*/
		var selected = '';
		if(v == value){
			selected = 'selected';
		}
		if(v == 'deposit'){
			label    = 'DEBIT';
		} else {
			label    = 'KREDIT';
		}
		template += '<option value="'+v+'" '+selected+'>'+label+'</option>';
	});
	template += '</select>';
	template += '</div>';
	return template;
}

function morebukukastransaction(filter,data){
	
	var template = '';
	var nominal  = 0;
	var jenis_transaksi 	= ['cash','debit','transfer'];
	var metodePembayaran 	= ['deposit','sewa'];

	if(data['kredit'] != 0){
		nominal = data['kredit'];
	}

	if(data['debit'] != 0){
		nominal = data['debit'];
	}

	// if(filter == 'add'){
	// 	template = 
	// 	selectJenisTransaksi(jenis_transaksi)
	// 	+ '<div class="form-group">'
	// 	+ '<label>Debit/Kredit</label>'
	// 	+ '<select class="form-control" name="transaksi_flag">'
	// 	+ '<option value="debit">Debit</option>'
	// 	+ '<option value="kredit">Kredit</option>'
	// 	+ '</select>'
	// 	+ '</div>'
	// 	+ '<div class="form-group">'
	// 	+ '<label>Note</label>'
	// 	+ '<textarea wrap="hard" style="height: 200px;" name="transaksi_note" class="form-control"></textarea>'
	// 	+ '</div>'
	// 	+ '<div class="form-group">'
	// 	+ '<label>Nominal</label>'
	// 	+ '<input type="text" name="transaksi" class="pricemask pricepayment form-control" value="0">'
	// 	+ '</div>';
		//+ selectDateAdjustment();
	// }

	if(filter == 'add'){
		template = 
		selectJenisTransaksi(jenis_transaksi)
		+ '<div class="form-group">'
		+ '<label>Debit/Kredit</label>'
		+ '<select class="form-control" name="jenis_transaksi">'
		+ '<option value="deposit">Debit</option>'
		+ '<option value="sewa">Kredit</option>'
		+ '</select>'
		+ '</div>'
		+ '<div class="form-group">'
		+ '<label>Note</label>'
		+ '<textarea wrap="hard" style="height: 200px;" name="jenis_transaksi_note" class="form-control"></textarea>'
		+ '</div>'
		+ '<div class="form-group">'
		+ '<label>Nominal</label>'
		+ '<input type="text" name="jenis_transaksi_nominal" class="pricemask pricepayment form-control" value="0">'
		+ '</div>';
		//+ selectDateAdjustment();
	}

	// if(filter == 'edit'){
	// 	template = 
	// 	selectJenisTransaksi(jenis_transaksi,data['jenis'])
	// 	+ selectMetodePembayaran(metodePembayaran,data['flag'])
	// 	+ '<div class="form-group">'
	// 	+ '<label>Note</label>'
	// 	+ '<textarea wrap="hard" style="height: 200px;" name="transaksi_note" class="form-control">' + data['note'] + '</textarea>'
	// 	+ '</div>'
	// 	+ '<div class="form-group">'
	// 	+ '<label>Nominal</label>'
	// 	+ '<input type="hidden" name="transaksi_id" value="'+ data['id'] +'">'
	// 	+ '<input type="text" name="transaksi" class="pricemask pricepayment form-control" value="'+ nominal +'">'
	// 	+ '</div>';
	// 	//+ selectDateAdjustment(data['tanggal']);
	// }

	if(filter == 'edit'){
		/*console.log(data);*/
		template = 
		selectJenisTransaksi(jenis_transaksi,data['jenis'])
		+ selectMetodePembayaran(metodePembayaran,data['flag'])
		+ '<div class="form-group">'
		+ '<label>Note</label>'
		+ '<textarea wrap="hard" style="height: 200px;" name="jenis_transaksi_note" class="form-control">' + data['note'] + '</textarea>'
		+ '</div>'
		+ '<div class="form-group">'
		+ '<label>Nominal</label>'
		+ '<input type="hidden" name="jenis_transaksi_id" value="'+ data['id'] +'">'
		+ '<input type="text" name="jenis_transaksi_nominal" class="pricemask pricepayment form-control" value="'+ nominal +'">'
		+ '</div>';
		//+ selectDateAdjustment(data['tanggal']);
	}

	return template;

}

function morecorrection(id){

	var template = '<div class="list-correction col-lg-12 col-md-12 col-sm-12 col-xs-12 item-more-'+id+'" style="padding-left: 0; padding-right: 0;">'
	+ '<input type="hidden" name="id[]">'
	+ '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
	+ '<label>Right</label>'
	+ '<input type="text" name="right[]" class="form-control">'
	+ '</div>'
	+ '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
	+ '<label>Wrong</label>'
	+ '<textarea type="text" name="wrong[]" class="form-control"></textarea>'
	+ '</div>'

	+ '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 5px;">'
	+ '<button class="remove-correction circle-button btn btn-danger btn-flat" type="button" id="item-more-'+id+'"><i class="fa fa-times"></i> Remove </button>'
	+ '</div>'

	+ '</div>'
	return template;

}

function stockmask(){
	var stockmask = $(document).find('.stockmask');
	$(stockmask).inputmask("999",{ showMaskOnFocus: false,showMaskOnHover: false, "placeholder": "" });
}

function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function formatDate(date) {
	var d = new Date(date),
	month = '' + (d.getMonth() + 1),
	day = '' + d.getDate(),
	year = d.getFullYear();

	if (month.length < 2) month = '0' + month;
	if (day.length < 2) day = '0' + day;

	return [year, month, day].join('-');
}

var dateToday 				= new Date();
var returnDateDefault       = new Date(); 
returnDateDefault 			= returnDateDefault.setDate(returnDateDefault.getDate()+3);
returnDateDefault  			= formatDate(returnDateDefault);
returnDateDefault  			= new Date(returnDateDefault); 

function openKCFinder(div) {
	window.KCFinder = {
		callBack: function(url) {

			var hostname    						 = document.location.origin;
			var image 								 = document.getElementById('preview-'+div);
			var flag                 				 = div;
			image.src 								 = document.location.origin+url;
			div 								 	 = document.getElementById(div);
			div.value 								 = document.location.origin+url;
			
			var picture 							 = document.getElementsByClassName('picture');
			var getScale                 			 = document.getElementsByName("product_scale")[0].value;

			if(picture.length > 0){
				var getImage 			 = document.getElementsByClassName('picture')[0].value;
				var scale                = document.getElementById('preview-scale');
				scale.src                = getImage;
				scale.style.transform    = "scale("+getScale+")";
			}

			window.KCFinder = null;
		}
	};
	window.open('/assets/adminsite/bower_components/kcfinder/browse.php?type=images&dir=images/public',
		'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
		'directories=0, resizable=1, scrollbars=0, width=800, height=600'
		);
}

$(function(){

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
	function notification(type,message,position = '', confirm_dialog = '',data_dialog = '') {
		if(position == 'center'){
			position = 'pnotify-center';
		}
		if(confirm_dialog == ''){
			confirm_dialog  = false;
		} else if(confirm_dialog !== true){
			confirm_dialog  = false;
		}

		var opts = {
			title: 	"Notification",
			text: 	"This is empty notification.",
			delay: 	4000,
			addclass: '',
			hide: true,
			confirm: { confirm: confirm_dialog }
		};
		switch (type) {
			case 'error':
			opts.title = "Notification";
			opts.text = message;
			opts.type = "error";
			opts.addclass = position;
			break;
			case 'delete':
			opts.title = "Notification";
			opts.text = "Data has been deleted successfully.";
			opts.type = "success";
			opts.addclass = position;
			break;
			case 'success':
			opts.title = "Notification";
			opts.text = "Data has been saved successfully.";
			opts.type = "success";
			opts.addclass = position;
			break;
			case 'update':
			opts.title = "Notification";
			opts.text = "Data has been updated successfully.";
			opts.type = "success";
			opts.addclass = position;
			break;
			case 'validation':
			opts.title = "Notification";
			opts.text = message;
			opts.type = "notice";
			opts.addclass = position;
			break;
			case 'success_with_message':
			opts.title = "Notification";
			opts.text = message;
			opts.type = "success";
			opts.addclass = position;
			break;
			case 'alert_confirmation':
			opts.title = "Notification";
			opts.text = message;
			opts.type = "notice";
			opts.addclass = position;
			opts.hide     = false;
			opts.confirm  = { confirm : confirm_dialog };
			break;
		}
		var notify = new PNotify(opts);
	}

	function button_submit_disabled(){
		$("button[type=submit]").attr("disabled",true);
	}

	function button_submit_enabled(){
		$("button[type=submit]").attr("disabled",false);
	}

	var base_url 		= $('base').attr('href'),
	loading_main 		= $(document).find('.overlay'),
	form_action 		= $('#form-action'),
	apply_form_action 	= $(form_action).find('button[type=submit]'),
	action_table 		= '#' + $('base').data('table'),
	form_side_right 	= $('#form-side-right-action'),
	thisInputQR 		= $('#search').find('input[type="search"]');

	var adjustment;

	var waitForEl = function(selector, callback) {
		if (jQuery(selector).length) {
			callback();
		} else {
			setTimeout(function() {
				waitForEl(selector, callback);
			}, 100);
		}
	};

	var group = $("ol.serialization").sortable({
		group: 'serialization',
		delay: 500,
		pullPlaceholder: false,
		onDrop: function  ($item, container, _super) {

			var getdata 	= group.sortable("serialize").get();
			var jsonString 	= JSON.stringify(getdata, null, ' ');
			var getpush     = [];
			$.each(getdata,function(i,v){
				$.each(v,function(k,val){
					getpush.push(val);
				});
			});
			
			$.ajax({
				url: base_url + 'adminsite/category_sort/update',
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: { data: getpush },
				beforeSend: function(data){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$(loading_main).hide();
				},
				success: function(result){

					if(result.flag == true){
						notification('update');

						if(action_table !== undefined){
							$(action_table).DataTable().ajax.reload();
						}

					} else {
						notification('error',result.message);
					}

				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});
			_super($item, container);
		}
	});


	tinymce.init({

		selector: '.tinymcefull',

		height: 250,

		menubar: true,

		plugins: [

		"advlist autolink link image lists charmap print preview hr anchor pagebreak",

		"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",

		"table contextmenu directionality emoticons paste textcolor rfm code"

		],

		toolbar: 'image media | rfm | undo redo | bold italic',

		image_advtab: true ,

		extended_valid_elements: 'span',

		external_plugins: { "filemanager" : base_url + "assets/adminsite/bower_components/rfm/tinymce/plugins/responsivefilemanager/plugin.min.js" },

		filemanager_access_key:"gadingkostumdcube2k18" ,

		filemanager_title:"Responsive Filemanager",

		external_filemanager_path: base_url + "assets/adminsite/bower_components/rfm/filemanager/",

		file_picker_types: 'file image media',

		file_picker_callback: function(cb, value, meta) {

			var width = window.innerWidth-30;

			var height = window.innerHeight-60;

			if(width > 1800) width=1800;

			if(height > 1200) height=1200;

			if(width>600){

				var width_reduce = (width - 20) % 138;

				width = width - width_reduce + 10;

			}

			var urltype=2;

			if (meta.filetype=='image') { urltype=1; }

			if (meta.filetype=='media') { urltype=3; }

			var title="RESPONSIVE FileManager";

			if (typeof this.settings.filemanager_title !== "undefined" && this.settings.filemanager_title) {

				title=this.settings.filemanager_title;

			}

			var akey="key";

			if (typeof this.settings.filemanager_access_key !== "undefined" && this.settings.filemanager_access_key) {

				akey=this.settings.filemanager_access_key;

			}

			var sort_by="";

			if (typeof this.settings.filemanager_sort_by !== "undefined" && this.settings.filemanager_sort_by) {

				sort_by="&sort_by="+this.settings.filemanager_sort_by;

			}

			var descending="false";

			if (typeof this.settings.filemanager_descending !== "undefined" && this.settings.filemanager_descending) {

				descending=this.settings.filemanager_descending;

			}

			var fldr="";

			if (typeof this.settings.filemanager_subfolder !== "undefined" && this.settings.filemanager_subfolder) {

				fldr="&fldr="+this.settings.filemanager_subfolder;

			}

			var crossdomain="";

			if (typeof this.settings.filemanager_crossdomain !== "undefined" && this.settings.filemanager_crossdomain) {

				crossdomain="&crossdomain=1";

				if(window.addEventListener){

					window.addEventListener('message', filemanager_onMessage, false);

				} else {

					window.attachEvent('onmessage', filemanager_onMessage);

				}

			}

			tinymce.activeEditor.windowManager.open({

				title: title,

				file: this.settings.external_filemanager_path+'dialog.php?type='+urltype+'&descending='+descending+sort_by+fldr+crossdomain+'&lang='+this.settings.language+'&akey='+akey,

				width: width,

				height: height,

				resizable: true,

				maximizable: true,

				inline: 1

			}, {

				setUrl: function (url) {

					cb(url);

				}

			});

		},
	});

	tinymce.init({

		selector: '.tinymcebasic',

		height: 250,

		menubar: true,

		plugins: [

		"paste code"

		],

		toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',

		image_advtab: true,

		extended_valid_elements: 'span',

	});

	$(action_table).on( 'page.dt', function () {
		setTimeout(function(){
			$(document).find(".btn-print-action").printPage();
		});
	});

	$(document).ajaxComplete(function() {
		$("input[name='status']").iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue',
		});

		$(".radio-custom").iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue',
		});

		$("input[type='checkbox']").iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue',
		});

		$('#check_action').on('ifChecked',function(evt) {
			var selected = $('input[name="check_action"]');
			$(selected).iCheck('check');
		});

		$('#check_action').on('ifUnchecked',function(evt) {
			var selected = $('input[name="check_action"]');
			$(selected).iCheck('uncheck');
		});

		//$(".btn-print-action").printPage();
	});

	$('.main-product-rental-wrapper').matchHeight();

	$("input[name='status']").iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
	});

	$("input[type='checkbox']").iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
	});

	$(".radio-custom").iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
	});

	if(action_table !== undefined){
		$(action_table).on('draw.dt', function () {
			$("input[type='checkbox']").iCheck({
				checkboxClass: 'icheckbox_minimal-blue',
				radioClass: 'iradio_minimal-blue',
			});
		});
	}
	//COLOR PICKER
	var colorpicker = $(".colorpicker");
	$(colorpicker).spectrum({
		preferredFormat: "hex3",
		showPaletteOnly: true,
		togglePaletteOnly: true,
		togglePaletteMoreText: 'more',
		togglePaletteLessText: 'less',
		color: '#000000',
		showInput: true,
		palette: [
		["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
		["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
		["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
		["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
		["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
		["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
		["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
		["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
		]
	});

	//INPUT MASK
	var inputmask 	= $('.input-number'),
	pricemask 		= $('.pricemask'),
	inputmask_init 	= $(inputmask).inputmask("9999",{ nullable: false, oncleared: function (e) { $(e.target).val(0);}, showMaskOnFocus: false,showMaskOnHover: false, "placeholder": "" });

	function pricemask_init(selector){
		$(selector).inputmask("numeric", {
			radixPoint: "",
			groupSeparator: ",",
			digits: 2,
			autoGroup: true,
			rightAlign: false,
			allowPlus: false,
			allowMinus: false,
			nullable: false,
			oncleared: function (e) { $(e.target).val(0);}
		});
	}

	function showCountExtraPayment(data){
		var this_total_extrapayment 	 = $('.total_extrapayment');
		var this_total_with_extrapayment = $('.total_with_extrapayment');
		var this_value 					 = $('input[name="rental_total_extrapayment"]');

		$(this_value).val(data['subtotal']);
		$(this_total_extrapayment).find('strong').html('Rp. ' + addCommas(data['subtotal']));
		$(this_total_with_extrapayment).find('strong').html('Rp. ' + addCommas(data['total']));
	}

	pricemask_init(inputmask);
	pricemask_init(pricemask);

	// DELETE
	$(action_table).on('click','.btn-ajax-restore-action',function(e){
		e.preventDefault();

		if(!confirm('Really to restore?')){
			return false;
		} else {
			var url 	= $(this).data('url');
			var value	= $(this).data('item');

			$.ajax({

				url: url,
				type: 'POST',
				dataType: 'json',
				data: {'id': value},
				beforeSend: function(){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$(loading_main).hide();
					if(form_side_right.length > 0){
						$(form_side_right)[0].reset();
					}
				},
				success: function(result){

					if(result.flag == true){
						notification('update');

						if(action_table !== undefined){
							$(action_table).DataTable().ajax.reload();
						}

					} else {
						notification('error',result.message);
					}

				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});

		}

	});

	// DELETE
	$(action_table).on('click','.btn-ajax-trash-action',function(e){
		e.preventDefault();

		if(!confirm('Really to move it?')){
			return false;
		} else {
			var url 	= $(this).data('url');
			var value	= $(this).data('item');

			$.ajax({

				url: url,
				type: 'POST',
				dataType: 'json',
				data: {'id': value},
				beforeSend: function(){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					$(loading_main).hide();
					if(form_side_right.length > 0){
						$(form_side_right)[0].reset();
					}
					button_submit_enabled();
				},
				success: function(result){
					button_submit_enabled();

					if(result.flag == true){
						notification('update');

						if(action_table !== undefined){
							$(action_table).DataTable().ajax.reload();
						}

					} else {
						notification('error',result.message);
					}

				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});

		}

	});

	// DELETE
	$(action_table).on('click','.btn-ajax-delete-action',function(e){
		e.preventDefault();

		if(!confirm('Really Delete?')){
			return false;
		} else {
			var url 	= $(this).data('url');
			var value	= $(this).data('item');

			$.ajax({

				url: url,
				type: 'POST',
				dataType: 'json',
				data: {'category_id': value},
				beforeSend: function(){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$(loading_main).hide();
					if(form_side_right.length > 0){
						$(form_side_right)[0].reset();
					}
				},
				success: function(result){

					if(result.flag == true){
						notification('delete');

						if(action_table !== undefined){
							$(action_table).DataTable().ajax.reload();
						}

					} else {
						notification('error',result.message);
					}

				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});

		}

	});

	// EDIT
	$(action_table).on('click','.btn-ajax-edit-action',function(e){

		e.preventDefault();

		var url 	= $(this).data('url');
		var value	= $(this).data('item');

		$.ajax({

			url: url,
			type: 'POST',
			dataType: 'json',
			data: {'category_id': value},
			beforeSend: function(){
				button_submit_disabled();
				$(loading_main).show();
				if($('.select2-init').length > 0){
					$(".select2-init").val(null).trigger("change"); 
				}
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){

				var select_element = $(form_side_right).find('select');
				if(result.data !== undefined){
					$.each(result.data,function(i,v){
						var field 				= $(form_side_right).find("[name='"+i+"']");
						if(!$.isArray(v) && i != 'status'){
							//console.log('a');
							$(field).val(v);
						}else if(!$.isArray(v) && i == 'status'){
							//console.log('b');
							var status = $(form_side_right).find("[name='"+i+"']");
							$.each(status,function(k,val){
								$(val).prop('checked',false).iCheck('update');
								var status_value = $(val).val();
								if(status_value == v){
									$(val).prop('checked',true).iCheck('update');	
								}
							});
						}else if($.isArray(v)){
							var this_element = $(form_side_right).find("[name='"+i+"'] > option");
							if(this_element.length > 0){
								$.each(this_element,function(k,val){
									var value = $(val).val();
									if(jQuery.inArray(value, v) != -1) {
										$(this).prop("selected", true);
										$(".select2-init").select2({
											tags: true
										});
									}
								});
							}
						}
					});
				}

				if(result.select !== undefined){
					var obj = result.select;
					$.each(obj,function(i,v){
						var field 				= $(form_side_right).find("[name='"+obj.name+"']");
						var value 				= $(field).val();
						if(value == obj.value){
							$(field).children('option:selected').val();
						}
					});
				}

				if(result.colorpicker !== undefined){
					var obj = result.colorpicker;
					$.each(obj,function(i,v){
						var field 	= $(form_side_right).find("[name='"+obj.name+"']");
						$(field).spectrum("set", obj.value);
					});
				}

				if(result.checkbox !== undefined){
					var obj = result.checkbox;
					$.each(obj,function(i,v){
						var field 	= $(form_side_right).find("[name='"+obj[i].name+"']");
						var value 	= $(field).val();
						$(field).prop('checked',false).iCheck('update');
						if(value == obj[i].value){
							$(field).prop('checked',true).iCheck('update');	
						}
					});
				}

				if(result.image !== undefined){
					var obj = result.image;
					$.each(obj,function(i,v){
						var field 		= $(form_side_right).find("[name='"+obj[i].name+"']");
						var	field_id 	= $(field).attr('id');
						if(obj[i].value == ''){
							obj[i].value = 'assets/images/no-image.png';
						}
						$(field).val(obj[i].value);
						$('.' + field_id).attr('src',obj[i].value);
					});
				}

			},
			fail: function(){
				button_submit_enabled();
				notification('error','Something is wrong please try again later.');
				$(loading_main).hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});

	});

	$(apply_form_action).on('click',function(e){

		e.preventDefault();

		var url = $('#action').val();

		var checked = $(document).find('input[name="check_action"]:checked');

		var selected = [];

		$(checked).each(function() {

			selected.push(this.value); 

		});

		if(url == 'undefined' || url == ''){

			return false;

		} else {

			if(confirm('Are you sure?')){

				$.ajax({

					url: url,
					type: 'POST',
					dataType: 'json',
					data: { id: selected },
					beforeSend: function(){
						button_submit_disabled();
						$(loading_main).show();
					},
					complete: function(){
						button_submit_enabled();
						$(loading_main).hide();
					},
					success: function(result){

						$('#check_action').prop('checked',false).iCheck('update');

						if(result == true){
							notification('update');
						}

						if(action_table !== undefined){
							$(action_table).DataTable().ajax.reload();
						}

					},
					fail: function(){
						button_submit_enabled();
						notification('error','Something is wrong please try again later.');
						$(loading_main).hide();

					},
					error: function(xhr, ajaxOptions, thrownError) {
						button_submit_enabled();
						notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						$(loading_main).hide();
					}

				})

			} else {

				return false;

			}

		}

	});

	// AJAX FORM SIDE RIGHT
	$(form_side_right).submit(function(e){

		e.preventDefault();

		var url 	= $(this).attr('action');
		var data 	= $(this).serializeArray(); 

		$.ajax({	

			url: url,
			type: 'POST',
			dataType: 'json',
			data: $(this).serializeArray(),
			beforeSend: function(){
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){
				if(result.flag == true && result.process == 'insert'){
					notification('success');

					if($('.select2-init').length > 0){
						$('.select2-init').val(null).trigger('change');
					}

					$(form_side_right).clear();

					$(form_side_right)[0].reset();
					setTimeout(function() {
						$("input[name='status']").iCheck('update');
						$("input[type='checkbox']").iCheck('update');
					});

				} else if(result.flag == true && result.process == 'update') {
					notification('update');

					if($('.select2-init').length > 0){
						$('.select2-init').val(null).trigger('change');
					}

					$(form_side_right).clear();

					$(form_side_right)[0].reset();
					setTimeout(function() {
						$("input[name='status']").iCheck('update');
						$("input[type='checkbox']").iCheck('update');
					});

				} else if(result.flag == false && result.process == 'validation'){
					notification('validation',result.message);
				} else {
					notification('error','Something Wrong Please Try Again Later');
				}

				if(action_table !== undefined){
					$(action_table).DataTable().ajax.reload();
				}

				//NEW DATA IN FORM
				if(result.upd_form !== undefined && $.isArray(result.upd_form) == true){
					$.each(result.upd_form,function(i,v){
						$(v.element).empty();
						setTimeout(function() {
							if(!$.trim($(v.element).html()).length) {
								$(v.element).append(v.template);
							};
						});
					});
				}

			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});


	});
	// AJAX RENTAL ORDER
	var save_order 		  	= $(document).find('.save-order');
	var save_return_order 	= $(document).find('.save-return-order');
	var print_invpinjam   	= $('#print-invpinjam');
	var print_invkembali   	= $('#print-invkembali');

	function ajax_save_order(flag_print,element){
		$(element).submit(function(ev){

			ev.preventDefault();

			var url 	= $(this).attr('action');
			var data 	= $(this).serializeArray(); 

			$.ajax({	

				url: url,
				type: 'POST',
				dataType: 'json',
				data: $(this).serializeArray(),
				beforeSend: function(obj){
					//console.log(obj);
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
				},
				success: function(result){
					//console.log(result);
					if(result.flag == true && flag_print == 2){
						$(print_invpinjam).printPage({ url: base_url + "adminsite/rental_order/" + result.ctr + result.printid, afterCallback:function(){$(loading_main).hide();window.location.replace(base_url + 'adminsite/rental_order');}});
						$(print_invpinjam).trigger( "click" );
					} else if(result.flag == true && flag_print == 1) {
						$(loading_main).hide();
						window.location.replace(base_url + 'adminsite/rental_order');
					} else {
						$(loading_main).hide();
						notification('error','Something Wrong Please Try Again Later');
					}
				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}

			});


		});
	}
	$(save_order).on('click',function(e){
		e.preventDefault();
		var flag_print = $(this).attr('value');
		ajax_save_order(flag_print,'.form-2');
		$('.form-2').trigger('submit');
	});

	$(save_return_order).on('click',function(e){
		e.preventDefault();
		var flag_print = $(this).attr('value');
		ajax_save_order(flag_print,'.form-3');
		$('.form-3').trigger('submit');
	});

	$('.iframe-btn').fancybox({	'width'	: 300, 'type': 'iframe','height' : 300 });

	$('.iframe-btn').on('click',function(){
		$(window).on('message', OnMessage);
	});

	stockmask();

	if($.trim($('#table-size-stock').html()).length){
		$('#table-size-stock').find('tbody')
		.accordion({
			header: "> tr > td > i"
		})
		.sortable({
			axis: "y",
			handle: "td",
			update: function (event, ui) {

			}
		});
	}

	$(document).on('click','.btn-add-more',function(e){
		e.preventDefault();
		var id = $(this).attr("id");
		var newid = parseInt(id) + 1;
		var flag = $(this).data('more');
		$(this).attr("id", ""+newid+"");
		$(loading_main).show();
		button_submit_disabled();
		setTimeout(function(){
			button_submit_enabled();
			$(loading_main).hide();
			if(flag == 'image'){
				$('.more').append(moreupload(base_url,newid)).hide().fadeIn(500);
				function responsive_filemanager_callback(field_id){ 
					var id = $('#' + field_id);
					var url = $('#' + field_id).val();
					$('.' + field_id).attr('src',url);
					$.fancybox.close();
				}
				$('.iframe-btn').fancybox({	'width'	: 300, 'type': 'iframe','height' : 300 });
			}

			if(flag == 'table-size-stock'){
				var table = $('#table-size-stock').find('tbody');
				var table_tr = $(table).find('tr');
				$(table).append(moresizestock()).hide().fadeIn(500);
				setTimeout(function(){
					if($.trim($('#table-size-stock').html()).length){
						$('#table-size-stock').find('tbody')
						.sortable({
							axis: "y",
							update: function (event, ui) {

							}
						});
					}
					stockmask();
				});
			}

			if(flag == 'table-payment'){
				var table 			= $('#rentalextrapaymentparent').find('table');
				var table_tbody 	= $(table).find('tbody');
				var table_tfoot 	= $(table).find('tfoot');
				//$(table).append(moreextrapayment()).hide().fadeIn(500);
				//if($('#table-order').find('#extra-payment')).length){

				//}
				$('#table-order').find('#extra-payment').append(moreextrapayment()).hide().fadeIn(500);
				// if(table_tfoot.length == 0){
				// 	$(table).append(totalmoreextrapayment()).hide().fadeIn(500);
				// }
				var inputmask 	= $('.pricemask');
				pricemask_init(inputmask);
			}

			if(flag == 'table-bukukas'){
				var table 		= $('#table-bukukas').find('tbody');
				var table_tr 	= $(table).find('tr');

				$(table).append(morebukukastransaction()).hide().fadeIn(500);
				var inputmask 	= $('.pricemask');
				pricemask_init(inputmask);
				//$(table).find('.adjustment-datepicker').datepicker({format: 'dd/mm/yyyy',autoclose: true});
			}
			
			if(flag == 'table-terima-uang'){
				var table 			 = $('#table-terima-uang').find('tbody');
				var table_tr 		 = $(table).find('tr');
				var check_table 	 = $(table).find('.empty-table');
				var selectTerimaUang = $('#pilih-terima-uang').val();
				var itemsTerima  	 = $(document).find('.item-terima-uang');
				var valueTerima 	 = 0;
				if(check_table.length > 0){
					$(check_table).remove();
				} else if(itemsTerima.length == 0 && check_table.length == 0) {
					$(table).empty();
					$(table).append(emptyTerimaJenisTransaksi()).hide().fadeIn(500);
				}

				if(selectTerimaUang == 'deposit'){
					valueTerima = countHargaDeposit('#table-order');
				} else{
					valueTerima = countHargaSewa('#table-order');
				}

				$(table).append(terimaJenisTransaksi(selectTerimaUang,valueTerima)).hide().fadeIn(500);
				var inputmask 	= $(table).find('.pricemask');
				pricemask_init(inputmask);
			}

			if(flag == 'table-kembali-uang'){
				var table 			 = $('#table-kembali-uang').find('tbody');
				var table_tr 		 = $(table).find('tr');
				var check_table 	 = $(table).find('.empty-table');
				var itemsTerima  	 = $(document).find('.item-kembali-uang');
				var tableReturn      = $('#table-order-return');
				if(check_table.length > 0){
					$(check_table).remove();
				} else if(itemsTerima.length == 0 && check_table.length == 0) {
					$(table).empty();
					$(table).append(emptyTerimaJenisTransaksi()).hide().fadeIn(500);
				}
				var returnlatecharges 	= $(tableReturn).find("input[name='return_late_charges']").val();
				var returndamagefine 	= $(tableReturn).find("input[name='return_damage_fine']").val();
				var getmoredenda 	    = countExtraDenda($(tableReturn).find(".item-biaya").find("input[name='rental_dendapayment[]']"));
				var defaultNominal 		= countHargaDeposit('#table-order-return') - parseFloat(returnlatecharges) - parseFloat(returndamagefine) - parseFloat(getmoredenda);
				$(table).append(kembaliJenisTransaksi(defaultNominal)).hide().fadeIn(500);
				var inputmask 	= $(table).find('.pricemask');
				pricemask_init(inputmask);
			}

			if(flag == 'table-biaya'){
				var thisBiayaContainer = $('#divider-biaya');
				$(thisBiayaContainer).before(moredenda).hide().fadeIn(500);
				var inputmask 	= $(document).find('.item-biaya').find('.pricemask');
				pricemask_init(inputmask);
			}
		},100);
});

$('#table-order').on('change',"select[name='rental_extrapayment_flag[]']",function(){
	var this_row 	  = $(this).closest('tr');
	var this_value    = $(this).val();
	var this_getrow   = $(this_row).find('.extrapayment-row');
	var this_sewa     = $(this_row).find('.extrapayment-sewa');
	var this_deposit  = $(this_row).find('.extrapayment-deposit');
	var inputmask 	  = $('.pricemask');
	if(this_value == 'deposit'){
		swap(this_sewa,this_deposit);
		$(this_getrow).toggleClass("extrapayment-sewa extrapayment-deposit");
	} else {
		swap(this_deposit,this_sewa);
		$(this_getrow).toggleClass("extrapayment-deposit extrapayment-sewa");
	}
	pricemask_init(inputmask);
	countSwapExtraPayment('#table-order');
});

$('#table-terima-uang').on('click','.remove-terima-uang',function(e){
	e.preventDefault();
	var element    = $(this).closest('tbody');
	var this_table = $(this).closest('table');
	var this_tr    = $(this).closest('tr');
	$(element).find(this_tr).fadeOut(500, function(){
		$(this_tr).remove();
		setTimeout(function(){
			var check_table= $(document).find(element).find('.item-terima-uang');
			if(check_table.length == 0){
				$(element).append(emptyTerimaJenisTransaksi()).hide().fadeIn(500);
			}
		});
	});
});

$('#table-kembali-uang').on('click','.remove-kembali-uang',function(e){
	e.preventDefault();
	var element    = $(this).closest('tbody');
	var this_table = $(this).closest('table');
	var this_tr    = $(this).closest('tr');
	$(element).find(this_tr).fadeOut(500, function(){
		$(this_tr).remove();
		setTimeout(function(){
			var check_table= $(document).find(element).find('.item-kembali-uang');
			if(check_table.length == 0){
				$(element).append(emptyTerimaJenisTransaksi()).hide().fadeIn(500);
			}
		});
	});
});

function countExtraDenda(payment){
	var subtotal = 0;
	var total 	 = 0;
	$.each(payment,function(i,v){
		var value = $(this).val().replace(/,/g, '');
		if(value == ''){
			value = 0;
		}
		if (!isNaN(value)){ subtotal+=parseFloat(value); };
	});

	if(payment.length > 0){
		total = parseFloat(subtotal);
	}

	return total;
}
function countExtraPayment(payment,totalorder){

	var subtotal = 0;
	var total 	 = 0;
	var result   = {};
	$.each(payment,function(i,v){
		var value = $(this).val().replace(/,/g, '');
		if(value == ''){
			value = 0;
		}
		if (!isNaN(value)){ subtotal+=parseFloat(value); };
	});

	if(payment.length > 0){
		total = parseFloat(totalorder) + parseFloat(subtotal);
	} else {
		total = parseFloat(totalorder);
	}

	result['subtotal'] 	= subtotal;
	result['total'] 	= total;

	return result;
}

function countSwapExtraPayment(table){
	var countTotalExtraPayment       = 0;
	var countSubTotalExtraPayment    = 0;
	var payment_sewa 				 = $(document).find(table).find('.extrapayment-sewa').find('input[name="rental_extrapayment[]"]');
	var payment_deposit 			 = $(document).find(table).find('.extrapayment-deposit').find('input[name="rental_extrapayment[]"]');
	var _rental_total_hargasewa 	 = $(document).find(table).find('input[name="rental_total_hargasewa"]').val();
	var _rental_total_deposit 		 = $(document).find(table).find('input[name="rental_total_deposit"]').val();
	var countPaymentSewa 			 = countExtraPayment(payment_sewa,_rental_total_hargasewa);
	var countPaymentDeposit 		 = countExtraPayment(payment_deposit,_rental_total_deposit);
	countTotalExtraPayment       = parseFloat(countPaymentSewa['total']) + parseFloat(countPaymentDeposit['total']);
	countSubTotalExtraPayment    = parseFloat(countPaymentSewa['subtotal']) + parseFloat(countPaymentDeposit['subtotal']);
	var totalExtraPayment 			 = $('input[name="rental_total_extrapayment"]');
	$(totalExtraPayment).val(countSubTotalExtraPayment);
	$(rental_total_hargasewa).find('strong').html('Rp. ' + addCommas(countPaymentSewa['total']));
	$(rental_total_deposit).find('strong').html('Rp. ' + addCommas(countPaymentDeposit['total']));
	$(rental_total).find('strong').html('Rp. ' + addCommas(countTotalExtraPayment));
}

function countHargaSewa(table){
		// dengan harga extra cost
		var _rental_total_hargasewa = $(document).find('#table-order').find('input[name="rental_total_hargasewa"]').val();
		var total 					= parseFloat(_rental_total_hargasewa);
		var payment_sewa    		= $(document).find(table).find('.extrapayment-sewa').find('input[name="rental_extrapayment[]"]');
		$.each(payment_sewa,function(i,v){
			var value = $(this).val().replace(/,/g, '');
			if(value == ''){
				value = 0;
			}
			if (!isNaN(value)){ total+=parseFloat(value); };
		});
		return total;
	}
	
	function countHargaDeposit(table){
		// dengan harga extra cost
		var _rental_total_deposit = $(document).find(table).find('input[name="rental_total_deposit"]').val();
		var total 				  = parseFloat(_rental_total_deposit);
		var payment_deposit 	  = $(document).find(table).find('.extrapayment-deposit').find('input[name="rental_extrapayment[]"]');
		$.each(payment_deposit,function(i,v){
			var value = $(this).val().replace(/,/g, '');
			if(value == ''){
				value = 0;
			}
			if (!isNaN(value)){ total+=parseFloat(value); };
		});
		return total;
	}

	$('#table-order').on('input','.pricepayment',function(){
		countSwapExtraPayment('#table-order');
	});

	$('#table-order').on('click','.remove-extrapayment',function(e){
		e.preventDefault();
		var _this      = $(this);
		var element    = $(this).closest('tbody');
		var this_table = $(this).closest('table');
		var this_tr    = $(this).closest('tr');
		$(this_tr).fadeOut('fast', function(){
			$(this_tr).remove();
			countSwapExtraPayment('#table-order');
		});
	});

	$('#table-order-return').on('click','.remove-denda',function(e){
		e.preventDefault();
		var _this      = $(this);
		var this_tr    = $(this).closest('tr');
		$(this_tr).fadeOut('fast', function(){
			$(this_tr).remove();
			countSwapExtraPaymentReturn('#table-order-return');
		});
	});

	$('#table-order-return').on('input','.pricepayment',function(){
		//console.log('ab');
		countSwapExtraPaymentReturn('#table-order-return');
	});

	$('#table-order-return').on('click','.remove-extrapayment',function(e){
		e.preventDefault();
		var _this      = $(this);
		var element    = $(this).closest('tbody');
		var this_table = $(this).closest('table');
		var this_tr    = $(this).closest('tr');
		$(this_tr).fadeOut('fast', function(){
			$(this_tr).remove();
			countSwapExtraPayment('#table-order-return');
		});
	});
	
	$(document).on('click','.btn-add-more-correction',function(e){
		e.preventDefault();
		var id = $(this).attr("id");
		var newid = parseInt(id) + 1;
		$(this).attr("id", ""+newid+"");
		$(loading_main).show();
		button_submit_disabled();
		setTimeout(function(){
			button_submit_enabled();
			$(loading_main).hide();
			var container = $('#correction');
			$(container).append(morecorrection(id)).hide().fadeIn(500);
		},100);
	});

	$(document).on('click','.remove-correction',function(e){
		e.preventDefault();
		var id 		= $(this).attr("id");
		var item 	= $(document).find('.' + id);
		$(item).fadeOut(500, function(){
			$(this).remove();
		});
	});
	
	var this_accordion_sortable = $(".accordion");

	if(this_accordion_sortable.length > 0){
		$.each(this_accordion_sortable,function(i,k){

			var this_element = $(k);
			var this_element_no_data = $(this_element).find('.no-data-group');
			if(this_element_no_data.length == 0){
				$(k)
				.accordion({
					header: "> div > div.content"
				})
				.sortable({
					axis: "y",
					handle: "div.content",
					update: function (event, ui) {

					}
				});
			}
		});
	}

	function this_accordion_template(name,id,text){
		var template = '<div class="group">'
		+ '<input type="hidden" name="'+name+'" value="'+id+'">'
		+ '<div class="content">'+text+'<i class="fa fa-times remove-list" aria-hidden="true" style="float: right; margin-top: 3px;"></i></div>'
		+ '</div>';
		return template;
	}

	$(document).find(this_accordion_sortable).on('click','.remove-list',function(e){
		e.preventDefault();
		var element = $(this).closest('.group');
		var this_accordion = $(this).closest('.accordion');

		$(element).fadeOut(500, function(){
			$(element).remove();
			setTimeout(function(){
				if(!$.trim($(this_accordion).html()).length) {
					$(this_accordion).append('<div class="group no-data-group"><div class="content">No data available</div></div>');
				};
			});
		});
	});

	$('.add-category-list').on('click',function(e){
		e.preventDefault();

		var this_data_list 		 = $(this).data('list');
		var this_accordion  	 = $(this_accordion_sortable);
		var this_id 		     = 0;
		var this_text 			 = '';
		var this_name 			 = '';
		var this_select2 		 = '';

		var this_validation      = false;
		var this_message 		 = 'This is message';

		$.each(this_accordion,function(i,k){
			var this_data_accordion = $(k).data('list');
			if(this_data_list == this_data_accordion){
				var this_element_no_data = $(k).find('.no-data-group');
				switch(this_data_list) {
					case 'product':
					this_id 	= $('#product-category').select2('data')[0].id;
					this_text 	= $('#product-category').select2('data')[0].text;
					this_name   = 'product_category_id[]';
					break;
					case 'gender':
					this_id 	= $('#gender-category').select2('data')[0].id;
					this_text 	= $('#gender-category').select2('data')[0].text;
					this_name   = 'gender_category_id[]';
					break;
					case 'size':
					this_id 	= $('#size-category').select2('data')[0].id;
					this_text 	= $('#size-category').select2('data')[0].text;
					this_name   = 'size_category_id[]';
					break;
					case 'store':
					this_id 	= $('#store-category').select2('data')[0].id;
					this_text 	= $('#store-category').select2('data')[0].text;
					this_name   = 'store_category_id[]';
					break;
				}

				var this_check_list = $(document).find('input[name="'+this_name+'"]');
				//this_validation = false;

				$.each(this_check_list,function(key,value){
					var this_hidden = $(value).val();

					if(this_hidden == this_id){
						this_validation = true;
						this_message 	= 'Already exist data in list';
					}

				});

				if(this_validation == false){
					if(this_element_no_data.length > 0){
						$(k).empty();
					}
					setTimeout(function(){
						$(k).append(this_accordion_template(this_name,this_id,this_text)).hide().fadeIn(500);
						$(k).accordion({ header: "> div > div.content"}).sortable({axis: "y",handle: "div.content"});
					});
					this_message = false;
				}
			}
		});

		if(this_validation){
			alert(this_message);
		}
	});

	$(document).on('click','.btn-cancel-more',function(e){
		e.preventDefault();
		var id 		= $(this).attr("id");
		var flag 	= $(this).data('more');
		var item 	= $(document).find('.' + id);
		$(item).fadeOut(500, function(){
			$(this).remove();

			setTimeout(function(){
				var picture 							 = document.getElementsByClassName('picture');
				var getScale                 			 = document.getElementsByName("product_scale")[0].value;

				if(picture.length > 0){
					var getImage 			 = document.getElementsByClassName('picture')[0].value;
					var scale                = document.getElementById('preview-scale');
					scale.src                = getImage;
					scale.style.transform    = "scale("+getScale+")";
				}
			},100);

		});
	});

	$(document).on('click','.remove-item-table',function(e){
		e.preventDefault();
		var element = $(this).closest('tr');
		$(element).fadeOut(500, function(){
			$(element).remove();
		});
	});

	$('.select2-init').select2({placeholder: 'Select'});
	$('.select2-init').on('select2:select', function (evt) { //disabled auto sorting
		var element = evt.params.data.element;
		var $element = $(element);

		$element.detach();
		$(this).append($element);
		$(this).trigger("change");
	});

	//RENTAL
	$('.select2-rental-init').select2({placeholder: 'Search Customer'});

	function datepicker_rental(element,opt){
		var opt = {};
		$(element).datepicker(opt);
	}

	function optDateReturn(current){
		var opt = {};
		opt 	= {
			format: 'd MM yyyy',
			startDate: current,
			autoclose: true,
		};
		return opt;
	}

	var datepicker_return 	= $('.datepicker-return').datepicker(optDateReturn(returnDateDefault)).datepicker("setDate", returnDateDefault).datepicker('setStartDate', dateToday).on('changeDate', function(e) {
		var calendar_init 	= $(document).find('#calendar');
		if(calendar_init.length > 0){
			$(loading_main).show();
			setTimeout(function(){
				$(calendar_init).datepicker('update');
				$(loading_main).hide();
			},100);

			return false;
		}
	});

	var datepicker_current 	= $('.datepicker-current').datepicker({
		format: 'd MM yyyy',
		autoclose: true,
	}).datepicker("setDate", dateToday).on('changeDate', function(e) {
		var date_callback = formatDate(e.format());
		var myDate = new Date(date_callback);
		var currDate = myDate.setDate(myDate.getDate());
		var returnDate = myDate.setDate(myDate.getDate()+3);
		currDate = formatDate(currDate);
		currDate = new Date(currDate);

		returnDate = formatDate(returnDate);
		returnDate = new Date(returnDate);

		var get_datepicker_return_order = $('.datepicker-return').val();
		get_datepicker_return_order = formatDate(get_datepicker_return_order);
		get_datepicker_return_order = new Date(get_datepicker_return_order);

		$('.datepicker-return').datepicker("destroy");
		if(currDate > get_datepicker_return_order){
			$('.datepicker-return').datepicker(optDateReturn(returnDate)).datepicker("setDate", returnDate).datepicker('setStartDate', currDate);
		} else {
			$('.datepicker-return').datepicker(optDateReturn(returnDate)).datepicker('setStartDate', currDate);
		}

		var check_sizestock_available = $(document).find('#available-sizestock');
		if(check_sizestock_available.length > 0){
			$('.select-product-size-rental').trigger("change");
		}

		var calendar_init 	= $(document).find('#calendar');
		if(calendar_init.length > 0){
			$(loading_main).show();
			setTimeout(function(){
				$(calendar_init).datepicker('update');
				$(loading_main).hide();
			},100);

			return false;
		}
	});

	var get_datepicker_view_start_order = $('.datepicker-view-start').val();
	get_datepicker_view_start_order = formatDate(get_datepicker_view_start_order);
	get_datepicker_view_start_order = new Date(get_datepicker_view_start_order);

	var datepicker_view_start_order 	= $('.datepicker-view-start').datepicker({
		format: 'd MM yyyy',
		autoclose: true,
	}).on('changeDate', function(e) {
		var date_callback = formatDate(e.format());
		var myDate = new Date(date_callback);
		var currDate = myDate.setDate(myDate.getDate());
		var returnDate = myDate.setDate(myDate.getDate()+3);
		currDate = formatDate(currDate);
		currDate = new Date(currDate);

		returnDate = formatDate(returnDate);
		returnDate = new Date(returnDate);

		var get_datepicker_view_end_order = $('.datepicker-view-end').val();
		get_datepicker_view_end_order = formatDate(get_datepicker_view_end_order);
		get_datepicker_view_end_order = new Date(get_datepicker_view_end_order);
		$('.datepicker-view-end').datepicker("destroy");
		if(currDate > get_datepicker_view_end_order){
			$('.datepicker-view-end').datepicker(optDateReturn(returnDate)).datepicker("setDate", returnDate).datepicker('setStartDate', currDate);
		} else {
			$('.datepicker-view-end').datepicker(optDateReturn(returnDate)).datepicker('setStartDate', currDate);
		}

		var check_sizestock_available = $(document).find('#available-sizestock');
		if(check_sizestock_available.length > 0){
			$('.select-product-size-rental').trigger("change");
		}

		var calendar_init 	= $(document).find('#calendar');
		if(calendar_init.length > 0){
			$(loading_main).show();
			setTimeout(function(){
				$(calendar_init).datepicker('update');
				$(loading_main).hide();
			},100);

			return false;
		}
	});

	var datepicker_view_end_order = $('.datepicker-view-end').datepicker({
		format: 'd MM yyyy',
		autoclose: true
	}).datepicker('setStartDate', get_datepicker_view_start_order).on('changeDate', function(e) {

		var check_sizestock_available = $(document).find('#available-sizestock');
		if(check_sizestock_available.length > 0){
			$('.select-product-size-rental').trigger("change");
		}

		var calendar_init 	= $(document).find('#calendar');
		if(calendar_init.length > 0){
			$(loading_main).show();
			setTimeout(function(){
				$(calendar_init).datepicker('update');
				$(loading_main).hide();
			},100);

			return false;
		}
	});

	var datepicker_view_end = $('.datepicker-view').datepicker({
		format: 'd MM yyyy',
		startDate: dateToday,
		autoclose: true
	});

	var datepicker_view = $('.datepicker-view').datepicker({
		format: 'd MM yyyy',
		startDate: dateToday,
		autoclose: true
	});

	var datepicker_ = $('.datepicker').datepicker({
		format: 'd MM yyyy',
		autoclose: true
	});

	if($('.datepicker-month').length > 0){
		$('.datepicker').datepicker('destroy');
		var datepicker_month = $('.datepicker-month').datepicker({
			format: "MM yyyy",
			startView: "months", 
			minViewMode: "months",
			autoclose: true,
			clearBtn: true
		});
	}

	var select_product_size_rental 	= $('.select-product-size-rental');
	var select_product_rental 		= $('.select-product-rental');
	var select_store_rental 		= $('.select-store-rental');
	var product_rental_detail 		= $('.product-rental-detail');

	$(select_store_rental).on('change', function(e) {
		var id = $(this).select2('data')[0].id;
		$.ajax({
			url: base_url + 'adminsite/rental_order/get_product',
			type: 'POST',
			dataType: 'json',
			data: {'id': id},
			beforeSend: function(){
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){

				if(result.flag == true){
					$('.popover').remove();
					$('#calendar').remove();
					if(result.template !== undefined){
						$(select_product_rental).empty();
						$(select_product_size_rental).empty();
						$(product_rental_detail).empty();
						setTimeout(function() {
							if(!$.trim($(select_product_rental).html()).length) {
								$(select_product_rental).append(result.template);
								$(select_product_rental).trigger('change'); 
							};
						});
					}

				} else {
					$(select_product_rental).empty();
					$(select_product_size_rental).empty();
					$(product_rental_detail).empty();
				}
				setTimeout(function(){
					$('.main-product-rental-wrapper').matchHeight();
				});
			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});

	});	

	var wrapper_select_product  	= $('.wrapper-select-product');
	var thumbnail_select_product    = $('.thumbnail-select-product');
	var getProductSize 				= false;

	$('a[href="#search"]').on('click', function(event) {
		event.preventDefault();
		$('.scan-qr').val('');
		$('#search').addClass('open');
		$(thisInputQR).focus();
		//$(document).scannerDetection('gadingkostum.com/tag/iaa0022-1233');
	});

	$('#search, #search button.close').on('click keyup', function(event) {
		if (event.target.className == 'close' || event.keyCode == 27) {
			$(this).removeClass('open');
		}
	});

	$(document).scannerDetection({
		timeBeforeScanTest: 200,
		avgTimeByChar: 100,
		onComplete: function(barcode, qty){
			//console.log(barcode);
			$.ajax({
				url: base_url + 'adminsite/rental_order/getProductByQR',
				type: 'POST',
				dataType: 'json',
				data: { kode: barcode },
				beforeSend: function(data){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$('#search, #search button.close').trigger('click');
					$('#foo').val("");
				},
				success: function(result){
					getProductSize = result.product_sizestock_id || false;
					$('html, body').animate({
						scrollTop: $("#form-1").find('.main-product-rental-wrapper').offset().top
					},'fast');
					$('.select-product-rental').val(result.product_id).trigger("change");
					setTimeout(function() {
						//console.log(result.product_id);
						//$(select_product_rental).select2().val(result.product_id).trigger("change");
						//$('.select-product-rental').val(result.product_id).trigger("change");
						//$(document).find('.select-product-rental option[value='+result.product_id+']').attr('selected','selected');
						//setTimeout(function() {
							//$(document).find('.select-product-rental').trigger('change');
							$(loading_main).hide();
						//},1000);
					});
				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});
		}
	});

			$('#some-test-element').click(function() {
     $('div#focus-here').focus();
});

	$(document).bind('scannerDetectionComplete',function(e,data){
   	console.log('complete');
   })
   .bind('scannerDetectionError',function(e,data){
   	console.log('detection error '+data.string);
   })
   .bind('scannerDetectionReceive',function(e,data){
   	console.log('Recieve');
   });

	$('.select-product-rental').on('change', function(e) {
		e.preventDefault();
		//var id 						= $(this).select2('data')[0].id;
		var id 						= $(this).val();
		//console.log(id);
		$.ajax({
			url: base_url + 'adminsite/rental_order/get_sizestock',
			type: 'POST',
			dataType: 'json',
			data: {'id': id},
			beforeSend: function(){
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){

				if(result.flag == true){
					$('#calendar').remove();
					$('.popover').remove();
					$('.popover-content').remove();
					if(result.template !== undefined){
						$(select_product_size_rental).empty();
						$(product_rental_detail).empty();
						$(thumbnail_select_product).attr('href',result.thumbnail);
						$(thumbnail_select_product).find('img').attr('src',result.thumbnail);

						if(!$.trim($(select_product_size_rental).html()).length) {
							$(select_product_size_rental).append(result.template);
							setTimeout(function() {
								if(getProductSize != false){
									//console.log(getProductSize);
									$(select_product_size_rental).val(getProductSize).trigger("change");
								}
								getProductSize = false;
							},1000);
						};
						setTimeout(function() {
						}, 1000);
					}

				} else {
					$(select_product_size_rental).empty();
					$(product_rental_detail).empty();
				}
				setTimeout(function(){
					$('.main-product-rental-wrapper').matchHeight();
				});
			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});

	});	

	function str_pad(n) {
		return String(n).slice(-2);
	}

	// function calendar_product(options){

	// 	$('.popover-content').remove();
	// 	$('.popover').remove();

	// 	var day = options.data;

	// 	var calendar_init 	= $(document).find('#main-calendar');
	// 	if(calendar_init.length > 0){
	// 		var glob_date 		= new Date();
	// 		var today 			= new Date(glob_date.getFullYear(), glob_date.getMonth(), glob_date.getDate());
	// 		var tooltip_event 	= [];
	// 		var dates 	  		= [];
	// 		var push_stock     	= [];
	// 		var push_rental     = [];
	// 		var _data_stock     = [];
	// 		var _data_rental 	= [];
	// 		var class_day 		= $(calendar_init).data('class');
	// 		var datetime  		= $(calendar_init).data('date');
	// 		var cal_stock 		= $(calendar_init).data('stock');
	// 		var cal_rental		= $(calendar_init).data('rental');

	// 		class_day 	  = class_day.split(",");
	// 		datetime 	  = datetime.split(",");
	// 		cal_stock 	  = cal_stock.split(",");
	// 		cal_rental 	  = cal_rental.split(",");

	// 		if(datetime.length > 0){
	// 			for(var index=0; index<datetime.length; index++){
	// 				dates[datetime[index]] 		 = class_day[index];
	// 				push_stock[datetime[index]]  = cal_stock[index];
	// 				push_rental[datetime[index]] = cal_rental[index];
	// 			}
	// 		}

	// 		$(calendar_init).datepicker({	
	// 			defaultViewDate: 'month',
	// 			stepMonths: 0,
	// 			changeMonth: false,
	// 			changeYear: false,
	// 			startDate: '-0d',
	// 			autoclose: true,
	// 			beforeShowDay: function(date) {
	// 				var calendar_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+('0'+date.getDate()).slice(-2);

	// 				if(datepicker_current.length > 0 && datepicker_return.length > 0){
	// 					var datepicker_view_start 	= $('.datepicker-current').val();
	// 					datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

	// 					var tes_start 				= new Date(Date.parse(datepicker_view_start));
	// 					datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

	// 					var datepicker_view_end 	= $('.datepicker-return').val();
	// 					datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

	// 					var tes_end 				= new Date(Date.parse(datepicker_view_end));
	// 					tes_end						= tes_end.setDate(tes_end.getDate() + 1);

	// 					datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

	// 					var datepicker_view_end 	= $('.datepicker-return').val();

	// 					var dateArray 		= getDatesArr(tes_start,tes_end);

	// 					var dateFormatArray = [];
	// 					if(dateArray.length > 0){
	// 						$.each(dateArray,function(i,v){
	// 							var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
	// 							dateFormatArray[date_change_format] = 'highlight-day';
	// 						});
	// 					}
	// 				}

	// 				if(datepicker_view_start_order.length > 0 && datepicker_view_end_order.length > 0){
	// 					var datepicker_view_start 	= $('.datepicker-view-start').val();
	// 					datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

	// 					var tes_start 				= new Date(Date.parse(datepicker_view_start));
	// 					datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

	// 					var datepicker_view_end 	= $('.datepicker-view-end').val();
	// 					datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

	// 					var tes_end 				= new Date(Date.parse(datepicker_view_end));
	// 					tes_end						= tes_end.setDate(tes_end.getDate() + 1);
	// 					datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

	// 					var datepicker_view_end 	= $('.datepicker-view-end').val();

	// 					var dateArray 		= getDatesArr(tes_start,tes_end);

	// 					var dateFormatArray = [];
	// 					if(dateArray.length > 0){
	// 						$.each(dateArray,function(i,v){
	// 							var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
	// 							dateFormatArray[date_change_format] = 'highlight-day';
	// 						});
	// 					}
	// 				}

	// 				var search = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

	// 				var date_range_start = $.inArray(datepicker_view_start,search);

	// 				if (search in push_stock) {
	// 					var _this_data = push_stock[search].split(',');
	// 					_data_stock.push(_this_data);
	// 				}

	// 				if (search in push_rental) {
	// 					var _this_data = push_rental[search].split(',');
	// 					_data_rental.push(_this_data);
	// 				}

	// 				if (search in dates) {

	// 					var split_date = search.split('/');
	// 					var split_data = dates[search].split(','); //class

	// 					tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

	// 					if(search in dateFormatArray){
	// 						return {
	// 							classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					} else {
	// 						return {
	// 							classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					}
						
	// 				} else if(search in dateFormatArray){

	// 					return {
	// 						classes: dateFormatArray[search] 
	// 					}

	// 				}

	// 				if(datepicker_current.length > 0){
	// 					$(datepicker_current).off('change').on('change', function(edc) {
	// 						edc.preventDefault();
	// 						var datepicker_view_start 	= $('.datepicker-current').val();
	// 						datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

	// 						var tes_start 				= new Date(Date.parse(datepicker_view_start));
	// 						datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

	// 						var datepicker_view_end 	= $('.datepicker-return').val();
	// 						datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

	// 						var tes_end 				= new Date(Date.parse(datepicker_view_end));
	// 						datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

	// 						var dateArray 		= getDatesArr(tes_start,tes_end);

	// 						var dateFormatArray = [];
	// 						if(dateArray.length > 0){
	// 							$.each(dateArray,function(i,v){
	// 								var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
	// 								dateFormatArray[date_change_format] = 'highlight-day';
	// 							});
	// 						}

	// 						if (search in dates) {

	// 							var split_date = search.split('/');
	// 					var split_data = dates[search].split(','); //class

	// 					tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

	// 					if(search in dateFormatArray){
	// 						return {
	// 							classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					} else {
	// 						return {
	// 							classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					}
						
	// 				} else if(search in dateFormatArray){

	// 					return {
	// 						classes: dateFormatArray[search] 
	// 					}

	// 				}
					
	// 			});
	// 				}

	// 				if(datepicker_return.length > 0){
	// 					$(datepicker_return).off('change').on('change', function(edr) {
	// 						edr.preventDefault();
	// 						var datepicker_view_start 	= $('.datepicker-current').val();
	// 						datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

	// 						var tes_start 				= new Date(Date.parse(datepicker_view_start));
	// 						datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

	// 						var datepicker_view_end 	= $('.datepicker-return').val();
	// 						datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

	// 						var tes_end 				= new Date(Date.parse(datepicker_view_end));
	// 						datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

	// 						var dateArray 		= getDatesArr(tes_start,tes_end);

	// 						var dateFormatArray = [];
	// 						if(dateArray.length > 0){
	// 							$.each(dateArray,function(i,v){
	// 								var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
	// 								dateFormatArray[date_change_format] = 'highlight-day';
	// 							});
	// 						}

	// 						if (search in dates) {

	// 							var split_date = search.split('/');
	// 					var split_data = dates[search].split(','); //class

	// 					tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

	// 					if(search in dateFormatArray){
	// 						return {
	// 							classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					} else {
	// 						return {
	// 							classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					}
						
	// 				} else if(search in dateFormatArray){

	// 					return {
	// 						classes: dateFormatArray[search] 
	// 					}

	// 				}
					
	// 			});
	// 				}

	// 				if(datepicker_view_start_order.length > 0){
	// 					$(datepicker_view_start_order).off('change').on('change', function(edr) {

	// 						edr.preventDefault();

	// 						var datepicker_view_start 	= $('.datepicker-view-start').val();

	// 						datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

	// 						var tes_start 				= new Date(Date.parse(datepicker_view_start));
	// 						datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

	// 						var datepicker_view_end 	= $('.datepicker-view-end').val();
	// 						datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

	// 						var tes_end 				= new Date(Date.parse(datepicker_view_end));
	// 						datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

	// 						var dateArray 		= getDatesArr(tes_start,tes_end);

	// 						var dateFormatArray = [];
	// 						if(dateArray.length > 0){
	// 							$.each(dateArray,function(i,v){
	// 								var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
	// 								dateFormatArray[date_change_format] = 'highlight-day';
	// 							});
	// 						}

	// 						if (search in dates) {

	// 							var split_date = search.split('/');
	// 					var split_data = dates[search].split(','); //class

	// 					tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

	// 					if(search in dateFormatArray){
	// 						return {
	// 							classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					} else {
	// 						return {
	// 							classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					}
						
	// 				} else if(search in dateFormatArray){
	// 					return {
	// 						classes: dateFormatArray[search] 
	// 					}

	// 				}
					
	// 			});
	// 				}

	// 				if(datepicker_view_end_order.length > 0){
	// 					$(datepicker_view_end_order).on('change', function(edr) {
	// 						edr.preventDefault();
	// 						var datepicker_view_start 	= $('.datepicker-view-start').val();
	// 						datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

	// 						var tes_start 				= new Date(Date.parse(datepicker_view_start));
	// 						datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

	// 						var datepicker_view_end 	= $('.datepicker-view-end').val();
	// 						datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

	// 						var tes_end 				= new Date(Date.parse(datepicker_view_end));
	// 						datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

	// 						var dateArray 		= getDatesArr(tes_start,tes_end);

	// 						var dateFormatArray = [];
	// 						if(dateArray.length > 0){
	// 							$.each(dateArray,function(i,v){
	// 								var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
	// 								dateFormatArray[date_change_format] = 'highlight-day';
	// 							});
	// 						}

	// 						if (search in dates) {

	// 							var split_date = search.split('/');
	// 					var split_data = dates[search].split(','); //class

	// 					tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

	// 					if(search in dateFormatArray){
	// 						return {
	// 							classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					} else {
	// 						return {
	// 							classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
	// 						}
	// 					}
						
	// 				} else if(search in dateFormatArray){

	// 					return {
	// 						classes: dateFormatArray[search] 
	// 					}

	// 				}
					
	// 			});
	// 				}
	// 			},
	// 		}).on('changeDate', function (ev) {
	// 			$('.popover-content').remove();
	// 			$('.popover').remove();

	// 			var dayLive = ev.format();
	// 			var content = '0/0';
	// 			if(day === undefined){
	// 				$('.popover-content').remove();
	// 				$('.popover').remove();
	// 			} else {
	// 				content = day['default'].available+'/'+day['default'].stock;
	// 				$.each(day,function(i,v){
	// 					if(dayLive == i && i !== 'default'){
	// 						content = v.available+'/'+v.stock;
	// 					}
	// 				});
	// 				$('.popover-content').remove();
	// 				$('.popover').remove();
	// 			}
	// 			var target = $(ev.target);
	// 			var _this_element = $(this).find('.datepicker-days').find('.day');

	// 			$(_this_element).each(function(i,v){

	// 				if($(v).hasClass('active')){

	// 					$(v).popover({
	// 						container: 'body',
	// 						trigger: 'click',
	// 						title: 'Availability',
	// 						content: content,
	// 						html: true,
	// 						placement: 'top',
	// 					});

	// 					$(v).popover('show');
	// 				} else {
	// 					$(v).popover('hide');
	// 				}

	// 			});

	// 		}).on('changeMonth',function(ev){
	// 			$('.popover-content').remove();
	// 			$('.popover').remove();
	// 		});

	// 		$(calendar_init).find('.datepicker-switch').click(function(event) {
	// 			event.preventDefault();
	// 			event.stopPropagation();
	// 		});
	// 	}
	// }

	function calendar_product_new(options){

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
				defaultViewDate: 'month',
				stepMonths: 0,
				changeMonth: false,
				changeYear: false,
				startDate: '-0d',
				autoclose: true,
				beforeShowDay: function(date) {
					var calendar_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+('0'+date.getDate()).slice(-2);

					if(datepicker_current.length > 0 && datepicker_return.length > 0){
						var datepicker_view_start 	= $('.datepicker-current').val();
						datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

						var tes_start 				= new Date(Date.parse(datepicker_view_start));
						datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

						var datepicker_view_end 	= $('.datepicker-return').val();
						datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

						var tes_end 				= new Date(Date.parse(datepicker_view_end));
						tes_end						= tes_end.setDate(tes_end.getDate() + 1);

						datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

						var datepicker_view_end 	= $('.datepicker-return').val();

						var dateArray 		= getDatesArr(tes_start,tes_end);

						var dateFormatArray = [];
						if(dateArray.length > 0){
							$.each(dateArray,function(i,v){
								var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
								dateFormatArray[date_change_format] = 'highlight-day';
							});
						}
					}

					if(datepicker_view_start_order.length > 0 && datepicker_view_end_order.length > 0){
						var datepicker_view_start 	= $('.datepicker-view-start').val();
						datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

						var tes_start 				= new Date(Date.parse(datepicker_view_start));
						datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

						var datepicker_view_end 	= $('.datepicker-view-end').val();
						datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

						var tes_end 				= new Date(Date.parse(datepicker_view_end));
						tes_end						= tes_end.setDate(tes_end.getDate() + 1);
						datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

						var datepicker_view_end 	= $('.datepicker-view-end').val();

						var dateArray 		= getDatesArr(tes_start,tes_end);

						var dateFormatArray = [];
						if(dateArray.length > 0){
							$.each(dateArray,function(i,v){
								var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
								dateFormatArray[date_change_format] = 'highlight-day';
							});
						}
					}

					var search = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

					var date_range_start = $.inArray(datepicker_view_start,search);

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

						if(search in dateFormatArray){
							return {
								classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						} else {
							return {
								classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						}
						
					} else if(search in dateFormatArray){

						return {
							classes: dateFormatArray[search] 
						}

					}

					if(datepicker_current.length > 0){
						$(datepicker_current).off('change').on('change', function(edc) {
							edc.preventDefault();
							var datepicker_view_start 	= $('.datepicker-current').val();
							datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

							var tes_start 				= new Date(Date.parse(datepicker_view_start));
							datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

							var datepicker_view_end 	= $('.datepicker-return').val();
							datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

							var tes_end 				= new Date(Date.parse(datepicker_view_end));
							datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

							var dateArray 		= getDatesArr(tes_start,tes_end);

							var dateFormatArray = [];
							if(dateArray.length > 0){
								$.each(dateArray,function(i,v){
									var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
									dateFormatArray[date_change_format] = 'highlight-day';
								});
							}

							if (search in dates) {

								var split_date = search.split('/');
						var split_data = dates[search].split(','); //class

						tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

						if(search in dateFormatArray){
							return {
								classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						} else {
							return {
								classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						}
						
					} else if(search in dateFormatArray){

						return {
							classes: dateFormatArray[search] 
						}

					}
					
				});
					}

					if(datepicker_return.length > 0){
						$(datepicker_return).off('change').on('change', function(edr) {
							edr.preventDefault();
							var datepicker_view_start 	= $('.datepicker-current').val();
							datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

							var tes_start 				= new Date(Date.parse(datepicker_view_start));
							datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

							var datepicker_view_end 	= $('.datepicker-return').val();
							datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

							var tes_end 				= new Date(Date.parse(datepicker_view_end));
							datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

							var dateArray 		= getDatesArr(tes_start,tes_end);

							var dateFormatArray = [];
							if(dateArray.length > 0){
								$.each(dateArray,function(i,v){
									var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
									dateFormatArray[date_change_format] = 'highlight-day';
								});
							}

							if (search in dates) {

								var split_date = search.split('/');
						var split_data = dates[search].split(','); //class

						tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

						if(search in dateFormatArray){
							return {
								classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						} else {
							return {
								classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						}
						
					} else if(search in dateFormatArray){

						return {
							classes: dateFormatArray[search] 
						}

					}
					
				});
					}

					if(datepicker_view_start_order.length > 0){
						$(datepicker_view_start_order).off('change').on('change', function(edr) {

							edr.preventDefault();

							var datepicker_view_start 	= $('.datepicker-view-start').val();

							datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

							var tes_start 				= new Date(Date.parse(datepicker_view_start));
							datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

							var datepicker_view_end 	= $('.datepicker-view-end').val();
							datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

							var tes_end 				= new Date(Date.parse(datepicker_view_end));
							datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

							var dateArray 		= getDatesArr(tes_start,tes_end);

							var dateFormatArray = [];
							if(dateArray.length > 0){
								$.each(dateArray,function(i,v){
									var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
									dateFormatArray[date_change_format] = 'highlight-day';
								});
							}

							if (search in dates) {

								var split_date = search.split('/');
						var split_data = dates[search].split(','); //class

						tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

						if(search in dateFormatArray){
							return {
								classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						} else {
							return {
								classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						}
						
					} else if(search in dateFormatArray){
						return {
							classes: dateFormatArray[search] 
						}

					}
					
				});
					}

					if(datepicker_view_end_order.length > 0){
						$(datepicker_view_end_order).on('change', function(edr) {
							edr.preventDefault();
							var datepicker_view_start 	= $('.datepicker-view-start').val();
							datepicker_view_start 		= new Date(Date.parse(datepicker_view_start));

							var tes_start 				= new Date(Date.parse(datepicker_view_start));
							datepicker_view_start 		= str_pad((datepicker_view_start.getMonth()+1))+'/'+str_pad(datepicker_view_start.getDate())+'/'+datepicker_view_start.getFullYear();

							var datepicker_view_end 	= $('.datepicker-view-end').val();
							datepicker_view_end 		= new Date(Date.parse(datepicker_view_end));

							var tes_end 				= new Date(Date.parse(datepicker_view_end));
							datepicker_view_end 		= str_pad((datepicker_view_end.getMonth()+1))+'/'+str_pad(datepicker_view_end.getDate())+'/'+datepicker_view_end.getFullYear();

							var dateArray 		= getDatesArr(tes_start,tes_end);

							var dateFormatArray = [];
							if(dateArray.length > 0){
								$.each(dateArray,function(i,v){
									var date_change_format = str_pad((v.getMonth()+1))+'/'+str_pad(v.getDate())+'/'+v.getFullYear();
									dateFormatArray[date_change_format] = 'highlight-day';
								});
							}

							if (search in dates) {

								var split_date = search.split('/');
						var split_data = dates[search].split(','); //class

						tooltip_event.push('toggle-'+split_date[0]+split_date[1]+split_date[2]);

						if(search in dateFormatArray){
							return {
								classes: dateFormatArray[search] + ' ' + split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						} else {
							return {
								classes: split_data[0] + ' ' + 'toggle-'+split_date[0]+split_date[1]+split_date[2]
							}
						}
						
					} else if(search in dateFormatArray){

						return {
							classes: dateFormatArray[search] 
						}

					}
					
				});
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

			}).on('changeMonth',function(ev){
				$('.popover-content').remove();
				$('.popover').remove();
			});

			$(calendar_init).find('.datepicker-switch').click(function(event) {
				event.preventDefault();
				event.stopPropagation();
			});
		}
	}

	$(document).on('change','.select-product-size-rental', function(e) {

		var product_id 	= $(".select-product-rental").select2('data')[0].id;
		var id 			= $(this).val();
		var start 		= $('input[name="start_date"]').val();
		var end 		= $('input[name="end_date"]').val();

		$.ajax({
			url: base_url + 'adminsite/rental_order/get_sizestock_detail',
			type: 'POST',
			dataType: 'json',
			data: {'id': id, 'product_id': product_id,'start':start,'end':end},
			beforeSend: function(obj){
				/*console.log(obj);*/
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){
				/*console.log(result);*/
				if(result.flag == true){

					if(result.template !== undefined){
						$(product_rental_detail).empty();
						setTimeout(function() {
							if(!$.trim($(product_rental_detail).html()).length) {
								$(product_rental_detail).append(result.template);
								setTimeout(function() {
									stockmask();
									$('.info-item-wrapper').matchHeight();
									$('.info-item-wrapper').find('.info-title').matchHeight();
									$('.info-item-wrapper').find('.info-data').matchHeight();
									$('.main-product-rental-wrapper').matchHeight();
								});
							};
						});
					}

				} else {
					$(product_rental_detail).empty();
					notification('error',result.message);
				}

			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});

		// $.ajax({
		// 	url: base_url + 'adminsite/rental_order/calendarproduct',
		// 	// url: base_url + 'getcalendar',
		// 	data: {'id': id, 'product_id': product_id},
		// 	type: 'POST',
		// 	dataType: 'json',
		// 	beforeSend: function(data){
		// 		/*console.log(data);*/
		// 		button_submit_disabled();
		// 		$(loading_main).show();
		// 	},
		// 	complete: function(){
		// 		button_submit_enabled();
		// 		$(loading_main).hide();
		// 	},
		// 	success: function(result){
		// 		/*console.log(result);*/
		// 		if(result.flag == true){

		// 			var _this_main_calendar = $('.main-calendar');
		// 			$(_this_main_calendar).empty();
		// 			var options = result;
		// 			setTimeout(function() {
		// 				if(!$.trim($(_this_main_calendar).html()).length) {
		// 					$(_this_main_calendar).append(result.template);

		// 					setTimeout(function(){
		// 						calendar_product(options);

		// 						$('.main-product-rental-wrapper').matchHeight();
		// 					});

		// 				} 
		// 			});
		// 		}
		// 	},
		// 	fail: function(){
		// 		button_submit_enabled();
		// 		notification('error','Connection Error or something is wrong please try again later.');
		// 		$(loading_main).hide();
		// 	},
		// 	error: function(xhr, ajaxOptions, thrownError) {
		// 		button_submit_enabled();
		// 		notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		// 		$(loading_main).hide();
		// 	}
		// });
		$.ajax({
			// url: base_url + 'adminsite/rental_order/calendarproductnew',
			url: base_url + 'getcalendar',
			data: {'product_sizestock_id': id, 'product_id': product_id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(data){
				/*console.log(data);*/
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){
				/*console.log(result);*/
				if(result.flag == true){

					var _this_main_calendar = $('.calendar');
					$(_this_main_calendar).empty();
					var options = result;
					setTimeout(function() {
						if(!$.trim($(_this_main_calendar).html()).length) {
							$(_this_main_calendar).append(result.template);

							setTimeout(function(){
								calendar_product_new(options);

								$('.main-product-rental-wrapper').matchHeight();
							});

						} 
					});
				}
			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}
		});

	});

	//ADD ITEM
	var select_customer = $('.select2-customer');

	$('.select2-customer').on('change',function(e) {

		var customer_id = $('.select2-customer').select2('data')[0].id;

		if(customer_id != 0 || customer_id != ''){
			$.ajax({
				url: base_url + 'adminsite/rental_order/get_customer',
				type: 'POST',
				dataType: 'json',
				data: {'customer_id': customer_id },
				beforeSend: function(){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$(loading_main).hide();
				},
				success: function(result){
					if(result.flag == true){
						$('input[name="customer_name"]').val(result.data.customer_name);
						$('input[name="customer_phone"]').val(result.data.customer_phone);
						$('textarea[name="customer_address"]').val(result.data.customer_address);
					}
				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});
		}
		
	});

	//ADD ITEM
	var add_item_button 		= $('.add-item-rental');
	var table_list_item 		= $('.table-list-item');
	var table_list_item_tbody 	= $(table_list_item).find('tbody');

	$(add_item_button).on('click',function(e) {
		e.preventDefault();

		var check_stock_available 		= $('#available-sizestock');
		var product_id 			 		= $(".select-product-rental").select2('data')[0].id;
		var product_sizestock_id 		= $(select_product_size_rental).val();
		var qty 				 		= $('.qty_rental').val();
		var search_product_sizestock_id = $(document).find(table_list_item_tbody).find('input[name="rental_product_sizestock_id[]"]');
		var search_product_id 			= $(document).find(table_list_item_tbody).find('input[name="product_id[]"]');
		var search_product_size 		= false;
		var search_product 				= false;

		$.each(search_product_sizestock_id,function(i,v){
			var search_value = $(this).val();
			if(product_sizestock_id == search_value){
				search_product_size = true;
			}
		});
		$.each(search_product_sizestock_id,function(i,v){
			var search_value = $(this).val();
			if(search_product_id == search_value){
				search_product = true;
			}
		});

		function _this_ajax_data(){
			$.ajax({
				url: base_url + 'adminsite/rental_order/add_item_product',
				type: 'POST',
				dataType: 'json',
				data: {'product_id': product_id, 'product_sizestock_id': product_sizestock_id, 'qty':qty},
				beforeSend: function(){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$(loading_main).hide();
				},
				success: function(result){
					var count_hargasewa 		= 0;
					var count_deposit 			= 0;
					var count_total 			= 0;
					var subtotal  				= $('.subtotal');
					var total 					= $('.total');

					if(result.flag == true){

						$('.popover').remove();
						$('.popover-content').remove();

						if(result.template !== undefined){
							$(table_list_item_tbody).append(result.template);
							$(".select-product-rental").val(0).trigger("change");
							$(product_rental_detail).empty();
							$(document).find('.select-product-size-rental').val(0);
							setTimeout(function(){

								$('#calendar').remove();

								var list_hargasewa 	= $(table_list_item_tbody).find('input[name="rental_product_hargasewa[]"]');
								var list_deposit 	= $(table_list_item_tbody).find('input[name="rental_product_deposit[]"]');

								$.each(list_hargasewa,function(i,v){
									var value = $(v).val();
									if (!isNaN(value)){ count_hargasewa+=parseFloat(value) };
								});
								$.each(list_deposit,function(i,v){
									var value = $(v).val();
									if (!isNaN(value)){ count_deposit+=parseFloat(value) };
								});
								count_total = parseFloat(count_hargasewa) + parseFloat(count_deposit);
								$(subtotal).find('input[name="rental_total_hargasewa"]').val(count_hargasewa);
								$(subtotal).find('input[name="rental_total_deposit"]').val(count_deposit);
								$(total).find('input[name="rental_total"]').val(count_total);

								$(subtotal).find('.pricetotal_hargasewa').html('Rp. ' + addCommas(count_hargasewa));
								$(subtotal).find('.pricetotal_deposit').html('Rp. ' + addCommas(count_deposit));
								$(total).find('.all_price').html('Rp. ' + addCommas(count_total));

								$('.main-product-rental-wrapper').matchHeight();
							});
							notification('success_with_message','Produk telah ditambahkan ke dalam list order','center');
						}

						$(thumbnail_select_product).attr('href','assets/images/no-image.png');
						$(thumbnail_select_product).find('img').attr('src','assets/images/no-image.png');

					} else if(result.flag == false){
						notification('validation',result.message);
					}else {
						notification('error',result.message);
					}

				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});
		}

		if(search_product_size == true && search_product == false){
			notification('validation','Product size already exists in table cost.');
		} else {
			if(product_id == 0 || product_sizestock_id == 0){
				notification('validation','Select size first.');
			} else if(qty == '' || qty == 0) {
				notification('validation','Input Qty first.');
			} else {

				if(check_stock_available.length > 0){
					var value_stock_available = $(check_stock_available).val();
					var count_stock 		  = parseFloat(value_stock_available) - parseFloat(qty); 

					if(value_stock_available <= 0 || count_stock < 0){
						new PNotify({
							title: 'Notification',
							text: 'Stock tidak mencukupi, apakah tetap ingin melanjutkan order?',
							hide: false,
							confirm: {
								confirm: true,
								buttons: [
								{
									text: 'Ya'
								},
								{
									text: 'Tidak'
								}
								]
							},
							buttons: {
								closer: false,
								sticker: false
							},
							//history: {
								//history: false
							//},
							addclass: 'pnotify-center'
						}).get().on('pnotify.confirm', function() {
							_this_ajax_data();
						});
					} else {
						_this_ajax_data();
					}

				} else {
					notification('validation','Something Wrong, product size not found.');
				}

			}
		}
		$('div#focus-here').focus();
	});

$(document).on('click','.remove-rental-product', function(e) {
	e.preventDefault();
	var element = $(this).closest('tr');
	var count_hargasewa 		= 0;
	var count_deposit 			= 0;
	var count_total 			= 0;
	var subtotal  				= $('.subtotal');
	var total 					= $('.total');
	var total_element 			= $(total).find('input[name="rental_total"]').val();
	var total_hargasewa 		= $(subtotal).find('input[name="rental_total_hargasewa"]').val();
	var total_deposit 			= $(subtotal).find('input[name="rental_total_deposit"]').val();
	var list_hargasewa 			= $(this).data('hargasewa');
	var list_deposit 			= $(this).data('deposit');

	count_hargasewa = parseFloat(total_hargasewa) - parseFloat(list_hargasewa);
	count_deposit 	= parseFloat(total_deposit) - parseFloat(list_deposit);
	count_total  	= parseFloat(count_hargasewa) + parseFloat(count_deposit);

	$(subtotal).find('input[name="rental_total_hargasewa"]').val(count_hargasewa);
	$(subtotal).find('input[name="rental_total_deposit"]').val(count_deposit);
	$(total).find('input[name="rental_total"]').val(count_total);

	$(subtotal).find('.pricetotal_hargasewa').html('Rp. ' + addCommas(count_hargasewa));
	$(subtotal).find('.pricetotal_deposit').html('Rp. ' + addCommas(count_deposit));
	$(total).find('.all_price').html('Rp. ' + addCommas(count_total));

	$(element).fadeOut(500, function(){
		$(element).remove();
		$('.main-product-rental-wrapper').matchHeight();
	});
});

	// Binding next button on first step

	var customer_id 		= $('#customer_id'),
	customer_name 			= $('#customer_name'),
	customer_phone			= $('#customer_phone'),
	customer_address		= $('#customer_address'),
	//customer_email			= $('#customer_email'),
	start_date				= $('#rental_start_date'),
	end_date 				= $('#rental_end_date'),
	invoice 				= $('#rental_invoice'),
	rental_note 			= $('#rental_note'),
	rental_hargasewa 		= $('#rental_hargasewa'),
	rental_deposit 			= $('#rental_deposit'),
	rental_total_hargasewa  = $('#rental_total_hargasewa'),
	rental_total_deposit 	= $('#rental_total_deposit'),
	rental_total 			= $('#rental_total'),
	table_order 			= $('#table-order'),
	table_order_tbody 		= $(table_order).find('#tbody-order'),
	store_address 			= $('.store_address');

	$("#open-form-2").submit(function(e) {
		e.preventDefault();

		var url 	= $(this).attr('action');
		var data 	= $(this).serializeArray(); 
		$.ajax({	
			url: url,
			type: 'POST',
			dataType: 'json',
			data: $(this).serializeArray(),
			beforeSend: function(){
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){

				//console.log(result);
				if(result.flag == true){
					$(store_address).html(result.data_single.store_address);
					$(customer_id).val(result.data_single.customer_id);
					$(customer_name).val(result.data_single.customer_name);
					$(customer_phone).val(result.data_single.customer_phone);
					$(customer_address).val(result.data_single.customer_address);
					$(start_date).val(result.data_single.start_date);
					$(end_date).val(result.data_single.end_date);
					$(invoice).val(result.data_single.rental_invoice);
					$(rental_note).val(result.data_single.rental_note);
					$(rental_total_hargasewa).find('strong').html('Rp. ' + result.data_single.rental_total_hargasewa);
					$(rental_total_deposit).find('strong').html('Rp. ' + result.data_single.rental_total_deposit);
					$(rental_total).find('strong').html('Rp. ' + result.data_single.rental_total);

					if(result.data_product !== undefined){
						$(table_order_tbody).append(result.data_product);
					}

					setTimeout(function(){
						$(".content-wrapper").hide();
						$("#form-2").show();

						var inputmask = $('.pricemask');
						pricemask_init(inputmask);

						var _rental_product_hargasewa 	= $(document).find('#table-order').find('input[name="rental_product_hargasewa[]"]');
						var _rental_product_deposit 	= $(document).find('#table-order').find('input[name="rental_product_deposit[]"]');
						var _rental_total_hargasewa 	= $(document).find('#table-order').find('input[name="rental_total_hargasewa"]');
						var _rental_total_deposit 		= $(document).find('#table-order').find('input[name="rental_total_deposit"]');
						var _rental_total 				= $(document).find('#table-order').find('input[name="rental_total"]');

						var _subtotal_hargasewa = 0;
						var _subtotal_deposit   = 0;
						var _this_rental_total 	= 0;

						$.each(_rental_product_hargasewa,function(i,v){
							var value = $(this).val().replace(/,/g, '');

							if(value == ''){
								value = 0;
							}

							if (!isNaN(value)){ _subtotal_hargasewa+=parseFloat(value) };
						});

						$.each(_rental_product_deposit,function(i,v){
							var value = $(this).val().replace(/,/g, '');
							if(value == ''){
								value = 0;
							}
							if (!isNaN(value)){ _subtotal_deposit+=parseFloat(value) };
						});

						_this_rental_total     	= parseFloat(_subtotal_hargasewa) + parseFloat(_subtotal_deposit);
						//extra payment
						var payment 			= $(document).find('#table-order').find('input[name="rental_extrapayment[]"]');
						var countPayment 		= countExtraPayment(payment,_this_rental_total);

						//console.log(countPayment);
						//showCountExtraPayment(countPayment);
						countSwapExtraPayment('#table-order');

						$(_rental_product_hargasewa).on('input',function(){
							_subtotal_hargasewa = 0;
							$.each(_rental_product_hargasewa,function(i,v){
								var value = $(this).val().replace(/,/g, '');
								if(value == ''){
									value = 0;
								}
								if (!isNaN(value)){ _subtotal_hargasewa+=parseFloat(value); };
							});
							$(_rental_total_hargasewa).val(_subtotal_hargasewa);
							$(rental_total_hargasewa).find('strong').html('Rp. ' + addCommas(_subtotal_hargasewa));
							_this_rental_total = 0;
							_this_rental_total = _subtotal_hargasewa + _subtotal_deposit;
							$(_rental_total).val(_this_rental_total);
							$(rental_total).find('strong').html('Rp. ' + addCommas(_this_rental_total));

							//extra payment
							var payment 		= $(document).find('#table-order').find('input[name="rental_extrapayment[]"]');
							var countPayment 	= countExtraPayment(payment,_this_rental_total);

							countSwapExtraPayment('#table-order');
							//showCountExtraPayment(countPayment);
						});

						$(_rental_product_deposit).on('input',function(){
							_subtotal_deposit   = 0;
							$.each(_rental_product_deposit,function(i,v){
								var value = $(this).val().replace(/,/g, '');
								if(value == ''){
									value = 0;
								}
								if (!isNaN(value)){ _subtotal_deposit+=parseFloat(value); };
							});
							$(_rental_total_deposit).val(_subtotal_deposit);
							$(rental_total_deposit).find('strong').html('Rp. ' + addCommas(_subtotal_deposit));
							_this_rental_total = 0;
							_this_rental_total = _subtotal_hargasewa + _subtotal_deposit;
							$(_rental_total).val(_this_rental_total);
							$(rental_total).find('strong').html('Rp. ' + addCommas(_this_rental_total));

							//extra payment
							var payment 		= $(document).find('#table-order').find('input[name="rental_extrapayment[]"]');
							var countPayment 	= countExtraPayment(payment,_this_rental_total);
							countSwapExtraPayment('#table-order');
							//showCountExtraPayment(countPayment);
						});
					},500);
					
				} else if(result.flag == false && result.process == 'validation'){
					notification('validation',result.message);
				} else {
					notification('error','Something Wrong Please Try Again Later');
				}

			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});

});

var customer_id_return 			= $('#customer_id_return'),
customer_name_return 			= $('#customer_name_return'),
customer_phone_return			= $('#customer_phone_return'),
customer_address_return			= $('#customer_address_return'),
start_date_return				= $('#rental_start_date_return'),
end_date_return 				= $('#rental_end_date_return'),
invoice_return 					= $('#rental_invoice_return'),
rental_hargasewa_return 		= $('#rental_hargasewa_return'),
rental_deposit_return 			= $('#rental_deposit_return'),
rental_total_hargasewa_return  	= $('#rental_total_hargasewa_return'),
rental_total_deposit_return 	= $('#rental_total_deposit_return'),
rental_total_return 			= $('#rental_total_return'),
return_note 					= $('#return_note'),
return_late_charges				= $('#return_late_charges'),
return_damage_fine 				= $('#return_damage_fine'),
return_deposit_ 				= $('#return_deposit_'),

table_order_return 				= $('#table-order-return'),
table_order_tbody_return 		= $(table_order_return).find('#tbody-order');

function countSwapExtraPaymentReturn(table){
	var countTotalExtraPayment       = 0;
	var countSubTotalExtraPayment    = 0;
	var countUangKembali 			 = 0;

	var payment_sewa 				 = $(document).find(table).find('.extrapayment-sewa').find('input[name="rental_extrapayment[]"]');
	var payment_deposit 			 = $(document).find(table).find('.extrapayment-deposit').find('input[name="rental_extrapayment[]"]');
	var payment_denda 			     = $(document).find(table).find('.item-biaya').find('input[name="rental_dendapayment[]"]');
	var _rental_total_hargasewa 	 = $(document).find(table).find('input[name="rental_total_hargasewa"]').val();
	var payment_late_charges         = $(document).find(table).find('input[name="return_late_charges"]').val();

	//console.log(payment_late_charges);
	var payment_return_damage_fine   = $(document).find(table).find('input[name="return_damage_fine"]').val();
	var _rental_total_deposit 		 = $(document).find(table).find('input[name="rental_total_deposit"]').val();
	var countPaymentDenda 			 = countExtraDenda(payment_denda);
	var countPaymentSewa 			 = countExtraPayment(payment_sewa,_rental_total_hargasewa);
	var countPaymentDeposit 		 = countExtraPayment(payment_deposit,_rental_total_deposit);
	var countUangKembali 			 = parseFloat(countPaymentDeposit['total']) - parseFloat(countPaymentDenda) - parseFloat(payment_late_charges) - parseFloat(payment_return_damage_fine);
	countTotalExtraPayment       = parseFloat(countPaymentSewa['total']) + parseFloat(countPaymentDeposit['total']);
	countSubTotalExtraPayment    = parseFloat(countPaymentSewa['subtotal']) + parseFloat(countPaymentDeposit['subtotal']);
	var totalExtraPayment 			 = $('input[name="rental_total_extrapayment"]');

	//console.log(countPaymentDeposit);
	//console.log(countPaymentDenda);

	$(totalExtraPayment).val(countSubTotalExtraPayment);
	$(rental_total_hargasewa_return).html('Rp. ' + addCommas(countPaymentSewa['total']));
	$(rental_total_deposit_return).html('Rp. ' + addCommas(countPaymentDeposit['total']));
	$(rental_total_return).find('strong').html('Rp. ' + addCommas(countTotalExtraPayment));
	$(return_deposit_).find('strong').html('Rp. ' + addCommas(countUangKembali));
}

$("#open-form-3").submit(function(e) {
	e.preventDefault();

	var url 	= $(this).attr('action');
	var data 	= $(this).serializeArray(); 
	
	$.ajax({	
		url: url,
		type: 'POST',
		dataType: 'json',
		data: data,
		beforeSend: function(){
			button_submit_disabled();
			$(loading_main).show();
		},
		complete: function(){
			button_submit_enabled();
			$(loading_main).hide();
		},
		success: function(result){
			button_submit_enabled();
			if(result.flag == true){
				$(store_address).html(result.data_single.store_address);
				$(customer_id_return).val(result.data_single.customer_id);
				$(customer_name_return).val(result.data_single.customer_name);
				$(customer_phone_return).val(result.data_single.customer_phone);
				$(customer_address_return).val(result.data_single.customer_address);
				$(start_date_return).val(result.data_single.start_date);
				$(end_date_return).val(result.data_single.return_date);
				$(invoice_return).val(result.data_single.rental_invoice);
				$(rental_total_hargasewa_return).html('Rp. ' + result.data_single.rental_total_hargasewa);
				$(rental_total_deposit_return).html('Rp. ' + result.data_single.rental_total_deposit);
				$(rental_total_return).find('strong').html('Rp. ' + result.data_single.rental_total);

				$(return_note).html(result.data_single.return_note);
				$(return_late_charges).find('strong').html('- Rp. ' + result.data_single.return_late_charges);
				$(return_damage_fine).find('strong').html('- Rp. ' + result.data_single.return_damage_fine);

				if(result.data_product !== undefined){
					$(table_order_tbody_return).append(result.data_product);
				}
				
				var _rental_total_deposit 	= $(document).find('#table-order-return').find('input[name="rental_total_deposit"]').val();
				var payment_deposit 		= $(document).find('#table-order-return').find('.extrapayment-deposit').find('input[name="rental_extrapayment[]"]');
				var countPaymentDeposit 	= countExtraPayment(payment_deposit,_rental_total_deposit);

				var this_rental_total 	= parseFloat(countPaymentDeposit['total']) - parseFloat(result.data_single.return_late_charges.replace(/,/g, '')) - parseFloat(result.data_single.return_damage_fine.replace(/,/g, ''));
				$(return_deposit_).find('strong').html('Rp. ' + addCommas(this_rental_total));
				// var this_total_extrapayment = $('#form-3').find('#total_extrapayment').val();

				// console.log(this_total_extrapayment);
				
				// if(this_total_extrapayment !== undefined){
				// 	var this_total_kembali = parseFloat(this_rental_total) + parseFloat(this_total_extrapayment);
				// 	$('#count_extrapayment').find('strong').html('Rp. '+addCommas(this_rental_total)+' + '+'Rp. '+addCommas(this_total_extrapayment));
				// 	$('#total_return_extrapayment').find('strong').html('Rp. '+addCommas(this_total_kembali));
				// }
				//var _this_rental_total  = parseFloat(_subtotal_hargasewa) + parseFloat(_subtotal_deposit);
				//var payment 			= $('#open-form-3').find('#rentalextrapaymentparent').find('.rentalextrapayment');
				//var countPayment 		= countExtraPayment(payment,_this_rental_total);
				//showCountExtraPayment(countPayment);

				setTimeout(function(){
					$(".content-wrapper").hide();
					$("#form-3").show();

					var inputmask = $('.pricemask');
					pricemask_init(inputmask);

					var _rental_product_hargasewa 	= $(document).find("#form-3").find('#table-order').find('input[name="rental_product_hargasewa[]"]');
					var _rental_product_deposit 	= $(document).find("#form-3").find('#table-order').find('input[name="rental_product_deposit[]"]');
					var _rental_total_hargasewa 	= $(document).find("#form-3").find('#table-order').find('input[name="rental_total_hargasewa"]');
					var _rental_total_deposit 		= $(document).find("#form-3").find('#table-order').find('input[name="rental_total_deposit"]');
					var _rental_total 				= $(document).find("#form-3").find('#table-order').find('input[name="rental_total"]');

					var _subtotal_hargasewa = 0;
					var _subtotal_deposit   = 0;
					var _this_rental_total 	= 0;

					$.each(_rental_product_hargasewa,function(i,v){
						var value = $(this).val().replace(/,/g, '');

						if(value == ''){
							value = 0;
						}

						if (!isNaN(value)){ _subtotal_hargasewa+=parseFloat(value) };
					});

					$.each(_rental_product_deposit,function(i,v){
						var value = $(this).val().replace(/,/g, '');
						if(value == ''){
							value = 0;
						}
						if (!isNaN(value)){ _subtotal_deposit+=parseFloat(value) };
					});

					_this_rental_total     	= parseFloat(_subtotal_hargasewa) + parseFloat(_subtotal_deposit);
						//extra payment
						var payment 			= $(document).find("#form-3").find('#table-order-return').find('input[name="rental_extrapayment[]"]');
						var countPayment 		= countExtraPayment(payment,_this_rental_total);
						countSwapExtraPaymentReturn('#table-order-return');
						//showCountExtraPayment(countPayment);

						$(_rental_product_hargasewa).on('input',function(){
							_subtotal_hargasewa = 0;
							$.each(_rental_product_hargasewa,function(i,v){
								var value = $(this).val().replace(/,/g, '');
								if(value == ''){
									value = 0;
								}
								if (!isNaN(value)){ _subtotal_hargasewa+=parseFloat(value); };
							});
							$(_rental_total_hargasewa).val(_subtotal_hargasewa);
							$(rental_total_hargasewa).find('strong').html('Rp. ' + addCommas(_subtotal_hargasewa));
							_this_rental_total = 0;
							_this_rental_total = _subtotal_hargasewa + _subtotal_deposit;
							$(_rental_total).val(_this_rental_total);
							$(rental_total).find('strong').html('Rp. ' + addCommas(_this_rental_total));

							//extra payment
							var payment 		= $(document).find("#form-3").find('#table-order-return').find('input[name="rental_extrapayment[]"]');
							var countPayment 	= countExtraPayment(payment,_this_rental_total);
							countSwapExtraPaymentReturn('#table-order-return');
							//showCountExtraPayment(countPayment);
						});

						$(_rental_product_deposit).on('input',function(){
							_subtotal_deposit   = 0;
							$.each(_rental_product_deposit,function(i,v){
								var value = $(this).val().replace(/,/g, '');
								if(value == ''){
									value = 0;
								}
								if (!isNaN(value)){ _subtotal_deposit+=parseFloat(value); };
							});
							$(_rental_total_deposit).val(_subtotal_deposit);
							$(rental_total_deposit).find('strong').html('Rp. ' + addCommas(_subtotal_deposit));
							_this_rental_total = 0;
							_this_rental_total = _subtotal_hargasewa + _subtotal_deposit;
							$(_rental_total).val(_this_rental_total);
							$(rental_total).find('strong').html('Rp. ' + addCommas(_this_rental_total));

							console.log(_this_rental_total);
							//extra payment
							var payment 		= $(document).find("#form-3").find('#table-order-return').find('input[name="rental_extrapayment[]"]');
							var countPayment 	= countExtraPayment(payment,_this_rental_total);
							countSwapExtraPaymentReturn('#table-order-return');
							//showCountExtraPayment(countPayment);
						});
					},500);
				
			} else if(result.flag == false && result.process == 'validation'){
				notification('validation',result.message);
			} else {
				notification('error','Something Wrong Please Try Again Later');
			}

		},
		fail: function(){
			button_submit_enabled();
			notification('error','Connection Error or something is wrong please try again later.');
			$(loading_main).hide();

		},
		error: function(xhr, ajaxOptions, thrownError) {
			button_submit_enabled();
			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			$(loading_main).hide();
		}

	});

});

   // Binding next button on second step
   $(".open-form-1").on('click',function(e) {
   	e.preventDefault();
   	//form 2
   	$(customer_id).val('');
   	$(customer_name).val('');
   	$(customer_phone).val('');
   	$(customer_address).val('');
   	$(start_date).val('');
   	$(end_date).val('');
   	$(invoice).val('');
   	$(table_order_tbody).empty();
   	//form 3
   	$(customer_id_return).val('');
   	$(customer_name_return).val('');
   	$(customer_phone_return).val('');
   	$(customer_address_return).val('');
   	$(start_date_return).val('');
   	$(end_date_return).val('');
   	$(invoice_return).val('');
   	$(table_order_tbody_return).empty();

   	$(".content-wrapper").hide();
   	$("#form-1").show();
   });

   var return_deposit 	= $('#return-deposit'),
   late_charges 		= $('#late-charges'),
   damage_fine 			= $('#damage-fine'),
   count_product 		= $('#count_product'),
   result_late_charges  = 0,
   result_damage_fine   = 0,
   result_deposit_minus = 0,
   result_count_total 	= 0;

   if(return_deposit.length > 0){
   	return_deposit = $(return_deposit).val().replace(/,/g, '');
   }
   if(late_charges.length > 0){
   	result_late_charges = $(late_charges).val().replace(/,/g, '');
   }
   if(damage_fine.length > 0){
   	result_damage_fine = $(damage_fine).val().replace(/,/g, '');
   }
   
   $(late_charges).on('input',function(){
   	value = $(this).val().replace(/,/g, '');

   	if(value == ''){
   		value = 0;
   	}

   	if (!isNaN(value)){
   		result_late_charges 	= parseFloat(value);
   	}

   	result_deposit_minus 	= parseFloat(result_late_charges) + parseFloat(result_damage_fine); 

   	result_count_total 		= parseFloat(return_deposit) - parseFloat(result_deposit_minus);

   	$(document).find('input[name="return_deposit"]').val(result_count_total);
   	$('#return-result-deposit').val(addCommas(result_count_total));
   });

   $(damage_fine).on('input',function(){
   	var value = $(this).val().replace(/,/g, '');

   	if(value == ''){
   		value = 0;
   	}
   	if (!isNaN(value)){
   		result_damage_fine 		= parseFloat(value);
   	}

   	result_deposit_minus 		= parseFloat(result_late_charges) + parseFloat(result_damage_fine); 

   	result_count_total 			= parseFloat(return_deposit) - parseFloat(result_deposit_minus); 
   	$(document).find('input[name="return_deposit"]').val(result_count_total);
   	$('#return-result-deposit').val(addCommas(result_count_total));
   });

   var 	get_datepicker_view_start_order = $('.datepicker-view-start').val();
   get_datepicker_view_start_order = formatDate(get_datepicker_view_start_order);
   get_datepicker_view_start_order = new Date(get_datepicker_view_start_order);

   var datepicker_return_date 	= $('.datepicker-return-date').datepicker({
   	format: 'd MM yyyy',
   	autoclose: true,
   }).datepicker('setStartDate', get_datepicker_view_start_order).on('changeDate', function(e) {
   	var end_date = $('#open-form-3').find('input[name="end_date"]').val();
   	end_date = formatDate(end_date);
   	end_date = new Date(end_date);

   	var date_callback = formatDate(e.format());
   	var returnDate = new Date(date_callback);
   	var oneDay = 24*60*60*1000;
   	var timeleft = 0;

   	timeleft = Math.round(Math.abs((returnDate.getTime() - end_date.getTime()))/(oneDay));

   	var newtimeleft  = Math.round((returnDate.getTime() - end_date.getTime()))/(oneDay);
   	newtimeleft = Math.sign(newtimeleft);

   	var late_charge_value = $('#late_charge_value').val();
   	var returnDateTime = returnDate.getTime();
   	var end_dateTime   = end_date.getTime();

   	if(newtimeleft >= 0 && returnDateTime > end_dateTime){
   		var charges =  parseFloat(late_charge_value) * (parseFloat($(count_product).val()) * parseFloat(timeleft));
   		$(late_charges).val(charges).trigger("input");
   	} else {
   		var charges = 0;
   		$(late_charges).val(charges).trigger("input");
   	}
   });

   var select_status_rental = $('.select-status-rental');

   $(action_table).on('change','.select-status-rental',function(e){
   	e.preventDefault();
   	var value = $(this).val();
   	$.ajax({
   		url: base_url + 'adminsite/rental_order/update_status',
   		type: 'POST',
   		dataType: 'json',
   		data: {'id':value},
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		complete: function(){
   			$(loading_main).hide();
   			button_submit_enabled();
   		},
   		success: function(result){

   			if(result.flag == true){
   				notification('update');

   				if(action_table !== undefined){
   					$(action_table).DataTable().ajax.reload();
   				}

   			} else {
   				notification('error',result.message);
   			}

   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();

   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}

   	});

   });

   var filtering_return_list = $('#filtering-return-list');
   $(filtering_return_list).submit(function(e){
   	e.preventDefault();
   	var url   = $(this).attr('action');
   	var value = $(this).serializeArray();
   	$.ajax({
   		url: url,
   		type: 'POST',
   		dataType: 'json',
   		data: value,
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		complete: function(){
   			$(loading_main).hide();
   			button_submit_enabled();
   		},
   		success: function(result){
   			
   			if(result.flag == false){
   				notification('validation',result.message);
   			} else if(result.flag == true){
   				if(action_table !== undefined){
   					$(action_table).DataTable().clear().draw();
   					$(action_table).DataTable().rows.add(result.data);
   					$(action_table).DataTable().columns.adjust().draw();
   				}
   			} else{
   				notification('error','Connection Error or something is wrong please try again later.');
   			}
   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();
   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}
   	});
   });

   var reset_filtering_return_list = $('.reset-filtering-return-list');
   $(reset_filtering_return_list).on('click',function(e){

   	if(action_table !== undefined){
   		$('input[name="start"]').val('');
   		$('input[name="end"]').val('');
   		$('select[name="filter"]').prop("selectedIndex", 0);
   		$('select[name="store_location"]').prop("selectedIndex", 0);
   		$(action_table).DataTable().ajax.reload();
   	}

   	button_submit_disabled();
   	$(loading_main).show();
   	$.ajax({
   		url: base_url + 'adminsite/return_list/reset_print_report',
   		dataType: 'json',
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		success: function(result){
   			if(result.flag == true){
   				setTimeout(function(){
   					$(loading_main).hide();
   					button_submit_enabled();
   				},2000);
   			}
   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();
   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}
   	});
   });

   var filtering_stock_list = $('#filtering-stock-list');
   $(filtering_stock_list).submit(function(e){
   	e.preventDefault();
   	var url   = $(this).attr('action');
   	var value = $(this).serializeArray();
   	$.ajax({
   		url: url,
   		type: 'POST',
   		dataType: 'json',
   		data: value,
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		complete: function(){
   			$(loading_main).hide();
   			button_submit_enabled();
   		},
   		success: function(result){
   			if(result.flag == false){
   				notification('validation',result.message);
   			} else if(result.flag == true){
   				if(action_table !== undefined){
   					$(action_table).DataTable().clear().draw();
   					$(action_table).DataTable().rows.add(result.data);
   					$(action_table).DataTable().columns.adjust().draw();
   				}
   			} else{
   				notification('error','Connection Error or something is wrong please try again later.');
   			}
   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();
   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}
   	});
   });

   var reset_filtering_stock_list = $('.reset-filtering-stock-list');
   $(reset_filtering_stock_list).on('click',function(e){

   	if(action_table !== undefined){
   		$('select[name="store_location"]').prop("selectedIndex", 0);
   		$(action_table).DataTable().ajax.reload();
   	}

   	button_submit_disabled();
   	$(loading_main).show();
   	$.ajax({
   		url: base_url + 'adminsite/stock_list/reset_print_report',
   		dataType: 'json',
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		success: function(result){
   			if(result.flag == true){
   				setTimeout(function(){
   					$(loading_main).hide();
   					button_submit_enabled();
   				},2000);
   			}
   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();
   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}
   	});
   });

   var filtering_most_rented = $('#filtering-most-rented');
   $(filtering_most_rented).submit(function(e){
   	e.preventDefault();
   	var url   = $(this).attr('action');
   	var value = $(this).serializeArray();
   	$.ajax({
   		url: url,
   		type: 'POST',
   		dataType: 'json',
   		data: value,
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		complete: function(){
   			$(loading_main).hide();
   			button_submit_enabled();
   		},
   		success: function(result){

   			if(result.flag == false){
   				notification('validation',result.message);
   			} else if(result.flag == true){
   				if(action_table !== undefined){
   					$(action_table).DataTable().clear().draw();
   					$(action_table).DataTable().rows.add(result.data);
   					$(action_table).DataTable().columns.adjust().draw();
   				}
   			} else{
   				notification('error','Connection Error or something is wrong please try again later.');
   			}
   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();
   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}
   	});
   });
   
   var reset_filtering_most_rented = $('.reset-filtering-most-rented');
   $(reset_filtering_most_rented).on('click',function(e){

   	if(action_table !== undefined){
   		$(action_table).DataTable().ajax.reload();
   	}

   	button_submit_disabled();
   	$(loading_main).show();
   	$.ajax({
   		url: base_url + 'adminsite/dashboard/reset_print_report',
   		dataType: 'json',
   		beforeSend: function(){
   			button_submit_disabled();
   			$(loading_main).show();
   		},
   		success: function(result){
   			if(result.flag == true){
   				setTimeout(function(){
   					$(loading_main).hide();
   					button_submit_enabled();
   				},2000);
   			}
   		},
   		fail: function(){
   			button_submit_enabled();
   			notification('error','Connection Error or something is wrong please try again later.');
   			$(loading_main).hide();
   		},
   		error: function(xhr, ajaxOptions, thrownError) {
   			button_submit_enabled();
   			notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   			$(loading_main).hide();
   		}
   	});
   });

   var this_select_datepicker = $('#show-datepicker');
   $(this_select_datepicker).on('change',function(e){
   	var this_value = $(this).val();
   	var this_open  = '#'+this_value;   
   	$(".collapse").collapse('hide');
   	if($(this_open).length > 0){
   		setTimeout(function(){
   			$(this_open).collapse('show');
   		},350);
   	}
   });	

   $('#transactionModal').on('shown.bs.modal', function (event) {
   	var filter 	= $(event.relatedTarget).attr('data-action');
   	var table 	= $(this).find('.modal-body');
   	var title   = $(this).find('.modal-title').html('');
   	var data 	= {};

   	if(filter == 'add'){
   		$(title).html('ADD NEW TRANSACTION');
   	}

   	if(filter == 'edit'){
   		$(title).html('EDIT TRANSACTION');
   		data['id'] 		= $(event.relatedTarget).attr('data-id');
   		data['jenis'] 	= $(event.relatedTarget).attr('data-jenis');
   		data['note'] 	= $(event.relatedTarget).attr('data-note');
   		data['debit'] 	= $(event.relatedTarget).attr('data-debit');
   		data['kredit'] 	= $(event.relatedTarget).attr('data-kredit');
   		data['flag']	= $(event.relatedTarget).attr('data-flag');
   		data['tanggal'] = $(event.relatedTarget).attr('data-tanggal');
   	}

   	if(!$.trim($(table).html()).length) {
   		$(table).append(morebukukastransaction(filter,data)).hide().fadeIn(500);
   		var inputmask 	= $(this).find('.pricemask');
   		pricemask_init(inputmask);
   	}
   	//$(table).find('.adjustment-datepicker').datepicker({format: 'dd/mm/yyyy',autoclose: true});
   });

   $('#transactionModal').on('show.bs.modal', function (event) {
   	var table 	= $(this).find('.modal-body');
   	$(table).empty();
   });

   $('.btn-remove-transaction').on('click',function(e){
   	if (confirm('Are you sure want to delete this?')) {
   		return true;
   	} else {
   		return false;
   	}
   });

   var select_product_set_custom 				= $('.select-product-set-custom');
   var select_product_size_set_custom 			= $('.select-product-size-set-custom');
   var thisInputQRSetCustom 					= $('#search-set-custom').find('input[type="search"]');
   var getProductSizeSetCustom 					= false;

   isScanning=false; /* variable to stop or start scanning loop */
   function qrReaderInitLoginWeb(callback=null){

   	var loadingMessage  = document.getElementById("loadingMessage"), /*element for show loading message */
   	wrapper         = $(".wrapper"),
   	canvasElement   = document.getElementById("canvas"),
   	canvas          = canvasElement.getContext("2d"),
   	video           = document.createElement("video"),
   	streamTemp      = null;

   	function drawLine(begin, end, color) { /* draw image from camera device to canvas */
   		canvas.beginPath();
   		canvas.moveTo(begin.x, begin.y);
   		canvas.lineTo(end.x, end.y);
   		canvas.lineWidth = 4;
   		canvas.strokeStyle = color;
   		canvas.stroke();
   	}

   	function tick() { /* function that call every second */

   		if (video.readyState === video.HAVE_ENOUGH_DATA) {
	            //loadingMessage.hidden   = true;
	            canvasElement.hidden    = false;

	            /* for responsive canvas */
	            var m=(parseInt(wrapper.css("width"),10) / 670 );
	            var h=video.videoHeight *m;
	            var w=video.videoWidth *m;

	            if(h<1){h=1}

	            	if(w<1){w=1}

	            		canvasElement.height = h;
	            	canvasElement.width  = w;

	            	canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

	            	var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

	            	var code = jsQR(imageData.data, imageData.width, imageData.height, {
	            		inversionAttempts: "dontInvert",
	            	});

	            	if (code) {/* if code from qrcode found */

	            		drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
	            		drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
	            		drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
	            		drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");

	            		isScanning = false;

	            		$.ajax({
	            			url: base_url + 'adminsite/rental_order/getProductByQR',
	            			type: 'POST',
	            			dataType: 'json',
	            			data: { kode: code.data },
	            			beforeSend: function(data){
	            				button_submit_disabled();
	            				$(loading_main).show();
	            			},
	            			complete: function(){
	            				button_submit_enabled();
	            				$('.close-modal-scan-camera').trigger('click');
	            			},
	            			success: function(result){
	            				if(result.product_id != ""){
	            					getProductSizeSetCustom = result.product_sizestock_id;
	            					setTimeout(function(){
	            						$(select_product_set_custom).select2().val(result.product_id).trigger("change");
	            						$(loading_main).hide();
	            					},200);
	            				} else {
	            					alert("product not found in QR");
	            				}
	            				$(loading_main).hide();
	            			},
	            			fail: function(){
	            				button_submit_enabled();
	            				notification('error','Connection Error or something is wrong please try again later.');
	            				$('.close-modal-scan-camera').trigger('click');
	            				$(loading_main).hide();

	            			},
	            			error: function(xhr, ajaxOptions, thrownError) {
	            				button_submit_enabled();
	            				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	            				$('.close-modal-scan-camera').trigger('click');
	            				$(loading_main).hide();
	            			}

	            		});
	            	}
	            }else{
	            //loadingMessage.innerText = "Loading video...";
	        }

	        if(isScanning){
	        	/* Continue scanning ... */
	        	requestAnimationFrame(tick);
	        }else{
	        	/* Stopping scan ... */
	        	streamTemp.getTracks()[0].stop();
	        }

	    }

	    if(isScanning){
	    	/* Use facingMode: environment to attemt to get the front camera on phones */
	    	navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
	    		window.localStream = stream;
	    		video.srcObject = stream;
	    		video.setAttribute("playsinline", true); /* required to tell iOS safari we don't want fullscreen */
	    		video.play();

	    		isScanning = true;
	    		streamTemp = stream;

	    		requestAnimationFrame(tick);
	    	})
	    	.catch(function(err) {
	    		alert(err + ' Refresh the page.');
	    	});

	    } else {
	    	if(localStream !== undefined){
	    		localStream.getTracks()[0].stop();
	    	}
	    }
	}

	$(select_product_set_custom).on('change', function(e) {
		var id 						= $(this).select2('data')[0].id;
		$.ajax({
			url: base_url + 'adminsite/set_custom/get_sizestock',
			type: 'POST',
			dataType: 'json',
			data: {'id': id},
			beforeSend: function(){
				button_submit_disabled();
				$(loading_main).show();
			},
			complete: function(){
				button_submit_enabled();
				$(loading_main).hide();
			},
			success: function(result){

				if(result.flag == true){
					if(result.template !== undefined){
						$(select_product_size_set_custom).empty();
						setTimeout(function() {
							if(!$.trim($(select_product_size_set_custom).html()).length) {
								$(select_product_size_set_custom).append(result.template);
								setTimeout(function() {
									//console.log(getProductSizeSetCustom);
									if(getProductSizeSetCustom != false){
										$(select_product_size_set_custom).select2().val(getProductSizeSetCustom).trigger("change");
										getProductSizeSetCustom = false;
									}
								},200);
							};
						});
					}

				} else {
					$(select_product_size_set_custom).empty();
				}
			},
			fail: function(){
				button_submit_enabled();
				notification('error','Connection Error or something is wrong please try again later.');
				$(loading_main).hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				button_submit_enabled();
				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$(loading_main).hide();
			}

		});
	});	

	$('#search-set-custom').on('click', function(event) {
		event.preventDefault();
		$('#modal-scan-camera').modal('show');
	});

	$('#modal-scan-camera').on('show.bs.modal', function (e) {
		$(loading_main).show();
		isScanning = true;
		qrReaderInitLoginWeb();
	});

	$('#modal-scan-camera').on('shown.bs.modal', function (e) {
		$(loading_main).hide();
	});

	$('#modal-scan-camera').on('hide.bs.modal', function (e) {
		isScanning = false;
		qrReaderInitLoginWeb();
	});

	$('#add-item-set-custom').on('click',function(e){

		e.preventDefault();

		var getData 				= [];
		var product_id 				= $(select_product_set_custom).select2('data')[0].id;
		var product_nama 			= $(select_product_set_custom).select2('data')[0].text;
		var product_sizestock_id 	= $(select_product_size_set_custom).select2('data')[0].id;
		var product_size 			= $(select_product_size_set_custom).select2('data')[0].text;
		var note					= $("textarea[name='note']").val();

		var search_product_sizestock_id = $(document).find('#table-set-custom').find('input[name="product_sizestock_id[]"]');
		var search_product_size 		= false;

		$.each(search_product_sizestock_id,function(i,v){
			var search_value = $(this).val();
			if(product_sizestock_id == search_value){
				search_product_size = true;
			}
		});

		if(search_product_size == true){
			notification('validation','Produk size sudah ada pada list items.');
		} else {
			getData.push({'product_id':product_id,'product_nama':product_nama,'product_sizestock_id':product_sizestock_id,'product_size':product_size,'note':note});

			$.ajax({
				url: base_url + 'adminsite/set_custom/add_item',
				type: 'POST',
				dataType: 'json',
				data: {'product_id': product_id,'product_sizestock_id':product_sizestock_id},
				beforeSend: function(){
					button_submit_disabled();
					$(loading_main).show();
				},
				complete: function(){
					button_submit_enabled();
					$(loading_main).hide();
				},
				success: function(result){
					if(result.flag == true && result.process == 'insert'){
						var table 	 = $('#table-set-custom').find('tbody');
						var table_tr = $(table).find('tr');
						$(table).append(moresetcustom(getData)).hide().fadeIn(500);
						$("textarea[name='note']").val("");
						if($('.select2-init').length > 0){
							$('.select2-init').val(null).trigger('change');
						}
						notification('success_with_message','Produk telah ditambahkan ke dalam list items','center');
					} else if(result.flag == false && result.process == 'validation'){
						notification('validation',result.message);
					} else {
						notification('error','Something Wrong Please Try Again Later');
					}
				},
				fail: function(){
					button_submit_enabled();
					notification('error','Connection Error or something is wrong please try again later.');
					$(loading_main).hide();

				},
				error: function(xhr, ajaxOptions, thrownError) {
					button_submit_enabled();
					notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					$(loading_main).hide();
				}

			});
		}

	});

	$(document).on('click','.remove-item-set-custom',function(e){
		e.preventDefault();
		var element = $(this).closest('tr');
		$(element).fadeOut(500, function(){
			$(element).remove();
		});
	});

      	/*$('#search-set-custom').on('click', function(event) {
   		event.preventDefault();
	   	$('.scan-qr').val('');
	   	$('#search-set-custom').addClass('open');
	   	$(thisInputQRSetCustom).focus();
		$(thisInputQRSetCustom).scannerDetection('gadingkostum.com/tag/iaa0022-1233');
	});*/

   /*$('#search-set-custom, #search-set-custom button.close').on('click keyup', function(event) {
   	if (event.target.className == 'close' || event.keyCode == 27) {
   		$(this).removeClass('open');
   	}
   });

   $(thisInputQRSetCustom).scannerDetection({
   	onComplete: function(barcode, qty){
   		console.log(barcode);
   		$.ajax({
   			url: base_url + 'adminsite/rental_order/getProductByQR',
   			type: 'POST',
   			dataType: 'json',
   			data: { kode: barcode },
   			beforeSend: function(data){
   				button_submit_disabled();
   				$(loading_main).show();
   			},
   			complete: function(){
   				button_submit_enabled();
   				$('#search-set-custom, #search-set-custom button.close').trigger('click');
   			},
   			success: function(result){
   				console.log(result.input);
   				console.log(result.splitKode);
   				console.log(result.base_url);
   				console.log(result.product_sizestock_id);
   				console.log(result.kode);
   				getProductSizeSetCustom = result.product_sizestock_id;
   				setTimeout(function(){
   					$(select_product_set_custom).select2().val(result.product_id).trigger("change");
   					$(loading_main).hide();
   				},200);
   			},
   			fail: function(){
   				button_submit_enabled();
   				notification('error','Connection Error or something is wrong please try again later.');
   				$(loading_main).hide();

   			},
   			error: function(xhr, ajaxOptions, thrownError) {
   				button_submit_enabled();
   				notification('error',thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   				$(loading_main).hide();
   			}

   		});
   	}
   });

   $(thisInputQRSetCustom).bind('scannerDetectionComplete',function(e,data){
   	console.log('complete');
   })
   .bind('scannerDetectionError',function(e,data){
   	console.log('detection error '+data.string);
   })
   .bind('scannerDetectionReceive',function(e,data){
   	console.log('Recieve');
   	console.log(data.evt.which);
   });*/
});