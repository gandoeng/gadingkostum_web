<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller{

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

	function cal_days_in_year($year){
		$days=0; 
		for($month=1;$month<=12;$month++){ 
			$days = $days + cal_days_in_month(CAL_GREGORIAN,$month,$year);
		}
		return $days;
	}

	function cal_days_in_month($month,$year){
		$days = 0; 

		for($month=1;$month<=12;$month++){ 
			$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		}
		return $days;
	}

	function get_range_days($start,$end){
		$datetime1 = new DateTime($start);
		$datetime2 = new DateTime($end);
		$difference = $datetime1->diff($datetime2);
		return $difference->days;
	}

	public function index(){
		$data['session_items']  = $this->session->userdata($this->config->item('access_panel'));
		$data['title']  		= 'Report';
		$filter  				= $this->input->get('filter_by',true);
		$sales_by 				= $this->input->get('sales_by',true);
		$prod_id 				= $this->input->get('prod_id',true);

		$year  	 		= date('Y');
		$month 	 		= date('m');
		$previous_month = date("m", strtotime("previous month"));
		$query   	= array();
		$grafik     = array();
		$count_avg  = 0;
		$count   	= 0;
		$data['display'] = false;//flag
		$data['items']   = 0;
		$data['prices']  = 0;
		$data['grafik'] 		 	= array();
		$data['grafik']['labels'] 	= array();
		$data['grafik']['sales'] 	= array();
		$data['grafik']['kostum'] 	= array();
		$data['grafik']['order'] 	= array();

		switch ($sales_by) {
			case 'date':
			switch ($filter) {
				case 'year':
				$grafik 	= $this->backend_model->get_report_grafik_year($year);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$query 		= $this->backend_model->get_report_order_year($year);
				$count_avg  = $this->get_range_days(date('Y').'-01-01',date('Y-m-d'));
				//$count_avg 	= $this->cal_days_in_year($year);
				break;
				case 'lastmonth':
				$grafik = $this->backend_model->get_report_grafik_lastmonth();

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$query 		 = $this->backend_model->get_report_order_lastmonth();
				//$count_avg   = $this->get_range_days(date("Y-m-d", strtotime("first day of last month")),date('Y-m-d'));
				$count_avg  = cal_days_in_month(CAL_GREGORIAN,$previous_month,$year);
				break;
				case 'thismonth':
				$grafik = $this->backend_model->get_report_grafik_thismonth($year,$month);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$query = $this->backend_model->get_report_order_thismonth($year,$month);
				$count_avg   = $this->get_range_days(date('Y-m-01'),date('Y-m-d'));
				//$count_avg  = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				break;
				case 'lastdays':
				$grafik = $this->backend_model->get_report_grafik_lastdays();

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$query  = $this->backend_model->get_report_order_lastdays();
				$count_avg  = 7;
				break;
				case 'custom':
				$start = $this->input->get('start',true);
				$start = str_replace('/', '-', $start);
				$start = date('Y-m-d', strtotime($start));
				$end   = $this->input->get('end',true);
				$end   = str_replace('/', '-', $end);
				$end   = date('Y-m-d', strtotime($end));

				$strtotimestart = strtotime($start);
				$strtotimeend   = strtotime($end);
				$count_avg 	    = $strtotimeend - $strtotimestart;
				$count_avg      = round($count_avg / (60 * 60 * 24));

				$grafik 		= $this->backend_model->get_report_grafik_custom($start,$end);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$query 			= $this->backend_model->get_report_order_custom($start,$end);
				break;
			}
			break;
			
			case 'product':

			$order 					 = array('product_nama' => 'asc');
			$data['product'] 		 = $this->backend_model->get_join_by_id('product','','','',$order);
			$data['check_product']   = $this->backend_model->get_check_report_product($prod_id);
			$data['display']		 = true;

			switch ($filter) {
				case 'year':
				$grafik 	= $this->backend_model->get_report_product_grafik_year($prod_id,$year);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$items 				= $this->backend_model->get_report_product_year($prod_id,$year);
				$price 				= $this->backend_model->get_report_product_sum_year($prod_id,$year);

				if(!empty($items) && !empty($items[0]['total_kostum'])){
					$data['items']  = $items[0]['total_kostum'];
				}

				if(!empty($price)){
					$data['prices']	= number_format($price[0]['sum']);
				}
				break;
				case 'lastmonth':

				$grafik 	= $this->backend_model->get_report_product_grafik_lastmonth($prod_id);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$items 				= $this->backend_model->get_report_product_lastmonth($prod_id);
				$price 				= $this->backend_model->get_report_product_sum_lastmonth($prod_id);

				if(!empty($items) && !empty($items[0]['total_kostum'])){
					$data['items']  = $items[0]['total_kostum'];
				}

				if(!empty($price)){
					$data['prices']	= number_format($price[0]['sum']);
				}
				break;
				case 'thismonth':
				$grafik 	= $this->backend_model->get_report_product_grafik_thismonth($prod_id,$year,$month);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$items 				= $this->backend_model->get_report_product_thismonth($prod_id,$year,$month);
				$price 				= $this->backend_model->get_report_product_sum_thismonth($prod_id,$year,$month);

				if(!empty($items) && !empty($items[0]['total_kostum'])){
					$data['items']  = $items[0]['total_kostum'];
				}

				if(!empty($price)){
					$data['prices']	= number_format($price[0]['sum']);
				}
				break;
				case 'lastdays':
				$grafik = $this->backend_model->get_report_product_grafik_lastdays($prod_id);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$items 				= $this->backend_model->get_report_product_lastdays($prod_id);
				$price 				= $this->backend_model->get_report_product_sum_lastdays($prod_id);

				if(!empty($items) && !empty($items[0]['total_kostum'])){
					$data['items']  = $items[0]['total_kostum'];
				}

				if(!empty($price)){
					$data['prices']	= number_format($price[0]['sum']);
				}

				break;
				case 'custom':
				$start = $this->input->get('start',true);
				$start = str_replace('/', '-', $start);
				$start = date('Y-m-d', strtotime($start));
				$end   = $this->input->get('end',true);
				$end   = str_replace('/', '-', $end);
				$end   = date('Y-m-d', strtotime($end));

				$strtotimestart = strtotime($start);
				$strtotimeend   = strtotime($end);
				$count_avg 	    = $strtotimeend - $strtotimestart;
				$count_avg      = round($count_avg / (60 * 60 * 24));

				$grafik 		= $this->backend_model->get_report_product_grafik_custom($prod_id,$start,$end);

				if(!empty($grafik)){
					foreach($grafik as $index => $value){
						$data['grafik']['labels'][] = '"'.date('d M',strtotime($value['labels'])).'"';
						$data['grafik']['sales'][]  = number_format($value['total_hargasewa'],0,'.','');
						$data['grafik']['kostum'][]  = '"'.$value['total_kostum'].'"';
						$data['grafik']['order'][]  = '"'.$value['total_order'].'"';
					}
				}

				$items 				= $this->backend_model->get_report_product_custom($prod_id,$start,$end);
				$price 				= $this->backend_model->get_report_product_sum_custom($prod_id,$start,$end);

				if(!empty($items) && !empty($items[0]['total_kostum'])){
					$data['items']  = $items[0]['total_kostum'];
				}

				if(!empty($price)){
					$data['prices']	= number_format($price[0]['sum']);
				}
				break;
			}
			break;
		}

		//exit;
		$id    = array();
		if(!empty($query)){
			$count = count($query);
			foreach($query as $index => $value){
				$id[] = $value['rental_order_id'];
			}
		}

		if(!empty($id) && $sales_by == 'date'){
			$price 				= $this->backend_model->get_report_sum($id);
			$data['prices']		= number_format($price[0]['sum']);
			$data['average'] 	= number_format($price[0]['sum'] / $count_avg);
			$data['orders']		= $count;

			$items 				= $this->backend_model->get_report_order_product($id);

			$count_items = 0;
			foreach($items as $index => $value){
				$count_items += $value['items'];
			}

			$data['items']		= $count_items;
			$data['display']	= true;
		}

		$data['query'] = $query;

		$data['load_view'] = 'adminsite/v_report';
		$this->load->view('adminsite/template/backend', $data);

	}

}

?>