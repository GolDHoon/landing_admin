<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	var $data = array();

	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('user')){
			redirect("/login");
		}
		$this->load->helper('form');
		$this->load->library('pagination');

		$this->load->model(
			array('ConsultModel','TemplateModel','ConsultBakModel','AlimTemplateModel')
		);

	}

	public function index()
	{

		$data = $this->data;
		$data['include_css'] = "/assets/css/admin/main.css";
		$data['include_js'] = "/assets/js/admin/main.js";

		$currentDate = date('Y-m-d');
		$oneMonthAgo = date('Y-m-d', strtotime('-1 month'));

		$data['created_at'] = $this->input->get('created_at') ?? "$oneMonthAgo - $currentDate";
		$data['condition'] = $this->input->get('condition') ?? '';
		$data['search_value'] = $this->input->get('search_value') ?? '';
		$data['orderBy_condition'] = $this->input->get('orderBy_condition') ?? '';
		$data['orderBy_value'] = $this->input->get('orderBy_value') ?? '';
		$data['page'] = $this->input->get('page') ?? 1;

		$pre_search_value = $data['search_value'];

		if($data['condition'] === 'status'){
			switch ($data['search_value']){
				case '상담요청':
					$data['search_value'] = 0;
					break;
				case '상담중':
					$data['search_value'] = 1;
					break;
				case '상담완료':
					$data['search_value'] = 2;
					break;
				case '상담보류':
					$data['search_value'] = 3;
					break;
				default:
					break;
			}
		}

		$arr_result = $this->create_pagination($data);

		if(empty($arr_result['created_at'])){
			$arr_result['created_at'] = "$oneMonthAgo - $currentDate";
		}
		$arr_result['templates'] = $this->TemplateModel->get_all_data();
		$arr_result['alim_templates'] = $this->AlimTemplateModel->get_all_data();

		// 알림톡 템플릿
//		$token = $this->drivenlib->get_alim_token();

		foreach ($arr_result['results'] as $v){
			$v->phone = $this->drivenlib->decrypt($v->phone);
			$v->name = $this->drivenlib->decrypt($v->name);
		}

		foreach ($arr_result['results'] as $v){
			switch ($v->region){
				case 0:
					$v->region = '서울';
					break;
				case 1:
					$v->region = '경기';
					break;
				case 2:
					$v->region = '인천';
					break;
				case 3:
					$v->region = '경상북도';
					break;
				case 4:
					$v->region = '경상남도';
					break;
				case 5:
					$v->region = '전라북도';
					break;
				case 6:
					$v->region = '전라남도';
					break;
				default:
					break;
			}
		}

		$arr_result['params']['search_value'] = $pre_search_value;

		$data['header'] = $this->load->view('common/header','',TRUE);
		$data['content'] = $this->load->view('common/content',$arr_result,TRUE);
		$data['footer'] = $this->load->view('common/footer','',TRUE);

		$this->load->view('main',$data);

	}

	public function create_pagination($data){

		$created_at = $data['created_at'];
		$condition = $data['condition'];
		$orderBy_condition = $data['orderBy_condition'];
		$orderBy_value = $data['orderBy_value'];
		$search_value = $data['search_value'];
		$page = $data['page'];

		if(!empty($created_at)){
			list($start_date,$end_date) = explode(' - ',$created_at);
		}

		$params = array(
			'created_at' => $created_at,
			'condition' => $condition,
			'start_date' => $start_date ?? '',
			'end_date' => $end_date ?? '',
			'search_value' => $search_value,
			'per_page' => 10,
			'orderBy_condition' => str_replace('orderBy_','',$orderBy_condition),
			'orderBy_value' => $orderBy_value,
		);

		$base_url = base_url('main');

		$arr_result = array();
//		$arr_result['arr_lists'] = $this->ConsultModel->consult_lists($params);

		$config = $this->drivenlib->set_pagination($params,$base_url);
		$config['total_rows'] = $this->ConsultModel->request_count($params);

		$this->pagination->initialize($config);

		$arr_result['results'] = $this->ConsultModel->get_data($config['per_page'],($page - 1) * $config['per_page'],$params);

		$arr_result['links'] = $this->pagination->create_links();

		$params['orderBy_condition'] = $data['orderBy_condition'];
		$params['search_value'] = $search_value;
		$arr_result['params'] = $params;

		return $arr_result;
	}

	public function update_memo(){

		$memo = $this->input->post('memo') ?? '';
		$idx = $this->input->post('idx') ?? '';

		try {

			$this->ConsultModel->update_memo($idx,$memo);

		}catch (Exception $e){
			echo $e->getMessage();
		}

	}

	public function update_status(){

		$status = $this->input->post('status') ?? '';
		$idx = $this->input->post('idx') ?? '';

		try {

			$this->ConsultModel->update_status($idx,$status);

		}catch (Exception $e){
			echo $e->getMessage();
		}

	}

	public function delete_consult_member(){

		$idx = $this->input->post('idx') ?? '';

		try {

			$arr_result = $this->ConsultModel->get_one_data($idx);
			$arr_result[0]['deleted_at'] = date("Y-m-d H:i:s");
			$this->ConsultModel->delete_data($idx);
			$this->ConsultBakModel->insert_consult_bak($arr_result[0]);

		}catch (Exception $e){
			log_message('error',$e->getMessage());
		}

	}

	public function change_session_value(){
		$landing_code = $this->input->post('landing_code') ?? '';
		$_SESSION['target_landing_code'] = $landing_code;
	}


}
