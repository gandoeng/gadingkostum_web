<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories_menu_lib
{

	public $data;

	public function __construct()
	{

		$this->CI =& get_instance();

		$this->CI->load->model('front_model');

		$this->CI->load->helper('html');

	}

	function menu($section = ''){

		$category 						= $this->CI->front_model->get_nested_categories($section);

		$categories		= array();

		$gender 		= array();

		$size			= array();

		$store_location	= array();

		if(!empty($category)){

			foreach($category as $index => $value){

				$category_flag = $value['category_flag'];

				switch ($category_flag) {

					case "product":

					$categories[]		= $value;

					break;

				}



			}

		}

		$result 					= array();

		$result						= $this->fetch_menu($categories);

		return $result;

	}

	function fetch_menu($data,$title = ''){

		$template = '';

		if(is_array($data) && !empty($data)){

			$template .= '<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">';

			foreach($data as $index => $menu){

				if(!empty($menu['sub'])){

					$template .= '<li class="dropdown-submenu dropdown">';
					$template .= '<a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown">'.strtoupper($menu['category_name']).'</a>';
					$template .= '<ul class="dropdown-menu">';
					$template .= $this->fetch_sub_menu($menu['sub']);
					$template .= '</ul>';
					$template .= '</li>';

				} else{

					$template .= '<li><a class="dropdown-item" href="'.base_url('categories/1').'?'.$menu['category_flag'].'='.$menu['category_slug'].'">'.strtoupper($menu['category_name']).'</a></li>';


				}

			}

			$template .= '</ul>';
		}

		return $template;

	}



	//update 22 januari 2019
	// command dulu ganti yang dibawah nya
	/*function fetch_sub_menu($sub_menu){

		$template = '';

		if(is_array($sub_menu) && !empty($sub_menu)){
			foreach($sub_menu as $index => $menu){
				if(!empty($menu['sub'])){

					$template .= $this->fetch_sub_menu($menu['sub']);

				} else{

					$template .= '<li><a href="'.base_url('categories/1').'?'.$menu['category_flag'].'='.$menu['category_slug'].'">'.strtoupper($menu['category_name']).'</a></li>';

				}
			}
		}

		return $template;

	}*/

	function fetch_sub_menu($sub_menu){

		$template 			= '';

		$all_link_submenu   = '';

		if(is_array($sub_menu) && !empty($sub_menu)){

			$get_menu = array();

			foreach($sub_menu as $index => $menu){

				if(empty($menu['sub'])){

					$get_menu[$index] = $menu['category_slug'];

					if(!empty($get_menu)){
						$all_link_submenu = '?'.$menu['category_flag'].'='.implode('||',$get_menu);
					} else{
						if($index == 0){
							$all_link_submenu = $menu['category_slug'];
						}
					}
				}
			}

			foreach($sub_menu as $index => $menu){

				if(!empty($menu['sub'])){

					$template .= '<li class="dropdown-submenu dropdown" aria-labelledby="navbarDropdownMenuLink">';
					$template .= '<a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown">'.ucwords(strtolower($menu['category_name'])).'</a>';
					$template .= '<ul class="dropdown-menu">';

					/*$template .= '<li><a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown-menu">'.ucwords(strtolower($menu['category_name'])).'</li></a>';*/

					$template .= $this->fetch_sub_menu($menu['sub'],$section);
					$template .= '</ul>';
					$template .= '</li>';

					//$template .= $this->fetch_sub_menu($menu['sub'],$section);

				} else{

					if($index == 0 && $menu['category_id'] == $menu['category_same_parent']){
						$template .= '<li>
						<a class="dropdown-item" href="'.base_url('categories/'.$all_link_submenu).'">All '.ucwords(strtoupper($menu['category_name'])).'</a></li>';
					} else {	
						$template .= '<li><a class="dropdown-item" href="'.base_url('categories/1').'?'.$menu['category_flag'].'='.$menu['category_slug'].'">'.strtoupper($menu['category_name']).'</a></li>';
					}

				}
			}
		}

		return $template;
	}
}
?>