<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Front_model extends CI_Model

{

	function existSizeInPageProduct($product,$size){
		$this->db->select('product_slug,product_sizestock_id,product_sizestock_slug');
		$this->db->join('product','product.product_id = product_sizestock.product_id','left');
		$this->db->group_start();
		$this->db->or_where('product_slug',$product);
		$this->db->or_where('product_kode',$product);
		$this->db->group_end();

		$this->db->group_start();
		$this->db->or_where('product_sizestock_id',$size);
		$this->db->or_where('product_sizestock_slug',$size);
		$this->db->group_end();
		return $this->db->get('product_sizestock')->row_array();
	}
	
	function getImageProduct($product_id){
		$this->db->select('product_id,product_image');
		$this->db->where_in('product_id',$product_id);
		$this->db->group_by('product_id');
		return $this->db->get('product_image')->result_array();
	}

	function getProductSizestock($product_id){
		$this->db->select('product_id,product_size,product_stock,product_sizestock_id');
		$this->db->where_in('product_id',$product_id);
		$this->db->order_by('product_sizestock_sort','asc');
		return $this->db->get('product_sizestock')->result_array();
	}

	//julian
	function queryProductSize($product_id){
		$this->db->select('product_id,product_size,product_stock,product_sizestock_id');
		$this->db->where_in('product_id',$product_id);
		$this->db->order_by('product_id','asc');
		return $this->db->get('product_sizestock')->result_array();
	}

	function queryRentalOrderLate($product_id, $start_date, $end_date){
		$this->db->select_sum('rental_product_qty');
		$this->db->select('rental_product_id,rental_product_sizestock_id,product_id,rental_order.rental_order_id,
		rental_order.rental_start_date,rental_order.rental_end_date,rental_product_qty,rental_return_date');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where_in('rental_product.product_id',$product_id);
		// $this->db->where('rental_order.rental_end_date <=',$end_date);
		$this->db->where('rental_order.rental_start_date <=',$start_date);
		$this->db->where('rental_active',1);
		$this->db->order_by('rental_order.rental_end_date','desc');
		$this->db->group_by('rental_product_sizestock_id');
		return $this->db->get('rental_product')->result_array();
	}

	function re_update_get_return_order(){
		$this->db->select('rental_order_id,return_date');
		return $this->db->get('return_order')->result_array();
	}
	
	function re_search_sum_product($prod_id) {
		$this->db->select('product_id,product_sizestock_id,product_stock');
		$this->db->where_in('product_id',$prod_id);
		$this->db->group_by(array('product_sizestock_id','product_id'));
		return $this->db->get('product_sizestock')->result_array();
	}

	function re_search_get_rental($prod_id){
		$this->db->select_sum('rental_product_qty');
		$this->db->select('rental_product_id,rental_product_sizestock_id,product_id,rental_order.rental_order_id,
		rental_order.rental_start_date,rental_order.rental_end_date,rental_product_qty,rental_return_date');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where_in('rental_product.product_id',$prod_id);
		//$this->db->where('rental_status !=','return');
		$this->db->where('rental_active',1);
		$this->db->group_by('rental_product_id');
		return $this->db->get('rental_product')->result_array();
	}

	function re_search_get_sizestock($id){
		$this->db->select('rental_product_id,rental_order_id,rental_product_qty,rental_product_sizestock_id');
		$this->db->where_in('rental_order_id',$id);
		return $this->db->get('rental_product')->result_array();
	}

	function re_count_search_product($query){
		$this->db->select('product.product_id');
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(isset($query['k']) && !empty($query['k'])){
				$this->db->group_start();
				foreach($query['k'] as $index => $value){
					$get_key   = str_replace("\\","",html_entity_decode($value));
					$key_value = array(
						'product_nama'  => $get_key,
						'product_kode'  => $get_key,
						'category_name' => $get_key
						);
					$this->db->or_like($key_value);
				}
				$this->db->group_end();
		}	
		if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){
			if(is_array($query) && !empty($query)){
				foreach($query as $index => $value){
					if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
						$this->db->group_start();
						foreach($value as $key => $row){
							$this->db->or_group_start();
							if($index == 'product'){
								$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'size'){
								$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
								$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

							if($index == 'store_location'){
								$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
							}
							$this->db->group_end();
						}
						$this->db->group_end();
					}
				}
			}
		}
		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		$this->db->group_by('product.product_id');
		return $this->db->count_all_results('product');
	}
	
	function re_search_product($query,$limit,$start,$order_by,$date_start = '',$date_end = ''){
		$this->db->select('product.product_id,product_nama,category_name,product_hargasewa,product_slug,product_kode');
		if(!empty($order_by) && isset($order_by['order_by']) && $order_by['order_by'] == 'popularity'){
			$this->db->join('product_popularity','product_popularity.product_id = product.product_id','left');
		}
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(isset($query['k']) && !empty($query['k'])){
				$this->db->group_start();
				foreach($query['k'] as $index => $value){
					$get_key   = str_replace("\\","",html_entity_decode($value));
					$key_value = array(
						'product_nama'  => $get_key,
						'product_kode'  => $get_key,
						'category_name' => $get_key
						);
					$this->db->or_like($key_value);
				}
				$this->db->group_end();
		}	

		if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){
			if(is_array($query) && !empty($query)){
				foreach($query as $index => $value){
					if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
						$this->db->group_start();
						foreach($value as $key => $row){
							$this->db->or_group_start();
							if($index == 'product'){
								$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'size'){
								$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
								$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

							if($index == 'store_location'){
								$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
							}
							$this->db->group_end();
						}
						$this->db->group_end();
					}
				}
			}
		}

		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		if(!empty($order_by) && isset($order_by['order_by'])){
			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'price_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_hargasewa',$sorting);
			}

			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'date_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_created',$sorting);
			}

			if($order_by['order_by'] == 'asc'){
				$this->db->order_by('product_nama', 'ASC');
			}

			if($order_by['order_by'] == 'desc'){
				$this->db->order_by('product_nama', 'DESC');
			}

			if($order_by['order_by'] == 'popularity'){
				$this->db->order_by('rented', 'DESC');
				$this->db->order_by('product_created','DESC');
			}
		} else {
			$this->db->order_by('product_nama', 'ASC');
		}
		if(empty($date_start) && empty($date_end)){
			$this->db->limit($limit, $start);
		}

		$this->db->group_by('product.product_id');
		return $this->db->get('product')->result_array();
	}

	function re_count_search_product_available($query,$product_unavailable = ''){
		$this->db->select('product.product_id');
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(isset($query['k']) && !empty($query['k'])){
				$this->db->group_start();
				foreach($query['k'] as $index => $value){
					$get_key   = str_replace("\\","",html_entity_decode($value));
					$key_value = array(
						'product_nama'  => $get_key,
						'product_kode'  => $get_key,
						'category_name' => $get_key
						);
					$this->db->or_like($key_value);
				}
				$this->db->group_end();
		}	
		if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){
			if(is_array($query) && !empty($query)){
				foreach($query as $index => $value){
					if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
						$this->db->group_start();
						foreach($value as $key => $row){
							$this->db->or_group_start();
							if($index == 'product'){
								$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'size'){
								$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
								$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

							if($index == 'store_location'){
								$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
							}
							$this->db->group_end();
						}
						$this->db->group_end();
					}
				}
			}
		}
		if(!empty($product_unavailable)){
			$this->db->where_not_in('product.product_id',$product_unavailable);
		}
		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		$this->db->group_by('product.product_id');
		return $this->db->count_all_results('product');
	}

	function re_search_product_available($query,$limit,$start,$order_by = '',$keyword = '',$product_unavailable = '') {

		$this->db->select('product.product_id,product_nama,category_name,product_hargasewa,product_slug,product_kode');
		if(!empty($order_by) && isset($order_by['order_by']) && $order_by['order_by'] == 'popularity'){
			$this->db->join('product_popularity','product_popularity.product_id = product.product_id','left');
		}
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(isset($query['k']) && !empty($query['k'])){
				$this->db->group_start();
				foreach($query['k'] as $index => $value){
					$get_key   = str_replace("\\","",html_entity_decode($value));
					$key_value = array(
						'product_nama'  => $get_key,
						'product_kode'  => $get_key,
						'category_name' => $get_key
						);
					$this->db->or_like($key_value);
				}
				$this->db->group_end();
		}	

		if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){
			if(is_array($query) && !empty($query)){
				foreach($query as $index => $value){
					if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
						$this->db->group_start();
						foreach($value as $key => $row){
							$this->db->or_group_start();
							if($index == 'product'){
								$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'size'){
								$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
								$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

							if($index == 'store_location'){
								$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
							}
							$this->db->group_end();
						}
						$this->db->group_end();
					}
				}
			}
		}

		if(!empty($product_unavailable)){
			$this->db->where_not_in('product.product_id',$product_unavailable);
		}

		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		if(!empty($order_by) && isset($order_by['order_by'])){
			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'price_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_hargasewa',$sorting);
			}

			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'date_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_created',$sorting);
			}

			if($order_by['order_by'] == 'asc'){
				$this->db->order_by('product_nama', 'ASC');
			}

			if($order_by['order_by'] == 'desc'){
				$this->db->order_by('product_nama', 'DESC');
			}

			if($order_by['order_by'] == 'popularity'){
				$this->db->order_by('rented', 'DESC');
				$this->db->order_by('product_created','DESC');
			}
		} else {
			$this->db->order_by('product_nama', 'ASC');
		}
		$this->db->limit($limit, $start);
		$this->db->group_by('product.product_id');
		return $this->db->get('product')->result_array();
	}

	function search_product_popularity_available($query,$limit,$start,$order_by = '',$keyword = '',$product_unavailable = '') {

						$this->db->select_sum('rental_product_qty');
							$this->db->select('product.product_id');
							$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
							$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
							$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
							if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

								if(is_array($query) && !empty($query)){
									foreach($query as $index => $value){
										if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
											$this->db->group_start();
											foreach($value as $key => $row){
												$this->db->or_group_start();
												if($index == 'product'){
													$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'size'){
													$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'gender'){
													if($row == 'men'){
														$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
													} else {
														$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
													}}

													if($index == 'store_location'){
														$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
													}
													$this->db->group_end();
												}
												$this->db->group_end();
											}
										}
									}
								}

								if(!empty($product_unavailable)){
								$this->db->where_not_in('product.product_id',$product_unavailable);
							}

								$this->db->where('product.product_status',1);
								$this->db->where('product.product_active',1);
								$this->db->group_by('product.product_id');
								$query  = $this->db->get('product')->result_array();

								$result_query_part = array();

								foreach($query as $index => $value){
									$product_id             = $value['product_id'];
									$quantity               = $value['rental_product_qty'];
									if(isset($count[$value['product_id']])){
										$count[$product_id]+=$quantity;
									} else {
										$count[$product_id]=$quantity;
									}
									$sum_qty = 0;
									foreach($count as $key => $row){

										if($key == $product_id){
											$sum_qty = $row;
										}
										$result_query_part[$product_id]              = $value;
										$result_query_part[$product_id]['rented']    = $sum_qty;
									}
								}

								$product_id = array();
								if(!empty($result_query_part)){
									usort($result_query_part, function($a, $b) {
										return $b['rented'] - $a['rented'];
									});

									foreach($result_query_part as $index => $value){
										$product_id[] = $value['product_id'];
									}
								}

									$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,GROUP_CONCAT(category_name SEPARATOR ",") as category_name,category_slug');
								$this->db->join('product_image','product.product_id = product_image.product_id','left');
								$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
								$this->db->join('category','category.category_id = product_category_detil.category_id','left');

								if(!empty($date_start) && !empty($date_end)){
									$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
									$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
									$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
									$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
								}

								if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

								if(is_array($query) && !empty($query)){
									foreach($query as $index => $value){
										if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
											$this->db->group_start();
											foreach($value as $key => $row){
												$this->db->or_group_start();
												if($index == 'product'){
													$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'size'){
													$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'gender'){
													if($row == 'men'){
														$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
													} else {
														$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
													}}

													if($index == 'store_location'){
														$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
													}
													$this->db->group_end();
												}
												$this->db->group_end();
											}
										}
									}
								}
								
								if(!empty($product_unavailable)){
								$this->db->where_not_in('product.product_id',$product_unavailable);
							}

								if(!empty($date_start) && !empty($date_end)){
									$this->db->where('rental_active',1);
									$this->db->group_by('rental_product.rental_product_id');
								} else {
									$this->db->group_by('product.product_id');
								}
								$result_query_part_2 = $this->db->get('product')->result_array();

								$result 	= array();
								if(!empty($result_query_part_2)){
									foreach($product_id as $key => $row){
										foreach($result_query_part_2 as $index => $value){
											if($product_id[$key] == $value['product_id']){
												$result[$product_id[$key]] = $value;
											}
										}
									}
									$result = array_values($result);
								}

								return $result;
						}

						function search_product_popularity($query,$limit,$start,$keyword = '',$date_start = '',$date_end = ''){
							$this->db->select_sum('rental_product_qty');
							$this->db->select('product.product_id');
							$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
							$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
							$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
							if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

								if(is_array($query) && !empty($query)){
									foreach($query as $index => $value){
										if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
											$this->db->group_start();
											foreach($value as $key => $row){
												$this->db->or_group_start();
												if($index == 'product'){
													$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'size'){
													$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'gender'){
													if($row == 'men'){
														$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
													} else {
														$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
													}}

													if($index == 'store_location'){
														$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
													}
													$this->db->group_end();
												}
												$this->db->group_end();
											}
										}
									}
								}

								$this->db->where('product.product_status',1);
								$this->db->where('product.product_active',1);
								$this->db->group_by('product.product_id');
								$query  = $this->db->get('product')->result_array();

								$result_query_part = array();

								foreach($query as $index => $value){
									$product_id             = $value['product_id'];
									$quantity               = $value['rental_product_qty'];
									if(isset($count[$value['product_id']])){
										$count[$product_id]+=$quantity;
									} else {
										$count[$product_id]=$quantity;
									}
									$sum_qty = 0;
									foreach($count as $key => $row){

										if($key == $product_id){
											$sum_qty = $row;
										}
										$result_query_part[$product_id]              = $value;
										$result_query_part[$product_id]['rented']    = $sum_qty;
									}
								}

								$product_id = array();
								if(!empty($result_query_part)){
									usort($result_query_part, function($a, $b) {
										return $b['rented'] - $a['rented'];
									});

									foreach($result_query_part as $index => $value){
										$product_id[] = $value['product_id'];
									}
								}

									$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,GROUP_CONCAT(category_name SEPARATOR ",") as category_name,category_slug');
								$this->db->join('product_image','product.product_id = product_image.product_id','left');
								$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
								$this->db->join('category','category.category_id = product_category_detil.category_id','left');

								if(!empty($date_start) && !empty($date_end)){
									$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
									$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
									$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
									$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
								}

								if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

								if(is_array($query) && !empty($query)){
									foreach($query as $index => $value){
										if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
											$this->db->group_start();
											foreach($value as $key => $row){
												$this->db->or_group_start();
												if($index == 'product'){
													$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'size'){
													$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
												}

												if($index == 'gender'){
													if($row == 'men'){
														$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
													} else {
														$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
													}}

													if($index == 'store_location'){
														$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
													}
													$this->db->group_end();
												}
												$this->db->group_end();
											}
										}
									}
								}
								
								if(!empty($date_start) && !empty($date_end)){
									$this->db->where('rental_active',1);
									$this->db->group_by('rental_product.rental_product_id');
								} else {
									$this->db->group_by('product.product_id');
								}
								$result_query_part_2 = $this->db->get('product')->result_array();

								$result 	= array();
								if(!empty($result_query_part_2)){
									foreach($product_id as $key => $row){
										foreach($result_query_part_2 as $index => $value){
											if($product_id[$key] == $value['product_id']){
												$result[$product_id[$key]] = $value;
											}
										}
									}
									$result = array_values($result);
								}

								return $result;

							}
							
	function get_product_sizestock($id){
		$this->db->where_in('product_id',$id);
		$this->db->order_by('product_sizestock_sort','asc');
		return $this->db->get('product_sizestock')->result_array();
	}

	function get_product_categories($id){
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');
		$this->db->where('product_id',$id);
		$this->db->where('category_status',1);
		$this->db->order_by('product_category_sort','asc');
		return $this->db->get('product_category_detil')->result_array();
	}

	function get_check_size_filter($size){
		$this->db->select('category_sizestock as product_size');
		$this->db->join('category','category.category_id = category_sizestock.category_id','left');
		$this->db->where('category_flag','size');
		$this->db->where('category_slug',$size);
		return $this->db->get('category_sizestock')->result_array();	
	}

	function get_meta_default(){
		$this->db->select('setting_name,setting_value');
		$this->db->where('setting_name','meta_title');
		$this->db->or_where('setting_name','meta_keyword');
		$this->db->or_where('setting_name','meta_description');

		return $this->db->get('setting')->result_array();
	}

	function get_category_for_seo($slug){

		$this->db->select('category_name,category_flag,category_slug');

		$this->db->where('category_slug',$slug);

		return $this->db->get('category')->result_array();

	}



	function get_correction(){

		$this->db->select('right,wrong');

		//$this->db->where_in('wrong',$wrong);

		//return $this->db->get_compiled_select('correction');

		return $this->db->get('correction')->result_array();

	}

	

	function sidebar_product($query,$limit,$start,$order_by = '',$date_start = '',$date_end = '') {
		if(empty($date_start) && empty($date_end)){
			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,category_name,category_slug');
		} else {
			$this->db->select_sum('rental_product_qty');
			$this->db->select('product.product_id,product_slug,product_nama,product_kode,product_stock,product_hargasewa,product_created,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,rental_product_qty,product_sizestock_id,product_size,category_slug,return_date');
		}
		if(empty($date_start) && empty($date_end)){
			$this->db->join('product_image','product.product_id = product_image.product_id','left');
		}
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(!empty($date_start) && !empty($date_end)){
			$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
			$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
			$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
			$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		}

		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

			foreach($query as $index => $value){
				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
					$this->db->group_start();
					foreach($value as $key => $row){
						$this->db->or_group_start();

						if($index == 'product'){
							$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
						}

						if($index == 'size'){
							$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
						}

						if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

						if($index == 'store_location'){
							$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
						}
						$this->db->group_end();
					}
					$this->db->group_end();
				}
			}

		}

		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		
		if(!empty($order_by) && isset($order_by['order_by'])){
			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'price_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_hargasewa',$sorting);
			}

			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'date_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_created',$sorting);
			}

			if($order_by['order_by'] == 'asc'){
				$this->db->order_by('product_nama', 'ASC');
			}

			if($order_by['order_by'] == 'desc'){
				$this->db->order_by('product_nama', 'DESC');
			}

		} else {
			$this->db->order_by('product_nama', 'ASC');
		}

		//if(empty($date_start) && empty($date_end)){
			//$this->db->limit($limit, $start);
		//}

		if(!empty($date_start) && !empty($date_end)){
			$this->db->where('rental_active',1);
			$this->db->group_by('rental_product.rental_product_id');
		} else {
			$this->db->group_by('product.product_id');
		}
		return $this->db->get('product')->result_array();
	}

	

	function search_sum_product($query,$keyword = '') {
		$this->db->select('product.product_id,product_stock,product_sizestock_id');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');

		$this->db->group_by(array('product_sizestock.product_sizestock_id','product.product_id'));
		$result_one = $this->db->get('product')->result_array();

		$result_two = array();	
		$final 		= array();
		if(!empty($result_one)){

			$product_id 			= array();
			$product_stock 		= array();
			$result_two 			= array();
			$product_sizestock_id 	= array();
			foreach($result_one as $index => $value){
				$product_id[] 	= $value['product_id'];
				$product_stock[$value['product_id']][$value['product_sizestock_id']] = $value['product_stock'];
			}

			if(!empty($product_id)){
				$this->db->select('product.product_id,product_nama,product_kode,GROUP_CONCAT(category_name SEPARATOR ",") as category_name');
				$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
				$this->db->join('category','category.category_id = product_category_detil.category_id','left');
				$this->db->where_in('product.product_id',$product_id);
				$this->db->group_by('product_category_detil.product_id');
				$result_two = $this->db->get('product')->result_array();
			}

			if(!empty($result_two)){
				foreach($result_two as $index => $value){
					foreach($product_stock as $key => $row){
						if($value['product_id'] == $key){
							foreach($row as $k => $v){
								$result_two[$index]['product_sizestock_id'][$k]['product_stock'] = $v;
							}
						}
					}
				}
			}	

		}
		return $result_two;
	}



	function search_category($title){

		$this->db->select('category_name,category_slug,category_flag');

		/*if(!empty($title)){

			$this->db->group_start();

			$this->db->or_like($title);

			$this->db->group_end();

		}*/



		if(!empty($title)){

			$this->db->group_start();

			//$this->db->or_like($title);

			foreach($title as $index => $value){

				$get_key = array(

						'category_name' => $value

					);

				$this->db->or_like($get_key);

			}

			$this->db->group_end();

		}

		

		$this->db->order_by('category_name', 'ASC');

		$this->db->limit(6);

		return $this->db->get('category')->result_array();

	}



	function search_autocomplete($title){

		$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa');

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','product_category_detil.category_id = category.category_id','left');

		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		/*if(!empty($title)){

			$this->db->group_start();

			$this->db->or_like($title);

			$this->db->group_end();

		}*/

		if(!empty($title)){

			$this->db->group_start();

			foreach($title as $index => $value){

				$get_key = array(

						'product_nama'  => $value,

						'product_kode'  => $value,

						'category_name' => $value

					);

				$this->db->or_like($get_key);

			}

			$this->db->group_end();

		}

		//$this->db->order_by('product_nama', 'ASC');

		$this->db->limit(6);

		$this->db->group_by('product_nama');

		return $this->db->get('product')->result_array();

	}



	/*function search_product($title){

		$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa');

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		$this->db->like('product_nama',$title,'both');

		$this->db->order_by('product_nama', 'ASC');

		$this->db->limit(6);

		return $this->db->get('product')->result_array();

	}*/



	function search_check_return_order($id){

		$this->db->select('rental_order_id');

		$this->db->where('rental_order_id',$id);

		return $this->db->get('return_order')->result_array();

	}



	function categories_check_return_order($id){

		$this->db->select('rental_order_id');

		$this->db->where('rental_order_id',$id);

		return $this->db->get('return_order')->result_array();

	}



	function count_categories_product_available($query,$product_unavailable = '') {

		$this->db->select('product.product_id');

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');



		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){



			foreach($query as $index => $value){

				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

					$this->db->group_start();

					foreach($value as $key => $row){

						$this->db->or_group_start();

						if($index == 'product'){

							$this->db->where("(`filter_product` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'size'){

							$this->db->where("(`filter_size` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'gender'){

							if($row == 'men'){

								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

							} else {

							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

						}}



						if($index == 'store_location'){

							$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

						}

						$this->db->group_end();

					}

					$this->db->group_end();

				}

			}



		}

		if(!empty($product_unavailable)){

			$this->db->where_not_in('product.product_id',$product_unavailable);

		}

		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		$this->db->order_by('product_nama', 'ASC');

		$this->db->group_by('product.product_id');

		return $this->db->count_all_results('product');

	}



	function categories_product_available($query,$limit,$start,$order_by = '',$product_unavailable = '') {



		if(empty($date_start) && empty($date_end)){

			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,category_name');

		} else {

		//$this->db->select('product.product_id,product_sizestock_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_stock,product_created,rental_order.rental_order_id,rental_product_qty,rental_start_date,rental_end_date,product_size');

			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_stock,product_hargasewa,product_created,rental_order.rental_order_id,rental_start_date,rental_end_date,rental_product_qty,product_sizestock_id,product_size');

		}

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');



		if(!empty($date_start) && !empty($date_end)){

			$this->db->join('rental_product','rental_product.product_id = product.product_id','left');

			$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');

			$this->db->join('product_sizestock','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');



		//$this->db->join('calendar_order','calendar_order.rental_order_id = rental_order.rental_order_id','left');

		}



		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){



			foreach($query as $index => $value){

				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

					$this->db->group_start();

					foreach($value as $key => $row){

						$this->db->or_group_start();



						if($index == 'product'){

							$this->db->where("(`filter_product` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'size'){

							$this->db->where("(`filter_size` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'gender'){

							if($row == 'men'){

								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

							} else {

							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

						}}



						if($index == 'store_location'){

							$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

						}

						$this->db->group_end();

					}

					$this->db->group_end();

				}

			}



		}



		if(!empty($product_unavailable)){

			$this->db->where_not_in('product.product_id',$product_unavailable);

		}

		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		//$this->db->order_by('product_nama', 'ASC');

		if(!empty($order_by) && isset($order_by['order_by'])){

			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){



				$sorting = 'asc';

				if($order_by['order_by'] == 'price_desc'){

					$sorting = 'desc';

				}

				$this->db->order_by('product_hargasewa',$sorting);

			}



			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){



				$sorting = 'asc';

				if($order_by['order_by'] == 'date_desc'){

					$sorting = 'desc';

				}

				$this->db->order_by('product_created',$sorting);

			}



		} else {

			$this->db->order_by('product_nama', 'ASC');

		}

		$this->db->limit($limit, $start);

		$this->db->group_by('product.product_id');

		return $this->db->get('product')->result_array();

	}



	function categories_product($query,$limit,$start,$order_by = '',$date_start = '',$date_end = '') {



		if(empty($date_start) && empty($date_end)){

			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,category_name');

		} else {



			/*$date_start = date('Y-m-d',strtotime('-1 day', strtotime($date_start)));

			$date_end   = date('Y-m-d',strtotime('+1 day', strtotime($date_end)));*/

			

			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_stock,product_hargasewa,product_created,rental_order.rental_order_id,rental_start_date,rental_end_date,rental_product_qty,product_sizestock_id,product_size');

		}

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');



		if(!empty($date_start) && !empty($date_end)){

			$this->db->join('rental_product','rental_product.product_id = product.product_id','left');

			$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');

			$this->db->join('product_sizestock','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');

		}



		if(!empty($date_start) && !empty($date_end)){

			/*$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$date_start);

			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$date_end);*/



			$this->db->where("'$date_start' BETWEEN rental_start_date AND rental_end_date AND '$date_end' BETWEEN rental_start_date AND rental_end_date OR ('$date_start' >= rental_start_date AND '$date_end' <= rental_end_date) ");

		}



		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){



			foreach($query as $index => $value){

				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

					$this->db->group_start();

					foreach($value as $key => $row){

						$this->db->or_group_start();

						if($index == 'product'){

							$this->db->where("(`filter_product` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'size'){

							$this->db->where("(`filter_size` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'gender'){

							if($row == 'men'){

								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

							} else {

							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

						}}



						if($index == 'store_location'){

							$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

						}

						$this->db->group_end();

					}

					$this->db->group_end();

				}

			}



		}

		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		//$this->db->order_by('product_nama', 'ASC');

		if(!empty($order_by) && isset($order_by['order_by'])){

			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){



				$sorting = 'asc';

				if($order_by['order_by'] == 'price_desc'){

					$sorting = 'desc';

				}

				$this->db->order_by('product_hargasewa',$sorting);

			}



			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_asc'){



				$sorting = 'asc';

				if($order_by['order_by'] == 'date_desc'){

					$sorting = 'desc';

				}

				$this->db->order_by('product_created',$sorting);

			}



		} else {

			$this->db->order_by('product_nama', 'ASC');

		}

		$this->db->limit($limit, $start);

		if(!empty($date_start) && !empty($date_end)){

			$this->db->group_by('product_sizestock.product_sizestock_id');

		} else {

			$this->db->group_by('product.product_id');

		}

		return $this->db->get('product')->result_array();

	}



	function count_categories_product($query) {

		$this->db->select('product.product_id');

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');



		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){



			foreach($query as $index => $value){

				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

					$this->db->group_start();

					foreach($value as $key => $row){

						$this->db->or_group_start();

						if($index == 'product'){

							$this->db->where("(`filter_product` LIKE '$row%')",NULL,FALSE);

						}

						

						if($index == 'size'){

							$this->db->where("(`filter_size` LIKE '$row%')",NULL,FALSE);

						}



						if($index == 'gender'){

							if($row == 'men'){

								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

							} else {

							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

						}}



						if($index == 'store_location'){

							$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

						}

						$this->db->group_end();

					}

					$this->db->group_end();

				}

			}



		}



		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		$this->db->order_by('product_nama', 'ASC');

		$this->db->group_by('product.product_id');

		return $this->db->count_all_results('product');

	}



	// CATEGORIES END //

	// SEARCH START //

	function count_search_product_available($query,$keyword,$product_unavailable = '') {



		$this->db->select('product.product_id');

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');



		if(isset($keyword) && isset($keyword['k'])){

			$keywords  = explode('_',$keyword['k']);

			if(!empty($keywords) && is_array($keywords)){

				$this->db->group_start();

				foreach($keywords as $index => $value){

					$get_key   = str_replace("\\","",html_entity_decode($value));

					$this->db->or_where("(`product_kode` LIKE '%$get_key%')",NULL,FALSE);

					

					$this->db->or_where("(`product_nama` LIKE '%$get_key%')",NULL,FALSE);

					

					$this->db->or_group_start();

					$this->db->or_where("(`category_name` LIKE '%$get_key%')",NULL,FALSE);

					$this->db->group_end();

				}

				$this->db->group_end();

			}

		}	



		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){



			foreach($query as $index => $value){

				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

					$this->db->group_start();

					foreach($value as $key => $row){

						$this->db->or_group_start();

						

						if($index == 'product'){

							$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);

						}

						

						if($index == 'size'){

							$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);

						}



						if($index == 'gender'){

							if($row == 'men'){

								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

							} else {

							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

						}}



						if($index == 'store_location'){

							$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);

						}

						$this->db->group_end();

					}

					$this->db->group_end();

				}

			}



		}

		if(!empty($product_unavailable)){

			$this->db->where_not_in('product.product_id',$product_unavailable);

		}

		$this->db->where('product.product_status',1);

		$this->db->where('product.product_active',1);

		$this->db->order_by('product_nama', 'ASC');

		$this->db->group_by('product.product_id');

		return $this->db->count_all_results('product');

	}



	function search_product_available($query,$limit,$start,$order_by = '',$keyword = '',$product_unavailable = '') {

		if(empty($date_start) && empty($date_end)){
			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,GROUP_CONCAT(category_name SEPARATOR ",") as category_name');
		} else {
			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_stock,product_hargasewa,product_created,rental_order.rental_order_id,rental_start_date,rental_end_date,rental_product_qty,product_sizestock_id,product_size,GROUP_CONCAT(category_name SEPARATOR ",") as category_name');
		}
		$this->db->join('product_image','product.product_id = product_image.product_id','left');
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(!empty($date_start) && !empty($date_end)){
			$this->db->join('rental_product','rental_product.product_id = product.product_id','left');
			$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
			$this->db->join('product_sizestock','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		}

		if(is_array($query) && !empty($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

			foreach($query as $index => $value){
				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
					$this->db->group_start();
					foreach($value as $key => $row){
						$this->db->or_group_start();
						   
						if($index == 'product'){
							$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
						}
						
						if($index == 'size'){
							$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
						}

						if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

						if($index == 'store_location'){
							$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
						}
						$this->db->group_end();
					}
					$this->db->group_end();
				}
			}

		}

		if(!empty($product_unavailable)){
			$this->db->where_not_in('product.product_id',$product_unavailable);
		}
		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		if(!empty($order_by) && isset($order_by['order_by'])){
			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'price_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_hargasewa',$sorting);
			}

			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'date_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_created',$sorting);
			}

			if($order_by['order_by'] == 'asc'){
				$this->db->order_by('product_nama', 'ASC');
			}

			if($order_by['order_by'] == 'desc'){
				$this->db->order_by('product_nama', 'DESC');
			}

		} else {
			$this->db->order_by('product_nama', 'ASC');
		}

		$this->db->group_by('product.product_id');
		return $this->db->get('product')->result_array();
	}



	function search_product($query,$limit,$start,$order_by = '',$keyword = '',$date_start = '',$date_end = '') {
		if(empty($date_start) && empty($date_end)){

			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,GROUP_CONCAT(category_name SEPARATOR ",") as category_name,category_slug');
		} else {
			$this->db->select_sum('rental_product_qty');
			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_stock,category_name,product_hargasewa,product_created,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,rental_product_qty,product_sizestock_id,product_size,category_slug,return_date');
		}
		$this->db->join('product_image','product.product_id = product_image.product_id','left');
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		if(!empty($date_start) && !empty($date_end)){
			$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
			$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
			$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
			$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		}

		if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){

			if(is_array($query) && !empty($query)){
				foreach($query as $index => $value){
					if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){
						$this->db->group_start();
						foreach($value as $key => $row){
							$this->db->or_group_start();
							if($index == 'product'){
								$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'size'){
								$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);
							}

							if($index == 'gender'){
							if($row == 'men'){
								$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);
							} else {
							$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);
						}}

							if($index == 'store_location'){
								$this->db->where("(`filter_store_location` LIKE '%$row%')",NULL,FALSE);
							}
							$this->db->group_end();
						}
						$this->db->group_end();
					}
				}
			}
		}

		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		if(!empty($order_by) && isset($order_by['order_by'])){
			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'price_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_hargasewa',$sorting);
			}

			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){

				$sorting = 'asc';
				if($order_by['order_by'] == 'date_desc'){
					$sorting = 'desc';
				}
				$this->db->order_by('product_created',$sorting);
			}

			if($order_by['order_by'] == 'asc'){
				$this->db->order_by('product_nama', 'ASC');
			}

			if($order_by['order_by'] == 'desc'){
				$this->db->order_by('product_nama', 'DESC');
			}

		} else {
			$this->db->order_by('product_nama', 'ASC');
		}

		/*if(empty($date_start) && empty($date_end)){
			$this->db->limit($limit, $start);
		}*/
		if(!empty($date_start) && !empty($date_end)){
			$this->db->where('rental_active',1);
			$this->db->group_by('rental_product.rental_product_id');
		} else {
			$this->db->group_by('product.product_id');
		}
		return $this->db->get('product')->result_array();
	}
	

	function search_product_tes($query,$limit,$start,$order_by = '',$keyword = '',$date_start = '',$date_end = '') {

		if(empty($date_start) && empty($date_end)){



			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_hargasewa,product_created,category_name,category_slug');

		} else {



			$this->db->select_sum('rental_product_qty');

			$this->db->select('product.product_id,product_slug,product_nama,product_image,product_kode,product_stock,product_hargasewa,product_created,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,rental_product_qty,product_sizestock_id,product_size,category_slug,return_date');

		}

		$this->db->join('product_image','product.product_id = product_image.product_id','left');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');



		if(!empty($date_start) && !empty($date_end)){

			$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');

			$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');

			$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');

			$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');

		}



		if(isset($keyword) && isset($keyword['k'])){

			$keywords  = explode('_',$keyword['k']);

			if(!empty($keywords) && is_array($keywords)){

				$this->db->group_start();

				foreach($keywords as $index => $value){

					$get_key   = str_replace("\\","",html_entity_decode($value));

					$key_value = array(

						'product_nama'  => $get_key,

						'product_kode'  => $get_key,

						'category_name' => $get_key

						);

					$this->db->or_like($key_value);

				}

				$this->db->group_end();

			}

		}	





		if(is_array($query) && isset($query['product']) || isset($query['size']) || isset($query['gender']) || isset($query['store_location'])){



			if(is_array($query) && !empty($query)){

				foreach($query as $index => $value){

					if(!empty($value) && $index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

						$this->db->group_start();

						foreach($value as $key => $row){

							$this->db->or_group_start();

							if($index == 'product'){

								$this->db->where("(`filter_product` LIKE '$row%')",NULL,FALSE);

							}



							if($index == 'size'){

								$this->db->where("(`filter_size` LIKE '$row%')",NULL,FALSE);

							}



							if($index == 'gender'){

								$this->db->where("(`filter_gender` LIKE '$row%')",NULL,FALSE);

							}



							if($index == 'store_location'){

								$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

							}

							$this->db->group_end();

						}

						$this->db->group_end();

					}

				}

			}

		}



		if(!empty($order_by) && isset($order_by['order_by'])){

			if($order_by['order_by'] == 'price_asc' || $order_by['order_by'] == 'price_desc'){



				$sorting = 'asc';

				if($order_by['order_by'] == 'price_desc'){

					$sorting = 'desc';

				}

				$this->db->order_by('product_hargasewa',$sorting);

			}



			if($order_by['order_by'] == 'date_desc' || $order_by['order_by'] == 'date_desc'){



				$sorting = 'asc';

				if($order_by['order_by'] == 'date_desc'){

					$sorting = 'desc';

				}

				$this->db->order_by('product_created',$sorting);

			}



		} else {

			$this->db->order_by('product_nama', 'ASC');

		}



		if(empty($date_start) && empty($date_end)){

			$this->db->limit($limit, $start);

		}



		if(!empty($date_start) && !empty($date_end)){

			$this->db->group_by('rental_product.rental_product_id');

		} else {

			$this->db->group_by('product.product_id');

		}

		return $this->db->get_compiled_select('product');

	}


	function count_search_product($query,$keyword) {
		$this->db->select('product.product_id');
		$this->db->join('product_image','product.product_id = product_image.product_id','left');
		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		$this->db->where('product.product_status',1);
		$this->db->where('product.product_active',1);
		$this->db->order_by('product_nama', 'ASC');
		$this->db->group_by('product.product_id');
		return $this->db->count_all_results('product');
	}



	function get_where_in_sizestock($id){

		$this->db->where_in('product_sizestock.product_id',$id);

		return $this->db->get('product_sizestock')->result_array();

	}



	function get_categories_by_flag(){

		$this->db->where('category_status',1);

		$this->db->order_by('category_order','desc');

		$this->db->order_by('category_name','asc');

		$query 	= $this->db->get('category');

		$result = array();

		if($query->num_rows() > 0){

			foreach($query->result_array() as $index => $value){

				$category_flag = $value['category_flag'];

				switch ($category_flag) {

					case 'gender':

					$result['category_gender'][] = $value; 

					break;

					case 'size':

					$result['category_size'][] = $value; 

					break;

					case 'store_location':

					$result['category_store'][] = $value; 

					break;

				}

			}

		}

		return $result;

	}

	

	function get_categories($uri = ''){

		if(!empty($uri)){

			$this->db->where('category_slug',$uri);

		}

		$this->db->where('category_status',1);

		$this->db->order_by('category_order','desc');

		$this->db->order_by('category_name','asc');

		return $this->db->get('category')->result_array();

	}



	function get_product($uri = ''){

		if(!empty($uri)){

			$this->db->where('product_slug',$uri);

		}

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$this->db->order_by('product_order','desc');

		return $this->db->get('product')->result_array();

	}

	public function get_nested_categories($section = ''){



		$this->db->from('category');

		$this->db->where('category_parent', 0);

		$this->db->where('category_status',1);

		$this->db->order_by('category_order','asc');

		$parent = $this->db->get();



		$categories = $parent->result_array();

		$i=0;

		foreach($categories as $index => $value){



			$categories[$i]['sub'] = $this->sub_categories($value['category_id']);

			if($value['category_id'] == $value['category_same_parent'] && $section == 'pagination'){

				array_unshift($categories[$i]['sub'],$value);

			}

			$i++;

		}

		return $categories;

	}



	public function sub_categories($id){



		$this->db->from('category');

		$this->db->where('category_parent', $id);

		$this->db->order_by('category_order','asc');
		
		$child = $this->db->get();

		$categories = $child->result_array();

		$i=0;

		foreach($categories as $index => $value){



			$categories[$i]['sub'] = $this->sub_categories($value['category_id']);

			if($value['category_id'] == $value['category_same_parent']){

				array_unshift($categories[$i]['sub'],$value);

			}

			$i++;

		}

		return $categories;       

	}



	public function fetch_data_product($limit, $start){

		$this->db->limit($limit, $start);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$this->db->order_by('product_nama','asc');

		return $this->db->get("product")->result_array();

	}



	public function count_product() {

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		return $this->db->count_all('product');

	}



	public function count_product_from_category($uri) {

		$cat_slug = str_replace('/','',$uri);

		$this->db->select('product.product_id,product_nama,product_hargasewa,category_slug,category_name,product_slug');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		$this->db->where('category_slug',$cat_slug);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$query = $this->db->get('product')->result_array();



		$count = 0;



		if(!empty($query)){

			$count = count($query);

		}

		return $count;

	}



	public function product_homepage($limit){

		$this->db->limit($limit);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$this->db->order_by('product_created','desc');

		$query = $this->db->get("product")->result_array();



		$this->db->where('setting_name','latest_product_at_homepage');

		$setting = $this->db->get('setting')->result_array();

		if($setting[0]['setting_value'] == 1){

			return $query;

		} else {

			return false;

		}

	}



	public function popular_category(){



		$this->db->where('setting_name','popular_category');

		$setting = $this->db->get('setting')->result_array();



		$num = 0;

		if(!empty($setting)){

			$num = $setting[0]['setting_value'];

		}

		$this->db->join('category','category.category_id = product_category.category_id','left');

		$this->db->limit($num);

		$this->db->where('popular_category',1);

		$this->db->where('category_status',1);

		$this->db->order_by('popular_category_order','desc');

		return $this->db->get("product_category")->result_array();

	}



	public function popular_theme(){



		$this->db->where('setting_name','popular_theme');

		$setting = $this->db->get('setting')->result_array();



		$num = 0;

		if(!empty($setting)){

			$num = $setting[0]['setting_value'];

		}

		$this->db->join('category','category.category_id = product_category.category_id','left');

		$this->db->limit($num);

		$this->db->where('popular_theme',1);

		$this->db->where('category_status',1);

		$this->db->order_by('popular_theme_order','desc');

		return $this->db->get("product_category")->result_array();

	}



	public function product_featured(){

		$this->db->where('product_featured',1);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$this->db->order_by('product_created','desc');

		return $this->db->get("product")->result_array();

	}



	public function fetch_data_product_by_slug($uri,$limit, $start) {

		$cat_slug = str_replace('/','',$uri);

		$this->db->select('product.product_id,product_nama,product_hargasewa,category_slug,category_name,product_slug,product_kode');

		$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');

		$this->db->join('category','category.category_id = product_category_detil.category_id','left');

		$this->db->limit($limit, $start);

		$this->db->where('category_slug',$cat_slug);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$this->db->order_by('product_nama','asc');

		return $this->db->get("product")->result_array();

	}



	public function dummy_product($limit = ''){

		$this->db->limit($limit);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$this->db->order_by('product_nama','asc');

		return $this->db->get('product')->result_array();

	}



	public function get_product_slug($uri){

		//$this->db->where('product_slug',$uri);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		$query = $this->db->get('product')->result_array();

		$result = array();



		if(!empty($query)){

			foreach($query as $index => $value){

				$prod_slug = str_replace('/','',$value['product_slug']);

				if($uri == $prod_slug){

					$result[] = $value;

				}

			}

		}

		return $result;

	}



	public function get_product_by_id($id){

		$this->db->where('product_id',$id);

		$this->db->where('product_active',1);

		$this->db->where('product_status',1);

		return $this->db->get('product')->result_array();
	}

	public function get_product_by_id_all($id){

		$this->db->where('product_id',$id);

		return $this->db->get('product')->result_array();
	}



	public function get_product_filter_sidebar($slug,$filter = array()){

		$result = array();

		if(!empty($slug)){

			$this->db->select('product_id,product_category_detil.category_id');

			$this->db->where('category_slug',$slug);

			$this->db->join('category','category.category_id = product_category_detil.category_id','left');

			$product_detil = $this->db->get('product_category_detil')->result_array();



			$product_category_detil_id 	= array();

			$category_detil_id 			= array();

			if(!empty($product_detil)){

				foreach($product_detil as $index => $value){

					$product_category_detil_id[] = $value['product_id'];

					$category_detil_id[] 		 = $value['category_id'];

				}

			}



			$category_id 	= array();

			if(!empty($filter)){

				$this->db->select('category_id');

				$this->db->where_in('category_slug',$filter);

				$category = $this->db->get('category')->result_array();

				if(!empty($category)){

					foreach($category as $index => $value){

						$category_id[] = $value['category_id'];

					}

				}

			}



			$this->db->select('product_category_detil.product_id,product_category_detil.category_id,product_nama,product_kode,product_hargasewa,product_slug');

			$this->db->join('product','product.product_id = product_category_detil.product_id','left');

			$this->db->join('category','category.category_id = product_category_detil.category_id','left');

			$this->db->where_in('product_category_detil.product_id',$product_category_detil_id);

			if(!empty($category_id)){

				$this->db->where_in('product_category_detil.category_id',$category_id);

			} else {

				$this->db->where_in('product_category_detil.category_id',$category_detil_id);

			}

			$result = $this->db->get('product_category_detil')->result_array();

		}

		//$result_all = array('product' => $product_detil,'category_id' => $category_id,'final_result' => $result);

		return $result;

	}



	function get_product_related($product_id,$category_id,$category_slug){

		$this->db->select('product_category_detil_id,product.product_id,product_nama,product_category_detil.category_id,flag,product_kode,product_slug,product_hargasewa');

		$this->db->join('product_category_detil','product.product_id = product_category_detil.product_id','left');

		$this->db->where('product.product_id !=',$product_id);

		if(is_array($category_slug) && !empty($category_slug)){

			$get_filter = array();

			foreach($category_slug as $index => $value){

				if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

					$this->db->group_start();

					foreach($value as $key => $row){

						if($index == 'product' || $index == 'size' || $index == 'gender' || $index == 'store_location'){

							$this->db->or_group_start();

							$get_index = $index;

							if($index == 'product'){

								$this->db->where("(`filter_product` LIKE '%$row%')",NULL,FALSE);

							}elseif($index == 'size'){

								$this->db->where("(`filter_size` LIKE '%$row%')",NULL,FALSE);

							}elseif($index == 'gender'){

								if($row == 'men'){

									$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

								} else {

									$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

								}

							}elseif($index == 'store_location') {

								$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

							}

							$this->db->group_end();

						}

					}

					$this->db->group_end();

				}

			}



		}

		$this->db->where('product_active',1);
		$this->db->where('product_status',1);
		$this->db->group_by('product_category_detil.product_id');

		$this->db->limit(10);

		$query = $this->db->get('product');

		$result = array();

		if($query->num_rows() <= 3){

			$prod_id = array();
			$product = array();

			foreach($query->result_array() as $index => $value){
				$prod_id[] = $value['product_id'];
				$product[] = $value;
			}

			$this->db->select('product_category_detil_id,product.product_id,product_nama,product_category_detil.category_id,flag,product_kode,product_slug,product_hargasewa');

			$this->db->join('product_category_detil','product.product_id = product_category_detil.product_id','left');

			$this->db->where('product.product_id !=',$product_id);
			$this->db->where_not_in('product.product_id',$prod_id);

			if(is_array($category_slug) && !empty($category_slug)){

				$get_filter = array();

				foreach($category_slug as $index => $value){

					if($index == 'product' || $index == 'gender' || $index == 'store_location'){

						$this->db->group_start();

						foreach($value as $key => $row){

							if($index == 'product' || $index == 'gender' || $index == 'store_location'){

								$this->db->or_group_start();

								$get_index = $index;

								if($index == 'product'){

									$this->db->where("(`filter_product` LIKE '$row%')",NULL,FALSE);

								} elseif($index == 'gender'){

									if($row == 'men'){

										$this->db->where("(`filter_gender` LIKE '%men%')",NULL,FALSE);
															$this->db->where("(`filter_gender` NOT LIKE 'women%')",NULL,FALSE);

									} else {

										$this->db->where("(`filter_gender` LIKE '%$row%')",NULL,FALSE);

									}

								}elseif($index == 'store_location') {

									$this->db->where("(`filter_store_location` LIKE '$row%')",NULL,FALSE);

								}

								$this->db->group_end();

							}

						}

						$this->db->group_end();

					}

				}



			}

			$this->db->where('product_active',1);
			$this->db->where('product_status',1);
			$this->db->group_by('product_category_detil.product_id');

			$this->db->limit(10);

			$query_next = $this->db->get('product')->result_array();

			$result = array_merge($query_next,$product);

			uasort($result, function ($a, $b) {

				return $a['product_nama'] > $b['product_nama'];

			});

		} else {

			$result = $query->result_array();

		}

		return $result;

	}



	public function get_all_rental_order_by_id($product_sizestock_id){
		$this->db->select('product_sizestock_id,product_stock,rental_product_qty,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,rental_return_date');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where('product_sizestock.product_sizestock_id',$product_sizestock_id);
		$this->db->where('product_active',1);
		$this->db->where('product_status',1);
		//$this->db->where('rental_status !=','return');
		$this->db->where('rental_active',1);
		$this->db->order_by('product.product_nama','asc');
		return $this->db->get('product');
	}


	function get_all_rental_order_by_product($product_id){

		$this->db->select('product.product_id,product_sizestock_id,product_size,product_stock,rental_product_qty,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,return_date');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('product_active',1);
		$this->db->where('product_status',1);
		$this->db->where('rental_active',1);
		if(!empty($product_id)){
		$this->db->where_in('rental_product.product_id',$product_id);
		}
		return $this->db->get('product')->result_array();
	}

	function get_thumbnail_image($id){

		$this->db->join('product_image','product_image.product_id = product.product_id','left');

		$this->db->where('product.product_id',$id);

		$this->db->limit(1);

		return $this->db->get('product')->result_array();

	}



	function get_product_sugges($id){

		$this->db->select('product_related');

		$this->db->where('product_id',$id);

		$this->db->order_by('product_related_id','asc');

		$product_related = $this->db->get('product_related')->result_array();



		$product_id = array();

		$result 	= array();

		if(!empty($product_related)){

			foreach($product_related as $index => $value){

				$product_id[] = $value['product_related'];

			}

			if(!empty($product_id)){

				$this->db->where_in('product_id',$product_id);

				$this->db->where('product_active',1);

				$this->db->where('product_status',1);

				$result = $this->db->get('product')->result_array();

			}

		}

		return $result;

	}



	function get_setting($setting_name,$value){

		$this->db->select($value);

		$this->db->where('setting_name',$setting_name);

		return $this->db->get("setting")->result_array();

	}



	function get_slideshow(){

		$this->db->select('slideshow_image,slideshow_flag,slideshow_video_id,slideshow_video');

		$this->db->where('slideshow_flag !=','unknown');

		$this->db->where('slideshow_status',1);

		$this->db->order_by('slideshow_order','desc');

		$this->db->order_by('slideshow_created','desc');

		return $this->db->get('slideshow')->result_array();

	}



	function get_testimonial(){

		$this->db->select('testimonial_image');

		$this->db->where('testimonial_status',1);

		$this->db->order_by('testimonial_created','desc');

		return $this->db->get('testimonial')->result_array();

	}



	function get_articel_thumbnail(){

		$this->db->select('article_title,article_created,article_slug,article_image,article_description_thumbnail');

		$this->db->where('article_status',1);

		$this->db->order_by('article_created','desc');

		$this->db->limit(2);

		return $this->db->get('article')->result_array();

	}



	function get_articel_by_slug($slug){

		$this->db->select('article_title,article_created,article_slug,article_image,article_description');

		$this->db->where('article_slug',$slug);

		$this->db->where('article_status',1);

		return $this->db->get('article')->result_array();

	}





	function get_count_pcs_kostum(){

		$this->db->select('product_stock');

		$this->db->join('product','product.product_id = product_sizestock.product_id','left');

		$this->db->where('product_status',1);

		$this->db->where('product_active',1);

		$query 	= $this->db->get('product_sizestock');

		$result = 0;

		if($query->num_rows() > 0){

			foreach($query->result_array() as $index => $value){

				$result+=$value['product_stock'];

			}

		}

		return $result;

	}



	function get_count_jenis_kostum(){

		$this->db->select('product_kode');

		$this->db->where('product_status',1);

		$this->db->where('product_active',1);

		$query = $this->db->get('product');

		$count = array();

		if($query->num_rows() > 0){

			foreach($query->result_array() as $index => $value){

				if(!empty($value['product_kode'])){

					@$count[$value['product_kode']]++;

				}

			}

		}

		$result = 0;

		if(!empty($count)){

			foreach($count as $index => $value){

				$result+=$value;

			}

		}

		return $result;

	}


	function get_page_by_slug($slug){
		$this->db->where('page_slug', $slug);
		$this->db->where('page_status', 1);
		$this->db->limit(1);
		$get = $this->db->get('pages');
		if($get->num_rows() > 0){
			return $get->row_array();
		}
		return false;
	}

	public function get_product_size_by_id($id)
	{
		$this->db->select('product_size', 'product_sizestock_id');
		$this->db->where('product_sizestock_id', $id);
		$size = $this->db->get('product_sizestock');
		return $size->row();
	}
}
?>