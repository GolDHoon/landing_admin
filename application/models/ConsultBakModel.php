<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConsultBakModel extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}


	public function insert_consult_bak($params){
		$this->db->insert('consult_member_bak', $params);
	}


}
