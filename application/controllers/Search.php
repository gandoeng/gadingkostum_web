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
		$this->load->helper('custom_helper');
	}

	public function index(){

		$data['title'] 		 		= 'Search - Gading Kostum';
		$data['categories_db'] 		= $this->front_model->get_categories_by_flag();
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

		$limit 			= 48;

		$get_selected   = array();

		if(isset($url) && isset($url['show'])){
			// $limit 		= $url['show']; 
			$limit 		= 48;
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
		$database   = $this->front_model->re_search_product($query,$limit,$offset,$order_by,$format_date_start,$format_date_end);
		$search_database_by_keyword = array();
		$query_product_available    = array();
		$product_unavailable 		= array();
		$cek_query  = array();
		$prod_id 	= array();
		$total_rows = $this->front_model->re_count_search_product($query);
		
		if(!empty($database) && !empty($get_date_start) && !empty($get_date_end)){
			foreach($database as $index => $value){
				$prod_id[] = $value['product_id'];
			}
		}
		
		$data_product 			= array();
		$query_product_rental 	= array();
		$product_unavailable    = array();
		$product                = array();
		$product_all_stock 		= array();
		$product_already_return = array();
		$count_sizestock 		= array();

		$tes = array();
		if(!empty($get_date_start) && !empty($get_date_end) && !empty($prod_id)){
			$current_date 			= date('Y-m-d');
			$str_current_date 		= strtotime($current_date);
			$day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

			$q_rental_order_id 		= array();
			$query_product_rental   = $this->front_model->re_search_get_rental($prod_id);
			if(!empty($query_product_rental)){
				foreach($query_product_rental as $index => $value){
					$q_rental_order_id[]    = $value['rental_order_id'];
				}
			}

			$stockProduct 				= $this->front_model->re_search_sum_product($prod_id);

			if(!empty($query_product_rental)){
				$no = 0;
				$rental_in_return       = array();

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
									if (array_key_exists('product_size', $row)) {
										if(!in_array($row['product_size'],$get_product_size)){
											unset($query_product_rental[$key]);
										}
									}
								}
								$query_product_rental = array_values($query_product_rental);
							}
						}
					}
				}
			
				$sum_qty = 0;
				$stock_product 			= array();
				foreach($query_product_rental as $index => $value){
					if((int)$day_after_return && !empty($value['rental_return_date'])){
						$rental_in_return[]  = $value['rental_order_id'];
					}
					$value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));

					$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]  = $value;
					$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty']  = '';
				}
				
				foreach($stockProduct as $index => $value){
					@$stock_product[$value['product_id']] += $value['product_stock'];
					$product_all_stock[$value['product_id']][$value['product_sizestock_id']] = $value['product_stock'];
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
					$str_late_date_from_current_date = false;
					if($str_current_date >= $value_late_date && $value_late_date <= $str_current_date){
						$str_late_date_from_current_date = strtotime(date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day')));
					}
					$return_date 			= '';

					if(!in_array($value['rental_order_id'],$rental_in_return)){
						if(!empty($value['rental_order_id'])) {
							if($value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end &&
								$value_late_date >= $value_format_date_start && $value_late_date <= $value_format_date_end || 
								$value_rental_start_date <= $value_format_date_start && $value_late_date >= $value_format_date_start && 
								$value_late_date >= $value_format_date_end || $value_rental_start_date >= $value_format_date_start && 
								$value_rental_start_date <= $value_format_date_end || $value_late_date >= $value_format_date_start && 
								$value_late_date <= $value_format_date_end){

								$quantity  = (int) $value['rental_product_qty'];
								if(isset($count[$value['rental_product_sizestock_id']][$value['rental_order_id']])){
									$count[$value['rental_product_sizestock_id']][$value['rental_order_id']]+=$quantity;
								} else {
									$count[$value['rental_product_sizestock_id']][$value['rental_order_id']]=$quantity;
								}

								$sum_qty1 = 0;
								foreach($count as $key => $row){
									foreach($row as $k => $v){
										if($k == $value['rental_product_sizestock_id']){
											$sum_qty1 = $v;
										}
										$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
									}
								}
							} elseif(!empty($str_late_date_from_current_date) && 
									($str_late_date_from_current_date >= $value_format_date_start) && ($str_late_date_from_current_date <= $value_format_date_end) ||
									($str_current_date >= $value_format_date_start) && ($str_current_date <= $value_format_date_end)) {
							
							$quantity  = (int) $value['rental_product_qty'];
							if(isset($count[$value['rental_product_sizestock_id']][$value['rental_order_id']])){
								$count[$value['rental_product_sizestock_id']][$value['rental_order_id']]+=$quantity;
							} else {
								$count[$value['rental_product_sizestock_id']][$value['rental_order_id']]=$quantity;
							}

							$sum_qty2 = 0;
							foreach($count as $key => $row){
								foreach($row as $k => $v){
									if($k == $value['rental_product_sizestock_id']){
										$sum_qty2 = $v;
									}
									$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
								}
							}
						} else {
							$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
						}

					} else {
						$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
					}

					} elseif(in_array($value['rental_order_id'],$rental_in_return)) {

						if(!empty($value['rental_return_date'])){
							$return_date  			= date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_return_date'])));
							$value_return_date 		= strtotime($return_date);
						}

						if(!empty($value['rental_order_id'])) {

							if($value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end ||
							$value_return_date >= $value_format_date_start && $value_return_date <= $value_late_date ||
							$value_return_date >= $value_format_date_start){
							
							$quantity  = (int) $value['rental_product_qty'];
							if(isset($count[$value['rental_product_sizestock_id']][$value['rental_order_id']])){
								$count[$value['rental_product_sizestock_id']][$value['rental_order_id']]+=$quantity;
							} else {
								$count[$value['rental_product_sizestock_id']][$value['rental_order_id']]=$quantity;
							}

							$sum_qty3 = 0;
							foreach($count as $key => $row){

								foreach($row as $k => $v){

									if($k == $value['rental_product_sizestock_id']){
										$sum_qty3 = $v;
									}
									$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
									$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['status'] = 'return';
								}
							}

						} else {
							$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;

						}

						} else {
							$product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
						}

					}
				}

				$count_sizestock 	= array();
				$count_stock 		= array();
				$count_rental 		= array();
				$sums 		 		= array();

				if(!empty($product)){
					foreach($product as $index => $value){
						foreach($value as $key => $row){
							@$count_sizestock[$row['product_id']][$row['rental_product_sizestock_id']]+=$row['rental_qty'];
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
												// julian edit
												// $sum_rental = $val - 1;
												$total_stock[$index][$i] = $sum_rental;
											} else {
												//29 Maret 2019
												$sum_rental = $val; // minus sum condition each sizestock or not in date periode
												$total_stock[$index][$i] = $sum_rental;
											}
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

				// define unavailable product
				// if(!empty($count_stock)){
				// 	foreach($count_stock as $key => $row){
				// 		if($row < 1){
				// 			$product_unavailable[] = $key;
				// 		}
				// 	}
				// }

				// $product_sizestock_id = array();

				// Julian refactored
				$tmpquerydatestart = strtotime($get_date_start);
				$tmpquerydateend = strtotime($get_date_end);
				$sum_products = [];

				if (!empty($product)) {
					foreach ($product as $pr) {
						
						// Julian check by date query if same sum set to true
						foreach ($pr as $key => $row) {
							$value_rental_start_date = $row['rental_start_date'];
							$value_rental_end_date = $row['rental_end_date'];
							$value_rental_qty = (int) $row['rental_product_qty'];
							$strrentaldate = strtotime($value_rental_start_date);
							$endrentaldate = strtotime($value_rental_end_date);
							//get range date
							$range_date = getBetweenDates(strtotime($value_rental_start_date), strtotime($value_rental_end_date));
							// print_r($range_date);
							// echo "<br>";
							// die();
							// $tmpstockproduct = $product_all_stock[$row['product_id']][$row['rental_product_sizestock_id']];
							if ((($endrentaldate >= $tmpquerydatestart) && ($endrentaldate <= $tmpquerydateend ) && ($strrentaldate >= $tmpquerydatestart) && ($strrentaldate <= $tmpquerydateend)) || (in_array(date('Y-m-d', $tmpquerydatestart), $range_date) || in_array(date('Y-m-d', $tmpquerydateend), $range_date))) {
								// array_push($product_sizestock_id, $row['rental_product_sizestock_id']);
								// $sum = $value_rental_qty;
								$sum_products[$row['product_id']][$row['rental_product_sizestock_id']][] = $value_rental_qty;
							}

							// $max_stok_in_date[$row['product_id']][$row['rental_product_sizestock_id']]['max'][] = 0;
							// if($sum > 0){

							// }
						}
					}

					// echo '<pre>';print_r($sum_products);echo '</pre>';
					// die();
				}

				// $product_rental_on_date = [];
				// foreach (array_unique($product_sizestock_id) as $value) {
					// $query_product_rental_on_date = $this->front_model->get_rentaled_on_date_by_size_id($value, $get_date_start, $get_date_end);
					// echo '<pre>';print_r($query_product_rental_on_date);echo '</pre>';
					
					// if (!empty($query_product_rental_on_date)) {
						// $product_rental_on_date[$query_product_rental_on_date['rental_product_sizestock_id']] += $query_product_rental_on_date['rental_product_qty'];
					// 	foreach ($query_product_rental_on_date as $query_product_row) {
					// 		echo '<pre>';print_r($query_product_row);echo '</pre>';
					// 	}
					// }
				// }

				// echo '<pre>';print_r($product_rental_on_date);echo '</pre>'; 
				// die();
			}

			unset($database);
			$total_rows 		     = 0;
			$total_rows 			 = $this->front_model->re_count_search_product_available($query,$product_unavailable);
			$data_product 			 = $this->front_model->re_search_product_available($query,$limit,$offset,$order_by,$keyword,$product_unavailable);

			// $data_product = $database;
		} else {
			$data_product = $database;
		}

		$getImage 	  = array();
		$getSizestock = array();
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
		$template .= '<div class="form-group float-right" style="display: none">';
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
			$no = 0;
			$getProductID = array();
			foreach($data_product as $index => $value){
				$getProductID[] = $value['product_id'];
			    $prouductFromDataProduct[$value['product_id']] = json_encode($value); 	
			}
			if(!empty($getProductID)){
				$getImage 		= $this->front_model->getImageProduct($getProductID);
				$getSizestock 	= $this->front_model->getProductSizestock($getProductID);
			}
			foreach($data_product as $index => $value){
				$image 	 	= 'assets/images/no-thumbnail.png';
				$max = false;
				$sizestock 	= array();
				if(!empty($getImage)){
					foreach($getImage as $key => $row){
						if($row['product_id'] == $value['product_id'] && file_exists($row['product_image'])){
							$image = $row['product_image'];
						} 
					}
				}
				if(!empty($getSizestock)){
					foreach($getSizestock as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$tmp_product_id = $row['product_id'];
							$tmp_styles = '';
							$tmp_product_stocksize_id = $row['product_sizestock_id'];
							$count_rented = 0;
							foreach ($sum_products as $product_id_in_sum => $sizestock_id_in_sum) {
								if ($product_id_in_sum == $tmp_product_id) {
									foreach ($sizestock_id_in_sum as $key_sizstock => $value_sizestock) {
										// echo $key_sizstock .'=>'.$value_sizestock.'<br>';
										// die();
										if ($tmp_product_stocksize_id == $key_sizstock) {
											foreach ($value_sizestock as $key_value => $value_rented) {
												$count_rented += $value_rented;
											}
										}
									}
								}
							}

							// echo $tmp_product_stocksize_id .'=>'.$count_rented. '|' . $row['product_stock']. '<br>';

							if ($count_rented >= $row['product_stock']) {
								$max = true;
							}else{
								$max = false;
							}

							if ($max == true) {
								$tmp_styles = 'color:red; text-decoration:line-through;';
							}

							// if(!empty($product_rental_on_date)){
							// 	foreach ($product_rental_on_date as $p_key => $p_value) {
							// 		if($p_key == $tmp_product_stocksize_id){
							// 			if($p_value >= $row['product_stock']){
							// 				$tmp_styles = 'color:red; text-decoration:line-through;';
							// 			}
							// 		}
							// 	}
							// }
							// red the size stock
							// if(array_key_exists($tmp_product_id, $max_stok_in_date) && array_key_exists($tmp_product_stocksize_id, $max_stok_in_date[$tmp_product_id])){
							// 	if (in_array(1, $max_stok_in_date[$tmp_product_id][$tmp_product_stocksize_id]['max'])) {
							// 		$tmp_styles = 'color:red; text-decoration:line-through;';
							// 	}
							// }

							$display_size_stock = '<i  style="font-style:normal; '.$tmp_styles.'">'.$row['product_size'].'</i>';
							$sizestock[] = $display_size_stock;
						} 
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
				if(!empty($sizestock)){
					$sizestock = implode(", ", $sizestock);
					$template .= '<span class="available-size">AVAILABLE SIZE:</span>';
					$template .= '<p class="size">'.$sizestock.'</p>';
				}
				$template .= '</div>';
				$template .= '</a>';
				$template .= '</div>';
				$no++;
			}

		} else {
			$template .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
			$template .= '<h4 style="margin-bottom: 15px; margin-top: 15px;">No products were found matching your selection.</h4>';
			$template .= '</div>';
		}
		$template .= '</div>';
		$result = array(
			'getpost'			=> $getpost,
			'query'				=> $query,
			'keyword'			=> $keyword,
			'data' 				=> $template,
			'url'  				=> $result_url,
			'page'				=> $page,
			'offset'        	=> $offset,
			'query_string' 		=> $base_url_query_string,
			'total_rows'		=> $total_rows,
			'limit'				=> $limit
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