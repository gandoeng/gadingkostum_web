<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Return_list extends CI_Controller {

    var $geturl;

    function __construct() {
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

        $this->geturl = $this->input->get(null,true);

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

    public function index() {
        //Data
        $data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
        $data['title']                  = 'Booking List';
        $data['geturl']                 = $this->input->get(null,true);
        $config = array(
            array(
                'field' => 'search',
                'label' => 'Search',
                'rules' => 'trim'
                ),
            );

        $this->form_validation->set_rules($config);
        $data['geturl']                 = $this->input->get(null,true);
        $where = array(
            'category_flag' => 'store_location'
            );

        $order = array(
            'category_name' => 'asc'
            );

        $data['store_location']       = $this->global_model->getDataWhereOrder('category',$where,$order);
        $data['table_data']           = 'return-list'; // element id table
        $data['ajax_data_table']      = 'adminsite/return_list/datatables_order'; //Controller ajax data
        $data['datatables_ajax_data'] = array(
            $this->custom_lib->datatables_ajax_bookinglist(TRUE,$data['table_data'],$data['ajax_data_table'],'','','','')
            //$this->custom_lib->datatables_ajax_bookinglist(TRUE,$data['table_data'],$data['ajax_data_table'],'','')
            );
        //View
        $data['load_view'] = 'adminsite/v_return_list';
        $this->load->view('adminsite/template/backend', $data);
    }

    public function datatables_order(){
        $get                    = $this->input->post('geturl');
        if(!empty($get)){
            $get                = unserialize(base64_decode($get));
        }
        $status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
        $due                    = (isset($get['due']) && !empty($get['due'])) ? trim($get['due']) : '';
        $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
        if($order == 'rental_invoice'){
            $order = 'rental_order_id';
        }
        $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';
        $filter                 = (isset($get['filter']) && !empty($get['filter'])) ? $get['filter'] : 'start';
        $store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
        $startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : date('Y-m-d', strtotime('first day of -2 month'));
        $startdate              = str_replace('/', '-', $startdate);
        $startdate              = date('Y-m-d', strtotime($startdate));

        $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : date('Y-m-d', strtotime('last day of +2 month'));
        $enddate                = str_replace('/', '-', $enddate);
        $enddate                = date('Y-m-d', strtotime($enddate));

        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 50; // Rows display per page
        $columnSortOrder        = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : ''; // asc or desc
        $totalRecords           = 0;
        $totalRecordwithFilter  = 0;
        $empRecords             = $this->backend_model->AllBookingList($searchValue,$startdate,$enddate,$filter,$store,$status);
        $getOrderBy             = SORT_ASC;

        $list                   = array();
        $product                = array();
        $sort                   = array();
        $getAllOrder            = array();
        $current_date           = date('Y-m-d');
        $data                   = array();

        if(!empty($empRecords)){
            foreach($empRecords->result_array() as $index => $value){
                $current_date = date('Y-m-d');
                $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
                $end_date     = date('Y-m-d',strtotime($value['rental_end_date']));

                $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                $strcurrent = strtotime($current_date);
                $strstart   = strtotime($start_date);
                $strreturn  = strtotime($end_date);

				$action = '<a target="blank" class="btn-print-action btn btn-warning btn-xs btn-flat" href="' . base_url('adminsite/rental_order/invpinjam/') . $value['rental_order_id'] . '" style="display: block; width: 70px; margin-bottom: 2px;">InvPinjam</a>';


                $timeleft   = '';
                if($current_date > $end_date){
                    $timeleft   = $strreturn-$strcurrent;
                    $timeleft   = round((($timeleft/24)/60)/60); 
                } else {
                    $timeleft   = $strreturn - $strstart;
                    $timeleft   = round((($timeleft/24)/60)/60);
                }
                if($check_return_order || $value['rental_status'] == 'booked'){
                    $remaining = '';
                } elseif($value['rental_status'] == 'pickup') {
                    if($timeleft <= 0){
                        $remaining = '<p style="color: red;">'.$timeleft.'</p>';
                    } else{
                        $remaining = '<p style="color: green;">'.$timeleft.'</p>';
                    }
                }

                $status_due   = '';

                if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                    $status_due = 'due_pickup';
                }

                if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                    $status_due = 'due_return';
                } 

                $list[$value['rental_order_id']] = array(
                    'rental_order_id'   => $value['rental_order_id'],
                    'rental_invoice'    => $value['rental_invoice'],
                    'customer_name'     => $value['customer_name'],
                    'customer_phone'    => $value['customer_phone'],
                    'rental_status'     => $value['rental_status'],
                    'rental_created'    => $value['rental_created'],
                    'rental_start_date' => $value['rental_start_date'],
                    'rental_end_date'   => $value['rental_end_date'],
                    'status_due'        => $status_due,
                    'action'         => $action
                    );
                $product[$value['rental_order_id']][] = array(
                    'rental_product_qty'    => $value['rental_product_qty'],
                    'rental_product_kode'   => $value['rental_product_kode'],
                    'rental_product_nama'   => $value['rental_product_nama'],
                    'rental_product_size'   => $value['rental_product_size']
                    );
            }
            if(!empty($searchValue)){
                $totalRecords = 0;
            }

            if($order_by == 'desc'){
                $getOrderBy = SORT_DESC;
            }

            if(!empty($status) && !empty($list)){
                foreach($list as $index => $value){
                    if($status != $value['rental_status']){
                        unset($list[$index]);
                    }
                }
            }

            if(!empty($due) && !empty($list)){
                foreach($list as $index => $value){
                    if($due != $value['status_due']){
                        unset($list[$index]);
                    }
                }
            }

            if(!empty($list)){
                foreach($list as $index => $value){
                    if(isset($product[$value['rental_order_id']])){
                        $list[$value['rental_order_id']]['product'] = $product[$value['rental_order_id']];
                    }
                    if(!empty($searchValue)){
                        $totalRecordwithFilter++;
                    }
                    $totalRecords++;
                    if(!empty($order) && !empty($order_by)){
                        $sort[$index] = $value[$order];
                    }
                }

                $list  = array_values($list);
                if(!empty($order) && !empty($order_by)){
                    array_multisort(array_column($list,$order),$getOrderBy,$list);
                }
            }

            $list                  = array_slice($list,$start,$offset);

            if(!empty($list)){
                foreach($list as $index => $value) {

                    $join_product = array();
                    if(isset($value['product']) && !empty($value['product'])){
                        foreach($value['product'] as $key => $row){
                            $join_product[] = $row['rental_product_qty'].' '.$row['rental_product_kode'].' '.$row['rental_product_nama'].' / '.$row['rental_product_size'];
                        }
                    }

                    $status = $value['rental_status'];
                    if($value['status_due'] == 'due_pickup'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due pickup</span>';
                    }

                    if($value['status_due'] == 'due_return'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due return</span>';
                    } 

                    $data[] = array(
                        'rental_order_id'   => $value['rental_invoice'],
                        'customer_name'     => '<p style="width: 100%; margin-bottom: 2px;">'.$value['customer_name'].'</p>
                    <p style="width: 90px; word-break:break-all; margin-bottom: 2px;margin-right: auto;margin-left: auto;">'.$value['customer_phone'].'</p>',
                        'product'           => implode("<br>",$join_product),
                        'rental_created'    => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_created'])).'</p>
                        <p style="width: 100%; margin-bottom: 2px;">'.date('h:i A',strtotime($value['rental_created'])).'</p>',
                        'rental_start_date' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_start_date'])).'</p>',
                        'rental_end_date'   => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_end_date'])).'</p>',
                        'rental_status'     => $status,
                        'action'         => $value['action']
                        );
                }
            }
        }

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecordwithFilter,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $data,
            );
        echo json_encode($response);
    }

    public function datatables(){

        $order = array(
            'rental_created' => 'desc'
            );
        $where = array();
        $where = array(
            'rental_active' => 1
            );

        $query = $this->global_model->getDataWhereOrderWithLimit('rental_order',$where,$order,10);

        $data = array();
        if(!empty($query)){
            foreach($query->result_array() as $index => $value) {

                $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);
                $query_product      = $this->global_model->select_where('rental_product',array('rental_order_id' => $value['rental_order_id']));

                $join_product = array();
                foreach($query_product as $key => $row){
                    $join_product[] = $row['rental_product_qty'].' '.$row['rental_product_kode'].' '.$row['rental_product_nama'].' / '.$row['rental_product_size'];
                }

                $current    = date('Y-m-d');
                $startdate  = date('Y-m-d',strtotime($value['rental_start_date'])); 
                $returndate = date('Y-m-d',strtotime($value['rental_end_date'])); 

                $strcurrent = strtotime($current);
                $strstart   = strtotime($startdate);
                $strreturn  = strtotime($returndate);

                if($current > $returndate){
                    $timeleft   = $strreturn-$strcurrent;
                    $timeleft   = round((($timeleft/24)/60)/60); 
                } else {
                    $timeleft   = $strreturn - $strstart;
                    $timeleft   = round((($timeleft/24)/60)/60);
                }

                if($check_return_order || $value['rental_status'] == 'booked'){
                    $remaining = '';
                } elseif($value['rental_status'] == 'pickup') {
                    if($timeleft <= 0){
                        $remaining = '<p style="color: red;">'.$timeleft.'</p>';
                    } else{
                        $remaining = '<p style="color: green;">'.$timeleft.'</p>';
                    }
                }

                $data[] = array(
                    $value['rental_invoice'],
                    '<p style="width: 100%; margin-bottom: 2px;">'.$value['customer_name']. '</p>
                    <p style="width: 90px; word-break:break-all; margin-bottom: 2px;margin-right: auto;margin-left: auto;">'.$value['customer_phone'].'</p>',
                    implode("<br>",$join_product),
                    '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_created'])).'</p>
                    <p style="width: 100%; margin-bottom: 2px;">'.date('h:i A',strtotime($value['rental_created'])).'</p>',
                    '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_start_date'])).'</p>',
                    '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_end_date'])).'</p>',
                    $value['rental_status'],
                    $remaining
                    );

            }
        }
        $result = $this->custom_lib->datatables_data($query,$data);
        echo json_encode($result);
    }

    public function filtering(){
        $store_location = $this->input->post('store_location');
        $filter         = $this->input->post('filter');
        $start_date     = date('Y-m-d',strtotime($this->input->post('start')));
        $end_date       = date('Y-m-d',strtotime($this->input->post('end')));
        $store          = $this->input->post('store_location');
        $query          = $this->backend_model->filtering_return_list_with_limit($store_location,$filter,$start_date,$end_date,10);

        $result     = array(
            'flag'      => false,
            'message'   => 'Not found data'
            );

        /*if(empty($filter) && !empty($start_date) && !empty($end_date)){
            $result     = array(
                'flag'      => false,
                'message'   => 'Select filter first.'
                );
            } else*/if(!empty($query)){
                $data       = array();
                $session    = array();
                foreach($query->result_array() as $index => $value) {

                    $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);
                    $query_product      = $this->global_model->select_where('rental_product',array('rental_order_id' => $value['rental_order_id']));

                    $join_product = array();

                    foreach($query_product as $key => $row){
                        $join_product[] = $row['rental_product_qty'].' '.$row['rental_product_kode'].' '.$row['rental_product_nama'].' / '.$row['rental_product_size'];
                    }

                    $current    = date('Y-m-d');
                    $startdate  = date('Y-m-d',strtotime($value['rental_start_date'])); 
                    $returndate = date('Y-m-d',strtotime($value['rental_end_date'])); 

                    $strcurrent = strtotime($current);
                    $strstart   = strtotime($startdate);
                    $strreturn  = strtotime($returndate);

                    if($current > $returndate){
                        $timeleft   = $strreturn-$strcurrent;
                        $timeleft   = round((($timeleft/24)/60)/60); 
                    } else {
                        $timeleft   = $strreturn - $strstart;
                        $timeleft   = round((($timeleft/24)/60)/60);
                    }
                    if($check_return_order || $value['rental_status'] == 'booked'){
                        $remaining = '';
                    } elseif($value['rental_status'] == 'pickup') {
                        if($timeleft <= 0){
                            $remaining = '<p style="color: red;">'.$timeleft.'</p>';
                        } else{
                            $remaining = '<p style="color: green;">'.$timeleft.'</p>';
                        }
                    }

                    $implode_join_product = implode("<br>",$join_product);
                    
                    $data[] = array(
                        $value['rental_invoice'],
                        '<p style="width: 100%; margin-bottom: 2px;">'.$value['customer_name'].'</p>
                    <p style="width: 90px; word-break:break-all; margin-bottom: 2px;margin-right: auto;margin-left: auto;">'.$value['customer_phone'].'</p>',
                        $implode_join_product,
                        '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_created'])).'</p>
                        <p style="width: 100%; margin-bottom: 2px;">'.date('h:i A',strtotime($value['rental_created'])).'</p>',
                        '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_start_date'])).'</p>',
                        '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_end_date'])).'</p>',
                        $value['rental_status'],
                        $remaining
                        );
                    $session[] = array(
                        'rental_invoice' => $value['rental_invoice'],
                        'customer'  => $value['customer_name'].'<br>'.$value['customer_phone'],
                        'items'          => $implode_join_product,
                        'rental_created' => date('d-m-Y',strtotime($value['rental_created'])).'<br>'.date('h:i A',strtotime($value['rental_created'])),
                        'rental_start_date' => date('d-m-Y',strtotime($value['rental_start_date'])),
                        'rental_end_date'=> date('d-m-Y',strtotime($value['rental_end_date'])),
                        'rental_status' => $value['rental_status'],
                        'remaining'      => $remaining
                        );
                }
                if(!empty($session)){
                    $this->session->set_userdata('booking_list',$session);
                    $this->session->mark_as_temp('booking_list', 100);
                }
                $result     = array(
                    'flag' => true,
                    'data' => $data,
                    'query' => $query->result_array(),
                    'post'  => $_POST,
                    'start'=> $start_date,
                    'end'=> $end_date
                    );
            }
            echo json_encode($result);
        }

        public function print_report1($get = ''){
            if(!empty($get)){
                $get                = unserialize(base64_decode($get));
            }
            $status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
            $due                    = (isset($get['due']) && !empty($get['due'])) ? trim($get['due']) : '';
            $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
            $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
            if($order == 'rental_invoice'){
                $order = 'rental_order_id';
            }
            $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';
            $filter                 = (isset($get['filter']) && !empty($get['filter'])) ? $get['filter'] : '';
            $store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
            $startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : date('Y-m-d', strtotime('first day of -2 month'));
            $startdate              = str_replace('/', '-', $startdate);
            $startdate              = date('Y-m-d', strtotime($startdate));

            $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : date('Y-m-d', strtotime('last day of +2 month'));
            $enddate                = str_replace('/', '-', $enddate);
            $enddate                = date('Y-m-d', strtotime($enddate));

            $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
            $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
            $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
            $columnSortOrder        = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : ''; // asc or desc
            $totalRecords           = 0;
            $totalRecordwithFilter  = 0;
            $empRecords             = $this->backend_model->AllBookingList($searchValue,$startdate,$enddate,$filter,$store);
            $getOrderBy             = SORT_ASC;

            $list                   = array();
            $product                = array();
            $sort                   = array();
            $getAllOrder            = array();
            $current_date           = date('Y-m-d');
            $data                   = array();

            echo '<pre>';
            print_r($empRecords->result_array());
            echo '</pre>';
            exit;
            if(!empty($empRecords)){
                foreach($empRecords->result_array() as $index => $value){
                    $current_date = date('Y-m-d');
                    $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
                    $end_date     = date('Y-m-d',strtotime($value['rental_end_date']));

                    $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                    $strcurrent = strtotime($current_date);
                    $strstart   = strtotime($start_date);
                    $strreturn  = strtotime($end_date);

                    $timeleft   = '';
                    if($current_date > $end_date){
                        $timeleft   = $strreturn-$strcurrent;
                        $timeleft   = round((($timeleft/24)/60)/60); 
                    } else {
                        $timeleft   = $strreturn - $strstart;
                        $timeleft   = round((($timeleft/24)/60)/60);
                    }
                    if($check_return_order || $value['rental_status'] == 'booked'){
                        $remaining = '';
                    } elseif($value['rental_status'] == 'pickup') {
                        if($timeleft <= 0){
                            $remaining = '<p style="color: red;">'.$timeleft.'</p>';
                        } else{
                            $remaining = '<p style="color: green;">'.$timeleft.'</p>';
                        }
                    }

                    $status_due   = '';
                    if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                        $status_due = 'due_pickup';
                    }

                    if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                        $status_due = 'due_return';
                    } 

                    $list[$value['rental_order_id']] = array(
                        'rental_order_id'   => $value['rental_order_id'],
                        'rental_invoice'    => $value['rental_invoice'],
                        'customer_name'     => $value['customer_name'],
                        'customer_phone'    => $value['customer_phone'],
                        'rental_status'     => $value['rental_status'],
                        'rental_created'    => $value['rental_created'],
                        'rental_start_date' => $value['rental_start_date'],
                        'rental_end_date'   => $value['rental_end_date'],
                        'status_due'        => $status_due,
                        'remaining'         => $remaining
                        );
                    $product[$value['rental_order_id']][] = array(
                        'rental_product_qty'    => $value['rental_product_qty'],
                        'rental_product_kode'   => $value['rental_product_kode'],
                        'rental_product_nama'   => $value['rental_product_nama'],
                        'rental_product_size'   => $value['rental_product_size']
                        );
                }
                if($order_by == 'desc'){
                    $getOrderBy = SORT_DESC;
                }

                if(!empty($status) && !empty($list)){
                    foreach($list as $index => $value){
                        if($status != $value['rental_status']){
                            unset($list[$index]);
                        }
                    }
                }

                if(!empty($due) && !empty($list)){
                    foreach($list as $index => $value){
                        if($due != $value['status_due']){
                            unset($list[$index]);
                        }
                    }
                }

                if(!empty($list)){
                    foreach($list as $index => $value){
                        if(isset($product[$value['rental_order_id']])){
                            $list[$value['rental_order_id']]['product'] = $product[$value['rental_order_id']];
                        }
                        $totalRecords++;
                        if(!empty($order) && !empty($order_by)){
                            $sort[$index] = $value[$order];
                        }
                    }

                    $list  = array_values($list);
                    if(!empty($order) && !empty($order_by)){
                        array_multisort(array_column($list,$order),$getOrderBy,$list);
                    }
                }

                if(!empty($list)){
                    foreach($list as $index => $value) {
                        $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);
                        $join_product = array();
                        if(isset($value['product']) && !empty($value['product'])){
                            foreach($value['product'] as $key => $row){
                                $join_product[] = $row['rental_product_qty'].' '.$row['rental_product_kode'].' '.$row['rental_product_nama'].' / '.$row['rental_product_size'];
                            }
                        }

                        $status = $value['rental_status'];
                        if($value['status_due'] == 'due_pickup'){
                            $status .= '<span class="required" style="display:block; width: 100%; font-size: 7px; margin-top: 3px; color: red !important;">due pickup</span>';
                        }

                        if($value['status_due'] == 'due_return'){
                            $status .= '<span class="required" style="display:block; width: 100%; font-size: 7px; margin-top: 3px; color: red !important;">due return</span>';
                        } 

                        $data[] = array(
                            'rental_invoice' => $value['rental_invoice'],
                            'customer' => '<p style="width: 100%; margin-bottom: 2px;">'.$value['customer_name'].'</p>
                            <p style="width: 100%; margin-bottom: 2px;">'.$value['customer_phone'].'</p>',
                            'items' => implode("<br>",$join_product),
                            'rental_created' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_created'])).'</p>
                            <p style="width: 100%; margin-bottom: 2px;">'.date('h:i A',strtotime($value['rental_created'])).'</p>',
                            'rental_start_date' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_start_date'])).'</p>',
                            'rental_end_date' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_end_date'])).'</p>',
                            'rental_status' => $status,
                            'remaining' => $value['remaining']
                            );
                    }
                }
            }

            if(!empty($data)){
                $result['data'] = $data;
            }

            $this->load->view('adminsite/v_print_booking',$result);
        }

        public function print_report($get = ''){
            if(!empty($get)){
                $get                = unserialize(base64_decode($get));
            }
            $status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
            $due                    = (isset($get['due']) && !empty($get['due'])) ? trim($get['due']) : '';
            $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
            $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
            if($order == 'rental_invoice'){
                $order = 'rental_order_id';
            }
            $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';
            $filter                 = (isset($get['filter']) && !empty($get['filter'])) ? $get['filter'] : '';
            $store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
            $startdate              = (isset($get['start']) && !empty($get['start'])) ? $get['start'] : date('Y-m-d', strtotime('first day of -2 month'));
            $startdate              = str_replace('/', '-', $startdate);
            $startdate              = date('Y-m-d', strtotime($startdate));

            $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : date('Y-m-d', strtotime('last day of +2 month'));
            $enddate                = str_replace('/', '-', $enddate);
            $enddate                = date('Y-m-d', strtotime($enddate));

            $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
            $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
            $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
            $columnSortOrder        = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : ''; // asc or desc
            $totalRecords           = 0;
            $totalRecordwithFilter  = 0;
            $empRecords             = $this->backend_model->AllBookingList($searchValue,$startdate,$enddate,$filter,$store,$status);
            $getOrderBy             = SORT_ASC;

            $list                   = array();
            $product                = array();
            $sort                   = array();
            $getAllOrder            = array();
            $current_date           = date('Y-m-d');
            $data                   = array();

            if(!empty($empRecords)){
                foreach($empRecords->result_array() as $index => $value){
                    $current_date = date('Y-m-d');
                    $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
                    $end_date     = date('Y-m-d',strtotime($value['rental_end_date']));

                    $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                    $strcurrent = strtotime($current_date);
                    $strstart   = strtotime($start_date);
                    $strreturn  = strtotime($end_date);

                    $timeleft   = '';
                    if($current_date > $end_date){
                        $timeleft   = $strreturn-$strcurrent;
                        $timeleft   = round((($timeleft/24)/60)/60); 
                    } else {
                        $timeleft   = $strreturn - $strstart;
                        $timeleft   = round((($timeleft/24)/60)/60);
                    }
                    if($check_return_order || $value['rental_status'] == 'booked'){
                        $remaining = '';
                    } elseif($value['rental_status'] == 'pickup') {
                        if($timeleft <= 0){
                            $remaining = '<p style="color: red;">'.$timeleft.'</p>';
                        } else{
                            $remaining = '<p style="color: green;">'.$timeleft.'</p>';
                        }
                    }

                    $status_due   = '';
                    if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                        $status_due = 'due_pickup';
                    }

                    if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                        $status_due = 'due_return';
                    } 

                    $list[$value['rental_order_id']] = array(
                        'rental_order_id'   => $value['rental_order_id'],
                        'rental_invoice'    => $value['rental_invoice'],
                        'customer_name'     => $value['customer_name'],
                        'customer_phone'    => $value['customer_phone'],
                        'rental_status'     => $value['rental_status'],
                        'rental_created'    => $value['rental_created'],
                        'rental_start_date' => $value['rental_start_date'],
                        'rental_end_date'   => $value['rental_end_date'],
                        'status_due'        => $status_due,
                        'remaining'         => $remaining
                        );
                    $product[$value['rental_order_id']][] = array(
                        'rental_product_qty'    => $value['rental_product_qty'],
                        'rental_product_kode'   => $value['rental_product_kode'],
                        'rental_product_nama'   => $value['rental_product_nama'],
                        'rental_product_size'   => $value['rental_product_size']
                        );
                }
                if($order_by == 'desc'){
                    $getOrderBy = SORT_DESC;
                }

                if(!empty($status) && !empty($list)){
                    foreach($list as $index => $value){
                        if($status != $value['rental_status']){
                            unset($list[$index]);
                        }
                    }
                }

                if(!empty($due) && !empty($list)){
                    foreach($list as $index => $value){
                        if($due != $value['status_due']){
                            unset($list[$index]);
                        }
                    }
                }

                if(!empty($list)){
                    foreach($list as $index => $value){
                        if(isset($product[$value['rental_order_id']])){
                            $list[$value['rental_order_id']]['product'] = $product[$value['rental_order_id']];
                        }
                        $totalRecords++;
                        if(!empty($order) && !empty($order_by)){
                            $sort[$index] = $value[$order];
                        }
                    }

                    $list  = array_values($list);
                    if(!empty($order) && !empty($order_by)){
                        array_multisort(array_column($list,$order),$getOrderBy,$list);
                    }
                }

                if(!empty($list)){
                    foreach($list as $index => $value) {
                        $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);
                        $join_product = array();
                        if(isset($value['product']) && !empty($value['product'])){
                            foreach($value['product'] as $key => $row){
                                $join_product[] = $row['rental_product_qty'].' '.$row['rental_product_kode'].' '.$row['rental_product_nama'].' / '.$row['rental_product_size'];
                            }
                        }

                        $status = $value['rental_status'];
                        if($value['status_due'] == 'due_pickup'){
                            $status .= '<span class="required" style="display:block; width: 100%; font-size: 7px; margin-top: 3px; color: red !important;">due pickup</span>';
                        }

                        if($value['status_due'] == 'due_return'){
                            $status .= '<span class="required" style="display:block; width: 100%; font-size: 7px; margin-top: 3px; color: red !important;">due return</span>';
                        } 

                        $data[] = array(
                            'rental_invoice' => $value['rental_invoice'],
                            'customer' => '<p style="width: 100%; margin-bottom: 2px;">'.$value['customer_name'].'</p>
                            <p style="width: 100%; margin-bottom: 2px;">'.$value['customer_phone'].'</p>',
                            'items' => implode("<br>",$join_product),
                            'rental_created' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_created'])).'</p>
                            <p style="width: 100%; margin-bottom: 2px;">'.date('h:i A',strtotime($value['rental_created'])).'</p>',
                            'rental_start_date' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_start_date'])).'</p>',
                            'rental_end_date' => '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_end_date'])).'</p>',
                            'rental_status' => $status,
                            'remaining' => $value['remaining']
                            );
                    }
                }
            }

            if(!empty($data)){
                $result['data'] = $data;
            }

            $this->load->view('adminsite/v_print_booking',$result);
        }

        public function reset_print_report(){
            if($this->session->userdata('booking_list') !== NULL){
                $this->session->unset_userdata('booking_list');
            }
            $result = array('flag' => true);
            echo json_encode($result);
        }
    }
    ?>