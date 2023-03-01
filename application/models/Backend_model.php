<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend_model extends CI_Model

{

	public function getKodeProductEditSetCustom($id){
		$this->db->select('product_kode');
		$this->db->where('product_id',$id);
		return $this->db->get('product')->row_array();
	}
	public function getEditSetCustom($id){
		$this->db->where('set_custom_id',$id);
		return $this->db->get('set_custom')->result_array();
	}

	public function getItemsEditSetCustom($id){
		$this->db->where('set_custom_id',$id);
		$this->db->order_by('set_custom_detail_id','asc');
		return $this->db->get('set_custom_detail')->result_array();
	}

	public function getKaryawanNamaForSetCustom($id){
		$this->db->select('karyawan_nama');
		$this->db->where('karyawan_id',$id);
		return $this->db->get('set_custom_karyawan')->row_array();
	}

	public function getProdukNamaForSetCustom($size){
		$this->db->select('product_sizestock.product_id,product_sizestock_id,product_nama,product_kode,product_size');
		$this->db->join('product','product.product_id = product_sizestock.product_id','left');
		$this->db->where('product_sizestock_id',$size);
		return $this->db->get('product_sizestock')->result_array();
	}

	public function getProdukSizeForSetCustom($id){
		$this->db->select('product_size');
		$this->db->where('product_sizestock_id',$id);
		return $this->db->get('product')->result_array();
	}

	public function getProdukForSetCustom(){
		$this->db->select('product_nama,product_kode,product_id');
		$this->db->order_by('product_nama','asc');
		return $this->db->get('product')->result_array();
	}
	public function countAllKaryawan($search = ''){
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
		}
		$this->db->order_by('created','desc');
		return $this->db->count_all_results('set_custom_karyawan');
	}

	public function AllSetCustomKaryawan($search = '',$start,$offset){
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
		}
		$this->db->order_by('created','desc');
		$this->db->limit($offset,$start);
		return $this->db->get('set_custom_karyawan');
	}

	public function PrintAllSetCustom($search = '',$start_date = '',$end_date = '',$note = false,$product = ''){

		$query = false;
		if(!$note){

			$this->db->select('set_custom.set_custom_id,karyawan_id,karyawan_nama,created');
			$this->db->join('set_custom_detail','set_custom.set_custom_id = set_custom_detail.set_custom_id','left');
			if(!empty($search)){
				$this->db->group_start();
				$this->db->where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($product)){
				$this->db->group_start();
				$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($start_date) && empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
				$this->db->group_end();
			}

			if(empty($start_date) && !empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($start_date) && !empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
				$this->db->group_end();
			}

			$this->db->group_by('set_custom.set_custom_id');
			$this->db->order_by('created','desc');

			$query = $this->db->get('set_custom')->result_array();

			$set_custom_id = array();
			if(!empty($query)){
				foreach($query as $index => $value){
					$set_custom_id[] = $value['set_custom_id'];
				}
				$this->db->select('set_custom.set_custom_id,set_custom_detail_id,product_nama,product_kode,product_size,note');
				$this->db->join('set_custom_detail','set_custom.set_custom_id = set_custom_detail.set_custom_id','left');
				$this->db->where_in('set_custom.set_custom_id',$set_custom_id);
				if(!empty($product)){
					$this->db->group_start();
					$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
					$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
					$this->db->group_end();
				}
				$this->db->order_by('created','desc');
				$second = $this->db->get('set_custom')->result_array();

				foreach($query as $index => $value){
					if(!empty($second)){
						foreach($second as $key => $row){
							if($row['set_custom_id'] == $value['set_custom_id']){
								$query[$index]['items'][] = $row;
							}
						}
					}
				}
			}

		} else {
			$this->db->select('set_custom_id,set_custom_detail_id,product_nama,product_kode,product_size,note');
			$this->db->where('note !=','');
			$this->db->where('note !=',NULL);
			if(!empty($product)){
				$this->db->group_start();
				$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->group_end();
			}
			$this->db->order_by('set_custom_id','desc');
			$first = $this->db->get('set_custom_detail')->result_array();

			$first_set_custom_id 			= array();
			$first_set_custom_detail_id 	= array();

			if(!empty($first)){
				foreach($first as $index => $value){
					$first_set_custom_id[] = $value['set_custom_id'];
					$first_set_custom_detail_id[] = $value['set_custom_detail_id'];
				}

				$this->db->select('set_custom_id,karyawan_id,karyawan_nama,created');
				$this->db->where_in('set_custom_id',$first_set_custom_id);

				if(!empty($search)){
					$this->db->group_start();
					$this->db->where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
					$this->db->group_end();
				}

				if(!empty($start_date) && empty($end_date)){
					$this->db->group_start();
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
					$this->db->group_end();
				}

				if(!empty($start_date) && !empty($end_date)){
					$this->db->group_start();
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
					$this->db->group_end();
				}

				$this->db->order_by('created','desc');

				$query = $this->db->get('set_custom')->result_array();


				$set_custom_id = array();
				if(!empty($query)){
					foreach($query as $index => $value){
						if(!empty($first)){
							foreach($first as $key => $row){
								if($row['set_custom_id'] == $value['set_custom_id']){
									$query[$index]['items'][] = $row;
								}
							}
						}
					}
				}
			}
		}

		return $query;
	}

	public function AllSetCustom($search = '',$start_date = '',$end_date = '',$note = false,$start,$offset,$product = ''){

		$query = false;
		if(!$note){

			$this->db->select('set_custom.set_custom_id');
			$this->db->join('set_custom_detail','set_custom.set_custom_id = set_custom_detail.set_custom_id','left');
			if(!empty($search)){
				$this->db->group_start();
				$this->db->where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($product)){
				$this->db->group_start();
				$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($start_date) && empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
				$this->db->group_end();
			}

			if(empty($start_date) && !empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($start_date) && !empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
				$this->db->group_end();
			}

			$this->db->group_by('set_custom.set_custom_id');
			$this->db->order_by('created','desc');
			$this->db->limit($offset,$start);

			$first = $this->db->get('set_custom')->result_array();

			$set_custom_id = array();
			if(!empty($first)){
				foreach($first as $index => $value){
					$set_custom_id[] = $value['set_custom_id'];
				}
				$this->db->select('set_custom.set_custom_id,karyawan_id,karyawan_nama,product_id,product_sizestock_id,product_nama,product_kode,product_size,note,set_custom.created');
				$this->db->join('set_custom_detail','set_custom.set_custom_id = set_custom_detail.set_custom_id','left');
				if(!empty($product)){
					$this->db->group_start();
					$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
					$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
					$this->db->group_end();
				}
				$this->db->where_in('set_custom.set_custom_id',$set_custom_id);
				$this->db->order_by('created','desc');
				$query = $this->db->get('set_custom')->result_array();
			}

		} else {
			$this->db->select('set_custom_id,set_custom_detail_id');
			$this->db->where('note !=','');
			$this->db->where('note !=',NULL);
			if(!empty($product)){
				$this->db->group_start();
				$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->group_end();
			}
			$this->db->order_by('set_custom_id','desc');
			$first = $this->db->get('set_custom_detail')->result_array();

			$first_set_custom_id 			= array();
			$first_set_custom_detail_id 	= array();

			if(!empty($first)){
				foreach($first as $index => $value){
					$first_set_custom_id[] = $value['set_custom_id'];
					$first_set_custom_detail_id[] = $value['set_custom_detail_id'];
				}

				$this->db->select('set_custom_id');
				$this->db->where_in('set_custom_id',$first_set_custom_id);

				if(!empty($search)){
					$this->db->group_start();
					$this->db->where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
					$this->db->group_end();
				}

				if(!empty($start_date) && empty($end_date)){
					$this->db->group_start();
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
					$this->db->group_end();
				}

				if(!empty($start_date) && !empty($end_date)){
					$this->db->group_start();
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
					$this->db->group_end();
				}

				$this->db->order_by('created','desc');
				$this->db->limit($offset,$start);

				$second = $this->db->get('set_custom')->result_array();

				$set_custom_id = array();
				if(!empty($second)){
					foreach($second as $index => $value){
						$set_custom_id[] = $value['set_custom_id'];
					}
					$this->db->select('set_custom.set_custom_id,karyawan_id,karyawan_nama,product_id,product_sizestock_id,product_nama,product_kode,product_size,note,set_custom.created');
					$this->db->join('set_custom_detail','set_custom.set_custom_id = set_custom_detail.set_custom_id','left');
					$this->db->where_in('set_custom.set_custom_id',$set_custom_id);
					$this->db->where_in('set_custom_detail_id',$first_set_custom_detail_id);
					$this->db->order_by('created','desc');
					$query = $this->db->get('set_custom')->result_array();
				}

			}
			
		}

		return $query;
	}

	public function countAllSetCustom($search = '',$start_date = '',$end_date = '',$note = false,$product = ''){
		$query = 0;
		if(!$note){

			$this->db->select('set_custom.set_custom_id');
			$this->db->join('set_custom_detail','set_custom.set_custom_id = set_custom_detail.set_custom_id','left');
			if(!empty($search)){
				$this->db->group_start();
				$this->db->where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($product)){
				$this->db->group_start();
				$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($start_date) && empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
				$this->db->group_end();
			}

			if(empty($start_date) && !empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
				$this->db->group_end();
			}

			if(!empty($start_date) && !empty($end_date)){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
				$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
				$this->db->group_end();
			}

			$this->db->group_by('set_custom.set_custom_id');
			$this->db->order_by('created','desc');

			$first = $this->db->get('set_custom')->result_array();

			$set_custom_id = array();
			if(!empty($first)){
				foreach($first as $index => $value){
					$set_custom_id[] = $value['set_custom_id'];
				}
				$this->db->where_in('set_custom_id',$set_custom_id);
				$query = $this->db->count_all_results('set_custom');
			}

		} else {
			$this->db->select('set_custom_id,set_custom_detail_id');
			$this->db->where('note !=','');
			$this->db->where('note !=',NULL);
			if(!empty($product)){
				$this->db->group_start();
				$this->db->where("(`product_nama` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($product)."%')",NULL,FALSE);
				$this->db->group_end();
			}
			$this->db->order_by('set_custom_id','desc');
			$first = $this->db->get('set_custom_detail')->result_array();

			$first_set_custom_id 			= array();
			$first_set_custom_detail_id 	= array();

			if(!empty($first)){
				foreach($first as $index => $value){
					$first_set_custom_id[] = $value['set_custom_id'];
					$first_set_custom_detail_id[] = $value['set_custom_detail_id'];
				}

				$this->db->select('set_custom_id');
				$this->db->where_in('set_custom_id',$first_set_custom_id);

				if(!empty($search)){
					$this->db->group_start();
					$this->db->where("(`karyawan_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
					$this->db->group_end();
				}

				if(!empty($start_date) && empty($end_date)){
					$this->db->group_start();
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
					$this->db->group_end();
				}

				if(!empty($start_date) && !empty($end_date)){
					$this->db->group_start();
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
					$this->db->where('DATE_FORMAT(created, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
					$this->db->group_end();
				}

				$this->db->order_by('created','desc');

				$second = $this->db->get('set_custom')->result_array();

				$set_custom_id = array();
				if(!empty($second)){
					foreach($second as $index => $value){
						$set_custom_id[] = $value['set_custom_id'];
					}
					$this->db->where_in('set_custom_id',$set_custom_id);
					$query = $this->db->count_all_results('set_custom');
				}

			}
			
		}

		return $query;
	}

	public function getProductByScanQR($kode){
		// $this->db->select('product_id');
		$this->db->where('product_kode',$kode);
		return $this->db->get('product')->row();
	}

	public function getProductByScanQRSlug($uri)
	{
		// $this->db->select('product_id');
		$this->db->where('product_slug',$uri);

		return $this->db->get('product')->row();
	}

	public function getProductNamaForQr(){
		$this->db->select('product_kode,product_sizestock_id,product_slug');
		$this->db->join('product','product.product_id = product_sizestock.product_id','left');
		//$this->db->limit(5);
		return $this->db->get('product_sizestock')->result_array();
	}

	public function getProductForQr(){
		$this->db->select('product_nama,product_slug');
		return $this->db->get('product')->result_array();
	}

	public function getProductByKode($kode,$multiple){
		$this->db->select('product_id,product_nama,product_kode,product_hargasewa,product_isipaket,product_deposit,product_slug,product_crop,product_scale');
		if(!empty($kode)){
			$this->db->where_in('product_kode',$kode);
		}
		if(!empty($multiple)){
			$this->db->where_in('product_kode',$multiple);
		}
		$this->db->where('product_active',1);
		$this->db->order_by('product_kode','asc');
		//$this->db->limit(10);
		$product 		= $this->db->get('product')->result_array();
		$product_id 	= array();
		$product_image 	= array();
		if(!empty($product)){
			foreach($product as $index => $value){
				$product[$index]['image'] 		= '';
				$product[$index]['sizestock'] 	= array();
				$this->db->select('product_id,product_image');
				$this->db->where_in('product_id',$value['product_id']);
				$this->db->limit(1);
				$product_image = $this->db->get('product_image')->result_array();
				if(!empty($product_image)){
					foreach($product_image as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['image'] = $row['product_image'];
						}
					}
				}
				$this->db->select('product_id,product_size,product_estimasiukuran,product_stock,product_sizestock_id');
				$this->db->where_in('product_id',$value['product_id']);
				$product_sizestock = $this->db->get('product_sizestock')->result_array();
				if(!empty($product_sizestock)){
					foreach($product_sizestock as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['sizestock'][] = $row;
						}
					}
				}
			}
		}
		return $product;
	}

	public function getProductForTag($id = ''){
		$this->db->select('product_id,product_nama,product_kode,product_hargasewa,product_isipaket,product_deposit,product_slug');
		if(!empty($id)){
			$this->db->where_in('product_id',$id);
		}
		$this->db->order_by('product_nama','asc');
		//$this->db->limit(10);
		$product 		= $this->db->get('product')->result_array();
		$product_id 	= array();
		$product_image 	= array();
		if(!empty($product)){
			foreach($product as $index => $value){
				$product[$index]['image'] 		= '';
				$product[$index]['sizestock'] 	= array();
				$this->db->select('product_id,product_image');
				$this->db->where_in('product_id',$value['product_id']);
				$this->db->limit(1);
				$product_image = $this->db->get('product_image')->result_array();
				if(!empty($product_image)){
					foreach($product_image as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['image'] = $row['product_image'];
						}
					}
				}
				$this->db->select('product_id,product_size,product_stock');
				$this->db->where_in('product_id',$value['product_id']);
				$product_sizestock = $this->db->get('product_sizestock')->result_array();
				if(!empty($product_sizestock)){
					foreach($product_sizestock as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['sizestock'][] = $row;
						}
					}
				}
			}
		}
		return $product;
	}

	public function getProductForCatalog($id = ''){
		$this->db->select('product_id,product_nama,product_kode,product_hargasewa,product_isipaket,product_deposit,product_slug');
		if(!empty($id)){
			$this->db->where_in('product_id',$id);
		}
		$this->db->order_by('product_nama','asc');
		//$this->db->limit(10);
		$product 		= $this->db->get('product')->result_array();
		$product_id 	= array();
		$product_image 	= array();
		if(!empty($product)){
			foreach($product as $index => $value){
				$product[$index]['image'] 		= '';
				$product[$index]['sizestock'] 	= array();
				$this->db->select('product_id,product_image');
				$this->db->where_in('product_id',$value['product_id']);
				$this->db->limit(1);
				$product_image = $this->db->get('product_image')->result_array();
				if(!empty($product_image)){
					foreach($product_image as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['image'] = $row['product_image'];
						}
					}
				}
				$this->db->select('product_id,product_size,product_stock,product_sizestock_id');
				$this->db->where_in('product_id',$value['product_id']);
				$product_sizestock = $this->db->get('product_sizestock')->result_array();
				if(!empty($product_sizestock)){
					foreach($product_sizestock as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['sizestock'][] = $row;
						}
					}
				}
			}
		}
		return $product;
	}
	/* ----------------------------------------------- */
	public function getProductKode(){
		$this->db->select('product_kode');
		$this->db->order_by('product_kode','asc');
		return $this->db->get('product')->result_array();
	}
	public function getExampleProductCatalog($id){
		$this->db->where('product_id',$id);
		return $this->db->get('product')->result_array();
	}
	/* ----------------------------------------------- */
	public function checkHistorySaldo($date){
		$this->db->where('DATE_FORMAT(tanggal, "%Y-%m-%d") =',$date);
		$query = $this->db->get('jenis_transaksi_saldo');
		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}

	public function getHistorySaldo($date){
		$this->db->select('jenis_transaksi,jenis_transaksi_nominal,jenis_transaksi_action,jenis_transaksi_created,jenis_transaksi_flag');
		$this->db->group_start();
		$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$date);
		$this->db->where('jenis_transaksi_active =',1);
		$this->db->where('jenis_transaksi_status =','paid');
		$this->db->group_end();
		$this->db->where('jenis_transaksi_active =',1);
		$this->db->where('jenis_transaksi_status =','paid');
		$this->db->order_by('jenis_transaksi_id','asc');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getDendaForViewOrder($id){
		$this->db->where('rental_order_id',$id);
		$this->db->where('rental_extrapayment_flag','denda');
		$this->db->order_by('rental_extrapayment_id','asc');
		return $this->db->get('rental_extrapayment')->result_array();
	}
	public function getExtraPaymentForViewOrder($id){
		$this->db->where('rental_order_id',$id);
		$this->db->group_start();
		$this->db->or_where('rental_extrapayment_flag','sewa');
		$this->db->or_where('rental_extrapayment_flag','deposit');
		$this->db->group_end();
		$this->db->order_by('rental_extrapayment_id','asc');
		return $this->db->get('rental_extrapayment')->result_array();
	}

	public function checkDBjenistransaksiReturn($id){
		$this->db->where('rental_order_id',$id);
		$this->db->where('jenis_transaksi','return');
		return $this->db->get('jenis_transaksi')->result_array();
	}
	public function checkDBjenistransaksi($id){
		$this->db->where('rental_order_id',$id);
		$this->db->group_start();
		$this->db->or_where('jenis_transaksi','sewa');
		$this->db->or_where('jenis_transaksi','deposit');
		$this->db->group_end();
		return $this->db->get('jenis_transaksi')->result_array();
	}
	public function checkDBextrapaymentDenda($id){
		$this->db->where('rental_order_id',$id);
		$this->db->where('rental_extrapayment_flag','denda');
		return $this->db->get('rental_extrapayment')->result_array();
	}
	public function checkDBextrapayment($id){
		$this->db->where('rental_order_id',$id);
		$this->db->group_start();
		$this->db->or_where('rental_extrapayment_flag','sewa');
		$this->db->or_where('rental_extrapayment_flag','deposit');
		$this->db->group_end();
		return $this->db->get('rental_extrapayment')->result_array();
	}
	public function getJenisTransaksiSewaDepositByRentalOrder($id){
		$this->db->where('rental_order_id',$id);
		$this->db->group_start();
		$this->db->or_where('jenis_transaksi','sewa');
		$this->db->or_where('jenis_transaksi','deposit');
		$this->db->group_end();
		return $this->db->get('jenis_transaksi')->result_array();
	}
	public function getCreatedJenisTransaksi($id){
		$this->db->select('jenis_transaksi_created');
		$this->db->where('rental_order_id',$id);
		return $this->db->get('jenis_transaksi')->row();
	}

	public function getDailyReport1($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$this->db->select('return_order_id,rental_order.rental_order_id,rental_order.rental_invoice,customer_name,customer_phone,rental_created,rental_return_date,rental_status,rental_order.rental_total_deposit,rental_order.rental_total_hargasewa,rental_order.rental_total,rental_total_extrapayment,rental_terima_uangsewa,rental_terima_uangdeposit,rental_return_uangdeposit,return_created_date,return_late_charges,return_damage_fine,jenis_transaksi,jenis_transaksi_nominal,jenis_transaksi_flag,jenis_transaksi_created');
		$this->db->join('jenis_transaksi','jenis_transaksi.rental_order_id = rental_order.rental_order_id','left');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_order.rental_invoice',$search);
			$this->db->or_like('rental_order.customer_name',$search);
			$this->db->or_like('rental_order.customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$start);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$start);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('rental_active',1);
		$this->db->where('rental_payment_status =','paid');
		$this->db->where('jenis_transaksi_flag !=','');
		$this->db->order_by('rental_created','desc');
		$this->db->order_by('jenis_transaksi_created','asc');
		$this->db->order_by('rental_return_date','desc');
		return $this->db->get('rental_order')->result_array();
	}
	
	public function getDailyReportTransfer($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->or_like('jenis_transaksi_customer_nama',$search);
			$this->db->or_like('jenis_transaksi_customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$start);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active',1);
		$this->db->where('jenis_transaksi_status','paid');
		$this->db->where('jenis_transaksi_flag','transfer');
		$this->db->order_by('jenis_transaksi_id','asc');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getDailyReportDebit($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->or_like('jenis_transaksi_customer_nama',$search);
			$this->db->or_like('jenis_transaksi_customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$start);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active',1);
		$this->db->where('jenis_transaksi_status','paid');
		$this->db->where('jenis_transaksi_flag','debit');
		$this->db->order_by('jenis_transaksi_id','asc');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getDailyReportCash($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->or_like('jenis_transaksi_customer_nama',$search);
			$this->db->or_like('jenis_transaksi_customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$start);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active',1);
		$this->db->where('jenis_transaksi_status','paid');
		$this->db->where('jenis_transaksi_flag','cash');
		$this->db->order_by('jenis_transaksi_id','asc');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getDailyReport($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->or_like('jenis_transaksi_customer_nama',$search);
			$this->db->or_like('jenis_transaksi_customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$start);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active =',1);
		$this->db->where('jenis_transaksi_status =','paid');
		$this->db->order_by('jenis_transaksi_created','desc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getOrderBukuKas($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->group_end();
		} else {
			if($show == 'month'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m") =',$start);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active =',1);
		$this->db->where('jenis_transaksi_flag','cash');
		$this->db->where('jenis_transaksi_status =','paid');
		$this->db->order_by('jenis_transaksi_id','asc');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	} 

	public function getBalanceDailyReportDebit($search,$show,$start,$end){
		$query          = array();
		$yesterday 		= (!empty($start)) ? date("Y-m-d", strtotime($start)) : date("Y-m-d");
		$start 			= date("Y-m-d", strtotime($start));
		$end 			= date("Y-m-d", strtotime($end));
		if(empty($search)){
			$this->db->select('saldo,tanggal,flag,created,tanggal_sekarang');
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$start);
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") <=',$end);
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$yesterday);
				$this->db->group_end();
			}
		}
		$this->db->where('flag','debit');
		return $this->db->get('jenis_transaksi_saldo')->result_array();
	}

	public function getBalanceDailyReportTransfer($search,$show,$start,$end){
		$query          = array();
		$yesterday 		= (!empty($start)) ? date("Y-m-d", strtotime($start)) : date("Y-m-d");
		$start 			= date("Y-m-d", strtotime($start));
		$end 			= date("Y-m-d", strtotime($end));
		if(empty($search)){
			$this->db->select('saldo,tanggal,flag,created,tanggal_sekarang');
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$start);
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") <=',$end);
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$yesterday);
				$this->db->group_end();
			}
		}
		$this->db->where('flag','transfer');
		return $this->db->get('jenis_transaksi_saldo')->result_array();
	}

	public function getBalanceDailyReportCash($search,$show,$start,$end){
		$query          = array();
		$yesterday 		= (!empty($start)) ? date("Y-m-d", strtotime($start)) : date("Y-m-d");
		$start 			= date("Y-m-d", strtotime($start));
		$end 			= date("Y-m-d", strtotime($end));
		if(empty($search)){
			$this->db->select('saldo,tanggal,flag,created,tanggal_sekarang');
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$start);
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") <=',$end);
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$yesterday);
				$this->db->group_end();
			}
		}
		$this->db->where('flag','cash');
		return $this->db->get('jenis_transaksi_saldo')->result_array();
	}

	public function getBalanceDailyReport($search,$show,$start,$end){
		$yesterday 		= (!empty($start)) ? date("Y-m-d", strtotime('-1 days',strtotime($start))) : date("Y-m-d", strtotime('-1 days'));
		$periode 		= date("Y-m-d", strtotime('-1 days',strtotime($start)));
		$month 			= date("Y-m-d",strtotime("last day of previous month",strtotime($start)));
		$this->db->select('jenis_transaksi,jenis_transaksi_nominal,jenis_transaksi_flag,jenis_transaksi_action,jenis_transaksi_created');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->group_end();
		} else {
			if($show == 'month'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m") <=',$month);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$periode);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$yesterday);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active =',1);
		$this->db->where('jenis_transaksi_status =','paid');
		$this->db->order_by('jenis_transaksi_created','desc');
		return $this->db->get('jenis_transaksi')->result_array();
		//return $this->db->get_compiled_select('jenis_transaksi');
	}

	public function getBalanceBukuKas($search,$show,$start,$end){
		$yesterday 		= date("Y-m-d", strtotime('-1 days'));
		$periode 		= date("Y-m-d", strtotime('-1 days',strtotime($start)));
		$month 			= date("Y-m-d",strtotime("last day of previous month",strtotime($start)));
		$this->db->select('jenis_transaksi,jenis_transaksi_nominal,jenis_transaksi_action');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_invoice',$search);
			$this->db->group_end();
		} else {
			if($show == 'month'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m") <=',$month);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$periode);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$yesterday);
				$this->db->where('jenis_transaksi_active =',1);
				$this->db->where('jenis_transaksi_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('jenis_transaksi_active =',1);
		$this->db->where('jenis_transaksi_flag','cash');
		$this->db->where('jenis_transaksi_status =','paid');
		$this->db->order_by('jenis_transaksi_id','asc');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getTanggalJenisTransaksiReturn($id){
		$this->db->select('jenis_transaksi_flag,jenis_transaksi_created,jenis_transaksi_nominal');
		$this->db->where('rental_order_id',$id);
		$this->db->where('jenis_transaksi','return');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getTanggalJenisTransaksiDeposit($id){
		$this->db->select('jenis_transaksi_flag,jenis_transaksi_created,jenis_transaksi_nominal');
		$this->db->where('rental_order_id',$id);
		$this->db->where('jenis_transaksi','deposit');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getTanggalJenisTransaksiSewa($id){
		$this->db->select('jenis_transaksi_flag,jenis_transaksi_created,jenis_transaksi_nominal');
		$this->db->where('rental_order_id',$id);
		$this->db->where('jenis_transaksi','sewa');
		$this->db->order_by('jenis_transaksi_created','asc');
		return $this->db->get('jenis_transaksi')->result_array();
	}

	public function getDendaForPrint($id){
		$this->db->select('rental_extrapayment,rental_extranote,rental_extrapayment_flag');
		$this->db->where('rental_order_id',$id);
		$this->db->where('rental_extrapayment_flag','denda');
		$this->db->order_by('rental_extrapayment_id','asc');
		return $this->db->get('rental_extrapayment')->result_array();
	}

	public function getExtraPaymentForPrint($id){
		$this->db->select('rental_extrapayment,rental_extranote,rental_extrapayment_flag');
		$this->db->where('rental_order_id',$id);
		$this->db->group_start();
		$this->db->or_where('rental_extrapayment_flag','sewa');
		$this->db->or_where('rental_extrapayment_flag','deposit');
		$this->db->group_end();
		$this->db->order_by('rental_extrapayment_id','asc');
		return $this->db->get('rental_extrapayment')->result_array();
	}

	public function getProductListForCatalog(){
		$this->db->select('product_id,product_nama,product_kode');
		$this->db->order_by('product_nama','asc');
		return $this->db->get('product')->result_array();
	}
	
	public function getProductForCatalog1($id = ''){
		$this->db->select('product_id,product_nama,product_kode,product_hargasewa,product_isipaket,product_deposit,product_slug');
		if(!empty($id)){
			$this->db->where_in('product_id',$id);
		}
		$this->db->order_by('product_nama','asc');
		$this->db->limit(10);
		$product 		= $this->db->get('product')->result_array();
		$product_id 	= array();
		$product_image 	= array();
		if(!empty($product)){
			foreach($product as $index => $value){
				$product[$index]['image'] 		= '';
				$product[$index]['sizestock'] 	= array();
				$this->db->select('product_id,product_image');
				$this->db->where_in('product_id',$value['product_id']);
				$this->db->limit(1);
				$product_image = $this->db->get('product_image')->result_array();
				if(!empty($product_image)){
					foreach($product_image as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['image'] = $row['product_image'];
						}
					}
				}
				$this->db->select('product_id,product_size,product_estimasiukuran');
				$this->db->where_in('product_id',$value['product_id']);
				$product_sizestock = $this->db->get('product_sizestock')->result_array();
				if(!empty($product_sizestock)){
					foreach($product_sizestock as $key => $row){
						if($row['product_id'] == $value['product_id']){
							$product[$index]['sizestock'][] = $row;
						}
					}
				}
			}
		}
		return $product;
	}
	
	public function checkExistReturnTransaksi($id){
		$this->db->where('jenis_transaksi','return');
		$this->db->where('jenis_transaksi_id',$id);
		$q = $this->db->get('jenis_transaksi');
		if($q->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}
	public function getLastCode($date) {
		$result = false;
		$this->db->select('code');
		$this->db->where('DATE_FORMAT(date, "%Y-%m-%d") =',$date);
		$this->db->order_by('Id','desc');
		$qry = $this->db->get('autonumber');
		if ($qry->num_rows() > 0){
			$result = $qry->row_array();
		}
		return $result;
	}

	public function getRentalOrderForUpdateMethodPayment(){
		$this->db->select('rental_order_id,rental_return_date');
		return $this->db->get('rental_order')->result_array();
	}
	// start daily report

	public function getUpdateRental(){
		$this->db->select('rental_order_id,rental_return_date');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <',date('Y-m-d',strtotime('-1 day')));
		return $this->db->get('rental_order')->result_array();
	}

	public function getTransaksiAdjustmentBukuKas($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$result      = array();
		if(empty($search)){
			if($show == 'month'){
				$this->db->where('DATE_FORMAT(transaksi_created, "%Y-%m") =',$start);
			} elseif($show == 'date'){
				$this->db->where('DATE_FORMAT(transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(transaksi_created, "%Y-%m-%d") <=',$end);
			} elseif($show == 'daily') {
				$this->db->where('DATE_FORMAT(transaksi_created, "%Y-%m-%d") =',$start);
			} else {
				$this->db->where('DATE_FORMAT(transaksi_created, "%Y-%m-%d") =',$currentdate);
			}
			$this->db->order_by('transaksi_id','asc');
			$result = $this->db->get('transaksi_bukukas')->result_array();
			//$result = $this->db->get_compiled_select('transaksi_bukukas');
		}
		return $result;
	}

	public function checkDBTransaksi($id){
		$this->db->select('transaksi_id');
		$this->db->where('transaksi_id',$id);
		return $this->db->get('transaksi_bukukas')->result_array();
	}
	public function getFirstProductDailyReport($orderid){
		$this->db->select('rental_order_id,rental_product_nama');
		$this->db->where('rental_order_id',$orderid);
		$this->db->order_by('rental_product_id','asc');
		$this->db->limit(1);
		return $this->db->get('rental_product')->result_array();
	}

	public function getDailyReportSaldo($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$this->db->select('SUM(rental_order.rental_total + rental_total_extrapayment - return_late_charges - return_damage_fine) as total', false);
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_order.rental_invoice',$search);
			$this->db->or_like('rental_order.customer_name',$search);
			$this->db->or_like('rental_order.customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$start);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$start);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('rental_active',1);
		$this->db->where('rental_payment_status =','paid');
		$this->db->order_by('rental_created','desc');
		$this->db->order_by('rental_return_date','desc');
		//return $this->db->get_compiled_select('rental_order');
		return $this->db->get('rental_order')->result_array();
	}

	/*public function getDailyReport($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$this->db->select('return_order_id,rental_order.rental_order_id,rental_order.rental_invoice,customer_name,customer_phone,rental_created,rental_return_date,rental_status,rental_order.rental_total_deposit,rental_order.rental_total_hargasewa,rental_order.rental_total,rental_total_extrapayment,rental_terima_uangsewa,rental_terima_uangdeposit,rental_return_uangdeposit,return_created_date,return_late_charges,return_damage_fine');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($search)){
			$this->db->group_start();
				$this->db->like('rental_order.rental_invoice',$search);
				$this->db->or_like('rental_order.customer_name',$search);
				$this->db->or_like('rental_order.customer_phone',$search);
			$this->db->group_end();
		} else {
			if($show == 'daily'){
				$this->db->group_start();
					$this->db->group_start();
						$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$start);
						$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
						$this->db->where('rental_active',1);
						$this->db->where('rental_payment_status =','paid');
					$this->db->group_end();
					$this->db->or_group_start();
						$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$start);
						$this->db->where('rental_active',1);
						$this->db->where('rental_payment_status =','paid');
					$this->db->group_end();
					$this->db->or_group_start();
						$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$start);
						$this->db->where('rental_active',1);
						$this->db->where('rental_payment_status =','paid');
					$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
					$this->db->where('rental_active',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
					$this->db->where('rental_active',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") >=',$start);
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") <=',$end);
					$this->db->where('rental_active',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
					$this->db->where('rental_active',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
					$this->db->where('rental_active',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$currentdate);
					$this->db->where('rental_active',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('rental_active',1);
		$this->db->where('rental_payment_status =','paid');
		$this->db->order_by('rental_created','desc');
		$this->db->order_by('rental_return_date','desc');
		return $this->db->get('rental_order')->result_array();
	}*/
	// end daily report

	// start buku kas
	
	public function getExtraPaymentByRentalID($id){
		$result = false;
		if(!empty($id)){
			$this->db->select('rental_order_id,rental_extrapayment_flag,rental_extrapayment');
			$this->db->where_in('rental_order_id',$id);
			$result = $this->db->get('rental_extrapayment')->result_array();
		}
		return $result;
	}

	public function getSaldoAwal($search,$show,$start,$end){
		$query       = array();
		$currentdate = date('Y-m-d');
		if(empty($search)){
			$this->db->select('saldo,tanggal,flag,created,tanggal_sekarang');
			if($show == 'month'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m") =',$start);
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") <=',$end);
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(tanggal_sekarang, "%Y-%m-%d") =',$currentdate);
				$this->db->group_end();
			}
			$this->db->where('flag','cash');
			$query = $this->db->get('jenis_transaksi_saldo')->result_array();
		}
		return $query;
	}

	public function getSaldo($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$this->db->select('SUM(rental_order.rental_total + rental_total_extrapayment - return_late_charges - return_damage_fine) as total', false);
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_order.rental_invoice',$search);
			$this->db->group_end();
		} else {
			if($show == 'month'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m") =',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m") =',null);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m") =',$start);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m") =',$start);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('rental_active',1);
		$this->db->where('rental_payment_status =','paid');
		$this->db->order_by('rental_created','desc');
		$this->db->order_by('rental_return_date','desc');
		//return $this->db->get_compiled_select('rental_order');
		return $this->db->get('rental_order')->result_array();
	}

	/*public function getOrderBukuKas($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$this->db->select('return_order_id,rental_active,rental_order.rental_order_id,rental_order.rental_invoice,customer_name,customer_phone,rental_created,rental_return_date,rental_status,rental_order.rental_total_deposit,rental_order.rental_total_hargasewa,rental_order.rental_total,rental_total_extrapayment,rental_terima_uangsewa,rental_terima_uangdeposit,rental_return_uangdeposit,return_created_date,return_late_charges,return_damage_fine,jenis_transaksi,jenis_transaksi_nominal,jenis_transaksi_flag,jenis_transaksi_created');
		$this->db->join('jenis_transaksi','jenis_transaksi.rental_order_id = rental_order.rental_order_id','left');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($search)){
			$this->db->group_start();
				$this->db->like('rental_order.rental_invoice',$search);
			$this->db->group_end();
		} else {
			if($show == 'month'){
				$this->db->group_start();
					$this->db->group_start();
						$this->db->where('DATE_FORMAT(rental_created, "%Y-%m") =',$start);
						$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m") =',null);
						$this->db->where('rental_active =',1);
						$this->db->where('rental_payment_status =','paid');
					$this->db->group_end();
					$this->db->or_group_start();
						$this->db->where('DATE_FORMAT(rental_created, "%Y-%m") =',$start);
						$this->db->where('rental_active =',1);
						$this->db->where('rental_payment_status =','paid');
					$this->db->group_end();
					$this->db->or_group_start();
						$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m") =',$start);
						$this->db->where('rental_active =',1);
						$this->db->where('rental_payment_status =','paid');
					$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
					$this->db->where('rental_active =',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
					$this->db->where('rental_active =',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") >=',$start);
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") <=',$end);
					$this->db->where('rental_active =',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
					$this->db->where('rental_active =',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") =',$currentdate);
					$this->db->where('rental_active =',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$currentdate);
					$this->db->where('rental_active =',1);
					$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('rental_active =',1);
		$this->db->where('rental_payment_status =','paid');
		$this->db->order_by('rental_created','desc');
		$this->db->order_by('rental_return_date','desc');
		return $this->db->get('rental_order')->result_array();
	} */
	public function getOrderBukuKas1($search,$show,$start,$end){
		$currentdate = date('Y-m-d');
		$this->db->select('return_order_id,rental_active,rental_order.rental_order_id,rental_order.rental_invoice,customer_name,customer_phone,rental_created,rental_return_date,rental_status,rental_order.rental_total_deposit,rental_order.rental_total_hargasewa,rental_order.rental_total,rental_total_extrapayment,rental_terima_uangsewa,rental_terima_uangdeposit,rental_return_uangdeposit,return_created_date,return_late_charges,return_damage_fine,jenis_transaksi,jenis_transaksi_nominal,jenis_transaksi_flag,jenis_transaksi_created');
		$this->db->join('rental_order','rental_order.rental_order_id = jenis_transaksi.rental_order_id','inner');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('rental_order.rental_invoice',$search);
			$this->db->group_end();
		} else {
			if($show == 'month'){
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m") =',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m") =',null);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m") =',$start);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m") =',$start);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->group_end();
			} elseif($show == 'date'){
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") >=',$start);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") <=',$end);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			} else{
				$this->db->group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',null);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(jenis_transaksi_created, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where('DATE_FORMAT(rental_return_date, "%Y-%m-%d") =',$currentdate);
				$this->db->where('rental_active =',1);
				$this->db->where('rental_payment_status =','paid');
				$this->db->group_end();
			}
		}
		$this->db->where('rental_active =',1);
		$this->db->where('rental_payment_status =','paid');
		$this->db->where('jenis_transaksi_flag !=','');
		$this->db->order_by('rental_created','desc');
		$this->db->order_by('jenis_transaksi_created','asc');
		$this->db->order_by('rental_return_date','desc');
		return $this->db->get('jenis_transaksi')->result_array();
	} 
	// end buku kas

	public function temp_get_non_active_rental(){
		$this->db->select_sum('rental_product_qty');
		$this->db->select('rental_product.product_id');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where('rental_active',0);
		$this->db->group_by('product_id');
		return $this->db->get('rental_product')->result_array(); 
	}

	public function get_check_rental_status_active($id){
		$this->db->where('rental_order_id',$id);
		$this->db->where('rental_active',1);
		$query = $this->db->get('rental_order');
		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}
	public function get_rental_product_to_trash($id){
		$this->db->select_sum('rental_product_qty');
		$this->db->select('rented,rented_in_trash,rental_product.product_id');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->join('product_popularity','product_popularity.product_id = rental_product.product_id','left');
		$this->db->where('rental_product.rental_order_id',$id);
		$this->db->where('rental_active',1);
		$this->db->group_by('product_id');
		return $this->db->get('rental_product')->result_array();  
	}
	
	public function get_rental_product_to_restore($id){
		$this->db->select_sum('rental_product_qty');
		$this->db->select('rented,rented_in_trash,rental_product.product_id');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->join('product_popularity','product_popularity.product_id = rental_product.product_id','left');
		$this->db->where('rental_product.rental_order_id',$id);
		$this->db->where('rental_active',0);
		$this->db->group_by('product_id');
		return $this->db->get('rental_product')->result_array();  
	}

	public function get_product_popularity($id){
		$this->db->select('rented,product_popularity.product_id');
		$this->db->join('rental_product','rental_product.product_id = product_popularity.product_id','left');
		$this->db->where_in('rental_order_id',$id);
		return $this->db->get('product_popularity')->result_array();
	}

	public function get_new_product_popularity($id){
		$this->db->where('product_id',$id);
		return $this->db->get('product_popularity')->result_array();
	}

	public function get_check_report_product($id){
		$this->db->select('product_nama,product_kode');
		$this->db->where('product_id',$id);
		return $this->db->get('product')->result_array();
	}
	
	public function get_report_product_sum_lastdays($id){
		$this->db->select_sum('rental_product_hargasewa','sum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_lastdays($id){
		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_grafik_lastdays($id){
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_product_hargasewa) as total_hargasewa');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_product_sum_custom($id,$start,$end){
		$this->db->select_sum('rental_product_hargasewa','sum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_custom($id,$start,$end){
		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_grafik_custom($id,$start,$end){
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_product_hargasewa) as total_hargasewa');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_product_grafik_thismonth($id,$year,$month){
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_product_hargasewa) as total_hargasewa');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_product_thismonth($id,$year,$month){
		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_sum_thismonth($id,$year,$month){
		$this->db->select_sum('rental_product_hargasewa','sum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_grafik_lastmonth($id){
		$start_date 	=  date("Y-m-d", strtotime("first day of previous month"));
		$end_date 		=  date("Y-m-d", strtotime("last day of previous month"));
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_product_hargasewa) as total_hargasewa');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_product_lastmonth($id){
		$start_date 	=  date("Y-m-d", strtotime("first day of previous month"));
		$end_date 		=  date("Y-m-d", strtotime("last day of previous month"));
		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_sum_lastmonth($id){
		$start_date 	=  date("Y-m-d", strtotime("first day of previous month"));
		$end_date 		=  date("Y-m-d", strtotime("last day of previous month"));
		$this->db->select_sum('rental_product_hargasewa','sum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_grafik_year($id,$year){
		$this->db->select('DATE_FORMAT(rental_created, "%M") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_product_hargasewa) as total_hargasewa');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_product_sum_year($id,$year){
		$this->db->select_sum('rental_product_hargasewa','sum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by(array('DATE_FORMAT(rental_created, "%Y")'));
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_product_year($id,$year){
		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		$this->db->where('product_id',$id);
		$this->db->group_by(array('DATE_FORMAT(rental_created, "%Y")'));
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_grafik_custom($start,$end){
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_total_hargasewa) as total_hargasewa');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_grafik_year($year){
		$this->db->select('DATE_FORMAT(rental_created, "%M") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_total_hargasewa) as total_hargasewa');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_grafik_lastmonth(){
		$start_date 	=  date("Y-m-d", strtotime("first day of previous month"));
		$end_date 		=  date("Y-m-d", strtotime("last day of previous month"));
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_total_hargasewa) as total_hargasewa');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_grafik_thismonth($year,$month){
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_total_hargasewa) as total_hargasewa');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_grafik_lastdays(){
		$this->db->select('DATE_FORMAT(rental_created, "%Y-%m-%d") as labels, count(rental_order.rental_order_id) as total_order,sum(rental_total_hargasewa) as total_hargasewa');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$result = $this->db->get('rental_order')->result_array();

		$this->db->select('sum(rental_product_qty) as total_kostum');
		$this->db->join('rental_order','rental_product.rental_order_id = rental_order.rental_order_id','left');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		$this->db->group_by('DATE_FORMAT(rental_created, "%Y-%m-%d")');
		$get_kostum = $this->db->get('rental_product')->result_array();

		if(!empty($get_kostum)){
			foreach($get_kostum as $index => $value){
				if(isset($result[$index])){
					$result[$index]['total_kostum'] = $value['total_kostum'];
				}
			}
		}

		return $result;
	}

	public function get_report_order_custom($start,$end){
		$this->db->select('rental_order_id,rental_created');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_order_lastdays(){
		$this->db->select('rental_order_id,rental_created');
		$this->db->where('rental_created BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_order_thismonth($year,$month){
		$this->db->select('rental_order_id,rental_created');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m")', $year.'-'.$month);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_order_lastmonth(){
		$start_date 	=  date("Y-m-d", strtotime("first day of previous month"));
		$end_date 		=  date("Y-m-d", strtotime("last day of previous month"));
		$this->db->select('rental_order_id,rental_created');
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") >=',$start_date);
		$this->db->where('DATE_FORMAT(rental_created, "%Y-%m-%d") <=',$end_date);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_order_year($year){
		$this->db->select('rental_order_id,rental_created');
		$this->db->where('DATE_FORMAT(rental_created,"%Y")', $year);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_sum($id){
		$this->db->select_sum('rental_total_hargasewa','sum');
		$this->db->where_in('rental_order_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_report_order_product($id){
		$this->db->select_sum('rental_product_qty','items');
		$this->db->where_in('rental_order_id',$id);
		return $this->db->get('rental_product')->result_array();
	}

	public function get_invoice_footer(){
		$this->db->select('setting_value_textarea');
		$this->db->where('setting_name','invoice_footer');
		return $this->db->get('setting')->result_array();
	}
	
	public function get_category_sizestock($size){
		$this->db->select('category_sizestock');
		$this->db->where('category_id',$size);
		return $this->db->get('category_sizestock')->result_array();
	}

	public function get_sizestock_category(){
		$this->db->select('product_size');
		$this->db->group_by('product_size');
		return $this->db->get('product_sizestock')->result_array();
	}
	
	public function get_total_penjualan_order_by_today($date){
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d")', $date);
		$this->db->where('rental_active',1);
		return $this->db->count_all_results('rental_order');
	}	

	public function get_total_penjualan_kostum_by_today($date){
		$this->db->select('SUM(rental_product_qty) as total');
		$this->db->join('rental_product','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d")', $date);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_total_penjualan_rupiah_by_today($date){
		$this->db->select('SUM(rental_total_hargasewa) as total');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d")', $date);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_total_penjualan_order_by_month1($first,$last){
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") >=', $first);
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") <=', $last);
		$this->db->where('rental_active',1);
		return $this->db->count_all_results('rental_order');
		//return $this->db->get_compiled_select('rental_order');
	}	

	public function get_total_penjualan_order_by_month($first,$last){
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") >=', $first);
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") <=', $last);
		$this->db->where('rental_active',1);
		return $this->db->count_all_results('rental_order');
	}	

	public function get_total_penjualan_kostum_by_month($first,$last){
		$this->db->select('SUM(rental_product_qty) as total');
		$this->db->join('rental_product','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") >=', $first);
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") <=', $last);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_total_penjualan_rupiah_by_month($first,$last){
		$this->db->select('SUM(rental_total_hargasewa) as total');
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") >=', $first);
		$this->db->where('DATE_FORMAT(rental_created,"%Y-%m-%d") <=', $last);
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function get_total_penjualan(){
		$this->db->select('SUM(rental_total_hargasewa) as total');
		$this->db->where('rental_active',1);
		return $this->db->get('rental_order')->result_array();
	}

	public function update_categories(){
		$this->db->select('product.product_id,filter_product');
		$this->db->join('product','product.product_id = product_category_detil.product_id','left');
		$this->db->where_in('category_id',array(26,35,39,32,48,42));
		$this->db->group_by('product_category_detil.product_id');
		return $this->db->get('product_category_detil')->result_array();
	}
	public function get_correction(){
		$this->db->select('Id');
		return $this->db->get('correction')->result_array();
	}
	
	public function check_slug_product($slug){
		$this->db->where('product_slug',$slug);
		$query = $this->db->get('product');
		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}
	
	public function check_slug_category($slug){
		$this->db->where('category_slug',$slug);
		$query = $this->db->get('category');
		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}
	
	public function get_category_by_product($id){
		$this->db->select('category_slug,flag');
		$this->db->join('category','product_category_detil.category_id = category.category_id','left');
		$this->db->where('product_id',$id);
		$this->db->order_by('product_category_sort','asc');
		return $this->db->get('product_category_detil')->result_array();
	}
	
	public function _get_last_invoice_number($id){

		$result = array();

		$this->db->select('rental_invoice,category_name');
		$this->db->join('category','category.category_id = rental_order.store_location_category_id','left');
		$this->db->where('category.category_id',$id);
		$this->db->order_by('rental_order_id','desc');
		$rental_order = $this->db->get('rental_order');
		if($rental_order->num_rows() > 0){
			foreach($rental_order->result_array() as $index => $value){
				return $result = $value['rental_invoice'];
			}
		}
		if(empty($result['rental_order'])){
			$this->db->select('category_value_text');
			$this->db->where('category_id',$id);
			$result['category'] = $this->db->get('category')->result_array();
		}

		return $result;
		/*if($query->num_rows() > 0){
			foreach($query->result_array() as $index => $value){
				return $value['rental_invoice'];
			}
		} else {
			return false;
		}*/
	}
	
	public function get_email_customer($id){
		$this->db->select('customer_email');
		$this->db->where('customer_id',$id);
		return $this->db->get('customer')->result_array();
	}

	public function get_rental_note($id){
		$this->db->select('rental_note');
		$this->db->where('rental_order_id',$id);
		return $this->db->get('rental_order')->result_array();
	}

	public function existing_return_order($id){
		$this->db->where('rental_order_id',$id);
		$query = $this->db->get('return_order');

		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}

	/*public function get_last_invoice_number($id){
		$this->db->select('rental_invoice,category_name');
		$this->db->join('category','category.category_id = rental_order.store_location_category_id','left');
		$this->db->order_by('rental_order_id','desc');
		$this->db->where('store_location_category_id',$id);
		$query = $this->db->get('rental_order');
		if($query->num_rows() > 0){
			foreach($query->result_array() as $index => $value){
				$result = array('rental_invoice' => $value['rental_invoice'],'category_name' => $value['category_name']);
				return $result;
			}
		} else {
			return false;
		}
	}*/

	public function get_last_invoice_number(){
		$this->db->select('rental_invoice,category_name');
		$this->db->join('category','category.category_id = rental_order.store_location_category_id','left');
		$this->db->order_by('rental_order_id','desc');
		$query = $this->db->get('rental_order');
		if($query->num_rows() > 0){
			foreach($query->result_array() as $index => $value){
				return $value['rental_invoice'];
			}
		} else {
			return false;
		}
	}

	public function product_active($active = 1){
		$this->db->from('product');
		$this->db->where('product_active',$active);
		return $this->db->count_all_results();
	}

	public function order_active($active = 1){
		$this->db->from('rental_order');
		$this->db->where('rental_active',$active);
		return $this->db->count_all_results();
	}
	//public function createSlug($table,$id,$name)
	public function createSlug($table,$id_table = '',$id_value = '',$slug_field = '',$name)
	{

		$count = 0;

		$name = url_title($name);

		$slug_name = $name;             

		while(true) 

		{

			$this->db->where($id_table.' !=', $id_value);

			$this->db->where($slug_field, $slug_name);   

			$query = $this->db->get($table);

			if ($query->num_rows() == 0) break;

			$slug_name = $name . '-' . (++$count);  

		}

		return strtolower($slug_name);

	}

	public function get_join($table,$where = '',$select,$join_array = array(),$order_array = array(),$join_position = 'left'){
		$this->db->select($select);
		if(!empty($join_array)){
			foreach($join_array as $index => $value){
				$this->db->join($index,$value,$join_position);
			}
		}
		if(!empty($order_array)){
			foreach($order_array as $index => $value){
				$this->db->order_by($index,$value);
			}
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		return $this->db->get($table);
	}

	public function get_join_by_id($table,$where = '',$select,$join_array = array(),$order_array = array(),$join_position = 'left'){
		$this->db->select($select);
		if(!empty($join_array)){
			foreach($join_array as $index => $value){
				$this->db->join($index,$value,$join_position);
			}
		}
		if(!empty($order_array)){
			foreach($order_array as $index => $value){
				$this->db->order_by($index,$value);
			}
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		return $this->db->get($table);
	}

	public function filtering_return_list($store_location = '',$flag = '',$start_date,$end_date){

		if($flag == 'start'){
			$this->db->order_by('rental_start_date','asc');
		} elseif($flag == 'end'){
			$this->db->order_by('rental_end_date','asc');
		} else {
			$this->db->order_by('rental_created','desc');
		}
		$this->db->where('rental_active',1);
		//$this->db->where('rental_status','pickup');
		if(!empty($store_location)){
			$this->db->where('store_location_category_id',$store_location);
		}
		if($flag == 'start'){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$end_date);
		}elseif($flag == 'end'){
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date);
		}
		return $this->db->get('rental_order');
	}

	public function filtering_return_list_with_limit($store_location = '',$flag = '',$start_date,$end_date,$limit = 0){

		if($flag == 'start'){
			$this->db->order_by('rental_start_date','asc');
		} elseif($flag == 'end'){
			$this->db->order_by('rental_end_date','asc');
		} else {
			$this->db->order_by('rental_created','desc');
		}
		$this->db->where('rental_active',1);
		//$this->db->where('rental_status','pickup');
		if(!empty($store_location)){
			$this->db->where('store_location_category_id',$store_location);
		}
		if($flag == 'start'){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$end_date);
		}elseif($flag == 'end'){
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date);
		}
		if($limit > 0){
			$this->db->limit($limit);
		}
		return $this->db->get('rental_order');
	}

	public function get_product_with_order_and_return($store_location = ''){
		$this->db->select('product.product_id,product.product_isipaket,rental_product.rental_order_id,product_nama,product_kode,product_sizestock.product_estimasiukuran,product_sizestock.product_size,product_sizestock.product_stock,product_sizestock.product_sizestock_id,rental_product_qty,rental_product.rental_product_sizestock_id');
		$this->db->join('product_sizestock','product.product_id = product_sizestock.product_id','left');
		$this->db->join('rental_product','rental_product.rental_product_sizestock_id = product_sizestock.product_sizestock_id','left');
		if(!empty($store_location)){
			$this->db->select('category.category_id');
			$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
			$this->db->join('category','product_category_detil.category_id = category.category_id','left');
			$this->db->where('category.category_id',$store_location);
		}
		return $this->db->get('product');
	}

	public function count_all_rental_order(){
		$this->db->from('rental_order');
		return $this->db->count_all_results();
	}	

	public function count_all_product(){
		$this->db->from('product');
		return $this->db->count_all_results();
	}

	public function get_thumbnail_product($id){
		$this->db->select('product_image');
		$this->db->where('product_id',$id);
		return $this->db->get('product_image')->result_array();
	}

	/*public function count_all_rented(){
		$this->db->from('rental_order');
		return $this->db->count_all_results();
	}		*/

	public function get_all_rental_order($store_location = ''){

		$this->db->select('product.product_id,product_nama,product_kode,product_sizestock_id,product_size,product_isipaket,product_estimasiukuran,product_stock,rental_product_qty,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,return_date');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		//$this->db->join('rental_product','product.product_id = rental_product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($store_location)){
			$this->db->select('category.category_id');
			$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
			$this->db->join('category','product_category_detil.category_id = category.category_id','left');
			$this->db->where('category.category_id',$store_location);
		}
		$this->db->order_by('product.product_nama','asc');
		return $this->db->get('product');
	}

	public function get_most_rented_product1($filter = '',$start_date = '',$end_date = ''){
		$this->db->select('product.product_id,product_nama,product_kode,rental_product_qty,rental_start_date,rental_end_date');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		//$this->db->join('rental_product','product.product_id = rental_product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');

		/*$this->db->where('sell_date  "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');*/

		if($filter == 'date'){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
			//$this->db->or_where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$start_date,NULL,FALSE);

			//$this->db->or_where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") >=',$end_date,NULL,FALSE);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
		}
		$this->db->where('rental_active',1);
		//$this->db->order_by('product.product_nama','asc');
		return $this->db->get('product');
	}

	public function get_most_rented_product($search,$filter = '',$start_date = '',$end_date = '',$ordername,$order_by,$start,$offset){
		$this->db->select('product.product_id,product_nama,product_kode,rental_product_qty,rental_start_date,rental_end_date');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$order = array(
			'product_order' => 'asc',
			'product_created' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
		}
		if($filter == 'date'){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
		}
		if(!empty($ordername) && !empty($order_by)){
			$this->db->order_by($ordername,$order_by);
		} else {
			foreach($order as $index => $value){
				$this->db->order_by($index,$value);
			} 
		}
		//$this->db->limit($offset,$start);
		$this->db->where('rental_active',1);
		return $this->db->get('product')->result_array();
	}

	/**
	 * Most rented method for dashboard widget
	 *
	 * this method created by Julian
	 *
	 **/
	public function mostRentedWigdet($search,$filter = '',$start_date = '',$end_date = '',$ordername,$order_by,$start,$offset)
	{
		$this->db->select_sum('rental_product.rental_product_qty', 'rented');
		$this->db->select('product.product_nama, product.product_kode');
		$this->db->join('rental_product', 'product.product_id = rental_product.product_id');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id');
		$this->db->group_by('rental_product.product_id');
		$this->db->where('rental_order.rental_active',1);
		if(!empty($search)){
			// $this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			// $this->db->group_end();
		}
		if($filter == 'date'){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date,NULL,FALSE);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date,NULL,FALSE);
		}
		if(!empty($ordername) && !empty($order_by)){
			if($ordername == 'product_code'){
				$this->db->order_by('product.product_kode',$order_by);
			}elseif($ordername == 'product_name'){

			}else{
				$this->db->order_by($ordername,$order_by);
			}
		} else {
			$this->db->order_by('SUM(rental_product.rental_product_qty)', 'DESC');
		}

		$this->db->limit($offset, $start);


		$result = $this->db->get('product')->result_array();
		return $result;
	}

	/**
	 * Most rented method for dashboard widget Count all
	 *
	 * this method created by Julian
	 *
	 **/
	public function countMostRentedWigdet()
	{
		$this->db->group_by('product_id');
		$result = $this->db->get('rental_product');
		return $result->num_rows();
	}

	public function count_most_rented_product(){
		$this->db->select('product.product_id,rental_product_qty');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->where('rental_active',1);
		return $this->db->get('product');
	}

	public function countAllRentaltrash(){
		$this->db->where('rental_active',0);
		return $this->db->count_all_results('rental_order');
	}

	public function countAllRentalorder($search){
		$this->db->group_start();
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('rental_active',1);
		} else {
			$this->db->where('rental_active',1);
		}
		$this->db->group_end();
		return $this->db->count_all_results('rental_order');
	}

	public function countAllRentalorderFiltered($search){
		$this->db->group_start();
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('rental_active',1);
		} else {
			$this->db->where('rental_active',1);
		}
		$this->db->group_end();
		return $this->db->count_all_results('rental_order');
	}

	public function countRentalorderDefault($search,$status){
		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->group_end();
			$this->db->where('rental_active',1);
		} else {
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->where('rental_active',1);
		}
		return $this->db->count_all_results('rental_order');
	}

	public function AllRentalorderDefault($search,$ordername,$order_by,$status,$start,$offset){


		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->group_end();
			$this->db->where('rental_active',1);
		} else {
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->where('rental_active',1);
		}
		if(!empty($ordername) && !empty($order_by)){
			$this->db->order_by($ordername,$order_by);
			if(!empty($orderfield) && !empty($orderfield_by)){
				$this->db->order_by($orderfield,$orderfield_by);
			}
		} else {
			foreach($order as $index => $value){
				$this->db->order_by($index,$value);
			} 
			if(!empty($orderfield) && !empty($orderfield_by)){
				$this->db->order_by($orderfield,$orderfield_by);
			}
		}
		$this->db->limit($offset,$start);
		return $this->db->get('rental_order')->result_array();
	}

	public function AllRentalorderSort($search,$due,$current,$ordername,$order_by,$status,$start,$offset){
		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
		}
		if($due == 'due_return'){
			$this->db->where('rental_status','pickup');
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <',$current);
		}
		if($due == 'due_pickup'){
			$this->db->where('rental_status','booked');
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$current);
		}
		$this->db->where('rental_active',1);
		if(!empty($ordername) && !empty($order_by)){
			$this->db->order_by($ordername,$order_by);
			if(!empty($orderfield) && !empty($orderfield_by)){
				$this->db->order_by($orderfield,$orderfield_by);
			}
		} else {
			foreach($order as $index => $value){
				$this->db->order_by($index,$value);
			} 
			if(!empty($orderfield) && !empty($orderfield_by)){
				$this->db->order_by($orderfield,$orderfield_by);
			}
		}
		$this->db->limit($offset,$start);
		return $this->db->get('rental_order')->result_array();
	}

	public function countRentalorderSort($search,$due,$current){
		
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
		}
		$this->db->where('rental_active',1);
		if($due == 'due_return'){
			$this->db->where('rental_status','pickup');
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <',$current);
		}
		if($due == 'due_pickup'){
			$this->db->where('rental_status','booked');
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$current);
		}
		return $this->db->count_all_results('rental_order');
	}

	public function countRentalorderTrashDefault($search,$status){
		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->group_end();
			$this->db->where('rental_active',0);
		} else {
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->where('rental_active',0);
		}
		return $this->db->count_all_results('rental_order');
	}

	public function AllRentalorderTrashDefault($search,$ordername,$order_by,$status,$start,$offset){
		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->group_end();
			$this->db->where('rental_active',0);
		} else {
			if(!empty($status)){
				$this->db->or_where("(`rental_status` LIKE '%$status%')",NULL,FALSE);	
			}
			$this->db->where('rental_active',0);
		}
		if(!empty($ordername) && !empty($order_by)){
			$this->db->order_by($ordername,$order_by);
			if(!empty($orderfield) && !empty($orderfield_by)){
				$this->db->order_by($orderfield,$orderfield_by);
			}
		} else {
			foreach($order as $index => $value){
				$this->db->order_by($index,$value);
			} 
			if(!empty($orderfield) && !empty($orderfield_by)){
				$this->db->order_by($orderfield,$orderfield_by);
			}
		}
		$this->db->limit($offset,$start);
		return $this->db->get('rental_order')->result_array();
	}

	public function AllRentalorderTrashSort($search){
		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_payment_status` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_hargasewa` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_total_deposit` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`rental_created` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('rental_active',0);
		} else {
			$this->db->where('rental_active',0);
		}
		foreach($order as $index => $value){
			$this->db->order_by($index,$value);
		}
		return $this->db->get('rental_order')->result_array();
	}

	public function countAllBookingList($start_date,$end_date,$flag = '',$store_location = ''){
		$this->db->where('rental_active',1);
		if($flag == 'start'){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$end_date);
		}elseif($flag == 'end'){
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date);
		}
		if(!empty($store_location)){
			$this->db->where('store_location_category_id',$store_location);
		}
		return $this->db->count_all_results('rental_order');
	}

	public function AllBookingList($search,$start_date,$end_date,$flag = '',$store_location = ''){
		$order = array(
			'rental_created' => 'desc',
			'rental_invoice' => 'desc'
		);
		$this->db->select('rental_created,rental_start_date,rental_end_date,rental_order.rental_order_id,
			rental_status,rental_invoice,customer_name,customer_phone,
			rental_product_qty,rental_product_kode,
			rental_product_nama,rental_product_size');
		$this->db->join('rental_product','rental_product.rental_order_id = rental_order.rental_order_id');
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`rental_invoice` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`customer_name` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`customer_phone` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_nama` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_kode` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_status` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_nama` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_qty` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_hargasewa` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_deposit` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_kode` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_isipaket` LIKE '%$search%')",NULL,FALSE);
			$this->db->or_where("(`rental_product_size` LIKE '%$search%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('rental_active',1);
		} else {
			$this->db->where('rental_active',1);
		}
		if($flag == 'start' && !empty($start_date) && !empty($end_date)){
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$end_date);
		}elseif($flag == 'end' && !empty($start_date) && !empty($end_date)){
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_end_date, "%Y-%m-%d") <=',$end_date);
		} else {
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") >=',$start_date);
			$this->db->where('DATE_FORMAT(rental_start_date, "%Y-%m-%d") <=',$end_date);
		}
		if(!empty($store_location)){
			$this->db->where('store_location_category_id',$store_location);
		}
		foreach($order as $index => $value){
			$this->db->order_by($index,$value);
		} 
		return $this->db->get('rental_order');
	}

	public function AllProductData($search,$ordername,$order_by,$start,$offset){

		$order = array(
			'product_order' => 'asc',
			'product_created' => 'desc'
		);

		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('product_active',1);
		} else {
			$this->db->where('product_active',1);
		}
		if(!empty($ordername) && !empty($order_by)){
			$this->db->order_by($ordername,$order_by);
		} else {
			foreach($order as $index => $value){
				$this->db->order_by($index,$value);
			} 
		}
		$this->db->limit($offset,$start);

		return $this->db->get('product')->result_array();
	}

	public function countAllProductData($search){
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('product_active',1);
		} else {
			$this->db->where('product_active',1);
		}
		return $this->db->count_all_results('product');
	}

	public function AllProductDataTrash($search,$columnName,$columnSortOrder,$start,$offset){

		$order = array(
			'product_order' => 'asc',
			'product_created' => 'desc'
		);

		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('product_active',0);
		} else {
			$this->db->where('product_active',0);
		}
		if(!empty($ordername) && !empty($order_by)){
			$this->db->order_by($ordername,$order_by);
		} else {
			foreach($order as $index => $value){
				$this->db->order_by($index,$value);
			} 
		}
		$this->db->limit($offset,$start);

		return $this->db->get('product')->result_array();
	}

	public function countAllProductDataTrash($search){
		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
			$this->db->where('product_active',0);
		} else {
			$this->db->where('product_active',0);
		}
		return $this->db->count_all_results('product');
	}

	public function AllStockList($search,$store_location = ''){

		$order = array(
			'product_nama' => 'asc',
		);

		$this->db->select('product.product_id,product_nama,product_kode,product_sizestock_id,product_size,product_isipaket,product_estimasiukuran,product_stock,rental_product_qty,rental_order.rental_order_id,rental_order.rental_start_date,rental_order.rental_end_date,return_date');
		$this->db->join('product_sizestock','product_sizestock.product_id = product.product_id','left');
		$this->db->join('rental_product','product_sizestock.product_sizestock_id = rental_product.rental_product_sizestock_id','left');
		$this->db->join('rental_order','rental_order.rental_order_id = rental_product.rental_order_id','left');
		$this->db->join('return_order','return_order.rental_order_id = rental_order.rental_order_id','left');
		if(!empty($store_location)){
			$this->db->select('category.category_id');
			$this->db->join('product_category_detil','product_category_detil.product_id = product.product_id','left');
			$this->db->join('category','product_category_detil.category_id = category.category_id','left');
		}

		if(!empty($search)){
			$this->db->or_group_start();
			$this->db->or_where("(`product_nama` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_kode` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_isipaket` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_estimasiukuran` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->or_where("(`product_size` LIKE '%".$this->db->escape_like_str($search)."%')",NULL,FALSE);
			$this->db->group_end();
		}
		if(!empty($store_location)){
			$this->db->where('category.category_id',$store_location);
		}
		$this->db->order_by('product_nama','asc');

		return $this->db->get('product')->result_array();
	}
}

?>
