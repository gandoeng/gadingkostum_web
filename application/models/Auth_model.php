<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{

	public function check_auth($username,$password){

		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->where('status',2);
		$query = $this->db->get('users');

		if($query->num_rows() == 1){

			return true;

		} else {

			return false;

		}

	}

	public function user_admin_by_login($username,$password,$table)
	{

		$this->db->where('username',$username);

		$this->db->where('password',$password);

		$this->db->where('status',2);

		$query = $this->db->get($table);

		return $query->result_array();

	}

	public function _prepare_ip($ip_address) {
		// just return the string IP address now for better compatibility
		return $ip_address;
	}

	public function user_admin_by_session($token = NULL,$table)
	{

		// if no id was passed use the current users id
		$token = isset($token) ? $id : $this->session->userdata('token');

		$this->db->limit(1);

		$query = $this->db->get($table)->result_array();

		if($query->num_rows > 0){

			return $query->result_array();

		} else {

			return FALSE;

		}

	}

	public function is_login($id,$token){

		$this->db->where('Id',$id);

		$this->db->where('token',$token);

		$this->db->where('status',2);

		$query = $this->db->get('users');

		if($query->num_rows() == 1){

			return TRUE;

		} else {

			return FALSE;

		}

	}

}