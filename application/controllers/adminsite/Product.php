<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller{

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

	public function update_url_image(){
		$query = $this->global_model->select('product_image');

		$product_image = array();

		$updated = false;
		foreach($query as $index => $value){
			$product_thumbnail = str_replace('/upload/product/','/upload/thumbnail/images/',$value['product_image']);

			if(!file_exists($product_thumbnail)){
				$update_image = array(
						'product_image' => $product_thumbnail
					);
				//$updated = $this->global_model->update('product_image',$update_image,array('product_image_id' => $value['product_image_id']));

			} else {

				$update_image_thumbnail = array(
						'product_image' => $product_thumbnail
					);
				//$updated = $this->global_model->update('product_image',$update_image_thumbnail,array('product_image_id' => $value['product_image_id']));
			}
		}
		if($updated){
			echo 'sukses';
		}
	}

	public function datatables_order(){

		$get                    = $this->input->post('geturl');
		if(!empty($get)){
			$get                = unserialize(base64_decode($get));
		}
		$status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
		$searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
		$store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
		$order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
		$order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';

		$draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
		$start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordWithFilter  = 0;

        $totalRecords           = $this->backend_model->countAllProductData($searchValue);
        $totalRecordwithFilter  = $totalRecords;
        $empRecords             = $this->backend_model->AllProductData($searchValue,$order,$order_by,$start,$offset);
        $data 					= array();

        if(!empty($empRecords)){
        	foreach($empRecords as $index => $value) {

        		$query_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));
        		$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
        		if(!empty($query_image)){
        			if (file_exists($query_image[0]['product_image'])) {
        				$image  = '<img class="img-thumbnail" src="'.str_replace('upload/images','upload/thumbnail/images',$query_image[0]['product_image']).'">';
        			}
        		}

        		$status = '';
        		if($value['product_status'] == 1){
        			$status = '<span class="label label-success">Yes</span>';
        		} else {
        			$status = '<span class="label label-danger">No</span>';
        		}

        		$product_modified = $value['product_modified'];

        		if(!empty($product_modified)){
        			$product_modified = date('d-M-Y',strtotime($value['product_modified']));
        		} else {
        			$product_modified = ' - ';
        		}

        		$action = '';
        		$action .= '<a class="btn-edit-action btn btn-warning btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/duplicate/').$value['product_id'].'" style="margin-right: 5px;">Duplicate</a>';
        		$action .= '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/edit/').$value['product_id'].'" style="margin-right: 5px;">Edit</a>';
        		$action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-sm btn-flat" data-item="'.$value['product_id'].'" data-url="'.base_url('adminsite/product/trash').'">Trash</a>';

        		$data[] = array(
        			'checkbox' => '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['product_id'].'"></div>',
        			'image' => $image,
        			'product_nama' => $value['product_nama'],
        			'product_kode' => $value['product_kode'],
        			'product_modified' => $product_modified,
        			'status' => $status,
        			'action' => $action
        			);
        	}
        }

        $response = array(
        	"draw" => intval($draw),
        	"iTotalRecords" => $totalRecordwithFilter,
        	"iTotalDisplayRecords" => $totalRecords,
        	"aaData" => $data
        	);
        echo json_encode($response);
    }

    public function datatables_new_trash(){

        $get                    = $this->input->post('geturl');
		if(!empty($get)){
			$get                = unserialize(base64_decode($get));
		}

        $status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
		$searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
		$store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
		$order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
		$order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';

		$draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
		$start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $totalRecords           = 0;
		$totalRecordWithFilter  = 0;
		
        $totalRecords           = $this->backend_model->countAllProductDataTrash($searchValue);
        $totalRecordwithFilter  = $totalRecords;
        $empRecords             = $this->backend_model->AllProductDataTrash($searchValue,$order,$order_by,$start,$offset);
        $data = array();

        $data_duepickup     = array();
        $data_duereturn     = array();

        if(!empty($empRecords)){
			foreach($empRecords as $index => $value) {

				$query_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));
				$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
				if(!empty($query_image)){
					if (file_exists($query_image[0]['product_image'])) {
						$image  = '<img class="img-thumbnail" src="'.str_replace('upload/images','upload/thumbnail/images',$query_image[0]['product_image']).'">';
					}
				}

				$status = '';
				if($value['product_status'] == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}

				$product_modified = $value['product_modified'];

				if(!empty($product_modified)){
					$product_modified = date('d-M-Y',strtotime($value['product_modified']));
				} else {
					$product_modified = ' - ';
				}

				$action = '';
				$action .= '<a class="btn-edit-action btn btn-warning btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/duplicate/').$value['product_id'].'" style="margin-right: 5px;">Duplicate</a>';
				$action .= '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/edit/').$value['product_id'].'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-sm btn-flat" data-item="'.$value['product_id'].'" data-url="'.base_url('adminsite/product/trash').'">Trash</a>';

				$data[] = array(
					'checkbox' => '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['product_id'].'"></div>',
					'image' => $image,
					'product_nama' => $value['product_nama'],
					'product_kode' => $value['product_kode'],
					'product_modified' => $product_modified,
					'status' => $status,
					'action' => $action
					);
			}
		}

        $response = array(
          "draw" => intval($draw),
          "post" => $_POST,
          "iTotalRecords" => $totalRecordwithFilter,
          "iTotalDisplayRecords" => $totalRecords,
          "aaData" => $data
          );
        echo json_encode($response);
    }

	public function index(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  			  = 'Product';
		$config = array(
			array(
				'field' => 'search',
				'label' => 'Search',
				'rules' => 'trim'
				),
			);

		$this->form_validation->set_rules($config);

		$data['geturl']                 = $this->input->get(null,true);
		$data['table_data']			  = 'product'; // element id table
		$data['ajax_sort_url'] 		  = 'adminsite/product/order'; // Controller row order data
		$data['ajax_data_table']	  = 'adminsite/product/datatables_order'; //Controller ajax data
		$data['count_product'] 		  = $this->backend_model->product_active();
		$data['count_product_trash']  = $this->backend_model->product_active(0);
		$data['datatables_ajax_data'] = array(
			$this->custom_lib->datatables_ajax_product(TRUE,$data['table_data'],$data['ajax_data_table'])
			//$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])
			);
        //View
		$data['load_view'] = 'adminsite/v_product';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function view_trash(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  			  = 'Product';
		$config = array(
			array(
				'field' => 'search',
				'label' => 'Search',
				'rules' => 'trim'
				),
			);

		$this->form_validation->set_rules($config);

		$data['geturl']                 = $this->input->get(null,true);
		$data['table_data']			  = 'product'; // element id table
		$data['ajax_sort_url'] 		  = 'adminsite/product/order'; // Controller row order data
		$data['ajax_data_table']	  = 'adminsite/product/datatables_new_trash'; //Controller ajax data
		$data['count_product'] 		  = $this->backend_model->product_active();
		$data['count_product_trash']  = $this->backend_model->product_active(0);
		$data['datatables_ajax_data'] = array(
			$this->custom_lib->datatables_ajax_product(TRUE,$data['table_data'],$data['ajax_data_table'])
			//$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])
			);
        //View
		$data['load_view'] = 'adminsite/v_product';
		$this->load->view('adminsite/template/backend', $data);

	}

	/*public function form(){
		$this->form_validation->set_rules('category_id','Category','trim');
		$this->form_validation->set_rules('category_name','Size Category','trim|required');
		$this->form_validation->set_rules('category_value_textarea','Size Measurement','trim');
		$this->form_validation->set_rules('status','Category Status','trim');

		if($this->form_validation->run() == false){

			$return = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
				);

		} else {

			$category_id 				= $this->input->post('category_id');
			$category_name 				= $this->input->post('category_name');
			$category_value_textarea 	= $this->input->post('category_value_textarea');
			$category_flag				= 'size';
			$category_status 			= $this->input->post('status');

			if(empty($category_status)){
				$category_status = 0;
			}

			if(empty($category_id)){

				$insert = array(
					'category_name'				=> $category_name,
					'category_value_textarea'	=> $category_value_textarea,
					'category_flag'				=> $category_flag,
					'category_status'			=> $category_status,
					'category_created'			=> date('c'),
					'category_modified'			=> NULL
					);

				$result_insert = $this->global_model->insert('category',$insert);
				$id = $this->db->insert_id();
				$slug = $this->backend_model->createSlug('category','category_id',$id,'category_slug',$category_name);
				$insert_slug = array(
					'category_slug' => $slug
					);
				$this->global_model->update('category',$insert_slug,array('category_id' => $id));
				$return = array('flag'=>true, 'process' => 'insert');

			} else {

				$slug = $this->backend_model->createSlug('category','category_id',$category_id,'category_slug',$category_name);
				$update = array(
					'category_name'				=> $category_name,
					'category_value_textarea'	=> $category_value_textarea,
					'category_flag'				=> $category_flag,
					'category_status'			=> $category_status,
					'category_modified'			=> date('c')
					);
				$updated = $this->global_model->update('category',$update,array('category_id' => $category_id));
				$return = array('flag'=>true, 'process' => 'update');
			}

		}

		echo json_encode($return);

	}*/

	public function add(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Add New Product';
		$product 		= array('category_flag' => 'product');
		$size 			= array('category_flag' => 'size');
		$gender 		= array('category_flag' => 'gender');
		$store_location = array('category_flag' => 'store_location');
		$order 			= array('category_name' => 'asc');
		$qr_folder 		= FCPATH."assets/qr/";
		$tag_folder 	= FCPATH."assets/qr/tag/";
		$base_url 	    = $this->config->item('get_base_url');

		$this->load->library('ciqrcode');
		//PRODUCT
		$data['product_related'] = $this->global_model->select_order('product','product_nama','asc');
		//CATEGORY
		$data['product'] 		 = $this->backend_model->get_join_by_id('category',$product,'','',$order);
		$data['size'] 			 = $this->backend_model->get_join_by_id('category',$size,'','',$order);
		$data['gender'] 		 = $this->backend_model->get_join_by_id('category',$gender,'','',$order);
		$data['store_location']  = $this->backend_model->get_join_by_id('category',$store_location,'','',$order);

		$this->form_validation->set_rules('product_nama','Nama Produk','trim|required');
		$this->form_validation->set_rules('product_kode','Kode','trim');
		$this->form_validation->set_rules('product_image_id','Picture ID','trim');
		$this->form_validation->set_rules('product_image','Picture','trim');
		$this->form_validation->set_rules('product_deskripsi','Deskripsi','trim');
		$this->form_validation->set_rules('product_isipaket','Isi Paket','trim');
		$this->form_validation->set_rules('product_hargasewa','Harga Sewa','trim');
		$this->form_validation->set_rules('product_deposit','Deposit','trim');
		$this->form_validation->set_rules('product_metatitle','Meta Title','trim');
		$this->form_validation->set_rules('product_metakeyword','Meta Keyword','trim');
		$this->form_validation->set_rules('product_metadescription','Meta Description','trim');
		$this->form_validation->set_rules('product_featured','Featured Product','trim');
		$this->form_validation->set_rules('product_slug','Url','trim');
		$this->form_validation->set_rules('status','Product Status','trim');

		//SIZE + STOCK
		$this->form_validation->set_rules('product_sizestock_id','Size Stock ID','trim');
		$this->form_validation->set_rules('product_size','Size','trim');
		$this->form_validation->set_rules('product_stock','Stock','trim');
		$this->form_validation->set_rules('product_estimasiukuran','Estimasi Ukuran','trim');

		//CATEGORY PRODUCT SIZE GENDER & STORE
		$this->form_validation->set_rules('category_id','Category','trim');
		$this->form_validation->set_rules('product_scale','Product Scale','trim');
		//RELATED
		$this->form_validation->set_rules('product_related','Product','trim');
		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',json_encode(validation_errors()));
			}
			$data['load_view'] = 'adminsite/v_product_add';
			$this->load->view('adminsite/template/backend', $data);

		} else {
			//PRODUCT
			$product_nama 				= $this->input->post('product_nama');
			$product_kode 				= $this->input->post('product_kode');
			$product_deskripsi 			= $this->input->post('product_deskripsi');
			$product_isipaket 			= $this->input->post('product_isipaket');
			$product_hargasewa 			= $this->input->post('product_hargasewa');
			$product_deposit 			= $this->input->post('product_deposit');
			$product_metatitle 			= $this->input->post('product_metatitle');
			$product_metakeyword 		= $this->input->post('product_metakeyword');
			$product_metadescription 	= $this->input->post('product_metadescription');
			$product_slug 				= $this->input->post('product_slug');
			$product_status 			= $this->input->post('status');
			$product_featured 			= $this->input->post('product_featured');
			//STOCK + SIZE
			$product_sizestock_id 		= $this->input->post('product_sizestock_id');
			$product_size 				= $this->input->post('product_size');
			$product_stock 				= $this->input->post('product_stock');
			$product_estimasiukuran 	= $this->input->post('product_estimasiukuran');

			//IMAGE
			$product_image_id 			= $this->input->post('product_image_id');
			$product_image 				= $this->input->post('product_image');

			//CATEGORY PRODUCT SIZE GENDER & STORE
			$category_id 				= $this->input->post('category_id');

			//PRODUCT CATEGORY
			$product_category_id 		= $this->input->post('product_category_id');

			//SIZE CATEGORY
			$size_category_id 			= $this->input->post('size_category_id');

			//GENDER CATEGORY
			$gender_category_id 		= $this->input->post('gender_category_id');

			//STORE LOCATION
			$store_category_id 			= $this->input->post('store_category_id');

			//RELATED
			$product_related 			= $this->input->post('product_related');
			if(empty($product_status)){
				$product_status 	= 0;
			}
			if(empty($product_featured)){
				$product_featured 	= 0;
			}

			$product_scale 				= $this->input->post('product_scale');
			//PRODUCT
			$insert_product = array(
				'product_nama'				=> $product_nama,
				'product_kode'				=> $product_kode,
				'product_deskripsi'			=> htmlentities($product_deskripsi),
				'product_isipaket'			=> htmlentities($product_isipaket),
				'product_hargasewa'			=> preg_replace("/[^0-9\.]/","",$product_hargasewa),
				'product_deposit'			=> preg_replace("/[^0-9\.]/","",$product_deposit),
				'product_metatitle'			=> $product_metatitle,
				'product_metakeyword'		=> $product_metakeyword,
				'product_metadescription'	=> $product_metadescription,
				'product_featured' 			=> $product_featured,
				'product_status'			=> $product_status,
				'product_active'			=> 1,
				'product_created'			=> date('c'),
				'product_modified'			=> NULL,
				'product_scale'				=> $product_scale
				);

			$result_product = $this->global_model->insert('product',$insert_product);
			$id 			= $this->db->insert_id();
			
			if(empty($product_slug)){
				$slug 		= $this->backend_model->createSlug('product','product_id',$id,'product_slug',$product_nama);
			} else {
				$check_slug = $this->backend_model->check_slug_product($product_slug);
				if($check_slug){
					$slug 		= $this->backend_model->createSlug('product','product_id',$id,'product_slug',$product_nama);
				} else {
					$slug 		= $product_slug;
				}
			}
			$insert_slug = array(
				'product_slug' => $slug
				);
			$this->global_model->update('product',$insert_slug,array('product_id' => $id));

			$qr_slug = str_replace('/','',$slug);
			if(!file_exists('assets/qr/'.$qr_slug)){
				$qr['data'] = $base_url.'product/'.$qr_slug;
				$qr['level'] = 'H';
				$qr['size'] = 10;
				$qr['savename'] = $qr_folder.$qr_slug.'.png';
				$this->ciqrcode->generate($qr);
			}

			//IMAGE
			if(is_array($product_image_id) && !empty($product_image_id)){
				foreach($product_image_id as $index => $value){
					$insert_product_image = array(
						'product_id'				=> $id,
						'product_image'				=> str_replace(base_url(),'',$product_image[$index])
						);
					$result_product_image = $this->global_model->insert('product_image',$insert_product_image);
				}
			}
			//STOCK + SIZE
			if(is_array($product_sizestock_id) && !empty($product_sizestock_id)){
				foreach($product_sizestock_id as $index => $value){
					if(!empty($product_size[$index]) && !empty($product_stock[$index])){

						$product_sizestock_slug   = url_title($product_size[$index],'-',true);
						$insert_product_sizestock = array(
							'product_id'				=> $id,
							'product_size' 				=> $product_size[$index],
							'product_stock'				=> $product_stock[$index],
							'product_estimasiukuran'	=> htmlentities($product_estimasiukuran[$index]),
							'product_sizestock_sort'	=> $index,
							'product_sizestock_slug'    => $product_sizestock_slug
							);
						$result_product_sizestock 	= $this->global_model->insert('product_sizestock',$insert_product_sizestock);
						$product_sizestock_id 	    = $this->db->insert_id();

						$url = $base_url.'tag/'.url_title($product_kode.'-'.$product_sizestock_id,'-',true);
						$filename = url_title($product_kode.'-'.$product_sizestock_id,'-',true);
						if(!file_exists('assets/qr/tag/'.$filename.'.png')){
							$tag['data'] 		= $url;
							$tag['level'] 		= 'L';
							$tag['size'] 		= 10;
							$tag['savename'] 	= $tag_folder.$filename.'.png';
							$this->ciqrcode->generate($tag);
						}

					}
				}
			}

			//CATEGORY PRODUCT
			if(is_array($product_category_id) && !empty($product_category_id)){
				foreach($product_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $product_category_id[$index],
						'flag'						=> 'product',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//CATEGORY SIZE
			if(is_array($size_category_id) && !empty($size_category_id)){
				foreach($size_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $size_category_id[$index],
						'flag'						=> 'size',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//CATEGORY GENDER
			if(is_array($gender_category_id) && !empty($gender_category_id)){
				foreach($gender_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $gender_category_id[$index],
						'flag'						=> 'gender',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//CATEGORY GENDER
			if(is_array($store_category_id) && !empty($store_category_id)){
				foreach($store_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $store_category_id[$index],
						'flag'						=> 'store_location',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//RELATED
			if(is_array($product_related) && !empty($product_related)){
				foreach($product_related as $index => $value){
					$insert_product_related = array(
						'product_id'					=> $id,
						'product_related' 				=> $product_related[$index],
						);
					$result_product_related = $this->global_model->insert('product_related',$insert_product_related);
				}
			}

			if(!empty($id)){
				$get_product_category_detil = $this->backend_model->get_category_by_product($id);
				if(!empty($get_product_category_detil)){
					$filter_product 		= array();
					$filter_size 			= array();
					$filter_gender 			= array();
					$filter_store_location 	= array();
					foreach($get_product_category_detil as $index => $value){
						if(!empty($value['category_slug'])){
							if($value['flag'] == 'product'){
								$filter_product[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'size'){
								$filter_size[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'gender'){
								$filter_gender[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'store_location'){
								$filter_store_location[] = $value['category_slug'];
							}
						}
					}

					if(!empty($filter_product)){
						$got_slug = implode(',',$filter_product);
						$update = array(
							'filter_product' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_size)){
						$got_slug = implode(',',$filter_size);
						$update = array(
							'filter_size' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_gender)){
						$got_slug = implode(',',$filter_gender);
						$update = array(
							'filter_gender' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_store_location)){
						$got_slug = implode(',',$filter_store_location);
						$update = array(
							'filter_store_location' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}
				}

				$insert_popularity = array(
					'product_id'   => $id,
					'nama'		   => $product_nama,
					'kode'		   => $product_kode
				);
				$this->global_model->insert('product_popularity',$insert_popularity);
			}
			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/product');
		}

	}

	public function duplicate(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Duplicate Product';
		$id 			= $this->uri->segment(4);
		$product 		= array('category_flag' => 'product');
		$size 			= array('category_flag' => 'size');
		$gender 		= array('category_flag' => 'gender');
		$store_location = array('category_flag' => 'store_location');
		$order 			= array('category_name' => 'asc');

		$qr_folder 		= FCPATH."assets/qr/";
		$tag_folder 	= FCPATH."assets/qr/tag/";
		$base_url 	    = $this->config->item('get_base_url');

		$data['id'] 	= $id;
		//PRODUCT
		$data['product_related'] = $this->global_model->select_order('product','product_nama','asc');
		//CATEGORY
		$data['product'] 		 = $this->backend_model->get_join_by_id('category',$product,'','',$order)->result_array();
		$data['size'] 			 = $this->backend_model->get_join_by_id('category',$size,'','',$order)->result_array();
		$data['gender'] 		 = $this->backend_model->get_join_by_id('category',$gender,'','',$order)->result_array();
		$data['store_location']  = $this->backend_model->get_join_by_id('category',$store_location,'','',$order)->result_array();

		$where 			= array('product_id' => $id);
		//PRODUCT (IN DATABASE)

		//CATEGORY
		$data['product_db']				= $this->global_model->select_where('product',array('product_id' => $id));

		$select_category = 'category_name,product_category_detil.category_id,product_category_detil_id,flag,product_category_sort';
		$order_prod_Cat  = array('product_category_sort' => 'asc');
		$join            = array(
			'category' => 'category.category_id = product_category_detil.category_id'
			);
		$data['category_db'] 			= $this->backend_model->get_join_by_id('product_category_detil',$where,$select_category,$join,$order)->result_array();

		$where_product  = array('product_id' => $id,'flag' => 'product');
		$data['cat_product'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_product,$select_category,$join,$order_prod_Cat)->result_array();
		$where_gender  = array('product_id' => $id,'flag' => 'gender');
		$data['cat_gender'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_gender,$select_category,$join,$order_prod_Cat)->result_array();
		$where_size  = array('product_id' => $id,'flag' => 'size');
		$data['cat_size'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_size,$select_category,$join,$order_prod_Cat)->result_array();
		$where_store  = array('product_id' => $id,'flag' => 'store_location');
		$data['cat_store'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_store,$select_category,$join,$order_prod_Cat)->result_array();

		$data['product_related_db'] 	= $this->backend_model->get_join_by_id('product_related',$where,'','','')->result_array();
		$data['image_db']				= $this->backend_model->get_join_by_id('product_image',$where,'','','')->result_array();

		$order_sizestock = array('product_sizestock_sort','asc');
		$data['sizestock_db']			= $this->backend_model->get_join_by_id('product_sizestock',$where,'','',$order_sizestock)->result_array();

		$data['category_product'] 	= array();

		if(is_array($data['category_db']) && !empty($data['category_db'])){
			foreach($data['category_db'] as $index => $value){
				$data['category_product'][] = $value['category_id'];
			}
		}

		$this->load->library('ciqrcode');
/*		echo '<pre>';
		print_r($data);
		echo '</pre>';*/

		$this->form_validation->set_rules('product_nama','Nama Produk','trim|required');
		$this->form_validation->set_rules('product_kode','Kode','trim');
		$this->form_validation->set_rules('product_image_id','Picture ID','trim');
		$this->form_validation->set_rules('product_image','Picture','trim');
		$this->form_validation->set_rules('product_deskripsi','Deskripsi','trim');
		$this->form_validation->set_rules('product_isipaket','Isi Paket','trim');
		$this->form_validation->set_rules('product_hargasewa','Harga Sewa','trim');
		$this->form_validation->set_rules('product_deposit','Deposit','trim');
		$this->form_validation->set_rules('product_metatitle','Meta Title','trim');
		$this->form_validation->set_rules('product_metakeyword','Meta Keyword','trim');
		$this->form_validation->set_rules('product_metadescription','Meta Description','trim');
		$this->form_validation->set_rules('product_featured','Featured Product','trim');
		$this->form_validation->set_rules('product_slug','Url','trim');
		$this->form_validation->set_rules('status','Product Status','trim');

		//SIZE + STOCK
		$this->form_validation->set_rules('product_sizestock_id','Size Stock ID','trim');
		$this->form_validation->set_rules('product_size','Size','trim');
		$this->form_validation->set_rules('product_stock','Stock','trim');
		$this->form_validation->set_rules('product_estimasiukuran','Estimasi Ukuran','trim');

		//CATEGORY PRODUCT SIZE GENDER & STORE
		$this->form_validation->set_rules('category_id','Category','trim');

		//RELATED
		$this->form_validation->set_rules('product_related','Product','trim');
		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',json_encode(validation_errors()));
			}
			$data['load_view'] = 'adminsite/v_product_duplicate';
			$this->load->view('adminsite/template/backend', $data);

		} else {
				//PRODUCT
			$product_nama 				= $this->input->post('product_nama');
			$product_kode 				= $this->input->post('product_kode');
			$product_deskripsi 			= $this->input->post('product_deskripsi');
			$product_isipaket 			= $this->input->post('product_isipaket');
			$product_hargasewa 			= $this->input->post('product_hargasewa');
			$product_deposit 			= $this->input->post('product_deposit');
			$product_metatitle 			= $this->input->post('product_metatitle');
			$product_metakeyword 		= $this->input->post('product_metakeyword');
			$product_metadescription 	= $this->input->post('product_metadescription');
			$product_slug 				= $this->input->post('product_slug');
			$product_status 			= $this->input->post('status');
			$product_featured 			= $this->input->post('product_featured');
			//STOCK + SIZE
			$product_sizestock_id 		= $this->input->post('product_sizestock_id');
			$product_size 				= $this->input->post('product_size');
			$product_stock 				= $this->input->post('product_stock');
			$product_estimasiukuran 	= $this->input->post('product_estimasiukuran');

			//IMAGE
			$product_image_id 			= $this->input->post('product_image_id');
			$product_image 				= $this->input->post('product_image');

			//CATEGORY PRODUCT SIZE GENDER & STORE
			$category_id 				= $this->input->post('category_id');

			//PRODUCT CATEGORY
			$product_category_id 		= $this->input->post('product_category_id');

			//SIZE CATEGORY
			$size_category_id 			= $this->input->post('size_category_id');

			//GENDER CATEGORY
			$gender_category_id 		= $this->input->post('gender_category_id');

			//STORE LOCATION
			$store_category_id 			= $this->input->post('store_category_id');

			//RELATED
			$product_related 			= $this->input->post('product_related');
			if(empty($product_status)){
				$product_status 	= 0;
			}
			if(empty($product_featured)){
				$product_featured 	= 0;
			}

			$product_scale				= $this->input->post('product_scale');
			//PRODUCT
			$insert_product = array(
				'product_nama'				=> $product_nama,
				'product_kode'				=> $product_kode,
				'product_deskripsi'			=> htmlentities($product_deskripsi),
				'product_isipaket'			=> htmlentities($product_isipaket),
				'product_hargasewa'			=> preg_replace("/[^0-9\.]/","",$product_hargasewa),
				'product_deposit'			=> preg_replace("/[^0-9\.]/","",$product_deposit),
				'product_metatitle'			=> $product_metatitle,
				'product_metakeyword'		=> $product_metakeyword,
				'product_metadescription'	=> $product_metadescription,
				'product_featured' 			=> $product_featured,
				'product_status'			=> $product_status,
				'product_active'			=> 1,
				'product_created'			=> date('c'),
				'product_modified'			=> NULL,
				'product_scale'				=> $product_scale,
				);

			$result_product = $this->global_model->insert('product',$insert_product);
			$id 			= $this->db->insert_id();
			
			if(empty($product_slug)){
				$slug 		= $this->backend_model->createSlug('product','product_id',$id,'product_slug',$product_nama);
			} else {
				$check_slug = $this->backend_model->check_slug_product($product_slug);
				if($check_slug){
					$slug 		= $this->backend_model->createSlug('product','product_id',$id,'product_slug',$product_nama);
				} else {
					$slug 		= $product_slug;
				}
			}
			$insert_slug = array(
				'product_slug' => $slug
				);
			$this->global_model->update('product',$insert_slug,array('product_id' => $id));

			$qr_slug = str_replace('/','',$slug);
			if(!file_exists('assets/qr/'.$qr_slug)){
				$qr['data'] = $base_url.'product/'.$qr_slug;
				$qr['level'] = 'H';
				$qr['size'] = 10;
				$qr['savename'] = $qr_folder.$qr_slug.'.png';
				$this->ciqrcode->generate($qr);
			}

			//IMAGE
			if(is_array($product_image_id) && !empty($product_image_id)){
				foreach($product_image_id as $index => $value){
					$insert_product_image = array(
						'product_id'				=> $id,
						'product_image'				=> str_replace(base_url(),'',$product_image[$index])
						);
					$result_product_image = $this->global_model->insert('product_image',$insert_product_image);
				}
			}
			//STOCK + SIZE
			if(is_array($product_sizestock_id) && !empty($product_sizestock_id)){
				foreach($product_sizestock_id as $index => $value){

					$product_sizestock_slug   = url_title($product_size[$index],'-',true);
					$insert_product_sizestock = array(
						'product_id'				=> $id,
						'product_size' 				=> $product_size[$index],
						'product_stock'				=> $product_stock[$index],
						'product_estimasiukuran'	=> htmlentities($product_estimasiukuran[$index]),
						'product_sizestock_sort'	=> $index,
						'product_sizestock_slug'    => $product_sizestock_slug
						);
					$result_product_sizestock = $this->global_model->insert('product_sizestock',$insert_product_sizestock);
					$product_sizestock_id 	    = $this->db->insert_id();

						$url = $base_url.'tag/'.url_title($product_kode.'-'.$product_sizestock_id,'-',true);
						$filename = url_title($product_kode.'-'.$product_sizestock_id,'-',true);
						if(!file_exists('assets/qr/tag/'.$filename.'.png')){
							$tag['data'] 		= $url;
							$tag['level'] 		= 'L';
							$tag['size'] 		= 10;
							$tag['savename'] 	= $tag_folder.$filename.'.png';
							$this->ciqrcode->generate($tag);
						}
				}
			}

			//CATEGORY PRODUCT
			if(is_array($product_category_id) && !empty($product_category_id)){
				$result_product_category_detil = false;
				foreach($product_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $product_category_id[$index],
						'flag'						=> 'product',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//CATEGORY SIZE
			if(is_array($size_category_id) && !empty($size_category_id)){
				foreach($size_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $size_category_id[$index],
						'flag'						=> 'size',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//CATEGORY GENDER
			if(is_array($gender_category_id) && !empty($gender_category_id)){
				foreach($gender_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $gender_category_id[$index],
						'flag'						=> 'gender',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//CATEGORY GENDER
			if(is_array($store_category_id) && !empty($store_category_id)){
				foreach($store_category_id as $index => $value){
					$insert_product_category_detil = array(
						'product_id'				=> $id,
						'category_id' 				=> $store_category_id[$index],
						'flag'						=> 'store_location',
						'product_category_sort'		=> $index
						);
					$result_product_category_detil = $this->global_model->insert('product_category_detil',$insert_product_category_detil);
				}
			}

			//RELATED
			if(is_array($product_related) && !empty($product_related)){
				foreach($product_related as $index => $value){
					$insert_product_related = array(
						'product_id'					=> $id,
						'product_related' 				=> $product_related[$index],
						);
					$result_product_related = $this->global_model->insert('product_related',$insert_product_related);
				}
			}

			if(!empty($id)){
				$get_product_category_detil = $this->backend_model->get_category_by_product($id);
				if(!empty($get_product_category_detil)){
					$filter_product 		= array();
					$filter_size 			= array();
					$filter_gender 			= array();
					$filter_store_location 	= array();
					foreach($get_product_category_detil as $index => $value){
						if(!empty($value['category_slug'])){
							if($value['flag'] == 'product'){
								$filter_product[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'size'){
								$filter_size[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'gender'){
								$filter_gender[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'store_location'){
								$filter_store_location[] = $value['category_slug'];
							}
						}
					}

					if(!empty($filter_product)){
						$got_slug = implode(',',$filter_product);
						$update = array(
							'filter_product' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_size)){
						$got_slug = implode(',',$filter_size);
						$update = array(
							'filter_size' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_gender)){
						$got_slug = implode(',',$filter_gender);
						$update = array(
							'filter_gender' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_store_location)){
						$got_slug = implode(',',$filter_store_location);
						$update = array(
							'filter_store_location' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}
				}

				$insert_popularity = array(
					'product_id'   => $id,
					'nama'		   => $product_nama,
					'kode'		   => $product_kode
				);
				$this->global_model->insert('product_popularity',$insert_popularity);
			}

			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/product','refresh');
		}

	}

	public function edit(){

		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Edit Product';
		$id 			= $this->uri->segment(4);
		$product 		= array('category_flag' => 'product');
		$size 			= array('category_flag' => 'size');
		$gender 		= array('category_flag' => 'gender');
		$store_location = array('category_flag' => 'store_location');
		$order 			= array('category_name' => 'asc');

		$qr_folder 		= FCPATH."assets/qr/";
		$tag_folder 	= FCPATH."assets/qr/tag/";
		$base_url 	    = $this->config->item('get_base_url');

		$data['id'] 	= $id;
		//PRODUCT
		$data['product_related'] = $this->global_model->select_order('product','product_nama','asc');
		//CATEGORY
		$data['product'] 		 = $this->backend_model->get_join_by_id('category',$product,'','',$order)->result_array();
		$data['size'] 			 = $this->backend_model->get_join_by_id('category',$size,'','',$order)->result_array();
		$data['gender'] 		 = $this->backend_model->get_join_by_id('category',$gender,'','',$order)->result_array();
		$data['store_location']  = $this->backend_model->get_join_by_id('category',$store_location,'','',$order)->result_array();

		$where 			= array('product_id' => $id);
		//CATEGORY
		$data['product_db']				= $this->global_model->select_where('product',array('product_id' => $id));

		$select_category = 'category_name,product_category_detil.category_id,product_category_detil_id,flag,product_category_sort';
		$order_prod_Cat  = array('product_category_sort' => 'asc');
		$join            = array(
			'category' => 'category.category_id = product_category_detil.category_id'
			);
		$data['category_db'] 			= $this->backend_model->get_join_by_id('product_category_detil',$where,$select_category,$join,$order)->result_array();

		$where_product  = array('product_id' => $id,'flag' => 'product');
		$data['cat_product'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_product,$select_category,$join,$order_prod_Cat)->result_array();
		$where_gender  = array('product_id' => $id,'flag' => 'gender');
		$data['cat_gender'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_gender,$select_category,$join,$order_prod_Cat)->result_array();
		$where_size  = array('product_id' => $id,'flag' => 'size');
		$data['cat_size'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_size,$select_category,$join,$order_prod_Cat)->result_array();
		$where_store  = array('product_id' => $id,'flag' => 'store_location');
		$data['cat_store'] 		= $this->backend_model->get_join_by_id('product_category_detil',$where_store,$select_category,$join,$order_prod_Cat)->result_array();

		$data['product_related_db'] 	= $this->backend_model->get_join_by_id('product_related',$where,'','','')->result_array();
		$data['image_db']				= $this->backend_model->get_join_by_id('product_image',$where,'','','')->result_array();

		$order_sizestock['product_sizestock_sort'] = 'asc';
		$data['sizestock_db']			= $this->backend_model->get_join_by_id('product_sizestock',$where,'','',$order_sizestock)->result_array();

		$data['category_product'] 	= array();

		if(is_array($data['category_db']) && !empty($data['category_db'])){

			foreach($data['category_db'] as $index => $value){

				$flag = $value['flag'];
				$data['category_product'][] = $value['category_id'];
			}
		}

		$this->form_validation->set_rules('product_nama','Nama Produk','trim|required');
		$this->form_validation->set_rules('product_kode','Kode','trim');
		$this->form_validation->set_rules('product_image_id','Picture ID','trim');
		$this->form_validation->set_rules('product_image','Picture','trim');
		$this->form_validation->set_rules('product_deskripsi','Deskripsi','trim');
		$this->form_validation->set_rules('product_isipaket','Isi Paket','trim');
		$this->form_validation->set_rules('product_hargasewa','Harga Sewa','trim');
		$this->form_validation->set_rules('product_deposit','Deposit','trim');
		$this->form_validation->set_rules('product_metatitle','Meta Title','trim');
		$this->form_validation->set_rules('product_metakeyword','Meta Keyword','trim');
		$this->form_validation->set_rules('product_metadescription','Meta Description','trim');
		$this->form_validation->set_rules('product_featured','Featured Product','trim');
		$this->form_validation->set_rules('product_slug','Url','trim');
		$this->form_validation->set_rules('status','Product Status','trim');

		//SIZE + STOCK
		$this->form_validation->set_rules('product_sizestock_id','Size Stock ID','trim');
		$this->form_validation->set_rules('product_size','Size','trim');
		$this->form_validation->set_rules('product_stock','Stock','trim');
		$this->form_validation->set_rules('product_estimasiukuran','Estimasi Ukuran','trim');

		//CATEGORY PRODUCT SIZE GENDER & STORE
		$this->form_validation->set_rules('category_id','Category','trim');

		//RELATED
		$this->form_validation->set_rules('product_related','Product','trim');

		$this->load->library('ciqrcode');

		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',json_encode(validation_errors()));
			}
			$data['load_view'] = 'adminsite/v_product_edit';
			$this->load->view('adminsite/template/backend', $data);

		} else {
			//PRODUCT
			$product_nama 				= $this->input->post('product_nama');
			$product_kode 				= $this->input->post('product_kode');
			$product_deskripsi 			= $this->input->post('product_deskripsi');
			$product_isipaket 			= $this->input->post('product_isipaket');
			$product_hargasewa 			= $this->input->post('product_hargasewa');
			$product_deposit 			= $this->input->post('product_deposit');
			$product_metatitle 			= $this->input->post('product_metatitle');
			$product_metakeyword 		= $this->input->post('product_metakeyword');
			$product_metadescription 	= $this->input->post('product_metadescription');
			$product_slug 				= $this->input->post('product_slug');
			$product_status 			= $this->input->post('status');
			$product_featured 			= $this->input->post('product_featured');
			//STOCK + SIZE
			$product_sizestock_id 		= $this->input->post('product_sizestock_id');
			$product_size 				= $this->input->post('product_size');
			$product_stock 				= $this->input->post('product_stock');
			$product_estimasiukuran 	= $this->input->post('product_estimasiukuran');

			//IMAGE
			$product_image_id 			= $this->input->post('product_image_id');
			$product_image 				= $this->input->post('product_image');

			// return print_r($product_nama);

			//CATEGORY PRODUCT SIZE GENDER & STORE

			//PRODUCT CATEGORY
			$product_category_id 		= array();
			$product_category_id 		= $this->input->post('product_category_id');

			
			//SIZE CATEGORY
			$size_category_id 			= array();
			$size_category_id 			= $this->input->post('size_category_id');

			//GENDER CATEGORY
			$gender_category_id 		= array();
			$gender_category_id 		= $this->input->post('gender_category_id');

			//STORE LOCATION
			$store_category_id 			= array();
			$store_category_id 			= $this->input->post('store_category_id');

			//RELATED
			$product_related 			= $this->input->post('product_related');

			$product_scale				= $this->input->post('product_scale');

			$category_id 				= array();

			if(empty($product_status)){
				$product_status 	= 0;
			}
			if(empty($product_featured)){
				$product_featured 	= 0;
			}
			if(empty($product_slug)){
				$slug 		= $this->backend_model->createSlug('product','product_id',$id,'product_slug',$product_nama);
			} else {
				$check_slug = $this->backend_model->check_slug_product($product_slug);
				if($check_slug){
					$slug 		= $product_slug;
				} else {
					$slug 		= $this->backend_model->createSlug('product','product_id',$id,'product_slug',$product_nama);
				}
			}
			//PRODUCT
			$update_product = array(
				'product_nama'				=> $product_nama,
				'product_kode'				=> $product_kode,
				'product_deskripsi'			=> htmlentities($product_deskripsi),
				'product_isipaket'			=> htmlentities($product_isipaket),
				'product_hargasewa'			=> preg_replace("/[^0-9\.]/","",$product_hargasewa),
				'product_deposit'			=> preg_replace("/[^0-9\.]/","",$product_deposit),
				'product_metatitle'			=> $product_metatitle,
				'product_metakeyword'		=> $product_metakeyword,
				'product_metadescription'	=> $product_metadescription,
				'product_featured' 			=> $product_featured,
				'product_slug' 				=> $slug,
				'product_status'			=> $product_status,
				'product_active'			=> 1,
				'product_modified'			=> date('c'),
				'product_scale'				=> $product_scale
				);

			$result_product = $this->global_model->update('product',$update_product,array('product_id' => $id));

			$qr_slug = str_replace('/','',$slug);
			if(!file_exists('assets/qr/'.$qr_slug)){
				$qr['data'] = $base_url.'product/'.$qr_slug;
				$qr['level'] = 'H';
				$qr['size'] = 10;
				$qr['savename'] = $qr_folder.$qr_slug.'.png';
				$this->ciqrcode->generate($qr);
			}

			//IMAGE
			if(isset($product_image_id) && is_array($product_image_id)){
				foreach($data['image_db'] as $index => $value){
					if(in_array($value['product_image_id'],$product_image_id)){
						foreach($product_image_id as $key => $values){
							$update = array(
								'product_image' => str_replace(base_url(),'',$product_image[$index])
								);
							$this->global_model->update('product_image',$update,array('product_image_id' => $value['product_image_id']));
						}
					} else {
						$this->global_model->delete('product_image',array('product_image_id' => $value['product_image_id']));
					}
				}

				foreach($product_image_id as $index => $value){
					if(empty($value)){
						if(!empty($product_image[$index])){
							$insert = array(
								'product_id'	=> $id,
								'product_image' => str_replace(base_url(),'',$product_image[$index])
								);
							$this->global_model->insert('product_image',$insert);
						}
					}
				}

			} else {
				$this->global_model->delete('product_image',array('product_id' => $id));
			}

			//PRODUCT RELATED
			if(isset($product_related) && is_array($product_related)){

				if(!empty($data['product_related_db'])){
					$product_related_id_db = array();
					foreach($data['product_related_db'] as $index => $value){
						$product_related_id_db[] = $value['product_related'];
						if(in_array($value['product_related'],$product_related)){
							$update = array(
								'product_id'		=> $id,
								'product_related' 	=> $value['product_related']
								);
							$this->global_model->update('product_related',$update,array('product_related_id' => $value['product_related_id']));

						} else {

							if(!empty($value['product_related'])){
								$this->global_model->delete('product_related',array('product_related_id' => $value['product_related_id']));
							} else {
								echo 'a';
								foreach($product_related as $key => $row){
									$insert = array(
										'product_id'	=> $id,
										'product_related' => $row
										);
									$this->global_model->insert('product_related',$insert);
								}
							}
						}
					}

					foreach($product_related as $index => $value){

						if(!empty($product_related_id_db)){
							if(!in_array($value,$product_related_id_db)){
								$insert = array(
									'product_id'	=> $id,
									'product_related' => $value
									);
								$this->global_model->insert('product_related',$insert);
							}
						}

					}

				} else {

					foreach($product_related as $index => $value){
						$insert = array(
							'product_id'	=> $id,
							'product_related' => $value
							);
						$this->global_model->insert('product_related',$insert);
					}
				}
			} else {
				$this->global_model->delete('product_related',array('product_id' => $id));
			}

			//SIZE + STOCK
			if(isset($product_sizestock_id) && is_array($product_sizestock_id)){
				$update = array();
				$no = 0;
				$product_sizestock_id_db = array();
				foreach($data['sizestock_db'] as $index => $value){
					$product_sizestock_id_db[] = $value['product_sizestock_id'];
				}
				foreach($product_sizestock_id as $index => $value){
					if(!empty($value) && in_array($value,$product_sizestock_id_db)){
						$product_sizestock_slug   		= url_title($product_size[$index],'-',true);
						$update = array(
							'product_size' 				=> $product_size[$index],
							'product_stock' 			=> preg_replace("/[^0-9\.]/","",$product_stock[$index]),
							'product_estimasiukuran' 	=> htmlentities($product_estimasiukuran[$index]),
							'product_sizestock_sort'	=> $index,
							'product_sizestock_slug'    => $product_sizestock_slug
							);
						$this->global_model->update('product_sizestock',$update,array('product_sizestock_id' => $value));

						$url = $base_url.'tag/'.url_title($product_kode.'-'.$value,'-',true);
						$filename = url_title($product_kode.'-'.$value,'-',true);
						if(!file_exists('assets/qr/tag/'.$filename.'.png')){
							$tag['data'] 		= $url;
							$tag['level'] 		= 'L';
							$tag['size'] 		= 10;
							$tag['savename'] 	= $tag_folder.$filename.'.png';
							$this->ciqrcode->generate($tag);
						}

					} elseif(empty($value)){
						$product_sizestock_slug   = url_title($product_size[$index],'-',true);
						$insert = array(
							'product_id'				=> $id,
							'product_size' 				=> $product_size[$index],
							'product_stock' 			=> preg_replace("/[^0-9\.]/","",$product_stock[$index]),
							'product_estimasiukuran' 	=> htmlentities($product_estimasiukuran[$index]),
							'product_sizestock_sort'	=> $index,
							'product_sizestock_slug'    => $product_sizestock_slug
							);
						$this->global_model->insert('product_sizestock',$insert);
						$new_product_sizestock_id 	    = $this->db->insert_id();

						$url = $base_url.'tag/'.url_title($product_kode.'-'.$new_product_sizestock_id,'-',true);
						$filename = url_title($product_kode.'-'.$new_product_sizestock_id,'-',true);
						if(!file_exists('assets/qr/tag/'.$filename.'.png')){
							$tag['data'] 		= $url;
							$tag['level'] 		= 'L';
							$tag['size'] 		= 10;
							$tag['savename'] 	= $tag_folder.$filename.'.png';
							$this->ciqrcode->generate($tag);
						}
					}
				}

				foreach($data['sizestock_db'] as $index => $value){
					if(!in_array($value['product_sizestock_id'],$product_sizestock_id)){
						$this->global_model->delete('product_sizestock',array('product_sizestock_id' => $value['product_sizestock_id']));
					}
				}
			} else {
				$this->global_model->delete('product_sizestock',array('product_id' => $id));
			}

			if(isset($product_category_id) && is_array($product_category_id)){

				if(!empty($data['category_db'])){

					$category_id_db = array();

					foreach($data['category_db'] as $index => $value){

						$key = array_search($value['category_id'],$product_category_id);

						$category_id_db[] = $value['category_id'];

						if(in_array($value['category_id'],$product_category_id)){

							$update = array(
								'category_id' => $value['category_id'],
								'product_category_sort' => $key
								);
							$this->global_model->update('product_category_detil',$update,array('product_category_detil_id' => $value['product_category_detil_id']));
						} else {

							if(!empty($value['category_id'])){
								if($value['flag'] == 'product'){
									$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
								}
							} else {

								foreach($product_category_id as $key => $row){
									$insert = array(
										'product_id' 	=> $id,
										'category_id' 	=> $row,
										'flag'			=> 'product',
										'product_category_sort' => $key
										);
									$this->global_model->insert('product_category_detil',$insert);
								}
							}
						}
					}

					foreach($product_category_id as $index => $value){

						if(!empty($category_id_db)){
							if(!in_array($value,$category_id_db)){
								$insert = array(
									'product_id' 	=> $id,
									'category_id' 	=> $value,
									'flag'			=> 'product',
									'product_category_sort' => $index
									);
								$this->global_model->insert('product_category_detil',$insert);
							}
						}

					}

				} else {

					foreach($product_category_id as $index => $value){
						$insert = array(
							'product_id' 	=> $id,
							'category_id' 	=> $value,
							'flag'			=> 'product',
							'product_category_sort' => $index
							);
						$this->global_model->insert('product_category_detil',$insert);
					}
				}
			} else {

				if(!empty($data['category_db'])){
					foreach($data['category_db'] as $index => $value){
						if($value['flag'] == 'product'){
							$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
						}
					}
				}
			}

			if(isset($size_category_id) && is_array($size_category_id)){

				if(!empty($data['category_db'])){

					$category_id_db = array();

					foreach($data['category_db'] as $index => $value){

						$key = array_search($value['category_id'],$size_category_id);

						$category_id_db[] = $value['category_id'];

						if(in_array($value['category_id'],$size_category_id)){
							$update = array(
								'category_id' => $value['category_id'],
								'product_category_sort' => $key
								);
							$this->global_model->update('product_category_detil',$update,array('product_category_detil_id' => $value['product_category_detil_id']));
						} else {

							if(!empty($value['category_id'])){
								if($value['flag'] == 'size'){
									$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
								}
							} else {

								foreach($size_category_id as $key => $row){
									$insert = array(
										'product_id' 	=> $id,
										'category_id' 	=> $row,
										'flag'			=> 'size',
										'product_category_sort' => $key
										);
									$this->global_model->insert('product_category_detil',$insert);
								}
							}
						}
					}

					foreach($size_category_id as $index => $value){

						if(!empty($category_id_db)){
							if(!in_array($value,$category_id_db)){
								$insert = array(
									'product_id' 	=> $id,
									'category_id' 	=> $value,
									'flag'			=> 'size',
									'product_category_sort' => $index
									);
								$this->global_model->insert('product_category_detil',$insert);
							}
						}

					}

				} else {

					foreach($size_category_id as $index => $value){
						$insert = array(
							'product_id' 	=> $id,
							'category_id' 	=> $value,
							'flag'			=> 'size',
							'product_category_sort' => $index 
							);
						$this->global_model->insert('product_category_detil',$insert);
					}
				}
			} else {

				if(!empty($data['category_db'])){
					foreach($data['category_db'] as $index => $value){
						if($value['flag'] == 'size'){
							$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
						}
					}
				}
			}

			if(isset($gender_category_id) && is_array($gender_category_id)){

				if(!empty($data['category_db'])){

					$category_id_db = array();

					foreach($data['category_db'] as $index => $value){

						$key = array_search($value['category_id'],$gender_category_id);

						$category_id_db[] = $value['category_id'];

						if(in_array($value['category_id'],$gender_category_id)){
							$update = array(
								'category_id' => $value['category_id'],
								'product_category_sort' => $key
								);
							$this->global_model->update('product_category_detil',$update,array('product_category_detil_id' => $value['product_category_detil_id']));
						} else {

							if(!empty($value['category_id'])){
								if($value['flag'] == 'gender'){
									$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
								}
							} else {

								foreach($gender_category_id as $key => $row){
									$insert = array(
										'product_id' 	=> $id,
										'category_id' 	=> $row,
										'flag'			=> 'gender',
										'product_category_sort' => $key
										);
									$this->global_model->insert('product_category_detil',$insert);
								}
							}
						}
					}

					foreach($gender_category_id as $index => $value){

						if(!empty($category_id_db)){
							if(!in_array($value,$category_id_db)){
								$insert = array(
									'product_id' 	=> $id,
									'category_id' 	=> $value,
									'flag'			=> 'gender',
									'product_category_sort' => $index
									);
								$this->global_model->insert('product_category_detil',$insert);
							}
						}

					}

				} else {

					foreach($gender_category_id as $index => $value){
						$insert = array(
							'product_id' 	=> $id,
							'category_id' 	=> $value,
							'flag'			=> 'gender',
							'product_category_sort' => $index
							);
						$this->global_model->insert('product_category_detil',$insert);
					}
				}
			} else {

				if(!empty($data['category_db'])){
					foreach($data['category_db'] as $index => $value){
						if($value['flag'] == 'gender'){
							$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
						}
					}
				}
			}

			if(isset($store_category_id) && is_array($store_category_id)){

				if(!empty($data['category_db'])){

					$category_id_db = array();

					foreach($data['category_db'] as $index => $value){

						$key = array_search($value['category_id'],$store_category_id);

						$category_id_db[] = $value['category_id'];

						if(in_array($value['category_id'],$store_category_id)){
							$update = array(
								'category_id' => $value['category_id'],
								'product_category_sort' => $key
								);
							$this->global_model->update('product_category_detil',$update,array('product_category_detil_id' => $value['product_category_detil_id']));
						} else {

							if(!empty($value['category_id'])){
								if($value['flag'] == 'store_location'){
									$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
								}
							} else {

								foreach($store_category_id as $key => $row){
									$insert = array(
										'product_id' 	=> $id,
										'category_id' 	=> $row,
										'flag'			=> 'store_location',
										'product_category_sort' => $key
										);
									$this->global_model->insert('product_category_detil',$insert);
								}
							}
						}
					}

					foreach($store_category_id as $index => $value){

						if(!empty($category_id_db)){
							if(!in_array($value,$category_id_db)){
								$insert = array(
									'product_id' 	=> $id,
									'category_id' 	=> $value,
									'flag'			=> 'store_location',
									'product_category_sort' => $index
									);
								$this->global_model->insert('product_category_detil',$insert);
							}
						}

					}

				} else {

					foreach($store_category_id as $index => $value){
						$insert = array(
							'product_id' 	=> $id,
							'category_id' 	=> $value,
							'flag'			=> 'store_location',
							'product_category_sort' => $index
							);
						$this->global_model->insert('product_category_detil',$insert);
					}
				}
			} else {

				if(!empty($data['category_db'])){
					foreach($data['category_db'] as $index => $value){
						if($value['flag'] == 'store_location'){
							$this->global_model->delete('product_category_detil',array('product_category_detil_id' => $value['product_category_detil_id']));
						}
					}
				}
			}

			if(!empty($id)){
				$get_product_category_detil = $this->backend_model->get_category_by_product($id);
				if(!empty($get_product_category_detil)){
					$filter_product 		= array();
					$filter_size 			= array();
					$filter_gender 			= array();
					$filter_store_location 	= array();
					foreach($get_product_category_detil as $index => $value){
						if(!empty($value['category_slug'])){
							if($value['flag'] == 'product'){
								$filter_product[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'size'){
								$filter_size[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'gender'){
								$filter_gender[] = $value['category_slug'];
							}
						}

						if(!empty($value['category_slug'])){
							if($value['flag'] == 'store_location'){
								$filter_store_location[] = $value['category_slug'];
							}
						}
					}

					if(!empty($filter_product)){
						$got_slug = implode(',',$filter_product);
						$update = array(
							'filter_product' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_size)){
						$got_slug = implode(',',$filter_size);
						$update = array(
							'filter_size' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_gender)){
						$got_slug = implode(',',$filter_gender);
						$update = array(
							'filter_gender' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}

					if(!empty($filter_store_location)){
						$got_slug = implode(',',$filter_store_location);
						$update = array(
							'filter_store_location' => $got_slug
							);
						$updated = $this->global_model->update('product',$update,array('product_id' => $id));
					}
				}
			}

			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/product/edit/'.$id);
		}

	}

	public function edit_display_data(){
		$id = $this->input->post('category_id');
		$query = $this->global_model->select_where('category',array('category_id' => $id));

		if(!empty($query)){
			$data = array();
			foreach($query as $index => $value){
				$data = array(
					'category_id' 				=> $value['category_id'],
					'category_name'				=> $value['category_name'],
					'category_value_textarea'	=> $value['category_value_textarea'],
					'status'					=> $value['category_status']
					);
			}
			$result = array(
				'data' => $data,
				'flag' => true
				);
		} else {
			$result = array(
				'message' 	=> 'Nothing found data.',
				'flag'		=> false
				);
		}

		echo json_encode($result);
	}

	public function trash(){
		$id = $this->input->post('id');
		$update = array(
			'product_active' => 0
			);
		$trash = $this->global_model->update('product',$update,array('product_id' => $id));
		if($trash){
			$return = array('flag'=>true);
		} else {
			$return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');
		}
		echo json_encode($return);
	}

	public function restore(){
		$id = $this->input->post('id');
		$update = array(
			'product_active' => 1
			);
		$trash = $this->global_model->update('product',$update,array('product_id' => $id));
		if($trash){
			$return = array('flag'=>true);
		} else {
			$return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');
		}
		echo json_encode($return);
	}

	public function multiple_restore(){

		$id = $this->input->post('id',true);
		$update = false;

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$update = array(
					'product_active' => 1
					);

				$delete = $this->global_model->update('product',$update,array('product_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function multiple_trash(){

		$id = $this->input->post('id',true);
		$update = false;

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$update = array(
					'product_active' => 0
					);

				$delete = $this->global_model->update('product',$update,array('product_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function delete(){

		$id = $this->input->post('id');

		$delete = $this->global_model->delete('product',array('product_id' => $id));

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

					'category_status' 		=> 1,
					'category_modified' 	=> date('c')

					);

				$update = $this->global_model->update('category',$data,array('category_id' => $id[$i]));

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
					'category_status' 		=> 0,
					'category_modified' 	=> date('c')
					);

				$update = $this->global_model->update('category',$data,array('category_id' => $id[$i]));

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

				$delete = $this->global_model->delete('product',array('product_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function datatables(){

		$order = array(
			'product_order' => 'asc',
			'product_created' => 'desc'
			);
		$where = array();
		$where = array(
			'product_active' => 1
			);

		$query = $this->global_model->getDataWhereOrder('product',$where,$order);

		$data = array();
		if(!empty($query)){
			foreach($query->result_array() as $index => $value) {

				$query_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));
				$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
				if(!empty($query_image)){
					if (file_exists($query_image[0]['product_image'])) {
						$image  = '<img class="img-thumbnail" src="'.str_replace('upload/images','upload/thumbnail/images',$query_image[0]['product_image']).'">';
					}
				}

				$status = '';
				if($value['product_status'] == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}

				$product_modified = $value['product_modified'];

				if(!empty($product_modified)){
					$product_modified = date('d-M-Y',strtotime($value['product_modified']));
				} else {
					$product_modified = ' - ';
				}

				$action = '';
				$action .= '<a class="btn-edit-action btn btn-warning btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/duplicate/').$value['product_id'].'" style="margin-right: 5px;">Duplicate</a>';
				$action .= '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/edit/').$value['product_id'].'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-sm btn-flat" data-item="'.$value['product_id'].'" data-url="'.base_url('adminsite/product/trash').'">Trash</a>';

				$data[] = array(
					'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['product_id'].'"></div>',
					$image,
					$value['product_nama'],
					$value['product_kode'],
					$product_modified,
					$status,
					$action
					);
			}
		}

		$result = $this->custom_lib->datatables_data($query,$data);
		echo json_encode($result);
	}

	public function datatables_trash(){
    	$get                    = $this->input->post('geturl');
		if(!empty($get)){
			$get                = unserialize(base64_decode($get));
		}
		$status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
		$searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
		$store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
		$order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
		$order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';

		$draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
		$start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordWithFilter  = 0;

        $totalRecords           = $this->backend_model->countAllProductDataTrash($searchValue);
        $totalRecordwithFilter  = $totalRecords;
        $empRecords             = $this->backend_model->AllProductDataTrash($searchValue,$order,$order_by,$start,$offset);
        $data 					= array();

        if(!empty($empRecords)){
        	foreach($empRecords as $index => $value) {

        		$query_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));
        		$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
        		if(!empty($query_image)){
        			if (file_exists($query_image[0]['product_image'])) {
        				$image  = '<img class="img-thumbnail" src="'.str_replace('upload/images','upload/thumbnail/images',$query_image[0]['product_image']).'">';
        			}
        		}

        		$status = '';
        		if($value['product_status'] == 1){
        			$status = '<span class="label label-success">Yes</span>';
        		} else {
        			$status = '<span class="label label-danger">No</span>';
        		}

        		$product_modified = $value['product_modified'];

        		if(!empty($product_modified)){
        			$product_modified = date('d-M-Y',strtotime($value['product_modified']));
        		} else {
        			$product_modified = ' - ';
        		}

        		$action = '';
        		$action .= '<a class="btn-edit-action btn btn-warning btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/duplicate/').$value['product_id'].'" style="margin-right: 5px;">Duplicate</a>';
        		$action .= '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value['product_id'].'" href="'.base_url('adminsite/product/edit/').$value['product_id'].'" style="margin-right: 5px;">Edit</a>';
        		$action .= '<a class="btn-ajax-restore-action btn btn-success btn-sm btn-flat" data-item="'.$value['product_id'].'" data-url="'.base_url('adminsite/product/restore').'">Restore</a>';

        		$data[] = array(
        			'checkbox' => '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['product_id'].'"></div>',
        			'image' => $image,
        			'product_nama' => $value['product_nama'],
        			'product_kode' => $value['product_kode'],
        			'product_modified' => $product_modified,
        			'status' => $status,
        			'action' => $action
        			);
        	}
        }

        $response = array(
        	"draw" => intval($draw),
        	"iTotalRecords" => $totalRecordwithFilter,
        	"iTotalDisplayRecords" => $totalRecords,
        	"aaData" => $data
        	);
        echo json_encode($response);
    }

}

?>