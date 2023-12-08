<?php
include_once("./_common.php"); 
include_once(G5_LIB_PATH.'/PHPExcel.php');

if($_REQUEST['fr_date']) {
    $fr_date = $_REQUEST['fr_date'];
} else {
    //$fr_date = date("Y-m-d 00:00:00", strtotime("-3 Day"));
    $to_date = date("2022-06-08 00:00:00");
}

if($_REQUEST['to_date']) {
    $to_date = $_REQUEST['to_date'];
} else {
    //$to_date = date("Y-m-d 23:59:59");
    $to_date = date("2022-06-08 23:59:59");
}

if($_REQUEST['type']) {
    $type = $_REQUEST['type'];
} 

if($_REQUEST['stx']) {
    $stx = $_REQUEST['stx'];
    $sfl = $_REQUEST['sfl'];
}

$sql_search = "";

if($member['mb_id'] !== "admin") {
    $sql_search .= " where a.region_id = '{$region_id}'";  
    $and = "and";
}  else {
    $and = "where";
}


if($stx) {
    if($sfl == "name") {
        $sql_search .= " {$and} a.name LIKE '%{$stx}%' ";
    } else if($sfl == "user_id") {
        $sql_search .= " {$and} c.user_id = '{$stx}' ";
    } else if($sfl == "phone") {
        $sql_search .= "{$and} a.phone = '{$stx}' ";
            
    }
    
} 

if (!$is_admin =="super")
  alert_close("최고 관리자 영역 입니다.");

/*
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/

$phpExcel = new PHPExcel();

$phpExcel = new PHPExcel();
$phpExcel->setActiveSheetIndex(0);
$phpExcel->getActiveSheet()
    ->setCellValue("A1", "번호")
    ->setCellValue("B1", "ID")
    ->setCellValue("C1", "나이")
    ->setCellValue("D1", "성별")
    ->setCellValue("E1", "이름")
    ->setCellValue("F1", "전화번호")
    ->setCellValue("G1", "지역")
    ->setCellValue("H1", "직업")
    ->setCellValue("I1", "진단명")
    ->setCellValue("J1", "수입")
    ->setCellValue("K1", "가구원")
    ->setCellValue("L1", "검사점수")
    ->setCellValue("M1", "검사점수")
    ->setCellValue("N1", "검사점수")
    ->setCellValue("O1", "측정값")
    ->setCellValue("P1", "날짜");

    $sql = "select account_id, name, phone, region_id from user {$sql_search} order by account_id desc";  // , c.user_id  //  AS a left outer join user_account AS c on (a.account_id=c.id)
    $result = $conn->query($sql);

    //$sheet1 = $phpExcel->createSheet(0);
    //$sheet2 = $phpExcel->createSheet(1);

    //$sheet1->setTitle("GPS");
    //$sheet2->setTitle("IDLE");

    if ($result->num_rows > 0) {

        $no = "1";

        while($user = $result->fetch_assoc()) {
                        
            /*
            if($type == "day") {
                $start_date = date('Y-m-d', strtotime($fr_date));
                $end_date = date('Y-m-d', strtotime($to_date));
            } else {
                $start_date = date('Y-m-d H:i:s', strtotime($fr_date.' 00:00:00'));
                $end_date = date('Y-m-d H:i:s', strtotime($to_date.' 23:59:59'));
            }
            */

            $sql0 = "select job, diagnosis, income, gender, age, family from user_addinfo where account_id = '{$user['account_id']}' ";
            $result0 = sql_query($sql0);
            $user_add = sql_fetch_array($result0);

            $sql1 = " select survey_scale from survey where user_id = '{$user[account_id]}' and survey_type = '우울증' order by survey_date desc limit 1";
            $result1 = sql_query($sql1);
            $sv1 = sql_fetch_array($result1);
        
            $sql2 = " select survey_scale from survey where user_id = '{$user[account_id]}' and survey_type = '불안증' order by survey_date desc limit 1";;
            $result2 = sql_query($sql2);
            $sv2 = sql_fetch_array($result2);
            


            $row="2";
            //while ($end_date >= $start_date) {
                /*
                if($type == "day") {
                    $value1 = day_field1_value($user['account_id'], $end_date);   
                    $value2 = day_field2_value($user['account_id'], $end_date);   
                    $value3 = day_field3_value($user['account_id'], $end_date);   
                    $value4 = day_field4_value($user['account_id'], $end_date);   
                    $value5 = day_field5_value($user['account_id'], $end_date);  
        
                } else if($type == "hour") {
                    $value1 = hour_field1_value($user['account_id'], $end_date); 
                    $value2 = hour_field2_value($user['account_id'], $end_date);  
                    $value3 = hour_field3_value($user['account_id'], $end_date);
                    $value4 = hour_field4_value($user['account_id'], $end_date);  
                    $value5 = hour_field5_value($user['account_id'], $end_date);  
                }
                */

                $phpExcel->setActiveSheetIndex(0)->setCellValue("A".$row, $no)
                ->setCellValue("B".$row, $user['account_id'])
                ->setCellValue("C".$row, $user_add['age'])
                ->setCellValue("D".$row, $user_add['gender'])
                ->setCellValue("E".$row, $user['name'])
                ->setCellValue("F".$row, $user['phone'])
                ->setCellValue("G".$row, $user['region_id'])
                ->setCellValue("H".$row, $user_add['job'])
                ->setCellValue("I".$row, $user_add['diagnosis'])
                ->setCellValue("J".$row, $user_add['income'])
                ->setCellValue("K".$row, $user_add['family'])
                ->setCellValue("L".$row, $sv1['survey_scale'])
                ->setCellValue("M".$row, $sv2['survey_scale'])
                ->setCellValue("N".$row, "32")
                ->setCellValue("O".$row, "11")
                ->setCellValue("P".$row, "1111");


                /*
                if($type == "day") {
                    $end_date = date('Y-m-d', strtotime($end_date . ' -1 day'));   
                } else {              
                    $end_date = date('Y-m-d H:i:s', strtotime($end_date . ' -1 hour'));   
                }
                */


                $row++;
            //}  //날짜별, 시간별돌리기


            $no++;

         } //회원별돌리기
    }




    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="test.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    $objWriter->save('php://output');

    $conn->close();
?>

