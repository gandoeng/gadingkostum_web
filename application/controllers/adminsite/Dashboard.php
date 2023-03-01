<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
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

            if($already_login !== false){
                $this->session_items = data_session($this->session->userdata($this->config->item('access_panel')));
                $this->start_session = $this->session_items;
            }

        } else {
            redirect('adminsite','refresh');
        }
    }

    public function rental_by_product(){
        $prod_id    = '';
        //$prod_id    = 1409;
        $stock_id   = '';
        //$stock_id   = 1957;
        if(!empty($prod_id) && !empty($stock_id)){
        $q          = $this->global_model->select_where('rental_product',array('product_id'=>$prod_id,'rental_product_sizestock_id'=>$stock_id));
        $rental_order_id = array();
        foreach($q as $index => $value){
            $rental_order_id[] = $value['rental_order_id'];
        }
        foreach($rental_order_id as $index => $value){
            $q1 = $this->global_model->select_where('rental_order',array('rental_order_id'=> $value));
            echo '<pre>';
            print_r($q1);
            echo '</pre>';
        }
        } else {
            echo 'insert product id & stock id in variable';
        }
    }

    public function insert_pop(){
        $get                    = $this->input->post('geturl');
        if(!empty($get)){
            $get                = unserialize(base64_decode($get));
        }
        $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $filter                 = (isset($get['filter']) && !empty($get['filter'])) ? trim($get['filter']) : '';
        $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
        $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';
        $startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : '';
        $startdate              = str_replace('/', '-', $startdate);
        $startdate              = date('Y-m-d', strtotime($startdate));
        $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : '';
        $enddate                = str_replace('/', '-', $enddate);
        $enddate                = date('Y-m-d', strtotime($enddate));
        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordwithFilter  = 0;
        $query                  = $this->backend_model->get_most_rented_product($searchValue,$filter,$startdate,$enddate,$order,$order_by,$start,$offset);
        $data                   = array();
        $template               = array();
        if(!empty($query)){
            foreach($query as $index => $value){
                $product_id             = $value['product_id'];
                $quantity               = (int) $value['rental_product_qty'];
                if(isset($count[$value['product_id']])){
                    $count[$product_id]+=$quantity;
                } else {
                    $count[$product_id]=$quantity;
                }
                $sum_qty = 0;
                foreach($count as $key => $row){

                    if($key == $product_id){
                        $sum_qty = $row;
                    }
                    $data[$product_id]              = $value;
                    $data[$product_id]['rented']    = $sum_qty;
                }
            }
            if(!empty($data)){
                $data = array_values($data);
                if(empty($order) && empty($order_by)){
                    $this->array_sort_by_column($data,'rented');
                }
                foreach($data as $index => $value){
                    $update = array(
                        'nama'       => $value['product_nama'],
                        'kode'       => $value['product_kode'],
                        'rented'     => $value['rented']
                    );
                    //$query = $this->global_model->update('product_popularity',$update,array('product_id'=>$value['product_id']));
                    //echo $query;
                    //echo '<br>';
                }
            }
        }
    }

    public function update_pop(){
        $query = $this->backend_model->temp_get_non_active_rental();
        if(!empty($query)){
            foreach($query as $index => $value){
                $q = $this->global_model->select_where('product_popularity',array('product_id' => $value['product_id']));
                if(!empty($q)){
                    foreach($q as $key => $row){
                        $update = array(
                            'rented' => $row['rented'] - $value['rental_product_qty']
                        );
                        //$upd = $this->global_model->update('product_popularity',$update,array('product_id' => $row['product_id']));
                        //echo $upd;
                        //echo '<br>';
                    }
                }
            }
        }
    }

    public function checking(){
        $id         = $this->input->get('id');
        $sizestock  = $this->input->get('size');
        $query      = $this->backend_model->checkingRentalWithProductAndSizestock($id,$sizestock);
        if(!empty($query)){
            echo '<pre>';
            print_r($query);
            echo '</pre>';
        } else {
            echo 'empty order';
        }
    }

/*    public function month1(){
        $current_month  = date('m');
        $first_day_month = date("Y-m-d", strtotime("first day of this month"));
        $last_day_month  = date("Y-m-d", strtotime("last day of this month"));
        $data['month_penjualan_order']   = $this->backend_model->get_total_penjualan_order_by_month($first_day_month,$last_day_month);
        $data['month_penjualan_rupiah']  = 0;
        $data['month_penjualan_kostum']  = 0;
        $month_penjualan_rupiah          = $this->backend_model->get_total_penjualan_rupiah_by_month($current_month);
        $month_penjualan_kostum          = $this->backend_model->get_total_penjualan_kostum_by_month($current_month);

        if(!empty($month_penjualan_rupiah)){
            foreach($month_penjualan_rupiah as $index => $value){
                if(!empty($value['total'])){
                    $data['month_penjualan_rupiah'] = 'Rp. '.number_format($value['total']);
                }
            }
        }

        if(!empty($month_penjualan_kostum)){
            foreach($month_penjualan_kostum as $index => $value){
                if(!empty($value['total'])){
                    $data['month_penjualan_kostum'] = number_format($value['total']);
                }
            }
        }

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }*/

    public function index() {

        $data['title'] = 'Dashboard';
        $data['session_items']           = $this->session->userdata($this->config->item('access_panel'));
        $current_date   = date('Y-m-d');
        $current_month  = date('m');
        $first_day_month = date("Y-m-d", strtotime("first day of this month"));
        $last_day_month  = date("Y-m-d", strtotime("last day of this month"));
        $config = array(
            array(
                'field' => 'search',
                'label' => 'Search',
                'rules' => 'trim'
                ),
            );
        $this->form_validation->set_rules($config);
        $data['geturl']                  = $this->input->get(null,true);
        $data['today_penjualan_order']   = $this->backend_model->get_total_penjualan_order_by_today($current_date);
        $data['today_penjualan_rupiah']  = 0;
        $data['today_penjualan_kostum']  = 0;
        $today_penjualan_rupiah          = $this->backend_model->get_total_penjualan_rupiah_by_today($current_date);
        $today_penjualan_kostum          = $this->backend_model->get_total_penjualan_kostum_by_today($current_date);

        $data['month_penjualan_order']   = $this->backend_model->get_total_penjualan_order_by_month($first_day_month,$last_day_month);
        $data['month_penjualan_rupiah']  = 0;
        $data['month_penjualan_kostum']  = 0;
        $month_penjualan_rupiah          = $this->backend_model->get_total_penjualan_rupiah_by_month($first_day_month,$last_day_month);
        $month_penjualan_kostum          = $this->backend_model->get_total_penjualan_kostum_by_month($first_day_month,$last_day_month);

        if(!empty($today_penjualan_rupiah)){
            foreach($today_penjualan_rupiah as $index => $value){
                if(!empty($value['total'])){
                    $data['today_penjualan_rupiah'] = 'Rp. '.number_format($value['total']);
                }
            }
        }

        if(!empty($today_penjualan_kostum)){
            foreach($today_penjualan_kostum as $index => $value){
                if(!empty($value['total'])){
                    $data['today_penjualan_kostum'] = number_format($value['total']);
                }
            }
        }

        if(!empty($month_penjualan_rupiah)){
            foreach($month_penjualan_rupiah as $index => $value){
                if(!empty($value['total'])){
                    $data['month_penjualan_rupiah'] = 'Rp. '.number_format($value['total']);
                }
            }
        }

        if(!empty($month_penjualan_kostum)){
            foreach($month_penjualan_kostum as $index => $value){
                if(!empty($value['total'])){
                    $data['month_penjualan_kostum'] = number_format($value['total']);
                }
            }
        }
        
        $data['count_all_rental_order'] = $this->backend_model->count_all_rental_order();
        $data['count_all_product']      = $this->backend_model->count_all_product();
        $count_rented                   = $this->backend_model->count_most_rented_product()->result_array();
        $prod = array();
        $count_product = 0;
        if(!empty($count_rented)){
            foreach($count_rented as $index => $value){
                $product_id             = $value['product_id'];
                $quantity               = (int) $value['rental_product_qty'];
                if(isset($count[$value['product_id']])){
                    $count[$product_id]+=$quantity;
                } else {
                    $count[$product_id]=$quantity;
                }
                $sum_qty = 0;
                foreach($count as $key => $row){

                    if($key == $product_id){
                        $sum_qty = $row;
                    }
                    $prod[$product_id] = $sum_qty;
                }
            }
        }
        if(!empty($prod)){
            $count_product = array_sum($prod);
        }
        $data['count_all_rented']     = $count_product;
        $data['table_data']           = 'most-rented'; // element id table
        $data['ajax_data_table']      = 'adminsite/dashboard/datatables_order'; //Controller ajax data
        $options_datatables           = array('"order": [[ 2, "desc" ]],');
        $data['datatables_ajax_data'] = array(
            $this->custom_lib->datatables_ajax_rented(TRUE,$data['table_data'],$data['ajax_data_table'],'',$options_datatables)
            //$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'],'',$options_datatables)
            );

        $data['load_view']              = 'adminsite/v_dashboard';
        $this->load->view('adminsite/template/backend', $data);
    }

    public function datatables_order(){
        $get                    = $this->input->post('geturl');
        if(!empty($get)){
            $get                = unserialize(base64_decode($get));
        }
        $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $filter                 = (isset($get['filter']) && !empty($get['filter'])) ? trim($get['filter']) : '';
        $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
        $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';
        $startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : '';
        $startdate              = str_replace('/', '-', $startdate);
        $startdate              = date('Y-m-d', strtotime($startdate));
        $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : '';
        $enddate                = str_replace('/', '-', $enddate);
        $enddate                = date('Y-m-d', strtotime($enddate));
        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = isset($_POST['start']) ? $_POST['start'] : 1;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $query                  = $this->backend_model->mostRentedWigdet($searchValue,$filter,$startdate,$enddate,$order,$order_by,$start,$offset);
        $template               = $query;
        $response = array(
            "draw" => intval($draw),
            "iTotalDisplayRecords" => $this->backend_model->countMostRentedWigdet(),
            "aaData" => $template
		);
        echo json_encode($response);
    }

    public function datatables(){
        $query          = $this->backend_model->get_most_rented_product();
        $query_result   = $query->result_array();
        $data           = array();
        $template       = array();
        if(!empty($query)){
            foreach($query_result as $index => $value){
                $product_id             = $value['product_id'];
                $quantity               = (int) $value['rental_product_qty'];
                if(isset($count[$value['product_id']])){
                    $count[$product_id]+=$quantity;
                } else {
                    $count[$product_id]=$quantity;
                }
                $sum_qty = 0;
                foreach($count as $key => $row){

                    if($key == $product_id){
                        $sum_qty = $row;
                    }
                    $data[$product_id]              = $value;
                    $data[$product_id]['rented']    = $sum_qty;
                }
            }
            if(!empty($data)){
                $data = array_values($data);
                $this->array_sort_by_column($data,'rented');
                foreach($data as $index => $value){
                    $template[] = array(
                        $value['product_nama'],
                        $value['product_kode'],
                        $value['rented']
                        );
                }
            }
        }
        $result = $this->custom_lib->datatables_data($query,$template);
        echo json_encode($result);
    }

    public function filtering(){
        $filter         = $this->input->post('filter');
        $start_date     = date('Y-m-d',strtotime($this->input->post('start')));
        $end_date       = date('Y-m-d',strtotime($this->input->post('end')));

        $query          = $this->backend_model->get_most_rented_product($filter,$start_date,$end_date);
        $query_result   = $query->result_array();
        $data           = array();
        $template       = array();
        
        $result     = array(
            'flag'      => false,
            'message'   => 'Not found data'
		);

        if(empty($filter) && !empty($start_date) && !empty($end_date)){
            $result     = array(
                'flag'      => false,
                'message'   => 'Select filter first.'
                );
        } elseif(!empty($query)){
            foreach($query_result as $index => $value){
                $product_id             = $value['product_id'];
                $quantity               = (int) $value['rental_product_qty'];
                if(isset($count[$value['product_id']])){
                    $count[$product_id]+=$quantity;
                } else {
                    $count[$product_id]=$quantity;
                }

                $sum_qty = 0;

                foreach($count as $key => $row){

                    if($key == $product_id){
                        $sum_qty = $row;
                    }
                    $data[$product_id]              = $value;
                    $data[$product_id]['rented']    = $sum_qty;
                }
            }
            if(!empty($data)){
                $data = array_values($data);
                $this->array_sort_by_column($data,'rented');
                $this->session->set_userdata('rented',$data);
                foreach($data as $index => $value){
                    $template[] = array(
                        $value['product_nama'],
                        $value['product_kode'],
                        $value['rented']
                        );
                }
            }
            $result     = array(
                'flag' => true,
                'data' => $template
                );
        }
        echo json_encode($result);
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    public function print_report($get = ''){
        if(!empty($get)){
            $get                = unserialize(base64_decode($get));
        }
        $result['data']         = array();
        $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $filter                 = (isset($get['filter']) && !empty($get['filter'])) ? trim($get['filter']) : '';
        $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
        $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';
        $startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : '';
        $startdate              = str_replace('/', '-', $startdate);
        $startdate              = date('Y-m-d', strtotime($startdate));
        $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : '';
        $enddate                = str_replace('/', '-', $enddate);
        $enddate                = date('Y-m-d', strtotime($enddate));
        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = 0;
        $offset                 = 0; // Rows display per page
        $query                  = $this->backend_model->get_most_rented_product($searchValue,$filter,$startdate,$enddate,$order,$order_by,$start,$offset);
        $data                   = array();

        if(!empty($query)){
            foreach($query as $index => $value){
                $product_id             = $value['product_id'];
                $quantity               = (int) $value['rental_product_qty'];
                if(isset($count[$value['product_id']])){
                    $count[$product_id]+=$quantity;
                } else {
                    $count[$product_id]=$quantity;
                }
                $sum_qty = 0;
                foreach($count as $key => $row){

                    if($key == $product_id){
                        $sum_qty = $row;
                    }
                    $data[$product_id]              = $value;
                    $data[$product_id]['rented']    = $sum_qty;
                }
            }
            if(!empty($data)){
                $data = array_values($data);
                if(empty($order) && empty($order_by)){
                    $this->array_sort_by_column($data,'rented');
                }
                foreach($data as $index => $value){
                    $result['data'][] = array(
                        'product_nama' => $value['product_nama'],
                        'product_kode' => $value['product_kode'],
                        'rented'       => $value['rented']
                        );
                }
            }
        }

        $this->load->view('adminsite/v_print_report',$result);
    }

    public function reset_print_report(){
        if($this->session->userdata('rented') !== NULL){
            $this->session->unset_userdata('rented');
        }
        $result = array('flag' => true);
        echo json_encode($result);
    }
}
