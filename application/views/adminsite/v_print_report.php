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
							echo '<th>No</th>';
							echo '<th>Product Name</th>';
							echo '<th>SKU</th>';
							echo '<th>Total Rented</th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							$no = 1;
							foreach($data as $index => $value){
							echo '<tr>';
							echo '<td>'.$no.'</td>';
							echo '<td>'.$value['product_nama'].'</td>';
							echo '<td>'.$value['product_kode'].'</td>';
							echo '<td>'.$value['rented'].'</td>';
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