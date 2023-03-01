<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Set_custom extends CI_Controller{

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

			if($already_login !== false){
				$this->session_items = data_session($this->session->userdata($this->config->item('access_panel')));
				$this->start_session = $this->session_items;
			}

		} else {
			redirect('adminsite','refresh');
		}
	}

	public function print_report($get = ''){
		if(!empty($get)){
			$get                = unserialize(base64_decode($get));
		}
		$searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
		$searchProduct          = (isset($get['prod']) && !empty($get['prod'])) ? trim($get['prod']) : '';
		$startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : '';

		if(!empty($startdate)){
			$startdate              = str_replace('/', '-', $startdate);
			$startdate              = date('Y-m-d', strtotime($startdate));
		}

		$enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : '';

		if(!empty($enddate)){
			$enddate                = str_replace('/', '-', $enddate);
			$enddate                = date('Y-m-d', strtotime($enddate));
		}

		$data['periode']		= 'Today : '.date('d/M/Y');
		if(!empty($startdate) && !empty($enddate)){
			$data['periode']    = 'Periode : '.date('d/M/Y',strtotime($startdate)).' s/d '.date('d/M/Y',strtotime($enddate));
		} elseif(!empty($startdate) && empty($enddate)){
			$data['periode']    = 'Filter >= tanggal : '.date('d/M/Y',strtotime($startdate));
		} elseif(empty($startdate) && !empty($enddate)){
			$data['periode']    = 'Filter <= tanggal : '.date('d/M/Y',strtotime($enddate));
		}

		$note 					= (isset($get['note']) && !empty($get['note'])) ? trim($get['note']) : false;
        $data['get']            = $this->backend_model->PrintAllSetCustom($searchValue,$startdate,$enddate,$note,$searchProduct);

        /*echo '<pre>';
        print_r($data['get']);
        echo '</pre>';
        exit;*/
        $this->load->view('adminsite/v_print_set_custom', $data);
    }

	public function datatables_order(){

		$get                    = $this->input->get('geturl');
		if(!empty($get)){
			$get                = unserialize(base64_decode($get));
		}
		$searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
		$searchProduct          = (isset($get['prod']) && !empty($get['prod'])) ? trim($get['prod']) : '';
		$startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : '';

		if(!empty($startdate)){
			$startdate              = str_replace('/', '-', $startdate);
			$startdate              = date('Y-m-d', strtotime($startdate));
		}

		$enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : '';

		if(!empty($enddate)){
			$enddate                = str_replace('/', '-', $enddate);
			$enddate                = date('Y-m-d', strtotime($enddate));
		}

		$note 					= (isset($get['note']) && $get['note'] == 1) ? $get['note'] : false;

		//$note = 1;
		$draw                   = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$start                  = isset($_GET['start']) ? $_GET['start'] : 0;
        $offset                 = isset($_GET['length']) ? $_GET['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordWithFilter  = 0;

        $totalRecords           = $this->backend_model->countAllSetCustom($searchValue,$startdate,$enddate,$note,$searchProduct);
        $query             		= $this->backend_model->AllSetCustom($searchValue,$startdate,$enddate,$note,$start,$offset,$searchProduct);
        $getData 				= array();

        if(!empty($query)){
        	foreach($query as $index => $value) {
        		$action = '';
        		$action .= '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value['set_custom_id'].'" href="'.base_url('adminsite/set_custom/edit/').$value['set_custom_id'].'" style="margin-right: 5px;">Edit</a>';
        		$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value['set_custom_id'].'" data-url="'.base_url('adminsite/set_custom/delete').'">Delete</a>';

        		$noted  = array();
        		$note   = explode("\n",$value['note']);
        		if(is_array($note) && !empty($note)){
        			foreach($note as $key => $row){
        				$noted[] = '<p>'.$row.'</p>';
        			}
        		}

        		$getData[] = array(
        			'checkbox' 		=> '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['set_custom_id'].'"></div>',
        			'karyawan_nama' => $value['karyawan_nama'],
        			'created' 		=> date('d-m-Y',strtotime($value['created'])).' '.date('h:i A',strtotime($value['created'])),
        			'product_nama'	=> $value['product_nama'].' / '.$value['product_kode'],
        			'product_size'	=> $value['product_size'],
        			'note'			=> implode(" ",$noted),
        			'action' 		=> $action
        		);
        	}
        }

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecordWithFilter,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $getData,
            "get"					=> $get
            );
        echo json_encode($response);
    }

    public function datatables_order_karyawan(){

    	$get                    = $this->input->post('geturl');
    	if(!empty($get)){
    		$get                = unserialize(base64_decode($get));
    	}
    	$searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
    	$draw                   = isset($_GET['draw']) ? $_GET['draw'] : 1;
    	$start                  = isset($_GET['start']) ? $_GET['start'] : 0;
        $offset                 = isset($_GET['length']) ? $_GET['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordwithFilter  = 0;

        $totalRecords           = $this->backend_model->countAllKaryawan($searchValue);
        $query             		= $this->backend_model->AllSetCustomKaryawan($searchValue,$start,$offset);
        $data 					= array();

        if(!empty($query)){
        	foreach($query->result() as $index => $value) {
        		$action = '';
        		$action .= '<a class="btn-edit-action btn-ajax-edit-action btn btn-primary btn-sm btn-flat" data-url="'.base_url('adminsite/set_custom/karyawan_edit/').$value->karyawan_id.'" data-item="'.$value->karyawan_id.'" href="'.base_url('adminsite/set_custom/karyawan_edit/').$value->karyawan_id.'" style="margin-right: 5px;">Edit</a>';
        		$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" href="'.base_url('adminsite/set_custom/karyawan_delete/').$value->karyawan_id.'" data-url="'.base_url('adminsite/set_custom/karyawan_delete/').$value->karyawan_id.'" data-item="'.$value->karyawan_id.'">Delete</a>';

        		$data[] = array(
        			'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->karyawan_id.'"></div>',
        			$value->karyawan_nama,
        			$action
        		);
        	}
        }

        $result = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecordwithFilter,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $data,
            );
        echo json_encode($result);
    }

    public function index(){
    	$data['session_items']        = $this->session->userdata($this->config->item('access_panel'));
    	$data['title']  			  = 'Set Kostum';
    	$config = array(
    		array(
    			'field' => 'search',
    			'label' => 'Search',
    			'rules' => 'trim'
    		),
    	);

    	$this->form_validation->set_rules($config);

    	$data['geturl']                 = $this->input->get(null,true);
		$data['table_data']			  = 'set_custom'; // element id table
		$data['ajax_data_table']	  = 'adminsite/set_custom/datatables_order'; //Controller ajax data
		$data['datatables_ajax_data'] = array(
			//$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'],'')
			$this->custom_lib->datatables_ajax_setcustom(TRUE,$data['table_data'],$data['ajax_data_table'],'')
		);
        //View
		$data['load_view'] = 'adminsite/v_set_custom';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function karyawan(){
		$data['session_items']        = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  			  = 'Set Kostum - List Karyawan';
		$config = array(
			array(
				'field' => 'search',
				'label' => 'Search',
				'rules' => 'trim'
			),
		);

		$this->form_validation->set_rules($config);

		$data['geturl']               = $this->input->get(null,true);
		$data['table_data']			  = 'karyawan'; // element id table
		$data['ajax_data_table']	  = 'adminsite/set_custom/datatables_order_karyawan'; //Controller ajax data
		$data['datatables_ajax_data'] = array(
			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])
		);
        //View
		$data['load_view'] = 'adminsite/v_set_custom_karyawan';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function form_karyawan(){
		$this->form_validation->set_rules('karyawan_id','ID','trim');
		$this->form_validation->set_rules('karyawan_nama','Karyawan Nama','trim|required');

		if($this->form_validation->run() == false){

			$return = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
			);

		} else {

			$karyawan_id 						= $this->input->post('karyawan_id');
			$karyawan_nama 						= $this->input->post('karyawan_nama');

			if(empty($karyawan_id)){
			//CATEGORY
				$insert_karyawan = array(
					'karyawan_nama'		=> $karyawan_nama,
					'created'			=> date('c'),
					'modified'			=> NULL
				);

				$result_insert 	= $this->global_model->insert('set_custom_karyawan',$insert_karyawan);

				$return = array(
					'flag'		=>	true, 
					'process' 	=>	'insert',
				);

			} else {
				$update_karyawan = array(
					'karyawan_nama'	=> $karyawan_nama,
					'modified'		=> date('c'),
				);
				$updated = $this->global_model->update('set_custom_karyawan',$update_karyawan,array('karyawan_id' => $karyawan_id));

				$return = array(
					'flag'		=>	true, 
					'process' 	=> 'update'
				);
			}

		}

		echo json_encode($return);

	}

	public function karyawan_edit(){
		$id 		= $this->input->post('category_id');
		$query 		= $this->global_model->select_where('set_custom_karyawan',array('karyawan_id' => $id));

		if(!empty($query)){
			$data = array();
			foreach($query as $index => $value){
				$data = array(
					'karyawan_id'					=> $value['karyawan_id'],
					'karyawan_nama'					=> $value['karyawan_nama']
				);
			}
			$result = array(
				'data' 		=> $data,
				'flag' 		=> true,
				'query'		=> $query
			);
		} else {
			$result = array(
				'message' 	=> 'Nothing found data.',
				'flag'		=> false,
				'query'		=> $query
			);
		}

		echo json_encode($result);
	}

	public function add_item(){
		$this->form_validation->set_rules('product_id','Product','trim|required');
		$this->form_validation->set_rules('product_sizestock_id','Product Size','trim|required');
		$this->form_validation->set_error_delimiters('', '');

		if($this->form_validation->run() == false){
			$result = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
			);
		} else {
			$result = array(
				'flag'              =>  true, 
				'process'           =>  'insert'
			);
		}
		echo json_encode($result);
	}

	public function get_sizestock(){
		$id = $this->input->post('id');
		$image              = $this->backend_model->get_thumbnail_product($id);
		$v_image            = 'assets/images/no-image.png';
		$thumbnail_image    = $v_image;
		if(!empty($image)){
			if(getimagesize($image[0]['product_image'])){
				$v_image = $image[0]['product_image'];
			}
			$thumbnail_image    = $v_image;
		}
		$sizestock  = $this->global_model->select_where('product_sizestock',array('product_id' => $id));
		$template   = '';
		$result     = array();
		if(!empty($sizestock)){
			$template .= '<option value="">Select Size</option>';
			foreach($sizestock as $index => $value){
				$template .= '<option value="'.$value['product_sizestock_id'].'">'.$value['product_size'].'</option>';
			}
			$result = array(
				'thumbnail'     => $thumbnail_image,
				'template'      => $template,
				'flag'          => true
			);
		} else {
			$result = array(
				'message'   => 'Nothing found data.',
				'flag'      => false
			);
		}
		echo json_encode($result);
	}

	public function add(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  				= 'Add New Set Kostum';
		$data['karyawan'] 				= $this->global_model->select_order('set_custom_karyawan','created','desc');
		$data['product']				= $this->backend_model->getProdukForSetCustom();

		$this->form_validation->set_rules('karyawan_id','Karyawan','trim');
		$this->form_validation->set_rules('product_id','Product','trim');
		$this->form_validation->set_rules('product_sizestock_id','Product Size','trim');
		$this->form_validation->set_rules('note','Note','trim');

		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',validation_errors());
			}
			$data['load_view'] = 'adminsite/v_set_custom_add';
			$this->load->view('adminsite/template/backend', $data);
		} else {
			
			$karyawan_id 			= $this->input->post('karyawan_id');
			$product_id  			= $this->input->post('product_id');
			$product_sizestock_id 	= $this->input->post('product_sizestock_id');
			$note 					= $this->input->post('note');

			$getKaryawan 			= $this->backend_model->getKaryawanNamaForSetCustom($karyawan_id);
			$karyawan_nama 			= (!empty($getKaryawan)) ? $getKaryawan['karyawan_nama'] : '';

			$result['detail'] 		= array();

			if(is_array($product_id) && !empty($product_id)){
				foreach($product_id as $index => $value){
					if(isset($product_sizestock_id[$index])){
						$getProduct 			= $this->backend_model->getProdukNamaForSetCustom($product_sizestock_id[$index]);
						if(!empty($getProduct)){
							foreach($getProduct as $key => $row){
								$result['detail'][] 	= array(
									'product_id'	 		=> $row['product_id'],
									'product_nama'   		=> $row['product_nama'],
									'product_kode'	 		=> $row['product_kode'],
									'product_sizestock_id' 	=> $row['product_sizestock_id'],
									'product_size'			=> $row['product_size'],
									'note'					=> $note[$index]
								);
							}
						}
					}
				}
			}

			$result['set_custom'] 		= array(
				'karyawan_id'	 => $karyawan_id,
				'karyawan_nama'  => $karyawan_nama,
				'created'		 => date('c')
			);

			$this->global_model->insert('set_custom',$result['set_custom']);
			$set_custom_id 	= $this->db->insert_id();
			
			if(!empty($result['detail'])){
				foreach($result['detail'] as $index => $value){
					$set_custom_detail = array(
						'set_custom_id' => $set_custom_id,
						'product_id'	 		=> $value['product_id'],
						'product_nama'   		=> $value['product_nama'],
						'product_kode'	 		=> $value['product_kode'],
						'product_sizestock_id' 	=> $value['product_sizestock_id'],
						'product_size'			=> $value['product_size'],
						'note'					=> $value['note']
					);
					$this->global_model->insert('set_custom_detail',$set_custom_detail);
				}
			}
			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/set_custom');
		}

	}

	public function edit($id){

		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  				= 'Edit Set Kostum';

		$id 							= $this->uri->segment(4);
		$data['id'] 					= $id;
		$data['karyawan'] 				= $this->global_model->select_order('set_custom_karyawan','created','desc');
		$data['product']				= $this->backend_model->getProdukForSetCustom();
		$data['get']					= $this->backend_model->getEditSetCustom($id);
		$data['items']					= $this->backend_model->getItemsEditSetCustom($id);

		$this->form_validation->set_rules('karyawan_id','Karyawan','trim');
		$this->form_validation->set_rules('product_id','Product','trim');
		$this->form_validation->set_rules('product_sizestock_id','Product Size','trim');
		$this->form_validation->set_rules('note','Note','trim');

		if($this->form_validation->run() == false){
			if(validation_errors()){
				$this->session->set_flashdata('validation',validation_errors());
			}
			$data['load_view'] = 'adminsite/v_set_custom_edit';
			$this->load->view('adminsite/template/backend', $data);
		} else {

			$set_custom_detail_id   = $this->input->post('set_custom_detail_id');
			$karyawan_id 			= $this->input->post('karyawan_id');
			$product_id  			= $this->input->post('product_id');
			$product_nama 			= $this->input->post('product_nama');
			$product_size 			= $this->input->post('product_size');
			$product_sizestock_id 	= $this->input->post('product_sizestock_id');
			$note 					= $this->input->post('note');

			$getKaryawan 			= $this->backend_model->getKaryawanNamaForSetCustom($karyawan_id);
			$karyawan_nama 			= (!empty($getKaryawan)) ? $getKaryawan['karyawan_nama'] : '';

			$result['set_custom'] 		= array(
				'karyawan_id'	 => $karyawan_id,
				'karyawan_nama'  => $karyawan_nama,
				'created'		 => date('c')
			);

			$this->global_model->update('set_custom',$result['set_custom'],array('set_custom_id'=>$id));

			if(isset($set_custom_detail_id) && is_array($set_custom_detail_id)){
				$update = array();
				$no = 0;
				$set_custom_detail_id_db = array();

				foreach($data['items'] as $index => $value){
					$set_custom_detail_id_db[] = $value['set_custom_detail_id'];
				}
				foreach($set_custom_detail_id as $index => $value){
					if(!empty($value) && in_array($value,$set_custom_detail_id_db)){
						$get_product_kode = $this->backend_model->getKodeProductEditSetCustom($product_id[$index]);
						$update = array(
							'set_custom_id' 		=> $id,
							'product_id'	 		=> $product_id[$index],
							'product_nama'   		=> $product_nama[$index],
							'product_kode'	 		=> $get_product_kode['product_kode'],
							'product_sizestock_id' 	=> $product_sizestock_id[$index],
							'product_size'			=> $product_size[$index],
							'note'					=> $note[$index]
						);
						$this->global_model->update('set_custom_detail',$update,array('set_custom_detail_id' => $value));

					} elseif(empty($value)){
						$get_product_kode = $this->backend_model->getKodeProductEditSetCustom($product_id[$index]);
						$insert = array(
							'set_custom_id' 		=> $id,
							'product_id'	 		=> $product_id[$index],
							'product_nama'   		=> $product_nama[$index],
							'product_kode'	 		=> $get_product_kode['product_kode'],
							'product_sizestock_id' 	=> $product_sizestock_id[$index],
							'product_size'			=> $product_size[$index],
							'note'					=> $note[$index]
						);
						$this->global_model->insert('set_custom_detail',$insert);

					}
				}

				foreach($data['items'] as $index => $value){
					if(!in_array($value['set_custom_detail_id'],$set_custom_detail_id)){
						$this->global_model->delete('set_custom_detail',array('set_custom_detail_id' => $value['set_custom_detail_id']));
					}
				}
			} else {
				$this->global_model->delete('set_custom_detail',array('set_custom' => $id));
			}

			$this->session->set_flashdata('success','Save success');
			redirect('adminsite/set_custom/edit/'.$id);
		}

	}

	public function karyawan_delete(){

		$id = $this->input->post('category_id');

		$delete = $this->global_model->delete('set_custom_karyawan',array('karyawan_id' => $id));
		if($delete){

			$return = array('flag'=>true);

		} else {

			$return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');

		}

		echo json_encode($return);

	}

	public function karyawan_multiple_delete(){

		$id = $this->input->post('id',true);
		$update = false;

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$delete = $this->global_model->delete('set_custom_karyawan',array('karyawan_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function delete(){

		$id = $this->input->post('category_id');

		$delete = $this->global_model->delete('set_custom',array('set_custom_id' => $id));
		$delete = $this->global_model->delete('set_custom_detail',array('set_custom_id' => $id));

		if($delete){

			$return = array('flag'=>true);

		} else {

			$return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');

		}

		echo json_encode($return);

	}

	public function multiple_delete(){

		$id = $this->input->post('id',true);
		$update = false;

		if(is_array($id) && !empty($id)){

			for($i=0; $i<count($id); $i++){

				$delete = $this->global_model->delete('set_custom',array('set_custom_id' => $id[$i]));
				$delete = $this->global_model->delete('set_custom_detail',array('set_custom_id' => $id[$i]));

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