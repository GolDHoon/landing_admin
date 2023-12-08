<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessLog extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}


	public function insert_access_log($params){
		$this->db->insert('access_log', $params);
	}


}
