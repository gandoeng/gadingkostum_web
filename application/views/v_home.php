<!DOCTYPE html>
<html>
<?php $this->load->view('v_header'); ?>
<style type="text/css">
	.embed-player {
		left: 0px;
		position: absolute;
		top: 0;
		width: 100%;
		height: 100%;
	}
</style>

<body class="drawer drawer--left" ontouchstart="">
	<?php $this->load->view('v_widget_fb'); ?>
	<?php $this->load->view('v_top_navigation'); ?>
	<?php $this->load->view('v_pagination'); ?>

	<section id="banner" class="section-banner desktop">

		<div class="container-fluid no-padding-lr-lg no-padding-lr-sm">

			<div class="row no-margin-lr-lg no-margin-lr-sm">

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding-lr-lg no-padding-lr-sm">

					<div class="bg-banner">

						<div class="main-bg-banner">

							<img src="<?php echo base_url('assets/images/banner1.png'); ?>" class="img-fluid">

						</div>

						<div class="content-banner wow bounceInUp">

							<div class="main-content-banner">

								<div id="filltext-title">

									<span class="title">SEWA KOSTUM ANAK & DEWASA</span>

								</div>

								<div id="filltext-desc">

									<span class="description">Berbagai jenis Kostum kami sediakan untuk memenuhi semua kebutuhan Anda. Dari kostum superhero, pakaian adat, pakaian negara, pakaian profesi, binatang dan lain-lain tersedia di tempat kami. Butik yang luas dan nyaman menambah kemudahan memilih kostum yang diinginkan.</span>

								</div>

							</div>

						</div>



						<div class="count-content-banner">

							<div class="row no-margin-lr-lg no-margin-lr-sm">

								<div class="col-lg-3 col-md-4 circle-count wow bounceIn" data-wow-delay=".5s">

									<div class="list pink">

										<p><span>150</span><span>&nbsp;m<sup>2</sup></span></p>

										<p>RUKO 3 LANTAI</p>

									</div>

								</div>



								<div class="col-lg-3 col-md-4 circle-count wow bounceIn" data-wow-delay=".5s">

									<div class="list green">

										<p><span><?php echo number_format($jenis_kostum); ?></span></p>

										<p>JENIS KOSTUM</p>

									</div>

								</div>



								<div class="col-lg-3 col-md-4 circle-count wow bounceIn" data-wow-delay=".5s">

									<div class="list blue">

										<p><span><?php echo number_format($pcs_kostum); ?></span></p>

										<p>PCS KOSTUM</p>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="hero-banner wow bounceIn">

						<div class="hero">

							<img src="<?php echo base_url('assets/images/banner2.png'); ?>" class="img-fluid">

						</div>

						<div class="circle-banner wow bounceIn">

							<img src="<?php echo base_url('assets/images/banner-tes1.png'); ?>" class="img-fluid">

						</div>

					</div>

				</div>

			</div>

		</div>

	</section>



	<section class="section-banner mobile">

		<div class="count-content-banner container no-padding-lr-lg no-padding-lr-sm wow bounceIn">

			<div class="row no-margin-lr-lg no-margin-lr-sm">

				<div class="col-lg-12 col-md-12 col-sm-12 wow bounceIn" data-wow-delay=".5s">
					<div class="main-content-banner">

						<div id="filltext-title">

							<span class="title">SEWA KOSTUM</span>
							<span class="title">ANAK & DEWASA</span>

						</div>

						<div id="filltext-desc">

							<span class="description">Berbagai jenis Kostum kami sediakan untuk memenuhi semua kebutuhan Anda. Dari kostum superhero, pakaian adat, pakaian negara, pakaian profesi, binatang dan lain-lain tersedia di tempat kami. Butik yang luas dan nyaman menambah kemudahan memilih kostum yang diinginkan.</span>

						</div>

					</div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 circle-count wow bounceIn" data-wow-delay=".5s">

					<div class="list pink">

						<p><span>150</span><span>&nbsp;m<sup>2</sup></span></p>

						<p>RUKO 3 LANTAI</p>

					</div>

				</div>



				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 circle-count wow bounceIn" data-wow-delay=".5s">

					<div class="list green">

						<p><span><?php echo number_format($jenis_kostum); ?></span></p>

						<p>JENIS KOSTUM</p>

					</div>

				</div>



				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 circle-count wow bounceIn" data-wow-delay=".5s">

					<div class="list blue">

						<p><span><?php echo number_format($pcs_kostum); ?></span></p>

						<p>PCS KOSTUM</p>

					</div>

				</div>

			</div>

		</div>

	</section>


	<?php if (!empty($slideshow)) { ?>
		<section id="slideshow">

			<div class="container">

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding-lr-lg no-padding-lr-sm">

					<div class="slideshow-main">
						<?php foreach ($slideshow as $index => $value) {

							$slideshow_flag = $value['slideshow_flag'];
							$template 		= '';
							$check_image    = false;
							$image 	 	= 'assets/images/no-thumbnail.png';

							if ($slideshow_flag == 'image') {

								if (file_exists($value['slideshow_image'])) {
									$check_image = true;
								}
							}
							if ($check_image) {

								$image   = $value['slideshow_image'];
							} else {

								$image 	 = 'assets/images/no-thumbnail.png';
							}

							switch ($slideshow_flag) {
								case 'youtube':
									$template = '<div class="slideshow-items"><div class="wrapper-image">
							<iframe class="embed-player slide-media" src="https://www.youtube.com/embed/' . $value['slideshow_image'] . '?enablejsapi=1&controls=0&fs=0&iv_load_policy=3&rel=0&showinfo=0&loop=1&playlist=' . $value['slideshow_video_id'] . '" frameborder="0" allowfullscreen></iframe>
						</div></div>';
									break;
								case 'image':
									$template = '<div class="slideshow-items">
						<div class="wrapper-image">
							<div class="image" style="background-image: url(' . base_url() . $image . ')"></div>
						</div>
					</div>';
									break;
							}
							echo $template;
						} ?>
					</div>

				</div>

				<div class="line-color">

					<div class="line-item"></div>

					<div class="line-item"></div>

					<div class="line-item"></div>

					<div class="line-item"></div>

					<div class="line-item"></div>

					<div class="line-item"></div>

				</div>

			</div>

		</section>
	<?php } ?>


	<?php if (!empty($featured_product)) { ?>
		<section class="product-home section-content">

			<div class="container">

				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

						<h1 class="title-section color-purple with-border">Featured Product</h1>

						<div class="title-section-border bg-pink-light"></div>

					</div>

				</div>



				<div class="row row-content">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<div class="product-home-slide">

							<?php foreach ($featured_product as $index => $value) {

								echo '<a href="' . base_url('product/') . $value['product_slug'] . '" class="product-items">';

								echo '<div class="image-content">';

								echo '<div class="image" href="' . base_url('product/') . $value['product_slug'] . '" style="background-image: url(' . $value['product_image'] . ');"></div>';

								echo '</div>';

								echo '<div class="content">';

								echo '<p class="title">' . $value['product_nama'] . '</p>';
								echo '<p class="title">' . $value['product_kode'] . '</p>';
								echo '<p class="price">Rp ' . number_format($value['product_hargasewa']) . '</p>';

								if (isset($value['product_sizestock']) || !empty($value['product_sizestock'])) {

									$sizestock = array();
									foreach ($value['product_sizestock'] as $key => $row) {
										$sizestock[] = $row['product_size'];
									}

									if (!empty($sizestock)) {
										$sizestock = implode(", ", $sizestock);
										echo '<span class="available-size">AVAILABLE SIZE:</span>';
										echo '<p class="size">' . $sizestock . '</p>';
									}
								}

								echo '</div>';

								echo '</a>';
							} ?>

						</div>

					</div>

				</div>



			</div>

		</section>
	<?php } ?>


	<section class="gender-home section-content">

		<div class="container-fluid">

			<div class="row">

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 no-padding-lr gender-home-list">

					<div class="gender-home-image">

						<a href="<?php echo base_url('categories/1?gender=men'); ?>" class="image" style="background-image: url(<?php echo base_url('assets/images/Men.jpg'); ?>);"></a>

					</div>

					<div class="title bg-purple">

						<a href="<?php echo base_url('categories/1?gender=men'); ?>" class="title-link">
							<p>MEN</p>
						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 no-padding-lr gender-home-list">

					<div class="gender-home-image">

						<a href="<?php echo base_url('categories/1?gender=women'); ?>" class="image" style="background-image: url(<?php echo base_url('assets/images/Women.jpg'); ?>);"></a>

					</div>

					<div class="title bg-pink">

						<a href="<?php echo base_url('categories/1?gender=women'); ?>" class="title-link">
							<p>WOMEN</p>
						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 no-padding-lr gender-home-list">

					<div class="gender-home-image">

						<a href="<?php echo base_url('categories/1?gender=boys'); ?>" class="image" style="background-image: url(<?php echo base_url('assets/images/Boys.jpg'); ?>);"></a>

					</div>

					<div class="title bg-blue-light">

						<a href="<?php echo base_url('categories/1?gender=boys'); ?>" class="title-link">
							<p>BOY</p>
						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 no-padding-lr gender-home-list">

					<div class="gender-home-image">

						<a href="<?php echo base_url('categories/1?gender=girls'); ?>" class="image" style="background-image: url(<?php echo base_url('assets/images/Girls.jpg'); ?>);"></a>

					</div>

					<div class="title bg-pink-light">

						<a href="<?php echo base_url('categories/1?gender=girls'); ?>" class="title-link">
							<p>GIRL</p>
						</a>

					</div>

				</div>

			</div>

		</div>

	</section>



	<?php if (!empty($latest_product)) { ?>

		<section class="product-home section-content">

			<div class="container">

				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

						<h1 class="title-section color-green with-border">Latest Product</h1>

						<div class="title-section-border bg-pink-light"></div>

					</div>

				</div>



				<div class="row row-content">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<div class="product-home-slide">

							<?php

							if (!empty($latest_product)) {

								foreach ($latest_product as $index => $value) {

									echo '<a href="' . base_url('product/') . $value['product_slug'] . '" class="product-items">';

									echo '<div class="image-content">';

									echo '<div class="image" href="' . base_url('product/') . $value['product_slug'] . '" style="background-image: url(' . $value['product_image'] . ');"></div>';

									echo '</div>';

									echo '<div class="content">';

									echo '<p class="title">' . $value['product_nama'] . '</p>';
									echo '<p class="title">' . $value['product_kode'] . '</p>';
									echo '<p class="price">Rp ' . number_format($value['product_hargasewa']) . '</p>';

									if (isset($value['product_sizestock']) || !empty($value['product_sizestock'])) {

										$sizestock = array();
										foreach ($value['product_sizestock'] as $key => $row) {
											$sizestock[] = $row['product_size'];
										}

										if (!empty($sizestock)) {
											$sizestock = implode(", ", $sizestock);
											echo '<span class="available-size">AVAILABLE SIZE:</span>';
											echo '<p class="size">' . $sizestock . '</p>';
										}
									}

									echo '</div>';

									echo '</a>';
								}
							} ?>

						</div>

					</div>

				</div>



			</div>

		</section>
	<?php }  ?>


	<?php if (!empty($popular_category)) { ?>
		<section class="popular-home section-content">

			<div class="container">

				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

						<h1 class="title-section color-blue-light with-border">POPULAR CATEGORY</h1>

						<div class="title-section-border bg-yellow"></div>

					</div>

				</div>



				<div class="row row-content">

					<?php

					foreach ($popular_category as $index => $value) {

						$image 	 = 'assets/images/no-thumbnail.png';

						if (file_exists(urldecode($value['product_category_picture']))) {
							$check_image = true;
						} else {
							$check_image = false;
						}

						if ($check_image) {

							$image   = $value['product_category_picture'];
						} else {

							$image 	 = 'assets/images/no-thumbnail.png';
						}

						echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 popular-home-list">

					<div class="popular-home-image">

						<a href="' . base_url('categories/1?') . $value['category_flag'] . '=' . $value['category_slug'] . '" class="image" style="background-image: url(' . $image . ');"></a>

					</div>

					<div class="title">

						<a href="' . base_url('categories/1?') . $value['category_flag'] . '=' . $value['category_slug'] . '" class="title-link"><p>' . strtoupper($value['category_name']) . '</p></a>

					</div>

				</div>';
					}

					?>

				</div>

			</div>

		</section>
	<?php } ?>


	<?php if (!empty($popular_theme)) { ?>
		<section class="popular-theme-home section-content">

			<div class="container">

				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

						<h1 class="title-section color-pink with-border">POPULAR THEME</h1>

						<div class="title-section-border bg-green"></div>

					</div>

				</div>



				<div class="row row-content">



					<?php

					foreach ($popular_theme as $index => $value) {

						$image 	 = 'assets/images/no-thumbnail.png';

						if (file_exists(urldecode($value['product_category_picture']))) {
							$check_image = true;
						} else {
							$check_image = false;
						}

						if ($check_image) {

							$image   = $value['product_category_picture'];
						} else {

							$image 	 = 'assets/images/no-thumbnail.png';
						}

						echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 popular-home-list">

				<div class="popular-theme-image">

					<a href="' . base_url('categories/1?') . $value['category_flag'] . '=' . $value['category_slug'] . '" class="image" style="background-image: url(' . $image . ');"></a>

				</div>

			</div>';
					}

					?>

				</div>

			</div>

		</section>
	<?php } ?>

	<?php if (!empty($article)) { ?>
		<section class="latest-news-home section-content">

			<div class="container">

				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

						<h1 class="title-section color-pink-light with-border">LATEST NEWS</h1>

						<div class="title-section-border bg-blue-light"></div>

					</div>

				</div>

				<div class="row row-content">

					<!-- DNY -->
					<?php foreach ($article as $key => $row) {
						$date = date('d M Y', strtotime($row['article_created']));
						$exdate = explode(' ', $date); ?>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 list-latest-news-home">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="latest-news-image">
										<a href="<?php echo base_url() . $row['article_slug']; ?>" class="image" style="background-position:left; background-size: cover; background-image: url(<?php echo base_url($row['article_image']); ?>);"></a>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 latest-news-content-wrapper">
									<div class="row">
										<div class="col-lg-2 col-md-3 col-sm-2 col-xs-2 col-2 list-content calendar-news-wrapper no-padding-r-lg no-padding-r-sm">
											<div class="calendar-news date bg-pink"><?php echo $exdate[0]; ?></div>
											<div class="calendar-news month bg-green"><?php echo strtoupper($exdate[1]); ?></div>
										</div>
										<div class="col-lg-10 col-md-9 col-sm-10 col-xs-10 col-10 list-content content">
											<a href="<?php echo base_url() . $row['article_slug']; ?>" class="title-link"><?php echo strtoupper($row['article_title']); ?></a>
											<div class="content">
												<?php echo html_entity_decode($row['article_description_thumbnail']); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

				</div>

			</div>

		</section>
	<?php } ?>


	<?php $this->load->view('v_footer'); ?>

</body>

</html>
