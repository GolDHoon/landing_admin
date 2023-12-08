<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

	var $data = array();

	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('user')){
			redirect("/login");
		}

		$this->data['header'] = $this->load->view('common/header','',TRUE);
		$this->data['content'] = $this->load->view('common/content','',TRUE);
		$this->data['footer'] = $this->load->view('common/footer','',TRUE);
	}


	public function lists()
	{
		$data = $this->data;
		$data['include_js'] = "/assets/js/admin/landing/register.js";
		$data['content'] = $this->load->view('/landing/register',$data,TRUE);
		$this->load->view('main',$data);
	}

	public function register()
	{
		$data = $this->data;
		$data['include_js'] = "/assets/js/admin/landing/register.js";
		$data['content'] = $this->load->view('/landing/register',$data,TRUE);
		$this->load->view('main',$data);
	}

}
