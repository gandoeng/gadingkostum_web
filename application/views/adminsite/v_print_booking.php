<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/AdminLTE.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/skins/_all-skins.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/style.css').'?v05_07_2019';?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/print.css').'?v05_07_2019';?>">
	<style type="text/css">
		@media all {
			.page-break	{ display: none; }
			thead tr th { vertical-align: middle!important; }
		}
	</style>
</head>
<body>
	<div class="content-wrapper content-invoice">
		<section class="content">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-print-12">
					<?php 
						if(!empty($data)){
							echo '<table class="table table-bordered table-report">';
							echo '<thead>';
							echo '<tr>';
							echo '<th style="width: 60px;">Inv#</th>';
							echo '<th>Name + Phone</th>';
							echo '<th style="width: 200px; !important;">Items</th>';
							echo '<th>Date Order</th>';
							echo '<th>Start Date</th>';
							echo '<th>Return Date</th>';
							echo '<th>Status</th>';
							echo '<th>Remaining Days</th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							$no = 1;
							foreach($data as $index => $value){
							echo '<tr>';
							echo '<td>'.$value['rental_invoice'].'</td>';
							echo '<td style="width: 40px;"><p style="padding:0;margin:0; width: 60px;">'.$value['customer'].'</p></td>';
							echo '<td>'.$value['items'].'</td>';
							echo '<td>'.$value['rental_created'].'</td>';
							echo '<td>'.$value['rental_start_date'].'</td>';
							echo '<td>'.$value['rental_end_date'].'</td>';
							echo '<td>'.$value['rental_status'].'</td>';
							echo '<td>'.$value['remaining'].'</td>';
							echo '</tr>';
							$no++;
							}
							echo '</tbody>';
							echo '</table>';
						} else {
							echo '<div class="text-center">';
							echo '<p> No data available</p>';
							echo '</div>';
						}
					?>
				</div>
			</div>
		</section>
	</div>
</body>
</html>