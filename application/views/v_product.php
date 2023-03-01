<!DOCTYPE html>



<html class="drawer-sidebar drawer--left--sidebar">



<?php $this->load->view('v_header');?>

<body class="drawer drawer--left">

	<?php $this->load->view('v_widget_fb');?>
	
	<?php $this->load->view('v_top_navigation');?>

	<?php $this->load->view('v_pagination');?>



	<section class="main-layout">

		<div class="container-fluid">

			<div class="row">

				<?php $this->load->view('v_sidebar_category');?>



				<div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 main-left-content blurring">

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="loader"></div>
						</div>
						<?php

						echo '</div>';

						echo '<div class="row" id="item-list-product"></div>';
						?>

						<div class="row">

							<div class="col-lg-12 col-md-12 col-xs-12">
								<div id="pagination-product"></div>
							</div>

						</div>

					</div>

				</div>

			</div>	

		</div>

	</section>



	<?php $this->load->view('v_footer');?>

</body>

</html>