<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use GuzzleHttp\Client;

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller
{

	var $data = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->model(
			array('ConsultModel','SmsQueue','TemplateModel','AdminMember')
		);

	}

	public function excel_download()
	{
		ini_set('memory_limit','-1');

		$condition = $this->input->post('condition') ?? '';
		$search_value = $this->input->post('search_value') ?? '';
		$created_at = $this->input->post('created_at') ?? '';

		if ($created_at) {
			list($start_date, $end_date) = explode(' - ', $created_at);
		}

		$params = array(
			'condition' => $condition,
			'start_date' => $start_date ?? '',
			'end_date' => $end_date ?? '',
			'search_value' => $search_value,
		);
		$arr_lists = $this->ConsultModel->consult_lists($params);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet -> getActiveSheet();
		foreach (range(0, 9) as $chr) {
			$value = '';
			switch ($chr) {
				case 0 : $value = '이름'; break;
				case 1 : $value = '전화번호'; break;
				case 2 : $value = '도메인'; break;
				case 3 : $value = '상담상태'; break;
				case 4 : $value = 'UTM_SOURCE'; break;
				case 5 : $value = 'UTM_MEDIUM'; break;
				case 6 : $value = 'UTM_CAMPAIGN'; break;
				case 7 : $value = 'UTM_TERM'; break;
				case 8 : $value = 'UTM_CONTENT'; break;
				case 9 : $value = '상담신청일'; break;
			}
			$chr = chr($chr + 65);
			$sheet -> setCellValue("{$chr}1", $value);
		}

		$idx = 2;
		foreach ( $arr_lists as $val ) {
			$status = '';
			switch ($val['status']) {
				case 0 : $status = '상담신청'; break;
				case 1 : $status = '상담중'; break;
				case 2 : $status = '상담완료'; break;
				case 3 : $status = '상담보류'; break;
			}
			$sheet -> setCellValue("A{$idx}", $this->drivenlib->decrypt($val['name']));
			$sheet -> setCellValue("B{$idx}", $this->drivenlib->decrypt($val['phone']));
			$sheet -> setCellValue("C{$idx}", $val['name']);
			$sheet -> setCellValue("D{$idx}", $status);
			$sheet -> setCellValue("E{$idx}", $val['utm_source']);
			$sheet -> setCellValue("F{$idx}", $val['utm_medium']);
			$sheet -> setCellValue("G{$idx}", $val['utm_campaign']);
			$sheet -> setCellValue("H{$idx}", $val['utm_term']);
			$sheet -> setCellValue("I{$idx}", $val['utm_content']);
			$sheet -> setCellValue("J{$idx}", $val['created_at']);
			$idx++;
		}
		$prefix = 'member_';
		$writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$filename = $prefix.date('Ymd').'.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename);
		header('Cache-Control: max-age=0');
		$writer -> save('php://output');

	}

	public function send_sms(){

		$seq_lists = $this->input->post('seq_lists') ?? '';
		$sms_message = $this->input->post('sms_message') ?? '';
		$title = $this->input->post('title') ?? '';

		$arr_idx = explode(',',$seq_lists);
		$arr_lists = $this->ConsultModel->consult_sms_lists($arr_idx);
		$template_idx = $this->TemplateModel->get_template_idx($title);

		foreach ($arr_lists as $v){
			$v['phone'] = $this->drivenlib->decrypt($v['phone']);
			$v['name'] = $this->drivenlib->decrypt($v['name']);

			$msg = "";
			$msg = str_replace('@{name}',$v['name'],$sms_message);
			$data = array(
				'msg' => $msg,
				'receiver' => $v['phone'],
				'destination' => "{$v['phone']}|{$v['name']}",
				'title' => $title,
			);

			log_message("error","before send sms => admin : {$_SESSION['user']} , ".json_encode($data));

			$result = $this->aligo_sms_api($data);

			log_message("error","after send sms => admin : {$_SESSION['user']} , phone : {$v['phone']} ".json_encode($result));

			$param_queue = array(
				'target_member_idx' => $v['idx'],
				'admin_code' => $_SESSION['admin_code'],
				'template_idx' => $template_idx[0]['idx'],
				'status' => $result['result_code'],
				'result_msg' => $result['message'],
				'fail_cnt' => 0,
				'message' => $msg,
				'receiver' => $v['phone'],
				'destination' => "{$v['phone']}|{$v['name']}",
				'title' => $title,
				'created_at' => date("Y-m-d H:i:s"),
			);

			try {

				$this->SmsQueue->insert_queue($param_queue);
				$param_queue['created_at'] = date("Y-m-d H:i:s");

			}catch (Exception $e) {
				log_message('error', $e->getMessage());
			}

		}

	}

	public function send_alim(){
		$token = $this->drivenlib->get_alim_token();
//		debug_var($token);
		if(!empty($token)){
			$arr_result = (array)$this->get_alim_template_list($token);

			if($arr_result['code'] == 0){
				foreach ($arr_result['list'] as $v){
					debug_var('msg - '.$v->templtContent);
					debug_var('title - '.$v->templtName);
					debug_var('t_code - '.$v->templtCode);
					debug_var('created_at - '.$v->cdate);
				}
			}else{
				// code error
			}

		}else{
			// token error
		}

	}

	public function get_alim_template_list($token)
	{
		/*
		-----------------------------------------------------------------------------------
		등록된 템플릿 리스트
		-----------------------------------------------------------------------------------
		등록된 템플릿 목록을 조회합니다. 템플릿 코드가 D 나 P 로 시작하는 경우 공유 템플릿이므로 삭제 불가능 합니다.
		*/

		$_apiURL = 'https://kakaoapi.aligo.in/akv10/template/list/';
		$_hostInfo = parse_url($_apiURL);
		$_port = (strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
		$_variables = array(
			'apikey' => $_SESSION['user_sms_api_key'],
			'userid' => $_SESSION['user_sms_id'],
			'token' => $token,
			'senderkey' => $_SESSION['user_alim_sender_key'],
//			'tpl_code'  => '조회할 템플릿 코드'
		);

		$oCurl = curl_init();
		curl_setopt($oCurl, CURLOPT_PORT, $_port);
		curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
		curl_setopt($oCurl, CURLOPT_POST, 1);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

		$ret = curl_exec($oCurl);
		$error_msg = curl_error($oCurl);
		curl_close($oCurl);

		// JSON 문자열 배열 변환
		$retArr = json_decode($ret);

		/*
		code : 0 성공, 나머지 숫자는 에러
		message : 결과 메시지
		*/

		return $retArr;

	}

	public function aligo_sms_api($data){

		$arr_remain = $this->aligo_remain();
		$check_sms_cnt = $arr_remain['SMS_CNT'] ?? 0;

		if($check_sms_cnt > 0){

			$sms_url = "https://apis.aligo.in/send/";
			$user_id = $_SESSION['user_sms_id'];
			$key = $_SESSION['user_sms_api_key'];

			$msg = $data['msg']; // 메세지 내용 : euc-kr로 치환이 가능한 문자열만 사용하실 수 있습니다. (이모지 사용불가능)
			$receiver = $data['receiver']; // 수신번호
			$destination = $data['destination']; // 수신인 %고객명% 치환
			$sender = $_SESSION['user_sms_sender']; // 발신번호
			$rdate = ''; // 예약일자 - 20161004 : 2016-10-04일기준
			$rtime = ''; // 예약시간 - 1930 : 오후 7시30분
			$testmode_yn = 'N'; // Y 인경우 실제문자 전송X , 자동취소(환불) 처리
			$subject = $data['title']; //  LMS, MMS 제목 (미입력시 본문중 44Byte 또는 엔터 구분자 첫라인)
			// $image = '/tmp/pic_57f358af08cf7_sms_.jpg'; // MMS 이미지 파일 위치 (저장된 경로)

			$check_byte = $this->drivenlib->check_byte($msg);
			//  SMS, LMS, MMS등 메세지 타입을 지정
			if($check_byte > 90){
				$msg_type = 'LMS';
			}else{
				$msg_type = 'SMS';
			}

			$payload = [
				'msg' => $msg,
				'receiver' => $receiver,
				'destination' => $destination,
				'sender' => $sender,
				'rdate' => $rdate,
				'rtime' => $rtime,
				'testmode_yn' => $testmode_yn,
				'subject' => $subject,
				'msg_type' => $msg_type,
			];

			$client = new Client();
			$response = $client->post($sms_url, [
				'multipart' => [
					[
						'name' => 'user_id',
						'contents' => $user_id,
					],
					[
						'name' => 'key',
						'contents' => $key,
					],
					[
						'name' => 'msg',
						'contents' => $payload['msg'],
					],
					[
						'name' => 'receiver',
						'contents' => $payload['receiver'],
					],
					[
						'name' => 'destination',
						'contents' => $payload['destination'],
					],
					[
						'name' => 'sender',
						'contents' => $payload['sender'],
					],
					[
						'name' => 'rdate',
						'contents' => $payload['rdate'],
					],
					[
						'name' => 'rtime',
						'contents' => $payload['rtime'],
					],
					[
						'name' => 'testmode_yn',
						'contents' => $payload['testmode_yn'],
					],
					[
						'name' => 'subject',
						'contents' => $payload['subject'],
					],
					[
						'name' => 'msg_type',
						'contents' => $payload['msg_type'],
					],
//				[
//					'name' => 'image',
//					'contents' => $payload['image'],
//				],
				],
			]);

			$retArr = json_decode($response->getBody(),true); // 결과배열
			return $retArr;

		}else{
			log_message("error", "ALIGO SMS 포인트를 충전해주세요.");
		}

	}

	private function aligo_remain(){

		$sms_url = "https://apis.aligo.in/remain/";
		$sms['user_id'] = $_SESSION['user_sms_id'];
		$sms['key'] = $_SESSION['user_sms_api_key'];

		// 인증정보
		$host_info = explode("/", $sms_url);
		$port = $host_info[0] == 'https:' ? 443 : 80;

		$client = new Client();

		$options = [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'form_params' => $sms,
			'verify' => false,
			'port' => $port,
		];

		try {
			$response = $client->post($sms_url, $options);

			$ret = $response->getBody()->getContents();

			$retArr = json_decode($ret, true);
			return $retArr;

		} catch (\Exception $e) {
			echo 'Error: ' . $e->getMessage();
		}

	}

	public function exec_fail_sms(){

		$arr_list = $this->SmsQueue->get_fail_list();

		try {

			foreach ($arr_list as $v){
				if($v['fail_cnt'] <= 3){
					$data = array(
						'msg' => $v['message'],
						'receiver' => $v['receiver'],
						'destination' => $v['destination'],
						'title' => $v['title']
					);
					$result = $this->aligo_sms_api($data);

					if($result['result_code'] != 1){
						$v['fail_cnt'] = $v['fail_cnt'] + 1;
					}

					$update_queue = array(
						'idx' => $v['idx'],
						'fail_cnt' => $v['fail_cnt'],
						'updated_at' => date("Y-m-d H:i:s"),
						'result_code' => $result['result_code'],
					);

					$this->SmsQueue->update_fail_queue($update_queue);
				}
			}

		}catch (Exception $e){
			log_message('error',$e->getMessage());
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

		if($check_cnt == 0){
			debug_var("사용가능한 {$mode} 코드 : ".$temp_code);
		}else{
			debug_var("중복된 코드 : 새로고침하여 다른코드를 받아주세요.");
		}

		return $temp_code;

	}

	private function create_code($length){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = str_shuffle($characters);
		return substr($randomString, 0, $length);
	}

	public function create_password($password){
		$result = $this->drivenlib->create_password($password);
		debug_var($result);
		return $result;
	}




}
