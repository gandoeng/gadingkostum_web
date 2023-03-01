<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rental_order_backup extends CI_Controller {

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

    public function index() {
        //Data
        $data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
        $data['title']  = 'Rental Order Backup';

        $config = array(
            array(
                'field' => 'search',
                'label' => 'Search',
                'rules' => 'trim'
                ),
            );

        $this->form_validation->set_rules($config);

        $data['geturl']               = $this->input->get(null,true);

        $data['table_data']           = 'rental-order-backup'; // element id table

        $data['ajax_data_table']      = 'adminsite/Rental_order_backup/datatables_order'; //Controller ajax data
        $data['datatables_ajax_data'] = array(
            $this->custom_lib->datatables_ajax_serverside(TRUE,$data['table_data'],$data['ajax_data_table'],'','','','rental_order_backup')
            );
        //View
        $data['load_view'] = 'adminsite/v_rental_order_backup';
        $this->load->view('adminsite/template/backend', $data);
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
                // $action .= '<a class="btn-delete-action btn-ajax-trash-action btn btn-danger btn-xs btn-flat" data-item="'.$value['rental_order_id'].'" data-url="'.base_url('adminsite/rental_order/trash').'" style="display: block; width: 70px; margin-bottom: 2px;">Trash</a>';
                // $action .= '<a class="btn-print-action btn btn-warning btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invpinjam/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvPinjam</a>';

                $check_return_order = $this->backend_model->existing_return_order($value['rental_order_id']);

                // if($check_return_order){
                //     $action .= '<a class="btn-print-action btn btn-success btn-xs btn-flat" href="'.base_url('adminsite/rental_order/invkembali/').$value['rental_order_id'].'" style="display: block; width: 70px; margin-bottom: 2px;">InvKembali</a>';
                // }

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


}