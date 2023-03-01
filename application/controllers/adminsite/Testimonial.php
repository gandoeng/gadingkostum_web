<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial extends CI_Controller{

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
		//$data['get'] = $this->backend_model->getData('testimonial',$order);
		$data['title']  = 'Testimonial';
		/*$where = array(
			'category_flag' => 'size'
			);

		$order = array(
			'category_order' => 'asc',
			'category_created' => 'desc'
			);

			$data['get'] 			= $this->global_model->getDataWhereOrder('category',$where,$order);*/
		$data['table_data']		= 'testimonial'; // element id table
		$data['ajax_sort_url'] 	= 'adminsite/testimonial/order'; // Controller row order data
		$data['ajax_data_table']= 'adminsite/testimonial/datatables'; //Controller ajax data
		/*$this->custom_lib->datatables_roworder(TRUE,'#testimonial','panel/testimonial/sort')*/

		$data['datatables_ajax_data'] = array(

			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])

			);

        //View
		$data['load_view'] = 'adminsite/v_testimonial';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function form(){
		$this->form_validation->set_rules('testimonial_id','testimonial','trim');
		$this->form_validation->set_rules('testimonial_image','Image','trim|required');
		$this->form_validation->set_rules('status','Category Status','trim');

		if($this->form_validation->run() == false){

			$return = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
				);

		} else {

			$testimonial_id 						= $this->input->post('testimonial_id');
			$testimonial_image 					= $this->input->post('testimonial_image');
			$testimonial_status 					= $this->input->post('status');

			if(empty($testimonial_status)){
				$testimonial_status 	= 0;
			}
			if(empty($testimonial_id)){
			//CATEGORY
				$insert_testimonial = array(
					'testimonial_image'			=> str_replace(base_url(),'', $testimonial_image),
					'testimonial_status'			=> $testimonial_status,
					'testimonial_created'			=> date('c'),
					'testimonial_modified'		=> NULL
					);

				$result_insert 	= $this->global_model->insert('testimonial',$insert_testimonial);

				$return = array(
					'flag'		=>	true, 
					'process' 	=>	'insert',
					);

			} else {
				$update_testimonial = array(
					'testimonial_image'			=> str_replace(base_url(),'', $testimonial_image),
					'testimonial_status'			=> $testimonial_status,
					'testimonial_modified'		=> date('c'),
					);
				$updated = $this->global_model->update('testimonial',$update_testimonial,array('testimonial_id' => $testimonial_id));

				$return = array(
					'flag'		=>	true, 
					'process' 	=> 'update'
					);
			}

		}

		echo json_encode($return);

	}

	public function edit_display_data(){
		$id 		= $this->input->post('category_id');
		$query 		= $this->global_model->select_where('testimonial',array('testimonial_id' => $id));

		if(!empty($query)){
			$data = array();
			$testimonial_image 			= '';
			foreach($query as $index => $value){
				$testimonial_image				= $value['testimonial_image'];

				$data = array(
					'testimonial_id'						=> $value['testimonial_id'],
					'status'							=> $value['testimonial_status'],
					);
			}
			$result = array(
				'data' 			=> $data,
				'image'			=>
				array(
					array(
						'name' 	=> 'testimonial_image',
						'value' => $testimonial_image
						),
					),
				'flag' 		=> true,
				'query'		=> $query
				);
		} else {
			$result = array(
				'message' 	=> 'Nothing found data.',
				'flag'		=> false,
				'query'		=> $query
				);
		}

		echo json_encode($result);
	}

	public function delete(){

		$id = $this->input->post('testimonial_id');

		$delete = $this->global_model->delete('testimonial',array('testimonial_id' => $id));
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

					'testimonial_status' 		=> 1,
					'testimonial_modified' 	=> date('c')

					);

				$update = $this->global_model->update('testimonial',$data,array('testimonial_id' => $id[$i]));

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
					'testimonial_status' 		=> 0,
					'testimonial_modified' 	=> date('c')
					);

				$update = $this->global_model->update('testimonial',$data,array('testimonial_id' => $id[$i]));

			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function multiple_delete(){

		$id = $this->input->post('id',true);
		$update = false;

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$delete = $this->global_model->delete('testimonial',array('testimonial_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function datatables(){
		$order_array = array(
				'testimonial_created' => 'desc'
			);
		$query 		= $this->backend_model->get_join_by_id('testimonial','','*','',$order_array);				
		$data = array();
		if(!empty($query)){

			foreach($query->result() as $index => $value) {

				$status = '';
				if($value->testimonial_status == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}

				$action 	= '';
				$action = '<a class="btn-edit-action btn-ajax-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value->testimonial_id.'" data-url="'.base_url('adminsite/testimonial/edit_display_data').'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value->testimonial_id.'" data-url="'.base_url('adminsite/testimonial/delete').'">Delete</a>';

				if(!empty($value->testimonial_image)){
					$image  = '<img class="img-thumbnail" src="'.$value->testimonial_image.'">';
				} else {
					$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
				}

				$data[] = array(
						'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->testimonial_id.'"></div>',
						$image,
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