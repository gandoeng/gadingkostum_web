<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_user_model extends CI_Model {

	public function check_username($username){
		$this->db->where('username',$username);
		$query = $this->db->get('users');

		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}

	public function check_email($email){
		$this->db->where('email',$email);
		$query = $this->db->get('users');

		if($query->num_rows() > 0){
			return true;
		} else {
			return false;
		}
	}

	public function getDataUser(){

		$this->db->order_by('created_date','desc');
		return $this->db->get('users')->result_array();

	}


}

?>