<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	var $data = array();

	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('user')){
			redirect("/login");
		}

		$this->load->library('pagination');

		$this->load->model(
			array('IpBlockModel','TemplateModel')
		);

	}


	public function ip_setting()
	{

		$data = $this->data;
//		$data['include_css'] = "/assets/css/admin/main.css";
		$data['include_js'] = "/assets/js/admin/setting/setting.js";

		$data['ip_value'] = $this->input->get('ip_value') ?? '';
		$data['page'] = $this->input->get('page') ?? 1;
		$arr_result = $this->create_pagination($data);

		$data['header'] = $this->load->view('common/header','',TRUE);
		$data['content'] = $this->load->view('setting/ip_setting',$arr_result,TRUE);
		$data['footer'] = $this->load->view('common/footer','',TRUE);
		$this->load->view('main',$data);
	}

	public function add_block_ip()
	{

		$ip_value = trim($this->input->get('ip_value')) ?? '';

		$params = array(
			'ip' => $ip_value,
			'created_user' => $this->session->userdata('user'),
			'created_at' => date("Y-m-d H:i:s"),
			'landing_code' => $_SESSION['target_landing_code'],
		);

		if($ip_value){
			try {

				// ip 등록 중복체크
				$cnt = $this->IpBlockModel->check_ip($ip_value);
				if($cnt > 0){
					echo "<script>alert('이미 등록된 IP 입니다.');history.go(-1);</script>";
					exit;
				}else{
					$this->IpBlockModel->insert_data($params);
					redirect('/setting/ip_setting');
				}

			}catch (Exception $e){
				echo $e->getMessage();
			}
		}else{
			echo "<script>alert('IP를 입력해주세요.');history.go(-1);</script>";
			exit;
		}




	}

	private function create_pagination($data){

		$page = $data['page'];
		$params = array(
			'per_page' => 20,
		);
		$base_url = base_url('setting/ip_setting');

		$arr_result = array();
		$arr_result['arr_lists'] = $this->IpBlockModel->ip_lists();
		$config = $this->drivenlib->set_pagination($params,$base_url);

		$config['total_rows'] = $this->IpBlockModel->request_count();

		$this->pagination->initialize($config);

		$arr_result['results'] = $this->IpBlockModel->get_data($config['per_page'],($page - 1) * $config['per_page'],$params);

		$arr_result['links'] = $this->pagination->create_links();

		$arr_result['params'] = $params;

		return $arr_result;
	}

	public function delete_ip(){

		$arr_result = [];

		$idx = $this->input->post('idx') ?? '';

		try {

			$this->IpBlockModel->delete_data($idx);
			$arr_result['code'] = 1;

		}catch (Exception $e){

			$arr_result['code'] = 0;
			echo $e->getMessage();

		}

		echo json_encode($arr_result);

	}

	public function delete_template(){

		$arr_result = [];

		$idx = $this->input->post('idx') ?? '';

		try {

			$this->TemplateModel->delete_data($idx);
			$arr_result['code'] = 1;

		}catch (Exception $e){

			$arr_result['code'] = 0;
			echo $e->getMessage();

		}

		echo json_encode($arr_result);

	}

	public function sms_template()
	{

		$data = $this->data;
		$data['include_css'] = "/assets/css/admin/setting/setting.css";
		$data['include_js'] = "/assets/js/admin/setting/sms_template.js";

		$data['page'] = $this->input->get('page') ?? 1;
		$arr_result = $this->create_pagination_sms($data);

		$data['header'] = $this->load->view('common/header','',TRUE);
		$data['content'] = $this->load->view('setting/sms_template',$arr_result,TRUE);
		$data['footer'] = $this->load->view('common/footer','',TRUE);
		$this->load->view('main',$data);

	}

	private function create_pagination_sms($data){

		$page = $data['page'];
		$params = array(
			'per_page' => 20,
		);
		$base_url = base_url('setting/sms_template');

		$arr_result = array();
		$arr_result['arr_lists'] = $this->TemplateModel->template_lists();
		$config = $this->drivenlib->set_pagination($params,$base_url);

		$config['total_rows'] = $this->TemplateModel->request_count();

		$this->pagination->initialize($config);

		$arr_result['results'] = $this->TemplateModel->get_data($config['per_page'],($page - 1) * $config['per_page'],$params);

		$arr_result['links'] = $this->pagination->create_links();

		$arr_result['params'] = $params;

		return $arr_result;
	}


	public function add_template(){

		$title = $this->input->post('title');
		$message = $this->input->post('message');


		$arr_result = array();

		$params = array(
			'title' => $title,
			'message' => $message,
			'created_at' => date("Y-m-d H:i:s"),
			'created_user' => $this->session->userdata('user'),
			'admin_code' => $_SESSION['admin_code'],
		);


		try {

			$cnt = $this->TemplateModel->check_title($title);

			if($cnt != 0){
				$arr_result['code'] = false;
				$arr_result['message'] = '이미 생성된 제목이 있습니다.';
			}else{
				$arr_result['code'] = true;
				$arr_result['message'] = '추가되었습니다.';
				$this->TemplateModel->insert_data($params);
			}


		}catch (Exception $e){
			$arr_result['code'] = true;
			$arr_result['message'] = 'ERROR.';
			log_message('error','SMS MESSAGE INSERT ERROR');
		}

		echo json_encode($arr_result);

	}

	public function update_template(){

		$title = $this->input->post('title');
		$message = $this->input->post('message');
		$idx = $this->input->post('idx');


		$arr_result = array();

		$params = array(
			'idx' => $idx,
			'title' => $title,
			'message' => $message,
			'updated_at' => date("Y-m-d H:i:s"),
			'updated_user' => $this->session->userdata('user'),
		);

		try {

			$cnt = $this->TemplateModel->check_title($title);

			if($cnt > 1){
				$arr_result['code'] = false;
				$arr_result['message'] = '이미 생성된 제목이 있습니다.';
			}else{
				$arr_result['code'] = true;
				$arr_result['message'] = '수정되었습니다.';
				$this->TemplateModel->update_template($params);
			}


		}catch (Exception $e){
			$arr_result['code'] = false;
			$arr_result['message'] = 'ERROR.';
			log_message('error','SMS MESSAGE INSERT ERROR');
		}

		echo json_encode($arr_result);

	}

	public function get_message(){

		$idx = $this->input->post('idx');
		$arr_result = array();

		try {

			$message = $this->TemplateModel->get_message($idx);
			$arr_result['code'] = true;
			$arr_result['message'] = $message[0]['message'];

		}catch (Exception $e){
			$arr_result['code'] = false;
			$arr_result['message'] = 'ERROR.';
			log_message('error','GET MESSAGE ERROR.');
		}

		echo json_encode($arr_result);

	}


}
