<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_lib
{

	public $data;

	public function __construct()
	{

		$CI =& get_instance();

		$CI->load->library('form_validation');

		$CI->load->helper('html');

		$CI->load->helper('date');

		$CI->load->model('Auth_model');

	}

	public function is_login()
	{



	}

	public function is_admin()
	{



	}

	public function error_message($message){

		$element  = '<div class="alert alert-danger alert-dismissible"><h5>';
		$element .= '<i class="icon fa fa-ban"></i> Something Happened !</h5><h6>';
		$element .= $message;
		$element .= '</h6></div>';

		return $element;

	}

	public function success_message($message){

		$element  = '<div class="alert alert-success alert-section"><h5>';
		$element .= '<i class="icon fa fa-check"></i>';
		$element .= $message;
		$element .= '</h5></div>';

		return $element;

	}

	public function message_note($message = FALSE,$param = NULL,$image_size = ''){
		
		if($message = TRUE && is_array($param) && !empty($param)){

			$message = array(

				'required_input',
				'image_show',
				'image_size',
				'add_more'

				);

			$element  = '<div class="alert alert-warning">';
			$element .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			$element .= '<h5>Attention!</h5>';

			foreach($param as $index => $value){

				if(in_array($value,$message)){

					if($value == 'required_input'){

						$element .= '- All input is required except publish (optional).<br>';

					}

					if($value == 'image_show'){

						$element .= '- If Your Image is not show, please to remove or compress and resize then re-upload it.<br>';

					}

					if($value == 'image_size' && !empty($image_size)){

						$element .= '- For best quality image resize it into : '.$image_size.'.<br>';

					}

					if($value == 'add_more'){

						$element .= '- If you not use form after click add more, please click remove or it will be read as required form.<br>';

					}


				} 

			}

			$element .= '</div>';

			return $element;

		}

	}

	public function filter_function_more_form($post = array()){

		$CI =& get_instance();

		$empty_key = array();

		foreach($post as $index => $value){

			foreach($value as $key => $row){

				if(empty($row)){

					$empty_key[$key] = $key;

					unset($post[$index][$key]);

				}

			}

		}

		foreach($post as $index => $value){

			foreach($value as $key => $row){

				if(in_array(array_keys($empty_key),array_keys($value)) != $key){

					unset($post[$index][$key]);

				}

			}

		}

		$field_required = array();

		foreach($post as $index => $value){

			$count_values = array_count_values($value);

			if(empty($count_values)){

				$field_required[] = $index;

			}

		}

		if(is_array($field_required) && !empty($field_required)){

			foreach($field_required as $index => $value){

				$message[] = 'The '.ucfirst($value).' field is required';

			}

			return $CI->session->set_flashdata('message',$message);

		} else {

			return $post;

		}

	}

	public function ajax_element_single_action_table($selector = '',$controller = ''){

		if(isset($selector) && isset($controller)){

			$element  = "$('".$selector."').on('click',function(){";

			$element .= "var id = $(this).parent().parent().attr('id');";

			$element .= "if(confirm('Are you sure to update your data?')){";

			$element .= "$.ajax({";

			$element .= "url: '".base_url().$controller."',";

			$element .= "type: 'POST',";

			$element .= "dataType: 'json',";

			$element .= "data: { id: id },";

			$element .= "beforeSend: function(){";

			$element .= "$('.overlay').show();";

			$element .= "},";

			$element .= "success: function(result){";

			$element .= "alert('Update Success, Redirecting..');";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "setTimeout(function(){"; 

			$element .= "window.location.href = '".base_url()."' + result;";

			$element .= "}, 500);";

			$element .= "},";

			$element .= "fail: function(){";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "alert('Something Wrong, Please Try Again');";

			$element .= "}";

			$element .= "})";

			$element .= "} else {";

			$element .= "return false;";

			$element .= "}";

			$element .= "});";

			return $element;

		} else {

			return false;
		}

	}

	public function ajax_element_published($selector = '',$selector_select = '',$controller = ''){

		if(isset($selector) && isset($selector_select) && isset($controller)){

			$element  = "$('".$selector."').on('click',function(){";

			$element .= "var checked_selected = $('".$selector_select."');";

			$element .= "var selected = [];";

			$element .= "var count_checked_length = 0;";

			$element .= "$(checked_selected).each(function(i,e){";

			$element .= "var checked = $(this).prop('checked');";

			$element .= "var checked_length = $(checked).length;";

			$element .= "count_checked_length+=checked_length;";

			$element .= "if(checked){";

			$element .= "selected.push($(this).val());";

			$element .= "}";

			$element .= "});";

			$element .= "if(count_checked_length > 0){";

			$element .= "if(confirm('Are you sure to update your item?')){";

			$element .= "$.ajax({";

			$element .= "url: '".base_url().$controller."',";

			$element .= "type: 'POST',";

			$element .= "dataType: 'json',";

			$element .= "data: { id: selected },";

			$element .= "beforeSend: function(){";

			$element .= "$('.overlay').show();";

			$element .= "},";

			$element .= "success: function(result){";

			$element .= "alert('Update Success, Redirecting..');";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "setTimeout(function(){"; 

			$element .= "window.location.href = '".base_url()."' + result;";

			$element .= "}, 500);";

			$element .= "},";

			$element .= "fail: function(){";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "alert('Something Wrong, Please Try Again');";

			$element .= "}";

			$element .= "})";

			$element .= "} else {";

			$element .= "return false;";

			$element .= "}";

			$element .= "} else {";

			$element .= "alert('You are not yet select items');";

			$element .= "return false;";

			$element .= "}";

			$element .= "});";

			return $element;

		} else {

			return false;
		}

	}

	public function ajax_element_unpublished($selector = '',$selector_select = '',$controller = ''){

		if(isset($selector) && isset($selector_select) && isset($controller)){

			$element = "$('".$selector."').on('click',function(){";

			$element .= "var checked_selected = $('".$selector_select."');";

			$element .= "var selected = [];";

			$element .= "var count_checked_length = 0;";

			$element .= "$(checked_selected).each(function(i,e){";

			$element .= "var checked = $(this).prop('checked');";

			$element .= "var checked_length = $(checked).length;";

			$element .= "count_checked_length+=checked_length;";

			$element .= "if(checked){";

			$element .= "selected.push($(this).val());";

			$element .= "}";

			$element .= "});";

			$element .= "if(count_checked_length > 0){";

			$element .= "if(confirm('Are you sure to update your item?')){";

			$element .= "$.ajax({";

			$element .= "url: '".base_url().$controller."',";
			$element .= "type: 'POST',";
			$element .= "dataType: 'json',";
			$element .= "data: { id: selected },";
			$element .= "beforeSend: function(){";

			$element .= "$('.overlay').show();";

			$element .= "},";
			$element .= "success: function(result){";

			$element .= "alert('Update Success, Redirecting..');";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "setTimeout(function(){ ";

			$element .= "window.location.href = '".base_url()."' + result;";

			$element .= "}, 500);";

			$element .= "},";
			$element .= "fail: function(){";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "alert('Something Wrong, Please Try Again');";

			$element .= "}";

			$element .= "})";

			$element .= "} else {";

			$element .= "return false;";

			$element .= "}";

			$element .= "} else {";

			$element .= "alert('You are not yet select items');";

			$element .= "return false;";

			$element .= "}";

			$element .= "});";

			return $element;

		} else {

			return false;
		}

	}

	public function ajax_element_delete($selector = '',$selector_select = '',$controller = ''){

		if(isset($selector) && isset($selector_select) && isset($controller)){

			$element  = "$('".$selector."').on('click',function(){";

			$element .= "var checked_selected = $('".$selector_select."');";

			$element .= "var selected = [];";

			$element .= "var count_checked_length = 0;";

			$element .= "$(checked_selected).each(function(i,e){";

			$element .= "var checked = $(this).prop('checked');";

			$element .= "var checked_length = $(checked).length;";

			$element .= "count_checked_length+=checked_length;";

			$element .= "if(checked){";

			$element .= "selected.push($(this).val());";

			$element .= "}";

			$element .= "});";

			$element .= "if(count_checked_length > 0){";

			$element .= "if(confirm('Are you sure to update your item?')){";

			$element .= "$.ajax({";

			$element .= "url: '".base_url().$controller."',";
			$element .= "type: 'POST',";
			$element .= "dataType: 'json',";
			$element .= "data: { id: selected },";
			$element .= "beforeSend: function(){";

			$element .= "$('.overlay').show();";

			$element .= "},";
			$element .= "success: function(result){";

			$element .= "alert('Delete Success, Redirecting..');";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "setTimeout(function(){ ";

			$element .= "window.location.href = '".base_url()."' + result;";

			$element .= "}, 500);";

			$element .= "},";
			$element .= "fail: function(){";

			$element .= "$('.overlay').hide().fadeOut('fast');";

			$element .= "alert('Something Wrong, Please Try Again');";

			$element .= "}";

			$element .= "})";

			$element .= "} else {";

			$element .= "return false;";

			$element .= "	}";

			$element .= "} else {";

			$element .= "alert('You are not yet select items');";

			$element .= "return false;";

			$element .= "}";

			$element .= "});";

			return $element;

		} else {

			return false;
		}

	}

	public function datatables_data($query,$data = array()){
		//$draw = intval($this->input->get("draw"));
		//$start = intval($this->input->get("start"));
		//$length = intval($this->input->get("length"));

		//$query = $this->db->get("items");

/*
		foreach($query->result() as $r) {
			$data[] = array(
				$r->id,
				$r->title,
				$r->description
				);
			}*/

			$result = array(
			//"draw" => $draw,
				"recordsTotal" => $query->num_rows(),
				"recordsFiltered" => $query->num_rows(),
				"data" => $data
				);

			return $result;
		}

		public function datatables_init($turn_on = FALSE,$selector = ''){

			if($turn_on == TRUE && isset($selector)){

				$element = "var non_drag_table = $('".$selector."').DataTable({";

				$element .= "responsive: true,";

				$element .= "scrollCollapse: true,";

				$element .= "scrollY: 500,";

				$element .= "scrollX: '100%',";

				$element .= "paging: false";

				$element .= "});";

				return $element;

			} else {

				return false;

			}

		}

		public function datatables_ajax_stocklist($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){
				$element = "var geturl = $('#geturl').val();";
				$element .= "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "$.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
					alert(message);
				};";
				$element .= "var ajax_data_table = $('#".$selector."').DataTable({";
				$element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				$element .= "processing: true,";
				$element .= "serverSide: true,";
				$element .= "serverMethod: 'post',";
				$element .= "searching: false,";
				$element .= "bInfo : false,";
				$element .= "filter: true,";
				$element .= "ordering: false,";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
				$element .=	"columnDefs: [";
				if(empty($section)){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
				}
				$element .=	"],";

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";
				$element .= "dataType: 'json',";
				$element .= "data: function (d) {
					d['geturl'] = geturl;
				},"; // insert query string variable into datatable post
				$element .= "dataSrc: function ( json ) {";
				$element .= "console.log(json);";
				$element .= "dataTableJson.push(json.aaData);";
				$element .= "return json.aaData;";
				$element .= "}";
				$element .= "},";

				$element .=	"columns: [
				{ data: 'product_nama' },
				{ data: 'product_kode' },
				{ data: 'product_isipaket' },
				{ data: 'product_size' },
				{ data: 'stock' },
				{ data: 'label' }
				],";

				$element .= "});";
				$element .= "console.log(dataTableJson);";
				return $element;

			} else {

				return false;

			}

		}
		
		public function datatables_ajax_product($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){
				$element = "var geturl = $('#geturl').val();";
				$element .= "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "$.fn.dataTable.ext.errMode = 'throw';";
				$element .= "var ajax_data_table = $('#".$selector."').DataTable({";
				$element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				$element .= "processing: true,";
				$element .= "serverSide: true,";
				$element .= "serverMethod: 'post',";
				$element .= "searching: false,";
				$element .= "bInfo : false,";
				$element .= "filter: true,";
				$element .= "ordering: false,";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
				$element .=	"columnDefs: [";
				if($section == 'rental_order'){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': 1 },";
				}

				if(empty($section)){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
				}
				$element .=	"],";

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";
				$element .= "dataType: 'json',";
				$element .= "data: function (d) {
					d['geturl'] = geturl;
				},"; // insert query string variable into datatable post
				$element .= "dataSrc: function ( json ) {";
				$element .= "console.log(json);";
				$element .= "dataTableJson.push(json.aaData);";
				$element .= "return json.aaData;";
				$element .= "}";
				$element .= "},";

				$element .=	"columns: [
				{ data: 'checkbox' },
				{ data: 'image' },
				{ data: 'product_nama' },
				{ data: 'product_kode' },
				{ data: 'product_modified' },
				{ data: 'status' },
				{ data: 'action' },
				],";

				$element .= "});";
				return $element;

			} else {

				return false;

			}

		}
		
		public function datatables_ajax_bookinglist($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){
				$element = "var geturl = $('#geturl').val();";
				$element .= "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.ext.errMode = 'throw';";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "var ajax_data_table = $('#".$selector."').DataTable({";
				$element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				$element .= "processing: true,";
				$element .= "serverSide: true,";
				$element .= "serverMethod: 'post',";
				$element .= "searching: false,";
				$element .= "bInfo : false,";
				$element .= "filter: true,";
				$element .= "ordering: false,";
				$element .= "paging: true,";
				$element .= "iDisplayLength: 50,";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
				$element .=	"columnDefs: [";
				if(empty($section)){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
				}
				$element .=	"],";

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";
				$element .= "dataType: 'json',";
				$element .= "data: function (d) {
					d['geturl'] = geturl;
				},"; // insert query string variable into datatable post
				$element .= "dataSrc: function ( json ) {";
				$element .= "console.log(json);";
				$element .= "dataTableJson.push(json.aaData);";
				$element .= "return json.aaData;";
				$element .= "}";
				$element .= "},";

				$element .=	"columns: [
				{ data: 'rental_order_id' },
				{ data: 'customer_name' },
				{ data: 'product' },
				{ data: 'rental_created' },
				{ data: 'rental_start_date' },
				{ data: 'rental_end_date' },
				{ data: 'rental_status' },
				{ data: 'action' }
				],";

				$element .= "});";
				$element .= "console.log(dataTableJson);";
				return $element;

			} else {

				return false;

			}

		}
		
		public function datatables_ajax_rented($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){
				$element = "var geturl = $('#geturl').val();";
				$element .= "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "$.fn.dataTable.ext.errMode = 'throw';";
				$element .= "var ajax_data_table = $('#".$selector."').DataTable({";
				$element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				$element .= "processing: true,";
				$element .= "serverSide: true,";
				$element .= "serverMethod: 'post',";
				$element .= "searching: false,";
				$element .= "bInfo : false,";
				$element .= "filter: true,";
				$element .= "ordering: false,";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
				$element .=	"columnDefs: [";
				if($section == 'rental_order'){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': 1 },";
				}

				if(empty($section)){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
				}
				$element .=	"],";

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";
				$element .= "dataType: 'json',";
				$element .= "data: function (d) {
					d['geturl'] = geturl;
				},"; // insert query string variable into datatable post
				$element .= "dataSrc: function ( json ) {";
				$element .= "console.log(json);";
				$element .= "dataTableJson.push(json.aaData);";
				$element .= "return json.aaData;";
				$element .= "}";
				$element .= "},";

				$element .=	"columns: [
				{ data: 'product_nama' },
				{ data: 'product_kode' },
				{ data: 'rented' },
				],";

				$element .= "});";
				return $element;

			} else {

				return false;

			}

		}
		
		public function datatables_ajax_serverside($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){
				$element = "var geturl = $('#geturl').val();";
				$element .= "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "$.fn.dataTable.ext.errMode = 'throw';";
				$element .= "var ajax_data_table = $('#".$selector. "').DataTable({";
				$element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				$element .= "processing: true,";
				$element .= "serverSide: true,";
				$element .= "serverMethod: 'post',";
				$element .= "searching: false,";
				$element .= "bInfo : false,";
				$element .= "filter: true,";
				$element .= "ordering: false,";
				$element .= "paging: true,";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
				$element .=	"columnDefs: [";
				if($section == 'rental_order'){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': 1 },";
				}

				if(empty($section)){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
				}
				$element .= "{ 'width': 90, 'targets': 2 },";
				$element .=	"],";
				$element .= "fixedColumns: true,";

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";
				$element .= "dataType: 'json',";
				$element .= "data: function (d) {
					d['geturl'] = geturl;
				},"; // insert query string variable into datatable post
				$element .= "dataSrc: function ( json ) {";
				$element .= "dataTableJson.push(json.aaData);";
				$element .= "return json.aaData;";
				$element .= "}";
				$element .= "},";

				$element .=	"columns: [
				{ data: 'checkbox' },
				{ data: 'invoice' },
				{ data: 'name' },
				{ data: 'created' },
				{ data: 'hargasewa' },
				{ data: 'payment' },
				{ data: 'deposit' },
				{ data: 'status' },
				{ data: 'action' },
				],";

				$element .= "});";
				return $element;

			} else {

				return false;

			}

		}

		public function datatables_ajax_data($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){

				$element = "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "var ajax_data_table = $('#".$selector."').DataTable({";
                $element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
/*				$element .= "responsive: true,";

				$element .= "scrollX: '100%',";*/
				$element .=	"columnDefs: [";
				if($section == 'rental_order'){
       				$element .=	"{ 'type': 'natural-nohtml', 'targets': 1 },";
       				//$element .=	"{ 'type': 'de_datetime', 'targets': 3}";
       			}

       			if(empty($section)){
       				$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
       			}
     			$element .=	"],";

     			/*$element .=	"columnDefs: [";
       			$element .=	"{ 'type': 'de_datetime', 'targets': 3 }";
     			$element .=	"],";
*/
				if(!empty($plugin_download)){
					$plugin_init = '';
					$plugin_text = '';
					switch ($plugin_download) {
					case "excel":
					$plugin_init		= "excel";
					$plugin_text 		= "Download XLS";
					break;
					case "pdf":
					$plugin_init		= "pdfHtml5";
					$plugin_text 		= "Download PDF";
					break;
				}
				$element .=	"dom: 'Bfrtip',";
				$element .=	"aaSorting: '[]',";

				$element .= "buttons: [ { ";

				$element .= "extend: '".$plugin_init."' ,";
				$element .= "text: '".$plugin_text."' ,";

  				$element .= "pageSize: 'LEGAL',";
  				$element .= "exportOptions: {";
     			if(!empty($columns_download)){
  					$element .= "columns: ".$columns_download;
  				}
  				$element .= "},";

  				$element .=	"customize : function(doc){";

  				//Remove the title created by datatTables
				$element .=	"doc.content.splice(0,1);";

				//Create a date string that we use in the footer. Format is dd-mm-yyyy
				$element .=	"var now = new Date();";
				$element .=	"var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();";

				// Create a footer object with 2 columns
				// Left side: report creation date
				// Right side: current page and total pages
						$element .=	"doc['footer']=(function(page, pages) {";
							$element .=	"return {";
								$element .=	"columns: [";
									$element .=	"{";
										$element .=	"alignment: 'left',";
										$element .=	"text: ['Created on: ', { text: jsDate.toString() }]";
									$element .=	"},";
									$element .=	"{";
										$element .=	"alignment: 'right',";
										$element .=	"text: ['page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]";
									$element .=	"}";
								$element .=	"],";
								$element .=	"margin: 20";
							$element .=	"}";
						$element .=	"});";

				// Change dataTable layout (Table styling)
				// To use predefined layouts uncomment the line below and comment the custom lines below
				// doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
						$element .=	"var objLayout = {};";
						$element .=	"objLayout['hLineWidth'] = function(i) { return .5; };";
						$element .=	"objLayout['vLineWidth'] = function(i) { return .5; };";
						$element .=	"objLayout['hLineColor'] = function(i) { return '#000'; };";
						$element .=	"objLayout['vLineColor'] = function(i) { return '#000'; };";
						$element .=	"objLayout['paddingLeft'] = function(i) { return 4; };";
						$element .=	"objLayout['paddingRight'] = function(i) { return 4; };";
						$element .=	"doc.content[0].layout = objLayout;";

				$element .=	"doc.styles.tableHeader = {alignment: 'center',bold: true,color: 'black',fillColor: 'white',fontSize: 11};";
				$element .=	"doc.styles.tableBodyOdd = {fillColor: 'white'};";

  				$element .=	"doc.defaultStyle.alignment = 'left';";
     			$element .=	"doc.pageMargins = [10, 10, 10,10 ];";

            	$element .=	"var colCount = new Array();";
            	$element .=	"$(ajax_init_table).find('tbody tr:first-child td').each(function(){";
                $element .=	"if($(this).attr('colspan')){";
                $element .=	"for(var i=1;i<=$(this).attr('colspan'); i++){";
                $element .=	"colCount.push('*');";
                $element .=	"}";
                $element .=	"}else{ colCount.push('*'); }";
            	$element .=	"});";
            	$element .=	"doc.content[0].table.widths = colCount;";
        		$element .=	"}";


				$element .= "}],";
				}

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";

				$element .= "type : 'GET'";

				$element .= "}";

				$element .= "});";

				return $element;

			} else {

				return false;

			}

		}

		public function datatables_ajax_setcustom($turn_on = FALSE,$selector = '',$controller = '',$plugin_download = '',$another_options = '',$columns_download = '',$section = ''){

			if($turn_on == TRUE && isset($selector)){
				$element = "var geturl = $('#geturl').val();";
				$element .= "var ajax_init_table = $('#".$selector."');";
				$element .= "$.fn.dataTable.ext.errMode = 'throw';";
				$element .= "$.fn.dataTable.moment('DD-MM-YYYY hh:mm A');";
				$element .= "var ajax_data_table = $('#".$selector."').DataTable({";

				$element .=	"order: [],";
				$element .=	"aaSorting: [],";
				$element .= "lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']],";
				$element .= "bPaginate: true,";
				$element .= "bProcessing: true,";
				$element .= "bServerSide: true,";
				$element .= "searching: false,";
				$element .= "filter: false,";
				$element .= "ordering: false,";
				if(is_array($another_options) && !empty($another_options)){
					foreach($another_options as $index => $value){
						$element .= $value;
					}
				}
				$element .=	"columnDefs: [";
				if(empty($section)){
					$element .=	"{ 'type': 'natural-nohtml', 'targets': '_all' },";
				}
				$element .=	"],";

				$element .= "ajax: {";

				$element .= "url: '".base_url().$controller."',";
				$element .= "dataType: 'json',";
				$element .= "data: function (d) {
					d['geturl'] = geturl;
				},"; // insert query string variable into datatable post
				$element .= "dataSrc: function ( json ) {";
				$element .= "console.log(json);";
				$element .= "dataTableJson.push(json.aaData);";
				$element .= "return json.aaData;";
				$element .= "}";
				$element .= "},";

				$element .=	"columns: [
				{ data: 'checkbox' },
				{ data: 'karyawan_nama' },
				{ data: 'created' },
				{ data: 'product_nama' },
				{ data: 'product_size' },
				{ data: 'note' },
				{ data: 'action' }
				],";
				$element .= "rowsGroup: [
        0,
        1,
        2,
        6
    ],";
				$element .= "});";
				//$element .= "console.log(dataTableJson);";
				return $element;

			} else {

				return false;

			}

		}
		
		public function datatables_roworder($turn_on = FALSE,$selector = '',$controller = ''){

			if($turn_on == TRUE && isset($selector) && isset($controller)){

				$element = "var drag_table = $('#".$selector."').DataTable({";

				$element .= "rowReorder: true,";

				$element .= "responsive: true,";

				$element .= "scrollCollapse: true,";

				$element .= "scrollY: 450,";

				$element .= "scrollX: '100%',";

				$element .= "paging: false,";

				$element .= "'sDom': 'rt'";

				$element .= "});";

				$element .= "drag_table.on( 'row-reorder', function ( e, diff, edit ) {";

				$element .= "setTimeout(function() {";

				$element .= "var id = [];";

				$element .= "var sort = [];";

				$element .= "$('".$selector."').find('tbody').find('tr').each(function(i,r){";

				$element .= "id.push($(r).attr('id'));";

				$element .= "sort.push(i);";

				$element .= "});";

				$element .= "if(diff.length > 0){";

				$element .= "$.ajax({";

				$element .= "url: '".base_url().$controller."',";

				$element .= "type: 'POST',";

				$element .= "dataType: 'json',";

				$element .= "data: { id: id, sort: sort },";

				$element .= "beforeSend: function(data){";

				$element .= "$('.overlay').show();";

				$element .= "	},";

				$element .= "success: function(result){";

				$element .= "var data = $.parseJSON(result);";

				$element .= "alert('Update Sort Success');";

				$element .= "if(data == true){";

				$element .= "$('.overlay').hide().fadeOut('fast');";

				$element .= "}";

				$element .= "},";

				$element .= "fail: function(){";

				$element .= "alert('Something wrong, please refresh your browser');";

				$element .= "$('.overlay').hide().fadeOut('fast');";

				$element .= "}";

				$element .= "});";

				$element .= "}";

				$element .= "}, 10);";

				$element .= " });";

				return $element;

			} else {

				return false;

			}

		}

		public function required_message_only($key,$message){

			return $message;

		}

		public function alert_bootstrap($message = ''){

			if(isset($message)){

				$element  = '<div id="alert-notifications" style="padding-right: 0;" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">';

				$element .= '<div class="modal-dialog modal-sm">';

				$element .= '<div class="modal-content">';

				$element .= '<div class="modal-header">';

				$element .= '<h4 class="modal-title"><i class="fa fa-info-circle"></i> Notifications</h4>';

				$element .= '</div>';

				$element .= '<div class="modal-body">';

				$element .= $message;

				$element .= '</div>';

				$element .= '<div class="modal-footer">';

				$element .= '<button type="button" class="close btn btn-default">OK</button>';

				$element .= '</div>';

				$element .= '</div>';

				$element .= '</div>';

				$element .= '</div>';

				return $element;

			} else {

				return false;

			}

		}


	}