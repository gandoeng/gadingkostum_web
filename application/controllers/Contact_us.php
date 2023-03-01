<?php



defined('BASEPATH') OR exit('No direct script access allowed');



class Contact_us extends CI_Controller{



	public function __construct()



	{


		parent::__construct();



		$this->load->model('front_model');

		$this->load->model('backend_model');

		$this->load->model('global_model');

		$this->load->library('categories_menu_lib');

	}



	public function index(){

		$data['title'] 		 		= 'Hubungi kami Gading Kostum - penyewaan kostum - costume rental';
		$data['meta'] = array(
				'title'		  =>  $data['title'],
				'type'		  => 'article',
				'description' => 'Hubungi Kami - Gading Kostum penyewaan kostum berkualitas , terbaik dan terbaru - alamat kami - denah - contact us',
				'site_name'   => 'Gading Kostum',
				'locale'	  => 'en_US',
				'card'		  => 'summary',
				'canonical'   => current_url(),
				'url'		  => current_url()
			);
		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');
		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');
		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');
		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');
		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');
		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');
		$this->load->view('v_contactus',$data);

	}


	public function submit(){
		//Set responses
		$result = array('status' => 0, 'message' => '', 'error' => array());

		//Set vars
		$name 		= $this->input->post('name', TRUE);
		$email 		= $this->input->post('email', TRUE);
		$subject 	= $this->input->post('subject', TRUE);
		$message 	= $this->input->post('message', TRUE);

		//Form validation
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|min_length[10]');
		$this->form_validation->set_rules('message', 'Message', 'trim|required|min_length[10]');

		if($this->form_validation->run()){
			$result['status'] 	= 1;
			$result['message']	= 'Your message has been successfully sent.';

		}else{
			if($_POST){
				$result['error'] = $this->form_validation->error_array();
			}
		}

		echo json_encode($result);
	}


}


?>