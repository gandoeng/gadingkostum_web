<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_sort extends CI_Controller{

	public function __construct()
	{

		parent::__construct();

		$this->load->model('global_model');

		$this->load->model('backend_model');

		$this->load->library('custom_lib');

		$this->load->model('auth_model');

		$this->load->helper('custom');
		
		$this->load->library('categories_menu_lib');

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

	public function a(){

			//$get = $this->global_model->select('category');
			/*$get = $this->backend_model->update_categories();

			$insert = false;
			$prod_id = array();
			foreach($get as $index => $value){
				$update = array(
						'filter_product' => $value['filter_product'].',themes'
				);
				$this->global_model->update('product',$update,$value['product_id']);

				$insert = array(
						'product_id' 	=> $value['product_id'],
						'category_id' 	=> 50,
						'flag'			=> 'product'
					);

				$this->global_model->insert('product_category_detil',$insert);
			}

			if($insert){
				echo 'sukses';
			}*/
	}

	public function index(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Category Sort';
		$where = array(
			'category_flag' => 'product'
			);

		$order = array(
			'category_order' => 'asc'
			);

		$data['get_product'] = $this->global_model->getDataWhereOrder('category',$where,$order)->result_array();

		$where = array(
			'category_flag' => 'gender'
			);

		$order = array(
			'category_order' => 'asc'
			);

		$data['get_gender'] = $this->global_model->getDataWhereOrder('category',$where,$order)->result_array();

		$where = array(
			'category_flag' => 'size'
			);

		$order = array(
			'category_order' => 'asc'
			);

		$data['get_size'] = $this->global_model->getDataWhereOrder('category',$where,$order)->result_array();

		$where = array(
			'category_flag' => 'store_location'
			);

		$data['get_store_location'] = $this->global_model->getDataWhereOrder('category',$where,$order)->result_array();

        //View
		$data['load_view'] = 'adminsite/v_category_sort';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function update(){
		$data = $this->input->post('data',true);

		$return = array(
			'flag'		=>  false,
			'message'	=> 'Update Failed'
			);

		$updated = false;
		if(!empty($data)){
			foreach($data as $index => $value){
				$update = array(
					'category_order' 	=> $index,
					'category_modified' => date('c')
					);

				$updated = $this->global_model->update('category',$update,array('category_id' => $value['name']));
			}
		}

		if($updated){
			$return = array(
				'flag'		=>	true, 
				'process' 	=> 'Update Success'
				);
		}
		echo json_encode($return);
	}

}
?>