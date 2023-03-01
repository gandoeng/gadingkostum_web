<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_kas extends CI_Controller {

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

    public function form(){
        $data['session_items']  = $this->session->userdata($this->config->item('access_panel'));
        $this->form_validation->set_rules('jenis_transaksi_id','ID','trim');  
        $this->form_validation->set_rules('jenis_transaksi_nominal','Transaksi Nominal','trim');
        $this->form_validation->set_rules('jenis_transaksi','Jenis Transaksi','trim');
        $this->form_validation->set_rules('jenis_transaksi_note','Transaksi Note','trim');
        $this->form_validation->set_rules('jenis_transaksi_flag','Transaksi Jenis Flag','trim');

        if($this->form_validation->run() == false){
            if(validation_errors()){
				$this->session->set_flashdata('validation',json_encode(validation_errors()));
			}
			redirect($this->index());
        } else {
            $current_url            = $this->input->post('current_url');
            $jenis_transaksi_id     = $this->input->post('jenis_transaksi_id');
            $jenis_transaksi_nominal= $this->input->post('jenis_transaksi_nominal');
            $jenis_transaksi_flag   = $this->input->post('jenis_transaksi_flag');
            $jenis_transaksi_note   = $this->input->post('jenis_transaksi_note');
            $jenis_transaksi        = $this->input->post('jenis_transaksi');
            $time                   = date('H:i:s');
            //$transaksi_created  = $this->input->post('transaksi_created');
            //(empty($transaksi_created)) ? $transaksi_created = date('Y-m-d H:i:s') : $transaksi_created = str_replace('/', '-', $transaksi_created); $transaksi_created = date('Y-m-d '.$time, strtotime($transaksi_created));
            $result             = false;

            if(isset($data['session_items']['role']) && $data['session_items']['role'] == 'admin'){ 
                if(empty($jenis_transaksi_id)){
                    $ins = array(
                        'jenis_transaksi_nominal'   => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal),
                        'jenis_transaksi_note'      => htmlentities($jenis_transaksi_note),
                        'jenis_transaksi_flag'      => $jenis_transaksi_flag,
                        'jenis_transaksi'           => $jenis_transaksi,
                        'jenis_transaksi_created'   => date("Y-m-d H:i:s"),
                        'jenis_transaksi_modified'  => date("Y-m-d H:i:s"),
                        'jenis_transaksi_action'    => 'adjustment'
                    );
                    $result = $this->global_model->insert('jenis_transaksi',$ins);
                } else {
                    $upd = array(
                        'jenis_transaksi_nominal'   => preg_replace("/[^0-9\.]/","",$jenis_transaksi_nominal),
                        'jenis_transaksi_note'      => htmlentities($jenis_transaksi_note),
                        'jenis_transaksi_flag'      => $jenis_transaksi_flag,
                        'jenis_transaksi'           => $jenis_transaksi,
                        'jenis_transaksi_created'   => date("Y-m-d H:i:s"),
                        'jenis_transaksi_modified'  => date("Y-m-d H:i:s"),
                        'jenis_transaksi_action'    => 'adjustment'
                    );
                    $result = $this->global_model->update('jenis_transaksi',$upd,array('jenis_transaksi_id' => $jenis_transaksi_id));
                }
            }

            if($result){
                $this->session->set_flashdata('success','Save success');
            } else {
                $this->session->set_flashdata('success','Save fail!');
            }
			redirect($current_url);
        }
    }
    public function index() {
        //Data
        $data['session_items']  = $this->session->userdata($this->config->item('access_panel'));
        $data['current_url']    = $this->getcurrent_url();
        $show                   = (isset($_GET['show']) && !empty($_GET['show'])) ? trim($_GET['show']) : '';
        $search                 = (isset($_GET['search']) && !empty($_GET['search'])) ? trim($_GET['search']) : '';

        $startdate              = '';
        if($show == 'date' && isset($_GET['start']) && !empty($_GET['start'])){
            $startdate          = $_GET['start'];
            $startdate          = str_replace('/', '-', $startdate);
            $startdate          = date('Y-m-d', strtotime($startdate));
        } elseif($show == 'month' && isset($_GET['month']) && !empty($_GET['month'])){
            $startdate          = $_GET['month'];
            $startdate          = str_replace('/', '-', $startdate);
            $startdate          = date('Y-m', strtotime($startdate));
        }

        $enddate                = (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : '';
        if(!empty($enddate)){
            $enddate            = str_replace('/', '-', $enddate);
            $enddate            = date('Y-m-d', strtotime($enddate));
        }

        $data['saldoawal']      = array(
            'debit'             => 0,
            'kredit'            => 0,
            'total'             => 0
        );
        $rental_order           = $this->backend_model->getOrderBukuKas($search,$show,$startdate,$enddate);
        $saldoawal 				= $this->backend_model->getSaldoAwal($search,$show,$startdate,$enddate);
        //$saldoawal              = $this->backend_model->getBalanceBukuKas($search,$show,$startdate,$enddate);
        if(!empty($saldoawal)){
            foreach($saldoawal as $index => $value){
                $data['saldoawal']['total'] += $value['saldo'];
            }
        }

        $result                 = array();
        $rental_order_id        = array();
        $current_date           = date('Y-m-d');

        if(!empty($rental_order)){
            foreach($rental_order as $index => $value){
                $rental_order_id[$value['rental_order_id']] = $value['rental_order_id'];
            }
            $rental_order_id = array_values($rental_order_id);
        }

        $getItems             = array();
        $temp                 = array();

        $adjustment = 1;
        foreach($rental_order as $index => $value){
            $label = '';
            switch ($value['jenis_transaksi_action']) {
                case 'order':
                    switch ($value['jenis_transaksi']) {
                        case 'sewa':
                            $label = '<span class="label label-primary">'.strtoupper($value['jenis_transaksi']).'</span>';
                            break;
                        case 'deposit':
                            $label = '<span class="label label-warning">'.strtoupper($value['jenis_transaksi']).'</span>';
                            break;
                        case 'return':
                            $label = '<span class="label label-success">'.strtoupper($value['jenis_transaksi']).'</span>';
                            break;
                    }
                break;
                case 'adjustment':
                    $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                break;
            }

            $indextransaksi = $value['rental_order_id'];
            if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                $indextransaksi = 'adjustment_'.$adjustment;
            }
            $temp[$indextransaksi][$value['jenis_transaksi']][$value['jenis_transaksi_flag']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    //'rental_created'            => strtotime($value['rental_created']),
                    //'rental_return_date'        => strtotime($value['rental_return_date']),
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'created'                   => $value['jenis_transaksi_created']
                );
            $adjustment++;
        }

        $saldo                = $data['saldoawal']['total'];
        $saldo_debit          = $data['saldoawal']['debit'];
        $saldo_kredit         = $data['saldoawal']['kredit'];
        $total                = 0;

        foreach($temp as $index => $value){
            foreach($value as $key => $row){
                foreach($row as $k => $val){
                    $count = 0;
                    foreach($val as $i => $v){
                        $count+=$v['jenis_transaksi_nominal'];
                        $getItems[$index][$key][$k] = $v;
                        $getItems[$index][$key][$k]['debit']  = 0;
                        $getItems[$index][$key][$k]['kredit'] = 0;
                        if($key == 'return'){
                            $saldo_debit  += $v['jenis_transaksi_nominal']; 
                            $saldo        -= $v['jenis_transaksi_nominal']; 
                            $getItems[$index][$key][$k]['debit']  = $count; 
                            $getItems[$index][$key][$k]['saldo']  = $saldo;
                        } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                            $saldo_debit  += $v['jenis_transaksi_nominal']; 
                            $saldo        -= $v['jenis_transaksi_nominal']; 
                            $getItems[$index][$key][$k]['debit']  = $count; 
                            $getItems[$index][$key][$k]['saldo']  = $saldo;
                        } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                            $saldo_kredit   += $v['jenis_transaksi_nominal'];
                            $saldo          += $v['jenis_transaksi_nominal']; 
                            $getItems[$index][$key][$k]['kredit'] = $count;
                            $getItems[$index][$key][$k]['saldo']  = $saldo;
                        } else {
                            $saldo_kredit   += $v['jenis_transaksi_nominal'];
                            $saldo          += $v['jenis_transaksi_nominal']; 
                            $getItems[$index][$key][$k]['kredit'] = $count;
                            $getItems[$index][$key][$k]['saldo']  = $saldo;
                        }
                    }
                }
            }
        }

        $data['result']         = $getItems;
        $data['totaldebit']     = $saldo_debit;
        $data['totalkredit']    = $saldo_kredit;
        $data['totalsaldo']     = $saldo;
        $data['result_adjustment'] = array();
        $data['load_view'] = 'adminsite/v_buku_kas';
        $this->load->view('adminsite/template/backend', $data);
    }

    public function delete($id){
        $id     = $this->uri->segment(4);
        $data['session_items']  = $this->session->userdata($this->config->item('access_panel'));
        $delete = false;
        if(isset($data['session_items']['role']) && $data['session_items']['role'] == 'admin'){ 
            $delete = $this->global_model->delete('jenis_transaksi',array('jenis_transaksi_id' => $id));
        }
        if($delete){
            $this->session->set_flashdata('success','Delete success');
        } else {
            $this->session->set_flashdata('success','Delete fail!');
        }
        redirect('adminsite/buku_kas');
    }

    function getcurrent_url()
    {
        $CI =& get_instance();

        $url = $CI->config->site_url($CI->uri->uri_string());
        return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
    }
}
?>