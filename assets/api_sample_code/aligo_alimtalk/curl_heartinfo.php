<?php

  /* 
  -----------------------------------------------------------------------------------
  발송가능건수
  -----------------------------------------------------------------------------------
  보유한 충전금 잔액으로 발송가능한 잔여건수를 문자구분(유형)별로 조회하실 수 있습니다.
  SMS, LMS, MMS, ALT 로 발송시 가능한 잔여건수이며 남은 충전금을 문자유형별로 보냈을 경우 가능한 잔여건입니다.
  예를들어 SMS_CNT : 11 , ALT_CNT : 15 인 경우 단문전송시 11건이 가능하고, 알림톡으로 전송시 15건이 가능합니다.
  */

  $_apiURL	  =	'https://kakaoapi.aligo.in/akv10/heartinfo/';
  $_hostInfo	=	parse_url($_apiURL);
  $_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables	=	array(
    'apikey' => '발급받은 API 키',
    'userid' => '사용중이신 아이디',
    'token'  => '생성한 토큰 문자열'
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

