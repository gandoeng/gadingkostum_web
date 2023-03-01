<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



	function set_session($key,$data)

	{	



			$CI =& get_instance();



			if($data){



				if(!isset($key)){



					$CI->session->set_userdata($this->key_panel_session,$data);



				}



				$CI->session->set_userdata($key,$data);



				return TRUE;



			} else {



				return FALSE;



			}



	}



	function data_session($data){



		if(is_array($data) && !empty($data)){



			$result = array();



			/*foreach($data as $index => $value){



				$result[] = $value;



			}*/



			return $data;



		} else {



			return FALSE;

		}



	}



	function data_sess_panel($data){



		$CI =& get_instance();

		

		if(is_array($data) && !empty($data)){



			$result = array();



			/*foreach($data as $index => $value){



				$result[$CI->config->item('access_panel')] = $value;



			}
*/


			return $data;



		} else {



			return FALSE;

		}



	}


	function manipulate_output($output){
	    $ci =& get_instance();
	    $ci->load->library('dom_parser');
	    $raw = $ci->dom_parser->str_get_html($output);
	    
	    /* Manipulate img src for tinymce */
	    foreach(@$raw->find('img') as $element){
	        $src = $element->src;
	        if(isset($src) && (substr($src, 0, 1) == '/' || substr($src, 0, 2) == './' || substr($src, 0, 3) == '../')){
	            $parts = explode('/upload/', $src);
	            $slash = substr(base_url(), -1) == '/' ? '' : '/';
	            $element->src = base_url().$slash.'upload/'.end($parts);
	        }
	        // echo substr($src, 0, 1).'<br>';
	    }

	    /* Manipulate a href for tinymce */
	    foreach(@$raw->find('a') as $element){
	        $href = $element->href;
	        if(isset($href) && (substr($href, 0, 1) == '/' || substr($href, 0, 2) == './' || substr($href, 0, 3) == '../')){
	            $slash = substr(base_url(), -1) == '/' ? '' : '/';

	            if(substr($href, 0, 1) == '/'){
	                $href = substr($href, 1);
	            }else if(substr($href, 0, 2) == './'){
	                $href = substr($href, 2);
	            }else if(substr($href, 0, 3) == '../'){
	                $href = substr($href, 3);
	            }
	            
	            $element->href = base_url().$slash.$href;
	        }
	    }
	    
	    /* Display */
	    echo $raw;
	}

	function gadingkostumAsset()
	{
		return 'https://gadingkostum.com/';
	}

	function getBetweenDates($startDate, $endDate)
    	{
	        $rangArray = [];
            
	        $startDate = $startDate;
	        $endDate = $endDate;
             
	        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += 86400) {
	            $date = date('Y-m-d', $currentDate);
	            $rangArray[] = $date;
	        }
  
	        return $rangArray;
    	}
	
