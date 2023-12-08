<?php

  /*
  -----------------------------------------------------------------------------------
  등록된 플러스친구 리스트
  -----------------------------------------------------------------------------------
  등록된 플러스 친구 목록을 조회합니다.
  */

  $_apiURL		=	'https://kakaoapi.aligo.in/akv10/profile/list/';
  $_hostInfo	=	parse_url($_apiURL);
  $_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables	=	array(
    'apikey'    => '발급받은 API 키',
    'userid'    => '사용중이신 아이디',
    'token'     => '생성한 토큰 문자열',
    'plusid'    => '플러스친구 아이디(@포함)',
    'senderkey' => '발신프로필키'
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