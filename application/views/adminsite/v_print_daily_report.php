<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/AdminLTE.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/skins/_all-skins.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/style.css').'?'.md5(date('c'));?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/print.css').'?'.md5(date('c'));?>">
	<style type="text/css">
		@media all {
			.page-break	{ display: none; }
			legend{
				margin-bottom: 1px;
			}
			.table tbody tr td{
				font-size: 10px !important;
				line-height: 1.1 !important;
			}

			.table thead tr th{
				padding: 5px !important;
				line-height: 1.1 !important;
			}
			.group{
				display: inline-block !important;
				padding-left: 3px;
				padding-right: 3px;
			}

			th{
				vertical-align:middle !important;
			}

			td{
				vertical-align: middle !important;
			}
    }
    
    @media print{
      .title{
        font-family: 'Open Sans', sans-serif;
        font-size: 9px !important;
        text-align:left;
      }
      table{
    margin-top: 15px;
    font-family: 'Open Sans', sans-serif;
    margin-bottom: 50px !important;
  }
  .table-bordered thead tr,.table-bordered tfoot tr,.table-bordered .saldoawal{
    background-color: #ccc !important;
    background: #ccc !important;
  }
  .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
    border: 1px solid #ccc;
  }
  .table-bordered thead tr,.table-bordered tfoot tr,.table-bordered .items td:first-child{
    text-align: left !important;
  }
  .table-striped>tbody>tr:nth-of-type(odd){
    background-color: #ecf0f5;
    background: #ecf0f5;
  }

  .table-report thead tr{
    -webkit-print-color-adjust: exact !important;
    background-color: #ccc;
    background: #ccc;
  }
  .table-report tbody td{
    padding: 5px !important;
    text-align: center;
  }
  .table-report tfoot td{
    border: 1px solid black !important;
    page-break-after:always !important;
  }
    }
  
  .table-report thead tr{
    -webkit-print-color-adjust: exact !important;
    background-color: #ccc !important;
    background: #ccc !important;
  }
  .table-report tbody td{
    padding: 5px !important;
    text-align: center;
  }

  .table-report tfoot td{
    border: 1px solid #ccc !important;
    page-break-after:always !important;
  }

  table{
    margin-top: 15px;
    font-family: 'Open Sans', sans-serif;
    margin-bottom: 30px !important;
  }
  .table-bordered thead tr,.table-bordered tfoot tr{
    background-color: #ccc;
    background: #ccc;
  }
  .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
    border: 1px solid #ccc !important;
  }
  .table-striped>tbody>tr:nth-of-type(odd){
    background-color: #ecf0f5;
    background: #ecf0f5;
  }
</style>
</head>
<body>
	<div class="content-wrapper content-invoice">
		<section class="content">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-print-12">
        <div class="title"><strong>Daily Report : <?php echo $periode;?></strong></div>
        <?php 
                  echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
                    echo '<thead>';
                      echo '<tr>';
                        echo '<th colspan="3" style="width: 300px;">'.strtoupper('cash').'</th>';
                        echo '<th>KETERANGAN</th>';
                        echo '<th>DEBIT</th>';
                        echo '<th>KREDIT</th>';
                        echo '<th>SALDO</th>';
                      echo '</tr>';
                    echo '</thead>';
                      echo '<tbody>';
                        echo '<tr class="saldoawal">';
                          echo '<td colspan="3"><strong>SALDO AWAL</strong></td>';
                          echo '<td></td>';
                          echo '<td><strong>'.number_format($saldoawal['cash']['debit']).'</strong></td>';
                          echo '<td><strong>'.number_format($saldoawal['cash']['kredit']).'</strong></td>';
                          echo '<td><strong>'.number_format($saldoawal['cash']['total']).'</strong></td>';
                        echo '</tr>';
                  if(isset($cash) && !empty($cash)){ 
                    foreach($cash as $index => $values){
                      if(is_array($values) && !empty($values)){
                        foreach($values as $k => $r){
                          foreach($r as $i => $val){
                            echo '<tr class="items">';
                              if(is_string($index)){
                                echo '<td colspan="3" style="width: 300px;">'.$val['label'].'</td>';
                                echo '<td></td>';
                              } else {
                                echo '<td colspan="3" style="width: 300px;">'.$val['rental_invoice'].' | '.$val['customer_name'].' '.$val['customer_phone'].' | ('.$val['jenis_transaksi_qty'].') '.$val['jenis_transaksi_product'].'</td>';
                                echo '<td>'.$val['label'].'</td>';
                              }
                                echo '<td>'.number_format($val['debit']).'</td>';
                                echo '<td>'.number_format($val['kredit']).'</td>';
                                echo '<td>'.number_format($val['total']).'</td>';
                            echo '</tr>';
                          }
                        }
                      }
                    }
                  }
                    echo '</tbody>';
                    echo '<tfoot>';
                      echo '<tr>';
                        echo '<td colspan="3"></td>';
                        echo '<td></td>';
                        echo (isset($saldoakhir['cash']['debit'])) ? '<td><strong>'.number_format($saldoakhir['cash']['debit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['cash']['kredit'])) ? '<td><strong>'.number_format($saldoakhir['cash']['kredit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['cash']['total'])) ? '<td><strong>'.number_format($saldoakhir['cash']['total']).'</strong></td>' : '<td></td>';
                      echo '</tr>';
                    echo '</tfoot>';
                  echo '</table>';
            ?>

            <?php 
                if(isset($debit) && !empty($debit)){
                  echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
                    echo '<thead>';
                      echo '<tr>';
                        echo '<th colspan="3" style="width: 300px;">'.strtoupper('debit').'</th>';
                        echo '<th>KETERANGAN</th>';
                        echo '<th>DEBIT</th>';
                        echo '<th>KREDIT</th>';
                        echo '<th>SALDO</th>';
                      echo '</tr>';
                    echo '</thead>';
                      echo '<tbody>'; 
                    foreach($debit as $index => $values){
                      if(is_array($values) && !empty($values)){
                        foreach($values as $k => $r){
                          foreach($r as $i => $val){
                            echo '<tr class="items">';
                              if(is_string($index)){
                                echo '<td colspan="3" style="width: 300px;">'.$val['label'].'</td>';
                                echo '<td></td>';
                              } else {
                                echo '<td colspan="3" style="width: 300px;">'.$val['rental_invoice'].' | '.$val['customer_name'].' '.$val['customer_phone'].' | ('.$val['jenis_transaksi_qty'].') '.$val['jenis_transaksi_product'].'</td>';
                                echo '<td>'.$val['label'].'</td>';
                              }
                                echo '<td>'.number_format($val['debit']).'</td>';
                                echo '<td>'.number_format($val['kredit']).'</td>';
                                echo '<td>'.number_format($val['total']).'</td>';
                            echo '</tr>';
                            }
                        }
                      }
                    }
                    echo '</tbody>';
                    echo '<tfoot>';
                      echo '<tr>';
                        echo '<td colspan="3"></td>';
                        echo '<td></td>';
                        echo (isset($saldoakhir['debit']['debit'])) ? '<td><strong>'.number_format($saldoakhir['debit']['debit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['debit']['kredit'])) ? '<td><strong>'.number_format($saldoakhir['debit']['kredit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['debit']['total'])) ? '<td><strong>'.number_format($saldoakhir['debit']['total']).'</strong></td>' : '<td></td>';
                      echo '</tr>';
                    echo '</tfoot>';
                  echo '</table>';
                }
            ?>

            <?php
                if(isset($transfer) && !empty($transfer)){ 
                  echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
                    echo '<thead>';
                      echo '<tr>';
                        echo '<th colspan="3" style="width: 300px;">'.strtoupper('transfer').'</th>';
                        echo '<th>KETERANGAN</th>';
                        echo '<th>DEBIT</th>';
                        echo '<th>KREDIT</th>';
                        echo '<th>SALDO</th>';
                      echo '</tr>';
                    echo '</thead>';
                      echo '<tbody>';
                    foreach($transfer as $index => $values){
                      if(is_array($values) && !empty($values)){
                        foreach($values as $k => $r){
                          foreach($r as $i => $val){
                            echo '<tr class="items">';
                              if(is_string($index)){
                                echo '<td colspan="3" style="width: 300px;">'.$val['label'].'</td>';
                                echo '<td></td>';
                              } else {
                                echo '<td colspan="3" style="width: 300px;">'.$val['rental_invoice'].' | '.$val['customer_name'].' '.$val['customer_phone'].' | ('.$val['jenis_transaksi_qty'].') '.$val['jenis_transaksi_product'].'</td>';
                                echo '<td>'.$val['label'].'</td>';
                              }
                                echo '<td>'.number_format($val['debit']).'</td>';
                                echo '<td>'.number_format($val['kredit']).'</td>';
                                echo '<td>'.number_format($val['total']).'</td>';
                            echo '</tr>';
                            }
                        }
                      }
                    }
                    echo '</tbody>';
                    echo '<tfoot>';
                      echo '<tr>';
                        echo '<td colspan="3"></td>';
                        echo '<td></td>';
                        echo (isset($saldoakhir['transfer']['debit'])) ? '<td><strong>'.number_format($saldoakhir['transfer']['debit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['transfer']['kredit'])) ? '<td><strong>'.number_format($saldoakhir['transfer']['kredit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['transfer']['total'])) ? '<td><strong>'.number_format($saldoakhir['transfer']['total']).'</strong></td>' : '<td></td>';
                      echo '</tr>';
                    echo '</tfoot>';
                  echo '</table>';
                }
            ?>
				</div>
			</div>
		</section>
	</div>
</body>
</html>