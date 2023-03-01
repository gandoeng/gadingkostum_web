<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog extends CI_Controller{

	public function __construct()
	{

		parent::__construct();

		$this->load->model('global_model');
		$this->load->model('backend_model');
		$this->load->model('auth_model');
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

			if($already_login !== false && $role == 'admin'){
				$this->session_items = data_session($this->session->userdata($this->config->item('access_panel')));
				$this->start_session = $this->session_items;
			}

		} else {
			redirect('adminsite','refresh');
		}
	}

	public function generateSizeUrl(){
		$product = $this->global_model->select('product_sizestock');
		echo '<pre>';
		print_r($product);
		echo '</pre>';
		/*foreach($product as $index => $value){
			$update = array(
					'product_sizestock_slug' => url_title($value['product_size'],'-',true)
				);
			$data = $this->global_model->update('product_sizestock',$update,array('product_sizestock_id'=>$value['product_sizestock_id']));
		}*/
	}
	public function generateQr(){
		//$product 					  = $this->backend_model->getProductForQr();
		//$qr_folder 					  = FCPATH."assets/qr/";
		$base_url 					  = $this->config->item('get_base_url');
		//echo $this->config->item('get_base_url');
		/*echo '<pre>';
		print_r($product);
		echo '</pre>';*/
		/*$this->load->library('ciqrcode');
		foreach($product as $index => $value){
			if($index > 1000){
				$product_slug = str_replace('/','',$value['product_slug']);
				if(!file_exists('assets/qr/'.$product_slug)){
					$qr['data'] = $base_url.'product/'.$product_slug;
					$qr['level'] = 'H';
					$qr['size'] = 10;
					$qr['savename'] = $qr_folder.$product_slug.'.png';
					$this->ciqrcode->generate($qr);
				}
			}
		}*/

		//echo str_replace(base_url('tag').'/','','https://fatih5.app-show.net/gadingkostum/tag/lak0001-5');

		//exit;
		$product = $this->backend_model->getProductNamaForQr();
		$qr_folder 					  = FCPATH."assets/qr/tag/";
		
		$this->load->library('ciqrcode');
		foreach($product as $index => $value){

			if($index > 2000){
			$url = $base_url.'tag/'.url_title($value['product_kode'].'-'.$value['product_sizestock_id'],'-',true);

			$filename = url_title($value['product_kode'].'-'.$value['product_sizestock_id'],'-',true);

				if(!file_exists('assets/qr/tag/'.$filename.'.png')){
				$qr['data'] 		= $url;
				$qr['level'] 		= 'L';
				$qr['size'] 		= 10;
				$qr['savename'] 	= $qr_folder.$filename.'.png';
				$this->ciqrcode->generate($qr);
				}
			}
		}
	}

	public function getRangeHuruf(){
		$kode   			= $this->backend_model->getProductKode();
		$data['huruf'] 		= array();
		foreach($kode as $index => $value){
			$huruf 	= substr($value['product_kode'], 0,3);
			$data['huruf'][$huruf] 		= $huruf;
		}
		$data['huruf']   = array_values($data['huruf']);
		return $data;
	}

	public function getRangeAngka(){
		$kode   		    = $this->backend_model->getProductKode();
		$data 		= array();
		foreach($kode as $index => $value){
			$huruf 	= substr($value['product_kode'], 0,3);
			$angka 	= substr($value['product_kode'], 3,7);
			$data[$huruf][] 	= $angka;
		}
		return $data;
	}

	public function getKodeByAngka(){
		$huruf 		= $this->input->post('huruf');
		$rangeKode 	= $this->getRangeAngka();
		$result     = (isset($rangeKode[$huruf])) ? $rangeKode[$huruf] : array();
		echo json_encode($result);
	}

	public function tes(){
		$first  = $this->input->post('angka_first');
		$last   = $this->input->post('angka_last');
		$kode 	= array();
		$result = array();
		if(!empty($first) && !empty($last)){
			for ($n = $first; $n <= $last; $n++) {
				$kode[] = sprintf("%04d",$n);
			}
		}
		if(!empty($kode)){
			$result = $this->backend_model->getProductForCatalog1($kode);
		}
		echo json_encode($result);
	}

	public function index(){
		$data['session_items']  = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  		= 'Katalog & Tag';
		$kode   				= $this->backend_model->getProductKode();
		$data['rangekode'] 		= $this->getRangeHuruf();
		$data['tag'] 			= array();
		/*$id = 1487;
		$q 	= $this->backend_model->getExampleProductCatalog($id);
		echo '<pre>';
		print_r($q);
		echo '</pre>';
		exit;*/
		$id 			   		= array(1487,298,299,1,2,3,4,5,6,7,10,11,12,13,14,15,16);
		$tempTag 				= array();
		$curtag 				= array();
		$tag 					= array();
		$get  	   				= $this->backend_model->getProductForCatalog($id);

		if(!empty($get)){
			foreach($get as $index => $value){
				if(is_array($value['sizestock']) && !empty($value['sizestock'])){
					foreach($value['sizestock'] as $key => $row){
						$replacing 										= array('Anak','anak','Dewasa','dewasa','Tahun','tahun');
						$product_size 									= str_replace($replacing,'',$row['product_size']); 
						unset($get[$index]['sizestock'][$key]['product_size']);
						$get[$index]['sizestock'][$key]['product_size'] = trim($product_size);
					}
				}
			}
			foreach($get as $index => $value){
				if(is_array($value['sizestock'])){
					foreach($value['sizestock'] as $key => $row){
						for ($i=1; $i <= $row['product_stock']; $i++) {
							$tempTag[] 		    = $value;
							$curtag[]['current']= $row;
						}
					}
				}
			}
		}

		if(!empty($tempTag)){
			foreach($tempTag as $index => $value){
				if(isset($curtag[$index])){
					$tempTag[$index]['qr'] = 'assets/qr/tag/'.strtolower($value['product_kode']).'-'.$curtag[$index]['current']['product_sizestock_id'].'.png';
				}
				$tag[$index] = array_merge($tempTag[$index],$curtag[$index]);
			}
		}
		$data['tag'] 	   = array_chunk($tag,3);
		$data['get'] 	   = array_chunk($get,3);
		$data['template']  = $this->templateKatalog($data['get']);
		$data['product']   = $this->backend_model->getProductListForCatalog();
		$data['load_view'] = 'adminsite/v_catalog';
		$this->load->view('adminsite/template/backend', $data);
	}

	public function test1(){
		$angka_first = "0001";
		$angka_last  = "0826";

		for ($i=$angka_first; $i <= $angka_last ; $i++) { 
			echo sprintf("%04d",$i);
			echo '<br>';
		}
	}
	
	public function formprint(){
		$huruf 			= $this->input->post('huruf');
		$angka_first 	= $this->input->post('angka_first');
		$angka_last 	= $this->input->post('angka_last');
		$type    		= $this->input->post('type');
		$qty 			= $this->input->post('qty');
		$get_product 	= $this->input->post('product');

		$kode 			= array();
		$data 			= array();
		$callback       = false;

		if(!empty($angka_first) && !empty($angka_last)){
			for ($i=$angka_first; $i <= $angka_last ; $i++) { 
				$kode[] = strtoupper($huruf).sprintf("%04d",$i);
			}
		} elseif(!empty($angka_first) && empty($angka_last)) {
			$kode[] = strtoupper($huruf).sprintf("%04d",$angka_first);
		} elseif(empty($angka_first) && !empty($angka_last)) {
			$kode[] = strtoupper($huruf).sprintf("%04d",$angka_last);
		}

		if(!empty($kode) || !empty($get_product)){
			$data 			= $this->backend_model->getProductByKode($kode,$get_product);
			if(!empty($data)){
				foreach($data as $index => $value){
					if(is_array($value['sizestock']) && !empty($value['sizestock'])){
						foreach($value['sizestock'] as $key => $row){
							$replacing 		= array('Anak','anak','Dewasa','dewasa','Tahun','tahun');
							$product_size 	= str_replace($replacing,'',$row['product_size']); 
							unset($data[$index]['sizestock'][$key]['product_size']);
							$data[$index]['sizestock'][$key]['product_size'] = $product_size;
						}
					}
				}
				$callback = true;
			}
		}
		$template 			= '';
		switch ($type) {
			case 'all':
			$template 		= $this->templateKatalogTag($data);
			break;
			case 'katalog':
			$katalog 		= array_chunk($data,3);
			$template 		= $this->templateKatalog($katalog);
			break;
			case 'tag':

			$tempTag 				= array();
			$curtag 				= array();
			$tag 					= array();
			if(!empty($data)){
				foreach($data as $index => $value){
					if(is_array($value['sizestock'])){
						foreach($value['sizestock'] as $key => $row){
							for ($i=1; $i <= $row['product_stock']; $i++) {
								if(isset($qty) && $i == $qty){ 
									$tempTag[] 		    = $value;
									$curtag[]['current']= $row;
								} elseif(!isset($qty)) {
									$tempTag[] 		    = $value;
									$curtag[]['current']= $row;
								}
							}
						}
					}
				}
				$callback = true;
			}

			if(!empty($tempTag)){
				foreach($tempTag as $index => $value){
					if(isset($curtag[$index])){
						$tempTag[$index]['qr'] = 'assets/qr/tag/'.strtolower($value['product_kode']).'-'.$curtag[$index]['current']['product_sizestock_id'].'.png';
					}
					$tag[$index] = array_merge($tempTag[$index],$curtag[$index]);
				}
			}
			$tag 			= array_chunk($tag,5);
			$template 		= $this->templateTag($tag);
			break;
		}
		$result  = array(
			'type' 			=> $type,
			'data' 			=> $data,
			'template'		=> $template,
			'kode'			=> $kode,
			'get_product'	=> $get_product,
			'callback'		=> $callback
		);
		echo json_encode($result);
	}

	public function templateTag($get = ''){
		$template = '';
		if(!empty($get)){
			$no=1;
			$numItems = count($get);
			$ii 	  = 0;
			foreach($get as $i => $val){

				$template .= '<div class="container container-list container-tag">';
				//$template .= '<div class="row">';

				foreach($val as $index => $value){ 
					$crop 		  = '';
					$product_slug = str_replace('/','',$value['product_slug']).'.png';
					$image        = $value['image'];
					if(!file_exists('assets/qr/'.$product_slug)){
						$product_slug = 'no-thumbnail.png';
					} 
					if(!file_exists($value['image'])){
						$image      = 'assets/images/no-thumbnail.png';
					}
					if($value['product_crop'] == 1){
						$crop 		= 'no-scale';
					}

					//$ukuran    = (isset($value['current'])) ? $value['current']['product_size'].' ('.$value['current']['product_stock'].' Set)' : '';
					$ukuran    = (isset($value['current'])) ? $value['current']['product_size'] : '';

					$template .= '<div class="container-box-list tag-box-list">';
					$template .= '<div class="wraper-box-list tag">';
					$template .= '<div class="main-catalog">';
					$template .= '<div class="main-footer-catalog tag-image">';
					$template .= '<img src="'.base_url($image).'" class="img-responsive" style="transform: scale('.$value['product_scale'].');">';
					$template .= '</div>';
					$template .= '</div>';
					$template .= '</div>';

					$template .= '<div class="wraper-box-list tag">';
					$template .= '<div class="main-catalog">';
					$template .= '<div class="main-header-catalog">';
					$template .= '<div class="content">';

					$template .= '<div class="title"><b>'.$value['product_nama'].'</b></div>';

					$template .= '<table class="table">';
					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Kode</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.$value['product_kode'].'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';

					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Ukuran</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.$ukuran.'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';

					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Harga Sewa</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.number_format($value['product_hargasewa']).'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';

					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Jaminan</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.number_format($value['product_deposit']).'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';
					$template .= '</table>';

					$template .= '</div>';

					$template .= '<div class="qr">';
					$template .= '<img src="'.base_url($value['qr']).'?cache=1.0" class="img-responsive">';
					$template .= '</div>';

					$template .= '</div>';

					$template .= '<div class="main-body-catalog">';
					$template .= '<div class="subtitle"><b>Isi Paket:</b></div>';
					$template .= '<div class="item-content">';
					$product_isipaket = explode("\n",$value['product_isipaket']);
					if(is_array($product_isipaket)){
						$template .= '<ul>';
						foreach($product_isipaket as $k => $r){ 
							$template .= '<li>'.$r.'</li>';
						}
						$template .= '</ul>';
					}
					$template .= '</div>';
					$template .= '</div>';

					$template .= '</div>';
					$template .= '</div>';
					$template .= '</div>';
				}
				//$template .= '</div>';
				$template .= '</div>';
				if($no%1==0){
					$template .= '<div class="pagebreak"></div>';
				}
				$no++;
			}
		}
		return $template;
	}

	public function templateKatalog($get = ''){
		$template = '';
		if(!empty($get)){
			$no=1;
			foreach($get as $i => $val){

				$template .= '<div class="container container-list">';
				$template .= '<div class="row">';

				foreach($val as $index => $value){ 
					$product_slug = str_replace('/','',$value['product_slug']).'.png';
					$image        = $value['image'];
					if(!file_exists('assets/qr/'.$product_slug)){
						$product_slug = 'no-thumbnail.png';
					} 
					if(!file_exists($value['image'])){
						$image      = 'assets/images/no-thumbnail.png';
					}

					$template .= '<div class="container-box-list col-xs-5">';
					$template .= '<div class="wraper-box-list col-xs-5">';
					$template .= '<div class="main-catalog">';
					$template .= '<div class="main-header-catalog">';
					$template .= '<div class="content">';

					$template .= '<div class="title"><b>'.$value['product_nama'].'</b></div>';

					$template .= '<table class="table">';
					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Kode</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.$value['product_kode'].'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';

					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Harga Sewa</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.number_format($value['product_hargasewa']).'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';

					$template .= '<tr>';
					$template .= '<td class="first">';
					$template .= '<div class="item-content">Jaminan</div>';
					$template .= '</td>';
					$template .= '<td class="second">';
					$template .= '<div class="item-content">:</div>';
					$template .= '</td>';
					$template .= '<td>';
					$template .= '<div class="item-content"><b>'.number_format($value['product_deposit']).'</b></div>';
					$template .= '</td>';
					$template .= '</tr>';
					$template .= '</table>';

					$template .= '</div>';

					$template .= '<div class="qr">';
					$template .= '<img src="'.base_url('assets/qr').'/'.$product_slug.'" class="img-responsive">';
					$template .= '</div>';

					$template .= '</div>';

					$template .= '<div class="main-body-catalog">';
					$template .= '<div class="content">';
					$template .= '<div class="title">Ukuran:</div>';
					$template .= '<div class="item-content">';
					if(isset($value['sizestock']) && !empty($value['sizestock'][0])){
						$template .= '<ul>';
						foreach($value['sizestock'] as $key => $row){
							$template .= '<li>'.$row['product_size'].' &nbsp;('.$row['product_stock'].' Set)</li>';
						}
						$template .= '</ul>';
					}
					$template .= '</div>';
					$template .= '</div>';
					$template .= '</div>';

					$template .= '<div class="main-body-catalog">';
					$template .= '<div class="title">Isi Paket:</div>';
					$template .= '<div class="item-content">';
					$product_isipaket = explode("\n",$value['product_isipaket']);
					if(is_array($product_isipaket)){
						$template .= '<ul>';
						foreach($product_isipaket as $k => $r){ 
							$template .= '<li>'.$r.'</li>';
						}
						$template .= '</ul>';
					}
					$template .= '</div>';
					$template .= '</div>';

					$template .= '<div class="main-footer-catalog">';
					$template .= '<img src="'.base_url($image).'" class="img-responsive">';
					$template .= '</div>';

					$template .= '</div>';
					$template .= '</div>';
					$template .= '</div>';
				}
				$template .= '</div>';
				$template .= '</div>';
				if($no%2==0){
					$template .= '<div class="pagebreak"></div>';
				} 
				$no++;
			}
		}
		return $template;
	}

	public function templateKatalogTag($data = ''){
		$template = '';
		if(!empty($data)){
			foreach($data as $i => $val){
				$template .= '<div class="container container-list">';
				$template .= '<div class="row">';
				$no = 0;
				foreach($val as $index => $value){ 
					$product_slug = str_replace('/','',$value['product_slug']).'.png';
					if(!file_exists('assets/qr/'.$product_slug)){
						$product_slug = 'no-thumbnail.png';
					}
					$template .= '<div class="container-box-list col-xs-5">';

					$template .= '<div class="wraper-box-list col-xs-5">';
					$template .= '<div class="main">';
					$template .= '<div class="wrapper-qr">';
					$template .= '<img src="'.base_url('assets/qr').'/'.$product_slug.'" class="img-responsive">';
					$template .= '</div>';
					$template .= '<div class="wrapper-title">';
					$template .= '<div class="title">'.$value['product_nama'].'</div>';
					$template .= '</div>';
					$template .= '<div class="wrapper-detail">';
					$template .= '<div class="detail">';
					$template .= '<div class="main-detail">Kode :&nbsp;</div>';
					$template .= '<div class="main-detail">'.$value['product_kode'].'</div>';
					$template .= '</div>';

					$template .= '<div class="detail">';
					$template .= '<div class="main-detail">Ukuran :&nbsp;</div>';
					if(isset($value['sizestock']) && !empty($value['sizestock'][0])){
						$template .= '<div class="main-detail">';
						$product_size = array();
						foreach($value['sizestock'] as $key => $row){
							$product_size = explode("\n",$row['product_size']);
						}
						if(is_array($product_size)){
							$template .= '<span>'.implode(', ', $product_size).'</span>';
						}
						$template .= '</div>';
					}
					$template .= '</div>';

					$template .= '<div class="detail">';
					$template .= '<div class="main-detail">Harga Sewa :&nbsp;</div>';
					$template .= '<div class="main-detail">'.number_format($value['product_hargasewa']).'</div>';
					$template .= '</div>';

					$template .= '<div class="detail">';
					$template .= '<div class="main-detail">Jaminan :&nbsp;</div>';
					$template .= '<div class="main-detail">'.number_format($value['product_deposit']).'</div>';
					$template .= '</div>';
					$template .= '</div>';

					$template .= '<div class="wrapper-paket">';
					$template .= '<div class="main-paket title">Isi Paket :&nbsp;</div>';
					$template .= '<div class="main-paket">'; 
					$product_isipaket = explode("\n",$value['product_isipaket']);
					if(is_array($product_isipaket)){
						foreach($product_isipaket as $k => $r){
							$template .= '<span>'.$r.'</span>';
						}
					}
					$template .= '</div>';
					$template .= '</div>';

					$template .= '<div class="wrapper-image">';
					$template .= '<img src="'.base_url($value['image']).'" class="img-responsive">';
					$template .= '</div>';
					$template .= '</div>';
					$template .= '</div>';
					$template .= '<div class="tag col-xs-5">';
					$template .= '<div class="tag-image">';
					$template .= '<img src="'.base_url($value['image']).'" class="img-responsive">';
					$template .= '</div>';
					$template .= '</div>';

					$template .= '</div>';
				}
				$template .= '<div class="pagebreak"> </div>';
			}
		}
		return $template;
	}

	public function add(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Add New article';

		$this->form_validation->set_rules('article_title','Title','trim|required');
		$this->form_validation->set_rules('article_image','Image','trim');
		$this->form_validation->set_rules('article_description_thumbnail','Description Thumbnail','trim');
		$this->form_validation->set_rules('article_description','Description','trim');
		$this->form_validation->set_rules('article_metatitle','Meta Title','trim');
		$this->form_validation->set_rules('article_metakeyword','Meta Keyword','trim');
		$this->form_validation->set_rules('article_metadescription','Meta Description','trim');
		$this->form_validation->set_rules('status','Status','trim');

		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',json_encode(validation_errors()));
			}
			$data['load_view'] = 'adminsite/v_article_add';
			$this->load->view('adminsite/template/backend', $data);

		} else {

			$article_title 						= $this->input->post('article_title');
			$article_image 						= $this->input->post('article_image');
			$article_description_thumbnail 		= $this->input->post('article_description_thumbnail');
			$article_description 				= $this->input->post('article_description');
			$article_metatitle 					= $this->input->post('article_metatitle');
			$article_metakeyword				= $this->input->post('article_metakeyword');
			$article_metadescription			= $this->input->post('article_metadescription');
			$article_status 					= $this->input->post('status');

			if(empty($article_status)){
				$article_status 	= 0;
			}

			$insert_article = array(
				'article_title'						=> $article_title,
				'article_image'						=> str_replace(base_url(),'',$article_image),
				'article_description'				=> $article_description,
				'article_description_thumbnail'		=> $article_description_thumbnail,
				'article_metatitle'					=> $article_metatitle,
				'article_metakeyword'				=> $article_metakeyword,
				'article_metadescription'			=> $article_metadescription,
				'article_status'					=> $article_status,
				'article_created'					=> date('c'),
				'article_modified'					=> NULL
			);

			$result_article = $this->global_model->insert('article',$insert_article);
			$id 			= $this->db->insert_id();
			
			if(empty($article_slug)){
				$slug 		= $this->backend_model->createSlug('article','article_id',$id,'article_slug',$article_title);
			}

			$insert_slug = array(
				'article_slug' => $slug
			);
			$this->global_model->update('article',$insert_slug,array('article_id' => $id));

			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/article','refresh');
		}

	}

	public function edit(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Edit Article';
		$id 			= $this->uri->segment(4);

		$data['id'] 	= $id;

		$data['article']				= $this->global_model->select_where('article',array('article_id' => $id));

		$this->form_validation->set_rules('article_title','Title','trim|required');
		$this->form_validation->set_rules('article_image','Image','trim');
		$this->form_validation->set_rules('article_description_thumbnail','Description Thumbnail','trim');
		$this->form_validation->set_rules('article_description','Description','trim');
		$this->form_validation->set_rules('article_metatitle','Meta Title','trim');
		$this->form_validation->set_rules('article_metakeyword','Meta Keyword','trim');
		$this->form_validation->set_rules('article_metadescription','Meta Description','trim');
		$this->form_validation->set_rules('status','Status','trim');

		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',json_encode(validation_errors()));
			}
			$data['load_view'] = 'adminsite/v_article_edit';
			$this->load->view('adminsite/template/backend', $data);

		} else {
			$article_title 						= $this->input->post('article_title');
			$article_image 						= $this->input->post('article_image');
			$article_description_thumbnail 		= $this->input->post('article_description_thumbnail');
			$article_description 				= $this->input->post('article_description');
			$article_metatitle 					= $this->input->post('article_metatitle');
			$article_metakeyword				= $this->input->post('article_metakeyword');
			$article_metadescription			= $this->input->post('article_metadescription');
			$article_status 					= $this->input->post('status');

			if(empty($article_status)){
				$article_status 	= 0;
			}

			$slug 		= $this->backend_model->createSlug('article','article_id',$id,'article_slug',$article_title);

			$update_article = array(
				'article_title'						=> $article_title,
				'article_image'						=> str_replace(base_url(),'',$article_image),
				'article_description'				=> $article_description,
				'article_description_thumbnail'		=> $article_description_thumbnail,
				'article_metatitle'					=> $article_metatitle,
				'article_metakeyword'				=> $article_metakeyword,
				'article_metadescription'			=> $article_metadescription,
				'article_status'					=> $article_status,
				'article_slug'						=> $slug,
				'article_modified'					=> date('c')
			);

			$result = $this->global_model->update('article',$update_article,array('article_id' => $id));

			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/article/edit/'.$id);
		}

	}

	public function delete(){

		$id = $this->input->post('category_id');

		$delete = $this->global_model->delete('article',array('article_id' => $id));
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

					'article_status' 		=> 1,
					'article_modified' 	=> date('c')

				);

				$update = $this->global_model->update('article',$data,array('article_id' => $id[$i]));

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
					'article_status' 		=> 0,
					'article_modified' 		=> date('c')
				);

				$update = $this->global_model->update('article',$data,array('article_id' => $id[$i]));

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

				$delete = $this->global_model->delete('article',array('article_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function datatables(){
		$order_array = array(
			'article_created' => 'desc'
		);
		$query 		= $this->backend_model->get_join_by_id('article','','*','',$order_array);				
		$data = array();
		if(!empty($query)){
			foreach($query->result() as $index => $value) {
				$status = '';

				if($value->article_status == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}

				$action = '';
				$action = '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value->article_id.'" href="'.base_url('adminsite/article/edit/').$value->article_id.'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value->article_id.'" data-url="'.base_url('adminsite/article/delete').'">Delete</a>';

				if(!empty($value->article_image)){
					$image  = '<img class="img-thumbnail" src="'.$value->article_image.'">';
				} else {
					$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
				}

				$data[] = array(
					'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->article_id.'"></div>',
					$value->article_title,
					$image,
					$status,
					$action
				);

			}
		}
		$result = $this->custom_lib->datatables_data($query,$data);
		echo json_encode($result);
	}
}

?>