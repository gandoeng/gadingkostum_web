<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_list extends CI_Controller {

    var $geturl; //global $_GET

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
        $data['title']                = 'Stock List';

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
        $data['table_data']           = 'stock-list'; // element id table
        $data['ajax_data_table']      = 'adminsite/stock-list/datatables_order'; //Controller ajax data
        $columns_datatables           = '[0, 1, 2, 3, 4, 5]';
        $data['datatables_ajax_data'] = array(
            //$this->custom_lib->datatables_ajax_data(TRUE,$data['table_data'],$data['ajax_data_table'],'','',$columns_datatables)
            $this->custom_lib->datatables_ajax_stocklist(TRUE,$data['table_data'],$data['ajax_data_table'],'','')
            );
        //View
        $data['load_view'] = 'adminsite/v_stock_list';
        $this->load->view('adminsite/template/backend', $data);
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
        $columnIndex            = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName             = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : ''; // Column name
        $columnSortOrder        = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : ''; // asc or desc
        $totalRecords           = 0;
        $totalRecordWithFilter  = 0;

        $empRecords             = $this->backend_model->AllStockList($searchValue,$store);
        $data   = array();
        $list   = array();
        //return order
        $query_return_order     = $this->global_model->select('return_order');
        
        //setting delay day after return
        $day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

        //current time
        $current_date           = date('Y-m-d');

        $count          = array();
        $filter_order   = array();
        $sort           = array();
        if(!empty($empRecords)){
            $no = 0;
            $rental_in_return       = array();
            $rental_in_returndate   = array();
            $product               = array();
            foreach($empRecords as $index => $value){

                if((int)$day_after_return && !empty($value['return_date'])){
                    $rental_in_return[]                              = $value['rental_order_id'];
                    $rental_in_returndate[$value['rental_order_id']] = array(
                        'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                        'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                        'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)))
                        );
                }

                $product[$value['product_sizestock_id']]  = $value;

            }
            $product_already_return = array();
            $data                   = array();
            $getOrderBy             = SORT_ASC;
            foreach($empRecords as $index => $value){

                $quantity               = (int) $value['rental_product_qty'];

                    //search who is already have return date on order
                if(in_array($value['rental_order_id'],$rental_in_return)){
                    $rental_order_id        = $value['rental_order_id'];
                    $rental_product_qty     = $value['rental_product_qty'];
                    $product_sizestock_id   = $value['product_sizestock_id'];

                    $sum_qty = 0;
                    foreach($count as $key => $row){

                        if($key == $value['product_sizestock_id']){
                            $sum_qty = $row;
                        }

                    }

                    if($rental_in_returndate[$rental_order_id]['return_date'] >= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date && !empty($rental_order_id)){

                        if(isset($count_return[$value['product_sizestock_id']])){
                            $count_return[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count_return[$value['product_sizestock_id']]=$quantity;
                        }

                        $sum_return = 0;
                        foreach($count_return as $key => $row){

                            if($key == $value['product_sizestock_id']){
                                $sum_return = $row;
                            }

                        }

                        $product[$value['product_sizestock_id']]['return_qty'] = $sum_return;
                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_return;
                    } else {

                        if(isset($count_return[$value['product_sizestock_id']])){
                            $count_return[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count_return[$value['product_sizestock_id']]=$quantity;
                        }

                        $sum_return = 0;
                        foreach($count_return as $key => $row){

                            if($key == $value['product_sizestock_id']){
                                $sum_return = $row;
                            }

                        }

                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_return;
                    }
                        //}
                } elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {
                    if(isset($count[$value['product_sizestock_id']])){
                        $count[$value['product_sizestock_id']]+=$quantity;
                    } else {
                        $count[$value['product_sizestock_id']]=$quantity;
                    }

                    $sum_qty = 0;
                    foreach($count as $key => $row){

                        if($key == $value['product_sizestock_id']){
                            $sum_qty = $row;
                        }
                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_qty;
                    }
                } else {
                    if(isset($count[$value['product_sizestock_id']])){
                        $count[$value['product_sizestock_id']]+=$quantity;
                    } else {
                        $count[$value['product_sizestock_id']]=$quantity;
                    }

                    $sum_qty = 0;
                    foreach($count as $key => $row){

                        if($key == $value['product_sizestock_id']){
                            $sum_qty = $row;
                        }
                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_qty;
                    }
                }
            }

            if(!empty($product)){

                $stock_available = 0;
                foreach($product as $index => $value){
                    $stock  = $value['product_stock'];
                    if(isset($value['return_qty'])){
                        $stock_available = $stock - $value['rental_qty']; 
                    } else {
                        $stock_available = $stock - $value['rental_qty'];
                    }

                    $percentage = 100;  
                    if($stock_available == $stock){
                        $percentage      = 100;
                    } elseif($stock_available < $stock) {
                        $percentage      = ($stock_available / $stock) * 100;
                    } else{
                        $percentage      = $stock / 100;   
                    }

                    /*$label_stock = '<span class="label label-success">Most Stocked</span>';
                    if($percentage > 50){
                        $label_stock = '<span class="label label-success">Most Stocked</span>';
                    } elseif($percentage <= 50 && $percentage > 0){
                        $label_stock = '<span class="label label-warning">Low</span>';
                    } else {
                        $label_stock = '<span class="label label-danger">Out Of Stock</span>';
                    }*/

                    $label_stock = 'most_stocked';
                    if($percentage > 50){
                        $label_stock = 'most_stocked';
                    } elseif($percentage <= 50 && $percentage > 0){
                        $label_stock = 'low';
                    } else {
                        $label_stock = 'out_of_stock';
                    }

                    $product_isipaket = html_entity_decode($value['product_isipaket']);
                    $product_isipaket = str_replace("\n","<br>",$value['product_isipaket']);

                    $product_estimasiukuran = html_entity_decode($value['product_estimasiukuran']);
                    $product_estimasiukuran = str_replace(', ',"<br>",$value['product_estimasiukuran']);

                    $list[$value['product_sizestock_id']] = array(
                        'product_nama' => $value['product_nama'],
                        'product_kode' => $value['product_kode'],
                        'product_isipaket' => '<p style="width: 100%; text-align:center;">'.$product_isipaket.'</p>',
                        'product_size' => '<p style="width: 100%; text-align:center;"><strong>'.$value['product_size'].'</strong></p>
                        <p style="width: 100%; text-align:center;">'.$product_estimasiukuran.'</p>',
                        'stock' => $stock_available.' / '.$stock,
                        'label' => $label_stock
                        );
                }

                if(!empty($searchValue)){
                    $totalRecords = 0;
                }

                if($order_by == 'desc'){
                    $getOrderBy = SORT_DESC;
                }

                if(empty($status)){
                    foreach($list as $index => $value){
                        if(!empty($searchValue)){
                            $totalRecordWithFilter++;
                        }
                        $totalRecords++;

                        if(!empty($order) && !empty($order_by)){
                            $sort[$index] = $value[$order];
                        }
                    }
                } else {
                    foreach($list as $index => $value){
                        if($status == $value['label']){
                            if(!empty($searchValue)){
                                $totalRecordWithFilter++;
                            }
                            $totalRecords++;

                            if(!empty($order) && !empty($order_by)){
                                $sort[$index] = $value[$order];
                            }
                        } else {
                            unset($list[$index]);
                        }
                    }
                }
                if(!empty($sort) && !empty($order) && !empty($order_by) && !empty($list)){
                    array_multisort($sort, $getOrderBy, $list);
                }

                $list                  = array_values($list);
                $list                  = array_slice($list,$start,$offset);
            }
        }

        if(!empty($list)){
            foreach($list as $index => $value){
                $label_stock = '';
                if($value['label'] == 'most_stocked'){
                    $label_stock = '<span class="label label-success">Most Stocked</span>';
                } elseif($value['label'] == 'low'){
                    $label_stock = '<span class="label label-warning">Low</span>';
                } else {
                    $label_stock = '<span class="label label-danger">Out Of Stock</span>';
                }
                $data[] = array(
                    'product_nama'      => $value['product_nama'],
                    'product_kode'      => $value['product_kode'],
                    'product_isipaket'  => $value['product_isipaket'],
                    'product_size'      => $value['product_size'],
                    'stock'             => $value['stock'],
                    'label'             => $label_stock
                    );
            }
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"  => $data
            );
        echo json_encode($response);
    }

    public function datatables(){

        //product
        $query                  = $this->backend_model->get_all_rental_order();
        
        //return order
        $query_return_order     = $this->global_model->select('return_order');
        
        //setting delay day after return
        $day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

        //get product,sizestock,rental order,rental product and return order
        $query_rental_product   = $query->result_array();

        //current time
        $current_date           = date('Y-m-d');

        $count          = array();
        $filter_order   = array();
        if(!empty($query_rental_product)){
            $no = 0;
            $rental_in_return       = array();
            $rental_in_returndate   = array();
            $product               = array();
            foreach($query_rental_product as $index => $value){

                if((int)$day_after_return && !empty($value['return_date'])){
                    $rental_in_return[]                              = $value['rental_order_id'];
                    $rental_in_returndate[$value['rental_order_id']] = array(
                        'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                        'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                        'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)))
                        );
                }
            }
            $product_already_return = array();
            $data                   = array();
            $product_already_return = array();
            $sum_qty = 0;
            $stock_product          = array();
            foreach($query_rental_product as $index => $value){
                $value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));

                $product[$value['product_sizestock_id']][$value['rental_order_id']]  = $value;
                $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty']  = '';
                $product[$value['product_sizestock_id']][$value['rental_order_id']]['status']      = '';
                $stock_product[$value['product_id']] = $value['product_stock'];
            }

            foreach($query_rental_product as $index => $value){

                $quantity               = (int) $value['rental_product_qty'];

                    //search who is already have return date on order
                if(in_array($value['rental_order_id'],$rental_in_return)){
                    $rental_order_id        = $value['rental_order_id'];
                    $rental_product_qty     = $value['rental_product_qty'];
                    $product_sizestock_id   = $value['product_sizestock_id'];

                    if($rental_in_returndate[$rental_order_id]['return_date'] >= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date && !empty($rental_order_id)){

                        $quantity  = (int) $value['rental_product_qty'];
                        if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
                            $count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                        } else {
                            $count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
                        }

                        $sum_qty1 = 0;
                        foreach($count as $key => $row){

                            foreach($row as $k => $v){

                                if($k == $value['product_sizestock_id']){
                                    $sum_qty1 = $v;
                                }
                                $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                                $product[$value['product_sizestock_id']][$value['rental_order_id']]['status']     = 'return';
                            }
                        }

                    } else {
                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                    }
                        //}
                } elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {
                    $quantity  = (int) $value['rental_product_qty'];
                    if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
                        $count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                    } else {
                        $count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
                    }

                    $sum_qty2 = 0;
                    foreach($count as $key => $row){

                        foreach($row as $k => $v){

                            if($k == $value['product_sizestock_id']){
                                $sum_qty2 = $v;
                            }
                            $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                        }
                    }
                } else {
                    $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                }
            }

            /*echo '<pre>';
            print_r($product);
            echo '</pre>';*/

            if(!empty($product)){

                $data_product           = array();
                $count_data_product     = array();
                foreach($product as $index => $value){
                    if(is_array($value)){
                        foreach($value as $key => $row){

                            if(isset($count_data_product[$row['product_sizestock_id']]) && $row['status'] != 'return'){
                                $count_data_product[$row['product_sizestock_id']]+=$row['rental_qty'];
                            } else {
                                $count_data_product[$row['product_sizestock_id']]=0;
                            }

                            if(is_array($row)){
                                $data_product[$row['product_sizestock_id']] = $row;
                            }
                        }
                    }
                }
/*
                echo '<pre>';
                print_r($product);
                echo '</pre>';

                exit;*/
                $stock_available = 0;
                foreach($data_product as $index => $value){
                    $stock  = $value['product_stock'];
                    if(isset($count_data_product[$index])){
                        $stock_available = $stock - $count_data_product[$index];
                    } else {
                        $stock_available = $value['product_stock'];
                    }
                    /*if(isset($value['return_qty'])){
                        $stock_available = $stock - $value['rental_qty']; 
                    } else {
                        $stock_available = $stock - $value['rental_qty'];
                    }*/

                    $percentage = 100;  
                    if($stock_available == $stock){
                        $percentage      = 100;
                    } elseif($stock_available < $stock) {
                        $percentage      = ($stock_available / $stock) * 100;
                    } else{
                        $percentage      = $stock / 100;   
                    }

                    $label_stock = '<span class="label label-success">Most Stocked</span>';
                    if($percentage > 50){
                        $label_stock = '<span class="label label-success">Most Stocked</span>';
                    } elseif($percentage <= 50 && $percentage > 0){
                        $label_stock = '<span class="label label-warning">Low</span>';
                    } else {
                        $label_stock = '<span class="label label-danger">Out Of Stock</span>';
                    }

                    $product_isipaket = html_entity_decode($value['product_isipaket']);
                    $product_isipaket = str_replace("\n","<br>",$value['product_isipaket']);

                    $product_estimasiukuran = html_entity_decode($value['product_estimasiukuran']);
                    $product_estimasiukuran = str_replace(', ',"<br>",$value['product_estimasiukuran']);

                    $data[$value['product_sizestock_id']] = array(
                        $value['product_nama'],
                        $value['product_kode'],
                        '<p style="width: 100%; text-align:center;">'.$product_isipaket.'</p>',
                        '<p style="width: 100%; text-align:center;"><strong>'.$value['product_size'].'</strong></p>
                        <p style="width: 100%; text-align:center;">'.$product_estimasiukuran.'</p>',
                        $stock_available.' / '.$stock,
                        $label_stock
                        );
                }
            }
        }

        //exit;
        if(!empty($data)){
            $data = array_values($data);
        }
        $result = $this->custom_lib->datatables_data($query,$data);
        echo json_encode($result);
    }

    public function filtering(){
        $store                  = $this->input->post('store_location');

         //product
        $query                  = $this->backend_model->get_all_rental_order($store);

        //return order
        $query_return_order     = $this->global_model->select('return_order');

        //setting delay day after return
        $day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

        //get product,sizestock,rental order,rental product and return order
        $query_rental_product   = $query->result_array();

        //current time
        $current_date           = date('Y-m-d');

        $count          = array();
        $filter_order   = array();
        $session        = array();

        $result         = array(
            'flag'      => false,
            'message'   => 'Not found data'
            );

        if(empty($store)){
            $result     = array(
                'flag'      => false,
                'message'   => 'Select store first.'
                );
        } elseif(!empty($query_rental_product)){
            $no = 0;
            $rental_in_return       = array();
            $rental_in_returndate   = array();
            $product                = array();
            foreach($query_rental_product as $index => $value){

                if((int)$day_after_return && !empty($value['return_date'])){
                    $rental_in_return[]                              = $value['rental_order_id'];
                    $rental_in_returndate[$value['rental_order_id']] = array(
                        'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                        'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                        'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)))
                        );
                }
            }
            $product_already_return = array();
            $data                   = array();
            $product_already_return = array();
            $sum_qty = 0;
            $stock_product          = array();
            foreach($query_rental_product as $index => $value){
                $value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));

                $product[$value['product_sizestock_id']][$value['rental_order_id']]  = $value;
                $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty']  = '';
                $product[$value['product_sizestock_id']][$value['rental_order_id']]['status']      = '';
                $stock_product[$value['product_id']] = $value['product_stock'];
            }

            foreach($query_rental_product as $index => $value){

                $quantity               = (int) $value['rental_product_qty'];

                    //search who is already have return date on order
                if(in_array($value['rental_order_id'],$rental_in_return)){
                    $rental_order_id        = $value['rental_order_id'];
                    $rental_product_qty     = $value['rental_product_qty'];
                    $product_sizestock_id   = $value['product_sizestock_id'];

                    if($rental_in_returndate[$rental_order_id]['return_date'] >= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date && !empty($rental_order_id)){

                        $quantity  = (int) $value['rental_product_qty'];
                        if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
                            $count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                        } else {
                            $count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
                        }

                        $sum_qty1 = 0;
                        foreach($count as $key => $row){

                            foreach($row as $k => $v){

                                if($k == $value['product_sizestock_id']){
                                    $sum_qty1 = $v;
                                }
                                $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                                $product[$value['product_sizestock_id']][$value['rental_order_id']]['status']     = 'return';
                            }
                        }

                    } else {
                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                    }
                        //}
                } elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {
                    $quantity  = (int) $value['rental_product_qty'];
                    if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
                        $count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                    } else {
                        $count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
                    }

                    $sum_qty2 = 0;
                    foreach($count as $key => $row){

                        foreach($row as $k => $v){

                            if($k == $value['product_sizestock_id']){
                                $sum_qty2 = $v;
                            }
                            $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                        }
                    }
                } else {
                    $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                }
            }

            if(!empty($product)){

                $data_product           = array();
                $count_data_product     = array();
                foreach($product as $index => $value){
                    if(is_array($value)){
                        foreach($value as $key => $row){

                            if(isset($count_data_product[$row['product_sizestock_id']]) && $row['status'] != 'return'){
                                $count_data_product[$row['product_sizestock_id']]+=$row['rental_qty'];
                            } else {
                                $count_data_product[$row['product_sizestock_id']]=0;
                            }

                            if(is_array($row)){
                                $data_product[$row['product_sizestock_id']] = $row;
                            }
                        }
                    }
                }

                $stock_available = 0;
                foreach($data_product as $index => $value){
                    $stock  = $value['product_stock'];
                    if(isset($count_data_product[$index])){
                        $stock_available = $stock - $count_data_product[$index];
                    } else {
                        $stock_available = $value['product_stock'];
                    }

                    $percentage = 100;  
                    if($stock_available == $stock){
                        $percentage      = 100;
                    } elseif($stock_available < $stock) {
                        $percentage      = ($stock_available / $stock) * 100;
                    } else{
                        $percentage      = $stock / 100;   
                    }

                    $label_stock = '<span class="label label-success">Most Stocked</span>';
                    if($percentage > 50){
                        $label_stock = '<span class="label label-success">Most Stocked</span>';
                    } elseif($percentage <= 50 && $percentage > 0){
                        $label_stock = '<span class="label label-warning">Low</span>';
                    } else {
                        $label_stock = '<span class="label label-danger">Out Of Stock</span>';
                    }

                    $product_isipaket = html_entity_decode($value['product_isipaket']);
                    $product_isipaket = str_replace("\n","<br>",$value['product_isipaket']);

                    $product_estimasiukuran = html_entity_decode($value['product_estimasiukuran']);
                    $product_estimasiukuran = str_replace(', ',"<br>",$value['product_estimasiukuran']);

                    $data[$value['product_sizestock_id']] = array(
                        $value['product_nama'],
                        $value['product_kode'],
                        '<p style="width: 100%; text-align:center;">'.$product_isipaket.'</p>',
                        '<p style="width: 100%; text-align:center;"><strong>'.$value['product_size'].'</strong></p>
                        <p style="width: 100%; text-align:center;">'.$product_estimasiukuran.'</p>',
                        $stock_available.' / '.$stock,
                        $label_stock
                        );

                    $session[$value['product_sizestock_id']] = array(
                        'product_nama'      => $value['product_nama'],
                        'product_kode'      => $value['product_kode'],
                        'product_isipaket'  => $product_isipaket,
                        'product_size'      => '<strong>'.$value['product_size'].'</strong><br>'.$product_estimasiukuran,
                        'stock'             => $stock_available.' / '.$stock,
                        'label_stock'       => $label_stock
                        );

                }

                if(!empty($data)){
                    $data = array_values($data);
                }

                $result     = array(
                    'flag' => true,
                    'data' => $data
                    );
            }

            //if(!empty($session)){
                //$session = array_values($session);
                //$this->session->set_userdata('stock_list',$session);
            //}

        }

        echo json_encode($result);

    }

    public function print_report($get = ''){

        $get                    = unserialize(base64_decode($get));
        $result['data']         = array();
        $data                   = array();

        $status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
        $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $store                  = (isset($get['store_location']) && !empty($get['store_location'])) ? $get['store_location'] : '';
        $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
        $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';

        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $columnIndex            = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName             = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : ''; // Column name
        $columnSortOrder        = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : ''; // asc or desc
        $totalRecords           = 0;
        $totalRecordWithFilter  = 0;

        $empRecords             = $this->backend_model->AllStockList($searchValue,$store);
        $data   = array();
        $list   = array();
        //return order
        $query_return_order     = $this->global_model->select('return_order');
        
        //setting delay day after return
        $day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

        //current time
        $current_date           = date('Y-m-d');

        $count          = array();
        $filter_order   = array();
        $sort           = array();

        if(!empty($empRecords)){
            $no = 0;
            $rental_in_return       = array();
            $rental_in_returndate   = array();
            $product               = array();
            foreach($empRecords as $index => $value){

                if((int)$day_after_return && !empty($value['return_date'])){
                    $rental_in_return[]                              = $value['rental_order_id'];
                    $rental_in_returndate[$value['rental_order_id']] = array(
                        'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                        'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                        'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)))
                        );
                }

                $product[$value['product_sizestock_id']]  = $value;

            }
            $product_already_return = array();
            $data                   = array();
            $getOrderBy             = SORT_ASC;
            foreach($empRecords as $index => $value){

                $quantity               = (int) $value['rental_product_qty'];

                    //search who is already have return date on order
                if(in_array($value['rental_order_id'],$rental_in_return)){
                    $rental_order_id        = $value['rental_order_id'];
                    $rental_product_qty     = $value['rental_product_qty'];
                    $product_sizestock_id   = $value['product_sizestock_id'];

                    $sum_qty = 0;
                    foreach($count as $key => $row){

                        if($key == $value['product_sizestock_id']){
                            $sum_qty = $row;
                        }

                    }

                    if($rental_in_returndate[$rental_order_id]['return_date'] >= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date && !empty($rental_order_id)){

                        if(isset($count_return[$value['product_sizestock_id']])){
                            $count_return[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count_return[$value['product_sizestock_id']]=$quantity;
                        }

                        $sum_return = 0;
                        foreach($count_return as $key => $row){

                            if($key == $value['product_sizestock_id']){
                                $sum_return = $row;
                            }

                        }

                        $product[$value['product_sizestock_id']]['return_qty'] = $sum_return;
                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_return;
                    } else {

                        if(isset($count_return[$value['product_sizestock_id']])){
                            $count_return[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count_return[$value['product_sizestock_id']]=$quantity;
                        }

                        $sum_return = 0;
                        foreach($count_return as $key => $row){

                            if($key == $value['product_sizestock_id']){
                                $sum_return = $row;
                            }

                        }

                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_return;
                    }
                        //}
                } elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {
                    if(isset($count[$value['product_sizestock_id']])){
                        $count[$value['product_sizestock_id']]+=$quantity;
                    } else {
                        $count[$value['product_sizestock_id']]=$quantity;
                    }

                    $sum_qty = 0;
                    foreach($count as $key => $row){

                        if($key == $value['product_sizestock_id']){
                            $sum_qty = $row;
                        }
                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_qty;
                    }
                } else {
                    if(isset($count[$value['product_sizestock_id']])){
                        $count[$value['product_sizestock_id']]+=$quantity;
                    } else {
                        $count[$value['product_sizestock_id']]=$quantity;
                    }

                    $sum_qty = 0;
                    foreach($count as $key => $row){

                        if($key == $value['product_sizestock_id']){
                            $sum_qty = $row;
                        }
                        $product[$value['product_sizestock_id']]['rental_qty'] = $sum_qty;
                    }
                }
            }

            if(!empty($product)){

                $stock_available = 0;
                foreach($product as $index => $value){
                    $stock  = $value['product_stock'];
                    if(isset($value['return_qty'])){
                        $stock_available = $stock - $value['rental_qty']; 
                    } else {
                        $stock_available = $stock - $value['rental_qty'];
                    }

                    $percentage = 100;  
                    if($stock_available == $stock){
                        $percentage      = 100;
                    } elseif($stock_available < $stock) {
                        $percentage      = ($stock_available / $stock) * 100;
                    } else{
                        $percentage      = $stock / 100;   
                    }
                    $label_stock = 'most_stocked';
                    if($percentage > 50){
                        $label_stock = 'most_stocked';
                    } elseif($percentage <= 50 && $percentage > 0){
                        $label_stock = 'low';
                    } else {
                        $label_stock = 'out_of_stock';
                    }

                    $product_isipaket = html_entity_decode($value['product_isipaket']);
                    $product_isipaket = str_replace("\n","<br>",$value['product_isipaket']);

                    $product_estimasiukuran = html_entity_decode($value['product_estimasiukuran']);
                    $product_estimasiukuran = str_replace(', ',"<br>",$value['product_estimasiukuran']);

                    $list[$value['product_id']]['product_nama']     = $value['product_nama'];
                    $list[$value['product_id']]['product_kode']     = $value['product_kode'];
                    $list[$value['product_id']]['isipaket'][$value['product_sizestock_id']] = $product_isipaket;
                    $list[$value['product_id']]['size'][$value['product_sizestock_id']] = '<strong>'.$value['product_size'].'</strong><br>'.$product_estimasiukuran;
                    $list[$value['product_id']]['stock'][$value['product_sizestock_id']] = $stock_available.' / '.$stock;
                    $list[$value['product_id']]['label'][$value['product_sizestock_id']] = $label_stock;

                }

                if($order_by == 'desc'){
                    $getOrderBy = SORT_DESC;
                }

                if(!empty($status)){
                    foreach($list as $index => $value){
                        if(isset($value['label']) && !empty($value['label'])){
                            foreach($value['label'] as $key => $row){
                                if($status == $row){
                                    if(!empty($order) && !empty($order_by)){
                                        $sort[$index] = $value[$order];
                                    }
                                } else {
                                    unset($list[$index]['size'][$key]);
                                    unset($list[$index]['label'][$key]);
                                    unset($list[$index]['isipaket'][$key]);
                                    unset($list[$index]['stock'][$key]);
                                }
                            }
                        }
                    }
                    foreach($list as $index => $value){
                        if(isset($value['size']) && empty($value['size'])){
                            unset($list[$index]);
                        }
                        if(isset($value['isipaket']) && !empty($value['isipaket'])){
                            $list[$index]['isipaket'] = array_values($value['isipaket']);
                        }
                    }
                } else {
                    foreach($list as $index => $value){
                        if(isset($value['isipaket']) && !empty($value['isipaket'])){
                            $list[$index]['isipaket'] = array_values($value['isipaket']);
                        }
                        if(!empty($order) && !empty($order_by)){
                            $sort[$index] = $value[$order];
                        }
                    }
                }
                if(!empty($sort) && !empty($order) && !empty($order_by) && !empty($list)){
                    array_multisort($sort, $getOrderBy, $list);
                }
                $list                  = array_values($list);
            }
        }
        if(!empty($list)){
            $result['data'] = $list;
        }
        $this->load->view('adminsite/v_print_stock',$result);
    }

    public function print_report_old(){
        //$check              = $this->session->userdata('stock_list');
        //$result['data']     = array();
        $data               = array();

        //if($check){
            //$result['data'] = $check;
        //} else {

        //product
            $query                  = $this->backend_model->get_all_rental_order();

        //return order
            $query_return_order     = $this->global_model->select('return_order');

        //setting delay day after return
            $day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

        //get product,sizestock,rental order,rental product and return order
            $query_rental_product   = $query->result_array();

        //current time
            $current_date           = date('Y-m-d');

            $count          = array();
            $filter_order   = array();

            if(!empty($query_rental_product)){
                $no = 0;
                $rental_in_return       = array();
                $rental_in_returndate   = array();
                $product                = array();
                foreach($query_rental_product as $index => $value){

                    if((int)$day_after_return && !empty($value['return_date'])){
                        $rental_in_return[]                              = $value['rental_order_id'];
                        $rental_in_returndate[$value['rental_order_id']] = array(
                            'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                            'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                            'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)))
                            );
                    }
                }
                $product_already_return = array();
                $data                   = array();
                $product_already_return = array();
                $sum_qty = 0;
                $stock_product          = array();
                foreach($query_rental_product as $index => $value){
                    $value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));

                    $product[$value['product_sizestock_id']][$value['rental_order_id']]  = $value;
                    $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty']  = '';
                    $product[$value['product_sizestock_id']][$value['rental_order_id']]['status']      = '';
                    $stock_product[$value['product_id']] = $value['product_stock'];
                }

                foreach($query_rental_product as $index => $value){

                    $quantity               = (int) $value['rental_product_qty'];

                    //search who is already have return date on order
                    if(in_array($value['rental_order_id'],$rental_in_return)){
                        $rental_order_id        = $value['rental_order_id'];
                        $rental_product_qty     = $value['rental_product_qty'];
                        $product_sizestock_id   = $value['product_sizestock_id'];

                        if($rental_in_returndate[$rental_order_id]['return_date'] >= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date && !empty($rental_order_id)){

                            $quantity  = (int) $value['rental_product_qty'];
                            if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
                                $count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                            } else {
                                $count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
                            }

                            $sum_qty1 = 0;
                            foreach($count as $key => $row){

                                foreach($row as $k => $v){

                                    if($k == $value['product_sizestock_id']){
                                        $sum_qty1 = $v;
                                    }
                                    $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                                    $product[$value['product_sizestock_id']][$value['rental_order_id']]['status']     = 'return';
                                }
                            }

                        } else {
                            $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                        }
                        //}
                    } elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {
                        $quantity  = (int) $value['rental_product_qty'];
                        if(isset($count[$value['product_sizestock_id']][$value['rental_order_id']])){
                            $count[$value['product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                        } else {
                            $count[$value['product_sizestock_id']][$value['rental_order_id']]=$quantity;
                        }

                        $sum_qty2 = 0;
                        foreach($count as $key => $row){

                            foreach($row as $k => $v){

                                if($k == $value['product_sizestock_id']){
                                    $sum_qty2 = $v;
                                }
                                $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                            }
                        }
                    } else {
                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                    }
                }

                if(!empty($product)){

                    $data_product           = array();
                    $count_data_product     = array();
                    foreach($product as $index => $value){
                        if(is_array($value)){
                            foreach($value as $key => $row){

                                if(isset($count_data_product[$row['product_sizestock_id']]) && $row['status'] != 'return'){
                                    $count_data_product[$row['product_sizestock_id']]+=$row['rental_qty'];
                                } else {
                                    $count_data_product[$row['product_sizestock_id']]=0;
                                }

                                if(is_array($row)){
                                    $data_product[$row['product_sizestock_id']] = $row;
                                }
                            }
                        }
                    }

                    $stock_available = 0;
                    foreach($data_product as $index => $value){
                        $stock  = $value['product_stock'];
                        if(isset($count_data_product[$index])){
                            $stock_available = $stock - $count_data_product[$index];
                        } else {
                            $stock_available = $value['product_stock'];
                        }

                        $percentage = 100;  
                        if($stock_available == $stock){
                            $percentage      = 100;
                        } elseif($stock_available < $stock) {
                            $percentage      = ($stock_available / $stock) * 100;
                        } else{
                            $percentage      = $stock / 100;   
                        }

                        $label_stock = '<span class="label label-success">Most Stocked</span>';
                        if($percentage > 50){
                            $label_stock = '<span class="label label-success">Most Stocked</span>';
                        } elseif($percentage <= 50 && $percentage > 0){
                            $label_stock = '<span class="label label-warning">Low</span>';
                        } else {
                            $label_stock = '<span class="label label-danger">Out Of Stock</span>';
                        }

                        $product_isipaket = html_entity_decode($value['product_isipaket']);
                        //$product_isipaket = str_replace("\n","<br>",$value['product_isipaket']);
                        $product_isipaket = str_replace("\n",", ",$value['product_isipaket']);

                        $product_estimasiukuran = html_entity_decode($value['product_estimasiukuran']);
                        $product_estimasiukuran = str_replace(', ',"<br>",$value['product_estimasiukuran']);

                        $data[$value['product_id']]['nama'] = $value['product_nama'];

                        $data[$value['product_id']]['kode'] = $value['product_kode'];

                        $data[$value['product_id']]['isipaket'][$value['product_sizestock_id']] = $product_isipaket;

                        $data[$value['product_id']]['isipaket'] = array_values($data[$value['product_id']]['isipaket']);

                        $data[$value['product_id']]['size'][$value['product_sizestock_id']] = '<strong>'.$value['product_size'].'</strong><br>'.$product_estimasiukuran;

                        $data[$value['product_id']]['stock'][$value['product_sizestock_id']] = $stock_available.' / '.$stock;

                    }

                    if(!empty($data)){
                        $data = array_values($data);
                        $this->session->set_userdata('stock_list',$data);
                    }
                }
            }
        //}
        if(!empty($data)){
            $result['data'] = $data;
        }
        $this->load->view('adminsite/v_print_stock',$result);
    }

    public function reset_print_report(){
        if($this->session->userdata('stock_list') !== NULL){
            $this->session->unset_userdata('stock_list');
        }
        $result = array('flag' => true);
        echo json_encode($result);
    }
}
?>