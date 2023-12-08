<?php

  /* 
  -----------------------------------------------------------------------------------
  템플릿 등록 요청
  -----------------------------------------------------------------------------------
  알림톡을 전송하기 위해서는 템플릿을 작성해야 하며, 작성된 템플릿은 다음-카카오측의 4-5일간의 검수후
  결과에 따라 거부되어 재작성 요청이 발생할 수 있습니다.
  */

  $_apiURL	  =	'https://kakaoapi.aligo.in/akv10/template/add/';
  $_hostInfo	=	parse_url($_apiURL);
  $_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables	=	array(
    'apikey'	    => '발급받은 API 키', 
    'userid'	    => '사용중이신 아이디', 
    'token'		    => '생성한 토큰 문자열', 
    'senderkey'	  => '발신프로필키', 
    'tpl_name'	  => '등록할 템플릿 명',
    'tpl_content' => '등록할 템플릿 컨텐츠',
    'tpl_button'  => '{"button":[{"name":"테스트 버튼","linkType":"DS"}]}'
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