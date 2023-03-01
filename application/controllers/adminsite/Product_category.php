<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_category extends CI_Controller{

	public function __construct()
	{

		parent::__construct();

		$this->load->model('global_model');

		$this->load->model('backend_model');

		$this->load->library('custom_lib');

		$this->load->model('auth_model');

		$this->load->helper('custom');

		$session_items = false;

		if($this->session->has_userdata($this->config->item('access_panel'))){

			$session_items  = data_sess_panel($this->session->userdata($this->config->item('access_panel')));
		}

		if($session_items != FALSE && is_array($session_items)){

			$id     = '';
			$role   = false;
			$token  = '';

			$id     = $session_items['id_adm'];
			$token  = $session_items['token_adm'];
			$role   = $session_items['role'];

			$already_login = $this->auth_model->is_login($id,$token);

			if($already_login !== false && $role == 'admin'){
				$this->session_items = data_session($this->session->userdata($this->config->item('access_panel')));
				$this->start_session = $this->session_items;
			}

		} else {
			redirect('adminsite','refresh');
		}
	}

	public function index(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		//$data['get'] = $this->backend_model->getData('slideshow',$order);
		$data['title']  = 'Product Category';
		/*$where = array(
			'category_flag' => 'size'
			);

		$order = array(
			'category_order' => 'asc',
			'category_created' => 'desc'
			);

			$data['get'] 			= $this->global_model->getDataWhereOrder('category',$where,$order);*/
		$data['table_data']		= 'category'; // element id table
		$data['ajax_sort_url'] 	= 'adminsite/product_category/order'; // Controller row order data
		$data['ajax_data_table']= 'adminsite/product_category/datatables'; //Controller ajax data
		/*$this->custom_lib->datatables_roworder(TRUE,'#slideshow','panel/slideshow/sort')*/

		$where = array(
			'category_flag' => 'product'
			);

		$order_array = array(
			'category_name' => 'asc',
			);

		$select     = 'category.category_id,category_name';
		$join_array = array(
			'product_category'	=> 'product_category.category_id = category.category_id'
			);

		//PARENT
		$data['parent'] 		= $this->backend_model->get_join('category',$where,$select,$join_array,$order_array);

		$data['datatables_ajax_data'] = array(

			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])

			);

        //View
		$data['load_view'] = 'adminsite/v_product_category';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function form(){
		$this->form_validation->set_rules('category_id','Category','trim');
		$this->form_validation->set_rules('category_name','Category Name','trim|required');
		$this->form_validation->set_rules('category_slug','Category Slug','trim|valid_url');
		$this->form_validation->set_rules('product_category_parent','Parent','trim');
		$this->form_validation->set_rules('product_category_picture','Picture','trim');
		$this->form_validation->set_rules('product_category_background_label','Product Category Background Label','trim');
		$this->form_validation->set_rules('popular_category','Popular Category','trim');
		$this->form_validation->set_rules('popular_category_order','Popular Category Order Number','trim');
		$this->form_validation->set_rules('popular_theme','Popular Theme','trim');
		$this->form_validation->set_rules('popular_theme_order','Popular Theme Order Number','trim');
		$this->form_validation->set_rules('status','Category Status','trim');

		$this->form_validation->set_rules('product_meta_title','Meta Title','trim');
		$this->form_validation->set_rules('product_meta_keyword','Meta Keyword','trim');
		$this->form_validation->set_rules('product_meta_description','Meta Description','trim');

		if($this->form_validation->run() == false){

			$return = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
				);

		} else {

			$category_id 						= $this->input->post('category_id');
			$category_name 						= $this->input->post('category_name');
			$product_category_id				= $this->input->post('product_category_id');
			$product_category_parent 			= $this->input->post('product_category_parent');
			$product_category_picture 			= $this->input->post('product_category_picture');
			$product_category_background_label 	= $this->input->post('product_category_background_label');
			$popular_category 					= $this->input->post('popular_category');
			$popular_category_order 			= $this->input->post('popular_category_order');
			$popular_theme 						= $this->input->post('popular_theme');
			$popular_theme_order 				= $this->input->post('popular_theme_order');

			$product_meta_title	                = $this->input->post('product_meta_title');
			$product_meta_keyword 				= $this->input->post('product_meta_keyword');
			$product_meta_description 			= $this->input->post('product_meta_description');

			$category_flag						= 'product';
			$category_status 					= $this->input->post('status');

			$category_slug 						= $this->input->post('category_slug');

			if(empty($category_status)){
				$category_status 	= 0;
			}

			if(empty($popular_category)){
				$popular_category 	= 0;
			}

			if(empty($popular_theme)){
				$popular_theme 	= 0;
			}

			if(empty($product_category_background_label)){
				$product_category_background_label = '#000';
			}

			$where = array(
				'category_flag' => 'product'
				);

			$order_array = array(
				'category_name' => 'asc',
				);

			$select     = 'category.category_id,category_name';
			$join_array = array(
				'product_category'	=> 'product_category.category_id = category.category_id'
				);

			//PRODUCT CATEGORY PARENT
			$template_category_parent 		= '';
			$query_product_category 		= $this->backend_model->get_join('category',$where,$select,$join_array,$order_array);
			$template_category_parent 		= '<option value="0">Non Parent</option>';
			if(!empty($query_product_category)){
				foreach($query_product_category->result_array() as $index => $value){
					$template_category_parent .= '<option value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
				}
			}

			if(empty($category_id)){
			//CATEGORY
				$insert_category = array(
					'category_name'				=> $category_name,
					'category_parent'			=> $product_category_parent,
					'category_flag'				=> $category_flag,
					'category_status'			=> $category_status,
					'category_created'			=> date('c'),
					'category_modified'			=> NULL
					);

				$result_insert 	= $this->global_model->insert('category',$insert_category);
				$id 			= $this->db->insert_id();
				if(empty($category_slug)){
					$slug 			= $this->backend_model->createSlug('category','category_id',$id,'category_slug',$category_name);
				} else {
					$check_slug = $this->backend_model->check_slug_category($category_slug);
					if($check_slug){
						$slug 		= $this->backend_model->createSlug('category','category_id',$id,'category_slug',$category_slug);
					} else {
						$slug 		= $category_slug;
					}
				}

				$insert_slug = array(
					'category_slug' => $slug
					);
				$this->global_model->update('category',$insert_slug,array('category_id' => $id));
			//PRODUCT CATEGORY
				$insert_product_category = array(
					'category_id'						=> $id,
					'product_category_parent'			=> $product_category_parent,
					'product_category_picture'			=> str_replace(base_url(),'', $product_category_picture),
					'product_category_background_label'	=> $product_category_background_label,
					'popular_category'					=> $popular_category,
					'popular_category_order'			=> $popular_category_order,
					'popular_theme'						=> $popular_theme,
					'popular_theme_order'				=> $popular_theme_order,
					'product_meta_title'				=> $product_meta_title,
					'product_meta_keyword'				=> $product_meta_keyword,
					'product_meta_description' 			=> $product_meta_description
					);
				$result_insert 			= $this->global_model->insert('product_category',$insert_product_category);

				$return = array(
					'flag'		=>	true, 
					'process' 	=>	'insert',
					'upd_form' 	=> 	array(
						'element' 	=> '.product_category_parent',
						'template'	=> $template_category_parent
						)
					);

			} else {

				if(empty($category_slug)){
					$slug 		= $this->backend_model->createSlug('category','category_id',$category_id,'category_slug',$category_name);
				} else {
					$check_slug = $this->backend_model->check_slug_category($category_slug);
					if($check_slug){

						$cat_slug_db = $this->global_model->select_where('category',array('category_id' => $category_id));
						if($cat_slug_db[0]['category_slug'] !== $category_slug){
							if($check_slug){
								$slug 		= $this->backend_model->createSlug('category','category_id',$category_id,'category_slug',$category_slug);
							} else {
								$slug 		= $category_slug;
							}
						} else {
							$slug 		= $this->backend_model->createSlug('category','category_id',$category_id,'category_slug',$category_slug);
						}

					} else {
						$slug 		= $category_slug;
					}
				}
			//CATEGORY
				$category_same_parent = 0;
				if($product_category_parent == $category_id){
					$category_same_parent = $category_id;
					$product_category_parent =  0;
				}
				$update_category = array(
					'category_name'				=> $category_name,
					'category_parent'			=> $product_category_parent,
					'category_same_parent' 		=> $category_same_parent,
					'category_flag'				=> $category_flag,
					'category_status'			=> $category_status,
					'category_slug' 			=> $slug,
					'category_modified'			=> date('c')
					);
				$updated = $this->global_model->update('category',$update_category,array('category_id' => $category_id));

			//CATEGORY PRODUCT
				if(!empty($product_category_id)){
				//PRODUCT CATEGORY
					$update_product_category = array(
						'category_id'						=> $category_id,
						'product_category_parent'			=> $product_category_parent,
						'product_category_picture'			=> str_replace(base_url(),'', $product_category_picture),
						'product_category_background_label'	=> $product_category_background_label,
						'popular_category'					=> $popular_category,
						'popular_category_order'			=> $popular_category_order,
						'popular_theme'						=> $popular_theme,
						'popular_theme_order'				=> $popular_theme_order,
						'product_meta_title'				=> $product_meta_title,
						'product_meta_keyword'				=> $product_meta_keyword,
						'product_meta_description' 			=> $product_meta_description
						);
					$updated = $this->global_model->update('product_category',$update_product_category,array('product_category_id' => $product_category_id));
				}

				if(empty($product_category_id)){
					$insert_product_category = array(
						'category_id'						=> $category_id,
						'product_category_parent'			=> $product_category_parent,
						'product_category_picture'			=> str_replace(base_url(),'', $product_category_picture),
						'product_category_background_label'	=> $product_category_background_label,
						'popular_category'					=> $popular_category,
						'popular_category_order'			=> $popular_category_order,
						'popular_theme'						=> $popular_theme,
						'popular_theme_order'				=> $popular_theme_order,
						'product_meta_title'				=> $product_meta_title,
						'product_meta_keyword'				=> $product_meta_keyword,
						'product_meta_description' 			=> $product_meta_description
						);
					$inserted = $this->global_model->insert('product_category',$insert_product_category);
				}

				$query_product_category_detil = $this->global_model->select_where('product_category_detil',array('category_id' => $category_id));
				$prod_id = array();
				if(!empty($query_product_category_detil)){
					foreach($query_product_category_detil as $index => $value){
						$prod_id[] = $value['product_id'];
					}
				}
				if(!empty($prod_id)){
					$filter_product 		= array();
					foreach($prod_id as $key => $row){
						$get_product_category_detil = $this->backend_model->get_category_by_product($row);
						if(!empty($get_product_category_detil)){
							foreach($get_product_category_detil as $index => $value){
								if(!empty($value['category_slug'])){
									if($value['flag'] == 'product'){
										$filter_product[$row][] = $value['category_slug'];
									}
								}
							}
						}
					}
					if(!empty($filter_product)){
						foreach($filter_product as $index => $value){
							$got_slug = implode(',',$value);
							$update = array(
								'filter_product' => $got_slug
								);
							$updated = $this->global_model->update('product',$update,array('product_id' => $index));
						}
					}
				}

				$return = array(
					'flag'		=>	true, 
					'process' 	=> 'update',
					'post'		=> $_POST,
					'upd_form' 	=> 	array(
						'element' 	=> '.product_category_parent',
						'template'	=> $template_category_parent
						)
					);
			}

		}

		echo json_encode($return);

	}

	public function edit_display_data(){
		$id 		= $this->input->post('category_id');
		$select     = 'category.category_id,category_name,category_status,product_category_id,product_category_parent,product_category_picture,popular_category,popular_category_order,product_category_background_label,popular_theme,popular_theme_order,product_meta_title,product_meta_keyword,product_meta_description,category_same_parent,category_slug';
		$join_array = array(
			'product_category'	=> 'product_category.category_id = category.category_id'
			);
		$query 		= $this->backend_model->get_join_by_id('category',array('category.category_id' => $id),$select,$join_array);

		if(!empty($query)){
			$data = array();
			$product_category_background_label 	= '#000000';
			$product_category_parent 			= 0;
			$popular_category 					= 0;
			$popular_theme 						= 0;
			$product_category_picture 			= '';
			foreach($query->result_array() as $index => $value){
				$product_category_background_label 		= $value['product_category_background_label'];
				$product_category_parent 				= $value['product_category_parent'];
				$product_category_picture				= $value['product_category_picture'];
				$popular_category 						= $value['popular_category'];
				$popular_theme 							= $value['popular_theme'];
				
				if($value['category_same_parent'] == $value['category_id']){
					$product_category_parent = $value['category_same_parent'];
				} else {
					$product_category_parent = $value['product_category_parent'];
				}

				$data = array(
					'product_category_id'				=> $value['product_category_id'],
					'category_id' 						=> $value['category_id'],
					'category_name'						=> $value['category_name'],
					'category_slug'						=> $value['category_slug'],
					'product_category_parent'			=> $product_category_parent,
					'product_category_background_label' => $value['product_category_background_label'],
					'popular_category_order'			=> $value['popular_category_order'],
					'popular_theme_order'				=> $value['popular_theme_order'],
					'status'							=> $value['category_status'],
					'product_meta_title'				=> $value['product_meta_title'],
					'product_meta_keyword'				=> $value['product_meta_keyword'],
					'product_meta_description' 			=> $value['product_meta_description']
					);
			}
			$result = array(
				'data' 			=> $data,
				'select'		=> array(
					'name' 	=> 'product_category_parent',
					'value' => $product_category_parent
					),
				'colorpicker'	=> array(
					'name' 	=> 'product_category_background_label',
					'value' => $product_category_background_label
					),
				'image'			=>
				array(
					array(
						'name' 	=> 'product_category_picture',
						'value' => $product_category_picture
						),
					),
				'checkbox'		=> 
				array(
					array(
						'name' 	=> 'popular_category',
						'value' => $popular_category
						),
					array(
						'name'	=> 'popular_theme',
						'value'	=> $popular_theme
						)
					),
				'flag' 		=> true,
				'query'		=> $query
				);
		} else {
			$result = array(
				'message' 	=> 'Nothing found data.',
				'flag'		=> false
				);
		}

		echo json_encode($result);
	}

	public function delete(){

		$id = $this->input->post('category_id');

		$query_product_category_detil = $this->global_model->select_where('product_category_detil',array('category_id' => $id));
		$prod_id = array();
		if(!empty($query_product_category_detil)){
			foreach($query_product_category_detil as $index => $value){
				$prod_id[] = $value['product_id'];
			}
		}

		$delete = $this->global_model->delete('category',array('category_id' => $id));
		$delete = $this->global_model->delete('product_category',array('category_id' => $id));

		if(!empty($prod_id)){
			$filter_product 		= array();
			foreach($prod_id as $key => $row){
				$get_product_category_detil = $this->backend_model->get_category_by_product($row);
				if(!empty($get_product_category_detil)){
					foreach($get_product_category_detil as $index => $value){
						if(!empty($value['category_slug'])){
							if($value['flag'] == 'product'){
								$filter_product[$row][] = $value['category_slug'];
							}
						}
					}
				}
			}
			if(!empty($filter_product)){
				foreach($filter_product as $index => $value){
					$got_slug = implode(',',$value);
					$update = array(
						'filter_product' => $got_slug
						);
					$updated = $this->global_model->update('product',$update,array('product_id' => $index));
				}
			}
		}

		if($delete){

			$return = array('flag'=>true);

		} else {

			$return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');

		}

		echo json_encode($return);

	}

	public function publish(){

		$id = $this->input->post('id');
		$update = false;

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$data = array(

					'category_status' 		=> 1,
					'category_modified' 	=> date('c')

					);

				$update = $this->global_model->update('category',$data,array('category_id' => $id[$i]));

			}

			$update = true;
		}
		echo json_encode($update);
	}

	public function unpublish(){

		$id = $this->input->post('id');
		$update = false;
		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$data = array(
					'category_status' 		=> 0,
					'category_modified' 	=> date('c')
					);

				$update = $this->global_model->update('category',$data,array('category_id' => $id[$i]));

			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function multiple_delete(){

		$id = $this->input->post('id');
		$update = false;

		if(is_array($id) && !empty($id)){

			$prod_id = array();
			for($i=0; $i<count($id); $i++){

				$query_product_category_detil = $this->global_model->select_where('product_category_detil',array('category_id' => $id[$i]));
				if(!empty($query_product_category_detil)){
					foreach($query_product_category_detil as $index => $value){
						$prod_id[] = $value['product_id'];
					}
				}

				$delete = $this->global_model->delete('category',array('category_id' => $id[$i]));
				$delete = $this->global_model->delete('product_category',array('category_id' => $id[$i]));
			}

			if(!empty($prod_id)){
				$filter_product 		= array();
				foreach($prod_id as $key => $row){
					$get_product_category_detil = $this->backend_model->get_category_by_product($row);
					if(!empty($get_product_category_detil)){
						foreach($get_product_category_detil as $index => $value){
							if(!empty($value['category_slug'])){
								if($value['flag'] == 'product'){
									$filter_product[$row][] = $value['category_slug'];
								}
							}
						}
					}
				}
				if(!empty($filter_product)){
					foreach($filter_product as $index => $value){
						$got_slug = implode(',',$value);
						$update = array(
							'filter_product' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $index));
					}
				}
			}

			$update = true;
		}
		echo json_encode($update);
	}

	public function datatables(){

		$where = array(
			'category_flag' => 'product'
			);

		$order_array = array(
			'category_order' => 'asc',
			'category_created' => 'desc'
			);

		$select     = 'category.category_id,category_name,category_status,product_category_parent,product_category_picture,popular_category,popular_category_order,popular_theme,popular_theme_order,category_same_parent';
		$join_array = array(
			'product_category'	=> 'product_category.category_id = category.category_id'
			);
		$query 		= $this->backend_model->get_join_by_id('category',$where,$select,$join_array,$order_array);				
		$data = array();
		if(!empty($query)){

			foreach($query->result() as $index => $value) {

				$status = '';
				if($value->category_status == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}

				$action 	= '';
				$action = '<a class="btn-edit-action btn-ajax-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value->category_id.'" data-url="'.base_url('adminsite/product_category/edit_display_data').'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value->category_id.'" data-url="'.base_url('adminsite/product_category/delete').'">Delete</a>';

				if(!empty($value->product_category_picture)){
					$image  = '<img class="img-thumbnail" src="'.$value->product_category_picture.'">';
				} else {
					$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
				}

				$product_category_parent = $value->product_category_parent;
				if($value->category_id == $value->category_same_parent){
					$product_category_parent = $value->category_same_parent;
				}
				$query_parent = $this->global_model->select_where('category',array('category_id' => $product_category_parent));

				if(!empty($query_parent)){
					foreach($query_parent as $key => $row){
						if($row['category_id'] == $product_category_parent){
							$data[] = array(
								'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->category_id.'"></div>',
								$image,
								$value->category_name,
								$row['category_name'],
								$status,
								$action
								);
						} else {
							$data[] = array(
								'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->category_id.'"></div>',
								$image,
								$value->category_name,
								'-',
								$status,
								$action
								);
						}
					}
				} else {
					$data[] = array(
						'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->category_id.'"></div>',
						$image,
						$value->category_name,
						'-',
						$status,
						$action
						);
				}
			}

		}

		$result = $this->custom_lib->datatables_data($query,$data);
		echo json_encode($result);

	}

	public function check_children(){

	}

}

?>