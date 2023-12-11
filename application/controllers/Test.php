<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	var $data = array();
	var $user_agent = "";
	var $ip = "";


	public function __construct()
	{
		parent::__construct();

		$this->load->model(
			array('AccessLog','ConsultModel')
		);

		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

	}

	// 로그인 페이지
	public function index()
	{

		$params = array(
			'user_agent' => $this->user_agent,
			'ip' => $this->ip,
			// TODO landing_code 넣어야함
			'landing_code' => '',
			'created_at' => date('Y-m-d H:i:s'),
		);
		$this->AccessLog->insert_access_log($params);
		$data = $this->data;
		$data['header'] = $this->load->view('common/header','',TRUE);
		$data['footer'] = $this->load->view('common/footer','',TRUE);
		$this->load->view('test',$data);
	}

	public function test_member(){

		$name = $this->input->get('name',true);
		$phone = $this->input->get('phone',true);
		$mode = $this->input->get('mode');
		$utm_source = $this->input->get('utm_source') ?? '';
		$utm_medium = $this->input->get('utm_medium') ?? '';
		$utm_campaign = $this->input->get('utm_campaign') ?? '';
		$utm_term = $this->input->get('utm_term') ?? '';


		if($mode === '1'){
			$params = array(
				'user_agent' => $this->user_agent,
				'ip' => $this->ip,
				// TODO landing_code 필수
				'landing_code' => '1234',
				// TODO 발급된 admin_code 필수
				'admin_code' => '1234',
				'created_at' => date('Y-m-d H:i:s'),
				'name' => $name,
				'phone' => $phone,
				'utm_source' => $utm_source,
				'utm_medium' => $utm_medium,
				'utm_campaign' => $utm_campaign,
				'utm_term' => $utm_term,
			);
			$this->ConsultModel->insert_request_member($params);
		}

	}


	function encryptString($input, $key) {
		$method = 'aes-256-cbc';
		$encrypted = openssl_encrypt($input, $method, $key, 0, DRIVEN_IV_KEY);
		return base64_encode(DRIVEN_IV_KEY . $encrypted);
	}

	function t($plaintext){
		$encryptedText = $this->encryptString($plaintext, DRIVEN_EN_DE_KEY);
		debug_var($encryptedText);
	}


	function decryptString($input, $key) {
		$method = 'aes-256-cbc';

		$input = base64_decode($input);
		$iv = substr($input, 0, 16);
		$encrypted = substr($input, 16);

		return openssl_decrypt($encrypted, $method, $key, 0, $iv);
	}

	function tt(){
		$encryptedText = 'MDEyMzQ1Njc4OTEyMzQ1NnM3dmd0NEgvNTM3SHBsVXdHdjBhL0E9PQ==';
		$decryptedText = $this-> decryptString($encryptedText, DRIVEN_EN_DE_KEY);
		debug_var('복호화된 문자열: ' . $decryptedText);
		echo "TEST";
	}

}
