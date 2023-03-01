<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller{
	
	public function __construct()
	{

		parent::__construct();

		$this->load->model('backend_model');

		$this->load->model('global_model');

		$this->load->helper('url');

	}


	public function index(){

		$this->load->view('import');
	}

	public function add(){

		if(isset($_POST["Import"]))
		{
			$filename=$_FILES["file"]["tmp_name"];
			if($_FILES["file"]["size"] > 0)
			{
				$file = fopen($filename, "r");
				$emapData = fgetcsv($file, 10000, ",");

				$result = array();
				$data = array();
				$product_category = array();
				$category = array();

				while(! feof($file))
				{
					$data = fgetcsv($file);
					$product_category[] = explode("\n",$data[10]);
					$product_size 			= explode("\n",$data[7]);
					$product_stock 			= explode("\n",$data[8]);
					$product_estimasiukuran = explode("\n",$data[9]);
					$sizestock_product_size 				= array();
					$sizestock_product_stock 				= array();
					$sizestock_product_estimasiukuran 		= array();
					$no = 1;
					foreach($product_size as $index => $value){
						if(array_key_exists($index,$product_estimasiukuran) || array_key_exists($index,$product_stock)) {
							$sizestock_product_size[$index] = $value;
						} elseif(!array_key_exists($index,$product_size) || !array_key_exists($index,$product_estimasiukuran)){
							$i = $index + 1;
							$sizestock_product_size[$no] = $value;
							$sizestock_product_stock[$no] = 0;
							$sizestock_product_estimasiukuran[$no] = ' ';
						}
						$no++;
					}
					$no = 1;
					foreach($product_stock as $index => $value){
						if(array_key_exists($index,$product_size) || array_key_exists($index,$product_estimasiukuran)) {
							$sizestock_product_stock[$index] = $value;
						} elseif(!array_key_exists($index,$product_size) || !array_key_exists($index,$product_estimasiukuran)){
							$i = $index + 1;
							if(empty($value)){
								$value = 0;
							}
							$sizestock_product_stock[$no] = $value;
							$sizestock_product_size[$no] = ' ';
							$sizestock_product_estimasiukuran[$no] = ' ';
						}
						$no++; 	
					}
					$no = 1;
					foreach($product_estimasiukuran as $index => $value){
						if(array_key_exists($index,$product_size) || array_key_exists($index,$product_stock)) {
							$sizestock_product_estimasiukuran[$index] = $value;
						} elseif(!array_key_exists($index,$product_size) || !array_key_exists($index,$product_stock)) {
							$i = $index + 1;
							$sizestock_product_estimasiukuran[$no] = $value;
							$sizestock_product_size[$no] = ' ';
							$sizestock_product_stock[$no] = 0;
						}	
						$no++; 
					}

					$result[] = array(
						'product_nama' 		=> $data[0],
						'product_kode' 		=> $data[1],
						'product_isipaket' 	=> $data[4],
						'product_hargasewa' => $data[5],
						'product_deposit' 	=> $data[6],
						'product_size' 		=> $sizestock_product_size,
						'product_stock'		=> $sizestock_product_stock,
						'product_estimasiukuran' => $sizestock_product_estimasiukuran, 
						'category' 			=> array(
							explode("\n",$data[10]),
							explode("\n",$data[11]),
							explode("\n",$data[12]),
							$data[14]
							//'product' 		=> explode("\n",$data[10]),
							//'size' 			=> explode("\n",$data[11]),
							//'gender' 		=> explode("\n",$data[12]),
							//'store_location'=> $data[14]
							),
						'product_slug'		=> str_replace('/product/','',$data[18])
						);

					$category[] = array(explode("\n",$data[10]),
						explode("\n",$data[11]),
						explode("\n",$data[12]),
						$data[14]);
					/*$result[] = array(
							'nama' 			=> $data[1],
							'kode' 			=> $data[2],
							'isi_paket' 	=> $data[5],
							'biaya_sewa' 	=> $data[6],
							'biaya_deposit' => $data[7],
							'6' => $data[6],
							'7' => $data[7],
							'8' => $data[8],
							'9' => $data[9],
							'10'=> $data[10],
							'11'=> $data[11],
							'12'=> $data[12],
							'13'=> $data[13],
							'14'=> $data[14],
							'15'=> $data[15],
							'16'=> $data[16],
							'17'=> $data[17],
							'18'=> $data[18],
							'19'=> $data[19]
							);*/
					/*$address = '<p>'.$data[1].'</p><p>'.$data[2].'</p><p>'.$data[3].'</p><p>'.$data[4].'</p><p>'.$data[5].'</p><p>'.$data[6].'</p>';
					$result[] = array(
						'lokasi_name' => $data[0],
						'lokasi_address' => $address,
						'lokasi_prop_id' => 9,
						'lokasi_cat_id' => 2,
						'lokasi_sub_cat_id' => 3,
						'lokasi_created' => date('Y-m-d H:i:s')
						);*/

					}

					fclose($file);
				/*$product_categories = array();
				foreach($product_category as $index => $value){
					foreach($value as $key => $row){
						if(!empty($row)){
						$product_categories[$row] = $row;
						}	
					}
				}*/

				
				$dataresult = array();
				$temp = array();
				$da = array();
				$insert_category = array();
				$insert_sizestock = array();
				foreach($category as $index => $value){


					foreach($value as $key => $row){


						if(is_array($row) && !empty($row)){
							foreach($row as $k => $v){
								$type = '';
								if($key == 0){
									$type = 'product';
								}
								if($key == 1){
									$type = 'size';
								}
								if($key == 2){
									$type = 'gender';
								}
								if($key == 3){
									$type = 'store_location';
								}
								if(!empty($v)){
									$insert_category[$v] = array(
										'category_name' 	=> $v,
										'category_flag'		=> $type,
										'category_status' 	=> 1,
										'category_created'  => date('c'),
										'category_modified' => NULL
										);
								}
							}
						}

						if(!is_array($row) && !empty($row)){
							if($key == 3){
								$type = 'store_location';
							}

							$insert_category[$row] = array(
								'category_name' 	=> $row,
								'category_flag'		=> $type,
								'category_status' 	=> 1,
								'category_created'  => date('c'),
								'category_modified' => NULL
								);
						}
					}
				}

				/*foreach($insert_category as $index => $value){
						$inserted_category = $value;
						$this->global_model->insert('category',$inserted_category);
						$id = $this->db->insert_id();
						foreach($inserted_category as $key => $values){
						$slug = $this->backend_model->createSlug('category','category_id',$id,'category_slug',$value['category_name']);
						$insert_slug = array(
							'category_slug' => $slug
							);
						$this->global_model->update('category',$insert_slug,array('category_id' => $id));
					}
				}*/

				foreach($result as $index => $value){
					/*$dataresult['product'][$index] = array(
						'product_nama' 				=> $value['product_nama'],
						'product_kode' 				=> $value['product_kode'],
						'product_isipaket' 			=> $value['product_isipaket'],
						'product_hargasewa'			=> preg_replace("/[^0-9\.]/","",$value['product_isipaket']),
						'product_deposit'			=> preg_replace("/[^0-9\.]/","",$value['product_deposit']),
						'product_metatitle'			=> '',
						'product_metakeyword'		=> '',
						'product_metadescription'	=> '',
						'product_slug'				=> $value['product_slug'],
						'product_featured' 			=> 0,
						'product_status'			=> 1,
						'product_active'			=> 1,
						'product_created'			=> date('c'),
						'product_modified'			=> NULL
						);*/
						$insert_product = array(
							'product_nama' 				=> $value['product_nama'],
							'product_kode' 				=> $value['product_kode'],
							'product_isipaket' 			=> $value['product_isipaket'],
							'product_hargasewa'			=> preg_replace("/[^0-9\.]/","",$value['product_hargasewa']),
							'product_deposit'			=> preg_replace("/[^0-9\.]/","",$value['product_deposit']),
							'product_metatitle'			=> '',
							'product_metakeyword'		=> '',
							'product_metadescription'	=> '',
							'product_slug'				=> $value['product_slug'],
							'product_featured' 			=> 0,
							'product_status'			=> 1,
							'product_active'			=> 1,
							'product_created'			=> date('c'),
							'product_modified'			=> NULL
							);
						$this->global_model->insert('product',$insert_product);
						$id_product = $this->db->insert_id();
					//PRODUCT
						if(isset($value['product_size']) && is_array($value['product_size'])){
							
							foreach($value['product_size'] as $key => $values){
								if(isset($value['product_stock'][$key]) && is_array($value['product_stock']) && isset($value['product_estimasiukuran'][$key]) && is_array($value['product_estimasiukuran'])){
									$insert_product_sizestock = array(
										'product_id' => $id_product,
										'product_size' => $values,
										'product_stock' => $value['product_stock'][$key],
										'product_estimasiukuran' => htmlentities($value['product_estimasiukuran'][$key])
										);
								$this->global_model->insert('product_sizestock',$insert_product_sizestock);
								}
							}
						}


						if(isset($value['category']) && is_array($value['category'])){

							foreach($value['category'] as $key => $values){

								if(isset($values) && is_array($values) && !empty($values)){

									foreach($values as $k => $v){
										if($key == 0){
											$type = 'product';
										}
										if($key == 1){
											$type = 'size';
										}
										if($key == 2){
											$type = 'gender';
										}
										if($key == 3){
											$type = 'store_location';
											$v    = $values;
										}

										if(!empty($v)){
										$category_db = $this->global_model->select_where('category',array('category_name' => $v));
										foreach($category_db as $ct => $vl){
											$insert_category = array(
											'product_id'				=> $id_product,
											'category_id' 			=> $vl['category_id'],
											);
											$this->global_model->insert('product_category_detil',$insert_category);
										}
										
									} 
								}
							}

							if(isset($values) && !is_array($values) && !empty($values)){

								if($key == 3){
									$type = 'store_location';
								}
								$categoryy_db = $this->global_model->select_where('category',array('category_name' => $values));
								foreach($categoryy_db as $ct => $vl){
								$inserted_category = array(
									'product_id'			=> $id_product,
									'category_id' 			=> $vl['category_id'],
									);
								}
								$this->global_model->insert('product_category_detil',$inserted_category);

							}
						}
					}
				}

				/*echo '<pre>';
								print_r($result);
								echo '</pre>';*/

				/*echo '<pre>';
				print_r($result);
				echo '</pre>';*/

				/*foreach($da as $index => $value){
					foreach($value as $key => $values){

						$kk = array(
									'product_size' => $value['product_size'][0],
									'product_stock'=> $value['product_stock'][0],
									'product_estimasiukuran' => $value['product_estimasiukuran'][0]
								);
					}
				}*/

				/*if(!empty($result)){

					foreach($result as $index => $value){

						if(!empty($value['lokasi_name'])){

						$data = array(

								'lokasi_name' => $value['lokasi_name'],
								'lokasi_address' => $value['lokasi_address'],
								'lokasi_prop_id' => $value['lokasi_prop_id'],
								'lokasi_cat_id' => $value['lokasi_cat_id'],
								'lokasi_created' => $value['lokasi_created'],
								'lokasi_status' => 1

							);

						echo '<pre>';

						print_r($data);

						echo '</pre>';

						$insert = $this->global_model->insert('lokasi',$data);

						if($insert){

							$this->session->set_flashdata('message','SUKSES BOSSSSS');
						
						}

							


						}

					}

				}*/

				//redirect('import','refresh');
			}
		}

	}

	function array_insert(&$array, $position, $insert)
	{
		if (is_int($position)) {
			array_splice($array, $position, 0, $insert);
		} else {
			$pos   = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
				);
		}
	}

	public function edit(){

		$uri = $this->uri->segment(3);

		$this->db->select('lokasi_id,lokasi_name,lokasi_address,lokasi_latitude,lokasi_longitude,lokasi_propinsi_name');
		$this->db->join('lokasi_category','lokasi_category.lokasi_category_id = lokasi.lokasi_cat_id','left');
		$this->db->join('lokasi_propinsi','lokasi_propinsi.lokasi_propinsi_id = lokasi.lokasi_prop_id','left');
		$this->db->where('lokasi_cat_id',$uri);
		//$this->db->where('lokasi_id >',635);
		$data['lokasi'] = $this->db->get('lokasi')->result_array();

		$this->load->view('import_edit',$data);

	}

	public function update(){

		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
		/*$lokasi_id = $this->input->post('lokasi_id',true);
		$lokasi_name = $this->input->post('lokasi_name',true);
		$lokasi_address = $this->input->post('lokasi_address',true);
		$lokasi_latitude = $this->input->post('lokasi_latitude',true);
		$lokasi_longitude = $this->input->post('lokasi_longitude',true);

		if(!empty($lokasi_id)){

			foreach($lokasi_id as $index => $value){

				$get_slug = $this->createSlug($lokasi_id[$index],$lokasi_name[$index]);

				$data = array(

						'lokasi_name' => $lokasi_name[$index],
						'lokasi_address' => htmlspecialchars($lokasi_address[$index]),
						'lokasi_latitude' => $lokasi_latitude[$index],
						'lokasi_longitude' => $lokasi_longitude[$index],
						'lokasi_slug' => $get_slug,
						'lokasi_status' => 1

					);

				$update = $this->global_model->update('lokasi',$data,array('lokasi_id' => $lokasi_id[$index]));

				echo '<pre>';

				print_r($data);

				echo '</pre>';
			}

			if($update){

				$this->session->set_flashdata('message','SUKSES BOSSSSS UPDATE NYA');

			}

		}

		redirect('import','refresh');*/

	}

	/*public function createSlug($id,$name){

		$count = 0;
		$name = url_title($name);
		$slug_name = $name;             
		while(true) 
		{
			$this->db->where('lokasi_id !=', $id);
			$this->db->where('lokasi_slug', $slug_name);   
			$query = $this->db->get('lokasi');
			if ($query->num_rows() == 0) break;
			$slug_name = $name . '-' . (++$count);  
		}
		return strtolower($slug_name);
	}
*/
	/*public function get(){
		$this->db->where('lokasi_id >',697);
		$query = $this->db->get('lokasi')->result_array();

		echo '<pre>';

		print_r($query);

		echo '</pre>';
		foreach($query as $index => $value){

			$data = array(
					'lokasi_sub_cat_id' => 3
				);

			$this->db->update('lokasi',$data,array('lokasi_id' => $value['lokasi_id']));
		}
	}*/
}

?>