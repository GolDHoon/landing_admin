<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CreateAdminMember extends CI_Controller {

	var $data = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->model(
			array('ConsultModel','AdminMember','AdminLanding')
		);

		$this->data['header'] = $this->load->view('common/header','',TRUE);
		$this->data['content'] = $this->load->view('common/content','',TRUE);
		$this->data['footer'] = $this->load->view('common/footer','',TRUE);
	}

	// 로그인 페이지
	public function index()
	{
		$this->load->view('create_admin_member');
	}


	public function insert_admin_member(){

		$user_id = $this->input->post('user_id') ?? '';
		$user_pw = $this->input->post('user_pw') ?? '';

		$temp_landing_code = $this->create_code(8);
		$temp_admin_code = $this->create_code(8);
		$landing_code_cnt = $this->ConsultModel->check_landing_code($temp_landing_code);
		$admin_code_cnt = $this->AdminMember->check_admin_code($temp_admin_code);
		$admin_id_cnt = $this->AdminMember->check_admin_id($user_id);

		if($admin_id_cnt > 0){
			echo "<script>alert('이미 존재하는 ID 입니다.');history.back();</script>";
			exit;
		}

		if($landing_code_cnt > 0){
			echo "<script>alert('이미 존재하는 landing_code 입니다.');history.back();</script>";
			exit;
		}else{
			$landing_code = $temp_landing_code;
		}

		if($admin_code_cnt > 0){
			echo "<script>alert('이미 존재하는 admin_code 입니다.');history.back();</script>";
			exit;
		}else{
			$admin_code = $temp_admin_code;
		}

		$sms_id = $this->input->post('sms_id') ?? '';
		$sms_sender = $this->input->post('sms_sender') ?? '';
		$sms_api_key = $this->input->post('sms_api_key') ?? '';
		$alim_id = $this->input->post('alim_id') ?? '';
		$alim_sender_key = $this->input->post('alim_sender_key') ?? '';
		$landing_type = $this->input->post('landing_type') ?? 0;
		$landing_type_value = $this->input->post('landing_type_value') ?? '';
		$name = $this->input->post('name') ?? '';

		$params = array(
			'user_id' => $user_id,
			'user_pw' => $this->drivenlib->create_password($user_pw),
			'admin_code' => $admin_code,
			'sms_id' => $sms_id,
			'sms_api_key' => $sms_api_key,
			'alim_id' => $alim_id,
			'alim_sender_key' => $alim_sender_key,
			'created_at' => date("Y-m-d H:i:s"),
		);

		if(!empty($sms_sender)){
			$params['sms_sender'] = $this->drivenlib->encrypt($sms_sender);
		}

		$landing_params = array(
			'admin_code' => $admin_code,
			'landing_code' => $landing_code,
			'landing_type' => $landing_type,
			'landing_type_value' => $landing_type_value,
			'name' => $name,
			'created_at' => date("Y-m-d H:i:s"),
		);

		try {

			$this->AdminMember->insert_admin_member($params);
			$this->AdminLanding->insert_admin_landing($landing_params);

		}catch (Exception $e){
			debug_var($e->getMessage());
		}
	}

	public function create_landing_admin_code($mode){

		$temp_code = $this->create_code(8);

		switch ($mode){
			case "landing":
				$check_cnt = $this->ConsultModel->check_landing_code($temp_code);
				break;
			case "admin":
				$check_cnt = $this->AdminMember->check_admin_code($temp_code);
				break;
			default :
				echo "<script>alert('정확한 세그먼트를 입력해주세요.')</script>";
				exit;
		}

		return $temp_code;

	}

	private function create_code($length){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = str_shuffle($characters);
		return substr($randomString, 0, $length);
	}


}
