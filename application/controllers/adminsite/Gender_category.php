<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gender_category extends CI_Controller{

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
		$data['title']  = 'Gender Category';
		$data['table_data']		= 'category'; // element id table
		$data['ajax_sort_url'] 	= 'adminsite/gender_category/order'; // Controller row order data
		$data['ajax_data_table']= 'adminsite/gender_category/datatables'; //Controller ajax data

		$data['datatables_ajax_data'] = array(

			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])

			);

        //View
		$data['load_view'] = 'adminsite/v_gender_category';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function form(){
		$this->form_validation->set_rules('category_id','Category','trim');
		$this->form_validation->set_rules('category_name','Size Category','trim|required');
		$this->form_validation->set_rules('category_slug','Category Slug','trim|valid_url');
		$this->form_validation->set_rules('status','Category Status','trim');

		if($this->form_validation->run() == false){

			$return = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
				);

		} else {

			$category_id 				= $this->input->post('category_id');
			$category_name 				= $this->input->post('category_name');
			$category_flag				= 'gender';
			$category_status 			= $this->input->post('status');
			$category_slug 				= $this->input->post('category_slug');

			if(empty($category_status)){
				$category_status = 0;
			}

			if(empty($category_id)){

				$insert = array(
					'category_name'				=> $category_name,
					'category_flag'				=> $category_flag,
					'category_status'			=> $category_status,
					'category_created'			=> date('c'),
					'category_modified'			=> NULL
					);

				$result_insert = $this->global_model->insert('category',$insert);
				$id = $this->db->insert_id();
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

				$return = array('flag'=>true, 'process' => 'insert');

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

				$update = array(
					'category_name'				=> $category_name,
					'category_flag'				=> $category_flag,
					'category_slug'				=> $slug,
					'category_status'			=> $category_status,
					'category_modified'			=> date('c')
					);
				$updated = $this->global_model->update('category',$update,array('category_id' => $category_id));

				$query_product_category_detil = $this->global_model->select_where('product_category_detil',array('category_id' => $category_id));
				$prod_id = array();
				if(!empty($query_product_category_detil)){
					foreach($query_product_category_detil as $index => $value){
						$prod_id[] = $value['product_id'];
					}
				}
				if(!empty($prod_id)){
					$filter_gender 		= array();
					foreach($prod_id as $key => $row){
						$get_product_category_detil = $this->backend_model->get_category_by_product($row);
						if(!empty($get_product_category_detil)){
							foreach($get_product_category_detil as $index => $value){
								if(!empty($value['category_slug'])){
									if($value['flag'] == 'gender'){
										$filter_gender[$row][] = $value['category_slug'];
									}
								}
							}
						}
					}
					if(!empty($filter_gender)){
						foreach($filter_gender as $index => $value){
							$got_slug = implode(',',$value);
							$update = array(
								'filter_gender' => $got_slug
								);
							$updated = $this->global_model->update('product',$update,array('product_id' => $index));
						}
					}
				}
				$return = array('flag'=>true, 'process' => 'update');
			}

		}

		echo json_encode($return);

	}

	public function edit_display_data(){
		$id = $this->input->post('category_id');
		$query = $this->global_model->select_where('category',array('category_id' => $id));

		if(!empty($query)){
			$data = array();
			foreach($query as $index => $value){
				$data = array(
					'category_id' 				=> $value['category_id'],
					'category_name'				=> $value['category_name'],
					'category_slug'				=> $value['category_slug'],
					'status'					=> $value['category_status']
					);
			}
			$result = array(
				'data' => $data,
				'flag' => true
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

		if(!empty($prod_id)){
			$filter_gender 		= array();
			foreach($prod_id as $key => $row){
				$get_product_category_detil = $this->backend_model->get_category_by_product($row);
				if(!empty($get_product_category_detil)){
					foreach($get_product_category_detil as $index => $value){
						if(!empty($value['category_slug'])){
							if($value['flag'] == 'gender'){
								$filter_gender[$row][] = $value['category_slug'];
							}
						}
					}
				}
			}
			if(!empty($filter_gender)){
				foreach($filter_gender as $index => $value){
					$got_slug = implode(',',$value);
					$update = array(
						'filter_gender' => $got_slug
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

		$id = $this->input->post('id',true);
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
			}

			if(!empty($prod_id)){
				$filter_gender 		= array();
				foreach($prod_id as $key => $row){
					$get_product_category_detil = $this->backend_model->get_category_by_product($row);
					if(!empty($get_product_category_detil)){
						foreach($get_product_category_detil as $index => $value){
							if(!empty($value['category_slug'])){
								if($value['flag'] == 'gender'){
									$filter_gender[$row][] = $value['category_slug'];
								}
							}
						}
					}
				}
				if(!empty($filter_gender)){
					foreach($filter_gender as $index => $value){
						$got_slug = implode(',',$value);
						$update = array(
							'filter_gender' => $got_slug
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
			'category_flag' => 'gender'
			);

		$order = array(
			'category_order' => 'asc',
			'category_created' => 'desc'
			);

		$query = $this->global_model->getDataWhereOrder('category',$where,$order);

		$data = array();
		if(!empty($query)){
			foreach($query->result() as $index => $value) {

				$status = '';
				if($value->category_status == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}

				$action = '';
				$action = '<a class="btn-edit-action btn-ajax-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value->category_id.'" data-url="'.base_url('adminsite/gender_category/edit_display_data').'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value->category_id.'" data-url="'.base_url('adminsite/gender_category/delete').'">Delete</a>';

				$data[] = array(
					'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->category_id.'"></div>',
					$value->category_name,
					$status,
					$action
					);
			}
		}

		$result = $this->custom_lib->datatables_data($query,$data);
		echo json_encode($result);
	}

}

?>