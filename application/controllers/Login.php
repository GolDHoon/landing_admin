<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	var $data = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(
			array('string')
		);

		$this->load->model(
			array('LoginModel','IpBlockModel','AdminMember')
		);

		$this->data['header'] = $this->load->view('common/header','',TRUE);
		$this->data['content'] = $this->load->view('common/content','',TRUE);
		$this->data['footer'] = $this->load->view('common/footer','',TRUE);
	}

	// 로그인 페이지
	public function index()
	{
		$this->load->view('login');
	}

	// 로그인 인증 로직.
	public function check_login(){

		$result = array();

		$user_id = $this->input->post('userId');
		$user_pw = $this->input->post('userPw');

		try {

			$arr_result = $this->LoginModel->check_member($user_id);

			if(count($arr_result) > 0){

				$arr_result = $this->LoginModel->check_id($user_id);
				$verify_result = $this->drivenlib->password_verify($user_pw,$arr_result[0]['user_pw']);

				if($verify_result == 1){
					$result['code'] = 1;
					$result['msg'] = 'SUCCESS';

					$arr_user_data = array(
						"user" => $user_id
					);
					$this->session->set_userdata($arr_user_data);

					$_SESSION['user_sms_sender'] = $this->drivenlib->decrypt($arr_result[0]['sms_sender']);
					$_SESSION['user_sms_api_key'] = $arr_result[0]['sms_api_key'];
					$_SESSION['user_sms_id'] = $arr_result[0]['sms_id'];
					$_SESSION['user_alim_id'] = $arr_result[0]['alim_id'] ?? '';
					$_SESSION['user_alim_sender_key'] = $arr_result[0]['alim_sender_key'] ?? '';

					if(!empty($_SESSION['user'])){
						$admin_code = $this->AdminMember->get_admin_code($_SESSION['user']);
						$_SESSION['admin_code'] = $admin_code[0]['admin_code'];


						if(!in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){
							$_SESSION['arr_landing_list'] = $this->AdminMember->get_landing_list($_SESSION['user']) ?? array();
							$_SESSION['target_landing_code'] = $_SESSION['arr_landing_list'][0]['landing_code'] ?? '';
							$_SESSION['target_admin_code'] = $_SESSION['arr_landing_list'][0]['admin_code'] ?? '';

						}else{
							$_SESSION['arr_landing_list'] = $this->AdminMember->get_landing_list('') ?? array();
							$_SESSION['target_landing_code'] = 'all';
							$_SESSION['target_admin_code'] = 'all';
						}


						$this->AdminMember->get_admin_code($_SESSION['user']);
					}
				}else{
					$result['code'] = 0;
					$result['msg'] = 'CHECK YOUR PASSWORD';
				}

			}else{
				$result['code'] = 2;
				$result['msg'] = 'THERE ISN\'T YOUR ID';
			}

		} catch (Exception $e){

			log_message("error","LOGIN ERROR");

		}

		echo json_encode($result);

	}

	public function sign_out(){
		$this->session->unset_userdata("user");
		$this->session->sess_destroy();
		redirect("/login");
	}

}
