<?php



defined('BASEPATH') OR exit('No direct script access allowed');



class Home extends CI_Controller{



	public function __construct()



	{



		parent::__construct();



		$this->load->model('front_model');

		$this->load->model('backend_model');

		$this->load->model('global_model');

		$this->load->library('categories_menu_lib');

	}



	public function index(){



		$data['title'] 		 		= 'Home - Gading Kostum';

		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');

		$data['pcs_kostum'] 		= $this->front_model->get_count_pcs_kostum();

		$data['jenis_kostum'] 		= $this->front_model->get_count_jenis_kostum();
		
		$data['slideshow'] 			= $this->front_model->get_slideshow();
		
		$data['article']            = $this->front_model->get_articel_thumbnail();
		
		$data['latest_news']     	= $this->tempArticle();
		
		// $data['latest_product']     = $this->front_model->product_homepage(6);
		$data['latest_product']     = [];

		$data['popular_category']   = $this->front_model->popular_category();

		// $data['popular_theme']   	= $this->front_model->popular_theme();
		$data['popular_theme']   	= [];

		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');

		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');

		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');

		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');

		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');

		if(!empty($data['latest_product'])){

			foreach($data['latest_product'] as $index => $value){

				$product_sizestock = $this->global_model->select_where('product_sizestock',array('product_id' => $value['product_id']));

				if(!empty($product_sizestock)){

					foreach($product_sizestock as $key => $row){

						$data['latest_product'][$index]['product_sizestock'][$key] = array('product_size' => $row['product_size'],'product_sizestock_slug' => $row['product_sizestock_slug']);

					}

				}



				$product_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));

				$image 	 = 'assets/images/no-thumbnail.png';

				if(!empty($product_image)){

					if(file_exists($product_image[0]['product_image'])){
						$check_image = true;
					} else {
						$check_image = false;
					}

					if($check_image){

						$image   = $product_image[0]['product_image'];

					} else {

						$image 	 = 'assets/images/no-thumbnail.png';

					}

				}

				$data['latest_product'][$index]['product_image'] = $image;

			}

		}


		$data['featured_product']     = $this->front_model->product_featured();

		if(!empty($data['featured_product'])){

			foreach($data['featured_product'] as $index => $value){

				$product_sizestock = $this->global_model->select_where('product_sizestock',array('product_id' => $value['product_id']));

				if(!empty($product_sizestock)){

					foreach($product_sizestock as $key => $row){

						$data['featured_product'][$index]['product_sizestock'][$key] = array('product_size' => $row['product_size'],'product_sizestock_slug' => $row['product_sizestock_slug']);

					}

				}



				$product_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));

				$image 	 = 'assets/images/no-thumbnail.png';

				if(!empty($product_image)){

					if(file_exists($product_image[0]['product_image'])){
						$check_image = true;
					} else {
						$check_image = false;
					}

					if($check_image){

						$image   = $product_image[0]['product_image'];

					} else {

						$image 	 = 'assets/images/no-thumbnail.png';

					}

				}

				$data['featured_product'][$index]['product_image'] = $image;

			}

		}

		$meta_tag 	= $this->front_model->get_meta_default();

		$data['meta'] = array(
				'title'		  => 'Gading Kostum - Sewa Kostum Anak & Dewasa - Costume Rental',
				'type'		  => 'website',
				'description' => 'Sewa kostum terbaru untuk anak dan dewasa di kelapa gading jakarta - kostum karakter, superhero, adat, profesi, negara, dll. Bisa dikirim / diantar',
				'site_name'   => 'Gading Kostum',
				'locale'	  => 'en_US',
				'card'		  => 'summary',
				'canonical'   => current_url(),
				'url'		  => current_url()
			);

		if(!empty($meta_tag)){
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
		}

		$this->load->view('v_home',$data);

	}


	public function article($id = '1'){
		$articledata = $this->tempArticle();

		$data['title'] 		 		= 'Home - Gading Kostum';
		$data['article']			= $articledata[$id];
		$data['categories_menu']  	= $this->categories_menu_lib->menu('pagination');
		$data['pcs_kostum'] 		= $this->front_model->get_count_pcs_kostum();
		$data['jenis_kostum'] 		= $this->front_model->get_count_jenis_kostum();				
		$data['slideshow'] 			= $this->front_model->get_slideshow();				
		$data['latest_product']     = $this->front_model->product_homepage(6);
		$data['popular_category']   = $this->front_model->popular_category();
		$data['popular_theme']   	= $this->front_model->popular_theme();
		$data['header_whatsapp']    = $this->front_model->get_setting('header_whatsapp','setting_value');
		$data['footer_address']    	= $this->front_model->get_setting('footer_address','setting_value');
		$data['footer_phone']    	= $this->front_model->get_setting('footer_phone','setting_value');
		$data['footer_email']    	= $this->front_model->get_setting('footer_email','setting_value');
		$data['footer_whatsapp']    = $this->front_model->get_setting('footer_whatsapp','setting_value');	

		$this->load->view('v_article',$data);
	}

	function tempArticle(){
		$dataArticle = array(
			'1' => array(
				'title' 			=> 'pakaian adat di hari kartini',
				'image' 			=> 'assets/images/news1.png',
				'description'		=> 'Hari Kartini kita rayakan untuk memperingati tokoh emansipasi wanita R.A. Kartini. Kartini adalah putri dari seorang Bupati Jepara yang lahir pada tanggal 21 April 1879. Semasa hidupnya dia banyak ',
				'thumbnailtext'	=> 'Hari Kartini kita rayakan untuk memperingati tokoh emansipasi wanita R.A. Kartini. Kartini adalah putri dari seorang Bupati Jepara yang lahir pada tanggal 21 April 1879. Semasa hidupnya dia banyak ',
				'postedBy'		=> 'admin',
				'postedDate'		=> '26 Dec 2018',
				), 
			'2' => array(
				'title' 			=> 'pakaian adat di hari kartini',
				'image' 			=> 'assets/images/news2.png',
				'description'		=> 'Hari Kartini kita rayakan untuk memperingati tokoh emansipasi wanita R.A. Kartini. Kartini adalah putri dari seorang Bupati Jepara yang lahir pada tanggal 21 April 1879. Semasa hidupnya dia banyak ',
				'thumbnailtext'	=> 'Hari Kartini kita rayakan untuk memperingati tokoh emansipasi wanita R.A. Kartini. Kartini adalah putri dari seorang Bupati Jepara yang lahir pada tanggal 21 April 1879. Semasa hidupnya dia banyak ',
				'postedBy'		=> 'admin',
				'postedDate'		=> '26 Dec 2018',
				),
			);

		return $dataArticle;
	}
	
	/*function add_kelapa_gading(){

		$this->db->select('product_id');
		$this->db->where('category_id',614);
		$query = $this->db->get('product_category_detil')->result_array();
		$product_id = array();
		foreach($query as $index => $value){
			$product_id[] = $value['product_id'];
		}

		$query_prod = $this->db->get('product')->result_array();
		foreach($query_prod as $index => $value){
			if(!in_array($value['product_id'],$product_id)){
				$insert = array(
						'product_id' => $value['product_id'],
						'category_id' => 614,
						'flag' 	=> 'store_location'
					);
				$this->db->insert('product_category_detil',$insert);
			}
		}
		echo 'done';
	}*/



}







?>