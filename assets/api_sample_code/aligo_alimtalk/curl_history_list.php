<?php

  /*
  -----------------------------------------------------------------------------------
  전송내역조회
  -----------------------------------------------------------------------------------
  최근 요청및 처리된 전송내역을 조회하실 수 있습니다.
  사이트내 전송결과조회 페이지와 동일한 내역이 조회되며, 날짜기준으로 조회가 가능합니다.
  발신번호별 조회기능은 제공이 되지 않습니다.
  조회시작일을 지정하실 수 있으며, 시작일 이전 몇일까지 조회할지 설정이 가능합니다.
  조회시 최근발송내역 순서로 소팅됩니다.
  */

  $_apiURL		=	'https://kakaoapi.aligo.in/akv10/history/list/';
  $_hostInfo	=	parse_url($_apiURL);
  $_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables	=	array(
    'apikey'        => '발급받은 API 키',
    'userid'        => '사용중이신 아이디',
    'token'         => '생성한 토큰 문자열',
    'page'          => '발신프로필키',
    'limit'         => '템플릿 코드',
    'startdate'     => '조회시작일자',
    'enddate'       => '조회마감일자'
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