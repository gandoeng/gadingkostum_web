<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller{

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
		$data['title']  	= 'Setting';
		$data['setting']    = $this->global_model->select('setting');
		$data['correction'] = $this->global_model->select('correction');

		$this->form_validation->set_rules('setting_id','Setting','trim');
		$this->form_validation->set_rules('setting_name','Setting Name','trim');
		$this->form_validation->set_rules('setting_value','Setting Value','trim');

		if($this->form_validation->run() == false){
			//View
			$data['load_view'] = 'adminsite/v_setting';
			$this->load->view('adminsite/template/backend', $data);

		} else {

			$setting_id 		= $this->input->post('setting_id');
			$setting_name 		= $this->input->post('setting_name');
			$setting_value 		= $this->input->post('setting_value');
			$popular_theme 		= $this->input->post('popular_theme');
			$popular_category 	= $this->input->post('popular_category');
			$latest_product_at_homepage = $this->input->post('latest_product_at_homepage');
			$late_charge  		= $this->input->post('late_charge');
			$day_after_return   = $this->input->post('day_after_return');
			$invoice_footer   	= $this->input->post('invoice_footer');
			
			if(empty($late_charge)){
				$late_charge = 0;
			}
			if(empty($latest_product_at_homepage)){
				$latest_product_at_homepage = 0;
			}
			if(empty($popular_theme)){
				$popular_theme 		= 0;
			}
			if(empty($popular_category)){
				$popular_category 	= 0;
			}

			if(empty($day_after_return)){
				$day_after_return 	= 0;
			}

			if(!isset($setting_value['latest_product_at_homepage']) || empty($setting_value['latest_product_at_homepage'])){
				$setting_value['latest_product_at_homepage'] = 0;
			}
			if(is_array($setting_name) && !empty($data['setting'])){
				foreach($setting_name as $index => $value){
					foreach($data['setting'] as $key => $row){
						if($value == $row['setting_name']){
							$update = array(
								'setting_value' => $setting_value[$index]
								);
							$this->global_model->update('setting',$update,array('setting_id' => $row['setting_id']));
						}
						if($row['setting_name'] == 'popular_theme'){
							$update = array(
								'setting_value' => $popular_theme
								);
							$this->global_model->update('setting',$update,array('setting_id' => $row['setting_id']));
						}
						if($row['setting_name'] == 'popular_category'){
							$update = array(
								'setting_value' => $popular_category
								);
							$this->global_model->update('setting',$update,array('setting_id' => $row['setting_id']));
						}
						if($row['setting_name'] == 'latest_product_at_homepage'){
							$update = array(
								'setting_value' => $latest_product_at_homepage
								);
							$this->global_model->update('setting',$update,array('setting_id' => $row['setting_id']));
						}
						if($row['setting_name'] == 'late_charge'){
							$update_late = array(
								'setting_value' => preg_replace("/[^0-9\.]/","",$late_charge)
								);
							$this->global_model->update('setting',$update_late,array('setting_id' => $row['setting_id']));
						}
						if($row['setting_name'] == 'day_after_return'){
							$update = array(
								'setting_value' => preg_replace("/[^0-9\.]/","",$day_after_return)
								);
							$this->global_model->update('setting',$update,array('setting_id' => $row['setting_id']));
						}
						if($row['setting_name'] == 'invoice_footer'){
							$update = array(
								'setting_value_textarea' => htmlentities($invoice_footer)
								);
							$this->global_model->update('setting',$update,array('setting_id' => $row['setting_id']));
						}
					}
				}
				$this->session->set_flashdata('success','Save success');
			} else {
				$this->session->set_flashdata('validation','No found data');
			}
			redirect('adminsite/setting','refresh');
		}	

	}

	public function correction(){
		$this->form_validation->set_rules('id','ID','trim');
		$this->form_validation->set_rules('wrong','Wrong','trim');
		$this->form_validation->set_rules('right','Right','trim');

		if($this->form_validation->run() == false){

			redirect('adminsite/setting','refresh');

		} else {

			$id    = $this->input->post('id');
			$wrong = $this->input->post('wrong');
			$right = $this->input->post('right');
			if(!empty($wrong)){
				foreach($wrong as $index => $value){
					$exploded 		= explode("\n",trim($value));
					$wrong[$index]  = json_encode(str_replace("\r","",$exploded));
				}
			}

			$correction_db 	= $this->backend_model->get_correction();
			$id_db 			= array();

			if(!empty($correction_db)){
				foreach($correction_db as $index => $value){
					$id_db[] = $value['Id'];
				}
			}

			$id_form = array();
			if(isset($id)){
				foreach($id as $index => $value){
					if(empty($value)){
						if(!empty($wrong[$index]) && !empty($right[$index])){
							$insert = array(
								'wrong' => $wrong[$index],
								'right' => $right[$index]
								);
							$this->global_model->insert('correction',$insert);
						}
					} else {
						$id_form[$index] = $value;
					}
				}
			}

			if(!empty($id_db)){
				foreach($id_db as $index => $value){
					if(in_array($value,$id_form)){
						foreach($id_form as $key => $row){
							if($value == $row){
								$update = array(
									'wrong' => $wrong[$key],
									'right' => $right[$key]
									);
								$this->global_model->update('correction',$update,array('Id' => $row));
							}
						}
					} else {
						$this->global_model->delete('correction',array('Id' => $value));
					}
				}
			}
			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/setting','refresh');
		}
	}

	public function form(){
		$this->form_validation->set_rules('category_id','Category','trim');
		$this->form_validation->set_rules('category_name','Size Category','trim|required');
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
				$slug = $this->backend_model->createSlug('category','category_id',$id,'category_slug',$category_name);
				$slug = $this->backend_model->createSlug('category','category_id',$id,'category_slug',$category_name);
				$return = array('flag'=>true, 'process' => 'insert');

			} else {

				$slug = $this->backend_model->createSlug('category','category_id',$category_id,'category_slug',$category_name);
				$update = array(
					'category_name'				=> $category_name,
					'category_flag'				=> $category_flag,
					'category_status'			=> $category_status,
					'category_modified'			=> date('c')
					);
				$updated = $this->global_model->update('category',$update,array('category_id' => $category_id));
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

		$delete = $this->global_model->delete('category',array('category_id' => $id));

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

			for($i=0; $i<count($id); $i++){

				$delete = $this->global_model->delete('category',array('category_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

}

?>