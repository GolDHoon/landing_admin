<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TemplateModel extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function template_lists()
	{
		$this->db->select('*');
		$this->db->from('sms_template');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function get_data($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from('sms_template');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function get_all_data(){
		$this->db->select('*');
		$this->db->from('sms_template');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function get_template_idx($param){
		$this->db->select('idx');
		$this->db->from('sms_template');
		$this->db->where('title',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function get_message($param){
		$this->db->select('message');
		$this->db->from('sms_template');
		$this->db->where('idx',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function request_count()
	{
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}
		return $this->db->count_all_results('sms_template');
	}

	public function insert_data($params){
		$this->db->insert('sms_template', $params);
	}

	public function delete_data($param){
		$this->db->where('idx', $param);
		$this->db->delete('sms_template');
	}

	public function check_title($param){
		$this->db->select('title');
		$this->db->from('sms_template');
		$this->db->where('title',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->count_all_results();
	}

	public function update_template($params){
		$this->db->where('idx', $params['idx']);
		$this->db->update('sms_template',$params);
	}

}
