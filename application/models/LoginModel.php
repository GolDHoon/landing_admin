<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginModel extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function check_member($param)
	{
		$this->db->select('*');
		$this->db->from('admin_member');
		$this->db->where('user_id',$param);
		return $this->db->get()->result_array();
	}

	public function check_id($id)
	{
		$this->db->select('*');
		$this->db->from('admin_member');
		$this->db->where('user_id',$id);
		return $this->db->get()->result_array();
	}
}
