<script type="text/javascript">
	
	var table_el 		= '#' + $('base').data('table');
	var ajax_data_table;
	var dataTableJson   = [];
	
	$(function(){ 

		var url = $('base').attr('href');

		$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
			$($.fn.dataTable.tables(true)).DataTable()
			.columns.adjust()
			.responsive.recalc();
		});

		<?php

		if(isset($datatables_roworder) && $datatables_roworder != FALSE){

			if(is_array($datatables_roworder) && !empty($datatables_roworder)){

				foreach($datatables_roworder as $index => $value){

					echo $value;

				}

			} else {

				echo $datatables_roworder;

			}

		}

		if(isset($datatables_ajax_data) && $datatables_ajax_data != FALSE){

			if(is_array($datatables_ajax_data) && !empty($datatables_ajax_data)){

				foreach($datatables_ajax_data as $index => $value){

					echo $value;

				}

			} else {

				echo $datatables_ajax_data;

			}

		}

		?>

	});
</script>