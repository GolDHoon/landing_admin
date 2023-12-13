<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConsultModel extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function consult_lists($params)
	{
		$this->db->select('a.*,b.domain_name');
		$this->db->from('consult_member a');
		$this->db->join('admin_landing b', 'a.landing_code = b.landing_code');

		if($params['condition'] && $params['search_value']){
			$this->db->where("a.{$params['condition']}",$params['search_value']);
		}
		if($params['start_date']){
			$this->db->where("a.created_at BETWEEN '$params[start_date] 00:00:00' AND '$params[end_date] 23:59:59'");
		}
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('b.landing_code',$_SESSION['target_landing_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('b.landing_code',$_SESSION['target_landing_code']);
			}
		}
		if(!empty($params['orderBy_condition'])){
			$this->db->order_by('a.'.$params['orderBy_condition'], $params['orderBy_value']);
		}


		return $this->db->get()->result_array();
	}

	public function get_data($limit, $offset, $params)
	{
		$this->db->select('a.*,b.domain_name');
		$this->db->from('consult_member a');
		$this->db->join('admin_landing b', 'a.landing_code = b.landing_code');
		if($params['condition'] && $params['search_value']){
			switch ($params['condition']) {
				case "name":
				case "phone":
					$params['search_value'] = $this->drivenlib->encrypt($params['search_value']);
					break;
				default:
					break;
			}
			$this->db->where("a.{$params['condition']}", $params['search_value']);
		}
		if($params['start_date']){
			$this->db->where("a.created_at BETWEEN '$params[start_date] 00:00:00' AND '$params[end_date] 23:59:59'");
		}
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('b.landing_code',$_SESSION['target_landing_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('b.landing_code',$_SESSION['target_landing_code']);
			}
		}
		if(!empty($params['orderBy_condition'])){
			$this->db->order_by('a.'.$params['orderBy_condition'], $params['orderBy_value']);
		}
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function request_count($params)
	{
		$this->db->select('a.idx');
		$this->db->from('consult_member a');
		$this->db->join('admin_landing b', 'a.landing_code = b.landing_code');
		if($params['condition'] && $params['search_value']){
			switch ($params['condition']) {
				case "name":
				case "phone":
				$params['search_value'] = $this->drivenlib->encrypt($params['search_value']);
					break;
				default:
					break;
			}
			$this->db->where("a.{$params['condition']}", $params['search_value']);
		}
		if($params['start_date']){
			$this->db->where("a.created_at BETWEEN '$params[start_date] 00:00:00' AND '$params[end_date] 23:59:59'");
		}
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('b.landing_code',$_SESSION['target_landing_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('b.landing_code',$_SESSION['target_landing_code']);
			}
		}
		return $this->db->count_all_results();
	}

	public function update_memo($idx,$memo){
		$this->db->set('memo',$memo);
		$this->db->where('idx', $idx);
		$this->db->update('consult_member');
	}

	public function update_status($idx,$status){
		$this->db->set('status',$status);
		$this->db->where('idx', $idx);
		$this->db->update('consult_member');
	}

	public function consult_sms_lists($params){
		$this->db->select('*');
		$this->db->from('consult_member');
		$this->db->where_in('idx', $params);
		return $this->db->get()->result_array();
	}

	public function insert_request_member($params){
		$this->db->insert('consult_member', $params);
	}

	public function check_landing_code($param){
		$this->db->select('idx');
		$this->db->from('consult_member');
		$this->db->where('landing_code', $param);
		return $this->db->count_all_results();
	}

	public function get_one_data($param){
		$this->db->select('*');
		$this->db->from('consult_member');
		$this->db->where('idx', $param);
		return $this->db->get()->result_array();
	}

	public function delete_data($param){
		$this->db->where('idx', $param);
		$this->db->delete('consult_member');
	}


}
