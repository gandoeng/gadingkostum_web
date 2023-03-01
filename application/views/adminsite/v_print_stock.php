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
				padding: 0 !important;
				text-align: left !important;
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
						echo '<th>Kode</th>';
						echo '<th>Isi Paket</th>';
						echo '<th>Estimasi Ukuran</th>';
						echo '<th>Stock Availability</th>';
							//echo '<th>Status</th>';
						echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						$no = 1;
						foreach($data as $index => $value){

							$nama 		= '';
							$kode 		= '';
							$isipaket 	= '';
							$size 		= '';
							$stock 		= '';

							echo '<tr>';

							if(isset($value['product_nama']) && !empty($value['product_nama'])){
								$nama = $value['product_nama'];
							}
							echo '<td style="text-align:center !important;"><div style="padding-left: 1px;">'.$no.'</div></td>';
							echo '<td style="max-width: 70px !important;"><div style="padding-left: 1px;">'.$nama.'</div></td>';

							if(isset($value['product_kode']) && !empty($value['product_kode'])){
								$kode = $value['product_kode'];
							}
							echo '<td><div style="padding-left: 1px;">'.$kode.'</div></td>';

							if(isset($value['isipaket']) && is_array($value['isipaket']) && !empty($value['isipaket'])){
								//$isipaket = implode('<legend></legend>',$value['isipaket']);
								echo '<td style="width: 150px !important;">';
								foreach($value['isipaket'] as $key => $row){
									$checkstring = '';
									if($key == 0){
										$checkstring = $row;
										echo '<div class="isipaket group">'.$row.'</div>';
									}
									if($key > 0){
										$pos = strpos($checkstring,$row);
										if($pos == true){
											echo '<div class="isipaket group">'.$row.'</div>';
										}
									}
								}
								echo '</td>';
							} else {
								echo '<td></td>';
							}

							if(isset($value['size']) && is_array($value['size']) && !empty($value['size'])){
								//$size = implode('<legend></legend>',$value['size']);
								echo '<td>';
								foreach($value['size'] as $key => $row){
									echo '<div class="size group">'.$row.'</div>';
								}
								echo '</td>';
							} else {
								echo '<td></td>';
							}

							if(isset($value['stock']) && is_array($value['stock']) && !empty($value['stock'])){
								//$stock = implode('<legend></legend>',$value['stock']);
								echo '<td style="width: 100px !important;">';
								foreach($value['stock'] as $key => $row){
									echo '<div class="stock group">'.$row.'</div>';
								}
								echo '</td>';
							} else {
								echo '<td></td>';
							}


							//echo '<td>'.$value['product_size'].'</td>';
							//echo '<td>'.$value['stock'].'</td>';
							//echo '<td>'.$value['label_stock'].'</td>';
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