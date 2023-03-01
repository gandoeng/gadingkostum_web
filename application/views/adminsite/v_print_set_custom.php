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
    table tbody td{
        padding: 5px !important;
      }
    p{
      margin: 0;
    }
  </style>
</head>
<body>
	<div class="content-wrapper content-invoice">
		<section class="content">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-print-12">
          <div class="title"><strong>Set Custom : <?php echo $periode;?></strong></div>
          <?php 
          echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
          echo '<thead>';
          echo '<tr>';
          echo '<th>Nama</th>';
          echo '<th style="width:80px;">Tanggal</th>';
          echo '<th style="width:150px;">Product Nama/Kode</th>';
          echo '<th style="width:150px;">Product Size</th>';
          echo '<th>Note</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';
          if(!empty($get)){
            foreach($get as $index => $value){
              $countrowspan = 0;
              $rowspan      = '';
              if(isset($value['items']) && !empty($value['items'])){
                $countrowspan = count($value['items']) + 1;
                $rowspan      = 'rowspan="'.$countrowspan.'"';
              }
              echo '<tr>';  
              echo '<td '.$rowspan.'>'.$value['karyawan_nama'].'</td>';
              echo '<td '.$rowspan.'>'.date('d-m-Y',strtotime($value['created'])).' '.date('h:i A',strtotime($value['created'])).'</td>';
              if(isset($value['items']) && !empty($value['items'])){
                foreach($value['items'] as $key => $row){

                  $noted  = array();
                  $note   = explode("\n",$row['note']);
                  if(is_array($note) && !empty($note)){
                    foreach($note as $k => $r){
                      $noted[] = '<p>'.$r.'</p>';
                    }
                  }

                  echo '<tr>';
                  echo '<td style="text-align: left !important;">'.$row['product_nama'].' / '.$row['product_kode'].'</td>';
                  echo '<td style="text-align: left !important;">'.$row['product_size'].'</td>';
                  echo '<td style="text-align: left !important;">'.implode(" ",$noted).'</td>';
                  echo '</tr>';

                }
              }
              echo '</tr>';
            }
          }
          echo '</tbody>';
          echo '</table>';
          ?>
        </div>
      </div>
    </section>
  </div>
</body>
</html>