<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_report extends CI_Controller {

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

    function has_string_keys(array $array) {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    function getSaldo(array $saldo){
        $data = array();
        if(!empty($saldo)){
            foreach($saldo as $index => $value){
                $data['saldoawal'][$value['jenis_transaksi_flag']] = array(
                    'kredit' => 0,
                    'debit'  => 0,
                    'total'  => 0
                );
                $data['saldoakhir'][$value['jenis_transaksi_flag']] = array(
                    'kredit' => 0,
                    'debit'  => 0,
                    'total'  => 0
                );
            }
            foreach($saldo as $index => $value){
                $data['saldoawal'][$value['flag']]['total']  = $value['saldo'];
                $data['saldoakhir'][$value['flag']]['total'] = $value['saldo'];
            }
        }
        return $data;
    }

    public function index() {
        //Data
        $data['session_items']          = $this->session->userdata($this->config->item('access_panel'));
        $data['geturl']                 = $this->input->get(null,true);

        if(!empty($data['geturl'])){
            $data['geturl']             = base64_encode(serialize($data['geturl']));
            $data['geturl']             = unserialize(base64_decode($data['geturl']));
        }

        $search                 = (isset($data['geturl']['search']) && !empty($data['geturl']['search'])) ? trim($data['geturl']['search']) : '';
        $curdate                = (isset($data['geturl']['date']) && !empty($data['geturl']['date'])) ? $data['geturl']['date'] : '';
        $show                   = (isset($_GET['show']) && !empty($_GET['show'])) ? trim($_GET['show']) : '';
        
        $startdate              = '';
        if($show == 'date' && isset($_GET['start']) && !empty($_GET['start'])){
            $startdate          = $_GET['start'];
            $startdate          = str_replace('/', '-', $startdate);
            $startdate          = date('Y-m-d', strtotime($startdate));
        } elseif($show == 'daily' && isset($_GET['date']) && !empty($_GET['date'])){
            $startdate          = $_GET['date'];
            $startdate          = str_replace('/', '-', $startdate);
            $startdate          = date('Y-m-d', strtotime($startdate));
        }

        $enddate                = (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : '';
        if(!empty($enddate)){
            $enddate            = str_replace('/', '-', $enddate);
            $enddate            = date('Y-m-d', strtotime($enddate));
        }

        $data['saldoawal']      = array();
        $data['saldoakhir']     = array();

        $getCash                = $this->backend_model->getDailyReportCash($search,$show,$startdate,$enddate);
        $getTransfer            = $this->backend_model->getDailyReportTransfer($search,$show,$startdate,$enddate);
        $getDebit               = $this->backend_model->getDailyReportDebit($search,$show,$startdate,$enddate);
        $saldoawalcash          = $this->backend_model->getBalanceDailyReportCash($search,$show,$startdate,$enddate);
        $saldoawaltransfer      = $this->backend_model->getBalanceDailyReportTransfer($search,$show,$startdate,$enddate);
        $saldoawaldebit         = $this->backend_model->getBalanceDailyReportDebit($search,$show,$startdate,$enddate);

        $getSaldoAwalCash       = $this->getSaldo($saldoawalcash);
        $getSaldoAwalTransfer   = $this->getSaldo($saldoawaltransfer);
        $getSaldoAwalDebit      = $this->getSaldo($saldoawaldebit);

        //CASH
        $adjustment = 1;
        if(!empty($getCash)){
            foreach($getCash as $index => $value){
                $nominal = 0;
                $debit   = 0;
                $kredit  = 0;
                $indextransaksi = $value['rental_order_id'];
                if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                    $indextransaksi = 'adjustment_'.$adjustment;
                }
                $nominal+=$value['jenis_transaksi_nominal'];
                $label = '';
                switch ($value['jenis_transaksi_action']) {
                    case 'order':
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];    
                                break;
                            case 'return':
                                $label = strtoupper('kembali deposit');
                                $debit+=$value['jenis_transaksi_nominal'];
                                break;
                        }
                    break;
                    case 'adjustment':
                        $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $debit+=$value['jenis_transaksi_nominal'];    
                                break;
                        }
                    break;
                }
                $data[$value['jenis_transaksi_flag']][$indextransaksi][$value['jenis_transaksi']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    'jenis_transaksi_product'   => $value['jenis_transaksi_product'],
                    'jenis_transaksi_qty'       => $value['jenis_transaksi_qty'],
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'nominal'                   => $nominal,
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'rental_created'            => $value['jenis_transaksi_created'],
                    'kredit'                    => $kredit,
                    'debit'                     => $debit,
                    'total'                     => 0
                    );
                $adjustment++;
            }

            $tempDebit  = (isset($getSaldoAwalCash['saldoakhir']['cash']['debit'])) ? $getSaldoAwalCash['saldoakhir']['cash']['debit'] : 0;
            $tempKredit = (isset($getSaldoAwalCash['saldoakhir']['cash']['kredit'])) ? $getSaldoAwalCash['saldoakhir']['cash']['kredit'] : 0;
            $tempSaldo  = (isset($getSaldoAwalCash['saldoakhir']['cash']['total'])) ? $getSaldoAwalCash['saldoakhir']['cash']['total'] : 0;

            if(isset($data['cash'])){
                foreach($data['cash'] as $index => $value){
                    foreach($value as $key => $val){
                        foreach($val as $i => $v){
                            if($v['jenis_transaksi'] == 'return'){
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            } else {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            }
                        }
                    }
                }
                $data['saldoawal']['cash'] = array(
                    'debit'     => (isset($getSaldoAwalCash['saldoawal']['cash']['debit'])) ? $getSaldoAwalCash['saldoawal']['cash']['debit'] : 0,
                    'kredit'    => (isset($getSaldoAwalCash['saldoawal']['cash']['kredit'])) ? $getSaldoAwalCash['saldoawal']['cash']['kredit'] : 0,
                    'total'     => (isset($getSaldoAwalCash['saldoawal']['cash']['total'])) ? $getSaldoAwalCash['saldoawal']['cash']['total'] : 0
                );
                $data['saldoakhir']['cash'] = array(
                    'debit'     => $tempDebit,
                    'kredit'    => $tempKredit,
                    'total'     => $tempSaldo
                );
            }
        } else {
            $data['saldoawal']['cash'] = array(
                'debit'     => (isset($getSaldoAwalCash['saldoawal']['cash']['debit'])) ? $getSaldoAwalCash['saldoawal']['cash']['debit'] : 0,
                'kredit'    => (isset($getSaldoAwalCash['saldoawal']['cash']['kredit'])) ? $getSaldoAwalCash['saldoawal']['cash']['kredit'] : 0,
                'total'     => (isset($getSaldoAwalCash['saldoawal']['cash']['total'])) ? $getSaldoAwalCash['saldoawal']['cash']['total'] : 0
            );
        }

        //TRANSFER
        $adjustment = 0;
        if(!empty($getTransfer)){
            foreach($getTransfer as $index => $value){
                $nominal = 0;
                $debit   = 0;
                $kredit  = 0;
                $indextransaksi = $value['rental_order_id'];
                if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                    $indextransaksi = 'adjustment_'.$adjustment;
                }
                $nominal+=$value['jenis_transaksi_nominal'];
                $label = '';
                switch ($value['jenis_transaksi_action']) {
                    case 'order':
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];    
                                break;
                            case 'return':
                                $label = strtoupper('kembali deposit');
                                $debit+=$value['jenis_transaksi_nominal'];
                                break;
                        }
                    break;
                    case 'adjustment':
                        $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $debit+=$value['jenis_transaksi_nominal'];    
                                break;
                        }
                    break;
                }
                $data[$value['jenis_transaksi_flag']][$indextransaksi][$value['jenis_transaksi']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    'jenis_transaksi_product'   => $value['jenis_transaksi_product'],
                    'jenis_transaksi_qty'       => $value['jenis_transaksi_qty'],
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'nominal'                   => $nominal,
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'rental_created'            => $value['jenis_transaksi_created'],
                    'kredit'                    => $kredit,
                    'debit'                     => $debit,
                    'total'                     => 0
                    );
                $adjustment++;
            }

            $tempDebit  = 0;
            $tempKredit = 0;
            $tempSaldo  = 0;

            if(isset($data['transfer'])){
                foreach($data['transfer'] as $index => $value){
                    foreach($value as $key => $val){
                        foreach($val as $i => $v){
                            if($v['jenis_transaksi'] == 'return'){
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            } else {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            }
                        }
                    }
                }
                $data['saldoawal']['transfer'] = array(
                    'debit'     => (isset($getSaldoAwalTransfer['saldoawal']['transfer']['debit'])) ? $getSaldoAwalTransfer['saldoawal']['transfer']['debit'] : 0,
                    'kredit'    => (isset($getSaldoAwalTransfer['saldoawal']['transfer']['kredit'])) ? $getSaldoAwalTransfer['saldoawal']['transfer']['kredit'] : 0,
                    'total'     => (isset($getSaldoAwalTransfer['saldoawal']['transfer']['total'])) ? $getSaldoAwalTransfer['saldoawal']['transfer']['total'] : 0
                );
                $data['saldoakhir']['transfer'] = array(
                    'debit'     => $tempDebit,
                    'kredit'    => $tempKredit,
                    'total'     => $tempSaldo
                );
            }
        }

        //DEBIT
        $adjustment = 0;
        if(!empty($getDebit)){
            foreach($getDebit as $index => $value){
                $nominal = 0;
                $debit   = 0;
                $kredit  = 0;
                $indextransaksi = $value['rental_order_id'];
                if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                    $indextransaksi = 'adjustment_'.$adjustment;
                }
                $nominal+=$value['jenis_transaksi_nominal'];
                $label = '';
                switch ($value['jenis_transaksi_action']) {
                    case 'order':
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];    
                                break;
                            case 'return':
                                $label = strtoupper('kembali deposit');
                                $debit+=$value['jenis_transaksi_nominal'];
                                break;
                        }
                    break;
                    case 'adjustment':
                        $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $debit+=$value['jenis_transaksi_nominal'];    
                                break;
                        }
                    break;
                }
                $data[$value['jenis_transaksi_flag']][$indextransaksi][$value['jenis_transaksi']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    'jenis_transaksi_product'   => $value['jenis_transaksi_product'],
                    'jenis_transaksi_qty'       => $value['jenis_transaksi_qty'],
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'nominal'                   => $nominal,
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'rental_created'            => $value['jenis_transaksi_created'],
                    'kredit'                    => $kredit,
                    'debit'                     => $debit,
                    'total'                     => 0
                    );
                $adjustment++;
            }

            $tempDebit  = 0;
            $tempKredit = 0;
            $tempSaldo  = 0;

            if(isset($data['debit'])){
                foreach($data['debit'] as $index => $value){
                    foreach($value as $key => $val){
                        foreach($val as $i => $v){
                            if($v['jenis_transaksi'] == 'return'){
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            } else {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            }
                        }
                    }
                }
                $data['saldoawal']['debit'] = array(
                    'debit'     => (isset($getSaldoAwalDebit['saldoawal']['debit']['debit'])) ? $getSaldoAwalDebit['saldoawal']['debit']['debit'] : 0,
                    'kredit'    => (isset($getSaldoAwalDebit['saldoawal']['debit']['kredit'])) ? $getSaldoAwalDebit['saldoawal']['debit']['kredit'] : 0,
                    'total'     => (isset($getSaldoAwalDebit['saldoawal']['debit']['total'])) ? $getSaldoAwalDebit['saldoawal']['debit']['total'] : 0
                );
                $data['saldoakhir']['debit'] = array(
                    'debit'     => $tempDebit,
                    'kredit'    => $tempKredit,
                    'total'     => $tempSaldo
                );
            }
        }
        $data['load_view'] = 'adminsite/v_daily_report';
        $this->load->view('adminsite/template/backend', $data);
    }

    public function update(){
        $query = $this->backend_model->getUpdateRental();
        foreach($query as $index => $value){
            $return = null;
            if(!empty($value['rental_return_date'])){
                $return = 'cash';
            }
            $update = array(
                'rental_terima_uangsewa'    => 'cash',
                'rental_terima_uangdeposit' => 'cash',
                'rental_return_uangdeposit' => $return
                );
            $upd = $this->global_model->update('rental_order',$update,array('rental_order_id' => $value['rental_order_id']));
        }
    }

    public function print_report($get = ''){
        if(!empty($get)){
            $get                = unserialize(base64_decode($get));
            $data['geturl']     = $get;
        }
        $search                 = (isset($get['search']) && !empty($get['search'])) ? trim($get['search']) : '';
        $curdate                = (isset($get['date']) && !empty($get['date'])) ? $get['date'] : '';
        $show                   = (isset($get['show']) && !empty($get['show'])) ? trim($get['show']) : '';
        
        $startdate              = '';
        if($show == 'date' && isset($get['start']) && !empty($get['start'])){
            $startdate          = $get['start'];
            $startdate          = str_replace('/', '-', $startdate);
            $startdate          = date('Y-m-d', strtotime($startdate));
        } elseif($show == 'daily' && isset($get['date']) && !empty($get['date'])){
            $startdate          = $get['date'];
            $startdate          = str_replace('/', '-', $startdate);
            $startdate          = date('Y-m-d', strtotime($startdate));
        }

        $enddate                = (isset($get['end']) && !empty($get['end'])) ? $get['end'] : '';
        if(!empty($enddate)){
            $enddate            = str_replace('/', '-', $enddate);
            $enddate            = date('Y-m-d', strtotime($enddate));
        }

        $data['periode']        = 'Today : '.date('d/M/Y');
        if(isset($get['show']) && !empty($get['show'])){
            switch ($get['show']) {
                case 'daily':
                    if(isset($get['date']) && !empty($get['date'])){
                        $data['periode'] = 'Daily : '.date('d/M/Y',strtotime($startdate));
                    }
                break;
                case 'date':
                    $data['periode']    = 'Periode : '.date('d/M/Y',strtotime($startdate)).' s/d '.date('d/M/Y',strtotime($enddate));
                break;
            }
        }
        $data['saldoawal']      = array();
        $data['saldoakhir']     = array();

        $getCash                = $this->backend_model->getDailyReportCash($search,$show,$startdate,$enddate);
        $getTransfer            = $this->backend_model->getDailyReportTransfer($search,$show,$startdate,$enddate);
        $getDebit               = $this->backend_model->getDailyReportDebit($search,$show,$startdate,$enddate);
        $saldoawalcash          = $this->backend_model->getBalanceDailyReportCash($search,$show,$startdate,$enddate);
        $saldoawaltransfer      = $this->backend_model->getBalanceDailyReportTransfer($search,$show,$startdate,$enddate);
        $saldoawaldebit         = $this->backend_model->getBalanceDailyReportDebit($search,$show,$startdate,$enddate);

        $getSaldoAwalCash       = $this->getSaldo($saldoawalcash);
        $getSaldoAwalTransfer   = $this->getSaldo($saldoawaltransfer);
        $getSaldoAwalDebit      = $this->getSaldo($saldoawaldebit);

        //CASH
        $adjustment = 1;
        if(!empty($getCash)){
            foreach($getCash as $index => $value){
                $nominal = 0;
                $debit   = 0;
                $kredit  = 0;
                $indextransaksi = $value['rental_order_id'];
                if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                    $indextransaksi = 'adjustment_'.$adjustment;
                }
                $nominal+=$value['jenis_transaksi_nominal'];
                $label = '';
                switch ($value['jenis_transaksi_action']) {
                    case 'order':
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];    
                                break;
                            case 'return':
                                $label = strtoupper('kembali deposit');
                                $debit+=$value['jenis_transaksi_nominal'];
                                break;
                        }
                    break;
                    case 'adjustment':
                        $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $debit+=$value['jenis_transaksi_nominal'];    
                                break;
                        }
                    break;
                }
                $data[$value['jenis_transaksi_flag']][$indextransaksi][$value['jenis_transaksi']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    'jenis_transaksi_product'   => $value['jenis_transaksi_product'],
                    'jenis_transaksi_qty'       => $value['jenis_transaksi_qty'],
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'nominal'                   => $nominal,
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'rental_created'            => $value['jenis_transaksi_created'],
                    'kredit'                    => $kredit,
                    'debit'                     => $debit,
                    'total'                     => 0
                    );
                $adjustment++;
            }

            $tempDebit  = (isset($getSaldoAwalCash['saldoakhir']['cash']['debit'])) ? $getSaldoAwalCash['saldoakhir']['cash']['debit'] : 0;
            $tempKredit = (isset($getSaldoAwalCash['saldoakhir']['cash']['kredit'])) ? $getSaldoAwalCash['saldoakhir']['cash']['kredit'] : 0;
            $tempSaldo  = (isset($getSaldoAwalCash['saldoakhir']['cash']['total'])) ? $getSaldoAwalCash['saldoakhir']['cash']['total'] : 0;

            if(isset($data['cash'])){
                foreach($data['cash'] as $index => $value){
                    foreach($value as $key => $val){
                        foreach($val as $i => $v){
                            if($v['jenis_transaksi'] == 'return'){
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            } else {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['cash'][$index][$key][$i]['total']  = $tempSaldo;
                            }
                        }
                    }
                }
                $data['saldoawal']['cash'] = array(
                    'debit'     => (isset($getSaldoAwalCash['saldoawal']['cash']['debit'])) ? $getSaldoAwalCash['saldoawal']['cash']['debit'] : 0,
                    'kredit'    => (isset($getSaldoAwalCash['saldoawal']['cash']['kredit'])) ? $getSaldoAwalCash['saldoawal']['cash']['kredit'] : 0,
                    'total'     => (isset($getSaldoAwalCash['saldoawal']['cash']['total'])) ? $getSaldoAwalCash['saldoawal']['cash']['total'] : 0
                );
                $data['saldoakhir']['cash'] = array(
                    'debit'     => $tempDebit,
                    'kredit'    => $tempKredit,
                    'total'     => $tempSaldo
                );
            }
        } else {
            $data['saldoawal']['cash'] = array(
                'debit'     => (isset($getSaldoAwalCash['saldoawal']['cash']['debit'])) ? $getSaldoAwalCash['saldoawal']['cash']['debit'] : 0,
                'kredit'    => (isset($getSaldoAwalCash['saldoawal']['cash']['kredit'])) ? $getSaldoAwalCash['saldoawal']['cash']['kredit'] : 0,
                'total'     => (isset($getSaldoAwalCash['saldoawal']['cash']['total'])) ? $getSaldoAwalCash['saldoawal']['cash']['total'] : 0
            );
        }

        //TRANSFER
        $adjustment = 0;
        if(!empty($getTransfer)){
            foreach($getTransfer as $index => $value){
                $nominal = 0;
                $debit   = 0;
                $kredit  = 0;
                $indextransaksi = $value['rental_order_id'];
                if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                    $indextransaksi = 'adjustment_'.$adjustment;
                }
                $nominal+=$value['jenis_transaksi_nominal'];
                $label = '';
                switch ($value['jenis_transaksi_action']) {
                    case 'order':
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];    
                                break;
                            case 'return':
                                $label = strtoupper('kembali deposit');
                                $debit+=$value['jenis_transaksi_nominal'];
                                break;
                        }
                    break;
                    case 'adjustment':
                        $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $debit+=$value['jenis_transaksi_nominal'];    
                                break;
                        }
                    break;
                }
                $data[$value['jenis_transaksi_flag']][$indextransaksi][$value['jenis_transaksi']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    'jenis_transaksi_product'   => $value['jenis_transaksi_product'],
                    'jenis_transaksi_qty'       => $value['jenis_transaksi_qty'],
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'nominal'                   => $nominal,
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'rental_created'            => $value['jenis_transaksi_created'],
                    'kredit'                    => $kredit,
                    'debit'                     => $debit,
                    'total'                     => 0
                    );
                $adjustment++;
            }

            $tempDebit  = 0;
            $tempKredit = 0;
            $tempSaldo  = 0;

            if(isset($data['transfer'])){
                foreach($data['transfer'] as $index => $value){
                    foreach($value as $key => $val){
                        foreach($val as $i => $v){
                            if($v['jenis_transaksi'] == 'return'){
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            } else {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['transfer'][$index][$key][$i]['total']  = $tempSaldo;
                            }
                        }
                    }
                }
                $data['saldoawal']['transfer'] = array(
                    'debit'     => (isset($getSaldoAwalTransfer['saldoawal']['transfer']['debit'])) ? $getSaldoAwalTransfer['saldoawal']['transfer']['debit'] : 0,
                    'kredit'    => (isset($getSaldoAwalTransfer['saldoawal']['transfer']['kredit'])) ? $getSaldoAwalTransfer['saldoawal']['transfer']['kredit'] : 0,
                    'total'     => (isset($getSaldoAwalTransfer['saldoawal']['transfer']['total'])) ? $getSaldoAwalTransfer['saldoawal']['transfer']['total'] : 0
                );
                $data['saldoakhir']['transfer'] = array(
                    'debit'     => $tempDebit,
                    'kredit'    => $tempKredit,
                    'total'     => $tempSaldo
                );
            }
        }

        //DEBIT
        $adjustment = 0;
        if(!empty($getDebit)){
            foreach($getDebit as $index => $value){
                $nominal = 0;
                $debit   = 0;
                $kredit  = 0;
                $indextransaksi = $value['rental_order_id'];
                if(empty($value['rental_order_id']) && $value['jenis_transaksi_action'] == 'adjustment'){
                    $indextransaksi = 'adjustment_'.$adjustment;
                }
                $nominal+=$value['jenis_transaksi_nominal'];
                $label = '';
                switch ($value['jenis_transaksi_action']) {
                    case 'order':
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $label = strtoupper($value['jenis_transaksi']);
                                $kredit+=$value['jenis_transaksi_nominal'];    
                                break;
                            case 'return':
                                $label = strtoupper('kembali deposit');
                                $debit+=$value['jenis_transaksi_nominal'];
                                break;
                        }
                    break;
                    case 'adjustment':
                        $label = 'Adjustment #'.$adjustment.': '.html_entity_decode($value['jenis_transaksi_note']);
                        switch ($value['jenis_transaksi']) {
                            case 'sewa':
                                $kredit+=$value['jenis_transaksi_nominal'];
                                break;
                            case 'deposit':
                                $debit+=$value['jenis_transaksi_nominal'];    
                                break;
                        }
                    break;
                }
                $data[$value['jenis_transaksi_flag']][$indextransaksi][$value['jenis_transaksi']][] = array(
                    'jenis_transaksi_id'        => $value['jenis_transaksi_id'],
                    'rental_order_id'           => $value['rental_order_id'],
                    'rental_invoice'            => $value['rental_invoice'],
                    'jenis_transaksi_product'   => $value['jenis_transaksi_product'],
                    'jenis_transaksi_qty'       => $value['jenis_transaksi_qty'],
                    'jenis_transaksi'           => $value['jenis_transaksi'],
                    'jenis_transaksi_flag'      => $value['jenis_transaksi_flag'],
                    'jenis_transaksi_note'      => $value['jenis_transaksi_note'],
                    'customer_name'             => $value['jenis_transaksi_customer_nama'],
                    'customer_phone'            => $value['jenis_transaksi_customer_phone'],
                    'jenis_transaksi_nominal'   => $value['jenis_transaksi_nominal'],
                    'nominal'                   => $nominal,
                    'label'                     => $label,
                    'transaksi'                 => $value['rental_invoice'],
                    'nama'                      => $value['jenis_transaksi_customer_nama'].'<br>'.$value['jenis_transaksi_customer_phone'],
                    'tanggal'                   => date('d F Y | h:i A',strtotime($value['jenis_transaksi_created'])),
                    'rental_created'            => $value['jenis_transaksi_created'],
                    'kredit'                    => $kredit,
                    'debit'                     => $debit,
                    'total'                     => 0
                    );
                $adjustment++;
            }

            $tempDebit  = 0;
            $tempKredit = 0;
            $tempSaldo  = 0;

            if(isset($data['debit'])){
                foreach($data['debit'] as $index => $value){
                    foreach($value as $key => $val){
                        foreach($val as $i => $v){
                            if($v['jenis_transaksi'] == 'return'){
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'deposit') {
                                $tempDebit  += $v['nominal']; 
                                $tempSaldo  -= $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            } elseif(is_string($index) && $v['jenis_transaksi'] == 'sewa') {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            } else {
                                $tempKredit  += $v['nominal']; 
                                $tempSaldo   += $v['nominal'];
                                $data['debit'][$index][$key][$i]['total']  = $tempSaldo;
                            }
                        }
                    }
                }
                $data['saldoawal']['debit'] = array(
                    'debit'     => (isset($getSaldoAwalDebit['saldoawal']['debit']['debit'])) ? $getSaldoAwalDebit['saldoawal']['debit']['debit'] : 0,
                    'kredit'    => (isset($getSaldoAwalDebit['saldoawal']['debit']['kredit'])) ? $getSaldoAwalDebit['saldoawal']['debit']['kredit'] : 0,
                    'total'     => (isset($getSaldoAwalDebit['saldoawal']['debit']['total'])) ? $getSaldoAwalDebit['saldoawal']['debit']['total'] : 0
                );
                $data['saldoakhir']['debit'] = array(
                    'debit'     => $tempDebit,
                    'kredit'    => $tempKredit,
                    'total'     => $tempSaldo
                );
            }
        }
        $this->load->view('adminsite/v_print_daily_report', $data);
    }
}
?>