<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('front_model');
		$this->load->model('global_model');
	}

	public function index($product = ''){
		$product		 		= $this->uri->segment(2);
		$tempParam 				= array();
		$sizestock_param 		= false;

		if(!empty($product)){
			$tempSize 			= explode("-", $product);

			if(!empty($tempSize) && isset($tempSize[1])){
				$sizestock_param= $this->front_model->existSizeInPageProduct(strtoupper($tempSize[0]),$tempSize[1]);
			}
		}

		if($sizestock_param){
			redirect(base_url().'product/'.$sizestock_param['product_slug'].'/'.$sizestock_param['product_sizestock_slug'],'location');
		} else {
			$this->load->view('v_404');
		}
	}
}

?>