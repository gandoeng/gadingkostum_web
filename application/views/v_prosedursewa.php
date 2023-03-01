<!DOCTYPE html>



<html>



<?php $this->load->view('v_header');?>



<body class="drawer drawer--left">

	<?php $this->load->view('v_widget_fb');?>

	<?php $this->load->view('v_top_navigation');?>

	<?php $this->load->view('v_pagination');?>

	<div class="container container_sewa">
		<div class="row justify-content-center">
			<div class="col-12"><h3>PROSEDUR PENYEWAAN KOSTUM</h3><br/></div>
		</div>
			
		<?php
		if(!empty($content)){
			echo $content['page_description'];
		}
		?>
		<!--<div class="row">
			<div class="col-12 col-md-6 sewa-box sewa-box-1">
				<div class='sewa-inner'>
					<p class="title"><span>1</span>Cara memilih kostum</p>
					<p>Datang langsung dan memilih pada toko kami. <a target="blank" href="https://www.google.co.id/maps/dir//Jl.+Raya+Klp.+Hybrida+Blok+PF+21+No.8,+Pegangsaan+Dua,+Klp.+Gading,+Kota+Jkt+Utara,+Daerah+Khusus+Ibukota+Jakarta+14250/@-6.1482442,106.9113494,17z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x2e698ab4a109f1eb:0x5fca755b9f50c5d5!2m2!1d106.9135381!2d-6.1482442">Klik</a> untuk lihat peta lokasi atau buka website <a target="blank" href="http://www.gadingkostum.com">www.gadingkostum.com</a> untuk melihat semua koleksi kami</p>
					<div class="bg_characters"></div>
					<!-- <img src="<?php echo base_url('assets/images/preview-web-gading-kostum-E.png')?>"> --
				</div>
			</div>
			<div class="col-12 col-md-6 sewa-box sewa-box-2">
				<div class='sewa-inner'>
					<p class='title'><span>2</span>Cara menyewa Kostum</p>
					<p>Menyewa langsung di toko kami. <a target="blank" href="https://www.google.co.id/maps/dir//Jl.+Raya+Klp.+Hybrida+Blok+PF+21+No.8,+Pegangsaan+Dua,+Klp.+Gading,+Kota+Jkt+Utara,+Daerah+Khusus+Ibukota+Jakarta+14250/@-6.1482442,106.9113494,17z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x2e698ab4a109f1eb:0x5fca755b9f50c5d5!2m2!1d106.9135381!2d-6.1482442">Klik</a> untuk lihat peta lokasi atau bisa order lewat whatsapp di :</p>
					<a target="blank" href="https://api.whatsapp.com/send?phone=6281353570168"><h3>081 3535 70 168</h3></a>
					<div class="bg_characters"></div>
					<!-- <img src="<?php echo base_url('assets/images/preview-web-gading-kostum-E-2.png')?>" alt="" srcset="" style="margin-top: 19px;"> --
				</div>
			</div>
			<div class="col-12 sewa-box sewa-box-3">
				<div class="sewa-inner">
					<p class='title'><span>3</span>Aturan penyewaan Kostum</p>
					<!-- <img src="<?php echo base_url('assets/images/preview-web-gading-kostum-E-3.png')?>" alt="" srcset=""> --
					<ul>
						<li>Untuk booking / keep kostum dapat dilakukan dengan membayar  	harga sewa</li>
						<li>Lama penyewaan adalah 3 hari (hari sabtu , minggu dan libur dihitung)</li>
						<li>Denda keterlambatan pengembalian Rp. 20.000 ,- per hari per	kostum</li>
						<li>Untuk sewa online ( bukan di toko ), ongkos pengiriman menjadi tanggung jawab penyewa</li>
						<li> Uang sewa dan deposit dibayar sebelum kostum diambil / dikirim</li>
						<li>Uang deposit akan dikembalikan setelah Kostum diterima kembali	dalam kondisi baik seperti sebelumnya</li>
						<li>Apabila Kostum dikembalikan via kurir / ekspedisi , uang deposit akan di transfer kembali 1  hari sesudah kostum kami terima</li>
						<li>Kostum yang disewa mohon tidak dicuci saat dikembalikan, kami akan mencuci Kostum tersebut</li>
						<li>Uang Sewa yang sudah dibayarkan tidak bisa dikembalikan dengan  alasan apapun</li>
						<li>Resiko keterlambatan pengiriman oleh pihak ketiga ( Gojek , JNE , dll )</li>
						<li>Apabila terjadi Kerusakan / Kehilangan Kostum / aksesoris akan dikenakan biaya sesuai ongkos perbaikan kostum / aksesoris tersebut</li>
					</ul>
					<div class="bg_characters"></div>
				</div>
			</div>
			<div class="col-12 sewa-box sewa-box-4">
				<div class="sewa-inner">
					<p class='title title-yellow' style="width:53%;"><span>4</span>Cara pengambilan / pengembalian Kostum</p>
					<!-- <img src="<?php echo base_url('assets/images/preview-web-gading-kostum-E-4.png')?>" alt="" srcset=""> --
					<ul>
						<li>Kostum dapat diambil dan dikembalikan langsung di toko kami atau dapat dikirim dan dikembalikan via gojek atau JNE</li>
						<li>Harga sewa belum termasuk ongkos kirim</li>
					</ul>
					<div class="bg_characters"></div>
				</div>
			</div>
		</div>
		-->
	</div>


	<?php $this->load->view('v_footer');?>

</body>
</html>