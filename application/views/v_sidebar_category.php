<style type="text/css">
	.categories-sidebar{
		touch-action: auto !important;
	}
	.drawer--left--sidebar.drawer-open{
		    overflow: auto !important;
	}
	.drawer--left--sidebar .categories-sidebar .ui-widget-content{
		padding: 0;
	}
	body{
		overflow-y: auto !important;
		overflow-x: auto !important;
		overflow: auto !important;
		height: auto !important;
	}
	.filter-wrapper{
		background-color: inherit;
	}
	@media (max-width: 767px) {
		.filter-wrapper{
			position: sticky;
			bottom: 0;
			padding: 1rem;
			text-align: center;
		}
	}
</style>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 categories-sidebar drawer-menu-sidebar">

<button type="button" class="drawer-toggle-sidebar drawer-hamburger drawer-hamburger-sidebar">

	<span class="sr-only">toggle navigation</span>

	<span class="drawer-hamburger-icon"></span>

	<p class="drawer-text" style="padding-top: 4px; position:relative; top: -4px;">FILTER</p>

</button>
		<div class="ui-widget-content">
			<div class="px-3 pt-3 px-md-0 pt-md-0">
		<?php 

		$get_start_date = '';
		$get_end_date 	= '';

		(isset($start_date) && !empty($start_date)) ? $get_start_date = $start_date : $get_start_date = '';
		(isset($end_date) && !empty($end_date)) ? $get_end_date = $end_date : $get_end_date = '';
		
		if(!empty($menu) && isset($menu['categories'])){
			echo '<div class="categories-heading"><h2>CATEGORIES</h2></div>';
			echo '<div class="ui styled accordion">';
			echo $menu['categories'];
			echo '</div>';
			echo '<div class="divider-categories"></div>';
		}

		if(!empty($menu) && isset($menu['gender'])){
			echo '<div class="categories-heading"><h2>GENDER</h2></div>';
			echo '<div class="ui styled accordion">';
			echo $menu['gender'];
			echo '</div>';
			echo '<div class="divider-categories"></div>';
		}

		if(!empty($menu) && isset($menu['size'])){
			echo '<div class="categories-heading"><h2>SIZE</h2></div>';
			echo '<div class="ui styled accordion">';
			echo $menu['size'];
			echo '</div>';
			echo '<div class="divider-categories"></div>';
		}

		// if(!empty($menu) && isset($menu['store_location'])){
		// 	echo '<div class="categories-heading"><h2>STORE LOCATION</h2></div>';
		// 	echo '<div class="ui styled accordion">';
		// 	echo $menu['store_location'];
		// 	echo '</div>';
		// 	echo '<div class="divider-categories"></div>';
		// }
		echo '<div class="categories-heading"><h2>AVAILABLE DATE</h2></div>';
		echo '<div class="ui styled accordion">';
		echo '<input type="text" value="'.$get_start_date.'" class="datepicker-start form-control" readonly="true" style="margin-top: 5px;" placeholder="Available Start Date">';
		echo '</div>';
		echo '<div class="ui styled accordion">';
		echo '<input type="text" value="'.$get_end_date.'" class="datepicker-end form-control" readonly="true" style="margin-top: 5px;" placeholder="Available End Date">';
		echo '</div>';
		echo '<div class="divider-categories"></div>';
		echo '</div>';
		echo '<div class="filter-wrapper">';
		echo '<button type="button" class="reset-filter btn btn-primary btn-gradient purple d-none hide">RESET FILTER</button>';
		echo '<button type="button" class="do-filter btn btn-primary btn-gradient purple">FILTER</button>';
		echo '</div>';
		?>
	</div>
</div>

<!-- <script type="text/javascript">
	$(document).ready(function(){

	  // jQuery methods go here...
		$(".datepicker-start").datepicker({
			changeMonth: true,
            minDate: '-1Y',

        });

	});
</script> -->