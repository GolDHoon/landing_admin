<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlimTemplateModel extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function template_lists()
	{
		$this->db->select('*');
		$this->db->from('alim_template');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_landing_code'] != 'all'){
				$this->db->where('admin_code',$_SESSION['target_admin_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function get_data($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from('alim_template');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_admin_code'] != 'all'){
				$this->db->where('admin_code',$_SESSION['target_admin_code']);
			}
		}
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function get_all_data(){
		$this->db->select('*');
		$this->db->from('alim_template');
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_admin_code'] != 'all'){
				$this->db->where('admin_code',$_SESSION['target_admin_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function get_template_idx($param){
		$this->db->select('idx');
		$this->db->from('alim_template');
		$this->db->where('idx',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_admin_code'] != 'all'){
				$this->db->where('admin_code',$_SESSION['target_admin_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function get_message($param){
		$this->db->select('message');
		$this->db->from('alim_template');
		$this->db->where('idx',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_admin_code'] != 'all'){
				$this->db->where('admin_code',$_SESSION['target_admin_code']);
			}
		}
		return $this->db->get()->result_array();
	}

	public function request_count()
	{
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}
		return $this->db->count_all_results('alim_template');
	}

	public function insert_data($params){
		$this->db->insert('alim_template', $params);
	}

	public function delete_data($param){
		$this->db->where('idx', $param);
		$this->db->delete('alim_template');
	}

	public function check_title($param){
		$this->db->select('title');
		$this->db->from('alim_template');
		$this->db->where('title',$param);
		if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
			$this->db->where('admin_code',$_SESSION['admin_code']);
		}else{
			if($_SESSION['target_admin_code'] != 'all'){
				$this->db->where('admin_code',$_SESSION['target_admin_code']);
			}
		}
		return $this->db->count_all_results();
	}

	public function check_template_code($param){
		$this->db->where('template_code',$param);
		return $this->db->count_all_results('alim_template');
	}

	public function delete_template($param){
		$this->db->where('idx', $param);
		$this->db->delete('alim_template');
	}

	public function target_template_list($param){
		$this->db->select("*");
		$this->db->from('alim_template');
		$this->db->where('idx',$param);
		return $this->db->get()->result_array();
	}

	public function delete_all_template($param){
		$this->db->where('admin_code', $param);
		$this->db->delete('alim_template');
	}



}
