<!DOCTYPE html>

<html>

<?php $this->load->view('v_header'); ?>

<style type="text/css">
	.ui.popup {
		overflow: auto;
	}

	.datepicker-inline table tbody tr td.day.disabled.red-date {
		color: red !important;
	}
	
	.legend.grey.add-red{
		color: red !important;
	}
</style>

<body class="drawer drawer--left">

	<?php $this->load->view('v_widget_fb'); ?>

	<?php $this->load->view('v_top_navigation'); ?>

	<?php $this->load->view('v_pagination'); ?>

	<section class="main-layout product">
		<div class="container">
			<?php if (!empty($product)) {
				echo '<div class="row">';
				echo '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 wrapper-box-info-product no-padding-r-lg no-padding-r-sm">';
				echo '<input type="hidden" name="prod" value="' . $product[0]['product_id'] . '">';
				echo '<div class="box-info-product blue">';
				echo '<span>' . $product[0]['product_kode'] . '</span>';
				echo '<h2 class="title">' . $product[0]['product_nama'] . '</h2>';
				echo '</div>';
				echo '</div>';
				echo '<div class="col-lg-3 col-md-6 col-sm-6 col-6 wrapper-box-info-product no-padding-lr-lg no-padding-lr-sm">';
				echo '<div class="box-info-product green">';
				echo '<span>HARGA SEWA (3 HARI)</span>';
				echo '<h2 class="title">Rp ' . number_format($product[0]['product_hargasewa']) . '</h2>';
				echo '</div>';
				echo '</div>';
				echo '<div class="col-lg-3 col-md-6 col-sm-6 col-6 wrapper-box-info-product no-padding-l-lg no-padding-l-sm">';
				echo '<div class="box-info-product pink">';
				echo '<span>DEPOSIT</span>';
				echo '<h2 class="title">Rp ' . number_format($product[0]['product_deposit']) . '</h2>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			?>
				<div class="row">
					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 wrapper-box-detail-product">
						<?php
						if (!empty($category_id)) {
							foreach ($category_id as $index => $value) {
								echo '<input type="hidden" name="cat[]" value="' . $value . '">';
							}
						}

						if (!empty($category_slug)) {
							foreach ($category_slug as $index => $value) {
								if (is_array($value) && !empty($value)) {
									foreach ($value as $key => $row) {
										echo '<input data-categories="' . $index . '" type="hidden" name="cat_slug[]" value="' . $row . '">';
									}
								}
							}
						}
						?>
						<?php if (!empty($product_image)) {
							echo '<div class="product-slide">';
							foreach ($product_image as $index => $value) {
								$file_img = 'assets/images/no-thumbnail.png';
								if (file_exists(urldecode($value['product_image']))) {
									$file_img = $value['product_image'];
									$file_img = str_replace('upload/thumbnail/images', 'upload/images', $file_img);
								}
								echo '<div class="wrapper-product-slide">';
								echo '<img class="img-fluid" src="' . $file_img . '">';
								echo '</div>';
							}
							echo '</div>';

							$count = count($product_image);

							if ($count > 1) {
								echo '<div class="product-slide-nav">';
								foreach ($product_image as $index => $value) {
									$file_img = 'assets/images/no-thumbnail.png';
									if (file_exists(urldecode($value['product_image']))) {
										$file_img = $value['product_image'];
									}
									echo '<div class="wrapper-product-slide-nav">';
									echo '<img class="img-fluid" src="' . $file_img . '">';
									echo '</div>';
								}
								echo '</div>';
							}
						} else {
							echo '<div class="product-slide">';
							echo '<div class="wrapper-product-slide no-img">';
							echo '<img class="img-fluid" src="assets/images/no-thumbnail.png">';
							echo '</div>';
							echo '</div>';
						} ?>

						<?php if (!empty($product_sizestock)) {
							echo '<div class="subtitle fancy desktop"><span>ESTIMASI UKURAN</span></div>';
							echo '<div class="content-detail desktop">';
							echo '<ul>';
							foreach ($product_sizestock as $index => $value) {
								$set = $value["product_stock"];
								echo '<li class="dashed">' . $value['product_size'] . ' (' . $set . ' Set)' . '</li>';
								echo '<li>' . $value['product_estimasiukuran'] . '</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						?>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 wrapper-box-detail-product">

						<?php
						if (!empty($isipaket)) {
							echo '<div class="subtitle fancy"><span>ISI PAKET</span></div>';
							echo '<div class="content-detail">';
							$isipaket = explode("\n", $isipaket);
							if (is_array($isipaket)) {
								foreach ($isipaket as $index => $value) {
									echo '<li>' . $value . '</li>';
								}
							}
							echo '</div>';
						}
						?>

						<?php if (!empty($product_sizestock)) {
							echo '<div class="subtitle fancy mobile"><span>ESTIMASI UKURAN</span></div>';
							echo '<div class="content-detail mobile">';
							echo '<ul>';
							foreach ($product_sizestock as $index => $value) {
								$sets = $value['product_stock'];
								echo '<li class="dashed">' . $value['product_size'] . ' (' . $sets . ' Set)' . '</li>';
								echo '<li>' . $value['product_estimasiukuran'] . '</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						?>

						<div class="subtitle fancy"><span class="calendar">CALENDAR AVAILABILITY</span></div>
						<div class="content-detail">
							<form>
								<div class="form-group form-advanced-search filter-size-detail">

									<?php if (!empty($product_sizestock)) {
										$no = 1;
										$subtitle 		= $product_sizestock[0]['product_size'];
										foreach ($product_sizestock as $index => $value) {
											$checked 	= '';
											$radiosize 	= '';
											// add not empty $size stock condition
											if (!empty($sizestock) && $value['product_sizestock_id'] == $sizestock) {
												$checked 	= 'checked';
												$radiosize 	= '<input type="hidden" name="radio_size" value="' . $value['product_sizestock_id'] . '">';
												$subtitle   = $value['product_size'];
											}
											if (empty($sizestock) && $no == 1) {
												echo '<input type="hidden" name="radio_size" value="' . $value['product_sizestock_id'] . '">';
												echo '<div class="radio">
												<label><input checked type="radio" name="radio_size_product" data-href="' . base_url('product/' . $uri . '/' . $value['product_sizestock_slug'] . '" value="' . $value['product_sizestock_id']) . '">' . $value['product_size'] . '</label></div>';
											} else {
												echo $radiosize;
												echo '<div class="radio">
												<label><input ' . $checked . ' type="radio" name="radio_size_product" value="' . $value['product_sizestock_id'] . '" data-href="' . base_url('product/' . $uri . '/' . $value['product_sizestock_slug']) . '">' . $value['product_size'] . '</label>
											</div>';
											}
											$no++;
										}
									} ?>
								</div>
							</form>
						</div>

						<?php if (!empty($product_sizestock)) {
							echo '<div class="subtitle fancy"><span class="subtitle-datepicker">' . $subtitle . '</span></div>';
						} ?>
						<div class="content-detail wrapper-datepicker">
							<div class="ui segment">
								<div class="ui inverted dimmer">
									<div class="ui text loader">Loading</div>
								</div>
								<p></p>
							</div>
							<div class="main-calendar"></div>
							<div class="legend-calendar">
								<p class="title">LEGEND</p>
								<div class="color-legend">
									<div class="legend grey">UNAVAILABLE</div>
									<div class="legend grey add-red">FULLY BOOKED</div>
									<div class="legend triangle">PARTIALLY BOOKED</div>
									<div class="legend pink">FULLY BOOKED</div>
									<div class="legend green-light">AVAILABLE</div>
								</div>
								<div><span class="required"><i>Click on date to check stock availability</i></span></div>
							</div>
						</div>
					</div>
				</div>

				<div class="row" style="visibility: hidden;">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper-box-detail-product">
						<div class="subtitle fancy subtitle-product-detail hide sugges"><span>YOU MAY ALSO LIKE</span></div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper-box-detail-product wrapper-box-sugges-product">
						<div class="ui segment">
							<div class="ui inverted dimmer">
								<div class="ui text loader">Loading</div>
							</div>
							<p></p>
						</div>
						<div class="wrapper-sugges-slide"></div>
					</div>
				</div>

				<div class="row" style="visibility: hidden;">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper-box-detail-product">
						<div class="subtitle fancy subtitle-product-detail hide related"><span>RELATED PRODUCT</span></div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper-box-detail-product wrapper-box-related-product">
						<div class="ui segment">
							<div class="ui inverted dimmer">
								<div class="ui text loader">Loading</div>
							</div>
							<p></p>
						</div>
						<div class="wrapper-related-slide"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper-additional">

						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="nav-additional-tab" data-toggle="tab" href="#nav-additional" role="tab" aria-controls="nav-additional" aria-selected="true">Additional Information</a>
							</div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-additional" role="tabpanel" aria-labelledby="nav-additional-tab">
								<table class="additional table table-striped">
									<tbody>
										<?php if (!empty($category)) {
											echo '<tr>';
											echo '<th scope="row">CATEGORY</th>';
											echo '<td>' . $category . '</td>';
											echo '</tr>';
										} ?>
										<?php if (!empty($gender)) {
											echo '<tr>';
											echo '<th scope="row">GENDER</th>';
											echo '<td>' . $gender . '</td>';
											echo '</tr>';
										} ?>
										<?php
										// if(!empty($store_location)){
										// 	echo '<tr>';
										// 	echo '<th scope="row">STORE LOCATION</th>';
										// 	echo '<td>'.$store_location.'</td>';
										// 	echo '</tr>';
										// } 
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>



	<?php $this->load->view('v_footer'); ?>

</body>

</html>
