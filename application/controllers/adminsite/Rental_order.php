<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rental_order extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('global_model');

        $this->load->model('backend_model');
        $this->load->model('front_model');

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

    public function cuman_tes(){
        echo $this->config->item('get_base_url');
    }


    //Ndungg start
    public function backup_rental_order(){
        $dateStart = $this->input->get('datedBackupStart', TRUE);
        $dateEnd = $this->input->get('datedBackupEnd', TRUE);


        $this->db->where('rental_created >=', $dateStart);
        $this->db->where('rental_created <=', $dateEnd);
        $data = $this->db->get('rental_order')->result();

        foreach($data as $d) { // loop over results
            //$this->db->insert('tableTo', $r); // insert each row to another table
            $this->db->insert('rental_order_backup',$d);
        }

        if($this->db->affected_rows() == 1){
            echo "<script>
            alert('Backup Successful');
            window.location = ('/adminsite/rental_order_backup');
            </script>";


        } else {
            echo "<script>
                alert('Backup Unsuccessful');
                window.location = ('/adminsite/rental_order_backup');
            </script>" ;
        }

    }

    //Ndung end
    
	//New Product QR scan ajax
	//Julian
    public function getProductByQR(){
		$kode = $this->input->post('kode');
		$check_url_by_pro = strpos($kode, '/product/');
		$check_url_by_tag = strpos($kode, '/tag/');
        $kodeSplit = [];

		if (!empty($check_url_by_pro)) {
			$kodeBreak = explode('/product/', $kode);
			$kodeSplit = [];
			$product = $this->backend_model->getProductByScanQRSlug($kodeBreak[1]);
			$data['product_id'] = $product->product_id;
		} elseif (!empty($check_url_by_tag)) {
			$kodeBreak = explode('/tag/', $kode);
			$kodeSplit = explode('-', $kodeBreak[1]);
			$product = $this->backend_model->getProductByScanQR($kodeSplit[0]);
			$data['product_id'] = $product->product_id;
		}
        $data['product_sizestock_id']   = (isset($kodeSplit[1]) && !empty($data['product_id'])) ? $kodeSplit[1] : '';
        $data['kode'] = $kode;
        $data['splitKode'] = $kodeSplit;
        $data['input'] = $this->input->post('kode',true);

		echo json_encode($data);
    }

	//Old getProduct QR commented
	// public function getProductByQR(){
    //     $base_url                       = $this->config->item('get_base_url');
    //     $kode                           = str_replace('gadingkostum.com/'.'tag/','',$this->input->post('kode',true));
    //     $splitKode                      = explode('-',$kode);
    //     $product_kode                   = (isset($splitKode[0])) ? $splitKode[0] : '';
    //     //$data['product_id']             = $this->backend_model->getProductByScanQR($product_kode);
    //     $product_id                     = $this->backend_model->getProductByScanQR($product_kode);
    //     $data['product_id']             = (!empty($product_id)) ? $this->backend_model->getProductByScanQR($product_kode)->product_id : '';
    //     $data['product_sizestock_id']   = (isset($splitKode[1]) && !empty($data['product_id'])) ? $splitKode[1] : ''; 
    //     $data['base_url'] = $base_url;
    //     $data['kode'] = $kode;
    //     $data['splitKode'] = $splitKode;
    //     $data['input'] = $this->input->post('kode',true);
    //     echo json_encode($data);
    // }
    
    public function nominal(){
        $nominal = 800000;
        $ppn     = 0.1;
        $potongan= $nominal * $ppn;
        $potong  = $nominal - $potongan;
        echo number_format($potong, 0, '.', '');
    }
    public function index() {
        //Data
        $data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
        $data['title']  = 'Rental Order';

        $config = array(
            array(
                'field' => 'search',
                'label' => 'Search',
                'rules' => 'trim'
                ),
            );

        $this->form_validation->set_rules($config);

        $data['geturl']               = $this->input->get(null,true);

        $data['table_data']           = 'rental-order'; // element id table

        $data['ajax_data_table']      = 'adminsite/rental_order/datatables_order'; //Controller ajax data
        $data['datatables_ajax_data'] = array(
            $this->custom_lib->datatables_ajax_serverside(TRUE,$data['table_data'],$data['ajax_data_table'],'','','','rental_order')
            );
        //View
        $data['load_view'] = 'adminsite/v_rental_order';
        $this->load->view('adminsite/template/backend', $data);
    }

    public function tes(){
        $url = 'https://gadingkostum.com/search/1?k=wonder_avengers&size=anak03-04tahun||anak01-02tahun';
        echo $url;
        echo '<br>';
        echo urlencode($url);
    }

    public function datatables_trash_new(){

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

        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordwithFilter  = 0;

        if(empty($due)){
            $queryDefault                = $this->backend_model->AllRentalorderTrashDefault($searchValue,$order,$order_by,$status,$start,$offset);
            $totalRecords                = $this->backend_model->countRentalorderTrashDefault($searchValue,$status);
        } else {
            $query                       = $this->backend_model->AllRentalorderTrashSort($searchValue);
        }

        $data = array();
        $list = array();
        $data_duepickup     = array();
        $data_duereturn     = array();

        if(!empty($queryDefault)){
            foreach($queryDefault as $index => $value) {

                $status_payment = '';
                if($value['rental_payment_status'] == 'unpaid'){
                    $status_payment = '<span class="label label-danger">Unpaid</span>';
                } else {
                    $status_payment = '<span class="label label-success">Paid</span>';
                }

                $current_date = date('Y-m-d');
                $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
                $end_date     = date('Y-m-d',strtotime($value['rental_end_date']));
                $status_due   = '';

                if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                    $status_due = 'due_pickup';
                }

                if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                    $status_due = 'due_return';
                } 

                $action = '';
                $action .= '<a class="btn-edit-action btn btn-primary btn-xs btn-flat" href="'.base_url('adminsite/rental_order/view/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">View</a>';
                $action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-xs btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/trash').'" style="display: block; width: 70px; margin-bottom: 2px;">Trash</a>';
                $action .= '<a class="btn-print-action btn btn-warning btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invpinjam/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvPinjam</a>';

                $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                if($check_return_order){
                    $action .= '<a class="btn-print-action btn btn-success btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invkembali/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvKembali</a>';
                }

                $query_return         = $this->global_model->select_where('return_order',array('rental_order_id' => $value['rental_order_id']));
                $minus_deposit        = 0;
                $result_minus_deposit = '';
                if(!empty($query_return)){
                    $minus_deposit = $query_return[0]['return_late_charges'] - $query_return[0]['return_damage_fine'];
                }
                if($minus_deposit > 0){
                    $result_minus_deposit = '<p style="width: 100%; margin-top: 2px; color: green">- '.number_format($minus_deposit).'</p>';
                }
                $list[] = array(
                    'checkbox'              => '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['rental_order_id'].'"></div>',
                    'rental_order_id'       => $value['rental_order_id'],
                    'rental_invoice'        => $value['rental_invoice'],
                    'customer_name'         => $value['customer_name'].'<br>'.$value['customer_phone'],
                    'rental_created'        => date('Y-m-d H:i:s',strtotime($value['rental_created'])),
                    'rental_total_hargasewa'=> $value['rental_total_hargasewa'],
                    'rental_payment_status' => $status_payment,
                    'rental_total_deposit'  => $value['rental_total_deposit'],
                    'result_minus_deposit'  => $result_minus_deposit,
                    'rental_status'         => $value['rental_status'],
                    'status_due'            => $status_due,
                    'action'                => $action
                    );
            }

            if(!empty($searchValue)){
                $totalRecords = 0;
            }

            if(!empty($list)){
                foreach($list as $index => $value){
                    $status = $value['rental_status'];
                    if($value['status_due'] == 'due_pickup'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due pickup</span> ';
                    }

                    if($value['status_due'] == 'due_return'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due return</span>';
                    } 
                    $data[] = array(
                        'checkbox'      => $value['checkbox'],
                        'invoice'       => $value['rental_invoice'],
                        'name'          => $value['customer_name'],
                        'created'       => date('d-m-Y',strtotime($value['rental_created'])).' '.date('h:i A',strtotime($value['rental_created'])),
                        'hargasewa'     => number_format($value['rental_total_hargasewa']),
                        'payment'       => $value['rental_payment_status'],
                        'deposit'       => number_format($value['rental_total_deposit']).$value['result_minus_deposit'],
                        'status'        => $status,
                        'action'        => $value['action']
                        );
                }
            }
        }

        if(!empty($query)){
            $getOrderBy             = SORT_ASC;
            foreach($query as $index => $value) {

                $status_payment = '';
                if($value['rental_payment_status'] == 'unpaid'){
                    $status_payment = '<span class="label label-danger">Unpaid</span>';
                } else {
                    $status_payment = '<span class="label label-success">Paid</span>';
                }

                $current_date = date('Y-m-d');
                $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
                $end_date     = date('Y-m-d',strtotime($value['rental_end_date']));
                $status_due   = '';

                if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                    $status_due = 'due_pickup';
                }

                if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                    $status_due = 'due_return';
                } 

                $action = '';
                $action .= '<a class="btn-edit-action btn btn-primary btn-xs btn-flat" href="'.base_url('adminsite/rental_order/view/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">View</a>';
                $action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-xs btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/trash').'" style="display: block; width: 70px; margin-bottom: 2px;">Trash</a>';
                $action .= '<a class="btn-print-action btn btn-warning btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invpinjam/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvPinjam</a>';

                $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                if($check_return_order){
                    $action .= '<a class="btn-print-action btn btn-success btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invkembali/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvKembali</a>';
                }

                $query_return         = $this->global_model->select_where('return_order',array('rental_order_id' => $value['rental_order_id']));
                $minus_deposit        = 0;
                $result_minus_deposit = '';
                if(!empty($query_return)){
                    $minus_deposit = $query_return[0]['return_late_charges'] - $query_return[0]['return_damage_fine'];
                }
                if($minus_deposit > 0){
                    $result_minus_deposit = '<p style="width: 100%; margin-top: 2px; color: green">- '.number_format($minus_deposit).'</p>';
                }
                $list[] = array(
                    'checkbox'              => '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['rental_order_id'].'"></div>',
                    'rental_order_id'       => $value['rental_order_id'],
                    'rental_invoice'        => $value['rental_invoice'],
                    'customer_name'         => $value['customer_name'].'<br>'.$value['customer_phone'],
                    'rental_created'        => date('Y-m-d H:i:s',strtotime($value['rental_created'])),
                    'rental_total_hargasewa'=> $value['rental_total_hargasewa'],
                    'rental_payment_status' => $status_payment,
                    'rental_total_deposit'  => $value['rental_total_deposit'],
                    'result_minus_deposit'  => $result_minus_deposit,
                    'rental_status'         => $value['rental_status'],
                    'status_due'            => $status_due,
                    'action'                => $action
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
                    if(!empty($searchValue)){
                        $totalRecordwithFilter++;
                    }
                    $totalRecords++;

                    if(!empty($order) && !empty($order_by)){
                        $sort[$index] = $value[$order];
                    }
                }

                $list                  = array_values($list);
                
                if(!empty($sort) && !empty($order) && !empty($order_by)){
                    array_multisort(array_column($list,$order),$getOrderBy,$list);
                }

                $list                  = array_slice($list,$start,$offset);

                foreach($list as $index => $value){
                    $status = $value['rental_status'];
                    if($value['status_due'] == 'due_pickup'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due pickup</span>';
                    }

                    if($value['status_due'] == 'due_return'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due return</span>';
                    } 
                    $data[] = array(
                        'checkbox'      => $value['checkbox'],
                        'invoice'       => $value['rental_invoice'],
                        'name'          => $value['customer_name'],
                        'created'       => date('d-m-Y',strtotime($value['rental_created'])).' '.date('h:i A',strtotime($value['rental_created'])),
                        'hargasewa'     => number_format($value['rental_total_hargasewa']),
                        'payment'       => $value['rental_payment_status'],
                        'deposit'       => number_format($value['rental_total_deposit']).$value['result_minus_deposit'],
                        'status'        => $status,
                        'action'        => $value['action']
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

    public function datatables_order(){

        $get                    = $this->input->post('geturl');
        if(!empty($get)){
            $get                = unserialize(base64_decode($get));
        }
        $status                 = (isset($get['status']) && !empty($get['status'])) ? trim($get['status']) : '';
        $due                    = (isset($get['due']) && !empty($get['due'])) ? trim($get['due']) : '';
        //$due                    = 'due_return';
        $searchValue            = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $order                  = (isset($get['order']) && !empty($get['order'])) ? trim($get['order']) : '';
        if($order == 'rental_invoice'){
            $order = 'rental_order_id';
        }
        $order_by               = (isset($get['order_by']) && !empty($get['order_by'])) ? trim($get['order_by']) : '';

        $draw                   = isset($_POST['draw']) ? $_POST['draw'] : 1;
        $start                  = isset($_POST['start']) ? $_POST['start'] : 0;
        $offset                 = isset($_POST['length']) ? $_POST['length'] : 10; // Rows display per page
        $totalRecords           = 0;
        $totalRecordwithFilter  = 0;
        $current_date           = date('Y-m-d');

        if(empty($due)){
            $queryDefault                = $this->backend_model->AllRentalorderDefault($searchValue,$order,$order_by,$status,$start,$offset);
            $totalRecords                = $this->backend_model->countRentalorderDefault($searchValue,$status);
        } else {
            $queryDefault                = $this->backend_model->AllRentalorderSort($searchValue,$due,$current_date,$order,$order_by,$status,$start,$offset);
            $totalRecords                = $this->backend_model->countRentalorderSort($searchValue,$due,$current_date);
        }

        $data = array();
        $list = array();
        $data_duepickup     = array();
        $data_duereturn     = array();

        if(!empty($queryDefault)){
            foreach($queryDefault as $index => $value) {

                $status_payment = '';
                if($value['rental_payment_status'] == 'unpaid'){
                    $status_payment = '<span class="label label-danger">Unpaid</span>';
                } else {
                    $status_payment = '<span class="label label-success">Paid</span>';
                }

                $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
                $end_date     = date('Y-m-d',strtotime($value['rental_end_date']));
                $status_due   = '';

                if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                    $status_due = 'due_pickup';
                }

                if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                    $status_due = 'due_return';
                } 

                $action = '';
                $action .= '<a class="btn-edit-action btn btn-primary btn-xs btn-flat" href="'.base_url('adminsite/rental_order/view/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">View</a>';
                $action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-xs btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/trash').'" style="display: block; width: 70px; margin-bottom: 2px;">Trash</a>';
                $action .= '<a class="btn-print-action btn btn-warning btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invpinjam/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvPinjam</a>';

                $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                if($check_return_order){
                    $action .= '<a class="btn-print-action btn btn-success btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invkembali/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvKembali</a>';
                }

                $query_return         = $this->global_model->select_where('return_order',array('rental_order_id' => $value['rental_order_id']));
                $minus_deposit        = 0;
                $result_minus_deposit = '';
                if(!empty($query_return)){
                    $minus_deposit = $query_return[0]['return_late_charges'] - $query_return[0]['return_damage_fine'];
                }
                if($minus_deposit > 0){
                    $result_minus_deposit = '<p style="width: 100%; margin-top: 2px; color: green">- '.number_format($minus_deposit).'</p>';
                }
                $list[] = array(
                    'checkbox'              => '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['rental_order_id'].'"></div>',
                    'rental_order_id'       => $value['rental_order_id'],
                    'rental_invoice'        => $value['rental_invoice'],
                    'customer_name'         => '<p style="width: 100%; margin-bottom: 2px;">' . $value['customer_name'] . '</p>
                    <p style="width: 90px; word-break:break-all; margin-bottom: 2px;margin-right: auto;margin-left: auto;">' . $value['customer_phone'] . '</p>',
                    'rental_created'        => date('Y-m-d H:i:s',strtotime($value['rental_created'])),
                    'rental_total_hargasewa'=> $value['rental_total_hargasewa'],
                    'rental_payment_status' => $status_payment,
                    'rental_total_deposit'  => $value['rental_total_deposit'],
                    'result_minus_deposit'  => $result_minus_deposit,
                    'rental_status'         => $value['rental_status'],
                    'status_due'            => $status_due,
                    'action'                => $action
                    );
            }

            if(!empty($searchValue)){
                $totalRecords = 0;
            }

            if(!empty($list)){
                foreach($list as $index => $value){
                    $status = $value['rental_status'];
                    if($value['status_due'] == 'due_pickup'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due pickup</span> <!-- Ndung --> <a class="btn-print-action btn btn-success btn-xs btn-flat" href="'.base_url('adminsite/rental_order/pickupnw/').$value['rental_order_id'].'" style=" width: 70px; margin-bottom: 2px;">Pickup Now</a>';
                    }

                    if($value['status_due'] == 'due_return'){
                        $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due return</span>';
                    } 
                    $data[] = array(
                        'checkbox'      => $value['checkbox'],
                        'invoice'       => $value['rental_invoice'],
                        'name'          => $value['customer_name'],
                        'created'       => date('d-m-Y',strtotime($value['rental_created'])).' '.date('h:i A',strtotime($value['rental_created'])),
                        'hargasewa'     => number_format($value['rental_total_hargasewa']),
                        'payment'       => $value['rental_payment_status'],
                        'deposit'       => number_format($value['rental_total_deposit']).$value['result_minus_deposit'],
                        'status'        => $status,
                        'action'        => $value['action']
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

    //Ndung
    Public function pickupnw(){
        $id = $this->uri->segment(4);

        $data = [
            'rental_status' => 'pickup',
        ];

        $this->db->where('rental_order_id', $id);
        $this->db->update('rental_order', $data);

        redirect('adminsite/rental_order');
    }

    public function add() {
        //Data
        $data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
        $data['title']          = 'Add New Rental Order';
        $where = array(
            'category_flag'     => 'store_location'
            );
        $order = array(
            'category_order'    => 'asc',
            'category_created'  => 'desc'
            );
        $data['store_location'] = $this->global_model->getDataWhereOrder('category',$where,$order)->result_array();

        $order_product          = array('product_nama' => 'asc');
        $product_category_detil = array();
        $data['product']        = array();
        $data['selected_store'] = '';
        if(!empty($data['store_location'])){
            foreach($data['store_location'] as $index => $value){
                if($value['category_id'] == 4){
                    $data['selected_store'] = $value['category_id'];
                    $product_category_detil = $this->global_model->select_where('product_category_detil',array('category_id' => $value['category_id']));
                }
            }
        }

        if(!empty($product_category_detil)){

            foreach($product_category_detil as $k => $v){

                $product = $this->global_model->select_where('product',array('product_id' => $v['product_id']));
                foreach($product as $index => $value){

                    $data['product'][] = $value;
                }
            }
        }

        $data['customer']       = $this->global_model->select('customer');
        $data['css_init']  = array(
            base_url('assets/front/plugins/datepicker/dist/css/bootstrap-datepicker.min.css'),
            base_url('assets/front/plugins/datepicker/dist/css/bootstrap-datepicker3.min.css')
            );
        $data['js_init']   = array(
            base_url('assets/front/plugins/datepicker/dist/js/bootstrap-datepicker.js')
            );
        $data['load_view']      = 'adminsite/v_rental_order_add';
        $this->load->view('adminsite/template/backend', $data);
    }

    public function view($id){
        $data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
        $id = $this->uri->segment(4);

        if(is_numeric($id)){
            $data['title']          = 'View Rental Order';
            $where = array(
                'category_flag'     => 'store_location'
                );
            $order = array(
                'category_order'    => 'asc',
                'category_created'  => 'desc'
                );

            $data['store_location'] = $this->global_model->getDataWhereOrder('category',$where,$order)->result_array();
            $late_charge            = $this->global_model->select_where('setting',array('setting_name' => 'late_charge'));
            if(empty($late_charge)){
                $data['late_charge'] = 0;
            } else {
                $data['late_charge'] = $late_charge[0]['setting_value'];
            }

            $order                      = array('product_nama' => 'asc');
            $data['rental_order']       = $this->global_model->select_where('rental_order',array('rental_order_id' => $id));
            $data['rental_product']     = $this->global_model->select_where('rental_product',array('rental_order_id' => $id));
            $data['rental_extrapayment']= $this->backend_model->getExtraPaymentForViewOrder($id);
            $data['rental_extradenda']  = $this->backend_model->getDendaForViewOrder($id);

            $data['exist_product_from_location']   = array();
            if(!empty($data['rental_order'])){

                $select             = 'product.product_id,product_nama,product_kode';

                $join_array = array(
                    'product'       => 'product.product_id = product_category_detil.product_id'
                    );
                $where_status = array(
                    'product_category_detil.category_id'                    => $data['rental_order'][0]['store_location_category_id'],
                    'flag'                                                  => 'store_location',
                );
                $exist_location         = $this->backend_model->get_join_by_id('product_category_detil',$where_status,$select,$join_array,'')->result_array();

                if(!empty($exist_location)){
                    foreach($exist_location as $index => $value){
                        $data['exist_product_from_location'][] = $value;
                    }
                }

            }

            $data['rental_status']      = array('booked','pickup','return');
            $data['return_order']       = $this->global_model->select_where('return_order',array('rental_order_id' => $id));
            $data['jenis_transaksi']    = $this->global_model->select_where('jenis_transaksi',array('rental_order_id' => $id));
            $data['jenis_transaksi_order']    = $this->backend_model->getJenisTransaksiSewaDepositByRentalOrder($id);
            $data['product']            = $this->backend_model->get_join_by_id('product','','','',$order);
            $data['css_init']  = array(
                base_url('assets/front/plugins/datepicker/dist/css/bootstrap-datepicker.min.css'),
                base_url('assets/front/plugins/datepicker/dist/css/bootstrap-datepicker3.min.css')
                );
            $data['js_init']   = array(
                base_url('assets/front/plugins/datepicker/dist/js/bootstrap-datepicker.js')
                );
            $data['load_view']          = 'adminsite/v_rental_order_view';

            $this->load->view('adminsite/template/backend', $data);
        } else {
            redirect('adminsite/rental_order');
        }
    }

public function datatables(){

    $order = array(
        'rental_created' => 'desc',
        'rental_invoice' => 'desc'
        );
    $where = array();
    $where = array(
        'rental_active' => 1
        );

    $query = $this->global_model->getDataWhereOrder('rental_order',$where,$order);

    $data = array();
    if(!empty($query)){
        foreach($query->result_array() as $index => $value) {

            $status_payment = '';
            if($value['rental_payment_status'] == 'unpaid'){
                $status_payment = '<span class="label label-danger">Unpaid</span>';
            } else {
                $status_payment = '<span class="label label-success">Paid</span>';
            }

            $current_date = date('Y-m-d');
            $start_date   = date('Y-m-d',strtotime($value['rental_start_date']));
            $end_date     = date('Y-m-d',strtotime($value['rental_end_date'])); 

            //$status_value = array('booked','pickup','return');
            $status = '';
            /*$status .= '<select class="form-control select-status-rental">';
            foreach($status_value as $index => $values){
                $selected = '';
                if($values == $value['rental_status']){
                    $selected = 'selected';
                }
                $status .= '<option '.$selected.' value="'.$values.'-'.$value['rental_order_id'].'">'.$values.'</option>';
            }*/

            $status .= $value['rental_status'];

            $status .= '</select>';
            if($current_date >= $start_date && $value['rental_status'] == 'booked'){
                $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due pickup</span>';
            }

            if($current_date > $end_date && $value['rental_status'] == 'pickup'){
                $status .= '<span class="required" style="display:block; width: 100%; font-size: 11px; margin-top: 3px;">due return</span>';
            }

            $action = '';
            $action .= '<a class="btn-edit-action btn btn-primary btn-xs btn-flat" href="'.base_url('adminsite/rental_order/view/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">View</a>';
            $action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-xs btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/trash').'" style="display: block; width: 70px; margin-bottom: 2px;">Trash</a>';
            $action .= '<a class="btn-print-action btn btn-warning btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invpinjam/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvPinjam</a>';

            $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

            if($check_return_order){
                $action .= '<a class="btn-print-action btn btn-success btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invkembali/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvKembali</a>';
            }

            $query_return = $this->global_model->select_where('return_order',array('rental_order_id' => $value['rental_order_id']));
            $minus_deposit = 0;
            $result_minus_deposit = '';
            if(!empty($query_return)){
                $minus_deposit = $query_return[0]['return_late_charges'] - $query_return[0]['return_damage_fine'];
            }
            if($minus_deposit > 0){
                $result_minus_deposit = '<p style="width: 100%; margin-top: 2px; color: green">- '.number_format($minus_deposit).'</p>';
            }
            $data[] = array(
                '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['rental_order_id'].'"></div>',
                $value['rental_invoice'],
                $value['customer_name'].'<br>'.$value['customer_phone'],
                date('d-m-Y',strtotime($value['rental_created'])).' '.date('h:i A',strtotime($value['rental_created'])),
                number_format($value['rental_total_hargasewa']),
                $status_payment,
                number_format($value['rental_total_deposit']).$result_minus_deposit,
                $status,
                $action
                );
        }
    }

    $result = $this->custom_lib->datatables_data($query,$data);
    echo json_encode($result);
}

public function datatables_trash(){

    $order = array(
        'rental_invoice' => 'desc'
        );
    $where = array();
    $where = array(
        'rental_active' => 0
        );

    $query = $this->global_model->getDataWhereOrder('rental_order',$where,$order);

    $data = array();
    if(!empty($query)){
        foreach($query->result_array() as $index => $value) {

            $status_payment = '';
            if($value['rental_payment_status'] == 'unpaid'){
                $status_payment = '<span class="label label-danger">Unpaid</span>';
            } else {
                $status_payment = '<span class="label label-success">Paid</span>';
            }

            $action = '';
            $action = '<a class="btn-ajax-restore-action btn btn-primary btn-sm btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/restore').'" style="margin-right: 5px;">Restore</a>';
            $action .= '<a class="btn-delete-action btn-ajax-delete-action btn btn-danger btn-sm btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/delete').'">Delete</a>';

            $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

            $data[] = array(
                '<div class="icheckbox"><input type="checkbox" name="check_action" value="'.$value['rental_order_id'].'"></div>',
                $value['rental_invoice'],
                '<p style="width: 100%; margin-bottom: 2px;">'.$value['customer_name'].'</p>
                <p style="width: 100%; margin-bottom: 2px;">'.$value['customer_phone'].'</p>',
                '<p style="width: 100%; margin-bottom: 2px;">'.date('d-m-Y',strtotime($value['rental_created'])).'</p>
                <p style="width: 100%; margin-bottom: 2px;">'.date('h:i A',strtotime($value['rental_created'])).'</p>',
                'Rp. '.number_format($value['rental_total']),
                $status_payment,
                'Rp. '.number_format($value['rental_total_deposit']),
                $value['rental_status'],
                $action
                );
        }
    }

    $result = $this->custom_lib->datatables_data($query,$data);
    echo json_encode($result);
}

public function invpinjam($id){
    $id = $this->uri->segment(4);

    $data['store_address']      = '';
    $data['rental_order']       = $this->global_model->select_where('rental_order',array('rental_order_id' => $id));
    $data['rental_product']     = $this->global_model->select_where('rental_product',array('rental_order_id' => $id));
    $data['rental_extrapayment']= $this->backend_model->getExtraPaymentForPrint($id);
    $data['terima_bayarsewa']   = $this->backend_model->getTanggalJenisTransaksiSewa($id);
    $data['terima_bayardeposit']= $this->backend_model->getTanggalJenisTransaksiDeposit($id);
    $data['invoice_footer']     = $this->backend_model->get_invoice_footer();
    if(!empty($data['rental_order'])){
        $store_address  = $this->global_model->select_where('category',array('category_id' => $data['rental_order'][0]['store_location_category_id']));
        $data['store_address']  = $store_address[0]['category_value_textarea'];
    }

    if(is_numeric($id)){
        $this->load->view('adminsite/v_print_invpinjam',$data);
    } else {
        $this->load->view('adminsite/v_print_not_found');
    }
}

public function invkembali($id){
    $id = $this->uri->segment(4);

    $data['store_address']      = '';
    $data['return_order']       = $this->global_model->select_where('return_order',array('rental_order_id' => $id));
    $data['rental_order']       = $this->global_model->select_where('rental_order',array('rental_order_id' => $id));
    $data['rental_product']     = $this->global_model->select_where('rental_product',array('rental_order_id' => $id));
    $data['rental_extrapayment']= $this->backend_model->getExtraPaymentForPrint($id);
    $data['rental_denda']       = $this->backend_model->getDendaForPrint($id);
    $data['kembali_deposit']    = $this->backend_model->getTanggalJenisTransaksiReturn($id);
    $data['invoice_footer']     = $this->backend_model->get_invoice_footer();
    if(!empty($data['rental_order'])){
        $store_address  = $this->global_model->select_where('category',array('category_id' => $data['rental_order'][0]['store_location_category_id']));
        $data['store_address']  = $store_address[0]['category_value_textarea'];
    }

    if(is_numeric($id)){
        $this->load->view('adminsite/v_print_invkembali',$data);
    } else {
        $this->load->view('adminsite/v_print_not_found');
    }
}

public function form() {
        //View
    //Ndung start
    $this->form_validation->set_rules('selectDelivery','Delivery Option','trim');
    //Ndung End
    $this->form_validation->set_rules('product_id','Product','trim');
    $this->form_validation->set_rules('customer_id','Customer','trim');
    $this->form_validation->set_rules('customer_name','Name','trim');
    $this->form_validation->set_rules('customer_phone','Phone','trim|required');
    $this->form_validation->set_rules('start_date','Rental Date','trim|required');
    $this->form_validation->set_rules('end_date','Return Date','trim|required');
    $this->form_validation->set_rules('store_location_category_id','Store Location','trim|required|callback_valid_store');
    $this->form_validation->set_rules('rental_product_sizestock_id[]','Product Items (Cost)','trim|required');
    $this->form_validation->set_error_delimiters('', '');
    if($this->form_validation->run() == false){
        $result = array(
            'process' => 'validation',
            'message' => validation_errors(),
            'flag'    => false
            );
    } else {
        $customer_id        = $this->input->post('customer_id');
        if(empty($customer_id)){
            $customer_id    = '';
        } else {
            $customer_id    = $this->input->post('customer_id');
        }
        $customer_name      = $this->input->post('customer_name');
        $customer_phone     = $this->input->post('customer_phone');
        $customer_address   = $this->input->post('customer_address');
        $end_date           = $this->input->post('end_date');
        $start_date         = $this->input->post('start_date');

        $store_location_category_id  = $this->input->post('store_location_category_id');
        $rental_order_id             = $this->input->post('rental_order_id');
        $rental_invoice              = $this->input->post('rental_invoice');
        $rental_status               = $this->input->post('rental_status');
        $rental_payment_status       = $this->input->post('rental_payment_status');
        $rental_product_id           = $this->input->post('rental_product_id');
        $product_id                  = $this->input->post('product_id');
        $rental_product_sizestock_id = $this->input->post('rental_product_sizestock_id');
        $rental_product_nama         = $this->input->post('rental_product_nama');
        $rental_product_isipaket     = $this->input->post('rental_product_isipaket');
        $rental_product_kode         = $this->input->post('rental_product_kode');
        $rental_product_size         = $this->input->post('rental_product_size');
        $rental_product_qty          = $this->input->post('rental_product_qty');
        $rental_product_hargasewa    = $this->input->post('rental_product_hargasewa');
        $rental_product_deposit      = $this->input->post('rental_product_deposit');
        $rental_total_deposit        = $this->input->post('rental_total_deposit');
        $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
        $rental_total                = $this->input->post('rental_total');

        //Ndung Start
        $delivery_option             = $this->input->post('selectDelivery');
        //Ndung End

        if(empty($rental_payment_status)){
            $rental_payment_status = 'paid';
        }

        if(empty($rental_status)){
            $rental_status = 'booked';
        }

            if(empty($rental_invoice)){
                if(!empty($store_location_category_id)){
                    $query_invoice_number = $this->backend_model->_get_last_invoice_number($store_location_category_id);

                    if(isset($query_invoice_number['category']) && !empty($query_invoice_number['category'])){

                        $rental_invoice = $query_invoice_number['category'][0]['category_value_text'].'-1';

                    } else {

                        $last_invoice = explode('-',$query_invoice_number);
                        $n = 1;
                        $i = 0;
                        while ($i <= $last_invoice[1]) { 
                            $id = sprintf("%01d", $n);
                            $i++; $n++;
                        }
                        $rental_invoice = $last_invoice[0].'-'. $id;

                    }
                }
            }

            $get_address_location = $this->global_model->select_where('category',array('category_id' => $store_location_category_id));
            $address              = $get_address_location[0]['category_value_textarea'];

            $customer_email = '';

            if(!empty($customer_id)){
                $get_email = $this->backend_model->get_email_customer($customer_id);
                $customer_email = $get_email[0]['customer_email'];
            }

            $rental_note = '';
            if(!empty($rental_order_id)){
                $get_rental_note = $this->backend_model->get_rental_note($rental_order_id);
                $rental_note = $get_rental_note[0]['rental_note'];
            }

            $data_single = array(
                'rental_invoice'             => $rental_invoice,
                'store_location_category_id' => $store_location_category_id,
                'rental_note'                => html_entity_decode($rental_note),
                'store_address'              => $address,
                'customer_id'                => $customer_id,
                'customer_name'              => $customer_name,
                'customer_email'             => $customer_email,
                'customer_phone'             => $customer_phone,
                'customer_address'           => $customer_address,
                'end_date'                   => $end_date,
                'start_date'                 => $start_date,
                'rental_total'               => number_format($rental_total),
                'rental_total_deposit'       => number_format($rental_total_deposit),
                'rental_total_hargasewa'     => number_format($rental_total_hargasewa),
                //Ndungg
                'delivery_option'            => $delivery_option
                );

            $data_product = '';
            if(is_array($rental_product_sizestock_id)){
                foreach($rental_product_sizestock_id as $index => $value){
                    $data_product .= '<tr>';
                    if(isset($rental_order_id) && !empty($rental_order_id)){
                        $data_product .= '<input type="hidden" name="rental_order_id" value="'.$rental_order_id.'">';    
                    }

                    if(isset($store_location_category_id) && !empty($store_location_category_id)){
                        $data_product .= '<input type="hidden" name="store_location_category_id" value="'.$store_location_category_id.'">';    
                    }

                    $data_product .= '<input type="hidden" name="rental_status" value="'.$rental_status.'">';    
                    $data_product .= '<input type="hidden" name="rental_payment_status" value="'.$rental_payment_status.'">';
                    $data_product .= '<input type="hidden" name="rental_total_deposit" value="'.$rental_total_deposit.'">';
                    $data_product .= '<input type="hidden" name="rental_total_hargasewa" value="'.$rental_total_hargasewa.'">';
                    $data_product .= '<input type="hidden" name="rental_total" value="'.$rental_total.'">';
                    $data_product .= '<input type="hidden" name="rental_total_extrapayment" value="0">';

                    $data_product .= '<td style="text-align: left; width: 150px;">';
                    if(isset($rental_product_id) && !empty($rental_product_id)){
                        $data_product .= '<input type="hidden" name="rental_product_id[]" value="'.$rental_product_id[$index].'">';    
                    }
                    $data_product .= '<input type="hidden" name="product_id[]" value="'.$product_id[$index].'">
                    <input type="hidden" name="rental_product_sizestock_id[]" value="'.$value.'">
                    <input type="hidden" name="rental_product_nama[]" value="'.$rental_product_nama[$index].'">'.$rental_product_nama[$index].'</td>';

                    $data_product .= '<td style="text-align: center; width: 20px;">
                    <input type="hidden" name="rental_product_kode[]" value="'.$rental_product_kode[$index].'">'.$rental_product_kode[$index].'</td>';

                    $data_product .= '<td style="text-align: center; width: 40px;">
                    <textarea name="rental_product_isipaket[]" style="text-align:center;">'.$rental_product_isipaket[$index].'</textarea></td>';

                    $data_product .= '<td style="text-align:center; width: 100px;">
                    <input type="hidden" name="rental_product_size[]" value="'.$rental_product_size[$index].'">'.$rental_product_size[$index].'</td>';

                    $data_product .= '<td style="text-align:center; width: 40px;">
                    <input type="hidden" name="rental_product_qty[]" value="'.$rental_product_qty[$index].'">'.$rental_product_qty[$index].'</td>';

                    $data_product .= '<td style="text-align:center;">
                    <input style="text-align:center;" type="text" class="form-control pricemask" name="rental_product_hargasewa[]" value="'.$rental_product_hargasewa[$index].'"></td>';

                    $data_product .= '<td style="text-align:center;">
                    <input style="text-align:center;" type="text" class="form-control pricemask" name="rental_product_deposit[]" value="'.$rental_product_deposit[$index].'"></td>';

                    $data_product .= '</tr>';
                }
            }

            $result = array(
                'flag'              =>  true, 
                'process'           =>  'insert',
                'data_single'       =>  $data_single,
                'data_product'      =>  $data_product,
                'query'             =>  $_POST
                );
        }
        echo json_encode($result);
    }

    public function return_order() {
        //View
        $this->form_validation->set_rules('return_order_id','Return Order ID','trim');
        $this->form_validation->set_rules('return_note','Return Note','trim');
        $this->form_validation->set_rules('return_current_deposit','Return Current Deposit','trim');
        $this->form_validation->set_rules('return_late_charges','Return Late Charges','trim');
        $this->form_validation->set_rules('return_damage_fine','Return Damage Fine','trim');
        $this->form_validation->set_rules('return_deposit','Return Deposit','trim');
        $this->form_validation->set_rules('return_date','Return Date','trim');

        $this->form_validation->set_rules('product_id','Product','trim');
        $this->form_validation->set_rules('customer_id','Customer','trim');
        $this->form_validation->set_rules('customer_name','Name','trim');
        $this->form_validation->set_rules('customer_phone','Phone','trim');
        $this->form_validation->set_rules('start_date','Rental Date','trim');
        $this->form_validation->set_rules('end_date','Return Date','trim');
        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run() == false){
            $result = array(
                'process' => 'validation',
                'message' => validation_errors(),
                'flag'    => false
                );
        } else {
            $customer_id        = $this->input->post('customer_id');
            if(empty($customer_id)){
                $customer_id    = '';
            } else {
                $customer_id    = $this->input->post('customer_id');
            }
            $customer_name      = $this->input->post('customer_name');
            $customer_phone     = $this->input->post('customer_phone');
            $customer_address   = $this->input->post('customer_address');
            $end_date           = $this->input->post('end_date');
            $start_date         = $this->input->post('start_date');

            $rental_order_id             = $this->input->post('rental_order_id');
            $rental_invoice              = $this->input->post('rental_invoice');
            $rental_status               = $this->input->post('rental_status');

            $return_order_id             = $this->input->post('return_order_id');
            $rental_product_id           = $this->input->post('rental_product_id');
            $product_id                  = $this->input->post('product_id');
            $rental_product_sizestock_id = $this->input->post('rental_product_sizestock_id');
            $rental_product_nama         = $this->input->post('rental_product_nama');
            $rental_product_isipaket     = $this->input->post('rental_product_isipaket');
            $rental_product_kode         = $this->input->post('rental_product_kode');
            $rental_product_size         = $this->input->post('rental_product_size');
            $rental_product_qty          = $this->input->post('rental_product_qty');
            $rental_product_hargasewa    = $this->input->post('rental_product_hargasewa');
            $rental_product_deposit      = $this->input->post('rental_product_deposit');
            $rental_total_deposit        = $this->input->post('rental_total_deposit');
            $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
            $rental_total                = $this->input->post('rental_total');
            $rental_payment_status       = $this->input->post('rental_payment_status');
            
            $return_date                 = $this->input->post('return_date');
            $return_note                 = $this->input->post('return_note');
            $return_current_deposit      = preg_replace("/[^0-9\.]/","",$this->input->post('return_current_deposit'));
            $return_late_charges         = preg_replace("/[^0-9\.]/","",$this->input->post('return_late_charges'));
            $return_damage_fine          = preg_replace("/[^0-9\.]/","",$this->input->post('return_damage_fine'));
            $return_deposit              = preg_replace("/[^0-9\.]/","",$this->input->post('return_deposit'));

            $customer_email = '';

            if(!empty($customer_id)){
                $get_email = $this->backend_model->get_email_customer($customer_id);
                $customer_email = $get_email[0]['customer_email'];
            }

            $data_single = array(
                'rental_invoice'        => $rental_invoice,
                'customer_id'           => $customer_id,
                'customer_name'         => $customer_name,
                'customer_phone'        => $customer_phone,
                'customer_address'      => $customer_address,
                'customer_email'        => $customer_email,
                'end_date'              => $end_date,
                'start_date'            => date('j F Y',strtotime($start_date)),
                'rental_total'          => number_format($rental_total),
                'rental_total_deposit'  => number_format($rental_total_deposit),
                'rental_total_hargasewa'=> number_format($rental_total_hargasewa),
                'return_note'           => $return_note,
                'return_date'           => $return_date,
                'return_current_deposit'=> number_format($return_current_deposit),
                'return_late_charges'   => number_format($return_late_charges),
                'return_damage_fine'    => number_format($return_damage_fine),
                'return_deposit'        => number_format($return_deposit)
                );

            $data_product = '';
            if(is_array($rental_product_sizestock_id)){
                foreach($rental_product_sizestock_id as $index => $value){
                    $data_product .= '<tr>';

                    if(isset($rental_order_id) && !empty($rental_order_id)){
                        $data_product .= '<input type="hidden" name="rental_order_id" value="'.$rental_order_id.'">';    
                    }
                    if(isset($return_order_id) && !empty($return_order_id)){
                        $data_product .= '<input type="hidden" name="return_order_id" value="'.$return_order_id.'">';    
                    }
                    $data_product .= '<input type="hidden" name="rental_end_date" value="'.$end_date.'">';
                    $data_product .= '<input type="hidden" name="return_current_deposit" value="'.$return_current_deposit.'">';
                    $data_product .= '<input type="hidden" name="return_late_charges" value="'.$return_late_charges.'">';
                    $data_product .= '<input type="hidden" name="return_damage_fine" value="'.$return_damage_fine.'">';
                    $data_product .= '<input type="hidden" name="return_deposit" value="'.$return_deposit.'">';
                    $data_product .= '<input type="hidden" name="rental_payment_status" value="'.$rental_payment_status.'">';
                    $data_product .= '<input type="hidden" name="rental_total_deposit" value="'.$rental_total_deposit.'">';
                    $data_product .= '<input type="hidden" name="rental_total_hargasewa" value="'.$rental_total_hargasewa.'">';
                    $data_product .= '<input type="hidden" name="rental_total" value="'.$rental_total.'">';

                    $data_product .= '<td style="text-align: left; width: 150px;">';
                    if(isset($rental_product_id) && !empty($rental_product_id)){
                        $data_product .= '<input type="hidden" name="rental_product_id[]" value="'.$rental_product_id[$index].'">';    
                    }
                    $data_product .= '<input type="hidden" name="product_id[]" value="'.$product_id[$index].'">
                    <input type="hidden" name="rental_product_sizestock_id[]" value="'.$value.'">
                    <input type="hidden" name="rental_product_nama[]" value="'.$rental_product_nama[$index].'"><span>'.$rental_product_nama[$index].'</span><span>'.$rental_product_kode[$index].'</span></td>';

                    $data_product .= '<td style="text-align: center; width: 40px;">
                    <textarea readonly>'.$rental_product_isipaket[$index].'</textarea></td>';

                    $data_product .= '<td style="text-align:center; width: 100px;">
                    <input type="hidden" name="rental_product_size[]" value="'.$rental_product_size[$index].'">'.$rental_product_size[$index].'</td>';

                    $data_product .= '<td style="text-align:center; width: 40px;">
                    <input type="hidden" name="rental_product_qty[]" value="'.$rental_product_qty[$index].'">'.$rental_product_qty[$index].'</td>';

                    $data_product .= '<td style="text-align:center; width: 130px;">
                    <input type="hidden" name="rental_product_hargasewa[]" value="'.$rental_product_hargasewa[$index].'">Rp. '.number_format($rental_product_hargasewa[$index]).'</td>';

                    $data_product .= '<td style="text-align:center; width: 130px;"><input type="hidden" name="rental_product_deposit[]" value="'.$rental_product_deposit[$index].'">Rp. '.number_format($rental_product_deposit[$index]).'</td>';

                    $data_product .= '</tr>';
                }
            }

            $result = array(
                'flag'              =>  true, 
                'process'           =>  'insert',
                'data_single'       =>  $data_single,
                'data_product'      =>  $data_product,
                'query'             =>  $_POST
                );
        }
        echo json_encode($result);
    }

    public function update(){
        if(isset($_POST) && is_array($_POST)){
            $customer_id                 = $this->input->post('customer_id');
            if(empty($customer_id)){
                $customer_id = 0;
            }
            $customer_name               = $this->input->post('customer_name');
            $customer_phone              = $this->input->post('customer_phone');
            $customer_address            = $this->input->post('customer_address');
            $customer_email              = $this->input->post('customer_email');
            $rental_end_date             = $this->input->post('rental_end_date');
            $rental_start_date           = $this->input->post('rental_start_date');
            $rental_total_deposit        = $this->input->post('rental_total_deposit');
            $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
            $rental_total                = $this->input->post('rental_total');
            $rental_note                 = $this->input->post('rental_note');
            $rental_konfirmasi_booking   = $this->input->post('rental_konfirmasi_booking');
            $rental_terima_uangsewa      = $this->input->post('rental_terima_uangsewa');
            $rental_terima_kostum        = $this->input->post('rental_terima_kostum');
            $rental_terima_uangdeposit   = $this->input->post('rental_terima_uangdeposit');
            $rental_terima_uangdeposit   = $this->input->post('rental_terima_uangdeposit');
            $rental_return_uangdeposit   = $this->input->post('rental_return_uangdeposit');
            $rental_invoice              = $this->input->post('rental_invoice');

            $rental_order_id             = $this->input->post('rental_order_id');
            $rental_invoice              = $this->input->post('rental_invoice');
            $rental_status               = $this->input->post('rental_status');

            $rental_payment_status       = $this->input->post('rental_payment_status');
            $rental_product_id           = $this->input->post('rental_product_id');
            $product_id                  = $this->input->post('product_id');
            $rental_product_sizestock_id = $this->input->post('rental_product_sizestock_id');
            $rental_product_nama         = $this->input->post('rental_product_nama');
            $rental_product_isipaket     = $this->input->post('rental_product_isipaket');
            $rental_product_kode         = $this->input->post('rental_product_kode');
            $rental_product_size         = $this->input->post('rental_product_size');
            $rental_product_qty          = $this->input->post('rental_product_qty');
            $rental_product_hargasewa    = $this->input->post('rental_product_hargasewa');
            $rental_product_deposit      = $this->input->post('rental_product_deposit');
            $rental_total_deposit        = $this->input->post('rental_total_deposit');
            $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
            $rental_total                = $this->input->post('rental_total');
            $save                        = $this->input->post('save');

            $rental_extrapayment_id      = $this->input->post('rental_extrapayment_id');
            $rental_extrapayment_flag    = $this->input->post('rental_extrapayment_flag');
            $rental_extranote            = $this->input->post('rental_extranote');
            $rental_extrapayment         = $this->input->post('rental_extrapayment');
            $rental_total_extrapayment   = $this->input->post('rental_total_extrapayment');

            $jenis_transaksi_id          = $this->input->post('jenis_transaksi_id');
            $jenis_transaksi             = $this->input->post('jenis_transaksi');
            $jenis_transaksi_flag        = $this->input->post('jenis_transaksi_flag');
            $jenis_transaksi_note        = $this->input->post('jenis_transaksi_note');
            $jenis_transaksi_nominal     = $this->input->post('jenis_transaksi_nominal');

            $store_location_category_id  = $this->input->post('store_location_category_id');
            $check_phone_number          = $this->global_model->select_where('customer',array('customer_phone' => $customer_phone));
            $rented                      = array();
            $temp_rented                 = array();

            //Ndung Start
            $delivery_option             = $this->input->post('selectDelivery');
            if($delivery_option == 'Diambil sendiri'){
                $delivery_option = 'sendiri';
            } else if($delivery_option == 'Gojek'){
                $delivery_option = 'gojek';
            } else if($delivery_option == 'JNE'){
                $delivery_option = 'jne';
            }

            $get_product_popularity      = $this->backend_model->get_product_popularity($rental_order_id);
            if(!empty($get_product_popularity)){
                $prod_rental = array();
                foreach($get_product_popularity as $index => $value){
                    $prod_rental[] = $value['product_id'];
                    $rented[$value['product_id']] = $value['rented'];
                }
                foreach($product_id as $index => $value){
                    if(!in_array($value,$prod_rental)){
                        $get_new_product = $this->backend_model->get_new_product_popularity($value);
                        if(!empty($get_new_product)){
                            foreach($get_new_product as $key => $row){
                                $rented[$row['product_id']] = $row['rented'];  
                            }
                        }
                    }
                }
            }

            if(!empty($check_phone_number)){
                foreach($check_phone_number as $index => $value){
                    $update_customer = array(
                        'customer_name'     => $customer_name,
                        'customer_phone'    => $customer_phone,
                        'customer_email'    => $customer_email,
                        'customer_address'  => htmlentities($customer_address),
                        'customer_modified' => date('Y-m-d H:i:s'),
                        );
                    $this->global_model->update('customer',$update_customer,array('customer_id'=> $value['customer_id']));
                }
            } else {
                $insert_customer = array(
                    'customer_name'     => $customer_name,
                    'customer_phone'    => $customer_phone,
                    'customer_address'  => htmlentities($customer_address),
                    'customer_email'    => $customer_email,
                    'customer_created'  => date('Y-m-d H:i:s'),
                    'customer_modified' => NULL
                    );
                $this->global_model->insert('customer',$insert_customer);
                $customer_id = $this->db->insert_id();
            }
            $current_date           = date('j F Y');
            /*$rental_payment_status  = 'unpaid';

            if($save == 1 && $current_date < $rental_start_date){
                $rental_payment_status = 'unpaid';
            } else {
                $rental_payment_status = 'paid';
            }*/

            $store_location_name    = '';
            $store_location_db      = $this->global_model->select_where('category',array('category_id' => $store_location_category_id));
            if(!empty($store_location_db)){
                $store_location_name = $store_location_db[0]['category_name'];
            }

            $update_rental_order = array(
                'store_location_category_id'=> $store_location_category_id,
                'store_location'            => $store_location_name,
                'customer_id'               => $customer_id,
                'customer_name'             => $customer_name,
                'customer_phone'            => $customer_phone,
                'customer_email'            => $customer_email,
                'customer_address'          => htmlentities($customer_address),
                'rental_start_date'         => date('Y-m-d H:i:s',strtotime($rental_start_date)),
                'rental_end_date'           => date('Y-m-d H:i:s',strtotime($rental_end_date)),
                'rental_total_deposit'      => $rental_total_deposit,
                'rental_total_hargasewa'    => $rental_total_hargasewa,
                'rental_total'              => $rental_total,
                'rental_payment_status'     => $rental_payment_status,
                'rental_note'               => htmlentities($rental_note),
                'rental_status'             => $rental_status,
                'rental_konfirmasi_booking' => $rental_konfirmasi_booking,
                'rental_terima_uangsewa'    => $rental_terima_uangsewa,
                'rental_terima_kostum'      => $rental_terima_kostum,
                'rental_terima_uangdeposit' => $rental_terima_uangdeposit,
                'rental_return_uangdeposit' => $rental_return_uangdeposit,
                'rental_total_extrapayment' => $rental_total_extrapayment,
                'rental_modified'           => date('Y-m-d H:i:s'),
                //Ndung
                'delivery_option'           => $delivery_option
                );
            $this->global_model->update('rental_order',$update_rental_order,array('rental_order_id'=>$rental_order_id));

            if($rental_status == 'booked' || $rental_status == 'pickup' && !empty($rental_order_id)){
                $this->global_model->update('rental_order',array('rental_return_date' => null,'rental_return_uangdeposit' => null),array('rental_order_id' => $rental_order_id));
                $this->global_model->delete('return_order',array('rental_order_id' => $rental_order_id));
                $this->global_model->delete('jenis_transaksi',array('rental_order_id' => $rental_order_id,'jenis_transaksi'=>'return'));
            }

            $check_db_product       = $this->global_model->select_where('rental_product',array('rental_order_id' => $rental_order_id));
            $check_db_extrapayment  = $this->backend_model->checkDBextrapayment($rental_order_id);
            $check_db_jenistransaksi= $this->backend_model->checkDBjenistransaksi($rental_order_id);

            if(is_array($rental_product_id)){
                $post_product_id            = array();
                $post_product               = array();
                $post_product_qty           = array();
                $post_product_sizestock_id  = array();
                $post_sum_product_qty       = array();

                foreach($check_db_product as $index => $value){
                    @$post_sum_product_qty[$value['product_id']]+=$value['rental_product_qty'];
                    $post_product_id[]           = $value['product_id'];
                    $post_product[]              = $value['rental_product_id'];
                    $post_product_qty[]          = $value['rental_product_qty'];
                    $post_product_sizestock_id[] = $value['rental_product_sizestock_id'];
                }

                foreach($product_id as $index => $value){
                    if(in_array($value,$post_product_id) && isset($rented[$value]) && isset($post_sum_product_qty[$value])){
                        $temp_rented[$value] = $rented[$value] - $post_sum_product_qty[$value];
                    } elseif(isset($rented[$value])) {
                        $temp_rented[$value] = $rented[$value]; 
                    }
                }

                foreach($post_product_id as $index => $value){
                    if(!in_array($value,$product_id) && isset($rented[$value])){
                        $temp_rented[$value] = $rented[$value];
                    }
                }

                foreach($post_product as $key => $row){
                    if(!in_array($row,$rental_product_id)){
                        $this->global_model->delete('rental_product',array("rental_product_id" => $row));
                    }
                }

                $total_qty_product          = 0;
                $update_sum_product_qty     = array();
                foreach($rental_product_id as $index => $value){
                    if(isset($rental_product_qty[$index])){
                        $total_qty_product+=$rental_product_qty[$index];
                    }
                    @$update_sum_product_qty[$product_id[$index]]+=$rental_product_qty[$index];
                }

                $firstProduct = '';
                if(isset($rental_product_nama[0])){
                    $firstProduct = $rental_product_nama[0];
                }
                foreach($rental_product_id as $index => $value){
                    if($value == "" || empty($value)){
                        $insert_rental_product = array(
                            'rental_order_id'               => $rental_order_id,
                            'product_id'                    => $product_id[$index],
                            'rental_product_sizestock_id'   => $rental_product_sizestock_id[$index],
                            'rental_product_nama'           => $rental_product_nama[$index],
                            'rental_product_qty'            => $rental_product_qty[$index],
                            'rental_product_hargasewa'      => preg_replace("/[^0-9\.]/","",$rental_product_hargasewa[$index]),
                            'rental_product_deposit'        => preg_replace("/[^0-9\.]/","",$rental_product_deposit[$index]),
                            'rental_product_kode'           => $rental_product_kode[$index],
                            'rental_product_isipaket'       => htmlentities($rental_product_isipaket[$index]),
                            'rental_product_size'           => $rental_product_size[$index]
                            );
                        $this->global_model->insert('rental_product',$insert_rental_product);
                    } else {
                        $update_rental_product = array(
                            'product_id'                    => $product_id[$index],
                            'rental_product_sizestock_id'   => $rental_product_sizestock_id[$index],
                            'rental_product_nama'           => $rental_product_nama[$index],
                            'rental_product_qty'            => $rental_product_qty[$index],
                            'rental_product_hargasewa'      => preg_replace("/[^0-9\.]/","",$rental_product_hargasewa[$index]),
                            'rental_product_deposit'        => preg_replace("/[^0-9\.]/","",$rental_product_deposit[$index]),
                            'rental_product_kode'           => $rental_product_kode[$index],
                            'rental_product_isipaket'       => htmlentities($rental_product_isipaket[$index]),
                            'rental_product_size'           => $rental_product_size[$index]
                            );
                        $this->global_model->update('rental_product',$update_rental_product,array('rental_product_id' => $value));
                    }
                }
            }

            if(!empty($temp_rented)){
                foreach($temp_rented as $index => $value){
                    $rented_value = 0;
                    if(isset($update_sum_product_qty[$index])){
                        $rented_value = $value + $update_sum_product_qty[$index];
                    } else {
                        $rented_value = $value - $post_sum_product_qty[$index];
                    }
                    $upd_popularity = array(
                        'rented' => $rented_value
                    );
                    $this->global_model->update('product_popularity',$upd_popularity,array('product_id' => $index));
                }
            }

            if(is_array($rental_extrapayment_id)){
                $get_rental_extrapayment_id            = array();
                foreach($check_db_extrapayment as $index => $value){
                    $get_rental_extrapayment_id[]           = $value['rental_extrapayment_id'];
                }
                foreach($get_rental_extrapayment_id as $key => $row){
                    if(!in_array($row,$rental_extrapayment_id)){
                        $this->global_model->delete('rental_extrapayment',array("rental_extrapayment_id" => $row));
                    }
                }
                foreach($rental_extrapayment_id as $index => $value){
                    if($value == "" || empty($value)){
                        $ins_payment = array(
                            'rental_order_id'           => $rental_order_id,
                            'rental_extrapayment'       => preg_replace("/[^0-9\.]/","",$rental_extrapayment[$index]),
                            'rental_extranote'          => htmlentities($rental_extranote[$index]),
                            'rental_extrapayment_flag'  => $rental_extrapayment_flag[$index],
                            'rental_extra_created'      => date("Y-m-d H:i:s"),
                            'rental_extra_modified'     => NULL
                        );
                        $this->global_model->insert('rental_extrapayment',$ins_payment);
                    } else {
                        $upd_payment = array(
                            'rental_order_id'           => $rental_order_id,
                            'rental_extrapayment'       => preg_replace("/[^0-9\.]/","",$rental_extrapayment[$index]),
                            'rental_extranote'          => htmlentities($rental_extranote[$index]),
                            'rental_extrapayment_flag'  => $rental_extrapayment_flag[$index],
                            'rental_extra_modified'     => date("Y-m-d H:i:s")
                        );
                        $this->global_model->update('rental_extrapayment',$upd_payment,array('rental_extrapayment_id' => $value));
                    }
                }
            } else {
                if(!empty($check_db_extrapayment)){
                    foreach($check_db_extrapayment as $index => $value){
                        $this->global_model->delete('rental_extrapayment',array('rental_extrapayment_id' => $value['rental_extrapayment_id']));
                    }
                    $this->global_model->update('rental_order',array('rental_total_extrapayment' => 0),array('rental_order_id' => $rental_order_id));
                }
            }

            if(is_array($jenis_transaksi_id)){
                $get_rental_jenis_transaksi_id            = array();
                foreach($check_db_jenistransaksi as $index => $value){
                    $get_rental_jenis_transaksi_id[]           = $value['jenis_transaksi_id'];
                }
                foreach($get_rental_jenis_transaksi_id as $key => $row){
                    if(!in_array($row,$jenis_transaksi_id)){
                        $this->global_model->delete('jenis_transaksi',array("jenis_transaksi_id" => $row));
                    }
                }
                foreach($jenis_transaksi_id as $index => $value){
                    if($value == "" || empty($value)){
                        $ins_jenis_transaksi = array(
                            'rental_order_id'                   => $rental_order_id,
                            'jenis_transaksi'                   => $jenis_transaksi[$index],
                            'jenis_transaksi_nominal'           => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal[$index]),
                            'jenis_transaksi_note'              => htmlentities($jenis_transaksi_note[$index]),
                            'jenis_transaksi_flag'              => $jenis_transaksi_flag[$index],
                            'jenis_transaksi_created'           => date("Y-m-d H:i:s"),
                            'jenis_transaksi_modified'          => date("Y-m-d H:i:s"),
                            'rental_invoice'                    => $rental_invoice,
                            'jenis_transaksi_qty'               => $total_qty_product,
                            'jenis_transaksi_product'           => $firstProduct,
                            'jenis_transaksi_status'            => $rental_payment_status,
                            'jenis_transaksi_customer_nama'     => $customer_name,
                            'jenis_transaksi_customer_phone'    => $customer_phone
                        );
                        $this->global_model->insert('jenis_transaksi',$ins_jenis_transaksi);
                    } else {
                        $upd_jenis_transaksi = array(
                            'jenis_transaksi'                   => $jenis_transaksi[$index],
                            'jenis_transaksi_nominal'           => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal[$index]),
                            'jenis_transaksi_note'              => htmlentities($jenis_transaksi_note[$index]),
                            'jenis_transaksi_flag'              => $jenis_transaksi_flag[$index],
                            'jenis_transaksi_modified'          => date("Y-m-d H:i:s"),
                            'jenis_transaksi_qty'               => $total_qty_product,
                            'jenis_transaksi_product'           => $firstProduct,
                            'jenis_transaksi_status'            => $rental_payment_status,
                            'jenis_transaksi_customer_nama'     => $customer_name,
                            'jenis_transaksi_customer_phone'    => $customer_phone
                        );
                        $this->global_model->update('jenis_transaksi',$upd_jenis_transaksi,array('jenis_transaksi_id' => $value));
                    }
                }
            } else {
                if(!empty($check_db_jenistransaksi)){
                    foreach($check_db_jenistransaksi as $index => $value){
                        $this->global_model->delete('jenis_transaksi',array('jenis_transaksi_id' => $value['jenis_transaksi_id']));
                    }
                }
            }

            $result = array(
                'ctr'         => 'invpinjam/',//Controller
                'printid'     => $rental_order_id,//rental order id
                'flag'        => true,
                'rented'      => $rented,
                'temp_rented' => $temp_rented,
                'update_sum_product_qty' => $update_sum_product_qty
                );
            $this->session->set_flashdata('success','Save success');
        } else {
            $result = array(
                'flag'        => false
                );
            $this->session->set_flashdata('validation','Something Wrong Please Try Again');
        }
        echo json_encode($result);
    }

    public function update_return(){
        if(isset($_POST) && is_array($_POST)){
            $return_order_id             = $this->input->post('return_order_id');
            $rental_order_id             = $this->input->post('rental_order_id');
            $rental_invoice              = $this->input->post('rental_invoice');
            $customer_id                 = $this->input->post('customer_id');
            $customer_name               = $this->input->post('customer_name');
            $customer_phone              = $this->input->post('customer_phone');
            $customer_address            = $this->input->post('customer_address');
            $customer_email              = $this->input->post('customer_email');

            $rental_end_date             = $this->input->post('rental_end_date');
            $rental_start_date           = $this->input->post('rental_start_date');
            $rental_total_deposit        = $this->input->post('rental_total_deposit');
            $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
            $rental_total                = $this->input->post('rental_total');
            $rental_return_uangdeposit   = $this->input->post('rental_return_uangdeposit');
            $rental_payment_status       = $this->input->post('rental_payment_status');
            $rental_product_sizestock_id = $this->input->post('rental_product_sizestock_id');
            $rental_product_nama         = $this->input->post('rental_product_nama');
            $rental_product_qty          = $this->input->post('rental_product_qty');

            $return_date                 = $this->input->post('return_date');
            $return_note                 = html_entity_decode($this->input->post('return_note'));
            $return_current_deposit      = $this->input->post('return_current_deposit');
            $return_late_charges         = $this->input->post('return_late_charges');
            $return_damage_fine          = $this->input->post('return_damage_fine');
            $return_deposit              = $this->input->post('return_deposit');

            $jenis_transaksi_id          = $this->input->post('jenis_transaksi_id');
            $jenis_transaksi             = $this->input->post('jenis_transaksi');
            $jenis_transaksi_flag        = $this->input->post('jenis_transaksi_flag');
            $jenis_transaksi_note        = $this->input->post('jenis_transaksi_note');
            $jenis_transaksi_nominal     = $this->input->post('jenis_transaksi_nominal');

            $rental_extrapayment_id      = $this->input->post('rental_extrapayment_id');
            $rental_extrapayment_flag    = $this->input->post('rental_extrapayment_flag');
            $rental_extranote            = $this->input->post('rental_extranote');
            $rental_extrapayment         = $this->input->post('rental_extrapayment');

            $rental_dendapayment_id      = $this->input->post('rental_dendapayment_id');
            $rental_dendapayment_flag    = $this->input->post('rental_dendapayment_flag');
            $rental_dendanote            = $this->input->post('rental_dendanote');
            $rental_dendapayment         = $this->input->post('rental_dendapayment');

            $rental_total_extrapayment   = $this->input->post('rental_total_extrapayment');

            $check_db_jenistransaksi     = $this->backend_model->checkDBjenistransaksiReturn($rental_order_id);
            $check_db_extrapayment       = $this->backend_model->checkDBextrapaymentDenda($rental_order_id);
            
            $current_date                = date('j F Y');
            if(!empty($return_order_id)){
                $update_return_order = array(
                    'rental_order_id'           => $rental_order_id,
                    'rental_invoice'            => $rental_invoice,
                    'rental_total_deposit'      => $rental_total_deposit,
                    'rental_total_hargasewa'    => $rental_total_hargasewa,
                    'rental_total'              => $rental_total,
                    'return_customer_id'        => $customer_id,
                    'return_customer_name'      => $customer_name,
                    'return_customer_phone'     => $customer_phone,
                    'return_customer_email'     => $customer_email,
                    'return_customer_address'   => htmlentities($customer_address),
                    'rental_start_date'         => date('Y-m-d H:i:s',strtotime($rental_start_date)),
                    'rental_end_date'           => date('Y-m-d H:i:s',strtotime($rental_end_date)),
                    'return_note'               => htmlentities($return_note),
                    'return_late_charges'       => $return_late_charges,
                    'return_damage_fine'        => $return_damage_fine,
                    'return_deposit'            => $return_deposit,
                    'return_date'               => date('Y-m-d H:i:s',strtotime($return_date)),
                    'return_modified_date'      => date('Y-m-d H:i:s'),
                    );
                $this->global_model->update('return_order',$update_return_order,array('return_order_id' => $return_order_id));

                $update_rental_order = array(
                    'rental_note'               => htmlentities($return_note),
                    'rental_status'             => 'return',
                    'rental_payment_status'     => 'paid',
                    'rental_return_uangdeposit' => $rental_return_uangdeposit,
                    'rental_return_date'        => date('Y-m-d H:i:s',strtotime($return_date)),
                    );
                $this->global_model->update('rental_order',$update_rental_order,array('rental_order_id' => $rental_order_id));
            }

            if(!isset($return_order_id) || empty($return_order_id)){
                $insert_return_order = array(
                    'rental_order_id'           => $rental_order_id,
                    'rental_invoice'            => $rental_invoice,
                    'rental_total_deposit'      => $rental_total_deposit,
                    'rental_total_hargasewa'    => $rental_total_hargasewa,
                    'rental_total'              => $rental_total,
                    'return_customer_id'        => $customer_id,
                    'return_customer_name'      => $customer_name,
                    'return_customer_phone'     => $customer_phone,
                    'return_customer_email'     => $customer_email,
                    'return_customer_address'   => htmlentities($customer_address),
                    'rental_start_date'         => date('Y-m-d H:i:s',strtotime($rental_start_date)),
                    'rental_end_date'           => date('Y-m-d H:i:s',strtotime($rental_end_date)),
                    'return_note'               => htmlentities($return_note),
                    'return_late_charges'       => $return_late_charges,
                    'return_damage_fine'        => $return_damage_fine,
                    'return_deposit'            => $return_deposit,
                    'return_date'               => date('Y-m-d H:i:s',strtotime($return_date)),
                    'return_created_date'       => date('Y-m-d H:i:s'),
                    'return_modified_date'      => NULL,
                    );
                $this->global_model->insert('return_order',$insert_return_order);

                $update_rental_order = array(
                    'rental_note'               => htmlentities($return_note),
                    'rental_status'             => 'return',
                    'rental_payment_status'     => 'paid',
                    'rental_return_uangdeposit' => $rental_return_uangdeposit,
                    'rental_return_date'        => date('Y-m-d H:i:s',strtotime($return_date))
                    );
                $this->global_model->update('rental_order',$update_rental_order,array('rental_order_id' => $rental_order_id));
            }

            // start jenis transaksi 
            $total_qty_product = 0;
            $firstProduct      = '';
            if(isset($rental_product_nama[0])){
                $firstProduct = $rental_product_nama[0];
            }
            // end jenis transaksi

            if(isset($rental_product_sizestock_id) && is_array($rental_product_sizestock_id)){
                $get_sum_product_qty = array();
                foreach($rental_product_sizestock_id as $index => $value){
                    if(isset($rental_product_qty[$index])){
                        $total_qty_product+=$rental_product_qty[$index];
                    }
                }
            }

            if(is_array($jenis_transaksi_id)){
                $get_rental_jenis_transaksi_id            = array();
                foreach($check_db_jenistransaksi as $index => $value){
                    if($value['jenis_transaksi'] == 'return'){
                        $get_rental_jenis_transaksi_id[]           = $value['jenis_transaksi_id'];
                    }
                }
                foreach($get_rental_jenis_transaksi_id as $key => $row){
                    $checkExistReturn = $this->backend_model->checkExistReturnTransaksi($row);
                    if($checkExistReturn && !in_array($row,$jenis_transaksi_id)){
                        $this->global_model->delete('jenis_transaksi',array("jenis_transaksi_id" => $row));
                    }
                }
                foreach($jenis_transaksi_id as $index => $value){
                    if($value == "" || empty($value)){
                        $ins_jenis_transaksi = array(
                            'rental_order_id'           => $rental_order_id,
                            'jenis_transaksi'           => $jenis_transaksi[$index],
                            'jenis_transaksi_nominal'   => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal[$index]),
                            'jenis_transaksi_note'      => htmlentities($jenis_transaksi_note[$index]),
                            'jenis_transaksi_flag'      => $jenis_transaksi_flag[$index],
                            'jenis_transaksi_created'   => date("Y-m-d H:i:s"),
                            'jenis_transaksi_modified'  => NULL,
                            'jenis_transaksi_qty'       => $total_qty_product,
                            'jenis_transaksi_product'   => $firstProduct,
                            'jenis_transaksi_status'    => $rental_payment_status,
                            'jenis_transaksi_customer_nama'     => $customer_name,
                            'jenis_transaksi_customer_phone'    => $customer_phone,
                            'rental_invoice'                    => $rental_invoice,
                        );
                        $this->global_model->insert('jenis_transaksi',$ins_jenis_transaksi);
                    } else {
                        $upd_jenis_transaksi = array(
                            'jenis_transaksi'             => $jenis_transaksi[$index],
                            'jenis_transaksi_nominal'     => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal[$index]),
                            'jenis_transaksi_note'        => htmlentities($jenis_transaksi_note[$index]),
                            'jenis_transaksi_flag'        => $jenis_transaksi_flag[$index],
                            'jenis_transaksi_modified'    => date("Y-m-d H:i:s"),
                            'jenis_transaksi_qty'         => $total_qty_product,
                            'jenis_transaksi_product'     => $firstProduct,
                            'jenis_transaksi_status'      => $rental_payment_status,
                            'jenis_transaksi_customer_nama'     => $customer_name,
                            'jenis_transaksi_customer_phone'    => $customer_phone
                        );
                        $this->global_model->update('jenis_transaksi',$upd_jenis_transaksi,array('jenis_transaksi_id' => $value));
                    }
                }
            } else {
                if(!empty($check_db_jenistransaksi)){
                    foreach($check_db_jenistransaksi as $index => $value){
                        $this->global_model->delete('jenis_transaksi',array('jenis_transaksi_id' => $value['jenis_transaksi_id']));
                    }
                }
            }

            if(is_array($rental_dendapayment_id)){
                $get_rental_dendapayment_id            = array();
                foreach($check_db_extrapayment as $index => $value){
                    $get_rental_dendapayment_id[]           = $value['rental_extrapayment_id'];
                }
                foreach($get_rental_dendapayment_id as $key => $row){
                    if(!in_array($row,$rental_dendapayment_id)){
                        $this->global_model->delete('rental_extrapayment',array("rental_extrapayment_id" => $row));
                    }
                }
                foreach($rental_dendapayment_id as $index => $value){
                    if($value == "" || empty($value)){
                        $ins_payment = array(
                            'rental_order_id'           => $rental_order_id,
                            'rental_extrapayment'       => preg_replace("/[^0-9\.]/","",$rental_dendapayment[$index]),
                            'rental_extranote'          => htmlentities($rental_dendanote[$index]),
                            'rental_extrapayment_flag'  => $rental_dendapayment_flag[$index],
                            'rental_extra_created'      => date("Y-m-d H:i:s"),
                            'rental_extra_modified'     => NULL
                        );
                        $this->global_model->insert('rental_extrapayment',$ins_payment);
                    } else {
                        $upd_payment = array(
                            'rental_order_id'           => $rental_order_id,
                            'rental_extrapayment'       => preg_replace("/[^0-9\.]/","",$rental_dendapayment[$index]),
                            'rental_extranote'          => htmlentities($rental_dendanote[$index]),
                            'rental_extrapayment_flag'  => $rental_dendapayment_flag[$index],
                            'rental_extra_modified'     => date("Y-m-d H:i:s")
                        );
                        $this->global_model->update('rental_extrapayment',$upd_payment,array('rental_extrapayment_id' => $value));
                    }
                }
            } else {
                if(!empty($check_db_dendapayment)){
                    foreach($check_db_dendapayment as $index => $value){
                        $this->global_model->delete('rental_extrapayment',array('rental_extrapayment_id' => $value['rental_extrapayment_id']));
                    }
                    $this->global_model->update('rental_order',array('rental_total_extrapayment' => 0),array('rental_order_id' => $rental_order_id));
                }
            }
            $result = array(
                'ctr'         => 'invkembali/',//Controller
                'printid'     => $rental_order_id,//rental order id
                'post'        => $_POST,
                'flag'        => true,
                );
            $this->session->set_flashdata('success','Save success');
        } else {
            $result = array(
                'flag'        => false,
                );
            $this->session->set_flashdata('validation','Something Wrong Please Try Again');
        }
        echo json_encode($result);
    }

    public function save(){
        $result = array(
            'flag'        => false,
            );
        if(isset($_POST) && is_array($_POST)){
            $customer_id                 = $this->input->post('customer_id');
            if(empty($customer_id)){
                $customer_id = 0;
            }
            $customer_name               = $this->input->post('customer_name');
            $customer_phone              = $this->input->post('customer_phone');
            $customer_address            = $this->input->post('customer_address');
            $customer_email              = $this->input->post('customer_email');
            $rental_end_date             = $this->input->post('rental_end_date');
            $rental_start_date           = $this->input->post('rental_start_date');
            $rental_total_deposit        = $this->input->post('rental_total_deposit');
            $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
            $rental_total                = $this->input->post('rental_total');
            $rental_note                 = $this->input->post('rental_note');
            $rental_konfirmasi_booking   = $this->input->post('rental_konfirmasi_booking');
            //$rental_terima_uangsewa      = $this->input->post('rental_terima_uangsewa');
            $rental_terima_kostum        = $this->input->post('rental_terima_kostum');
            //$rental_terima_uangdeposit   = $this->input->post('rental_terima_uangdeposit');

            $product_id                  = $this->input->post('product_id');
            $store_location_category_id  = $this->input->post('store_location_category_id');
            $rental_product_sizestock_id = $this->input->post('rental_product_sizestock_id');
            $rental_product_nama         = $this->input->post('rental_product_nama');
            $rental_product_isipaket     = $this->input->post('rental_product_isipaket');
            $rental_product_kode         = $this->input->post('rental_product_kode');
            $rental_product_size         = $this->input->post('rental_product_size');
            $rental_product_qty          = $this->input->post('rental_product_qty');
            $rental_product_hargasewa    = $this->input->post('rental_product_hargasewa');
            $rental_product_deposit      = $this->input->post('rental_product_deposit');
            $rental_total_deposit        = $this->input->post('rental_total_deposit');
            $rental_total_hargasewa      = $this->input->post('rental_total_hargasewa');
            $rental_total                = $this->input->post('rental_total');
            $save                        = $this->input->post('save');
            $rental_payment_status       = $this->input->post('rental_payment_status');
            $rental_status               = $this->input->post('rental_status');
            $check_phone_number          = $this->global_model->select_where('customer',array('customer_phone' => $customer_phone));

            $store_location_category_id  = $this->input->post('store_location_category_id');

            $rental_extrapayment_id      = $this->input->post('rental_extrapayment_id');
            $rental_extrapayment_flag    = $this->input->post('rental_extrapayment_flag');
            $rental_extranote            = $this->input->post('rental_extranote');
            $rental_extrapayment         = $this->input->post('rental_extrapayment');
            $rental_total_extrapayment   = $this->input->post('rental_total_extrapayment');
            $jenis_transaksi_id          = $this->input->post('jenis_transaksi_id');
            $jenis_transaksi             = $this->input->post('jenis_transaksi');
            $jenis_transaksi_flag        = $this->input->post('jenis_transaksi_flag');
            $jenis_transaksi_note        = $this->input->post('jenis_transaksi_note');
            $jenis_transaksi_nominal     = $this->input->post('jenis_transaksi_nominal');

            //Ndung Start
            $delivery_option             = $this->input->post('selectDelivery');
            if($delivery_option == 'Diambil sendiri'){
                $delivery_option = 'sendiri';
            } else if($delivery_option == 'Gojek'){
                $delivery_option = 'gojek';
            } else if($delivery_option == 'JNE'){
                $delivery_option = 'jne';
            }

            $query_invoice_number = $this->backend_model->_get_last_invoice_number($store_location_category_id);

            if(isset($query_invoice_number['category']) && !empty($query_invoice_number['category'])){

                $rental_invoice = $query_invoice_number['category'][0]['category_value_text'].'-1';

            } else {

                $last_invoice = explode('-',$query_invoice_number);
                $n = 1;
                $i = 0;
                while ($i <= $last_invoice[1]) { 
                    $id = sprintf("%01d", $n);
                    $i++; $n++;
                }
                $rental_invoice = $last_invoice[0].'-'. $id;

            }

            if(!empty($check_phone_number)){
                foreach($check_phone_number as $index => $value){
                    $update_customer = array(
                        'customer_name'     => $customer_name,
                        'customer_phone'    => $customer_phone,
                        'customer_email'    => $customer_email,
                        'customer_address'  => htmlentities($customer_address),
                        'customer_modified' => date("Y-m-d H:i:s"),
                        );
                    $this->global_model->update('customer',$update_customer,array('customer_id'=> $value['customer_id']));
                }
            } else {
                $insert_customer = array(
                    'customer_name'     => $customer_name,
                    'customer_phone'    => $customer_phone,
                    'customer_address'  => htmlentities($customer_address),
                    'customer_email'    => $customer_email,
                    'customer_created'  => date("Y-m-d H:i:s"),
                    'customer_modified' => NULL
                    );
                $this->global_model->insert('customer',$insert_customer);
                $customer_id = $this->db->insert_id();
            }
            $current_date           = date('j F Y');
            /*$rental_payment_status  = 'unpaid';*/

            /*
            $rental_status          = 'booked';
            if($current_date == $rental_start_date){
                $rental_status      = 'pickup';
            } elseif($current_date < $rental_start_date) {
                $rental_status      = 'booked';
            }*/


            /*if($save == 1 && $current_date < $rental_start_date){
                $rental_payment_status = 'unpaid';
            } else {
                $rental_payment_status = 'paid';
            }
*/
            $store_location_name    = '';
            $store_location_db      = $this->global_model->select_where('category',array('category_id' => $store_location_category_id));
            if(!empty($store_location_db)){
                $store_location_name = $store_location_db[0]['category_name'];
            }

            $insert_rental_order = array(
                'rental_invoice'            => $rental_invoice,
                'store_location_category_id'=> $store_location_category_id,
                'store_location'            => $store_location_name,
                'customer_id'               => $customer_id,
                'customer_name'             => $customer_name,
                'customer_phone'            => $customer_phone,
                'customer_email'            => $customer_email,
                'customer_address'          => htmlentities($customer_address),
                'rental_start_date'         => date('Y-m-d H:i:s',strtotime($rental_start_date)),
                'rental_end_date'           => date('Y-m-d H:i:s',strtotime($rental_end_date)),
                'rental_total_deposit'      => $rental_total_deposit,
                'rental_total_hargasewa'    => $rental_total_hargasewa,
                'rental_total'              => $rental_total,
                'rental_payment_status'     => $rental_payment_status,
                'rental_note'               => htmlentities($rental_note),
                'rental_status'             => $rental_status,
                'rental_konfirmasi_booking' => $rental_konfirmasi_booking,
                'rental_terima_uangsewa'    => '',//$rental_terima_uangsewa,
                'rental_terima_kostum'      => $rental_terima_kostum,
                'rental_terima_uangdeposit' => '',//$rental_terima_uangdeposit,
                'rental_total_extrapayment' => $rental_total_extrapayment,
                'rental_created'            => date("Y-m-d H:i:s"),
                'rental_modified'           => NULL,
                'rental_active'             => 1,

                //Ndung
                'delivery_option'           => $delivery_option
                );
            $this->global_model->insert('rental_order',$insert_rental_order);
            $inserted_order_id = $this->db->insert_id();
            
            // start jenis transaksi 
            $total_qty_product = 0;
            $firstProduct      = '';
            if(isset($rental_product_nama[0])){
                $firstProduct = $rental_product_nama[0];
            }
            // end jenis transaksi

            if(isset($rental_product_sizestock_id) && is_array($rental_product_sizestock_id)){
                $get_sum_product_qty = array();
                foreach($rental_product_sizestock_id as $index => $value){
                    if(isset($rental_product_qty[$index])){
                        $total_qty_product+=$rental_product_qty[$index];
                    }
                    @$get_sum_product_qty[$product_id[$index]]+=$rental_product_qty[$index];
                    $insert_rental_product = array(
                        'rental_order_id'               => $inserted_order_id,
                        'product_id'                    => $product_id[$index],
                        'rental_product_sizestock_id'   => $value,
                        'rental_product_nama'           => $rental_product_nama[$index],
                        'rental_product_qty'            => $rental_product_qty[$index],
                        'rental_product_hargasewa'      => preg_replace("/[^0-9\.]/","",$rental_product_hargasewa[$index]),
                        'rental_product_deposit'        => preg_replace("/[^0-9\.]/","",$rental_product_deposit[$index]),
                        'rental_product_kode'           => $rental_product_kode[$index],
                        'rental_product_isipaket'       => htmlentities($rental_product_isipaket[$index]),
                        'rental_product_size'           => $rental_product_size[$index]
                        );
                    $this->global_model->insert('rental_product',$insert_rental_product);
                }
                $get_product_popularity = $this->backend_model->get_product_popularity($inserted_order_id);
                if(!empty($get_product_popularity)){
                    foreach($get_product_popularity as $key => $row){
                        if(isset($get_sum_product_qty[$row['product_id']])){
                            $update_rented     = array(
                                'rented'  => $row['rented'] + $get_sum_product_qty[$row['product_id']]
                            );
                        $this->global_model->update('product_popularity',$update_rented,array('product_id' => $row['product_id']));
                        }
                    }
                }
            }
            if(isset($rental_extrapayment_id) && !empty($rental_extrapayment_id)){
                foreach($rental_extrapayment_id as $index => $value){
                    $ins_payment = array(
                        'rental_order_id'           => $inserted_order_id,
                        'rental_extrapayment'       => preg_replace("/[^0-9\.]/","",$rental_extrapayment[$index]),
                        'rental_extranote'          => htmlentities($rental_extranote[$index]),
                        'rental_extrapayment_flag'  => $rental_extrapayment_flag[$index],
                        'rental_extra_created'      => date("Y-m-d H:i:s"),
                        'rental_extra_modified'     => NULL
                    );
                    $this->global_model->insert('rental_extrapayment',$ins_payment);
                }
            }
            if(isset($jenis_transaksi_id) && !empty($jenis_transaksi_id)){
                foreach($jenis_transaksi_id as $index => $value){
                    $ins_jenis_transaksi = array(
                        'rental_order_id'                   => $inserted_order_id,
                        'jenis_transaksi'                   => $jenis_transaksi[$index],
                        'jenis_transaksi_nominal'           => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal[$index]),
                        'jenis_transaksi_note'              => htmlentities($jenis_transaksi_note[$index]),
                        'jenis_transaksi_flag'              => $jenis_transaksi_flag[$index],
                        'jenis_transaksi_created'           => date("Y-m-d H:i:s"),
                        'jenis_transaksi_modified'          => date("Y-m-d H:i:s"),
                        'rental_invoice'                    => $rental_invoice,
                        'jenis_transaksi_qty'               => $total_qty_product,
                        'jenis_transaksi_product'           => $firstProduct,
                        'jenis_transaksi_status'            => $rental_payment_status,
                        'jenis_transaksi_customer_nama'     => $customer_name,
                        'jenis_transaksi_customer_phone'    => $customer_phone
                    );
                    $this->global_model->insert('jenis_transaksi',$ins_jenis_transaksi);
                }
            }
            $result = array(
                'ctr'         => 'invpinjam/',
                'printid'     => $inserted_order_id,
                'flag'        => true,
                'flag_button' => $save,
                'post'        => $_POST,
                );
            $this->session->set_flashdata('success','Save success');
        } else {
            $result = array(
                'flag'        => false,
                );
            $this->session->set_flashdata('validation','Something Wrong Please Try Again');
        }
        echo json_encode($result);
    }

    public function get_customer(){
        $customer_id = $this->input->post('customer_id');
        $query       = $this->global_model->select_where('customer',array('customer_id' => $customer_id));
        if(!empty($query)){
            $dataquery = array();
            foreach($query as $index => $value){
                $dataquery = array(
                    'customer_name'     => $value['customer_name'],
                    'customer_phone'    => $value['customer_phone'],
                    'customer_address'  => htmlentities($value['customer_address']),
                    'customer_email'    => $value['customer_email'] 
                    );
            }
            $result = array(
                'flag' => true,
                'data' => $dataquery
                );
        } else {
            $result = array(
                'flag' => false
                );
        }
        echo json_encode($result);
    }

    public function get_product(){
        $id             = $this->input->post('id');
        $select         = 'product.product_id,product_nama,product_kode,category.category_id';
        $where          = array('product_category_detil.category_id' => $id);
        $join = array(
            'product_category_detil'    => 'product.product_id = product_category_detil.product_id',
            'category'                  => 'product_category_detil.category_id = category.category_id'
            );
        $order = array(
            'product_nama'          => 'asc'
            );
        $product = $this->backend_model->get_join_by_id('product',$where,$select,$join,$order,'left');
        $template   = '';
        $result     = array();
        if(!empty($product)){
/*            $template .= '<option value="0">Select Size</option>';
            foreach($sizestock as $index => $value){
                $template .= '<option value="'.$value['product_sizestock_id'].'">'.$value['product_size'].'</option>';
            }*/
            $product_result = array();
            $template .= '<option value="0">Select Product</option>';
            foreach($product->result_array() as $index => $value){
                $template .= '<option value="'.$value['product_id'].'">'.$value['product_nama'].' / '.$value['product_kode'].'</option>';
            }

            $result = array(     
                'template'      => $template,
                'flag'          => true,
                );
        } else {
            $result = array(
                'message'   => 'Nothing found data.',
                'flag'      => false
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
            $template .= '<option value="0">Select Size</option>';
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

    /*public function get_sizestock_detail(){
        $product_id = $this->input->post('product_id');
        $id = $this->input->post('id');
        $select      = 'product_nama,product_hargasewa,product_deposit,product_stock,product_sizestock_id,product_size';
        $join_array = array(
            'product'         => 'product.product_id = product_sizestock.product_id',
            );
        $where = array(
            'product_sizestock.product_id'                             => $product_id,
            'product_sizestock.product_sizestock_id'                   => $id
            );
        $product              = $this->backend_model->get_join_by_id('product_sizestock',$where,$select,$join_array,'')->result_array();

        $select      = 'rental_product.rental_order_id,rental_product_sizestock_id,rental_product_qty';

        $join_array = array(
            'rental_order'    => 'rental_order.rental_order_id = rental_product.rental_order_id'
            );

        $where_status = array(
            'product_id'                  => $product_id,
            'rental_product_sizestock_id' => $id,
            'rental_active'               => 1
            );
        $rental_product       = $this->backend_model->get_join_by_id('rental_product',$where_status,$select,$join_array,'')->result_array();
        $return_order         = $this->global_model->select('return_order');
        $day_after_return     = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));
        $current_date = date('Y-m-d');
        $result     = array();
        $template   = '';
        $i = 0;
        $available_stock = 0;
        $filter_order = array();
        $nonexist_filter_order = array();
        $rental_order_id_from_return = array();
        if(empty($rental_product)){ // DI RENTAL PRODUCT
            if(!empty($product)){
                $template .= '<div class="info">';
                foreach($product as $index => $value){
                    $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                    $template .= '<div class="info-item info-title">Size</div>';
                    $template .= '<div class="info-item info-data">'.$value['product_size'].'</div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                    $template .= '<div class="info-item info-title" style="text-align:center;">Stock</div>';

                    $template .= '<input type="hidden" id="available-sizestock" value="'.$value['product_stock'].'">';
                    $template .= '<div class="info-item info-data" style="text-align:center;">'.$value['product_stock'].' / '.$value['product_stock'].'</div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                    $template .= '<div class="info-item info-title" style="text-align:center;">Quantity</div>';
                    $template .= '<div class="info-item info-data" style="text-align:center;"><input type="text" class="stockmask form-control qty_rental" value="1"></div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-6 info-item-wrapper">';
                    $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Booking Cost Rp. '.number_format($value['product_hargasewa']).'</strong></div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-6 info-item-wrapper">';
                    $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Deposit Rp. '.number_format($value['product_deposit']).'</strong></div>';
                    $template .= '</div>';
                }
                $template .= '</div>';
            }
        } else {
            // KALAU RENTAL PRODUCT ADA, CEK PEMINJAMAN YANG LAGI DIPINJAM
            foreach($rental_product as $index => $value){

                if(!empty($return_order)){
                    foreach($return_order as $key => $row){
                        $return_date = '';
                        $before_take_date = $current_date;
                        $after_take_date = $current_date;
                        $return_date = date('Y-m-d',strtotime($row['return_date']));
                        if(!empty($day_after_return)){
                            $return_date = date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($row['return_date'])));
                            $before_take_date = date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)));
                            $after_take_date = date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)));
                        }

                        if($row['rental_order_id'] == $value['rental_order_id']){
                            $rental_order_id_from_return[] = $row['rental_order_id'];
                            $filter_order[$key] = array('rental_order_id'=>$row['rental_order_id'],'return_date' => $return_date,'before_take_date' => $before_take_date,'after_take_date' => $after_take_date,'rental_product_qty' => $value['rental_product_qty']);
                        }
                    }
                }
                if(in_array($value['rental_order_id'],$rental_order_id_from_return)){
                    if(!empty($filter_order)){
                        foreach($filter_order as $x => $val){
                            if($val['rental_order_id'] == $value['rental_order_id']){
                                if($val['return_date'] < $val['before_take_date'] || $val['after_take_date'] < $current_date){
                                    $i+=$value['rental_product_qty'];
                                }
                            }
                        }
                    }
                } else {
                    $i+=$value['rental_product_qty'];
                }
            }

            $template .= '<div class="info">';
            foreach($product as $index => $value){
                $available_stock = $value['product_stock']-$i;
                $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                $template .= '<div class="info-item info-title">Size</div>';
                $template .= '<div class="info-item info-data">'.$value['product_size'].'</div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                $template .= '<div class="info-item info-title" style="text-align:center;">Stock</div>';

                $template .= '<input type="hidden" id="available-sizestock" value="'.$available_stock.'">';
                $template .= '<div class="info-item info-data" style="text-align:center;">'.$available_stock.' / '.$value['product_stock'].'</div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                $template .= '<div class="info-item info-title" style="text-align:center;">Quantity</div>';
                $template .= '<div class="info-item info-data" style="text-align:center;"><input type="text" class="stockmask form-control qty_rental" value="1"></div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-6 info-item-wrapper">';
                $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Booking Cost Rp. '.number_format($value['product_hargasewa']).'</strong></div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-6 info-item-wrapper">';
                $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Deposit Rp. '.number_format($value['product_deposit']).'</strong></div>';
                $template .= '</div>';
            }
            $template .= '</div>';    
        }

        if(!empty($product)){
            $result = array(
                'flag'                    => true,
                'template'                => $template,
                'jumlah_qty'              => $i,
                'available_stock'         => $available_stock,
                'filter'                  => $filter_order,
                );
        } else {
            $result = array(
                'flag'      => false,
                'message'   => 'No found product size'
                );
        }

        echo json_encode($result);
    }*/

    public function get_sizestock_detail(){
        //$id = 7;
        $product_id         = $this->input->post('product_id');
        $id                 = $this->input->post('id');
        $format_date_start  = date('Y-m-d',strtotime($this->input->post('start')));
        $format_date_end    = date('Y-m-d',strtotime($this->input->post('end')));

        //$product_id = 20;
        //$id = 35;

        //$format_date_start  = '5 March 2019';
        //$format_date_end    = '8 March 2019';

        //$format_date_start  = date('Y-m-d',strtotime($format_date_start));
        //$format_date_end    = date('Y-m-d',strtotime($format_date_end));

        $select      = 'product_nama,product_hargasewa,product_deposit,product_stock,product_sizestock_id,product_size';
        $join_array = array(
            'product'         => 'product.product_id = product_sizestock.product_id',
            );
        $where = array(
            'product_sizestock.product_id'                             => $product_id,
            'product_sizestock.product_sizestock_id'                   => $id
            );
        $q_product              = $this->backend_model->get_join_by_id('product_sizestock',$where,$select,$join_array,'')->result_array();

        $select      = 'rental_product.rental_order_id,rental_product_sizestock_id,rental_product_qty,rental_order.rental_start_date,rental_order.rental_end_date,return_date';

        $join_array = array(
            'rental_order'    => 'rental_order.rental_order_id = rental_product.rental_order_id',
            'return_order'    => 'return_order.rental_order_id = rental_order.rental_order_id'
            );

        $where_status = array(
            'product_id'                  => $product_id,
            'rental_product_sizestock_id' => $id,
            'rental_active'               => 1
            );
        $rental_product       = $this->backend_model->get_join_by_id('rental_product',$where_status,$select,$join_array,'')->result_array();

        $count_stock          = 0;

        if(!empty($q_product) && (int)$q_product[0]['product_stock']){
            $count_stock = $q_product[0]['product_stock'];
        }

        if(!empty($rental_product)){

            $current_date           = date('Y-m-d');
            $day_after_return       = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

            $no = 0;
            $rental_in_return       = array();
            $rental_in_returndate   = array();

            foreach($rental_product as $index => $value){
                if((int)$day_after_return && !empty($value['return_date'])){
                    $rental_in_return[]                              = $value['rental_order_id'];
                    $rental_in_returndate[$value['rental_order_id']] = array(
                        'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                        'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                        'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_end_date'])))
                        );
                }
            }

            $product                = array();
            $product_already_return = array();
            $sum_qty = 0;

            foreach($rental_product as $index => $value){
                $value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));

                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]  = $value;
                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty']  = 0;
                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['return_qty']  = 0;
            }

            foreach($rental_product as $index => $value){
                $late_date                  = date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_end_date'])));
                $value_late_date            = strtotime($late_date);
                $return_date                = '';

                $rental_order_id            = $value['rental_order_id'];
                $value['rental_start_date'] = date('Y-m-d',strtotime($value['rental_start_date']));
                $value['rental_end_date']   = date('Y-m-d',strtotime($value['rental_end_date']));
                $value_rental_start_date    = strtotime($value['rental_start_date']);
                $value_format_date_start    = strtotime($format_date_start);
                $value_rental_end_date      = strtotime($value['rental_end_date']);
                $value_format_date_end      = strtotime($format_date_end);

                if(!in_array($value['rental_order_id'],$rental_in_return)){

                    if(!empty($value['rental_order_id'])) {

                        if($value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end &&
                            $value_late_date >= $value_format_date_start && $value_late_date <= $value_format_date_end || $value_rental_start_date <= $value_format_date_start && $value_late_date >= $value_format_date_start && $value_late_date >= $value_format_date_end || $value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end || $value_late_date >= $value_format_date_start && $value_late_date <= $value_format_date_end){

                            $quantity  = (int) $value['rental_product_qty'];
                        if(isset($count[$value['rental_product_sizestock_id']][$value['rental_order_id']])){
                            $count[$value['rental_product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                        } else {
                            $count[$value['rental_product_sizestock_id']][$value['rental_order_id']]=$quantity;
                        }

                        $sum_qty1 = 0;
                        foreach($count as $key => $row){

                            foreach($row as $k => $v){

                                if($k == $value['rental_product_sizestock_id']){
                                    $sum_qty1 = $v;
                                }
                                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                            }
                        }
                    } else {
                        $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                    }

                } else {
                    $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                }

            } elseif(in_array($value['rental_order_id'],$rental_in_return)) {

                if(!empty($value['return_date'])){
                    $return_date            = date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date'])));
                    $value_return_date      = strtotime($return_date);
                }

                if(!empty($value['rental_order_id'])) {

                    if($value_rental_start_date >= $value_format_date_start && $value_rental_start_date <= $value_format_date_end || $value_return_date >= $value_format_date_start && $value_return_date <= $value_late_date){


                        $quantity  = (int) $value['rental_product_qty'];
                        if(isset($count[$value['rental_product_sizestock_id']][$value['rental_order_id']])){
                            $count[$value['rental_product_sizestock_id']][$value['rental_order_id']]+=$quantity;
                        } else {
                            $count[$value['rental_product_sizestock_id']][$value['rental_order_id']]=$quantity;
                        }

                        $sum_qty2 = 0;
                        foreach($count as $key => $row){

                            foreach($row as $k => $v){

                                if($k == $value['rental_product_sizestock_id']){
                                    $sum_qty2 = $v;
                                }
                                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $v;
                                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['status'] = 'return';
                                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['return_qty'] = $value['rental_product_qty'];
                            }
                        }

                    }

                    if($value_return_date < $value_format_date_end) {

                        $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;

                    }

                } else {

                    $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
                }

            } else {

                $sum_qty = 0;
                $product[$value['rental_product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = 0;
            } 
        }

        $periode    = array();
        $tes_       = array();
        $tes__      = array();

        if(!empty($product)){
            foreach($product as $index => $value){

                foreach($value as $key => $row){
                    $date_periode   = $this->date_range_function($row['rental_start_date'],$row['rental_end_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');

                    if(isset($row['return_date']) && !empty($row['return_date'])){
                        $date_periode   = $this->date_range_function($row['rental_start_date'],$row['return_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');
                    }

                    if(!empty($row['rental_order_id']) && empty($row['return_date'])){
                        $periode[$row['rental_order_id']] = array(
                            'product_stock'         => $q_product[0]['product_stock'],
                            'product_order'         => $row['rental_qty'],
                            'periode'               => $date_periode,
                            'rental_product_qty'    => $row['rental_product_qty'],
                            'rental_start_date'     => date('m/d/Y',strtotime($row['rental_start_date'])),
                            //'rental_end_date'       => date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date']))),
                            'rental_end_date'		=> date('m/d/Y',strtotime($row['rental_end_date'])),
                            'return_date'           => ''
                            );
                    } else {
                        $periode[$row['rental_order_id']] = array(
                            'product_stock'         => $q_product[0]['product_stock'],
                            'product_order'         => $q_product[0]['product_stock'] - $row['return_qty'],
                            'periode'               => $date_periode,
                            'rental_product_qty'    => $row['rental_product_qty'],
                            'rental_start_date'     => date('m/d/Y',strtotime($row['rental_start_date'])),
                            //'rental_end_date'       => date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date']))),
                            'rental_end_date'		=> date('m/d/Y',strtotime($row['rental_end_date'])),
                            'return_date'           => date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date']))),
                            );
                    }
                }
            }
        }

        foreach($periode as $index => $value){

            $rental_start_date  = $value['rental_start_date'];
            $rental_end_date    = $value['rental_end_date'];
            $return_date        = '';

            foreach($value['periode'] as $key => $row){

                    $tes_[$row][]   = $value['rental_product_qty']; //hitung total sum
                    $sum_all        = array_sum($tes_[$row]);

                    if(!empty($value['return_date'])){
                        $return_date = date('m/d/Y',strtotime($value['return_date']));
                    }

                    if($row <= $return_date){
                        $tes__[$row][] = array(
                            'return_date'       => $return_date,
                            'product_stock'     => $value['product_stock'],
                            'rental_product_qty'=> $value['rental_product_qty'],
                            'rental_start_date' => $value['rental_start_date'],
                            'rental_end_date'   => $value['rental_end_date']
                            );
                    } else {

                        $tes__[$row][] = array(
                            'return_date'       => '',
                            'product_stock'     => $value['product_stock'],
                            'rental_product_qty'=> $value['rental_product_qty'],
                            'rental_start_date' => $value['rental_start_date'],
                            'rental_end_date'   => $value['rental_end_date']
                            );
                    }   

                }

            }

            $total_qty_order_per_day = array();
            $filter_per_day          = array();

            if(!empty($tes__)){
                foreach($tes__ as $index => $value){
                    $date   = date('m/d/Y',strtotime($index));
                    $sum    = 0;
                    foreach($value as $key => $row){
                        if(isset($total_qty_order_per_day[$date])){
                            $total_qty_order_per_day[$date]+=$row['rental_product_qty'];
                        } else {
                            $total_qty_order_per_day[$date]=$row['rental_product_qty'];
                        }
                    }   
                }
            }

            if(!empty($total_qty_order_per_day)){
                foreach($tes__ as $index => $value){
                    $day_date   = date('m/d/Y',strtotime($index));
                    $total_qty  = $total_qty_order_per_day[$index];
                    foreach($value as $key => $row){
                        $total_qty_order_per_day[$index]                        = $row;
                        $total_qty_order_per_day[$index]['total_qty']           = $total_qty;
                        $total_qty_order_per_day[$index]['status']              = '';
                        $total_qty_order_per_day[$index]['rental_late_date']    = '';
                    }

                    foreach($value as $key => $row){

                        // salah satu orderan telat
                        $late_date 		= date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date'])));

                        if(strtotime($late_date) <= strtotime($current_date) && empty($row['return_date'])){
                            $total_qty_order_per_day[$index]['status']              = 'late';
                            $total_qty_order_per_day[$index]['rental_late_date']    = $late_date;

                        }
                    }
                }
            }

            $count      = 0;
            $tes_late_display = '';

            if(!empty($total_qty_order_per_day)){
                $rental_product_qty_late = array();
                foreach($total_qty_order_per_day as $index => $values){

                    $stock       = $values['product_stock'];
                    $total_qty   = $values['total_qty'];

                    $late_date   = date('Y-m-d',strtotime('+1 day',strtotime($values['rental_end_date'])));

                    $count       = $stock - $total_qty;

                    if($values['status'] == 'late'){

                        $tes_late_display = 'telat';

                        // bug 2 Januari 2019 - +1 late day pada current date
                        // command wrong
                        //$tambahan   = $this->date_range_function(date('Y-m-d',strtotime($values['rental_end_date'])),date('Y-m-d',strtotime($current_date)),'m/j/Y');

                        // add new variable 
                        $tambahan   = $this->date_range_function(date('Y-m-d',strtotime('+1 day',strtotime($values['rental_late_date']))),date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day',strtotime($current_date))),'m/d/Y');

                        //rental yang telat - nanti dulu aja
                        $rental_product_qty_late = $values['rental_product_qty'];
                    }
                }

                foreach($total_qty_order_per_day as $index => $values){

                    if($values['status'] == 'late'){
                            // late date
                        if(is_array($tambahan) && !empty($tambahan)){
                            foreach($tambahan as $key => $row){ 
                                if($index == $row){
                                    $qty_tambah = $values['rental_product_qty'];

                                    if(isset($rental_product_qty_late[$row])){
                                        $qty_tambah = $total_qty + $rental_product_qty_late[$row];
                                    }

                                    $total_qty_order_per_day[$row] = array(
                                        'total_qty'         => $values['total_qty'],
                                        'product_stock'     => $stock,
                                        );
                                } else {
                                    $total_qty_order_per_day[$row] = array(
                                        'total_qty'         => $values['total_qty'],
                                        'product_stock'     => $stock,
                                        );
                                } 
                            }
                        }
                    }
                }

                $range_date = array();
                $range_date = $this->date_range_function(date('Y-m-d',strtotime('0 day',strtotime($format_date_start))),date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day',strtotime($format_date_end))),'m/d/Y');

                $current_stock = array();
                if(!empty($range_date)){
                    foreach($range_date as $index => $value){
                        if(!array_key_exists($value,$total_qty_order_per_day)){
                            $current_stock[$value] = array(
                                'product_stock' => $q_product[0]['product_stock'],
                                'total_qty'     => 0,
                                ); 
                        }
                    }

                    foreach($range_date as $index => $value){
                        foreach($total_qty_order_per_day as $key => $row){
                            if($key == $value){
                                $current_stock[$value] = array(
                                    'product_stock' => $row['product_stock'],
                                    'total_qty'     => $row['total_qty'],
                                    );
                            }
                        }
                    }
                }

                $count_stock    = array();
                if(!empty($current_stock)){
                    foreach($current_stock as $index => $values){
                        $count_qty      = $values['product_stock']-$values['total_qty'];
                        $count_stock[]  = $count_qty;
                        rsort($count_stock);
                    }
                }

                if(is_array($count_stock) && !empty($count_stock)){
                    $count_stock = $count_stock[0]; 
                }
            }
        }

        $result     = array();
        $template   = '';

        if(empty($rental_product)){ // DI RENTAL PRODUCT
            if(!empty($q_product)){
                $template .= '<div class="info">';
                foreach($q_product as $index => $value){
                    $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                    $template .= '<div class="info-item info-title">Size</div>';
                    $template .= '<div class="info-item info-data">'.$value['product_size'].'</div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                    $template .= '<div class="info-item info-title" style="text-align:center;">Stock</div>';

                    $template .= '<input type="hidden" id="available-sizestock" value="'.$value['product_stock'].'">';
                    $template .= '<div class="info-item info-data" style="text-align:center;">'.$value['product_stock'].' / '.$value['product_stock'].'</div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                    $template .= '<div class="info-item info-title" style="text-align:center;">Quantity</div>';
                    $template .= '<div class="info-item info-data" style="text-align:center;"><input type="text" class="stockmask form-control qty_rental" value="1"></div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-6 info-item-wrapper">';
                    $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Booking Cost Rp. '.number_format($value['product_hargasewa']).'</strong></div>';
                    $template .= '</div>';
                    $template .= '<div class="col-lg-6 info-item-wrapper">';
                    $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Deposit Rp. '.number_format($value['product_deposit']).'</strong></div>';
                    $template .= '</div>';
                }
                $template .= '</div>';
            }
        } else {

            $template .= '<div class="info">';
            foreach($q_product as $index => $value){
                $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                $template .= '<div class="info-item info-title">Size</div>';
                $template .= '<div class="info-item info-data">'.$value['product_size'].'</div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                $template .= '<div class="info-item info-title" style="text-align:center;">Stock</div>';

                $template .= '<input type="hidden" id="available-sizestock" value="'.$count_stock.'">';
                $template .= '<div class="info-item info-data" style="text-align:center;">'.$count_stock.' / '.$value['product_stock'].'</div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-4 col-md-4 col-sm-12 info-item-wrapper">';
                $template .= '<div class="info-item info-title" style="text-align:center;">Quantity</div>';
                $template .= '<div class="info-item info-data" style="text-align:center;"><input type="text" class="stockmask form-control qty_rental" value="1"></div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-6 info-item-wrapper">';
                $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Booking Cost Rp. '.number_format($value['product_hargasewa']).'</strong></div>';
                $template .= '</div>';
                $template .= '<div class="col-lg-6 info-item-wrapper">';
                $template .= '<div class="info-item info-data" style="text-align:center;"><strong>Deposit Rp. '.number_format($value['product_deposit']).'</strong></div>';
                $template .= '</div>';
            }
            $template .= '</div>';    
        }

        if(!empty($q_product)){
            $result = array(
                'flag'                    => true,
                'template'                => $template,
                'available_stock'         => $count_stock,
                );
        } else {
            $result = array(
                'flag'      => false,
                'message'   => 'No found product size'
                );
        }

        echo json_encode($result);
    }
    
    public function add_item_product(){
        $product_id             = $this->input->post('product_id');
        $qty                    = $this->input->post('qty');
        $product_sizestock_id   = $this->input->post('product_sizestock_id');

        $select      = 'product_sizestock.product_id,product_nama,product_hargasewa,product_deposit,product_stock,product_sizestock_id,product_size,product_isipaket,product_kode';
        $join_array = array(
            'product'         => 'product.product_id = product_sizestock.product_id',
            );
        $where = array(
            'product_sizestock.product_sizestock_id' => $product_sizestock_id
            );
        $product              = $this->backend_model->get_join_by_id('product_sizestock',$where,$select,$join_array,'')->result_array();

        $select      = 'rental_product.rental_order_id,rental_product_sizestock_id,rental_product_qty';

        $join_array = array(
            //'rental_order'    => 'rental_order.rental_order_id = return_order.rental_order_id',
            'rental_product'  => 'rental_order.rental_order_id = rental_product.rental_order_id'
            );

        $where_status = array(
            'product_id'                  => $product_id,
            'rental_product_sizestock_id' => $product_sizestock_id
            );
        $rental_product       = $this->backend_model->get_join_by_id('rental_order',$where_status,$select,$join_array,'')->result_array();
        $return_order         = $this->global_model->select('return_order');

        $day_after_return     = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));

        $template       = '';

        $current_date   = date('Y-m-d');
        $result         = array();
        $i              = 0;
        $available_stock = 0;
        $filter_order = array();
        $nonexist_filter_order = array();
        $rental_order_id_from_return = array();
        
        if(empty($rental_product)){
            if(!empty($product)){
                foreach($product as $index => $value){
                    $available_stock = $value['product_stock'];
                    $i               = $value['product_stock'];
                    $count_hargasewa = $qty * $value['product_hargasewa'];
                    $count_deposit   = $qty * $value['product_deposit'];

                    $template .= '<tr>';
                    $template .= '<td style="text-align: center;">
                    <input type="hidden" name="rental_product_id[]">
                    <input type="hidden" name="product_id[]" value="'.$value['product_id'].'">
                    <input type="hidden" name="rental_product_size[]" value="'.$value['product_size'].'">
                    <input type="hidden" name="rental_product_nama[]" value="'.$value['product_nama'].'">
                    <input type="hidden" name="rental_product_isipaket[]" value="'.$value['product_isipaket'].'">
                    <input type="hidden" name="rental_product_kode[]" value="'.$value['product_kode'].'">
                    <input type="hidden" name="rental_product_sizestock_id[]" value="'.$value['product_sizestock_id'].'">
                    <button data-hargasewa="'.$count_hargasewa.'" data-deposit="'.$count_deposit.'" class="remove-rental-product btn btn-danger btn-flat btn-xs">Remove</button></td>';
                    $template .= '<td style="text-align:center;"><input type="hidden" name="rental_product_qty[]" value="'.$qty.'">'.$qty.'</td>';
                    $template .= '<td style="text-align:left;"><span>'.$value['product_nama'].'</span><span>'.$value['product_size'].'</span></td>';
                    $template .= '<td style="text-align: right;"><input type="hidden" name="rental_product_hargasewa[]" value="'.$count_hargasewa.'">Rp. '.number_format($count_hargasewa).'</td>';
                    $template .= '<td style="text-align: right;"><input type="hidden" name="rental_product_deposit[]" value="'.$count_deposit.'">Rp. '.number_format($count_deposit).'</td>';
                    $template .= '</tr>';
                }
            }
        } else {
            /*
            // KALAU RENTAL PRODUCT ADA, CEK PEMINJAMAN YANG LAGI DIPINJAM
            foreach($rental_product as $index => $value){

                if(!empty($return_order)){
                    foreach($return_order as $key => $row){
                        $return_date = '';
                        $before_take_date = $current_date;
                        $after_take_date = $current_date;
                        $return_date = date('Y-m-d',strtotime($row['return_date']));
                        if(!empty($day_after_return)){
                            $return_date = date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($row['return_date'])));
                            $before_take_date = date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)));
                            $after_take_date = date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($current_date)));
                        }

                        if($row['rental_order_id'] == $value['rental_order_id']){
                            $rental_order_id_from_return[] = $row['rental_order_id'];
                            $filter_order[$key] = array('rental_order_id'=>$row['rental_order_id'],'return_date' => $return_date,'before_take_date' => $before_take_date,'after_take_date' => $after_take_date,'rental_product_qty' => $value['rental_product_qty']);
                        }
                    }
                }
                if(in_array($value['rental_order_id'],$rental_order_id_from_return)){
                    if(!empty($filter_order)){
                        foreach($filter_order as $x => $val){
                            if($val['rental_order_id'] == $value['rental_order_id']){
                                //if($val['return_date'] < $val['before_take_date'] || $val['return_date'] > $current_date){
                                if($val['return_date'] < $val['before_take_date'] || $val['after_take_date'] < $current_date){
                                    $i+=$value['rental_product_qty'];
                                }
                            }
                        }
                    }
                } else {
                    $i+=$value['rental_product_qty'];
                }
            }
            */
            foreach($product as $index => $value){
                $available_stock = $value['product_stock'] - $i;
                $count_hargasewa = $qty * $value['product_hargasewa'];
                $count_deposit   = $qty * $value['product_deposit'];

                $template .= '<tr>';
                $template .= '<td style="text-align: center;"><input type="hidden" name="rental_product_id[]"><input type="hidden" name="product_id[]" value="'.$value['product_id'].'"><input type="hidden" name="rental_product_size[]" value="'.$value['product_size'].'"><input type="hidden" name="rental_product_nama[]" value="'.$value['product_nama'].'"><input type="hidden" name="rental_product_isipaket[]" value="'.$value['product_isipaket'].'"><input type="hidden" name="rental_product_kode[]" value="'.$value['product_kode'].'"><input type="hidden" name="rental_product_sizestock_id[]" value="'.$value['product_sizestock_id'].'"><button data-hargasewa="'.$count_hargasewa.'" data-deposit="'.$count_deposit.'" class="remove-rental-product btn btn-danger btn-flat btn-xs">Remove</button></td>';
                $template .= '<td style="text-align:center;"><input type="hidden" name="rental_product_qty[]" value="'.$qty.'">'.$qty.'</td>';
                $template .= '<td style="text-align:left;"><span>'.$value['product_nama'].'</span><span>'.$value['product_size'].'</span></td>';
                $template .= '<td style="text-align: right;"><input type="hidden" name="rental_product_hargasewa[]" value="'.$count_hargasewa.'">Rp. '.number_format($count_hargasewa).'</td>';
                $template .= '<td style="text-align: right;"><input type="hidden" name="rental_product_deposit[]" value="'.$count_deposit.'">Rp. '.number_format($count_deposit).'</td>';
                $template .= '</tr>';
            }  
        }

        /*if($available_stock == 0 || $available_stock < 0){
            $result = array(
                'flag'      => false,
                'message'   => 'Product stock unavailable'
                );
        } else*/ // VALIDATION STOCK

        if(empty($product)) {
            $result = array(
                'flag'      => false,
                'message'   => 'No found product size'
                );
        /*} elseif($qty > $available_stock){
            $result = array(
                'flag'      => false,
                'message'   => 'Max '.$available_stock.' Quantity'
                );*/ // VALIDATION STOCK
            } else {
                $result = array(
                    'flag'      => true,
                    'template'  => $template,
                    'stock'     => $i,
                    'available_stock' => $available_stock
                    );
            }

            echo json_encode($result);

        }

        public function view_trash(){
            $data['session_items']          = $this->start_session;
            $data['title']                = 'Rental Order';
            $config = array(
                array(
                    'field' => 'search',
                    'label' => 'Search',
                    'rules' => 'trim'
                    ),
                );
            $this->form_validation->set_rules($config);
            $data['geturl']               = $this->input->get(null,true);
        $data['table_data']           = 'rental-order'; // element id table
        $data['ajax_sort_url']        = 'adminsite/rental_order/order'; // Controller row order data
        $data['ajax_data_table']      = 'adminsite/rental_order/datatables_trash_new'; //Controller ajax data
        $data['count_order']        = $this->backend_model->order_active();
        $data['count_order_trash']  = $this->backend_model->order_active(0);
        $data['datatables_ajax_data'] = array(
            $this->custom_lib->datatables_ajax_serverside(TRUE,$data['table_data'],$data['ajax_data_table'],'','','','rental_order')
            );
        //View
        $data['load_view'] = 'adminsite/v_rental_order';
        $this->load->view('adminsite/template/backend', $data);

    }

    public function update_status(){
        $id         = $this->input->post('id');
        $split      = explode("-",$id);
        $status     = '';
        $order_id   = '';

        $result = array(
            'flag'      => false,
            'message'   => 'Nothing Change Data'
            );

        if(is_array($split) && !empty($split)){
            $status     = $split[0];
            $order_id   = $split[1];
        }
        $data = array();
        if(!empty($status) && !empty($order_id)){
            $data = array(
                'rental_status' => $status
                );
            $updated = $this->global_model->update('rental_order',$data,array('rental_order_id' => $order_id));
            if($updated){
                $result = array(
                    'flag'      => true,
                    );
            }
        }
        echo json_encode($result);
    }

    public function trash(){
        $now = strtotime(date('Y-m-d'));
        $id = $this->input->post('id');
        $get_rental_product = $this->backend_model->get_rental_product_to_trash($id);
        if(!empty($get_rental_product)){
            foreach($get_rental_product as $index => $value){
                $update = array(
                   //'rented_in_trash' => $value['rented_in_trash'] + $value['rental_product_qty'] 
                   'rented' => $value['rented'] - $value['rental_product_qty'] 
                );
                $this->global_model->update('product_popularity',$update,array('product_id' => $value['product_id']));
            }
        }
        $update = array(
            'rental_active' => 0
            );
        $trash = $this->global_model->update('rental_order',$update,array('rental_order_id' => $id));
        $cekDateJenisTransaksi = $this->backend_model->getCreatedJenisTransaksi($id);
        if(!empty($cekDateJenisTransaksi)){
            if(strtotime($cekDateJenisTransaksi->jenis_transaksi_created) >= $now){   
                $update_jenis_active = array(
                    'jenis_transaksi_active' => 0
                );
                $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id));
            } else {
                $update_jenis_active = array(
                    'jenis_transaksi_active' => 1
                );
                $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id));
            }
        }
        if($trash){
            $return = array('flag'=>true);
        } else {
            $return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');
        }
        echo json_encode($return);
    }

    public function restore(){
        $id = $this->input->post('id');
        $get_rental_product = $this->backend_model->get_rental_product_to_restore($id);
        if(!empty($get_rental_product)){
            foreach($get_rental_product as $index => $value){
                $update = array(
                   //'rented_in_trash' => $value['rented_in_trash'] - $value['rental_product_qty'] 
                   'rented' => $value['rented'] + $value['rental_product_qty'] 
                );
                $this->global_model->update('product_popularity',$update,array('product_id' => $value['product_id']));
            }
        }
        $update = array(
            'rental_active' => 1
            );
        $trash = $this->global_model->update('rental_order',$update,array('rental_order_id' => $id));
        $update_jenis_active = array(
            'jenis_transaksi_active' => 1
        );
        $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id));
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
                $get_rental_product = $this->backend_model->get_rental_product_to_restore($id[$i]);
                if(!empty($get_rental_product)){
                    foreach($get_rental_product as $index => $value){
                        $update = array(
                            //'rented_in_trash' => $value['rented_in_trash'] - $value['rental_product_qty'] 
                            'rented' => $value['rented'] + $value['rental_product_qty'] 
                        );
                        $this->global_model->update('product_popularity',$update,array('product_id' => $value['product_id']));
                    }
                }
                $update = array(
                    'rental_active' => 1
                    );

                $delete = $this->global_model->update('rental_order',$update,array('rental_order_id' => $id[$i]));
                $update_jenis_active = array(
                    'jenis_transaksi_active' => 1
                );
                $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id[$i]));
            }
            $update = true;
        }
        echo json_encode($update);
    }

    public function multiple_trash(){
        $now = strtotime(date('Y-m-d'));
        $id = $this->input->post('id',true);
        $update = false;

        if(is_array($id) && !empty($id)){

            for($i=0; $i<count($id); $i++){
                $get_rental_product = $this->backend_model->get_rental_product_to_trash($id[$i]);
                if(!empty($get_rental_product)){
                    foreach($get_rental_product as $index => $value){
                        $update = array(
                            //'rented_in_trash' => $value['rented_in_trash'] + $value['rental_product_qty'] 
                            'rented' => $value['rented'] - $value['rental_product_qty'] 
                        );
                        $this->global_model->update('product_popularity',$update,array('product_id' => $value['product_id']));
                    }
                }
                $update_active = array(
                    'rental_active' => 0
                );
                $cekDateJenisTransaksi = $this->backend_model->getCreatedJenisTransaksi($id[$i]);
                if(!empty($cekDateJenisTransaksi)){
                    if(strtotime($cekDateJenisTransaksi->jenis_transaksi_created) >= $now){   
                        $update_jenis_active = array(
                            'jenis_transaksi_active' => 0
                        );
                        $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id[$i]));
                    } else {
                        $update_jenis_active = array(
                            'jenis_transaksi_active' => 1
                        );
                        $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id[$i]));
                    }
                }
            }
            $update = true;
        }
        echo json_encode($update);
    }

    public function delete(){
        $now = strtotime(date('Y-m-d'));
        $id = $this->input->post('category_id');
        $check_active   = $this->backend_model->get_check_rental_status_active($id);
        if($check_active){
            $get_popularity = $this->backend_model->get_rental_product_to_trash($id);
            if(!empty($get_popularity)){
                foreach($get_popularity as $index => $value){
                    $update = array(
                        //'rented_in_trash' => $value['rented_in_trash'] - $value['rental_product_qty'] 
                        'rented' => $value['rented'] - $value['rental_product_qty'] 
                    );
                    $this->global_model->update('product_popularity',$update,array('product_id' => $value['product_id']));
                }
            }
        }
        $delete                 = $this->global_model->delete('rental_order',array('rental_order_id' => $id));
        $delete_product         = $this->global_model->delete('rental_product',array('rental_order_id' => $id));
        $delete_return          = $this->global_model->delete('return_order',array('rental_order_id' => $id));
        $delete_extrapayment    = $this->global_model->delete('rental_extrapayment',array('rental_order_id' => $id));
        $cekDateJenisTransaksi = $this->backend_model->getCreatedJenisTransaksi($id);
                if(!empty($cekDateJenisTransaksi)){
                    if(strtotime($cekDateJenisTransaksi->jenis_transaksi_created) >= $now){   
                        $this->global_model->delete('jenis_transaksi',array('rental_order_id' => $id));
                    } else {
                        $update_jenis_active = array(
                            'jenis_transaksi_active' => 1
                        );
                        $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id));
                    }
                }
        if($delete_product){
            $return = array('flag'=>true);
        } else {
            $return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');
        }

        echo json_encode($return);

    }

    public function multiple_delete(){
        $now = strtotime(date('Y-m-d'));
        $id = $this->input->post('id');
        $update = false;

        if(is_array($id) && !empty($id)){

            for($i=0; $i<count($id); $i++){
                $check_active   = $this->backend_model->get_check_rental_status_active($id[$i]);
                if($check_active){
                    $get_popularity = $this->backend_model->get_rental_product_to_trash($id[$i]);
                    if(!empty($get_popularity)){
                        foreach($get_popularity as $index => $value){
                            $update = array(
                                //'rented_in_trash' => $value['rented_in_trash'] - $value['rental_product_qty'] 
                                'rented' => $value['rented'] - $value['rental_product_qty'] 
                            );
                            $this->global_model->update('product_popularity',$update,array('product_id' => $value['product_id']));
                        }
                    }
                }
                $delete                 = $this->global_model->delete('rental_order',array('rental_order_id' => $id[$i]));
                $delete_product         = $this->global_model->delete('rental_product',array('rental_order_id' => $id[$i]));
                $delete_return          = $this->global_model->delete('return_order',array('rental_order_id' => $id[$i]));
                $delete_extrapayment    = $this->global_model->delete('rental_extrapayment',array('rental_order_id' => $id[$i]));
                $cekDateJenisTransaksi = $this->backend_model->getCreatedJenisTransaksi($id[$i]);
                if(!empty($cekDateJenisTransaksi)){
                    if(strtotime($cekDateJenisTransaksi->jenis_transaksi_created) >= $now){   
                        $this->global_model->delete('jenis_transaksi',array('rental_order_id' => $id[$i]));                    
                        } else {
                            $update_jenis_active = array(
                                'jenis_transaksi_active' => 1
                            );
                        $updated_jenis_active = $this->global_model->update('jenis_transaksi',$update_jenis_active,array('rental_order_id' => $id[$i]));
                    }
                }
            }
            $update = true;
        }

        if($update){

            $return = array('flag'=>true);

        } else {

            $return = array('flag'=>false,'message' => 'Sorry, Nothing change data.');

        }

        echo json_encode($return);
    }

    function valid_store($str) 
    { 
        if ($str == 0 || !is_numeric($str)) { 
            $this->form_validation->set_message('valid_store', 'The Store Location field is required.'); 
            return false; 
        } else { 
            return true; 
        } 
    }

    function calendarproduct(){

        $id                         = $this->input->post('product_id',true);
        $product_sizestock_id       = $this->input->post('id',true);

        $return_order               = $this->global_model->select('return_order');
        $day_after_return           = $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));
        $current_date               = date('Y-m-d');
        $result                     = array();
        $template                   = '';
        $i                          = 0;
        $available_stock            = 0;
        $filter_order               = array();
        $nonexist_filter_order      = array();
        $rental_order_id_from_return= array();

        $current_date               = date('m/d/Y');
        $check_late_rental_date     = date('m/d/Y',strtotime('+1 day'));
        $data['product']            = array();
        $data['product_sizestock']  = array();
        $periode                    = array();

        $json_data                  = array();
        $query_product = $this->global_model->select_where('product',array('product_id' => $id));

        $template       = '';
        $result         = array(
            'flag'      => false,
            'template'  => $template,
            'data'      => $json_data
            );

        if(!empty($query_product)){
            foreach($query_product as $index => $value){
                $data['product'][] = $value;
                $data['product_sizestock']  = $this->global_model->select_where('product_sizestock',
                    array(
                        'product_id' => $value['product_id'],
                        'product_sizestock_id' => $product_sizestock_id
                        )
                    );
            }

            if(!empty($data['product_sizestock'])){

                $query_db_rental_order  = $this->front_model->get_all_rental_order_by_id($product_sizestock_id)->result_array();

                $no = 0;
                $rental_in_return       = array();
                $rental_in_returndate   = array();
                foreach($query_db_rental_order as $index => $value){
                    if((int)$day_after_return && !empty($value['return_date'])){
                        $rental_in_return[]                              = $value['rental_order_id'];
                        $rental_in_returndate[$value['rental_order_id']] = array(
                            'return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['return_date']))),
                            'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
                            'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_end_date'])))
                            );
                    }
                }

                $product                = array();
                $product_already_return = array();
                $sum_qty = 0;
                foreach($query_db_rental_order as $index => $value){

                    $product[$value['product_sizestock_id']][$value['rental_order_id']]  = $value;
                    $quantity               = (int) $value['rental_product_qty'];
                    if(in_array($value['rental_order_id'],$rental_in_return)){

                        $rental_order_id        = $value['rental_order_id'];
                        $rental_product_qty     = $value['rental_product_qty'];
                        $product_sizestock_id   = $value['product_sizestock_id'];

                        /*$sum_qty_1 = 0;

                        if(isset($count[$value['product_sizestock_id']])){
                            $count[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count[$value['product_sizestock_id']]=$quantity;
                        }

                        foreach($count as $key => $row){
                            
                            if($key == $value['product_sizestock_id']){
                                $sum_qty_1 = $row;
                            }
                        }*/

                        // jika return date dibawah atau sama dengan before take date
                            // jika after take date kurang dari current date
                            // jika return date dibawah after take date
                        if($rental_in_returndate[$rental_order_id]['return_date'] <= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date || $rental_in_returndate[$rental_order_id]['return_date'] < $rental_in_returndate[$rental_order_id]['after_take_date']){
                            $product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = $value['rental_product_qty'];
                        } else {
                            $product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = 0;
                        }

                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $value['product_stock'];

                    } elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {

                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = 0;

                        /*$sum_qty_2 = 0;

                        if(isset($count_2[$value['product_sizestock_id']])){
                            $count_2[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count_2[$value['product_sizestock_id']]=$quantity;
                        }

                        foreach($count_2 as $key => $row){
                            
                            if($key == $value['product_sizestock_id']){
                                $sum_qty_2 = $row;
                            }
                        }
*/
                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $value['product_stock'];

                    } else {

                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = 0;

                        /*$sum_qty_3 = 0;
                        if(isset($count_3[$value['product_sizestock_id']])){
                            $count_3[$value['product_sizestock_id']]+=$quantity;
                        } else {
                            $count_3[$value['product_sizestock_id']]=$quantity;
                        }

                        foreach($count_3 as $key => $row){
                            
                            if($key == $value['product_sizestock_id']){
                                $sum_qty_3 = $row;
                            }
                        }*/
                        $product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $value['product_stock'];
                    }
                }
            }

            /*echo 'variable product';
            echo '<pre>';
            print_r($product);
            echo '</pre>';*/

            $periode = array();
            $tes_ = array();
            $tes__ = array();
            /*foreach($query_db_rental_order as $index => $value){
                $date_periode   = $this->date_range_function($value['rental_start_date'],$value['rental_end_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');

                if(!empty($value['rental_order_id']) && empty($value['return_date'])){
                    $periode[$value['rental_order_id']] = array(
                        'product_stock'         => $value['product_stock'],
                        'periode'               => $date_periode,
                        'rental_product_qty'    => $value['rental_product_qty'],
                        'rental_start_date'     => date('m/d/Y',strtotime($value['rental_start_date'])),
                        'rental_end_date'       => date('m/d/Y',strtotime('+1 day', strtotime($value['rental_end_date'])))
                        );
                }
            }*/

            if(!empty($product)){
                foreach($product as $index => $value){

                    foreach($value as $key => $row){
                        $date_periode   = $this->date_range_function($row['rental_start_date'],$row['rental_end_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');

                        if(isset($row['return_date']) && !empty($row['return_date'])){
                            $date_periode   = $this->date_range_function($row['rental_start_date'],$row['return_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');
                        }

                        if(!empty($row['rental_order_id']) && empty($row['return_date'])){
                            $periode[$row['rental_order_id']] = array(
                                'product_stock'         => $row['product_stock'],
                                'product_order'         => $row['rental_qty'],
                                'periode'               => $date_periode,
                                'rental_product_qty'    => $row['rental_product_qty'],
                                'rental_start_date'     => date('m/d/Y',strtotime($row['rental_start_date'])),
                                'rental_end_date'       => date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date']))),
                                'return_date'           => ''
                                );
                        } else {
                            $periode[$row['rental_order_id']] = array(
                                'product_stock'         => $row['product_stock'],
                                'product_order'         => $row['product_stock'] - $row['return_qty'],
                                'periode'               => $date_periode,
                                'rental_product_qty'    => $row['rental_product_qty'],
                                'rental_start_date'     => date('m/d/Y',strtotime($row['rental_start_date'])),
                                'rental_end_date'       => date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date']))),
                                'return_date'           => date('m/d/Y',strtotime('+1 day', strtotime($row['return_date']))),
                                );
                        }
                    }
                }
            }

            /*echo 'variable periode';
            echo '<pre>';
            print_r($periode);
            echo '</pre>';*/

            foreach($periode as $index => $value){

                $rental_start_date  = $value['rental_start_date'];
                $rental_end_date    = $value['rental_end_date'];
                $return_date        = '';

                foreach($value['periode'] as $key => $row){

                    $tes_[$row][]   = $value['rental_product_qty']; //hitung total sum
                    $sum_all        = array_sum($tes_[$row]);

                    if(!empty($value['return_date'])){
                        $return_date = date('m/d/Y',strtotime($value['return_date']));
                    }

                    if($row <= $return_date){
                        $tes__[$row][] = array(
                            'return_date'       => $return_date,
                            'product_stock'     => $value['product_stock'],
                            'rental_product_qty'=> $value['rental_product_qty'],
                            'rental_start_date' => $value['rental_start_date'],
                            'rental_end_date'   => $value['rental_end_date']
                            );
                    } else {

                        $tes__[$row][] = array(
                            'return_date'       => '',
                            'product_stock'     => $value['product_stock'],
                            'rental_product_qty'=> $value['rental_product_qty'],
                            'rental_start_date' => $value['rental_start_date'],
                            'rental_end_date'   => $value['rental_end_date']
                            );

                        //$tes__[$row]['product_stock']         = $value['product_stock'];
                        //$tes__[$row]['rental_product_qty'         = $sum_all;
                        //$tes__[$row]['rental_start_date']     = $value['rental_start_date'];
                        //$tes__[$row]['rental_end_date']   = $value['rental_end_date'];
                    }   

                }

            }

            $total_qty_order_per_day = array();
            $filter_per_day          = array();

            if(!empty($tes__)){
                foreach($tes__ as $index => $value){
                    $date   = date('m/d/Y',strtotime($index));
                    $sum    = 0;
                    foreach($value as $key => $row){
                        if(isset($total_qty_order_per_day[$date])){
                            $total_qty_order_per_day[$date]+=$row['rental_product_qty'];
                        } else {
                            $total_qty_order_per_day[$date]=$row['rental_product_qty'];
                        }
                    }   
                }
            }

            /*echo 'tes__';
            echo '<pre>';
            print_r($tes__);
            echo '</pre>';

            echo 'variable total qty order per day before';
            echo '<pre>';
            print_r($total_qty_order_per_day);
            echo '</pre>';*/

            if(!empty($total_qty_order_per_day)){
                foreach($tes__ as $index => $value){
                    $day_date   = date('m/d/Y',strtotime($index));
                    $total_qty  = $total_qty_order_per_day[$index];
                    foreach($value as $key => $row){
                        $total_qty_order_per_day[$index]                        = $row;
                        $total_qty_order_per_day[$index]['total_qty']           = $total_qty;
                        $total_qty_order_per_day[$index]['status']              = '';
                        $total_qty_order_per_day[$index]['rental_late_date']    = '';
                    }

                    foreach($value as $key => $row){

                        // salah satu orderan telat
                        $late_date  = date('m/d/Y',strtotime($row['rental_end_date']));

/*                      echo '<pre>';
                        echo 'late date';
                        echo '<br>';
                        echo $late_date;
                        echo '</pre>';
                        echo '<br>';
                        echo 'day date';
                        echo '<br>';
                        echo $day_date;
                        echo '<br>';
                        echo 'current date';
                        echo '<br>';
                        echo $current_date;
                        echo '<br>';*/

                        // late date example:
                        // 14 Februari 2019
                        // 08 Februari 2019

                        // current date
                        // 14 Februari 2019

                        // day date
                        // index

                        if($late_date <= $current_date && empty($row['return_date'])){

                            /*echo 'lateeeeeee date';
                            echo '<br>';
                            echo $late_date;
                            echo '<br>';
                            echo 'in';
                            echo '<br>';
                            echo $index;
                            echo '<br>';
                            echo '<br>';
                            echo '<br>';
                            echo '<br>';*/
                            $total_qty_order_per_day[$index]['status']              = 'late';
                            $total_qty_order_per_day[$index]['rental_late_date']    = $late_date;

                        } /*elseif($late_date <= $day_date && $current_date <= $late_date && empty($row['return_date'])){
                            echo 'lateeeeeee date2';
                            echo $late_date;
                            echo '<br>';
                        }*/
                    }
                }
            }

            /*echo 'variable total qty order per day before';
            echo '<pre>';
            print_r($total_qty_order_per_day);
            echo '</pre>';*/

            $data_calendar = array();
            $data_calendar['date']  = array();
            $data_calendar['stock'] = array();
            $data_calendar['rental']= array();
            $data_calendar['class'] = array();

            $count      = 0;
            $tes_late_display = '';

            if(!empty($total_qty_order_per_day)){
                $rental_product_qty_late = array();
                foreach($total_qty_order_per_day as $index => $values){

                    $stock      = $values['product_stock'];
                    $total_qty  = $values['total_qty'];
                    $class      = 'default available';

                    $late_date   = date('Y-m-d',strtotime('+1 day',strtotime($values['rental_end_date'])));

                    $count      = $stock - $total_qty;

                    if($values['status'] == 'late'){

                        $tes_late_display = 'telat';

                        // bug 2 Januari 2019 - +1 late day pada current date
                        // command wrong
                        //$tambahan   = $this->date_range_function(date('Y-m-d',strtotime($values['rental_end_date'])),date('Y-m-d',strtotime($current_date)),'m/j/Y');

                        // add new variable 
                        $tambahan   = $this->date_range_function(date('Y-m-d',strtotime('+1 day',strtotime($values['rental_late_date']))),date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day',strtotime($current_date))),'m/d/Y');

                        //rental yang telat - nanti dulu aja
                        $rental_product_qty_late = $values['rental_product_qty'];
                    }
                }

                /*echo 'tambahan';
                echo '<br>';
                echo '<pre>';
                print_r($tambahan);
                echo '</pre>';*/
                foreach($total_qty_order_per_day as $index => $values){

                    if($values['status'] == 'late'){
                            //echo $rental_end_date;
                            // late date
                        if(is_array($tambahan) && !empty($tambahan)){
                            foreach($tambahan as $key => $row){ 
                                if($index == $row){
                                    $qty_tambah = $values['rental_product_qty'];

                                    if(isset($rental_product_qty_late[$row])){
                                        $qty_tambah = $total_qty + $rental_product_qty_late[$row];
                                    }

                                    $total_qty_order_per_day[$row] = array(
                                        'date'              => $row,
                                        'total_qty'         => $values['total_qty'],
                                        'product_stock'     => $stock,
                                        'rental_start_date' => $values['rental_start_date'],
                                        'rental_end_date'   => $values['rental_end_date'],
                                        'rental_late_date'  => $row,
                                        'status'            => $values['status']
                                        );
                                } else {
                                    $total_qty_order_per_day[$row] = array(
                                        'date'              => $row,
                                        'total_qty'         => $values['total_qty'],
                                        'product_stock'     => $stock,
                                        'rental_start_date' => $values['rental_start_date'],
                                        'rental_end_date'   => $values['rental_end_date'],
                                        'rental_late_date'  => $row,
                                        'status'            => $values['status']
                                        );
                                } 
                            }
                        }
                    }
                }

                /*echo 'total_qty_order_per_day';
                echo '<br>';
                echo '<pre>';
                print_r($total_qty_order_per_day);
                echo '</pre>';*/

                foreach($total_qty_order_per_day as $index => $value){

                    $stock      = $value['product_stock'];
                    $total_qty  = $value['total_qty'];
                    $class      = 'default available';

                    $late_date   = date('m/d/Y',strtotime('+1 day',strtotime($value['rental_end_date'])));

                    $count      = $stock - $total_qty;

                    if($count <= 0 && empty($value['rental_late_date']) && $value['status'] == ''){
                        $class = 'full';
                    } elseif($count > 0 && empty($value['rental_late_date'])){
                        $class = 'partially';
                    } elseif($count > 0 && !empty($value['rental_late_date']) && $value['status'] == 'late'){
                        $class = 'late partially';
                    } elseif($count <= 0 && !empty($value['rental_late_date']) && $value['status'] == 'late'){
                        $class = 'late full';
                    }

                    /*echo '<pre>';
            print_r($class);
            echo '</pre>';*/

            $format_datepicker = date('n/j/Y',strtotime($index));
            $data_calendar['class'][$format_datepicker]     = $class;
            $data_calendar['date'][$format_datepicker]      = $format_datepicker;
            $data_calendar['stock'][$format_datepicker]     = $stock;
            $data_calendar['rental'][$format_datepicker]    = $total_qty;

            $day = date('m/d/Y',strtotime($index));
            $json_data[$day] = array(
                'date'      => $day,
                'available' => $count,
                'stock'     => $stock,
                );
            $json_data['default'] = array(
                'available' => $stock,
                'stock'     => $stock
                ); 
        }

    } else {

        if(!empty($data['product_sizestock'])){
            foreach($data['product_sizestock'] as $index => $value){
                $stock = $value['product_stock'];
                $json_data['default'] = array(
                    'available' => $stock,
                    'stock'     => $stock
                    ); 
            }
        } else {
            $json_data['default'] = array(
                'available' => 0,
                'stock'     => 0
                ); 
        }
    }

            /*echo '<pre>';
            print_r($total_qty_order_per_day);
            echo '</pre>';*/

            $data_calendar['class'] = array_values($data_calendar['class']);
            $data_calendar['date']  = array_values($data_calendar['date']);
            $data_calendar['stock'] = array_values($data_calendar['stock']);
            $data_calendar['rental']= array_values($data_calendar['rental']);

            $data_date  = '';
            $data_stock = '';
            $data_rental= '';
            $data_class = '';
            $template   = '';

            if(!empty($data_calendar['date'])){
                $data_date      = implode(",",$data_calendar['date']);
            }
            if(!empty($data_calendar['stock'])){
                $data_stock     = implode(",",$data_calendar['stock']);
            }
            if(!empty($data_calendar['rental'])){
                $data_rental    = implode(",",$data_calendar['rental']);
            }
            if(!empty($data_calendar['class'])){
                $data_class     = implode(",",$data_calendar['class']);
            }

            $template = '<div id="calendar" class="date" data-class="'.$data_class.'" data-stock="'.$data_stock.'" data-rental="'.$data_rental.'" data-date="'.$data_date.'"></div>';

            $result         = array(
                'flag'      => true,
                'template'  => $template,
                'data'      => $json_data,
                'product_sizestock' => $data['product_sizestock']
                );
        }

        echo json_encode($result);
    }


    function date_range_function($first, $last, $output_format = 'd/m/Y', $delay_first = '+0 day', $delay_last = '+0 day') {
        $step               = '+1 day';
        $dates              = array();
        $first              = date('Y-m-d',strtotime($delay_first, strtotime($first)));
        $current            = strtotime($first);
        //$last                 = strtotime($last);
        $last               = date('Y-m-d',strtotime($delay_last, strtotime($last)));
        $last               = strtotime($last);
        while( $current <= $last ) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

}
