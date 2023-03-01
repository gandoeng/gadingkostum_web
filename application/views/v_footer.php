<section class="footer">

	<div class="line-color">

		<div class="line-item"></div>

		<div class="line-item"></div>

		<div class="line-item"></div>

		<div class="line-item"></div>

		<div class="line-item"></div>

		<div class="line-item"></div>

	</div>

	<div class="container footer-container">

		<div class="row row-content">

			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none d-lg-block list-footer">

				<h2 class="title-section color-blue-light with-border" style="display: none;" >FACEBOOK</h2>

				<div class="title-section-border bg-yellow left title-border-footer" style="display: none;"></div>
				<div id="fb-root"></div>
				<script>
					(function(d){
						var js, id = 'facebook-jssdk';
						if (d.getElementById(id)) {return;}
						js = d.createElement('script');
						js.id = 404780996951172;
						js.async = true;
						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						d.getElementsByTagName('head')[0].appendChild(js);
					}(document));
				</script>
				<div class="content fb-container" style="display: none;">
					<div class="fb-page" data-href="https://www.facebook.com/gadingkostum" data-height="130" data-small-header="false" data-adapt-container-width="false" data-hide-cover="false" data-show-facepile="true"></div>
				</div>

			</div>
			
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 list-footer">

				<h2 class="title-section color-blue-light with-border">INSTAGRAM</h2>

				<div class="title-section-border bg-yellow left title-border-footer"></div>

				<div class="content instagram-container">
					<a href="https://www.instagram.com/gadingkostum/" target="blank">
						<img src="<?= base_url('assets/images/ig.jpg'); ?>" alt="" class="w-100">
					</a>
				</div>

			</div>


			

		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 list-footer">

			<h2 class="title-section color-blue-light with-border">CONTACT US</h2>

			<div class="title-section-border bg-yellow left title-border-footer"></div>

			<div class="content contact">
				<?php if(isset($footer_address) && !empty($footer_address)){
					if(!empty($footer_address[0]['setting_value'])){
						$text = html_entity_decode($footer_address[0]['setting_value']);
						$text = str_replace("\n","<br>",$footer_address[0]['setting_value']);
						echo '<h5 class="title first"><strong>ADDRESS:</strong></h5>';
						echo $text;
						echo '<br class="br">';
					}
				}
				?>
				<?php if(isset($footer_phone) && !empty($footer_phone)){
					if(!empty($footer_phone[0]['setting_value'])){
						$text = html_entity_decode($footer_phone[0]['setting_value']);
						echo '<h5 class="title"><strong>PHONE :</strong></h5>';
						echo $text;
						echo '<br class="br">';
					}
				}
				?>
				<?php if(isset($footer_whatsapp) && !empty($footer_whatsapp)){
					if(!empty($footer_whatsapp[0]['setting_value'])){
						$text = html_entity_decode($footer_whatsapp[0]['setting_value']);
						echo '<h5 class="title"><strong>WHATSAPP (CHAT ONLY):</strong></h5>';

						/*-- DNY --*/
						echo '<a href="https://api.whatsapp.com/send?phone=62'.str_replace(' ', '', substr($text, 1)).'"><i class="fa fa-whatsapp"></i> &nbsp;'.$text.'</a>';
						echo '<br class="br">';
					}
				}
				?>
				<?php if(isset($footer_email) && !empty($footer_email)){
					if(!empty($footer_email[0]['setting_value'])){
						$text = html_entity_decode($footer_email[0]['setting_value']);
						echo '<h5 class="title"><strong>EMAIL :</strong></h5>';
						echo $text;
						echo '<br class="br">';
					}
				}
				?>
				<br>
				<h5 class="title"><strong>WORKING DAYS/HOURS:</strong></h5>
				<p>Setiap hari jam 9 pagi - 8 malam</p>
				<p>(termasuk hari Sabtu, Minggu dan hari libur)</p>
			</div>

		</div>

	</div>

</div>

</section>



<section class="second-footer">

	<div class="container-fluid">

		<div class="row">

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 list-second-footer social-media">

				<div class="icon">

					<a target="_blank" href="https://www.facebook.com/gadingkostum/"><i class="fa fa-facebook-square fa-lg"></i></a>

				</div>

				<div class="icon">

					<a target="_blank" href="https://www.instagram.com/gadingkostum/"><i class="fa fa-instagram fa-lg"></i></a>

				</div>

			</div>



			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 list-second-footer copyright">Gading Kostum © Copyright 2017. All Rights Reserved.</div>

		</div>

	</div>

</section>

<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/popper/popper.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/bootstrap/js/bootstrap.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/loaded/imagesloaded.pkgd.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/slick/slick/slick.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/FitText/jquery.fittext.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/jquery.matchHeight.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/semantic-2.3.1/dist/semantic.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/iscroll/build/iscroll.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/drawer/dist/js/drawer.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/wow/dist/wow.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/numscroller/numscroller-1.0.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/textfill/dist/jquery.textfill.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/select2/dist/js/select2.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/icheck/icheck.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/datepicker/dist/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/url/src/URI.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/url/src/jquery.URI.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/lightbox2/dist/js/lightbox.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/zoom/jquery.zoom.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/loader/dist/app.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/history/scripts/bundled/html4+html5/jquery.history.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/pagination/jquery.simplePagination.js');?>"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/front/plugins/instalink/instalink-2.1.3.min.js');?>"></script> -->
<?php
	// $application = 'assets/front/js/application.min.js?cache=1.0';
	$timeNows = time();
	$application = 'assets/front/js/style_qa.js?cache='.$timeNows;
    if($this->uri->segment(1) == 'demo_search' || $this->uri->segment(1) == 'demo_product'){
		$application = 'assets/front/js/demo_application.min.js?cache=1.1';
    }
?>
<script type="text/javascript" src="<?php echo base_url($application);?>"></script>
<script>
	new WOW().init();
</script>
