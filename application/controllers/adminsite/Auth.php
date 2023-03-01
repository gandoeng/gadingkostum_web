<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');

		$this->load->model('global_model');

		$this->load->library('custom_lib');

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

			if($already_login !== false){
				$this->session_items = data_session($this->session->userdata($this->config->item('access_panel')));
				$this->start_session = $this->session_items;
			}

			redirect('adminsite/dashboard','refresh');

		}

	}

	public function login()
	{

		$data['title'] = 'Login';

		$this->form_validation->set_rules('username','Username', 'trim|required|callback_check_validate');

		$this->form_validation->set_rules('password','Password', 'trim|required');

		if ($this->form_validation->run() == false){

			if(validation_errors()){

				$data['error_message'] = $this->custom_lib->error_message(validation_errors());

			}

			$this->load->view('adminsite/v_login',$data);

		}else{
			
			$username = $this->input->post('username',true);

			$password = md5($this->input->post('password',true));

			$query = $this->auth_model->user_admin_by_login($username,$password,'users');

			if(!empty($query) && is_array($query)){

				foreach($query as $index => $value){

					$session_item = array(

						'id_adm' => $value['Id'],
						'username_adm' => $value['username'],
						'token_adm' => md5(date('c')),
						'last_login_adm' => $value['last_login'],
						'ip_address_adm' => $this->auth_model->_prepare_ip($this->input->ip_address()),
						'role'	=> $value['role'],
						'status_adm' => $value['status']

						);

					$key_session = $this->config->item('access_panel');

					if(isset($key_session)){

						$session_start = set_session($key_session,$session_item);

					} else {

						$session_start = set_session('default_panel',$session_item);

					}

				}

				if($session_start != FALSE){

					$update_user = array(

						'last_login' => date('Y-m-d H:i:s'),
						'token' => md5(date('c')),
						'ip_address' => $this->auth_model->_prepare_ip($this->input->ip_address())
						);

					$this->global_model->update('users',$update_user,array('Id' => $value['Id']));

					redirect('adminsite/dashboard','refresh');

				} else {

					$this->session->set_flashdata('error_message','Something wrong with the session.');

					redirect('adminsite','refresh');

				}

				redirect('adminsite/dashboard','refresh');
			} else {

				$this->session->set_flashdata('error_message','Username or password incorrect.');

				redirect('adminsite','refresh');

			}

		}

	}

	public function check_validate($param){

		$param = $this->input->post('username',true);

		$another_param = md5($this->input->post('password',true));

		$query = $this->auth_model->check_auth($param,$another_param);

		if($param == ''){

			$this->form_validation->set_message('check_validate', 'The username field is required.');

			return false;

		} else if($query == false){

			$this->form_validation->set_message('check_validate', 'Username or password incorrect.');

			return false;

		} else {

			return true;

		}

	}


}
