<?php



defined('BASEPATH') OR exit('No direct script access allowed');



class Categories extends CI_Controller{



	public function __construct()



	{



		parent::__construct();



		$this->load->model('front_model');

		$this->load->model('global_model');

		$this->load->library('pagination');

		$this->load->library('categories_menu_lib');

	}



	public function seo($seg1 = '',$seg2 = ''){
		$seg1  = $this->uri->segment(1);
		$seg2  = $this->uri->segment(2);
		if(!empty($seg2)){
			$seg2 = str_replace('-','',$seg2);
		}
		$query = $this->front_model->get_category_for_seo($seg2);
		if(!empty($query) && $seg1 == 'product-category'){
			header('Location: '.base_url('categories').'?'.$query[0]['category_flag'].'='.$query[0]['category_slug']);
			die();
		} else {
			header('Location: '.base_url('categories'));
			die();
		}
	}

	public function index($uri = ''){
		
		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');

		$data['title'] = 'Categories Archives - Gading Kostum';

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

		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');

		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');

		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');

		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');

		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');
		
		$uri 			  			= $this->uri->segment(2);

		$category 				= $this->front_model->get_nested_categories();
		
		$GET_url 				= $this->input->get();

		$get 					= array();

		if(isset($GET_url) && is_array($GET_url)){

			foreach($GET_url as $index => $value){

				$explode 	= explode("%7C%7C",urlencode(trim($value)));

				foreach($explode as $key => $values){

					$get[] 	= $values; 

				}

			}

		}

		$temp_val 		= array();

		$cat_slug 	    = array();

		$cat_flag 		= array_column($category, 'category_flag');

		$categories		= array();

		$gender 		= array();

		$size			= array();

		$store_location	= array();

		function compare($a, $b)
		{
			return ($a['category_name'] > $b['category_name']);
		}

		if(!empty($category)){

			foreach($category as $index => $value){

				$category_flag = $value['category_flag'];

				switch ($category_flag) {

					case "product":
					$categories[]		= $value;
					break;

					case "gender":
					$gender[]			= $value;
					
					break;

					case "size":
					$size[] 			= $value;
					
					break;

					case "store_location":
					$store_location[] 	= $value;
					
					break;

				}

			}

		}

		$data['menu'] 					= array();
		$get_slug_query_string 			= array();
		$get_slug_product 				= array();
		$get_slug_size 					= array();
		$get_slug_gender				= array();
		$get_slug_store_location	    = array();

		if(!empty($GET_url)){
			foreach($GET_url as $index => $value){
				$get_slug_query_string[$index] = $value;
			}

			if(isset($get_slug_query_string) && !empty($get_slug_query_string)){
				foreach($get_slug_query_string as $index => $value){
					$flag = $index;
					switch ($flag) {
						case 'product':
						$get_slug_product = explode('||',$value);
						break;
						case 'size':
						$get_slug_size = explode('||',$value);
						break;
						case 'gender':
						$get_slug_gender = explode('||',$value);
						break;
						case 'store_location':
						$get_slug_store_location = explode('||',$value);
						break;
					}
				}
			}
		}
		$data['menu']['categories']		= $this->fetch_menu('categories',$categories,$get_slug_product);

		$data['menu']['gender']			= $this->fetch_menu('gender',$gender,$get_slug_gender);

		$data['menu']['size']			= $this->fetch_menu('size',$size,$get_slug_size);

		$data['menu']['store_location']	= $this->fetch_menu('store-location',$store_location,$get_slug_store_location);

		$data['start_date'] 			= '';

		$data['end_date']				= '';

		if(isset($get_slug_query_string['start']) && !empty($get_slug_query_string['start'])){
			$data['start_date']			= date('j F Y',strtotime($get_slug_query_string['start']));
		}
		if(isset($get_slug_query_string['end']) && !empty($get_slug_query_string['end'])){
			$data['end_date']			= date('j F Y',strtotime($get_slug_query_string['end']));
		}

		$this->load->view('v_categories',$data);

	}



	function fetch_menu($classname = '',$data,$category_slug = ''){

		$template = '';

		if(is_array($data) && !empty($data)){

			$class_index = 1;

			foreach($data as $index => $menu){

				$category_flag = $menu['category_flag'];

				$cat_filter    = $menu['category_flag'];

				//if($category_flag != 'product'){

				//$category_flag = $menu['category_flag'].'[]';
				$category_flag = $menu['category_flag'];
				//}

				$get_url = '';

				$checked = '';	
				$active  = '';
				$icon    = 'plus';

				if(isset($menu['get_url'])){

					$get_url = $menu['get_url'];

				} else {

					$get_url = $menu['category_slug'];

				}

				if(!empty($category_slug)){

					foreach($category_slug as $index => $value){
						if($value == $menu['category_slug']){
							$checked = 'checked';
							$active  = 'active';
							$icon    = 'minus';
						}
					}

				}

				if(!empty($menu['sub'])){

					//22 Januari 2019
					// ganti dulu dibawahnya
					// command start
					//$template .= '<div class="title '.$active.' dropdown-children">';
					// command end

					$template .= '<div id="dropdown-'.$menu['category_slug'].'" class="title '.$active.' dropdown-children">';

					//if($cat_filter != 'product'){

					//22 Januari 2019
					// ganti dulu dibawahnya
					// command start
					/*$template .= '<a href="'.base_url('categories?').$menu['category_slug'].'='.$get_url.'"><label class="radio-inline"><input data-index="'.$classname.'-menu-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="categories categories-check '.$classname.'-menu-'.$class_index.'" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label></a><i class="arrow right icon"></i></div>';*/
					// command end

					$template .= '<label class="radio-inline"><input data-index="'.$classname.'-menu-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="categories categories-check '.$classname.'-menu-'.$class_index.'" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label><i class="'.$icon.' square icon"></i></div>';

					//} else {

						//$template .= '<a href="'.base_url('categories/').$menu['category_slug'].$get_url.'">'.strtoupper($menu['category_name']).'</a><i class="arrow right icon"></i></div>';

					//}

				} else{

					$template .= '<div class="content active primary-view">';

					//if($cat_filter != 'product'){

					//22 Januari 2019
					// ganti dulu dibawahnya
					// command start
					/*$template .= '<a href="'.base_url('categories?').$menu['category_slug'].'='.$get_url.'"> <label class="radio-inline"><input data-index="'.$classname.'-menu-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="categories categories-check '.$classname.'-menu-'.$class_index.'" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label></a>';*/
					//command end

					$template .= '<label class="radio-inline"><input data-index="'.$classname.'-menu-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="categories categories-check '.$classname.'-menu-'.$class_index.'" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label>';

					//} else {

						//$template .= '<a href="'.base_url('categories/').$menu['category_slug'].'">'.strtoupper($menu['category_name']).'</a>';

					//}

					$template .= '</div>';	

				}



				if(!empty($menu['sub'])){


					//22 Januari 2019
					// ganti dulu dibawahnya
					// command start

					/*$template .= '<div class="content children-view '.$active.'">';

					$template .= '<div class="accordion transition visible" style="display: block !important;">';

					$template .= $this->fetch_sub_menu($menu['sub'],$category_slug);

					$template .= '</div>';

					$template .= '</div>';*/
					// command end
					$template .= '<div class="dropdown-'.$menu['category_slug'].' content children-view '.$active.'">';

					$template .= '<div class="accordion transition visible" style="display: block !important;">';

					$template .= $this->fetch_sub_menu($menu['sub'],$category_slug,$menu['category_slug']);

					$template .= '</div>';

					$template .= '</div>';

				}

				$class_index++;

			}



		}

		return $template;

	}

	function searchArrayKeyVal($sKey, $id, $array) {

		foreach ($array as $key => $val) {

			if ($val[$sKey] == $id) {

				return $key;

			}

		}

		return false;

	}

	function fetch_sub_menu($sub_menu,$category_slug = '',$category_parent = ''){



		$template = '';

		if(is_array($sub_menu) && !empty($sub_menu)){

			$class_index = 1;
			foreach($sub_menu as $index => $menu){



				$category_flag = $menu['category_flag'];

				$cat_filter    = $menu['category_flag'];

				//if($category_flag != 'product'){

				//$category_flag = $menu['category_flag'].'[]';

				//}

				$get_url = '';

				$checked = '';
				$active  = '';
				$icon 	 = 'plus';

				if(isset($menu['get_url'])){

					$get_url = $menu['get_url'];

				} else {

					$get_url = $menu['category_slug'];

				}



				if(!empty($category_slug)){

					foreach($category_slug as $index => $value){
						if($value == $menu['category_slug']){
							$checked = 'checked';
							$active  = 'active';
							$icon 	 = 'minus';
						}
					}

				}

				if(!empty($menu['sub'])){

					$template .= '<div id="dropdown-'.$menu['category_slug'].'" class="title dropdown-children '.$active.'">';

					//if($cat_filter != 'product'){

					//22 Januari 2019
					// ganti dulu dibawahnya
					// command start
					/*$template .= '<a href="'.base_url('categories?').$menu['category_slug'].'='.$get_url.'"><label class="radio-inline"><input data-index="category-submenu-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="categories categories-check category-submenu-'.$class_index.'" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label></a><i class="arrow right icon"></i></div>';*/
					// command end

					$template .= '<label class="radio-inline"><input data-index="category-submenu-'.$category_parent.'-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="category-submenu-'.$category_parent.'-'.$class_index.'" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label><i class="'.$icon.' square icon"></i></div>';

					//} else {

						//$template .= '<a href="'.base_url('categories/').$menu['category_slug'].$get_url.'">'.strtoupper($menu['category_name']).'</a><i class="arrow right icon"></i></div>';

					//}

				} else{

					$template .= '<div class="content primary-view">';

					//if($cat_filter != 'product'){

					//22 Januari 2019
					// ganti dulu dibawahnya
					// command start
					/*$template .= '<a href="'.base_url('categories?').$menu['category_slug'].'='.$get_url.'"> <label class="radio-inline"><input data-index="category-submenu-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="category-submenu-'.$class_index.' categories categories-check" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label></a>';*/
					// command end

					$template .= '<label class="radio-inline"><input data-index="category-submenu-'.$category_parent.'-'.$class_index.'" data-categories="'.$menu['category_flag'].'" data-slug="'.$menu['category_slug'].'" '.$checked.' class="category-submenu-'.$category_parent.'-'.$class_index.' categories categories-check" type="checkbox" name="'.$category_flag.'" value="'.$menu['category_slug'].'"> '.strtoupper($menu['category_name']).'</label>';

					//} else {

						//$template .= '<a href="'.base_url('categories/').$menu['category_slug'].'">'.strtoupper($menu['category_name']).'</a>';

					//}

					$template .= '</div>';	

				}



				if(!empty($menu['sub'])){



					$template .= '<div class="dropdown-'.$menu['category_slug'].' content children-view '.$active.'">';

					$template .= '<div class="accordion transition visible" style="display: block !important;">';

					$template .= $this->fetch_sub_menu($menu['sub']);

					$template .= '</div>';

					$template .= '</div>';



				}


				$class_index++;
			}

		}



		return $template;

	}

	function getparamurl($post = '',$page = ''){

		$change_url 	= false;
		$result_url 	= '';
		$query      	= array();
		$order_by    	= '';
		$order_date     = '';
		$show_display 	= '';
		$keyword 		= '';

		$get_selected 					= array();
		$get_selected['product']		= array();
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

			if(isset($url['product']) && !empty($url['product']) && is_string($url['product'])){
				$get_selected['product'] = explode('||',$url['product']);
				$url['product'] 	= explode('||',$url['product']);
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

				if($flag == 'product' || $flag == 'size' || $flag == 'gender' || $flag == 'store_location'){

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

									if(!empty($slug)){
										array_push($url[$index],$slug);
									}

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

					}
				}

				if(is_array($url) && !empty($url)){

					$temp_show_display = array();
					foreach($url as $index => $value){
						if(is_array($value)){
							$url[$index] 	= array_values($url[$index]);
							$query[$index] 	= array_values($url[$index]);
							$url[$index] 	= implode('||',$url[$index]);
						}

						if($index == 'show'){
							$url[$index]	= $url[$index];
						}

						if($index == 'order_by'){
							$url[$index]			= $url[$index];
							$order_by[$index] 	= $url[$index];
						}
					}

					$result_url  = http_build_query($url);
					$result_url  = urldecode($result_url);
				}

			} else {
			// jika tidak ada isi dari variable url

				if($flag == 'product' || $flag == 'size' || $flag == 'gender' || $flag == 'store_location'){

				//21 Januari 2019
				//cek slug and get url exist or not
					if(!empty($slug) && !empty($url)){

						$url[$flag][0] 		= $slug;
						$query[$flag][0] 	= $slug;
					//$url[$flag] 		= implode('||',$url[$flag]);

						$result_url  		= http_build_query($url);
						$result_url  		= urldecode($result_url);

					} elseif(!empty($slug)){

						$url[$flag][0] 		= $slug;
						$query[$flag][0] 	= $slug;
						$url[$flag] 		= implode('||',$url[$flag]);

						$result_url  		= http_build_query($url);
						$result_url  		= urldecode($result_url);
					}
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

	function filteringselect($page = 1){
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
			$keyword		= '';
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

			if(isset($url['product']) && !empty($url['product']) && is_string($url['product'])){
				$url['product'] 	= explode('||',$url['product']);
			}

			if(isset($url['gender']) && !empty($url['gender']) && is_string($url['gender'])){
				$url['gender'] 	= explode('||',$url['gender']);
			}

			if(isset($url['size']) && !empty($url['size']) && is_string($url['size'])){
				$url['size'] 	= explode('||',$url['size']);
			}

			if(isset($url['store_location']) && !empty($url['store_location']) && is_string($url['store_location'])){
				$url['store_location'] 	= explode('||',$url['store_location']);
			}

			$getparamurl    = $this->getparamurl($getpost,$page);
			$result_url 	= $getparamurl['result_url'];
			$query 			= $getparamurl['query'];
			$order_by    	= $getparamurl['order_by'];
			$order_date     = $getparamurl['order_date'];
			$show_display 	= $getparamurl['show_display'];
			$keyword 		= $getparamurl['keyword'];

			$get_selected 	= $getparamurl['get_selected'];

		// configure template
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

			$offset 	= $config_page*$limit;
			$total_rows = 0;

			if(isset($order_by) && isset($order_by['order_by']) && $order_by['order_by'] == 'popularity'){
				$database   = $this->front_model->search_product_popularity($query,$limit,$offset,$order_by,'',$format_date_start,$format_date_end);
				if(!empty($database)){
					foreach($database as $index => $value){
						$total_rows++;
					}
					$database = array_slice($database,$offset,$limit);
				}
			} else {
				$database 	= $this->front_model->sidebar_product($query,$limit,$offset,$order_by,$format_date_start,$format_date_end);
				if(!empty($database)){
					foreach($database as $index => $value){
						$total_rows++;
					}
					$database = array_slice($database,$offset,$limit);
				}
			}

			$data_product = array();

			if(!empty($get_date_start) && !empty($get_date_end)){

				$get_all_stock 			= $this->front_model->search_sum_product($query);

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

			}

			if(isset($order_by) && isset($order_by['order_by']) && $order_by['order_by'] == 'popularity'){
				$query_product_available = $this->front_model->search_product_popularity_available($query,$limit,$offset,$order_by,$keyword,$product_unavailable);
			} else {
				$query_product_available = $this->front_model->search_product_available($query,$limit,$offset,$order_by,$keyword,$product_unavailable);
			}

			if(!empty($query_product_available)){
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
			if(!empty($database)){
				$data_product = $database;
			}
		}

		if(!empty($data_product)){

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

		$result = array(
			'start_date' 	=> $get_date_start,
			'end_date'		=> $get_date_end,
			'data' 			=> $template,
			'url'  			=> $result_url,
			'page'			=> $page,
			'checked'		=> $checked,
			'classname'		=> $classname,
			'current_classname' => $current_classname,
			'offset'        => $offset,
			'query_string' => $base_url_query_string,
			'total_rows'	=> $total_rows,
			'limit'			=> $limit
			);
	} else {
		$result = array('message' => '');
	}
	echo json_encode($result);

}


}


?>