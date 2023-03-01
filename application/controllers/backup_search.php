<?php



defined('BASEPATH') OR exit('No direct script access allowed');



class Search extends CI_Controller{



	public function __construct()



	{

		parent::__construct();

		$this->load->model('front_model');

		$this->load->model('backend_model');

		$this->load->model('global_model');

		$this->load->library('categories_menu_lib');

		$sections = array(
			'config'  => TRUE,
			'queries' => TRUE
			);

		$this->output->set_profiler_sections($sections);

	}

	public function index(){

		$data['title'] 		 		= 'Search - Gading Kostum';

		$data['meta'] = array(
				'title'		  =>  $data['title'],
				'type'		  => 'article',
				'description' => 'Sewa kostum terbaru untuk anak dan dewasa di kelapa gading jakarta - kostum karakter, superhero, adat, profesi, negara, dll. Bisa dikirim / diantar',
				'site_name'   => 'Gading Kostum',
				'locale'	  => 'en_US',
				'card'		  => 'summary',
				'canonical'   => current_url(),
				'url'		  => current_url()
			);
		
		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');
		
		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');

		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');

		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');

		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');

		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');
		
		$search = trim($this->input->get('k', TRUE));

		$data['result'] = array();

		$this->load->view('v_search',$data);

	}

	function getparamurl($post = '',$page = ''){

		$change_url 	= false;
		$result_url 	= '';
		$query      	= array();
		$order_by    	= '';
		$order_date     = '';
		$show_display 	= '';
		$keyword 		= array();

		$get_selected 					= array();
		$get_selected['size'] 			= array();
		$get_selected['gender'] 		= array();
		$get_selected['store_location']	= array();

		$final 				 	= array();
		$final['result_url'] 	= $result_url;
		$final['query']		 	= $query;
		$final['change_url'] 	= $change_url;
		$final['order_by'] 	 	= $order_by;
		$final['order_date'] 	= $order_date;
		$final['show_display'] 	= $show_display;
		$final['keyword']		= $keyword;
		$final['get_selected']  = $get_selected;

		if(!empty($post)){
			$flag 				= isset($post['categories']) ? $post['categories'] : '';
			$slug 				= isset($post['slug']) ? $post['slug'] : '';
			$checked 			= isset($post['checked']) ? $post['checked'] : '';
			$classname 			= isset($post['classname']) ? $post['classname'] : '';
			$current_classname 	= isset($post['current_classname']) ? $post['current_classname'] : '';
			$category   		= $this->global_model->select('category');
			$url 				= isset($post['url']) ? $post['url'] : '';
			$page 				= isset($post['page']) ? $post['page'] : '';

			if(empty($page) || $page == ""){
				$page = 1;
			}

			$limit 			= 12;

			if(isset($url) && isset($url['show'])){
				$limit 		= $url['show'];
			}

			$get_date_start     = '';
			$get_date_end 		= '';

			$format_date_start  = '';
			$format_date_end    = '';

			if(isset($url) && isset($url['start'])){
				$get_date_start    = date('j F Y',strtotime($url['start']));
				$format_date_start = date('Y-m-d',strtotime($url['start']));
				$url['start'] 	   = date('Y-m-d',strtotime($url['start']));
			}

			if(isset($url) && isset($url['end'])){
				$get_date_end = date('j F Y',strtotime($url['end']));
				$format_date_end = date('Y-m-d',strtotime($url['end']));
				$url['end'] = date('Y-m-d',strtotime($url['end']));
			}

			if(isset($url['k'])){
				$url['k'] = strtolower(str_replace("+"," ",$url['k']));
				$url['k'] = explode('_',$url['k']);
				foreach($url['k'] as $index => $value){
					$search_query[] = $value;
					$decoded = urldecode($value);
					$decoded = str_replace("\\","",$value);
					$decoded = addslashes($value);
					$decoded = htmlentities($value);
					$url['k'][$index] = $decoded;
				}
			}

			$check_correction = $this->front_model->get_correction();
			if(!empty($check_correction)){

				$get_correction = array();
				foreach($check_correction as $index => $value){
					$corr_decode[$index]['wrong'] = json_decode($value['wrong']);
					if(!empty($corr_decode)){
						foreach($corr_decode as $key => $row){
							foreach($row as $k => $r){
								if($index == $key){
									$corr_decode[$index]['right'][] = $value['right'];
								}
							}
						}
					}
				}

				if(!empty($get_correction)){
					$get_correction = array_unique($get_correction);
				}

				if(!empty($corr_decode)){
					foreach($corr_decode as $index => $value){
						foreach($value['wrong'] as $key => $row){

						// ganti wrong menjadi right;
							if(!empty($url['k'])){
								if(in_array($row,$url['k'])){
									foreach($url['k'] as $k => $r){
										if($row == $r){
											unset($url['k'][$k]);
										}
									}
									$url['k'][] = $value['right'][0];
									$url['k'] 	= array_values($url['k']);
								}
							} 
						}
					}
				}

				if(isset($url['k']) && !empty($url['k'])){
				//$url['k'] = explode('_',$url['k']);
					foreach($url['k'] as $index => $value){
						$search_query[] = $value;
						$decoded = urldecode($value);
						$decoded = str_replace("\\","",$value);
						$decoded = addslashes($value);
						$decoded = htmlentities($value);
						$url['k'][$index] = $decoded;
					}
				}

			} else {

				if(isset($url['k']) && !empty($url['k'])){
				//$url['k'] = explode('_',$url['k']);
					foreach($url['k'] as $index => $value){
						$search_query[] = $value;
						$decoded = urldecode($value);
						$decoded = str_replace("\\","",$value);
						$decoded = addslashes($value);
						$decoded = htmlentities($value);
						$url['k'][$index] = $decoded;
					}
				}

			}

			if(isset($url['gender']) && !empty($url['gender']) && is_string($url['gender'])){
				$get_selected['gender'] = explode('||',$url['gender']);
				$url['gender'] 	= explode('||',$url['gender']);
			}

			if(isset($url['size']) && !empty($url['size']) && is_string($url['size'])){
				$get_selected['size'] = explode('||',$url['size']);
				$url['size'] 	= explode('||',$url['size']);
			}

			if(isset($url['store_location']) && !empty($url['store_location']) && is_string($url['store_location'])){
				$get_selected['store_location'] = explode('||',$url['store_location']);
				$url['store_location'] 	= explode('||',$url['store_location']);
			}

			if(isset($url) && is_array($url) && !empty($url)){

				$check_key_exist 	= array_key_exists($flag, $url);

				$slug_array_push 	= false;

				if($flag == 'size' || $flag == 'gender' || $flag == 'store_location'){

					if($check_key_exist == true){

						foreach($url as $index => $value){

							if($index == $flag){

								if(in_array($slug,$value)){

									$count_array 	= count($value);

									foreach($value as $key => $row){
										if($count_array <= 1 && $row == $slug){

											unset($url[$index]);

										} elseif($row == $slug) {

											unset($url[$index][$key]);

										} elseif($index == 'page'){

											unset($url[$index]);

										}
									}

								} else {

									array_push($url[$index],$slug);

								}

							}
						}
					} else {
						$url[$flag][0] = $slug;

						if(isset($url['show']) && is_numeric($url['show']) && !is_array($url['show'])){
							$url['show'] = array($url['show']);
						}

						if(isset($url['order_by']) && !is_array($url['order_by'])){
							$url['order_by'] = array($url['order_by']);
						}

						if(isset($url['k']) && !is_array($url['k'])){
							$url['k'] = array($url['k']);
						}
					}
				}

				if(is_array($url) && !empty($url)){

					$temp_show_display = array();
					foreach($url as $index => $value){
						if(is_array($value)){
							$url[$index] 	= array_values($url[$index]);
							$query[$index] 	= array_values($url[$index]);
							if($index != 'k'){
								$url[$index] 	= implode('||',$url[$index]);
							} elseif($index == 'k'){
								$url[$index] 	= implode('_',$url[$index]);
							}
						}

						if($index == 'show'){
							$url[$index]	= $url[$index];
						}

						if($index == 'order_by'){
							$url[$index]			= $url[$index];
							$order_by[$index] 	= $url[$index];
						}

						if($index == 'k'){
							$url[$index]			= $url[$index];
							$keyword[$index] 		= $url[$index];
						}
					}

					$result_url  = http_build_query($url);
					$result_url  = urldecode($result_url);
				}

			} else {
			// jika tidak ada isi dari variable url

				if($flag == 'size' || $flag == 'gender' || $flag == 'store_location' && !empty($slug)){
					$url[$flag][0] 		= $slug;
					$query[$flag][0] 	= $slug;
					$url[$flag] 		= implode('||',$url[$flag]);
				} elseif($flag == 'k'){
					$url[$flag] 		= implode('_',$url[$flag]);
				}

				if(is_array($url) && !empty($url)){
					$result_url  		= http_build_query($url);
					$result_url  		= urldecode($result_url);
				} else {
					$result_url 		= '';
					$result_url 		= urldecode($result_url);
				}
			}

			$final['result_url'] 	= $result_url;
			$final['query']		 	= $query;
			$final['change_url'] 	= $change_url;
			$final['order_by'] 	 	= $order_by;
			$final['order_date'] 	= $order_date;
			$final['show_display'] 	= $show_display;
			$final['keyword']		= $keyword;
			$final['get_selected']  = $get_selected;

		}

		return $final;
	}

	public function filteringsearch($page = 1){
		if($this->input->is_ajax_request()){

		$getpost		= $this->input->post(NULL, TRUE);

		$flag 			= $this->input->post('categories',true);
		$slug 			= $this->input->post('slug',true);
		$checked 		= $this->input->post('checked',true);
		$classname 		= $this->input->post('classname',true);
		$current_classname = $this->input->post('current_classname',true);
		$category   	= $this->global_model->select('category');
		$url 			= $this->input->post('url',true);
		$page 			= $this->input->post('page',true);
		
		$tes_query 		= array();
		$tes_total_rows = array();

		if(empty($page) || $page == ""){
			$page = 1;
		}

		$limit 			= 12;

		$get_selected   = array();

		if(isset($url) && isset($url['show'])){
			$limit 		= $url['show'];
		}

		$get_date_start     = '';
		$get_date_end 		= '';

		$format_date_start  = '';
		$format_date_end    = '';

		if(isset($url) && isset($url['start'])){
			$get_date_start    = date('j F Y',strtotime($url['start']));
			$format_date_start = date('Y-m-d',strtotime($url['start']));
			$url['start'] 	   = date('Y-m-d',strtotime($url['start']));
		}

		if(isset($url) && isset($url['end'])){
			$get_date_end = date('j F Y',strtotime($url['end']));
			$format_date_end = date('Y-m-d',strtotime($url['end']));
			$url['end'] = date('Y-m-d',strtotime($url['end']));
		}

		$display_show   = array(12,24,48);
		$sort_show      = array(
			array(
				'name'  => 'order_by',
				'value' => 'desc',
				'text'  => 'Product Name Z-A'
				),
			array(
				'name'  => 'order_by',
				'value' => 'date_desc',
				'text'  => 'Sort by newness'
				),
			array(
				'name'  => 'order_by',
				'value' => 'popularity',
				'text'  => 'Sort by popularity'
				),
			array(
				'name'  => 'order_by',
				'value' => 'price_asc',
				'text'  => 'Sort by price: low to high'
				),
			array(
				'name'  => 'order_by',
				'value' => 'price_desc',
				'text'  => 'Sort by price: high to low'
				),
			);

		$getparamurl    = $this->getparamurl($getpost,$page);
		$result_url 	= $getparamurl['result_url'];
		$query 			= $getparamurl['query'];
		$order_by    	= $getparamurl['order_by'];
		$order_date     = $getparamurl['order_date'];
		$show_display 	= $getparamurl['show_display'];
		$keyword 		= $getparamurl['keyword'];

		$get_selected 	= $getparamurl['get_selected'];

		$template = '';
		$base_url_query_string = '';
		if(!empty($result_url)){
			$base_url_query_string = '?'.$result_url;
		}

		$data_page 					= (isset($page)) ? $page : 0;
		$config_page  				= 0;

		if($data_page > 0){
			$config_page = $data_page-1;
		}

		$total_rows = 0;
		$offset 	= $config_page*$limit;
		if(isset($order_by) && isset($order_by['order_by']) && $order_by['order_by'] == 'popularity'){
			$database   = $this->front_model->search_product_popularity($query,$limit,$offset,$order_by,'',$format_date_start,$format_date_end);
		} else {
			$database 	= $this->front_model->search_product($query,$limit,$offset,$order_by,'',$format_date_start,$format_date_end);
		}

		$search_database_by_keyword = array();
		$query_product_available    = array();
		$product_unavailable 		= array();
		$cek_query = array();
		if(!empty($database)){
			foreach($database as $index => $value){
				if(is_array($query) && !empty($query)){
					if(isset($query['k']) && is_array($query['k'])){
						foreach($query['k'] as $key => $row){
							$search_product_nama 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['product_nama']));
							$search_product_kode	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['product_kode']));
							if($value['category_name'] == 'men'){
								$search_category_name 	 = $this->like_match(strtolower($row).'%',strtolower($value['category_name']));
							} elseif($value['category_name'] !== 'men'){
								$search_category_name 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['category_name']));
							}
							if($search_product_nama || $search_product_kode || $search_category_name){
								$search_database_by_keyword[$value['product_id']] = $value;
								
							} else {
								$database = array();
								
							} 
						}
					}
				}
			}
		}

		$total_rows = 0;

		if(empty($get_date_start) && empty($get_date_end) && !empty($search_database_by_keyword)){
			$search_database_by_keyword = array_values($search_database_by_keyword);
			$total_rows = 0;
			foreach($search_database_by_keyword as $index => $value){
				$total_rows++;
			}
			$search_database_by_keyword = array_slice($search_database_by_keyword,$offset,$limit);
			unset($database);
			$database = $search_database_by_keyword;	
		} else {
			if(!empty($database)){
				$total_rows = 0;
				foreach($database as $index => $value){
					$total_rows++;
				}
				$database = array_slice($database,$offset,$limit);
			}
		}

		$data_product = array();
		$tes = array();
		if(!empty($get_date_start) && !empty($get_date_end)){

			$get_all_stock 	= $this->front_model->search_sum_product($query,$keyword);
			$get_all_stock_by_keyword 	= array();
			if(!empty($get_all_stock)){
				foreach($get_all_stock as $index => $value){
					if(is_array($query) && !empty($query)){
						if(isset($query['k']) && is_array($query['k'])){
							foreach($query['k'] as $key => $row){
								$search_product_nama 	 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['product_nama']));
								$search_product_kode	 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['product_kode']));
								if($value['category_name'] == 'men'){
									$search_category_name 	 = $this->like_match(strtolower($row).'%',strtolower($value['category_name']));
								} elseif($value['category_name'] !== 'men'){
									$search_category_name 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['category_name']));
								}
								if($search_product_nama || $search_product_kode || $search_category_name){
									$get_all_stock_by_keyword[$value['product_id']] = $value;
									
								} else {
									$get_all_stock = array();
									
								}
							}
						}
					}
				}

				if(!empty($get_all_stock_by_keyword)){
					$get_all_stock_by_keyword = array_values($get_all_stock_by_keyword);
					//$get_all_stock_by_keyword = array_slice($get_all_stock_by_keyword,$offset,$limit);
					unset($get_all_stock);
					$get_all_stock = $get_all_stock_by_keyword;
					
				}
			}

			$product_rental 			= array();//product_id
			$product_all_stock  		= array();
			if(!empty($get_all_stock)){
				foreach($get_all_stock as $index => $value){

					if(isset($value['product_sizestock_id']) && !empty($value['product_sizestock_id'])){
						foreach($value['product_sizestock_id'] as $key => $row){
							$product_all_stock[$value['product_id']][$key] = $row['product_stock'];
							$product_rental[$value['product_id']] = $value['product_id'];
						}
					}
				}

				if(!empty($product_rental)){
					$product_rental = array_values($product_rental);
				}
			}

			$current_date 			= date('Y-m-d');
			$day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));
			$product_unavailable 	= array();

			$query_product_rental   = $this->front_model->get_all_rental_order_by_product($product_rental);

			if(!empty($query_product_rental)){
				$no = 0;
				$rental_in_return       = array();
				$rental_in_returndate   = array();

				if(!empty($query)){

					$check_filter_size = array();
					if(isset($query['size']) && !empty($query['size'])){
						foreach($query['size'] as $index => $value){
							$check_filter_size = $this->front_model->get_check_size_filter($value);
						}

						if(is_array($check_filter_size) && !empty($check_filter_size)){

							$get_product_size = array();
							foreach($check_filter_size as $key => $row){
								$get_product_size[] = $row['product_size'];
							}

							if(!empty($get_product_size)){
								foreach($query_product_rental as $key => $row){
									if(!in_array($row['product_size'],$get_product_size)){
										//$tes[] = $row;
										unset($query_product_rental[$key]);
									}
								}
								$query_product_rental = array_values($query_product_rental);
							}
						}
					}
				}
				
				foreach($query_product_rental as $index => $value){
					if((int)$day_after_return && !empty($value['return_date'])){
						$rental_in_return[]                              = $value['rental_order_id'];
						$rental_in_returndate[$value['rental_order_id']] = array(
							'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
							'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
							'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_end_date'])))
							);
					}
				}

				$product                = array();
				$product_already_return = array();
				$sum_qty = 0;
				$stock_product 			= array();
				foreach($query_product_rental as $index => $value){
					$value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));

					$product[$value['product_sizestock_id']][$value['rental_order_id']]  = $value;
					$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty']  = '';
					$stock_product[$value['product_id']] = $value['product_stock'];
				}

				foreach($query_product_rental as $index => $value){

					$value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));
					$value_rental_start_date 	= strtotime($value['rental_start_date']);
					$value_format_date_start 	= strtotime($format_date_start);
					$value['rental_end_date']   = date('Y-m-d',strtotime($value['rental_end_date']));
					$value_rental_end_date   	= strtotime($value['rental_end_date']);
					$value_format_date_end 		= strtotime($format_date_end);

					$rental_order_id        = $value['rental_order_id'];
					$late_date   			= date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_end_date'])));
					$value_late_date 		= strtotime($late_date);
					$return_date 			= '';

					if(!in_array($value['rental_order_id'],$rental_in_return)){

						if(!empty($value['rental_order_id'])) {
								if($value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end &&
									$value_late_date >= $value_format_date_start && $value_late_date <= $value_format_date_end || $value_rental_start_date <= $value_format_date_start && $value_late_date >= $value_format_date_start && $value_late_date >= $value_format_date_end || $value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end || $value_late_date >= $value_format_date_start && $value_late_date <= $value_format_date_end){

									$quantity  = (int) $value['rental_product_qty'];
								if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
									$count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
								} else {
									$count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
								}

								$sum_qty1 = 0;
								foreach($count as $key => $row){

									foreach($row as $k => $v){

										if($k == $value['product_sizestock_id']){
											$sum_qty1 = $v;
										}
										$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
									}
								}
							} else {
								$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
							}

						} else {
							$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
						}

					} elseif(in_array($value['rental_order_id'],$rental_in_return)) {
						
						if(!empty($value['return_date'])){
							$return_date  			= date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date'])));
							$value_return_date 		= strtotime($return_date);
						}

						if(!empty($value['rental_order_id'])) {

							if($value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end || $value_return_date >= $value_format_date_start && $value_return_date <= $value_late_date || $value_return_date >= $value_format_date_start && $value_return_date <= $value_format_date_end){


							$quantity  = (int) $value['rental_product_qty'];
							if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
								$count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
							} else {
								$count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
							}

							$sum_qty2 = 0;
							foreach($count as $key => $row){

								foreach($row as $k => $v){

									if($k == $value['product_sizestock_id']){
										$sum_qty2 = $v;
									}
									$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
									$product[$value['product_sizestock_id']][$value['rental_order_id']]['status'] = 'return';
								}
							}

						} else {
							$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;

						}

							//command 29 Maret 2019
							//if($value_return_date < $value_format_date_end) {
								//$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
							//}

						} else {

							$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
						}

					} else {

						$sum_qty = 0;
						$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
					}
				}

				$count_sizestock 	= array();
				$count_stock 		= array();
				$count_rental 		= array();
				$sums 		 		= array();

				if(!empty($product)){
					foreach($product as $index => $value){
						foreach($value as $key => $row){
							@$count_sizestock[$row['product_id']][$row['product_sizestock_id']]+=$row['rental_qty'];
						}
					}
				}

				$total_stock = array();
				if(!empty($product_all_stock)){
					foreach($product_all_stock as $index => $value){
						foreach($value as $i => $val){
							$sum_rental = $val;
							if(!empty($count_sizestock)){
								foreach($count_sizestock as $key => $row){
									foreach($row as $k => $r){
										if($i == $k){

											if($r > 0){
												$sum_rental = $val - $r;
												$total_stock[$index][$i] = $sum_rental;
											} else {
												//29 Maret 2019
												$sum_rental = $val; // minus sum condition each sizestock or not in date periode
												$total_stock[$index][$i] = $sum_rental;
											}
											//command 29 Maret 2019
											//echo $sum_rental;
											//if($sum_rental <= 0){
												//$sum_rental = $val; // minus sum condition each sizestock or not in date periode
												//$total_stock[$index][$i] = $sum_rental;
											//}

										}
										
									}
								}
							} else {
								$total_stock[$index][$i] = 0;
							}
						}
					}
				}

				if(!empty($total_stock)){
					foreach($total_stock as $index => $value){
						foreach($value as $key => $row){
							@$count_stock[$index]+=$row;
						}
					}
				}

				if(!empty($total_stock)){
					foreach($total_stock as $index => $value){
						foreach($value as $key => $row){
							@$count_rental[$index]+=$row;
						}
					}
				}

				if(isset($stock_product) && isset($count_rental)){
					foreach (array_keys($stock_product + $count_rental) as $key) {
						$sums[$key] = (isset($stock_product[$key]) ? $stock_product[$key] : 0) - (isset($count_rental[$key]) ? $count_rental[$key] : 0);
					}
				}

				if(!empty($count_stock)){
					foreach($count_stock as $key => $row){
						if($row < 1){
							$product_unavailable[] = $key;
						}
					}
				}

				$tes['product_rental'][] = $query_product_rental;
				$tes['product'][] = $product;
				$tes['product_all_stock'][] = $product_all_stock;
				$tes['count_sizestock'][] = $count_sizestock;
				$tes['total_stock'][] = $total_stock;
				$tes['count_stock'][] = $count_stock;
				$tes['stock_product'][] = $stock_product;
				$tes['count_rental'][] = $count_rental;
				$tes['count_sums'][] = $sums;
			}

			if(isset($order_by) && isset($order_by['order_by']) && $order_by['order_by'] == 'popularity'){
			$query_product_available = $this->front_model->search_product_popularity_available($query,$limit,$offset,$order_by,$keyword,$product_unavailable);
			} else {
			$query_product_available = $this->front_model->search_product_available($query,$limit,$offset,$order_by,$keyword,$product_unavailable);
			}

			$search_query_product_available = array();
			if(!empty($query_product_available)){

				foreach($query_product_available as $index => $value){
					if(is_array($query) && !empty($query)){
						if(isset($query['k']) && is_array($query['k'])){
							foreach($query['k'] as $key => $row){
								$search_product_nama 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['product_nama']));
								$search_product_kode	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['product_kode']));
								if($value['category_name'] == 'men'){
									$search_category_name 	 = $this->like_match(strtolower($row).'%',strtolower($value['category_name']));
								} elseif($value['category_name'] !== 'men'){
									$search_category_name 	 = $this->like_match('%'.strtolower($row).'%',strtolower($value['category_name']));
								}
								if($search_product_nama || $search_product_kode || $search_category_name){
									$search_query_product_available[$value['product_id']] = $value;
									
								} else {
									$query_product_available = array();
									
								}
							}
						}
					}
				}
			}

			if(!empty($search_query_product_available)){
				$search_query_product_available = array_values($search_query_product_available);
				$total_rows = 0;
				foreach($search_query_product_available as $index => $value){
					$total_rows++;
				}
				$search_query_product_available = array_slice($search_query_product_available,$offset,$limit);
				unset($query_product_available);
				$query_product_available = $search_query_product_available;
				$data_product 			 = $query_product_available;
				

			} elseif(!empty($query_product_available)) {
				$total_rows = 0;
				foreach($query_product_available as $index => $value){
					$total_rows++;
				}
				$query_product_available = array_slice($query_product_available,$offset,$limit);
				$data_product 			 = $query_product_available;

			} else {
				$query_product_available = array();
				$data_product 			 = array();
				$total_rows 			 = 0;
			}
		} else {
			$data_product = $database;
		}				

		$categories_db = $this->front_model->get_categories_by_flag();

		$template .= '<form class="form-search-product" style="margin-top: 50px;">';

		$template .= '<div class="form-group form-advanced-search d-none d-lg-block">';
		$keywords_show = '';
		if(isset($keyword['k']) && !empty($keyword['k'])){
			$keywords_show = html_entity_decode($keyword['k']);
			$keywords_show = str_replace("\\","",$keyword['k']);
			$keywords_show = str_replace("||","_",$keyword['k']);
		}

		//Search input value (OLD)
		/*
		$template .= '<input type="text" value="'.$keywords_show.'" name="k" placeholder="Keyword" class="form-control border-with-color blue-border">';
		*/
		//Search input value (NEW)
		$template .= 'Search value for <strong>'.$keywords_show.'</strong>';
		$template .= '<input type="hidden" value="'.$keywords_show.'" name="k">';
		$template .= '</div>';



		$template .= '<div class="form-group form-advanced-search">';

		$template .= '<select style="width:100%;" name="size[]" multiple="multiple" class="size-select2 form-control border-with-color blue-border">';
		if(isset($categories_db['category_size']) && !empty($categories_db['category_size'])){
			foreach($categories_db['category_size'] as $index => $value){
				$selected = '';
				if(isset($get_selected['size']) && in_array($value['category_slug'],$get_selected['size'])){
					$selected = 'selected';
				}
				$template .= '<option '.$selected.' value="'.$value['category_slug'].'">'.ucfirst($value['category_name']).'</option>';
			}
		}
		$template .= '</select>';

		$template .= '</div>';




		$template .= '<div class="form-group form-advanced-search">';

		$template .= '<select style="width:100%;" name="gender[]" multiple="multiple" class="gender-select2 form-control border-with-color blue-border">';

		if(isset($categories_db['category_gender']) && !empty($categories_db['category_gender'])){
			foreach($categories_db['category_gender'] as $index => $value){
				$selected = '';
				if(isset($get_selected['gender']) && in_array($value['category_slug'],$get_selected['gender'])){
					$selected = 'selected';
				}
				$template .= '<option '.$selected.' value="'.$value['category_slug'].'">'.ucfirst($value['category_name']).'</option>';
			}
		}

		$template .= '</select>';

		$template .= '</div>';




		$template .= '<div class="form-group form-advanced-search">';

		$template .= '<select style="width:100%;" name="store_location[]" multiple="multiple" class="store-select2 form-control border-with-color blue-border">';

		if(isset($categories_db['category_store']) && !empty($categories_db['category_store'])){
			foreach($categories_db['category_store'] as $index => $value){
				$selected = '';
				if(isset($get_selected['store_location']) && in_array($value['category_slug'],$get_selected['store_location'])){
					$selected = 'selected';
				}
				$template .= '<option '.$selected.' value="'.$value['category_slug'].'">'.ucfirst($value['category_name']).'</option>';
			}
		}

		$template .= '</select>';

		$template .= '</div>';

		$template .= '<div class="form-group form-advanced-search date start" style="width: 49.8%;">';

		$template .= '<input readonly="true" value="'.$get_date_start.'" placeholder="Available Start Date" autocomplete="off" type="text" name="start" class="border-with-color blue-border datepicker-search-start form-control">';

		$template .= '</div>';


		$template .= '<div class="form-group form-advanced-search date end" style="width: 49.8%;">';

		$template .= '<input readonly="true" value="'.$get_date_end.'" placeholder="Available End Date" autocomplete="off" type="text" name="end" class="border-with-color blue-border datepicker-search-end form-control">';

		$template .= '</div>';



		$template .= '<div class="form-group form-advanced-search" style="display: inline-block; clear: both; width: 100%; text-align: right;">';
		$template .= '<button type="submit" class="btn btn-primary btn-gradient green pull-right mb-3">FILTER</button>';
		$template .= '<button type="button" class="reset-filter btn btn-primary btn-gradient pink pull-right mb-3">RESET FILTER</button>';
		$template .= '</div>';

		$template .= '</form>';

		$template .= '<div class="row">';

		$template .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

		$template .= '<form class="form-filter-product">';

		$template .= '<div class="form-group float-left">';

		$template .= '<label>SORT BY</label>';

		$template .= '<select class="sort-by custom-select sm-4 border-with-color blue-border" id="inlineFormCustomSelect">';

		$template .= '<option value="default" data-name="order_by">Product Name A-Z</option>';

		foreach($sort_show as $k => $v){

			$selected = '';
			if(isset($url) && isset($url['order_by']) && $url['order_by'] == $v['value']){
				$selected = 'selected';
			}
			$template .= '<option '.$selected.' value="'.$v['value'].'" data-name="'.$v['name'].'">'.$v['text'].'</option>';
		}

		$template .= '</select>';

		$template .= '</div>';

		$template .= '<div class="form-group float-right">';

		$template .= '<label>SHOW:</label>';

		$template .= '<select class="show custom-select sm-4 border-with-color blue-border" id="inlineFormCustomSelect">';

		foreach($display_show as $k => $v){

			$selected = '';
			if($limit == $v){
				$selected = 'selected';
			}
			$template .= '<option '.$selected.' value="'.$v.'">'.$v.'</option>';
		}

		$template .= '</select>';

		$template .= '</div>';

		$template .= '</form>';

		$template .= '</div>';

		if(!empty($data_product)){

			foreach($data_product as $index => $value){

				$product_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));

				$image 	 = 'assets/images/no-thumbnail.png';

				if(!empty($product_image)){
					
					if(file_exists($product_image[0]['product_image'])){
						$check_image = true;
					} else {
						$check_image = false;
					}

					if($check_image){

						$image   = $product_image[0]['product_image'];

					} else {

						$image 	 = 'assets/images/no-thumbnail.png';

					}

				}

				$template .= '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">';

				$template .= '<a href="'.base_url('product/').$value['product_slug'].'" class="product-items">';

				$template .= '<div class="image-content">';

				$template .= '<div class="image" style="background-image: url('.$image.');"></div>';

				$template .= '</div>';

				$template .= '<div class="content">';

				$template .= '<p class="title">'.$value['product_nama'].'</p>';

				$template .= '<p class="title">'.$value['product_kode'].'</p>';
				
				$template .= '<p class="price">Rp '.number_format($value['product_hargasewa']).'</p>';

				$product_sizestock = $this->global_model->select_where('product_sizestock',array('product_id' => $value['product_id']));

				if(!empty($product_sizestock)){

					$sizestock = array();
					foreach($product_sizestock as $key => $row){
						$sizestock[] = $row['product_size'];
					}

					if(!empty($sizestock)){
						$sizestock = implode(", ", $sizestock);
						$template .= '<span class="available-size">AVAILABLE SIZE:</span>';
						$template .= '<p class="size">'.$sizestock.'</p>';
					}

				}

				$template .= '</div>';

				$template .= '</a>';

				$template .= '</div>';

			}

		} else {

			$template .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

			$template .= '<h4 style="margin-bottom: 15px; margin-top: 15px;">No products were found matching your selection.</h4>';

			$template .= '</div>';
		}

		$template .= '</div>';

		$result = array(
			'data' 				=> $template,
			'url'  				=> $result_url,
			'page'				=> $page,
			'checked'			=> $checked,
			'classname'			=> $classname,
			'current_classname' => $current_classname,
			'offset'        	=> $offset,
			'query_string' 		=> $base_url_query_string,
			'total_rows'		=> $total_rows,
			'limit'				=> $limit,
			'prod'				=> $tes
			);
		} else {
			$result = array('message' => '');
		}
		echo json_encode($result);
	}

	function like_match($pattern, $subject)
	{
		$pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
		return (bool) preg_match("/^{$pattern}$/i", $subject);
	}
}

?>