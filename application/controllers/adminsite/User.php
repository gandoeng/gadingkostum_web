<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller{

	public function __construct()
	{

		parent::__construct();

		$this->load->model('global_model');

		$this->load->model('backend_user_model');

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
		
		$data['title'] = 'Panel Admin';
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$this->session_items = data_session($this->session->all_userdata());

		foreach($this->session_items as $index => $value){

			$data['session_items'] = $value;

		}

		$data['table_data']		= 'user'; // element id table
		$data['ajax_data_table']= 'adminsite/user/datatables'; //Controller ajax data

		$data['datatables_ajax_data'] = array(

			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])

			);

		$data['load_view']          = 'adminsite/v_user';

		$this->load->view('adminsite/template/backend', $data);

	}

	public function datatables(){

		$data = array();

		$session          = $this->session->userdata($this->config->item('access_panel'));

		$order_array = array(
			'created_date' => 'desc'
			);
		$query 	= $this->backend_model->get_join_by_id('users','','*','',$order_array);				

		if(!empty($query)){

			foreach($query->result() as $index => $value) {

				$status = '';
				if($value->status == 2){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}
				
				$action 	= '';
				$action = '<a class="btn-edit-action btn btn-primary btn-sm" style="margin-right: 5px;" href="'.base_url('adminsite/user/edit/').$value->Id.'">Edit</a>';
				$action .= '<a class="btn-delete-action btn btn-danger btn-sm" href="'.base_url('adminsite/user/delete/').$value->Id.'">Delete</a>';

				$checkbox  = '';
				if(isset($session) && is_array($session)){
					if($session['id_adm'] == $value->Id){
						$checkbox .= 'Current';
					} else {
						$checkbox .= '<input type="checkbox" name="check_action" value="'.$value->Id.'">';
					}
				}

				$data[] = array(
					$checkbox,
					$value->username,
					$value->role,
					$status,
					$action
					);

			}

		}

		$result = $this->custom_lib->datatables_data($query,$data);
		echo json_encode($result);

	}

	public function add(){

		$data['title'] = 'Panel Admin';
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$this->session_items = data_session($this->session->all_userdata());

		foreach($this->session_items as $index => $value){

			$data['session_items'] = $value;

		}

		$this->form_validation->set_rules('username','Username','required|trim|alpha_dash|callback_validate_username');
		$this->form_validation->set_rules('password','Password','required|trim');
		$this->form_validation->set_rules('role','Role','trim');
		$this->form_validation->set_rules('email','Email','trim');
		$this->form_validation->set_rules('re_password','Re-type New Password','required|trim|matches[password]');
		if($this->form_validation->run() == false){

			$data['load_view']          = 'adminsite/v_user_add';
			$this->load->view('adminsite/template/backend', $data);

		} else {

			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$email 	  = $this->input->post('email');
			$role 	  = $this->input->post('role');
			$status   = $this->input->post('status');

			if(empty($status)){
				$status = 1;
			}

			$data = array(
				'username' => $username,
				'password' => md5($password),
				'email'	   => $email,
				'role'	   => $role,
				'status'   => $status,
				'created_date' => date('c')
				);

			$insert = $this->global_model->insert('users',$data);

			if($insert){

				$this->session->set_flashdata('success','Save success');

			} else {

				$this->session->set_flashdata('error','Something wrong please try again later');

			}

			redirect('adminsite/user');

		}

	}

	public function edit(){

		$data['title'] = 'Panel Admin';
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$id = $this->uri->segment(4);

		$this->session_items = data_session($this->session->all_userdata());

		foreach($this->session_items as $index => $value){

			$data['session_items'] = $value;

		}

		$data['id']	 = $id;
		$data['get'] = $this->global_model->select_where('users',array('Id' => $id));

		$this->form_validation->set_rules('role','Role','trim');
		$this->form_validation->set_rules('password','Password','trim');

		$username = $this->input->post('username');
		$email 	  = $this->input->post('email');
		$role 	  = $this->input->post('role');
		$status   = $this->input->post('status');
		$password = $this->input->post('password');

		if(!empty($data['get'])){

			if($data['get'][0]['username'] !== $username){
				$this->form_validation->set_rules('username','Username','required|trim|alpha_dash|callback_validate_username');
			} else {
				$this->form_validation->set_rules('username','Username','required|trim|alpha_dash');
			}

			if($data['get'][0]['email'] !== $email){
				$this->form_validation->set_rules('email','Email','trim');
			} else {
				$this->form_validation->set_rules('email','Email','trim');
			}

			if(!empty($password)){
				$this->form_validation->set_rules('re_password','Re-type New Password','required|trim|matches[password]');
			}
		}

		if($this->form_validation->run() == false){

			$data['load_view']          = 'adminsite/v_user_edit';
			$this->load->view('adminsite/template/backend', $data);

		} else {

			if(empty($status)){
				$status = 1;
			}

			if(empty($password)){
				$password = $data['get'][0]['password'];
			} else {
				$password = md5($password);
			}

			$updated = array(
				'username' => $username,
				'password' => $password,
				'email'	   => $email,
				'role'	   => $role,
				'nickname' => $nickname,
				'status'   => $status,
				'modified_date' => date('c')
				);

			$update = $this->global_model->update('users',$updated,array('Id' => $id));

			if(is_array($data['session_items']) && !empty($data['session_items'])){
				if($id == $data['session_items']['id_adm']){
					$sess_update = array(
						'id_adm' 		=> $id,
						'username_adm' 	=> $username,
						'role'			=> $role,
						'token_adm'		=> $data['session_items']['token_adm'],
						'last_login_adm'=> $data['session_items']['last_login_adm'],
						'ip_address_adm'=> $data['session_items']['ip_address_adm'],
						'status_adm'	=> $data['session_items']['status_adm']
						);
					$this->session->set_userdata('sess_panel_akari',$sess_update);
				}
			}

			if($updated){

				$this->session->set_flashdata('success','Save success');

			} else {

				$this->session->set_flashdata('error','Something wrong please try again later');

			}

			redirect('adminsite/user/edit/'.$id);

		}

	}

	public function publish(){

		$id = $this->input->post('id');

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$data = array(

					'status' => 2,
					'modified_date' => date('Y-m-d H:i:s')

					);

				$update = $this->global_model->update('users',$data,array('Id' => $id[$i]));

			}

		} else {

			$update = FALSE;
		}

		if($update == true){

			echo json_encode(TRUE);
			$this->session->set_flashdata('success','Save success');

		} else {
			echo json_encode(FALSE);
			$this->session->set_flashdata('success','Nothing change data');

		}


	}

	public function unpublish(){

		$id = $this->input->post('id');

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$data = array(

					'status' => 1,
					'modified_date' => date('Y-m-d H:i:s')

					);

				$update = $this->global_model->update('users',$data,array('Id' => $id[$i]));

			}

		} else {

			$update = FALSE;
		}

		if($update == true){

			echo json_encode(TRUE);
			$this->session->set_flashdata('success','Save success');

		} else {
			echo json_encode(FALSE);
			$this->session->set_flashdata('success','Nothing change data');

		}

	}

	public function delete($id){

		$id = $this->uri->segment(4);

		$delete = $this->global_model->delete('users',array('Id' => $id));

		if($delete){

			$this->session->set_flashdata('success','Delete success');

		} else {

			$this->session->set_flashdata('error','Delete success');

		}

		redirect('adminsite/user');

	}

	public function multiple_delete(){

		$id = $this->input->post('id',true);

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$delete = $this->global_model->delete('users',array('Id' => $id[$i]));
			}

		} else {

			$delete = FALSE;
		}

		if($delete == true){

			echo json_encode(TRUE);
			$this->session->set_flashdata('success','Delete success');

		} else {

			echo json_encode(FALSE);
			$this->session->set_flashdata('success','Nothing change data');

		}

	}

	public function validate_username($param){

		$query = $this->backend_user_model->check_username($param);

		if($query){

			$this->form_validation->set_message('validate_username', 'Username already taken.');

			return false;

		} else {

			return true;

		}

	}

	public function validate_email($param){

		$query = $this->backend_user_model->check_email($param);

		if($query){

			$this->form_validation->set_message('validate_email', 'Email already taken.');

			return false;

		} else {

			return true;

		}

	}

}

?>