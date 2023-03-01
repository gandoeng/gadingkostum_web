<!DOCTYPE html>
<html>
<?php $this->load->view('v_header'); ?>
<style>
	@media screen and (max-width:574px) {
		.form-advanced-search {
			width: 100% !important;
			padding-right: 0 !important;
			padding-left: 0 !important;
		}
	}
</style>

<body class="drawer drawer--left">
	<?php $this->load->view('v_widget_fb'); ?>
	<?php $this->load->view('v_top_navigation'); ?>
	<?php $this->load->view('v_pagination'); ?>
	<section id="content-wrapper" style="min-height: 100px;">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<form class="form-search-product" style="margin-top: 50px;">

						<div class="form-group form-advanced-search d-none d-lg-block">
							<?php $keywords_show = '';
							if (isset($_GET['k']) && !empty($_GET['k'])) {
								$keywords_show = html_entity_decode($_GET['k']);
								$keywords_show = str_replace("\\", "", $_GET['k']);
								$keywords_show = str_replace("||", "_", $_GET['k']);
							} ?>
							Search value for <strong><?php echo $keywords_show; ?></strong>
							<input type="hidden" value="<?php echo $keywords_show; ?>" name="k">
						</div>

						<div class="form-group form-advanced-search">

							<select style="width:100%;" name="size[]" multiple="multiple" class="size-select2 form-control border-with-color blue-border">
								<?php if (isset($categories_db['category_size']) && !empty($categories_db['category_size'])) {
									$get_selected['size'] = array();
									if (isset($_GET['size']) && !empty($_GET['size'])) {
										$get_selected['size'] = explode('||', $_GET['size']);
									}
									foreach ($categories_db['category_size'] as $index => $value) {
										$selected = '';
										if (isset($get_selected['size']) && in_array($value['category_slug'], $get_selected['size'])) {
											$selected = 'selected';
										}
										echo '<option ' . $selected . ' value="' . $value['category_slug'] . '">' . ucfirst($value['category_name']) . '</option>';
									}
								} ?>
							</select>

						</div>

						<div class="form-group form-advanced-search">

							<select style="width:100%;" name="gender[]" multiple="multiple" class="gender-select2 form-control border-with-color blue-border">

								<?php if (isset($categories_db['category_gender']) && !empty($categories_db['category_gender'])) {
									$get_selected['gender'] = array();
									if (isset($_GET['gender']) && !empty($_GET['gender'])) {
										$get_selected['gender'] = explode('||', $_GET['gender']);
									}
									foreach ($categories_db['category_gender'] as $index => $value) {
										$selected = '';
										if (isset($get_selected['gender']) && in_array($value['category_slug'], $get_selected['gender'])) {
											$selected = 'selected';
										}
										echo '<option ' . $selected . ' value="' . $value['category_slug'] . '">' . ucfirst($value['category_name']) . '</option>';
									}
								} ?>
							</select>

						</div>

						<div class="form-group form-advanced-search" style="display: none;">

							<select style="width:100%;" name="store_location[]" multiple="multiple" class="store-select2 form-control border-with-color blue-border">

								<?php
								// if(isset($categories_db['category_store']) && !empty($categories_db['category_store'])){
								// 	$get_selected['store_location'] = array();
								// 	if(isset($_GET['store_location']) && !empty($_GET['store_location'])){
								// 		$get_selected['store_location'] = explode('||',$_GET['store_location']);
								// 	}
								// 	foreach($categories_db['category_store'] as $index => $value){
								// 		$selected = '';
								// 		if(isset($get_selected['store_location']) && in_array($value['category_slug'],$get_selected['store_location'])){
								// 			$selected = 'selected';
								// 		}
								// 		echo '<option '.$selected.' value="'.$value['category_slug'].'">'.ucfirst($value['category_name']).'</option>';
								// 	}
								// } 
								?>
							</select>

						</div>

						<div class="form-group form-advanced-search date start" style="width: 49.8%;">
							<?php $get_date_start = (isset($_GET['start'])) ? date('j F Y', strtotime($_GET['start'])) : ''; ?>
							<input readonly="true" value="<?php echo $get_date_start; ?>" placeholder="Available Start Date" autocomplete="off" type="text" name="start" class="border-with-color blue-border datepicker-search-start form-control">

						</div>

						<div class="form-group form-advanced-search date end" style="width: 49.4%;">
							<?php $get_date_end = (isset($_GET['end'])) ? date('j F Y', strtotime($_GET['end'])) : ''; ?>
							<input readonly="true" value="<?php echo $get_date_end; ?>" placeholder="Available End Date" autocomplete="off" type="text" name="end" class="border-with-color blue-border datepicker-search-end form-control">

						</div>

						<div class="form-group form-advanced-search" style="display: inline-block; clear: both; width: 100%; text-align: right;">
							<button type="submit" class="btn btn-primary btn-gradient green pull-right mb-3">FILTER</button>
							<button type="button" style="display: none;" class="reset-filter btn btn-primary btn-gradient pink pull-right mb-3">RESET FILTER</button>
						</div>

					</form>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="loading-mask"></div>
				</div>
				<div id="search-list" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-xs-12">
					<div id="pagination-search-product"></div>
				</div>
			</div>
		</div>
	</section>
	<?php $this->load->view('v_footer'); ?>
</body>

</html>
