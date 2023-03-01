<?php



defined('BASEPATH') OR exit('No direct script access allowed');



class Article extends CI_Controller{



	public function __construct()



	{



		parent::__construct();



		$this->load->model('front_model');

		$this->load->model('backend_model');

		$this->load->model('global_model');

		$this->load->library('categories_menu_lib');

	}



	public function index(){

		$slug = $this->uri->segment(2);

		$data['title'] 		 		= 'Article - Gading Kostum';

		$data['article']            = $this->front_model->get_articel_by_slug($slug);


		if(!empty($data['article'])){

			$metatitle 			= $data['article'][0]['article_title'].' - Gading Kostum';
			$metadescription 	= $data['article'][0]['article_title'].' - Gading Kostum';
			$metakeyword 		= $data['article'][0]['article_title'].' - Gading Kostum';

			$article_created_hour    = date('H',strtotime($data['article'][0]['article_created']));
			$article_created_minute  = date('i',strtotime($data['article'][0]['article_created']));
			$article_created_second  = date('s',strtotime($data['article'][0]['article_created']));
			$article_created_month   = date('m',strtotime($data['article'][0]['article_created']));
			$article_created_day     = date('d',strtotime($data['article'][0]['article_created']));
			$article_created_year    = date('Y',strtotime($data['article'][0]['article_created']));

			$article_modified_hour    = date('H',strtotime($data['article'][0]['article_modified']));
			$article_modified_minute  = date('i',strtotime($data['article'][0]['article_modified']));
			$article_modified_second  = date('s',strtotime($data['article'][0]['article_modified']));
			$article_modified_month   = date('m',strtotime($data['article'][0]['article_modified']));
			$article_modified_day     = date('d',strtotime($data['article'][0]['article_modified']));
			$article_modified_year    = date('Y',strtotime($data['article'][0]['article_modified']));

			if(!empty($data['article'][0]['article_metatitle'])){
				$metatitle = $data['article'][0]['article_metatitle'].' - Gading Kostum';
			}
			if(!empty($data['article'][0]['article_metadescription'])){
				$metatitle = $data['article'][0]['article_metadescription'];
			}

			if(!empty($data['article'][0]['article_metakeyword'])){
				$metakeyword = $data['article'][0]['article_metakeyword'];
			}

			$data['meta'] = array(
				'title'		  =>  $metatitle,
				'keywords'	  =>  $metakeyword,
				'type'		  => 'article',
				'site_name'   => 'Gading Kostum',
				'locale'	  => 'en_US',
				'card'		  => 'summary',
				'description' => $metadescription,
				'canonical'   => current_url(),
				'url'		  => current_url(),
				'article_section' => 'Event',
				'article_published_time' => gmdate(DATE_ATOM,mktime($article_created_hour,$article_created_minute,$article_created_second,$article_created_month,$article_created_day,$article_created_year)),
				'article_modified_time' => gmdate(DATE_ATOM,mktime($article_modified_hour,$article_modified_minute,$article_modified_second,$article_modified_month,$article_modified_day,$article_modified_year)),
				'updated_time' => gmdate(DATE_ATOM,mktime($article_modified_hour,$article_modified_minute,$article_modified_second,$article_modified_month,$article_modified_day,$article_modified_year)),
				'image'  		=> base_url().$data['article'][0]['article_image'],
				'image_width'   => 800,
				'image_height'	=> 503
				);
		}

		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');

		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');

		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');

		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');

		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');

		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');
		
		$this->load->view('v_article',$data);

	}

}

?>