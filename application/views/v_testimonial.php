<!DOCTYPE html>
<html>
<?php $this->load->view('v_header');?>
<body class="drawer drawer--left">
	<?php $this->load->view('v_widget_fb');?>
	<?php $this->load->view('v_top_navigation');?>
	<?php $this->load->view('v_pagination');?>
	<div class="container container-testimonial">
		<h3 class="container-testimonial-title">TESTIMONIAL</h3>
		<div class="row">
			<?php 
			if(!empty($testimonial)){
				foreach($testimonial as $index => $value){

					list($width, $height) = getimagesize($value['testimonial_image']);
					if($width > $height){
						$addclass = 'lanscape';
					}else{
						$addclass = '';
					}

					echo '
					<div class="col-sm-6 col-md-4 testimonial-box">
						<a class="inner-testimonial-box" href="'.$value['testimonial_image'].'" data-lightbox="roadtrip" data-title="" style="background-image:url(\''.$value['testimonial_image'].'\')">
							<img src="'.$value['testimonial_image'].'" alt="..." class="img-thumbnail testimonial-img js-lightBox '.$addclass.'" data-group="group-1">
						</a>
					</div>
					';
				}
			}
			?>
		</div>
	</div>


	<?php $this->load->view('v_footer');?>
	<script>
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		})
	</script>
</body>
</html>