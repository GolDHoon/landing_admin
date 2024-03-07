<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tiktok extends CI_Controller
{

	var $data = array();
	var $user_agent = "";
	var $ip = "";


	public function __construct()
	{
		parent::__construct();

		$this->load->model(
			array('')
		);

	}


	public function getAccessToken(){

		$curl = curl_init();
		$access_token = '';
		$advertiser_ids = [];

		$arr_data = array(
			'app_id' => '7286018889811492866',
			'auth_code' => '8248741e20a7724efc2a4620a97511e61a1404d5',
			'secret' => 'b6b2139faf84a7bbb16a6bbf79f92a9262822eca'
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://business-api.tiktok.com/open_api/v1.3/oauth2/access_token/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($arr_data),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		$response = json_decode($response,true);
		debug_var($response);
		if($response['code'] == 0){
			return $response['data'];
		}else{
			return null;
		}

		// faae2e181fa67684674b29e58736144f80a779ae

	}

	// 오디언스
	public function getQualifiedLeads(){

		$curl = curl_init();

		$access_token = '7f4258d80513f51324eefe701c74188643e7c586';

		$args[0] = '7221049888014876674';

		$url = 'https://business-api.tiktok.com/open_api/v1.3/dmp/custom_audience/list';

		$url_param = "";
		foreach ($args as $k => $v){
			if($k == 0){
				$url_param .= "?advertiser_id=".$v;
			}else{
				$url_param .= "&advertiser_id=".$v;
			}
		}

		$url = $url . $url_param;

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				"Access-Token: ${access_token}"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

	}

	// 오디언스 디테일
	public function getQualifiedLeadsDetails(){

		$access_token = '7f4258d80513f51324eefe701c74188643e7c586';

		$curl = curl_init();

		$custom_audience_ids = array('172855897','172855894');

		$url = "https://business-api.tiktok.com/open_api/v1.3/dmp/custom_audience/get";
		$url_params = "?advertiser_id=7221049888014876674&custom_audience_ids=".json_encode($custom_audience_ids);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url . $url_params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				"Access-Token: ${access_token}"
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response,true);
		curl_close($curl);
//		debug_var($response);
		debug_var($response['data']['list'][0]['audience_details']);
	}


	public function createLeads(){

		$access_token = '7f4258d80513f51324eefe701c74188643e7c586';

		$advertiser_id = "7281569074709315586";
		$page_id = "7302323281934156034";
		$task_id = "7324115845269963054";

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://business-api.tiktok.com/open_api/v1.3/page/lead/task/?advertiser_id={$advertiser_id}&page_id={$page_id}&task_id={$task_id}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'x-lead-region: us',
				"Access-Token: ${access_token}"
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response,true);
		curl_close($curl);

		// task_id 추출
		debug_var($response);

	}

	public function downloadLeads(){

		$curl = curl_init();

		$access_token = '7f4258d80513f51324eefe701c74188643e7c586';

		$advertiser_id = "7281569074709315586";
		$task_id = "7330050138428719362";

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://business-api.tiktok.com/open_api/v1.3/page/lead/task/download/?advertiser_id={$advertiser_id}&task_id={$task_id}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				"Access-Token: ${access_token}",
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
//		var_dump($response);
//		exit;

		$date = date("Ymd_His");
		// 파일 다운로드를 위한 헤더 설정
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename='${date}.csv'");
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . strlen($response));
//
//		// 파일 내용을 출력
		echo $response;

	}

}
