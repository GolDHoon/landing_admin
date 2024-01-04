<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DrivenLib{


	function encryptString($input, $key) {
		$method = 'aes-256-cbc';
		$encrypted = openssl_encrypt($input, $method, $key, 0, DRIVEN_IV_KEY);

		return base64_encode(DRIVEN_IV_KEY . $encrypted);
	}

	function encrypt($plaintext){
		return $this->encryptString($plaintext, DRIVEN_EN_DE_KEY);
	}


	function decryptString($input, $key) {
		$method = 'aes-256-cbc';
		$input = base64_decode($input);
		$iv = substr($input, 0, 16);
		$encrypted = substr($input, 16);
		return openssl_decrypt($encrypted, $method, $key, 0, $iv);
	}

	function decrypt($encryptedText){
		return $this-> decryptString($encryptedText, DRIVEN_EN_DE_KEY);
	}

	function create_password($param){
		return password_hash($param, PASSWORD_BCRYPT);
	}

	function password_verify($enter_pw,$saved_pw){
		if (password_verify($enter_pw, $saved_pw)) {
			return 1;
		} else {
			return 0;
		}
	}

	function set_pagination($params, $base_url) {

		$params['created_at'] = !empty($params['created_at']) ? $params['created_at'] : "";
		$params['condition'] = !empty($params['condition']) ? $params['condition'] : '';
		$params['search_value'] = !empty($params['search_value']) ? $params['search_value'] : '';

		$config['base_url'] = $base_url . '?created_at=' . urlencode($params['created_at']) . '&condition=' . urlencode($params['condition']) . '&search_value=' . urlencode($params['search_value']);
		$config['num_links'] = 2;
		$config['per_page'] = $params['per_page'];
		$config['enable_query_strings'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;
		$config['query_string_segment'] = 'page';

		$config['suffix'] = "&orderBy_condition={$params['orderBy_condition']}&orderBy_value={$params['orderBy_value']}";
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close'] = '</span></li>';
		$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['prev_link'] = '이전';
		$config['prev_tag_open'] = '<li class="page-item %s"><span class="page-link">';
		$config['prev_tag_close'] = '</span></li>';
		$config['next_link'] = '다음';
		$config['next_tag_open'] = '<li class="page-item %s"><span class="page-link">';
		$config['next_tag_close'] = '</span></li>';
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close'] = '</span></li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
//		$config['first_page_url'] = $config['base_url'] . $config['suffix'];
//		$config['next_link'] = "<button type='button' class='btn btn-info btn-xs'>Next</button>";  //다음으로
//		$config['prev_link'] = "<button type='button' class='btn btn-info btn-xs'>Prev</button>";  //이전으로

		return $config;
	}

	public function get_alim_token(){
		/*
		-----------------------------------------------------------------------------------
		알림톡 토큰 생성
		-----------------------------------------------------------------------------------
		API호출 URL의 유효시간을 결정하며 URL 의 구성중 "30"은 요청의 유효시간을 의미하며, "s"는 y(년), m(월), d(일), h(시), i(분), s(초) 중 하나이며 설정한 시간내에서만 토큰이 유효합니다.
		운영중이신 보안정책에 따라 토큰의 유효시간을 특정 기간만큼 지정할 경우 매번 호출할 필요없이 해당 유효시간내에 재사용 가능합니다.
		주의하실 점은 서버를 여러대 운영하실 경우 토큰은 서버정보를 포함하므로 각 서버에서 생성된 토큰 문자열을 사용하셔야 하며 토큰 문자열을 공유해서 사용하실 수 없습니다.
		*/

		$_apiURL	  =	'https://kakaoapi.aligo.in/akv10/token/create/30/s/';
		$_hostInfo	=	parse_url($_apiURL);
		$_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
		$_variables	=	array(
			'apikey' => $_SESSION['user_sms_api_key'],
			'userid' => $_SESSION['user_sms_id']
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

		$retArr = json_decode($ret);
		return $retArr->token;
	}

	public function check_byte($message){
		return mb_strlen($message, 'UTF-8');
	}

	public function get_alim_template_list($token){
		/*
		-----------------------------------------------------------------------------------
		등록된 템플릿 리스트
		-----------------------------------------------------------------------------------
		등록된 템플릿 목록을 조회합니다. 템플릿 코드가 D 나 P 로 시작하는 경우 공유 템플릿이므로 삭제 불가능 합니다.
		*/

		$_apiURL		=	'https://kakaoapi.aligo.in/akv10/template/list/';
		$_hostInfo	=	parse_url($_apiURL);
		$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
		$_variables	=	array(
			'apikey'    => $_SESSION['user_sms_api_key'],
			'userid'    => $_SESSION['user_sms_id'],
			'token'     => $token,
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

		// 리턴 JSON 문자열 확인
		print_r($ret . PHP_EOL);

		// JSON 문자열 배열 변환
		$retArr = json_decode($ret);

		// 결과값 출력
		print_r($retArr);

		/*
		code : 0 성공, 나머지 숫자는 에러
		message : 결과 메시지
		*/
	}

}
