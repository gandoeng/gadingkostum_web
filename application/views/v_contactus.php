<!DOCTYPE html>

<html>

<?php $this->load->view('v_header');?>

<body class="drawer drawer--left">

	<?php $this->load->view('v_widget_fb');?>

	<?php $this->load->view('v_top_navigation');?>
	
	<?php $this->load->view('v_pagination');?>

	<section id="content-wrapper">

	<div class="container-fluid">
		<div class="row">
				<div id="map"></div>
			</div>
	</div>

	<div class="container container-map">
		<div class="row d-flex justify-content-center">
			<div class="col-lg-6 col-md-12 sm-12 map-img" style="border: 0;">
				<span class='zoom' id='ex1'>
					<img class="img-fluid" src="<?php echo base_url("assets/images/map-gading-now.jpg");?>" alt="Peta Gading Kostum" srcset="">
				</span>
			</div>

		
			<div class="col-lg-6 col-md-12 sm-12 d-none">
			
				<form class="form-filter-product" id="form-contact-us" action="<?php echo base_url('contact_us/submit'); ?>" method="post">
					<h2 class="title-contact">Contact Us</h2>
					<br/>
					<div id="alert"></div>
					<div class="form-group form-advanced-search">
						<input type="text" name="name" placeholder="Name" class="form-control border-with-color blue-border">
					</div>
					<div class="form-group form-advanced-search">
						<input type="text" name="email" placeholder="Email" class="form-control border-with-color blue-border">
					</div>
					<div class="form-group form-advanced-search">
						<input type="text" name="subject" placeholder="Subject" class="form-control border-with-color blue-border">
					</div>
					<div class="form-group form-advanced-search">
						<textarea class="form-control border-with-color blue-border" name="message" placeholder="Message" rows="7"></textarea>
					</div>
					<button type="submit" class="btn btn-primary btn-gradient green pull-right mb-3 submit" id="btn-save">SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
	

	
	</section>




	<?php $this->load->view('v_footer');?>

	<!-- GOOGLE MAPS JS -->
	<script>
		// Initialize and add the map
		function initMap() {
			// The location of Uluru
			var uluru = {lat: -6.148205,lng:  106.91339500000004, };
			// The map, centered at Uluru
			var map = new google.maps.Map(
				document.getElementById('map'), {zoom:19, center: uluru});
			// The marker, positioned at Uluru
			var marker = new google.maps.Marker({position: uluru, map: map});

			var direction = 'https://www.google.co.id/maps/dir//Jl.+Raya+Klp.+Hybrida+Blok+PF+21+No.8,+Pegangsaan+Dua,+Klp.+Gading,+Kota+Jkt+Utara,+Daerah+Khusus+Ibukota+Jakarta+14250/@-6.1482442,106.9113494,17z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x2e698ab4a109f1eb:0x5fca755b9f50c5d5!2m2!1d106.9135381!2d-6.1482442';

			infowindow  = new google.maps.InfoWindow({
						 		content:'<strong>GADING KOSTUM</strong><p style="text-align:center;"><br><a href="'+direction+'" target="_blank">Get Direction</a></p>'
						 	  });

			google.maps.event.addListener(marker, 'click', function(){
				infowindow.open(map,marker);
			});

			infowindow.open(map,marker);
		}
	</script>
	
	<!-- FORM JS -->
	<script>
		
		$('#form-contact-us').submit(function(){
			var _this = $(this);
			$.ajax({
				method 	: 'POST',
				data 	: _this.serializeArray(),
				url 	: _this.attr('action'),
				beforeSend : function(){
					_this.find('.submit').prop('disabled', true).addClass('.disabled').html('<i class="fa fa-refresh fa-spin"></i>');
				},
				success : function(response){
					var _resp = $.parseJSON(response);

					if(_resp.status == 1){
						_this.find('#alert').html('<div class="alert alert-success">Success ' + _resp.message + '</div>');

					}else{
						var _tmp_error = '';
						$.each(_resp.error, function(index, value){
							_tmp_error += '<p>&bull; '+ value +'</p>';
						});
						_this.find('#alert').html('<div class="alert alert-danger">' + _tmp_error + '</div>');
					}

					_this.find('.submit').prop('disabled', false).removeClass('.disabled').html('SUBMIT');
				},
				complete : function(){

				},
				error : function(){

				}
			});
			return false;

			/*
			$('#alert').html("	<div class='alert alert-success alert-dismissible fade show' role='alert'> <strong>Success!</strong> Your Message Have Been Successfully Sent <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div> ");

			$("#form-contact-us")[0].reset();
			*/


			
		})

	
	</script>


	<!-- JQuery ZOOM  -->
	<script>
		$(document).ready(function(){
				//$('#ex1').zoom({magnify: 1,on: 'click'});
		});
	
	</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMh-UbEM4XRYWpjdQPFG0lw9RV73LRBo4&callback=initMap">
</script>

</body>

</html>