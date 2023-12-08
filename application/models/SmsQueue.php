<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SmsQueue extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function get_fail_cnt($target_member_idx,$admin_code){
		$this->db->select('fail_cnt');
		$this->db->from('sms_queue');
		$this->db->where('target_member_idx', $target_member_idx);
		$this->db->where('admin_code', $admin_code);
		return $this->db->get()->result_array();
	}

	public function update_queue($params){
		$this->db->set('fail_cnt');
		$this->db->where('target_member_idx', $params['target_member_idx']);
		$this->db->where('admin_code', $params['admin_code']);
		return $this->db->update('sms_queue');
	}

	public function insert_queue($params){
		$this->db->insert('sms_queue', $params);
	}

	public function get_fail_list(){
		$this->db->select('*');
		$this->db->from('sms_queue');
		$this->db->where('status != 1');
		return $this->db->get()->result_array();
	}

	public function update_fail_queue($params){
		$this->db->set('fail_cnt',$params['fail_cnt']);
		$this->db->set('updated_at',$params['updated_at']);
		$this->db->set('status',$params['result_code']);
		$this->db->where('idx', $params['idx']);
		$this->db->update('sms_queue');
	}

}
