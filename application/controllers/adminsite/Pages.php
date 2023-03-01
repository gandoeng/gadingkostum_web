<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Pages extends CI_Controller{





	public function __construct(){

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





	public function index(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  			  = 'Pages';

		$data['table_data']			  = 'pages'; // element id table

		$data['ajax_sort_url'] 		  = 'adminsite/pages/order'; // Controller row order data

		$data['ajax_data_table']	  = 'adminsite/pages/datatables'; //Controller ajax data

		$data['datatables_ajax_data'] = array(

			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])

			);

        //View

		$data['load_view'] = 'adminsite/v_pages';

		$this->load->view('adminsite/template/backend', $data);



	}





	public function add(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Add New Page';

		$this->form_validation->set_rules('page_title','Title','trim');

		$this->form_validation->set_rules('page_image','Image','trim');

		$this->form_validation->set_rules('page_description_thumbnail','Description Thumbnail','trim');

		$this->form_validation->set_rules('page_description','Description','trim');

		$this->form_validation->set_rules('page_metatitle','Meta Title','trim');

		$this->form_validation->set_rules('page_metakeyword','Meta Keyword','trim');

		$this->form_validation->set_rules('page_metadescription','Meta Description','trim');

		$this->form_validation->set_rules('status','Status','trim');



		if($this->form_validation->run() == false){

			if(validation_errors()){

				$this->session->set_flashdata('validation',json_encode(validation_errors()));

			}

			$data['load_view'] = 'adminsite/v_pages_add';

			$this->load->view('adminsite/template/backend', $data);



		} else {



			$page_title 					= $this->input->post('page_title');

			$page_image 					= $this->input->post('page_image');

			$page_description_thumbnail 	= $this->input->post('page_description_thumbnail');

			$page_description 				= $this->input->post('page_description');

			$page_metatitle 				= $this->input->post('page_metatitle');

			$page_metakeyword				= $this->input->post('page_metakeyword');

			$page_metadescription			= $this->input->post('page_metadescription');

			$page_status 					= $this->input->post('status');



			if(empty($page_status)){

				$page_status 	= 0;

			}



			$insert_page = array(

				'page_title'					=> $page_title,

				'page_image'					=> str_replace(base_url(),'',$page_image),

				'page_description'				=> $page_description,

				'page_description_thumbnail'	=> $page_description_thumbnail,

				'page_metatitle'				=> $page_metatitle,

				'page_metakeyword'				=> $page_metakeyword,

				'page_metadescription'			=> $page_metadescription,

				'page_status'					=> $page_status,

				'page_created'					=> date('c'),

				'page_modified'					=> NULL

			);



			$result_page 	= $this->global_model->insert('pages',$insert_page);

			$id 			= $this->db->insert_id();

			

			if(empty($page_slug)){

				$slug 		= $this->backend_model->createSlug('pages','page_id', $id,'page_slug',$page_title);

			}



			$insert_slug = array(

				'page_slug' => $slug

			);



			$this->global_model->update('pages', $insert_slug, array('page_id' => $id));



			$this->session->set_flashdata('success','Save success');

			redirect('adminsite/page','refresh');

		}

	}





	public function edit(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  = 'Edit Page';

		$id 			= $this->uri->segment(4);

		$data['id'] 	= $id;

		$data['page']	= $this->global_model->select_where('pages', array('page_id' => $id));

		$this->form_validation->set_rules('page_image','Image','trim');

		$this->form_validation->set_rules('page_description_thumbnail','Description Thumbnail','trim');

		$this->form_validation->set_rules('page_description','Description','trim');

		$this->form_validation->set_rules('page_metatitle','Meta Title','trim');

		$this->form_validation->set_rules('page_metakeyword','Meta Keyword','trim');

		$this->form_validation->set_rules('page_metadescription','Meta Description','trim');

		$this->form_validation->set_rules('status','Status','trim');





		if($this->form_validation->run() == false){

			if(validation_errors()){

				$this->session->set_flashdata('validation',json_encode(validation_errors()));

			}

			$data['load_view'] = 'adminsite/v_pages_edit';

			$this->load->view('adminsite/template/backend', $data);



		}else{

			$page_image 					= $this->input->post('page_image');

			$page_description_thumbnail 	= $this->input->post('page_description_thumbnail');

			$page_description 				= $this->input->post('page_description');

			$page_metatitle 				= $this->input->post('page_metatitle');

			$page_metakeyword				= $this->input->post('page_metakeyword');

			$page_metadescription			= $this->input->post('page_metadescription');

			// $page_status 					= $this->input->post('status');

			$page_status 					= '1';



			if(empty($page_status)){

				$page_status 	= 0;

			}



			// $slug = $this->backend_model->createSlug('pages','page_id',$id,'page_slug',$page_title);



			$update_page = array(

				'page_image'					=> str_replace(base_url(),'',$page_image),

				'page_description'				=> $page_description,

				'page_description_thumbnail'	=> $page_description_thumbnail,

				'page_metatitle'				=> $page_metatitle,

				'page_metakeyword'				=> $page_metakeyword,

				'page_metadescription'			=> $page_metadescription,

				// 'page_status'					=> $page_status,

				// 'page_slug'						=> $slug,

				'page_modified'					=> date('c')

				);



			$result = $this->global_model->update('pages',$update_page,array('page_id' => $id));



			$this->session->set_flashdata('success','Save success');

			redirect('adminsite/pages/edit/'.$id);

		}



	}





	public function delete(){

		$id = $this->input->post('category_id');

		$delete = $this->global_model->delete('pages', array('page_id' => $id));

		if($delete){

			$return = array('flag'=>true);

		}else{

			$return = array('flag'=>false, 'message' => 'Sorry, Nothing change data.');

		}

		echo json_encode($return);

	}



	public function publish(){



		$id = $this->input->post('id');

		$update = false;



		if(is_array($id) && !empty($id)){



			for($i=0; $i<count($id); $i++){



				$data = array(



					'page_status' 		=> 1,

					'page_modified' 	=> date('c')



					);



				$update = $this->global_model->update('pages',$data,array('page_id' => $id[$i]));



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

					'page_status' 		=> 0,

					'page_modified' 		=> date('c')

					);



				$update = $this->global_model->update('pages',$data,array('page_id' => $id[$i]));



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



				$delete = $this->global_model->delete('pages',array('page_id' => $id[$i]));

			}

			$update = true;

		}

		echo json_encode($update);

	}



	public function datatables(){

		$order_array = array(

			'page_created' => 'desc'

			);

		$query 		= $this->backend_model->get_join_by_id('pages','','*','',$order_array);				

		$data = array();

		if(!empty($query)){



			foreach($query->result() as $index => $value) {



				$status = '';

				if($value->page_status == 1){

					$status = '<span class="label label-success">Yes</span>';

				} else {

					$status = '<span class="label label-danger">No</span>';

				}



				$action = '';

				$action = '<a class="btn-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value->page_id.'" href="'.base_url('adminsite/pages/edit/').$value->page_id.'" style="margin-right: 5px;">Edit</a>';

				// $action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value->page_id.'" data-url="'.base_url('adminsite/pages/delete').'">Delete</a>';



				if(!empty($value->page_image)){

					$image  = '<img class="img-thumbnail" src="'.$value->page_image.'">';

				} else {

					$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';

				}





				$data[] = array(

					$value->page_title,

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