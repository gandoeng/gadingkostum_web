<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slideshow extends CI_Controller{

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

	public function index(){
		$data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
		//$data['get'] = $this->backend_model->getData('slideshow',$order);
		$data['title']  = 'Slideshow';
		/*$where = array(
			'category_flag' => 'size'
			);

		$order = array(
			'category_order' => 'asc',
			'category_created' => 'desc'
			);

			$data['get'] 			= $this->global_model->getDataWhereOrder('category',$where,$order);*/
		$data['table_data']		= 'slideshow'; // element id table
		$data['ajax_sort_url'] 	= 'adminsite/slideshow/order'; // Controller row order data
		$data['ajax_data_table']= 'adminsite/slideshow/datatables'; //Controller ajax data
		/*$this->custom_lib->datatables_roworder(TRUE,'#slideshow','panel/slideshow/sort')*/

		$data['datatables_ajax_data'] = array(

			$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'])

			);

        //View
		$data['load_view'] = 'adminsite/v_slideshow';
		$this->load->view('adminsite/template/backend', $data);

	}

	public function form(){
		$this->form_validation->set_rules('slideshow_id','Slideshow','trim');
		$this->form_validation->set_rules('slideshow_image','Image','trim|required');
		$this->form_validation->set_rules('slideshow_order','Order','trim');
		$this->form_validation->set_rules('status','Category Status','trim');

		if($this->form_validation->run() == false){

			$return = array(
				'process' => 'validation',
				'message' => validation_errors(),
				'flag'    => false
				);

		} else {

			$slideshow_id 						= $this->input->post('slideshow_id');
			$slideshow_image 					= $this->input->post('slideshow_image');
			$slideshow_order					= $this->input->post('slideshow_order');
			$slideshow_status 					= $this->input->post('status');
			$slideshow_video 					= '';
			$slideshow_flag 					= 'image';
			$slideshow_video_id 				= '';
			$checkfile = $this->videoType($slideshow_image);

			if($checkfile == 'youtube' || $checkfile == 'vimeo'){
				$slideshow_video 	= $slideshow_image;
				$slideshow_video_id = $this->getYoutubeIdFromUrl($slideshow_image);
				if($checkfile == 'youtube'){
					$slideshow_flag  	= 'youtube';
				} elseif($checkfile == 'vimeo'){
					$slideshow_flag 	= 'vimeo';
				}
			} elseif($checkfile == 'unknown'){
				$slideshow_image 	= '';
				$slideshow_video 	= '';
				$slideshow_video_id = '';
				$slideshow_flag  	= 'unknown';
			} else {
				$slideshow_image = str_replace(base_url(),'', $slideshow_image);
			}

			if(empty($slideshow_status)){
				$slideshow_status 	= 0;
			}
			if(empty($slideshow_id)){
			//CATEGORY
				$insert_slideshow = array(
					'slideshow_image'			=> $slideshow_image,
					'slideshow_video'			=> $slideshow_video,
					'slideshow_video_id'		=> $slideshow_video_id,
					'slideshow_flag'			=> $slideshow_flag,
					'slideshow_status'			=> $slideshow_status,
					'slideshow_order'			=> $slideshow_order,
					'slideshow_created'			=> date('c'),
					'slideshow_modified'		=> NULL
					);

				$result_insert 	= $this->global_model->insert('slideshow',$insert_slideshow);

				$return = array(
					'flag'		=>	true, 
					'process' 	=>	'insert',
					);

			} else {
				$update_slideshow = array(
					'slideshow_image'			=> $slideshow_image,
					'slideshow_video'			=> $slideshow_video,
					'slideshow_video_id'		=> $slideshow_video_id,
					'slideshow_flag'			=> $slideshow_flag,
					'slideshow_status'			=> $slideshow_status,
					'slideshow_order'			=> $slideshow_order,
					'slideshow_modified'		=> date('c'),
					);
				$updated = $this->global_model->update('slideshow',$update_slideshow,array('slideshow_id' => $slideshow_id));

				$return = array(
					'flag'		=>	true, 
					'process' 	=> 'update'
					);
			}

		}

		echo json_encode($return);

	}

	public function edit_display_data(){
		$id 		= $this->input->post('category_id');
		$query 		= $this->global_model->select_where('slideshow',array('slideshow_id' => $id));

		if(!empty($query)){
			$data = array();
			$slideshow_image 			= '';
			foreach($query as $index => $value){
				$slideshow_image				= $value['slideshow_image'];

				$data = array(
					'slideshow_id'						=> $value['slideshow_id'],
					'slideshow_order'					=> $value['slideshow_order'],
					'status'							=> $value['slideshow_status'],
					);
			}
			$result = array(
				'data' 			=> $data,
				'image'			=>
				array(
					array(
						'name' 	=> 'slideshow_image',
						'value' => $slideshow_image
						),
					),
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

	public function delete(){

		$id = $this->input->post('category_id');

		$delete = $this->global_model->delete('slideshow',array('slideshow_id' => $id));
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

					'slideshow_status' 		=> 1,
					'slideshow_modified' 	=> date('c')

					);

				$update = $this->global_model->update('slideshow',$data,array('slideshow_id' => $id[$i]));

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
					'slideshow_status' 		=> 0,
					'slideshow_modified' 	=> date('c')
					);

				$update = $this->global_model->update('slideshow',$data,array('slideshow_id' => $id[$i]));

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

				$delete = $this->global_model->delete('slideshow',array('slideshow_id' => $id[$i]));
			}
			$update = true;
		}
		echo json_encode($update);
	}

	public function datatables(){
		$order_array = array(
			'slideshow_created' => 'desc'
			);
		$query 		= $this->backend_model->get_join_by_id('slideshow','','*','',$order_array);				
		$data = array();
		if(!empty($query)){

			foreach($query->result() as $index => $value) {

				$status = '';
				if($value->slideshow_status == 1){
					$status = '<span class="label label-success">Yes</span>';
				} else {
					$status = '<span class="label label-danger">No</span>';
				}
				$slideshow_flag 				= $value->slideshow_flag;
				$slideshow_video_id 			= $value->slideshow_video_id;
				
				$action 	= '';
				$action = '<a class="btn-edit-action btn-ajax-edit-action btn btn-primary btn-sm btn-flat" data-item="'.$value->slideshow_id.'" data-url="'.base_url('adminsite/slideshow/edit_display_data').'" style="margin-right: 5px;">Edit</a>';
				$action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value->slideshow_id.'" data-url="'.base_url('adminsite/slideshow/delete').'">Delete</a>';

				if(!empty($value->slideshow_image) && $slideshow_flag == 'image'){
					$image  = '<img class="img-thumbnail" src="'.$value->slideshow_image.'">';
				} else {
					$image 	= '<img class="img-thumbnail" src="assets/images/no-thumbnail.png">';
				}

				if($slideshow_flag == 'youtube'){
					$image 			= '<img class="img-thumbnail" src="http://img.youtube.com/vi/'.$slideshow_video_id.'/sddefault.jpg">';
				}

				$data[] = array(
					'<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value->slideshow_id.'"></div>',
					$image,
					$status,
					$action
					);

			}

		}

		$result = $this->custom_lib->datatables_data($query,$data);
		echo json_encode($result);

	}

	function getYoutubeIdFromUrl($url) {
		$parts = parse_url($url);
		if(isset($parts['query'])){
			parse_str($parts['query'], $qs);
			if(isset($qs['v'])){
				return $qs['v'];
			}else if(isset($qs['vi'])){
				return $qs['vi'];
			}
		}
		if(isset($parts['path'])){
			$path = explode('/', trim($parts['path'], '/'));
			return $path[count($path)-1];
		}
		return false;
	}

	function videoType($url) {
		if (strpos($url, 'youtube') > 0) {
			return 'youtube';
		} elseif (strpos($url, 'vimeo') > 0) {
			return 'vimeo';
		} elseif(exif_imagetype($url)) {
			return 'image';
		} else {
			return 'unknown';
		}
	}

}

?>