<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Correction extends CI_Controller{

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

	public function add(){
		$this->form_validation->set_rules('id','ID','trim');
		$this->form_validation->set_rules('wrong','Wrong','trim');
		$this->form_validation->set_rules('right','Right','trim');

		if($this->form_validation->run() == false){

			redirect('adminsite/correction','refresh');

		} else {

			$wrong = $this->input->post('wrong');
			$right = $this->input->post('right');

			if(!empty($wrong)){
				$wrong 					= explode("\n",trim($wrong));
				$wrong  	    		= json_encode(str_replace("\r","",$wrong));
			}

			$insert = array(
					'right' => $right,
					'wrong' => $wrong
				);

			$this->global_model->insert('correction',$insert);
			
			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/correction','refresh');
		}

	}
	public function index(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  	= 'Correction';
		$data['correction'] = $this->global_model->select_order("correction", "right", "ASC");

		$this->form_validation->set_rules('id','ID','trim');
		$this->form_validation->set_rules('wrong','Wrong','trim');
		$this->form_validation->set_rules('right','Right','trim');

		if($this->form_validation->run() == false){

			$data['load_view'] = 'adminsite/v_correction';
			$this->load->view('adminsite/template/backend', $data);

		} else {

			$id    = $this->input->post('id');
			$wrong = $this->input->post('wrong');
			$right = $this->input->post('right');
			if(!empty($wrong)){
				foreach($wrong as $index => $value){
					$exploded 					= explode("\n",trim($value));
					$wrong[$index]  			= json_encode(str_replace("\r","",$exploded));
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
			redirect('adminsite/correction','refresh');
		}
	}
}
?>