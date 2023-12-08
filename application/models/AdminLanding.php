<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminLanding extends CI_Model {

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function insert_admin_landing($params){
		$this->db->insert('admin_landing',$params);
	}


}
