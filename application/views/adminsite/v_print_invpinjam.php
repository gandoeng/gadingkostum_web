<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/AdminLTE.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/skins/_all-skins.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/style.css').'?v1.1';?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/print.css').'?'.md5(date('c'));?>">
	<style type="text/css">
		.payment p{
			margin: 0;
		}
		.payment p:last_child{
			margin-bottom: 5px;
		}
		@media all {
			.page-break	{ display: none; }
		}
		@media print{
			.payment p{
				margin: 0;
			}
			.payment p:last_child{
				margin-bottom: 5px;
			}
		}
	</style>
</head>
<body>
	<div class="content-wrapper content-invoice">
		<section class="content">
			<div class="row">

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-print-12">
					<div class="box box-solid">
						<div class="box-body">
							<section class="invoice invoice-print">

								<div class="row invoice-info">
									<div class="col-sm-12 col-print-12 invoice-col page-title text-center">
										<h4 class="text-center">PEMINJAMAN KOSTUM INVOICE NOMOR : <?php echo $rental_order[0]['rental_invoice'];?></h4>
									</div>
									<div class="col-sm-6 col-print-6 invoice-col" style="font-size: 11px;">
										<address style="font-size: 11px;">
											<strong style="font-size: 11px;">Gading Kostum Menyewakan Kostum Anak & Dewasa</strong><br>
											<?php if(!empty($store_address)){
												echo $store_address.'<br>';
											} ?>
											Website : www.gadingkostum.com, <i class="fa fa-instagram"></i> : gadingkostum<br>
											Telpon : (021) 4584 3087, WA : 0813 53570168<br>
											Cabang: Kelapa Gading
										</address>
									</div>

									<div class="col-sm-6 col-print-6 invoice-col text-right" style="position:relative;">
										<!-- <img style="position:fixed; top: 0; right: 0; width: 170px; " class="img-responsive" src="<?php echo base_url('assets/images/logo-245x162.png');?>"> -->
										<img style="width: 120px; margin: 0 0 0 auto !important;" class="img-responsive" src="<?php echo base_url('assets/images/logo-tes-print.png');?>">
									</div>
								</div>

								<div class="row">
									<?php if(isset($rental_order) && !empty($rental_order)){

										if($rental_order[0]['delivery_option'] == 'sendiri') {
											$delivery = 'Diambil sendiri';
										} else if($rental_order[0]['delivery_option'] == 'gojek') {
											$delivery = 'Gojek';
										} else if($rental_order[0]['delivery_option'] == 'jne') {
											$delivery = 'JNE';
										} else {
											$delivery = $rental_order[0]['delivery_option'];
										}

										echo '<div class="col-sm-7">
										<table class="box-information">
											<tr>
												<td class="box-border border grey"><span>Nama</span></td>
												<td class="box-border border">'.$rental_order[0]['customer_name'].'</td>
											</tr>

											<tr>
												<td class="box-border border grey">Nomor Telp</td>
												<td class="box-border border">'.$rental_order[0]['customer_phone'].'</td>
											</tr>

											<tr>
												<td class="box-border border grey">Alamat</td>
												<td class="box-border border">'.$rental_order[0]['customer_address'].'</td>
											</tr>

											<tr>
												<td class="box-border border grey">Delivery</td>
												<td class="box-border border">'.$delivery.'</td>
											</tr>
										</table>
									</div>';

									echo '<div class="col-sm-5">

									<table class="box-information">
										<tr>
											<td class="box-border border grey">Tanggal Cetak</td>
											<td class="box-border border">'.date('d M Y').'</td>
										</tr>

										<tr>
											<td class="box-border border grey">Tanggal Sewa - Pengembalian</td>
											<td class="box-border border">'.date('d M Y',strtotime($rental_order[0]['rental_start_date'])).' - '.date('d M Y',strtotime($rental_order[0]['rental_end_date'])).'</td>
										</tr>
									</table>';
									} ?>
								</div>
							</section>
						</div>

						<div class="row">
							<div class="col-xs-12 col-print-12">
								<table class="box-information box-product table-break">
									<thead>
										<tr>
											<th class="box-border border grey"><strong>No</strong></th>
											<th class="box-border border grey"><strong>Nama Produk</strong></th>
											<th class="box-border border grey"><strong>Kode</strong></th>
											<th class="box-border border grey"><strong>Isi Paket</strong></th>
											<th class="box-border border grey"><strong>Size</strong></th>
											<th class="box-border border grey"><strong>Qty</strong></th>
											<th class="box-border border grey"><strong>Harga Sewa</strong></th>
											<th class="box-border border grey"><strong>Harga Deposit</strong></th>
										</tr>
									</thead>
									<tbody>
										<?php if(isset($rental_product) && !empty($rental_product)){
											$no = 1;
											foreach($rental_product as $index => $value){
												$isipaket = nl2br($value['rental_product_isipaket']);
												$isipaket = trim($isipaket);
												echo '<tr>
												<td class="box-border border" style="width: 40px;">'.$no.'</td>
												<td class="box-border border" style="width: 150px;">'.$value['rental_product_nama'].'</td>
												<td class="box-border border">'.$value['rental_product_kode'].'</td>
												<td class="box-border border">'.$isipaket.'</td>
												<td class="box-border border">'.$value['rental_product_size'].'</td>
												<td class="box-border border" style="width: 40px;">'.$value['rental_product_qty'].'</td>
												<td class="box-border border" style="width: 100px;">Rp. '.number_format($value['rental_product_hargasewa']).'</td>
												<td class="box-border border" style="width: 100px;">Rp. '.number_format($value['rental_product_deposit']).'</td>
											</tr>';
											$no++;
											}
										}?>
									</tbody>
									<tbody>
										<?php 
										$total_extrapayment_sewa 	= 0;
										$total_extrapayment_deposit = 0;
										if(isset($rental_extrapayment) && !empty($rental_extrapayment)){
											foreach($rental_extrapayment as $index => $value){
												echo '<tr>';
												echo '<td class="box-border border" style="width: 40px;">'.$no.'</td>';
												echo '<td colspan="4" align="left" class="box-border border" style="text-align: left;">'.html_entity_decode($value['rental_extranote']).'</td>';
												echo '<td class="box-border border"><input type="hidden"></td>';
												if($value['rental_extrapayment_flag'] == 'sewa'){
													$total_extrapayment_sewa+=$value['rental_extrapayment'];
													echo '<td class="box-border border">Rp. '.number_format($value['rental_extrapayment']).'</td>';
													echo '<td class="box-border border"></td>';
												} elseif($value['rental_extrapayment_flag'] == 'deposit'){
													$total_extrapayment_deposit+=$value['rental_extrapayment'];
													echo '<td class="box-border border"></td>';
													echo '<td class="box-border border">Rp. '.number_format($value['rental_extrapayment']).'</td>';
												}
												echo '</tr>';
												$no++;
											}
										}
										?>
									</tbody>
								<tbody>
									<?php if(isset($rental_order) && !empty($rental_order)){
									$total_sewa    = $rental_order[0]['rental_total_hargasewa']+$total_extrapayment_sewa;
									$total_deposit = $rental_order[0]['rental_total_deposit']+$total_extrapayment_deposit;
									$total 		   = $total_sewa + $total_deposit;
									echo '<tr>
										<td colspan="5" rowspan="5" style="vertical-align:top; width: 150px; text-align:left !important;"><p style="text-align:left !important;"><strong>Note:</strong> '.html_entity_decode($rental_order[0]['rental_note']).'</p></td>
										<td colspan="2" class="box-border border grey">Total Harga Sewa</td>
										<td class="box-border border">Rp. '.number_format($total_sewa).'</td>
									</tr>
									<tr>
										<td colspan="2" class="box-border border grey">Total Deposit</td>
										<td class="box-border border">Rp. '.number_format($total_deposit).'</td>
									</tr>
									<tr>
										<td colspan="2" class="box-border border grey">Total Sewa + Deposit</td>
										<td class="box-border border">Rp. '.number_format($total).'</td>
									</tr>';
									}?>
								</tbody>
							<tbody>
							<tr class="break-it">
								<table class="box-information box-product">
									<thead>
										<tr>
											<th class="box-border border"><strong>Konfirmasi Booking</strong></th>
											<th class="box-border border"><strong>Terima Uang Sewa</strong></th>
											<th class="box-border border"><strong>Terima Kostum</strong></th>
											<th class="box-border border"><strong>Terima Uang Deposit</strong></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="box-border border"><input type="text" class="form-control invoice-signature" name="rental_konfirmasi_booking"><p class="placeholder-signature">Penyewa</p></td>
											<td class="box-border border box-payment">
												<input type="text" class="form-control invoice-signature" name="rental_terima_uangsewa">
												<div class="payment">
													<?php 
														if(!empty($terima_bayarsewa)){
															foreach($terima_bayarsewa as $index => $value){
																echo '<p>'.date('d M Y',strtotime($value['jenis_transaksi_created'])).' - '.$value['jenis_transaksi_flag'].' - Rp.'.number_format($value['jenis_transaksi_nominal']).'</p>';
															}
														}
													?>
												</div>
												<p class="placeholder-signature">Petugas</p>
											</td>
											<td class="box-border border"><input type="text" class="form-control invoice-signature" name="rental_terima_kostum"><p class="placeholder-signature">Penyewa</p></td>
											<td class="box-border border box-payment">
												<input type="text" class="form-control invoice-signature" name="rental_terima_uangdeposit">
												<div class="payment">
													<?php 
														if(!empty($terima_bayardeposit)){
															foreach($terima_bayardeposit as $index => $value){
																echo '<p>'.date('d M Y',strtotime($value['jenis_transaksi_created'])).' - '.$value['jenis_transaksi_flag'].' - Rp.'.number_format($value['jenis_transaksi_nominal']).'</p>';
															}
														}
													?>
												</div>
												<p class="placeholder-signature">Petugas</p>
											</td>
										</tr>
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-invoice table-signature sk-print">
									<tr>
										<td>
											<h6><strong>Syarat & Ketentuan:</strong></h6>
											<?php
												if(!empty($invoice_footer)){
													echo html_entity_decode($invoice_footer[0]['setting_value_textarea']);
												}
											?>
										</td>
									</tr>
								</table>
							</tr>
							</tbody>
						</table>
					</div>
				</div>

			</section>
		</div>
	</div>
</div>
</div>
</section>
</div>

</body>
</html>