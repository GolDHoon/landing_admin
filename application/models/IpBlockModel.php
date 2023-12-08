<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IpBlockModel extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function ip_lists()
	{
		$this->db->select('*');
		$this->db->from('ip_block');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('landing_code',$_SESSION['target_landing_code']);
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
		$this->db->from('ip_block');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('landing_code',$_SESSION['target_landing_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function request_count()
	{
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('landing_code',$_SESSION['target_landing_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->count_all_results('ip_block');
	}

	public function insert_data($params){
		$this->db->insert('ip_block', $params);
	}

	public function delete_data($param){
		$this->db->where('idx', $param);
		$this->db->delete('ip_block');
	}

	public function get_ip_lists(){
		$this->db->select('ip');
		$this->db->from('ip_block');
		if(!empty($_SESSION['user'])){
			if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}else{
				if($_SESSION['target_landing_code'] != 'all'){
					$this->db->where('landing_code',$_SESSION['target_landing_code']);
				}
			}
		}

		$result = $this->db->get()->result_array();
		return array_column($result, 'ip');
	}

	public function check_ip($param){
		$this->db->select('ip');
		$this->db->from('ip_block');
		$this->db->where('ip',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('landing_code',$_SESSION['target_landing_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->count_all_results();

	}

}
