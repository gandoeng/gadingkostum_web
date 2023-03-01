<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajaxprod extends CI_Controller{

	public function __construct()

	{

		parent::__construct();

		$this->load->model('front_model');
		$this->load->model('global_model');
		$this->load->library('pagination');
		$this->load->library('categories_menu_lib');
	}

	public function relatedproduct(){

		$result = array('message' => '');
		echo json_encode($result);
		exit;

		if($this->input->is_ajax_request()){
			$product_id 	= $this->input->post('product_id',true);
			$category_id 	= $this->input->post('category_id',true);
			$category_slug 	= $this->input->post('category_slug',true);

			if(isset($category_slug['product'])){
				foreach($category_slug['product'] as $index => $value){
				//if($index > 1 || $index > 0){
					//unset($category_slug['product'][$index]);
				//}
				}
			}

			if(isset($category_slug['size'])){
				foreach($category_slug['size'] as $index => $value){
					if($index > 1){
						unset($category_slug['size'][$index]);
					}
				}
			}
		//$product_id 	= '11';//$this->input->post('prod_id',true);
		//$category_id 	= array(24,19,47,49,8,9);
			$query 			= $this->front_model->get_product_related($product_id,$category_id,$category_slug);
			$template 		= '';
			$result 		= array(
				'flag' 		=> false,
				'template' 	=> $template
				);

			if(!empty($query)){
				$template .= '<div class="related-slide">';
				foreach($query as $index => $value){
					$product_sizestock = $this->global_model->select_where('product_sizestock',array('product_id' => $value['product_id']));
					if(!empty($product_sizestock)){
						foreach($product_sizestock as $key => $row){
							$query[$index]['product_sizestock'][$key] = array('product_size' => $row['product_size'],'product_sizestock_slug' => $row['product_sizestock_slug']);
						}
					}

					$product_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));
					$image 	 = 'assets/images/no-thumbnail.png';
					if(!empty($product_image)){
						if(file_exists($product_image[0]['product_image'])){
							$image   = $product_image[0]['product_image'];
						} else {
							$image 	 = 'assets/images/no-thumbnail.png';
						}
					}
					$query[$index]['product_image'] = $image;
				}

				foreach($query as $index => $value){
					$template .= '<a href="'.base_url('product/').$value['product_slug'].'" class="product-items">';
					$template .= '<div class="image-content">';
					$template .= '<div class="image" style="background-image: url('.$value['product_image'].');"></div>';
					$template .= '</div>';
					$template .= '<div class="content">';
					$template .= '<p class="title">'.$value['product_nama'].'</p>';
					$template .= '<p class="title">'.$value['product_kode'].'</p>';
					$template .= '<p class="price">Rp '.number_format($value['product_hargasewa']).'</p>';
					if(isset($value['product_sizestock']) || !empty($value['product_sizestock'])){

						$sizestock = array();
						foreach($value['product_sizestock'] as $key => $row){
							$sizestock[] = $row['product_size'];
						}

						if(!empty($sizestock)){
							$sizestock = implode(", ", $sizestock);
							$template .=  '<span class="available-size">AVAILABLE SIZE:</span>';
							$template .=  '<p class="size">'.$sizestock.'</p>';
						}
					}
					$template .= '</div>';
					$template .= '</a>';
				}
				$template .= '</div>';

				$result 		= array(
					'flag' 		=> true,
					'template' 	=> $template
					);

			} 
		} else {
			$result = array('message' => '');
		}

		echo json_encode($result);

	}

	public function calendarproduct(){

		if($this->input->is_ajax_request()){
			$id							= $this->input->post('product_id',true);
			$product_sizestock_id 		= $this->input->post('product_sizestock_id',true);
			// $return_order         		= $this->global_model->select('return_order');
			// echo '<pre>';print_r(['message' => '']);echo '</pre>';
			// die;
			$day_after_return     		= $this->global_model->select_where('setting',array('setting_name' => 'day_after_return'));
			$current_date 				= date('Y-m-d');
			$result     				= array();
			$template   				= '';
			$i 							= 0;
			$available_stock 			= 0;
			$filter_order 				= array();
			$nonexist_filter_order 		= array();
			$rental_order_id_from_return= array();

			$current_date 				= date('m/d/Y');
			$check_late_rental_date 	= date('m/d/Y',strtotime('+1 day'));
			$data['product'] 			= array();
			$data['product_sizestock'] 	= array();
			$periode 					= array();

			$json_data 				    = array();
			$query_product = $this->front_model->get_product_by_id_all($id);

			$template 		= '';
			$result 		= array(
				'flag' 		=> false,
				'template' 	=> $template,
				'data'		=> $json_data
				);

			if(!empty($query_product)){
				foreach($query_product as $index => $value){
					$data['product'][] = $value;
					$data['product_sizestock'] 	= $this->global_model->select_where('product_sizestock',
						array(
							'product_id' => $value['product_id'],
							'product_sizestock_id' => $product_sizestock_id
							)
						);
				}

				if(!empty($data['product_sizestock'])){

					$query_db_rental_order 	= $this->front_model->get_all_rental_order_by_id($product_sizestock_id)->result_array();
					$no = 0;
					$rental_in_return       = array();
					$rental_in_returndate   = array();
					foreach($query_db_rental_order as $index => $value){
						if((int)$day_after_return && !empty($value['rental_return_date'])){
							$rental_in_return[]                              = $value['rental_order_id'];
							$rental_in_returndate[$value['rental_order_id']] = array(
								'rental_return_date'       => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_return_date']))),
								'before_take_date'  => date('Y-m-d',strtotime('-'.$day_after_return[0]['setting_value'].' day', strtotime($current_date))),
								'after_take_date'   => date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day', strtotime($value['rental_end_date'])))
								);
						}
					}

					$product                = array();
					$product_already_return = array();
					$sum_qty = 0;
					foreach($query_db_rental_order as $index => $value){

						$product[$value['product_sizestock_id']][$value['rental_order_id']]  = $value;
						$quantity               = (int) $value['rental_product_qty'];
						if(in_array($value['rental_order_id'],$rental_in_return)){

							$rental_order_id        = $value['rental_order_id'];
							$rental_product_qty     = $value['rental_product_qty'];
							$product_sizestock_id   = $value['product_sizestock_id'];

							// jika return date dibawah atau sama dengan before take date
							// jika after take date kurang dari current date
							// jika return date dibawah after take date
							if($rental_in_returndate[$rental_order_id]['rental_return_date'] <= $rental_in_returndate[$rental_order_id]['before_take_date'] || $rental_in_returndate[$rental_order_id]['after_take_date'] < $current_date || $rental_in_returndate[$rental_order_id]['rental_return_date'] < $rental_in_returndate[$rental_order_id]['after_take_date']){
								$product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = $value['rental_product_qty'];
							} else {
								$product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = 0;
							}

							$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $value['product_stock'];

						} elseif(!in_array($value['rental_order_id'],$rental_in_return) && !empty($rental_order_id)) {

							$product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = 0;
							$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $value['product_stock'];

						} else {

							$product[$value['product_sizestock_id']][$value['rental_order_id']]['return_qty'] = 0;
							$product[$value['product_sizestock_id']][$value['rental_order_id']]['rental_qty'] = $value['product_stock'];
						}
					}
				}

				$periode = array();
				$tes_ = array();
				$tes__ = array();

				if(!empty($product)){
					foreach($product as $index => $value){

						foreach($value as $key => $row){
							$date_periode 	= $this->date_range_function($row['rental_start_date'],$row['rental_end_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');

							if(isset($row['rental_return_date']) && !empty($row['rental_return_date'])){
								$date_periode 	= $this->date_range_function($row['rental_start_date'],$row['rental_return_date'],'m/d/Y','+0 day','+'.$day_after_return[0]['setting_value'].' day');
							}

							if(!empty($row['rental_order_id']) && empty($row['rental_return_date'])){
								$periode[$row['rental_order_id']] = array(
									'product_stock'			=> $row['product_stock'],
									'product_order'			=> $row['rental_qty'],
									'periode'				=> $date_periode,
									'rental_product_qty' 	=> $row['rental_product_qty'],
									'rental_start_date'		=> date('m/d/Y',strtotime($row['rental_start_date'])),
									'rental_end_date'		=> date('m/d/Y',strtotime($row['rental_end_date'])),
									'rental_return_date'	=> ''
									);
							} else {
								$periode[$row['rental_order_id']] = array(
									'product_stock'			=> $row['product_stock'],
									'product_order'			=> $row['product_stock'] - $row['return_qty'],
									'periode'				=> $date_periode,
									'rental_product_qty' 	=> $row['rental_product_qty'],
									'rental_start_date'		=> date('m/d/Y',strtotime($row['rental_start_date'])),
									'rental_end_date'		=> date('m/d/Y',strtotime($row['rental_end_date'])),
									'rental_return_date'	=> date('m/d/Y',strtotime('+1 day', strtotime($row['rental_return_date']))),
									);
							}
						}
					}
				}

				foreach($periode as $index => $value){

					$rental_start_date 	= $value['rental_start_date'];
					$rental_end_date 	= $value['rental_end_date'];
					$return_date 		= '';

					foreach($value['periode'] as $key => $row){

					$tes_[$row][] 	= $value['rental_product_qty']; //hitung total sum
					$sum_all  		= array_sum($tes_[$row]);

					if(!empty($value['rental_return_date'])){
						$return_date = date('m/d/Y',strtotime($value['rental_return_date']));
					}

					if(strtotime($row) <= strtotime($return_date)){
						$tes__[$row][] = array(
							'rental_return_date' 		=> $return_date,
							'product_stock' 	=> $value['product_stock'],
							'rental_product_qty'=> $value['rental_product_qty'],
							'rental_start_date' => $value['rental_start_date'],
							'rental_end_date' 	=> $value['rental_end_date']
							);
					} else {
						$tes__[$row][] = array(
							'rental_return_date' 		=> '',
							'product_stock' 	=> $value['product_stock'],
							'rental_product_qty'=> $value['rental_product_qty'],
							'rental_start_date' => $value['rental_start_date'],
							'rental_end_date' 	=> $value['rental_end_date']
							);
					}	
				}
			}

			$total_qty_order_per_day = array();
			$filter_per_day 		 = array();

			if(!empty($tes__)){
				foreach($tes__ as $index => $value){
					$date 	= date('m/d/Y',strtotime($index));
					$sum 	= 0;
					foreach($value as $key => $row){
						if(isset($total_qty_order_per_day[$date])){
							$total_qty_order_per_day[$date]+=$row['rental_product_qty'];
						} else {
							$total_qty_order_per_day[$date]=$row['rental_product_qty'];
						}
					}	
				}
			}

			if(!empty($total_qty_order_per_day)){
				foreach($tes__ as $index => $value){
					$day_date 	= date('m/d/Y',strtotime($index));
					$total_qty 	= $total_qty_order_per_day[$index];
					foreach($value as $key => $row){
						$total_qty_order_per_day[$index] 						= $row;
						$total_qty_order_per_day[$index]['total_qty'] 			= $total_qty;
						$total_qty_order_per_day[$index]['status'] 				= '';
						$total_qty_order_per_day[$index]['rental_late_date'] 	= '';
					}

					foreach($value as $key => $row){
						$late_date 		= date('m/d/Y',strtotime('+1 day', strtotime($row['rental_end_date'])));

						if(strtotime($late_date) <= strtotime($current_date) && empty($row['rental_return_date'])){
							$total_qty_order_per_day[$index]['status'] 				= 'late';
							$total_qty_order_per_day[$index]['rental_late_date'] 	= $late_date;

						}
					}
				}
			}

			$data_calendar = array();
			$data_calendar['date'] 	= array();
			$data_calendar['stock'] = array();
			$data_calendar['rental']= array();
			$data_calendar['class']	= array();

			$count 		= 0;
			$tes_late_display = '';

			if(!empty($total_qty_order_per_day)){
				$rental_product_qty_late = array();
				foreach($total_qty_order_per_day as $index => $values){

					$stock      = $values['product_stock'];
					$total_qty  = $values['total_qty'];
					$class      = 'default available';

					$late_date   = date('Y-m-d',strtotime('+1 day',strtotime($values['rental_end_date'])));

					$count      = $stock - $total_qty;

					if($values['status'] == 'late'){
						$tambahan   = $this->date_range_function(date('Y-m-d',strtotime('+1 day',strtotime($values['rental_late_date']))),date('Y-m-d',strtotime('+'.$day_after_return[0]['setting_value'].' day',strtotime($current_date))),'m/d/Y');
						$rental_product_qty_late = $values['rental_product_qty'];
					}
				}

				foreach($total_qty_order_per_day as $index => $values){

					if($values['status'] == 'late'){
                    		// late date
						if(is_array($tambahan) && !empty($tambahan)){
							foreach($tambahan as $key => $row){	
								if($index == $row){
									$qty_tambah = $values['rental_product_qty'];

									if(isset($rental_product_qty_late[$row])){
										$qty_tambah = $total_qty + $rental_product_qty_late[$row];
									}

									$total_qty_order_per_day[$row] = array(
										'date'              => $row,
										'total_qty'         => $values['total_qty'],
										'product_stock'     => $stock,
										'rental_start_date' => $values['rental_start_date'],
										'rental_end_date'   => $values['rental_end_date'],
										'rental_late_date'	=> $row,
										'status'			=> $values['status']
										);
								} else {
									$total_qty_order_per_day[$row] = array(
										'date'              => $row,
										'total_qty'         => $values['total_qty'],
										'product_stock'     => $stock,
										'rental_start_date' => $values['rental_start_date'],
										'rental_end_date'   => $values['rental_end_date'],
										'rental_late_date'	=> $row,
										'status'			=> $values['status']
										);
								} 
							}
						}
					}
				}

				foreach($total_qty_order_per_day as $index => $value){

					$stock      = $value['product_stock'];
					$total_qty  = $value['total_qty'];
					$class      = 'default available';

					$late_date   = date('m/d/Y',strtotime('+1 day',strtotime($value['rental_end_date'])));

					$count      = $stock - $total_qty;

					if($count <= 0 && empty($value['rental_late_date']) && $value['status'] == ''){
						$class = 'full';
					} elseif($count > 0 && empty($value['rental_late_date'])){
						$class = 'partially';
					} elseif($count > 0 && !empty($value['rental_late_date']) && $value['status'] == 'late'){
						$class = 'late partially';
					} elseif($count <= 0 && !empty($value['rental_late_date']) && $value['status'] == 'late'){
						$class = 'late full';
					}

					$format_datepicker = date('n/j/Y',strtotime($index));
					$data_calendar['class'][$format_datepicker]     = $class;
					$data_calendar['date'][$format_datepicker]      = $format_datepicker;
					$data_calendar['stock'][$format_datepicker]     = $stock;
					$data_calendar['rental'][$format_datepicker]    = $total_qty;

					$day = date('m/d/Y',strtotime($index));
					$json_data[$day] = array(
						'date'      => $day,
						'available' => $count,
						'stock'     => $stock,
						);
					$json_data['default'] = array(
						'available' => $stock,
						'stock'     => $stock
						); 
				}

			} else {

				if(!empty($data['product_sizestock'])){
					foreach($data['product_sizestock'] as $index => $value){
						$stock = $value['product_stock'];
						$json_data['default'] = array(
							'available' => $stock,
							'stock' 	=> $stock
							); 
					}
				} else {
					$json_data['default'] = array(
						'available' => 0,
						'stock' 	=> 0
						); 
				}
			}
			$data_calendar['class']	= array_values($data_calendar['class']);
			$data_calendar['date'] 	= array_values($data_calendar['date']);
			$data_calendar['stock'] = array_values($data_calendar['stock']);
			$data_calendar['rental']= array_values($data_calendar['rental']);

			$data_date	= '';
			$data_stock	= '';
			$data_rental= '';
			$data_class	= '';
			$template   = '';

			if(!empty($data_calendar['date'])){
				$data_date 		= implode(",",$data_calendar['date']);
			}
			if(!empty($data_calendar['stock'])){
				$data_stock 	= implode(",",$data_calendar['stock']);
			}
			if(!empty($data_calendar['rental'])){
				$data_rental 	= implode(",",$data_calendar['rental']);
			}
			if(!empty($data_calendar['class'])){
				$data_class 	= implode(",",$data_calendar['class']);
			}

			$template = '<div id="calendar" class="date" data-class="'.$data_class.'" data-stock="'.$data_stock.'" data-rental="'.$data_rental.'" data-date="'.$data_date.'"></div>';

			$result 		= array(
				'flag' 		=> true,
				'template' 	=> $template,
				'data'		=> $json_data,
				'product_sizestock' => $data['product_sizestock']
				);
		}
	} else {
		$result = array('message' => '');
	}
	echo json_encode($result);
	
}

public function suggesproduct(){

	$result = array('message' => '');
	echo json_encode($result);
	exit;

	if($this->input->is_ajax_request()){
		$product_id 	= $this->input->post('product_id',true);
		$query 			= $this->front_model->get_product_sugges($product_id);
		$template 		= '';
		$result 		= array(
			'flag' 		=> false,
			'template' 	=> $template
			);

		if(!empty($query)){
			$template .= '<div class="sugges-slide">';
			foreach($query as $index => $value){
				$product_sizestock = $this->global_model->select_where('product_sizestock',array('product_id' => $value['product_id']));
				if(!empty($product_sizestock)){
					foreach($product_sizestock as $key => $row){
						$query[$index]['product_sizestock'][$key] = array('product_size' => $row['product_size'],'product_sizestock_slug' => $row['product_sizestock_slug']);
					}
				}

				$product_image = $this->global_model->select_where('product_image',array('product_id' => $value['product_id']));
				$image 	 = 'assets/images/no-thumbnail.png';
				if(!empty($product_image)){
					if(file_exists($product_image[0]['product_image'])){
						$image   = $product_image[0]['product_image'];
					} else {
						$image 	 = 'assets/images/no-thumbnail.png';
					}
				}
				$query[$index]['product_image'] = $image;
			}

			foreach($query as $index => $value){
				$template .= '<a href="'.base_url('product/').$value['product_slug'].'" class="product-items">';
				$template .= '<div class="image-content">';
				$template .= '<div class="image" style="background-image: url('.$value['product_image'].');"></div>';
				$template .= '</div>';
				$template .= '<div class="content">';
				$template .= '<p class="title">'.$value['product_nama'].'</p>';
				$template .= '<p class="title">'.$value['product_kode'].'</p>';
				$template .= '<p class="price">Rp '.number_format($value['product_hargasewa']).'</p>';
				if(isset($value['product_sizestock']) || !empty($value['product_sizestock'])){
					$sizestock = array();
					foreach($value['product_sizestock'] as $key => $row){
						$sizestock[] = $row['product_size'];
					}

					if(!empty($sizestock)){
						$sizestock = implode(", ", $sizestock);
						$template .=  '<span class="available-size">AVAILABLE SIZE:</span>';
						$template .=  '<p class="size">'.$sizestock.'</p>';
					}
				}
				$template .= '</div>';
				$template .= '</a>';
			}
			$template .= '</div>';

			$result 		= array(
				'flag' 		=> true,
				'template' 	=> $template
				);

		}
	} else {
		$result = array('message' => '');
	}
	echo json_encode($result);

}

function date_range_function($first, $last, $output_format = 'd/m/Y', $delay_first = '+0 day', $delay_last = '+0 day') {
	$step 				= '+1 day';
	$dates 				= array();
	$first 				= date('Y-m-d',strtotime($delay_first, strtotime($first)));
	$current 			= strtotime($first);
		//$last 				= strtotime($last);
	$last 				= date('Y-m-d',strtotime($delay_last, strtotime($last)));
	$last 				= strtotime($last);
	while( $current <= $last ) {
		$dates[] = date($output_format, $current);
		$current = strtotime($step, $current);
	}

	return $dates;
}

}
?>