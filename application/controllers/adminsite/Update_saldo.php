<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Update_saldo extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('global_model');
		$this->load->model('backend_model');
	}

	public function index(){
		$getHistorySaldo = array();
		$twodays         = array(
			date('Y-m-d',strtotime('-1 days')),
			date('Y-m-d',strtotime('-2 days')),
			date('Y-m-d',strtotime('-3 days'))
		);
		foreach($twodays as $key => $row){
			$check = $this->backend_model->checkHistorySaldo($row);
			if(!$check){
				$getHistorySaldo[$row]['cash']      = array('tanggal'=>date('Y-m-d',strtotime('+1 days',strtotime($row))),'debit'=>0,'kredit'=>0,'total'=>0);
				$getHistorySaldo[$row]['debit']     = array('tanggal'=>date('Y-m-d',strtotime('+1 days',strtotime($row))),'debit'=>0,'kredit'=>0,'total'=>0);
				$getHistorySaldo[$row]['transfer']  = array('tanggal'=>date('Y-m-d',strtotime('+1 days',strtotime($row))),'debit'=>0,'kredit'=>0,'total'=>0);
			}
		}
		if(!empty($getHistorySaldo)){
			foreach($getHistorySaldo as $key => $row){
				$q = $this->backend_model->getHistorySaldo($key);
				if(!empty($q)){
					foreach($q as $index => $value){
						switch ($value['jenis_transaksi_action']) {
							case 'order':
							switch ($value['jenis_transaksi']) {
								case 'sewa':
								$getHistorySaldo[$key][$value['jenis_transaksi_flag']]['kredit']+=$value['jenis_transaksi_nominal'];
								break;
								case 'deposit':
								$getHistorySaldo[$key][$value['jenis_transaksi_flag']]['kredit']+=$value['jenis_transaksi_nominal'];
								break;
								case 'return':
								$getHistorySaldo[$key][$value['jenis_transaksi_flag']]['debit']+=$value['jenis_transaksi_nominal'];
								break;
							}
							break;
							case 'adjustment':
							switch ($value['jenis_transaksi']) {
								case 'sewa':
								$getHistorySaldo[$key][$value['jenis_transaksi_flag']]['kredit']+=$value['jenis_transaksi_nominal'];
								break;
								case 'deposit':
								$getHistorySaldo[$key][$value['jenis_transaksi_flag']]['debit']+=$value['jenis_transaksi_nominal'];
								break;
							}
							break;
						}
						$getHistorySaldo[$key][$value['jenis_transaksi_flag']]['total'] = $getHistorySaldo[$key][$value['jenis_transaksi_flag']]['kredit'] - $getHistorySaldo[$key][$value['jenis_transaksi_flag']]['debit'];
					}
				}
			}
			if(!empty($getHistorySaldo)){
				foreach($getHistorySaldo as $index => $value){
					foreach($value as $key => $row){
						$saldo = array(
							'saldo'   => $row['total'],
							'tanggal' => $index,
							'tanggal_sekarang' => $row['tanggal'],
							'debit'   => $row['debit'],
							'kredit'  => $row['kredit'],
							'created' => date('c'),
							'flag'    => $key
						);
						$this->global_model->insert('jenis_transaksi_saldo',$saldo);
					}
				}
			}
		}
	}
}

?>