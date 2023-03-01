<section id="navbar-header">

	<div class="container">

		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<nav class="navbar navbar-expand-lg navbar-header-main drawer-nav" role="navigation">

					<div class="collapse navbar-collapse">

						<ul class="navbar-nav mx-auto drawer-menu">

							<li class="nav-item">

								<a class="nav-link" href="<?php echo base_url();?>">HOME</a>

							</li>

							<li class="nav-item">

								<a class="nav-link" href="<?php echo base_url('product');?>">PRODUCT</a>

							</li>

								<li class="dropdown nav-item">

									<a class="dropdown-toggle nav-link" href="#" id="navbarDropdown1" data-toggle="dropdown">CATEGORIES</a>

									<?php if(isset($categories_menu) && !empty($categories_menu)){

									echo $categories_menu;

								} ?>

				</li>

				<li class="nav-item">

					<a class="nav-link" href="<?php echo base_url('prosedur-sewa');?>">PROSEDUR SEWA</a>

				</li>

				<li class="nav-item">

					<a class="nav-link" href="<?php echo base_url('testimonials');?>">TESTIMONIAL</a>

				</li>

				<li class="nav-item">

					<a class="nav-link" href="<?php echo base_url('hubungi-kami');?>">LOKASI TOKO</a>

				</li>

			</ul>

		</nav>	

	</div>

</div>

</div>

</section>