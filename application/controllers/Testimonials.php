<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonials extends CI_Controller{

	public function __construct()

	{

		parent::__construct();

		$this->load->model('front_model');

		$this->load->model('backend_model');

		$this->load->model('global_model');

		$this->load->library('categories_menu_lib');

	}

	public function index(){

		$data['title'] 		 		= 'Testimonials - Gading Kostum';

		$data['meta'] = array(
				'title'		  =>  $data['title'],
				'type'		  => 'article',
				'description' => 'Testimonial Gading Kostum',
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
		$data['testimonial']		= $this->front_model->get_testimonial();

		$this->load->view('v_testimonial',$data);

	}


}


?>