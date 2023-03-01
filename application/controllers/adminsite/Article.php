<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller{

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

	public function index(){
		$data['session_items']        = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  			  = 'Article';
		$data['table_data']			  = 'article'; // element id table
		$data['ajax_sort_url'] 		  = 'adminsite/article/order'; // Controller row order data
		$data['ajax_data_table']	  = 'adminsite/article/datatables'; //Controller ajax data
		$data['datatables_ajax_data'] = array(
			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])
			);
        //View
		$data['load_view'] = 'adminsite/v_article';
		$this->load->view('adminsite/template/backend', $data);

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