<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller{
	
	public function __construct()
	{

		parent::__construct();

	}

	public function index(){

        //$this->session->sess_destroy();
		$key_session = $this->config->item('access_panel');

		if($this->session->has_userdata('default_panel')){
			$this->session->unset_userdata('default_panel');
		}
		if(isset($key_session) && !empty($key_session)){
			$this->session->unset_userdata($key_session);
		}

		redirect('adminsite','refresh');

	}

}

?>