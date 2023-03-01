<!DOCTYPE html>
<html>

<?php $this->load->view('v_header'); ?>

<body class="drawer drawer--left">

	<?php $this->load->view('v_widget_fb');?>

	<?php $this->load->view('v_top_navigation');?>

	<?php $this->load->view('v_pagination');?>

	<div class="container container-testimonial">
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-12">
					<p>
						Posted By : Admin
						<br>
						Post Date : <?php echo (!empty($article)) ? date('d M Y',strtotime($article[0]['article_created'])) : '' ; ?>
					</p>
				</div>
				<hr>
				<div class="col-xs-12">
					<h2>
						<?php echo (!empty($article)) ? strtoupper($article[0]['article_title']) : '' ; ?>
					</h2>
					
					<br>
					
					<img src="<?php echo (!empty($article)) ? $article[0]['article_image'] : '' ; ?>" width="100%">
					
					<br><br>
					
					<p>
						<?php echo (!empty($article)) ? html_entity_decode($article[0]['article_description']) : '' ; ?>
					</p>
					
					<br><br>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('v_footer');?>
</body>
</html>