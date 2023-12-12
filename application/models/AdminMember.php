<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMember extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function get_admin_code($param){
		$this->db->select('admin_code');
		$this->db->from('admin_member');
		$this->db->where('user_id', $param);
		return $this->db->get()->result_array();
	}

	public function check_admin_code($param){
		$this->db->select('idx');
		$this->db->from('admin_member');
		$this->db->where('admin_code', $param);
		return $this->db->count_all_results();
	}

	public function check_admin_id($param){
		$this->db->select('idx');
		$this->db->from('admin_member');
		$this->db->where('user_id', $param);
		return $this->db->count_all_results();
	}

	public function insert_admin_member($params){
		$this->db->insert('admin_member',$params);
	}

	public function get_landing_list($param){
		$this->db->select('b.landing_code,b.landing_type_value');
		$this->db->from('admin_member a');
		$this->db->join('admin_landing b', 'a.admin_code = b.admin_code');
		if(!empty($param)){
			$this->db->where('a.user_id',$param);
		}
		return $this->db->get()->result_array();
	}

}
