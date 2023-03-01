<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Prosedur_sewa extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('front_model');
		$this->load->model('backend_model');
		$this->load->model('global_model');
		$this->load->library('categories_menu_lib');
	}


	public function index(){
		$data['title'] 		 		= 'Prosedur Sewa - Gading Kostum';
		//$meta_tag 	= $this->front_model->get_meta_default();

		$data['meta'] = array(
				'title'		  => 'prosedur sewa Gading Kostum - cara menyewa kostum di tempat kami',
				'type'		  => 'article',
				'description' => 'pilih langsung di toko - kontak via telpon atau wa - kostum bisa diantar / dikirim',
				'site_name'   => 'Gading Kostum',
				'locale'	  => 'en_US',
				'card'		  => 'summary',
				'canonical'   => current_url(),
				'url'		  => current_url()
			);

		/*if(!empty($meta_tag)){
			foreach($meta_tag as $index => $value){
				switch ($value['setting_name']) {
					case 'meta_title':
						if($value['setting_value'] !== ''){
							$data['meta']['title'] = $value['setting_value'];
						}
						break;
					case 'meta_description':
						if($value['setting_value'] !== ''){
							$data['meta']['description'] = $value['setting_value'];
						}
						break;
				}
			}
		}*/

		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');
		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');
		$data['content']			= $this->front_model->get_page_by_slug('prosedur-sewa');
		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');
		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');
		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');
		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');

		$this->load->view('v_prosedursewa',$data);
	}
}
?>