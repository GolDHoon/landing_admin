document.addEventListener("DOMContentLoaded", function() {
  // 드래그 가능하게
  dragula([
    document.querySelector("#tasks-upcoming"),
    document.querySelector("#tasks-progress"),
    document.querySelector("#tasks-hold"),
    document.querySelector("#tasks-completed")
  ]);
});

// ---------------------------------------------------------------------------------------
// function 
// ---------------------------------------------------------------------------------------

// 한글만
$(document).on("keyup", "input:text[korOnly]", function() {$(this).val( $(this).val().replace(/[a-z0-9]|[ \[\]{}()<>?|`~!@#$%^&*-_+=,.;:\"\\]/g,"") );});
// 영문만
$(document).on("keyup", "input:text[engOnly]", function() {$(this).val( $(this).val().replace(/[0-9]|[^\!-z]/gi,"") );});

// 글자수 체크 (한글 2 byte, 기타 1 byte)
// 글자수 체크 (한글 2 byte, 기타 1 byte)
// 글자수 체크 (한글 2 byte, 기타 1 byte)
String.prototype.getBytes = function() {
  const contents = this;
  let str_character;
  let int_char_count = 0;
  let int_contents_length = contents.length;
  for (k = 0; k < int_contents_length; k++) {
      str_character = contents.charAt(k);
      if (escape(str_character).length > 4)
          int_char_count += 2;
      else
          int_char_count++;
  }
  return int_char_count;
}

// 콘텐츠 수정 :: 사진 수정 시 이미지 미리보기
// 콘텐츠 수정 :: 사진 수정 시 이미지 미리보기
// 콘텐츠 수정 :: 사진 수정 시 이미지 미리보기
function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
          $('#imgArea').attr('src', e.target.result); 
      }
      reader.readAsDataURL(input.files[0]);
  }
}
  
// 배송/교환/반품 안내 
function load_delivery_info()
{
  var data = {}
  $.ajax({
    url: "/configs/get_delivery_info_list",
    dataType: 'json',
    type: "POST",
    async: false,
    data: data,
    beforeSend: function() {$('body').css('opacity', '0.5');},
    success: function(json, textStatus, jqXHR) {
      if(json.result == "OK")
      {
        // 항목 채우기
        var idx = 1;
        var html = "";
        var check_yn = "";
        $.each(json.subject_list, function (key, val) {
          html+="<tr style='background-color:#efefef;'>";
          html+="  <th style='width:150px;'>단락명</th>";
          html+="  <th class='text-start'>"+val.subject+"</th>";
          html +=" <th class='text-start' style='width:300px;'>";
          html +="   <a class='btn btn-success btn-sm btn_edit_delivery' href='#' data-seq='"+val.seq+"' data-bs-toggle='modal' data-bs-target='#modalAddDeliverySubject'>수정</a>&nbsp;";
          html +="   <a class='btn btn-danger btn-sm btn_delete_delivery' href='#' data-seq='"+val.seq+"'>삭제</a> &nbsp;";
          html +="   <button type='button' class='btn btn-info  btn-sm delivery_item_plus'  data-seq='"+val.seq+"' >내용추가</button>";
          html +=" </th>";
          html+"</tr>";

          var rowspan = 0;
          $.each(json.item_list, function (key2, val2) {
            if(val.seq ==  val2.subject_seq) rowspan++;
          });

          var sub_idx = 0;
          $.each(json.item_list, function (key2, val2) {
            if(val.seq ==  val2.subject_seq)
            {
              if(sub_idx == 0)
              {
                html+="<tr>";
                html+="  <th style='width:150px;vertical-align:middle' rowspan='"+rowspan+"'>내용</th>";
                html+="  <td class='text-start'>"+val2.item_idx+". "+val2.item+"</td>";
                html +=" <td class='text-start' style='width:300px;'>";
                html +="   <a class='btn btn-success btn-sm btn_edit_delivery_item' href='#' data-seq='"+val2.seq+"' data-bs-toggle='modal' data-bs-target='#modalAddDeliveryItem'>수정</a>&nbsp;";
                html +="   <a class='btn btn-danger btn-sm btn_delete_delivery_item' href='#' data-seq='"+val2.seq+"'>삭제</a> &nbsp;";
                html +=" </td>";
                html+="</tr>";
              }else{
                html+="<tr>";
                html+="  <td class='text-start'>"+val2.item_idx+". "+val2.item+"</td>";
                html +=" <td class='text-start' style='width:300px;'>";
                html +="   <a class='btn btn-success btn-sm btn_edit_delivery_item' href='#' data-seq='"+val2.seq+"' data-bs-toggle='modal' data-bs-target='#modalAddDeliveryItem'>수정</a>&nbsp;";
                html +="   <a class='btn btn-danger btn-sm btn_delete_delivery_item' href='#' data-seq='"+val2.seq+"'>삭제</a> &nbsp;";
                html +=" </td>";
                html+="</tr>";
              }
              sub_idx++;
            }
          });
          
          
          idx++;
        });
        $("tbody.delivery_info").html('');
        $("tbody.delivery_info").empty();
        $("tbody.delivery_info").append(html);
        
      }else{
        alert(json.msg);
        return;
      }
    },
    complete: function() {$('body').css('opacity', '1.0');},
    statusCode: {404: function() {alert("page not found");}}
  });



}

// 상품정보 제공고시 - 항목리스트 출력하기
function load_goods_item_list(subject_seq)
{
 
  if(!subject_seq) {
    alert("품목을 선택하여 주세요.");
    return;
  }
  
  var data = {"subject_seq" : subject_seq}
  $.ajax({
    url: "/configs/get_goods_notice_item",
    dataType: 'json',
    type: "POST",
    async: false,
    data: data,
    beforeSend: function() {$('body').css('opacity', '0.5');},
    success: function(json, textStatus, jqXHR) {
      if(json.result=="OK"){
        // TODO 예외처리 하기
        //if(json.subject.subject !== undefined)
        {
            $(".notice_subject_name").html(json.subject.subject);
        }

        // 항목 채우기
        var idx = 1;
        var html = "";
        var check_yn = "";
        $.each(json.list, function (key, val) {

          if(val.item_ref_yn=="Y") check_yn="checked='checked'";
          else  check_yn="";
          html+="<tr>";
          html+="  <td rowspan='2'>"+idx+"</td>";
          html+="  <td>항목</td>";
          html+="  <td style='text-align:left'>"+val.item_name+"</td>";
          html+="  <td rowspan='2'><input type='checkbox' name='ref_yn' value='Y' class='ref_yn_"+val.seq+"'' "+check_yn+"></td>";
          html+="  <td rowspan='2'>";
          html+="        <a class='btn btn-secondary update_notice_item' data-seq='"+val.seq+"'>수정</a>&nbsp;";            
          html+="        <a class='btn btn-danger btn_delete_notice_item' data-seq='"+val.seq+"'>삭제</a>";            
          html+="   </td>";
          html+="</tr>";
          html+="<tr>";
          html+="   <td>내용</td>";
          html+="   <td><textarea class='form-control notice_item_contents_"+val.seq+"' rows='3'>"+val.item_contents+"</textarea></td>";
          html+="</tr>";
          idx++;
        });
        $(".notice_subject_item").html('');
        $(".notice_subject_item").empty();
        $(".notice_subject_item").append(html);
        
      }else{
        alert(json.msg);
        return;
      }
    },
    complete: function() {$('body').css('opacity', '1.0');},
    statusCode: {404: function() {alert("page not found");}}
  });
}

// 상품정보 제공고시 - 품목리스트 출력하기
function load_goods_notice_list()
{
  var data = {}
  $.ajax({
    url: "/configs/get_goods_notice_list",
    dataType: 'json',
    type: "POST",
    async: false,
    data: data,
    beforeSend: function() {$('body').css('opacity', '0.5');},
    success: function(json, textStatus, jqXHR) {
      if(json.result=="OK"){

        
        $(".notice_subject").children('option:not(:first)').remove();
        $.each(json.list, function (key, val) {
          $(".notice_subject").append('<option value="'+val.seq+'">'+val.subject+'</option>');
        });
        
      }else{
        alert(json.msg);
        return;
      }
    },
    complete: function() {$('body').css('opacity', '1.0');},
    statusCode: {404: function() {alert("page not found");}}
  });
}

// 상품컬러필터 가져오기
function load_color_filter_list()
{

  var color_name = $(".color_name").val();
  var color_value = $(".selected_color").val();
  var data = { "color_name" : color_name, "color_value"  : color_value}
  $.ajax({
    url: "/configs/get_color_filter_list",
    dataType: 'json',
    type: "POST",
    async: false,
    data: data,
    beforeSend: function() {$('body').css('opacity', '0.5');},
    success: function(json, textStatus, jqXHR) {
      if(json.result=="OK"){
        // 색상 검색  초기화 
        $('.search_color_list').empty();
        $('.search_color_list').html('');

        // 색상 검색 추가 
        var html = "";
        $.each(json.list, function (key, val) {
          html +="<a class='btn btn-sm btn_select_color' style='width:25px;background-color:"+val.color_value+"' href='#' data-color='"+val.color_value+"' title='"+val.color_kor+"'>&nbsp;</a>&nbsp;";
        });
        $('.search_color_list').append(html);

        // 리스트 초기화 
        $('tbody.color_list').empty();
        $('tbody.color_list').html('');

        // 리스트 추가 
        var html = "";
        $.each(json.list, function (key, val) {
          html +="<tr>";
          html +="<td class='text-center'>";
          html +="<input type='text' class='form-control color_idx_list text-center' style='width:100px' value='"+val.color_idx+"' readonly>";
          html +="<input type='hidden' class='color_seq_list' value='"+val.seq+"'>";
          html +="</td>";
          html +="<td class='text-center'>";
          html +="  <a class='btn btn-sm btn_select_color' style='width:25px;background-color:"+val.color_value+"'  title='"+val.color_value+"'>&nbsp;</a>";
          html +="</td>";
          html +="<td class='text-center'>"+val.color_kor+"</td>";
          html +="<td class='text-center'>"+val.color_eng+"</td>";
          html +="<td class='text-center'>"+val.color_use_yn+"</td>";
          html +="<td class='text-center'>";
          html +="  <a class='btn btn-success btn-sm btn_edit_color' href='#' data-seq='"+val.seq+"'  data-bs-toggle='modal' data-bs-target='#modalAddColor'>수정</a>&nbsp;";
          html +="  <a class='btn btn-danger btn-sm btn_delete_color' href='#' data-seq='"+val.seq+"' data-color_kor='"+val.color_kor+"'>삭제</a>";
          html +="</td>";
          html +="</tr>";
        });
        $('tbody.color_list').append(html);

        
      }else{
        alert(json.msg);
        return;
      }
    },
    complete: function() {$('body').css('opacity', '1.0');},
    statusCode: {404: function() {alert("page not found");}}
  });
}

// 실측사이즈 이미지 미리보기 
$(":input[name='survey_file']").change(function() {
  if( $(":input[name='survey_file']").val() == '' ) {
      $('#imgArea').attr('src' , '');  
  }
  $('#imgViewArea').css({ 'display' : '' });
  readURL(this);
});

// 실측사이즈 이미지 에러 시 미리보기영역 미노출
function imgAreaError(){
  $('#imgViewArea').css({ 'display' : 'none' });
}

// 실측사이즈 기준표 상세 정보 가져오기
function load_survey_detail(cate_seq)
{
  var data = {"cate_seq" : cate_seq}
  $.ajax({
    url: "/configs/get_survey_detail",
    dataType: 'json',
    type: "POST",
    async: false,
    data: data,
    beforeSend: function() {$('body').css('opacity', '0.5');},
    success: function(json, textStatus, jqXHR) {
      if(json.result=="OK"){
        
        if(json.img_info)
        {
          if(json.img_info.survey_image)
          {
            // 이미지 출력
            $("#imgArea").attr("src", json.img_info.survey_image);
            
          }else{
            $("#imgArea").attr("src", "/assets/img/configs/survey_ready.png");
          }
        }else{
          $("#imgArea").attr("src", "/assets/img/configs/survey_ready.png");
        }

        // 실측사이즈 기준 출력
        $('div#tasks-upcoming').empty();
        $('div#tasks-upcoming').html('');

        var html = "";
        $.each(json.item_list, function (key, val) {
          html +="<div class='card mb-3 bg-light cursor-grab div_survey_item_"+val.seq+"'>";
          html +="<div class='card-body p-3'>";
          html +="<div class='float-end me-n2'> cm";
          html +="</div>";
          html +="<p>"+val.item_name+"</p>";
          html +="<input type='hidden' name='survey_seq_list' class='survey_seq_list' value='"+val.seq+"'>";
          html +="<a class='btn btn-secondary btn-sm btn_update_survey_item' data-seq='"+val.seq+"' data-bs-toggle='modal' data-bs-target='#modalUpdateSurvey' data-txt='"+val.item_name+"'>수정</a> &nbsp;&nbsp;";
          html +="<a class='btn btn-danger btn-sm btn_delete_survey_item' href='#' data-seq='"+val.seq+"' data-txt='"+val.item_name+"'>삭제</a>";
          html +="</div>";
          html +="</div>";
        });

        $('#tasks-upcoming').append(html);
        
      }else{
        alert(json.msg);
        return;
      }
    },
    complete: function() {$('body').css('opacity', '1.0');},
    statusCode: {404: function() {alert("page not found");}}
  });
}

// 한정판매문구 가져오기
function load_limited_sale_list(seq)
{

  var data = {}
  $.ajax({
    url : '/configs/get_limited_sale_list',
    type: "POST",
    dataType: 'json',
    async: false,
    data: data,
    beforeSend: function() {$('body').css('opacity', '0.5');},
    success: function(json, textStatus, jqXHR) {
      if(json.result=="OK"){        
        // 테이블에 추가하기
        var chk = "";
        var html = "";
        $.each(json.list, function(idx, val){
          html +="<tr class='tr_limited_sale'>";
          html +="<td scope='row' class='text-center'>"+val.state_name+"</td>";
          html +="<td scope='row' class 'text-center'>"+val.state_text+"</td>";
          html +="<td class='text-center'>";
          html +="<a class='btn btn-success btn-sm btn_view_limited_sale' data-seq='"+val.seq+"' data-bs-toggle='modal' data-bs-target='#modalLimitedSaleView'>미리보기</a> &nbsp;&nbsp;";            
          html +="</td>";
          html +="<td class='text-center'>";
          html +="<a class='btn btn-secondary btn-sm btn_edit_limited_sale' data-seq='"+val.seq+"' data-bs-toggle='modal' data-bs-target='#modalLimitedSale'>수정하기</a> &nbsp;&nbsp;";            
          html +="</td>";
          html +="</tr>";
        });
        $("tbody.tbody_limited_sale").empty().html(html);
      }else{
        alert(json.msg);
        return;
      }
    },
    complete: function() {$('body').css('opacity', '1.0');},
    statusCode: {404: function() {alert("page not found");}}
  });
}

// 제품정보제공고시 항목
var notice_item = "";
notice_item +="<div class='mb-3 row notice_item'>";
notice_item +="    <label class='col-form-label col-sm-2 text-sm-end'>항목 </label>";
notice_item +="    <div class='col-sm-7'>";
notice_item +="        <input type='text' class='form-control input_notice_item'>";
notice_item +="    </div>";
notice_item +="    <div class='col-sm-3'>";
notice_item +="        <button type='button' class='btn btn-info  notice_item_plus'>+</button>";
notice_item +="        <button type='button' class='btn btn-danger  notice_item_minus'> - </button>";
notice_item +="        &nbsp;";
notice_item +="        <button type='button' class='btn btn-primary  notice_item_up'> ∧ </button>";
notice_item +="        <button type='button' class='btn btn-success   notice_item_down'>∨ </button>";
notice_item +="    </div>";
notice_item +="</div>";

// ---------------------------------------------------------------------------------------
//
//
// jquery 
//
//
// ---------------------------------------------------------------------------------------
$(document).ready(function(){

  // 실측이미지 클릭시 파일선택 연동
  $("#imgArea").click(function(){
    $(".survey_file").click();
  });


  // 배송/교환/반품안내 - 단락 삭제하기
  $("tbody.delivery_info").on("click", ".btn_delete_delivery", function(e){
    e.preventDefault();

    var seq = $(this).data('seq');
    if(!confirm("삭제하시겠습니까?")) return;

    var data = {'seq' : seq}
    $.ajax({
      url: "/configs/delete_delivery_info_subject",
      dataType: 'json',
      type: "POST",
      async: true,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        console.log(json);
        if(json.result=="OK"){
          load_delivery_info(); // 리스트 리로드          
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });    

  // 배송/교환/반품안내 - 내용 삭제하기
  $("tbody.delivery_info").on("click", ".btn_delete_delivery_item", function(e){
    e.preventDefault();

    var seq = $(this).data('seq');
    if(!confirm("삭제하시겠습니까?")) return;

    var data = {'seq' : seq}
    $.ajax({
      url: "/configs/delete_delivery_info_item",
      dataType: 'json',
      type: "POST",
      async: true,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        console.log(json);
        if(json.result=="OK"){
          load_delivery_info(); // 리스트 리로드          
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });  
  
  // 배송/교환/반품안내 - 내용 수정하기
  $("#modalAddDeliveryItem").on("click", ".btn_update_delivery_info_item", function(){
    var seq = $("#delivery_info_item_seq").val();
    var item = $("#delivery_info_item").val(); 
    var item_idx = $("#delivery_info_item_idx").val(); 
    
    var data = {'seq' : seq, "item" :  item, "item_idx" : item_idx}
    $.ajax({
      url: "/configs/update_delivery_info_imem",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("저장하였습니다.");
          $('.modal').modal("hide"); // 모달 닫기 
          load_delivery_info(); // 리스트 리로드
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });

  // 배송/교환/반품안내 - 내용 수정 모달폼 띄울때 초기화 (수정하기)
  $("tbody.delivery_info").on("click", ".btn_edit_delivery_item", function(){

    
    var seq = $(this).data('seq');
    $("#delivery_info_item_seq").val(seq);

    var data = {'seq' : seq}
    $.ajax({
      url: "/configs/get_delivery_info_item",
      dataType: 'json',
      type: "POST",
      async: true,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        console.log(json);
        if(json.result=="OK"){
          $("#delivery_info_item").val(json.info.item);
          $("#delivery_info_item_idx").val(json.info.item_idx);
          
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });

  // 배송/교환/반품안내 - 내용 저장하기
  $("tbody.delivery_info").on("click", ".save_delivery_item", function(){
    var subject_seq = $(this).data('subject_seq');
    var item_idx = '';
    var item = '';
    
    // 내용 출력 순번
    var tmp = $(this).parents('tr').find(".input_devlivery_idx");
    item_idx = tmp.val();
    
    // 내용
    var tmp = $(this).parents('tr').find(".input_delivery_item");
    item = tmp.val();

    var data = {'subject_seq' : subject_seq, "item_idx" : item_idx, "item" :  item}
    $.ajax({
      url: "/configs/insert_delivery_info_imem",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("저장하였습니다.");
          $('.modal').modal("hide"); // 모달 닫기 
          load_delivery_info(); // 리스트 리로드
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });

  // 배송/교환/반품안내 - 내용 FORM 추가 
  $("tbody.delivery_info").on("click", ".delivery_item_plus", function(){
    var subject_seq = $(this).data("seq");
    var html = "";
    html +="<tr>";
    html +="  <td><input type='number' class='form-control input_devlivery_idx' placeholder='순서'></td>";
    html +="  <td><input type='text' class='form-control input_delivery_item'></td>";
    html +="  <td><button type='button' class='btn btn-primary btn-sm save_delivery_item' data-subject_seq='"+subject_seq+"'>완료</button>";
    html +="</tr>";

    $(this).parent().parent().after(html);
  });

  // 배송/교환/반품안내 - 단락명 모달폼 띄울때 초기화 (수정하기)
  $("tbody.delivery_info").on("click", ".btn_edit_delivery", function(){

    
    var seq = $(this).data('seq');
    $("#delivery_info_subject_seq").val(seq);

    var data = {'seq' : seq}
    $.ajax({
      url: "/configs/get_delivery_info_subject",
      dataType: 'json',
      type: "POST",
      async: true,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        console.log(json);
        if(json.result=="OK"){
          $("#delivery_info_subject").val(json.info.subject);
          $("#delivery_info_subject_idx").val(json.info.subject_idx);
          
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });

  // 배송/교환/반품안내 - 단락명 모달폼 띄울때 초기화 (신규등록)
  $(".btn_delivery_info_subject").click(function(){
    $("#delivery_info_subject").val('');
    $("#delivery_info_subject_seq").val('');
    $("#delivery_info_subject_idx").val('');
  });
  
  // 배송/교환/반품안내 - 단락명 저장하기 (모달폼에서 클릭)
  $(".btn_add_delivery_info_subject").on('click', function() {     

    var subject = $("#delivery_info_subject").val();
    var seq = $("#delivery_info_subject_seq").val();
    var subject_idx = $("#delivery_info_subject_idx").val();

    var data = {'subject' : subject, "seq" : seq, "subject_idx" :  subject_idx}
    $.ajax({
      url: "/configs/insert_delivery_info_subject",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("저장하였습니다.");
          $('.modal').modal("hide"); // 모달 닫기 
          load_delivery_info(); // 리스트 리로드
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  });



  // 상품정보제공고시 항목 추가 버튼 클릭시
  $(".notice_subject_item").on('click', '.btn_add_notice_item', function(e) { 
    
    var tr = $(this).parent().parent();
    var tmp = tr.find($(".add_item_name"));
    var item_name = tmp.val();
    
    var item_ref_yn = "N";
    tmp = tr.find($(".ref_yn"));
    if(tmp.prop('checked') == true) {
      item_ref_yn = "Y";
    }    

    tr = tr.next();
    tmp = tr.find($(".add_item_contents"));
   
    var item_contents = tmp.val();
    var subject_seq = $("select.notice_subject").val();
    var data = {"subject_seq" : subject_seq, "item_name" : item_name, "item_ref_yn" : item_ref_yn, "item_contents" : item_contents}
    console.log(data);

    $.ajax({
      url: "/configs/add_notice_item",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("저장하였습니다.");
          var subject_seq = $("select.notice_subject").val();
          load_goods_item_list(subject_seq);
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });

  });




  // 상품정보제공고시 항목 삭제 버튼 클릭시
  $(".notice_subject_item").on('click', '.update_notice_item', function(e) { 
    e.preventDefault();
    
    if(!confirm("수정하시겠습니까?")) return;

    var seq = $(this).data("seq");
    var contents = $(".notice_item_contents_"+seq).val();
    var ref_yn = "N";
    if($(".ref_yn_"+seq).prop('checked') == true) {
      ref_yn = "Y";
    }
    var data = {"seq" : seq, "contents" : contents, "ref_yn" : ref_yn}
    
    $.ajax({
      url: "/configs/update_notice_item",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("수정하였습니다.");
          var subject_seq = $("select.notice_subject").val();
          load_goods_item_list(subject_seq);
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  });


  // 상품정보제공고시 항목 삭제 버튼 클릭시
  $(".notice_subject_item").on('click', '.btn_delete_notice_item', function() { 
    var seq = $(this).data("seq");
    if(!confirm("삭제하시겠습니까?")) return;
    
    var data = {"seq" : seq}
    $.ajax({
      url: "/configs/delete_notice_item",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          if(json.subject_reload=="N")
          {
            // 항목만 삭제한 경우
            var subject_seq = $("select.notice_subject").val();
            load_goods_item_list(subject_seq);
            return;
          }else{
            // 항목과 품목을 모두 삭제한 경우 
            load_goods_notice_list();

            // 항목영역 초기화 
            $(".notice_subject_item").html('');
            $(".notice_subject_item").empty();
            $(".notice_subject_name").html("품목을 선택해 주세요.");
            return;
          }  
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  });

  
  // 상품정보제공고시 항목 추가 버튼 클릭시
  $(".btn_add_suject_item").off('click').on('click', function() { 
      
      var subject_seq = $(".notice_subject").val();
      if(!subject_seq) {
        alert("먼저 추가할 품목을 선택하여 주세요.");
        return false;
      }

      // 항목 추가 
      var html = "";
      html+="<tr>";
      html+="  <td rowspan='2'>-</td>";
      html+="  <td>항목</td>";
      html+="  <td style='text-align:left'><input type='text' class='form-control add_item_name'></td>";
      html+="  <td rowspan='2'><input type='checkbox' name='ref_yn' value='Y' class='ref_yn'></td>";
      html+="   <td rowspan='2'>";
      html+="        <a class='btn btn-success btn_add_notice_item' data-seq='add'>추가하기</a>";            
      html+="   </td>";
      html+="</tr>";
      html+="<tr>";
      html+="   <td>내용</td>";
      html+="   <td><textarea class='form-control add_item_contents' rows='3'></textarea></td>";
      html+="</tr>";
 
    
    $(".notice_subject_item").append(html);      



  });


  // TODO         
  $(".notice_subject").change(function() { 
    var subject_seq = $(this).val();
    
    // 영역 초기화 
    if(subject_seq == "")
    {
      $(".notice_subject_item").html('');
      $(".notice_subject_item").empty();
      $(".notice_subject_name").html("품목을 선택해 주세요.");
    }else{
      // 항목리스트 가져와 출력하기
      load_goods_item_list(subject_seq);
    }
  });


  // 상품정보제공고시 품목 추가 - 모델창 저장하기
  $(".btn_save_notice").off('click').on('click', function() { 
 
    // 품목명
    var subject = $("input.modal_notice_subject").val();
    
    // 항목
    var item_list = new Array();
    $('.notice_item_list div.notice_item').each(function (index, item) {
      item_list.push($(this).find(".input_notice_item").val());
    });
    
    // if(!confirm("저장하시겠습니까?")) return;

    var data = {"subject" : subject, "item_list"  : item_list}
    $.ajax({
      url: "/configs/save_notice_subject",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("\n품목 리스트에 추가되었습니다.");
          $('.modal').modal("hide"); // 모달 닫기 
          load_goods_notice_list(); // 리스트 리로드          
          
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    }); 




  });


  
  // 상품정보 제공고시 품목 추가 - 모델창 띄울때 초기화 
  $(".btn_add_subject").click(function(){
    $('.notice_item_list').empty();
    for(var i = 0; i<4; i++)
    {
      $('.notice_item_list').append(notice_item);
    }
  });

  // 상품정보 제공고시 품목 추가 - 모달창의 항목 위로 이동
  $("#modalAddNotice").on("click", ".notice_item_up", function(){
    var html = $(this).parent().parent().clone();
    var idx = $(".notice_item").index($(this).parent().parent());
    if(idx == 0) return;
    $(this).parent().parent().remove();
    if(idx == 1) $('.notice_item_list').prepend(html);
    else $('.notice_item').eq(idx-2).after(html);
  });

  // 상품정보 제공고시 품목 추가 - 모달창의 항목 아래로 이동
  $("#modalAddNotice").on("click", ".notice_item_down", function(){
    var html = $(this).parent().parent().clone();
    var idx = $(".notice_item").index($(this).parent().parent());
    var cnt = $(".notice_item").length;
    if(idx+1 == cnt) return;
    $(this).parent().parent().remove();
    $('.notice_item').eq(idx).after(html);
  });


  // 상품정보 제공고시 품목 추가 - 모달창의 항목 추가
  $("#modalAddNotice").on("click", ".notice_item_plus", function(){
    $(".notice_item_list").append(notice_item);
  });

  // 상품정보 제공고시 품목 삭제 - 모달창의 항목 삭제
  $("#modalAddNotice").on("click", ".notice_item_minus", function(){
    $(this).parent().parent().remove();
  });



  // 상품 컬러필 터 순서 수정을 위한 readonly 제거
  $(".btn_update_color_idx").click(function () {
    $(".color_idx_list").removeAttr("readonly");
    $(".btn_update_color_idx").addClass("d-none");
    $(".btn_save_color_idx").removeClass("d-none");
  });

  // 상품 컬러 필터 순서 저장하기
  $(".btn_save_color_idx").click(function () {

    $(".btn_save_color_idx").addClass("d-none");
    $(".btn_update_color_idx").removeClass("d-none");
    $(".btn_update_color_idx").attr('readonly', true);
      
    let color_idx_list = [];
    let color_seq_list = [];
    
    $('tbody.color_list tr').each(function (index, item) {
      color_idx_list.push($(this).find(".color_idx_list").val());
      color_seq_list.push($(this).find(".color_seq_list").val());
    });
      
    let arrData = {
      "seq_list" : color_seq_list,
      "idx_list" : color_idx_list
    };

    $.ajax({
      url: "/configs/update_color_idx",
      dataType: 'json',
      type: "POST",
      async: false,
      data: arrData,
      beforeSend: function() {},
      success: function(json) {
        if(json.result=="OK"){
          alert("저장 되었습니다.");
          load_color_filter_list();
        }else{
          alert(json.msg);
        }
      },
      complete: function() {},
      statusCode: {404: function() {alert("page not found");}}
    });
});



  // 상품컬러필터 수정모달 띄우기
  $("tbody.color_list").on("click", ".btn_edit_color", function(e){
    
    var seq = $(this).data("seq");
    var data = {"seq" : seq}
    $.ajax({
      url: "/configs/get_color_filter",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          // 모달창에 값 셋팅하기
          // 모달창에 값 셋팅하기
          // 모달창에 값 셋팅하기
          if(json.info){
            $(".selected_color_seq").val(json.info.seq);
            $(".color_kor").val(json.info.color_kor);
            $(".color_eng").val(json.info.color_eng);
            $(".now_color_value").val(json.info.color_value);
            $(".color_idx").val(json.info.color_idx);
            $("input:radio[name ='color_use_yn']:input[value='"+json.info.color_use_yn+"']").prop("checked", true); // 표시형태
            // 색상 매칭
            
            $("#my-color-picker-1").children().first().css("background-color", json.info.color_value);
          }
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    }); 
  });

  // 상품컬러필터 검색하기
  $(".btn_search_color").click(function(){
    var color_name = $(".color_name").val();
    var color_value = $(".selected_color").val();
    if(!$.trim(color_name) && !$.trim(color_value))
    {
      alert("색상명이나 색상값을 선택하여 주세요.");
      return false;
    }
    load_color_filter_list();
  });

  // 검색에서 색상 선택시
  $(".search_color_list").on("click", "a.btn_select_color", function(e){
    e.preventDefault();
    var select_color = $(this).data("color");
    $(".selected_color").val(select_color);
  });

  // 색상 삭제하기
  $("tbody.color_list").on("click", ".btn_delete_color", function(e){
    e.preventDefault();
    var seq = $(this).data("seq");
    var color_kor = $(this).data("color_kor");

    if(!confirm("["+color_kor+"] 삭제하시겠습니까?")) return;

    
    var data = {"seq" : seq}
    $.ajax({
      url: "/configs/delete_color_filter",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          load_color_filter_list();
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    

  });

  // 색상 저장하기
  $(".btn_save_color").click(function(){
    var seq = $(".selected_color_seq").val();
    var color_kor = $(".color_kor").val();
    var color_eng = $(".color_eng").val();
    var now_color_value = $(".now_color_value").val();
    var color_idx = $(".color_idx").val();
    var color_use_yn = $(".color_use_yn").val();
    var data = {"seq": seq, "color_kor" : color_kor, "color_eng"  : color_eng, "color_value" : now_color_value, "color_idx" : color_idx, "color_use_yn" : color_use_yn}
    $.ajax({
      url: "/configs/insert_color_filter",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert("저장하였습니다.");
          $(".selected_color_seq").val('');
          $(".color_kor").val('');
          $(".color_eng").val('');
          $(".now_color_value").val('');
          $(".color_idx").val('');
          $('.modal').modal("hide"); //모달 닫기 
          load_color_filter_list();
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });

  // 칼러픽커 select 클릭시 input에 값 넘기기
  $(".jcp-btn").click(function(){
    var color_value = $(".color_value").val();
    $(".now_color_value").val("#"+color_value);
    $(".now_color_value").click();
  });

  // 한정판매문구 수정하기 
  $(".btn_update_limited_sale").click(function(){

    var seq = $(".now_limited_sale_seq").val();
    var state_text= $("#update_limited_sale_text").val();
    var data = {"seq" : seq, "state_text"  : state_text}
    console.log(data);
    $.ajax({
      url: "/configs/update_limited_sale",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          load_limited_sale_list();
          $('.modal').modal("hide"); //모달 닫기 
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });

  });


  // 한정판매문구 수정 모달 띄우기
  $("tbody.tbody_limited_sale").on("click", "a.btn_edit_limited_sale", function(e){    
    var seq = $(this).data('seq');
    $(".now_limited_sale_seq").val(seq);
    var data = {"seq" : seq}
    $.ajax({
      url: "/configs/get_limite_sale",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          $("#update_limited_sale_name").val(json.info.state_name);
          $("#update_limited_sale_text").val(json.info.state_text);
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });   


  });






  // 실측사이즈 아이템 수정
  $(".btn_update_survey_item").click(function(){
    
    var seq = $("#update_survey_item_seq").val();
    var item_name = $("#update_survey_item_name").val();

    var data = {"seq" : seq, "item_name"  : item_name}
    $.ajax({
      url: "/configs/update_survey_item",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          $('.modal').modal("hide"); //모달 닫기 
          $('#jstree3').jstree(true).refresh();	// 트리 재실행, (트리 재실행될때 리스트도 가져옴)
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });   

  });


  // 실측사이즈 아이템 수정 모달 초기화
  $("div#tasks-upcoming").on("click", "a.btn_update_survey_item", function(e){
    e.preventDefault();

    var seq = $(this).data("seq");
    var txt = $(this).data("txt");
    
    $("#update_survey_item_seq").val(seq);
    $("#update_survey_item_name").val(txt);

    console.log(seq, txt);
});


  // 실측사이즈 순서 저장하기
  $("button.btn_insert_survey_list").click(function(){
    

    var cate_seq = $(".selected_category_id").val();
    var item_list = new Array();

    // 실측사이즈 seq
    $("div#tasks-upcoming").find(".survey_seq_list").each(function(idx, val) {
        
       if($(this).val()) item_list.push($(this).val());
    });

    if(!confirm("순서를 저장하시겠습니까?")) return;
    
    var data = {"cate_seq" : cate_seq, "item_list"  : item_list}
    $.ajax({
      url: "/configs/update_survey_order",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          // 해당DIV 삭제
          alert("저장하였습니다.");
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
    
    
  });



  // 실측사이즈 아이템 삭제 
  $("div#tasks-upcoming").on("click", "a.btn_delete_survey_item", function(e){
      e.preventDefault();

      var seq = $(this).data("seq");
      var txt = $(this).data("txt");
      

      if(!confirm("선택하신 [ "+txt+" ]를 삭제 하시겠습니까?")) return;
      var data = {"seq" : seq}
      $.ajax({
        url: "/configs/delete_survey_item",
        dataType: 'json',
        type: "POST",
        async: false,
        data: data,
        beforeSend: function() {$('body').css('opacity', '0.5');},
        success: function(json, textStatus, jqXHR) {
          if(json.result=="OK"){
            // 해당DIV 삭제
            $("div#tasks-upcoming").find(".div_survey_item_"+seq).remove();
            return;
          }else{
            alert(json.msg);
            return;
          }
        },
        complete: function() {$('body').css('opacity', '1.0');},
        statusCode: {404: function() {alert("page not found");}}
      });
  });

  // 실측사이즈 이미지 업로드
  $(".btn_insert_survey_image").off('click').on('click', function() {
    var form = $('.form_survey_image')[0];
    var formData = new FormData(form);

    if(!$.trim($(".survey_file").val())) {
      alert("파일 선택을 먼저 해주세요.");
      $('.survey_file').click();
      return;
    }
    $.ajax({
        url : '/configs/insert_survey_image',
        type : 'POST',
        data : formData,
        contentType : false,
        processData : false,
        beforeSend: function() {$('body').css('opacity', '0.5');},
        success: function(resonse, textStatus, jqXHR) {
          json = JSON.parse(resonse);
          if(json.result=="OK"){
            alert(json.msg);
            $('#jstree3').jstree(true).refresh();	// 트리 재실행, (트리 재실행될때 리스트도 가져옴)
            return;
          }else{
            alert(json.msg);
            return;
          }
        },
        complete: function() {$('body').css('opacity', '1.0');},
        statusCode: {404: function() {alert("page not found");}}       
    });
  });

  // 실측사이즈이미지 삭제
  $(".btn_delete_survey_image").off('click').on('click', function() {

    if(!confirm("실측이미지를 삭제하시겠습니까?")) return;
    var cate_seq = $(".selected_category_id").val();
    var data = {"cate_seq" : cate_seq}
    $.ajax({
      url: "/configs/delete_survey_image",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          $('#jstree3').jstree(true).refresh();	// 트리 재실행, (트리 재실행될때 리스트도 가져옴)
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  });

  // 기준추가 모달 초기화
  $(".btn_modal_add_item").click(function(){
    $("#survey_item_name").val("");
  });
  
  // 실측사이즈 아이템 저장
  
  $("div#modalAddSurvey").off("click").on("click", "button.btn_insert_survey_item", function(e){

    var cate_seq = $(".selected_category_id").val();
    var item_name = $("#survey_item_name").val();

    //var item_list = new Array();

    if(!$.trim(cate_seq)){
      alert("카테고리를 선택하여 주세요.");
      return false;
    }

    if(!$.trim(item_name)){
      alert("기준명을 입력하여 주세요.");
      return false;
    }

    var data = {"cate_seq" : cate_seq, "item_name" : item_name}
    $.ajax({
      url: "/configs/insert_survey_item",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {

        if(json.result=="OK"){
          $('.modal').modal("hide"); //모달 닫기 
          $('#jstree3').jstree(true).refresh();	// 트리 재실행, (트리 재실행될때 리스트도 가져옴)
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });  
  });

  // 실측사이즈 아이템 취소
  $(".btn_reset_survey_item").click(function(){
    $('#tasks-upcoming').empty();
    $('#tasks-upcoming').html('');
  });

  // 실측사이즈 기준표 복사
  $(".btn_add_item").click(function(){
    // 
  });

  // 실측사이즈 이미지 등록
  $(".btn_survey_image").click(function(e){
    $("div.tab_4_btn_1").hide();
    $("div.tab_4_btn_2").removeClass("visually-hidden");


    $("div.tab-4_1").hide();
    $("div.tab-4_2").removeClass("visually-hidden");
    $("div.tab-4_2").fadeIn();
  });

  // 실측사이즈기준표로 복귀
  $(".btn_survey_back").click(function(e){
    $("div.tab_4_btn_2").addClass("visually-hidden");
    $("div.tab_4_btn_1").show();

    $("div.tab-4_2").addClass("visually-hidden");
    $("div.tab-4_2").hide();
    $("div.tab-4_1").fadeIn();
    // load_poll_list_main();
  });



  // 상품만족도를 카테고리와 연결하기
  $(".btn_connect_category").click(function(){
    
    var cate_seq = $(".selected_category_id").val();
    var seq_list = [];
    $('.table_goods_poll_main .chk_goods_poll_seq').each(function (index, item) {
      if($(this).is(":checked") == true){
        seq_list.push($(this).val());
      }
    });  
    
    if(!cate_seq){
      alert("중분류를 선택하여 주세요.");
      return;
    }

    if(seq_list.length > 6)
    {
      alert("항목은 6개까지 선택하실 수 있습니다.");
      return;
    }
    
    var data = {"cate_seq" : cate_seq, "seq_list" : seq_list}
    $.ajax({
      url: "/configs/insert_category_poll",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        
        if(json.result=="OK"){
          //alert(json.msg);
          load_poll_list_main(cate_seq);
          $('#jstree2').jstree(true).refresh();	
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });  


  });

  // 배송/교환/반품(tab-8) 클릭시
  $(".tab-8").click(function(){
    load_delivery_info();
  });  

  // 상품정보 제공고시(tab-7) 클릭시
  $(".tab-7").click(function(){
    load_goods_notice_list();
  });  

  // 상품컬러필터(tab-6) 클릭시
  $(".tab-6").click(function(){
    load_color_filter_list();
  });

  // 한정판매문구(tab-5) 클릭시
  $(".tab-5").click(function(){
    load_limited_sale_list();
  });
  

  // 상품만족도탭(tab-3) 클릭시
  $(".tab-3").click(function(){
    load_poll_list_main();
  });


  // 상품만족도 선택 삭제
  $(".btn_select_delete").click(function(){
    
    let seq_list = [];
    $('#table_goods_pool .chk_goods_poll_seq').each(function (index, item) {
      if($(this).is(":checked") == true){
        seq_list.push($(this).val());
      }
    });

    if(seq_list.length == 0){
      alert("삭제할 항목을 선택하여 주세요.");
      return;
    } 


    if(!confirm("선택한 항목을 삭제하시겠습니까?")) return;

    
    var data = {"seq_list" : seq_list}
    $.ajax({
      url: "/configs/delete_select_goods_poll",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          load_poll_list();
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });    
  });

  var now_question = "";
  var now_answer_1 = "";
  var now_answer_2 = "";
  var now_answer_3 = "";

  // 질문의 키업
  $("#poll_question").keyup(function() {
    var str = $(this).val();
    var qcnt = str.getBytes();

    var answer = ""; // 답변
    var acnt = 0;    // 답변 카운트
    var tmp = 0;
    for(var i=1; i<=3; i++)
    {
      answer = $("#poll_answer_"+i).val();
      acnt = answer.getBytes();
      if(qcnt + acnt > 20) 
      {
        $("#poll_question").val(now_question);
        tmp = qcnt+acnt;
        $("#poll_question").val(now_question);
        qcnt = now_question.getBytes();
        $(".question_byte").html(qcnt);    
      }
    }
    now_question = $("#poll_question").val();
    $(".question_byte").html(qcnt);
  });

  // 답변의 키업
  $("#poll_answer_1, #poll_answer_2, #poll_answer_3").keyup(function() {
    
    var seq = $(this).data("seq");
    
    var str = $(this).val();
    var acnt = str.getBytes();
    
    var question = $("#poll_question").val();
    var qcnt = question.getBytes();
    
    if(acnt + qcnt > 20) {
    

      if(seq ==  1) {
        $("#poll_answer_1").val(now_answer_1);
      }else if(seq == 2) {
        $("#poll_answer_2").val(now_answer_2);
      }else if(seq == 3) {
        $("#poll_answer_3").val(now_answer_3);
      }
    }else{
      if(seq ==  1) {
        now_answer_1 = $(this).val();
        $("#poll_answer_1").val(now_answer_1);
        $(".answer_1_byte").html(acnt);
      }else if(seq == 2) {
        now_answer_2 = $(this).val();
        $("#poll_answer_2").val(now_answer_2);
        $(".answer_2_byte").html(acnt);
      }else if(seq == 3) {
        now_answer_3 = $(this).val();
        $("#poll_answer_3").val(now_answer_3);
        $(".answer_3_byte").html(acnt);
      }
    }
  });

  // 상품만족도 리스트 가져오기
  function load_poll_list_main(seq)
  {
    var cate_seq = 0;
    if(seq) {
      cate_seq = seq;
    }
    
    var data = {"cate_seq" : cate_seq}
    $.ajax({
      url : '/configs/get_poll_list_main',
      type: "POST",
      dataType: 'json',
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){        
          // 테이블에 추가하기
          var chk = "";
          var html = "";
          $.each(json.list, function(idx, val){
            
            if(cate_seq == val.cate_seq) chk="checked='checked'";
            else chk ="";

            html +="<tr class='tr_main_poll'>";
            html +="<td scope='row' class='text-center'>";
            html +="<label class='form-check form-check-inline'>";
            html +="<input class='form-check-input chk_goods_poll_seq' type='checkbox' value='"+val.seq+"' "+chk+">";
            html +="<span class='form-check-label'>";
            html +="</span>";
            html +="</label> ";
            html +="</td>";
            html +="<td class='text-center'>"+val.question+"</td>";
            html +="<td class='text-center'>"+val.answer_1+"</td>";
            html +="<td class='text-center'>"+val.answer_2+"</td>";
            html +="<td class='text-center'>"+val.answer_3+"</td>";
            html +="</tr>";
          });
          $("tbody.poll_list_main").empty().html(html);
          $(".div_in_table_goods_poll_main").scrollTop(0);
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  }

  // 상품만족도 리스트 가져오기
  function load_poll_list()
  {

    $.ajax({
      url : '/configs/get_poll_list',
      type: "POST",
      dataType: 'json',
      async: false,
      data: '',
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          // 테이블에 추가하기
          var html = "";
          $.each(json.list, function(idx, val){

            html +="<tr class='tr_poll'>";
            html +="<td scope='row' class='text-center'>";
            html +="<label class='form-check form-check-inline'>";
            html +="<input class='form-check-input chk_goods_poll_seq' type='checkbox' value='"+val.seq+"'>";
            html +="<span class='form-check-label'>";
            html +="</span>";
            html +="</label> ";
            html +="</td>";
            html +="<td class='text-center'><input type='text' class='form-control goods_question' value='"+val.question+"' readonly></td>";
            html +="<td class='text-center'><input type='text' class='form-control goods_answer_1' value='"+val.answer_1+"' readonly></td>";
            html +="<td class='text-center'><input type='text' class='form-control goods_answer_2' value='"+val.answer_2+"' readonly></td>";
            html +="<td class='text-center'><input type='text' class='form-control goods_answer_3' value='"+val.answer_3+"' readonly></td>";
            html +="<td class='table-action text-center'>";
            html +="<input type='hidden' class='goods_poll_seq' value='"+val.seq+"'> ";
            html +="<button type='button' class='btn btn-danger btn_delete_poll' data-seq='"+val.seq+"'>삭제</button>";
            html +="</td>";
            html +="</tr>";
          });
          $("tbody.poll_list_detail").empty().html(html);
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  }

  // 상품만족도 항목 수정
  $(".modifyGoodsPollBtn").click(function () {
    $(".goods_question").removeAttr("readonly");
    $(".goods_answer_1").removeAttr("readonly");
    $(".goods_answer_2").removeAttr("readonly");
    $(".goods_answer_3").removeAttr("readonly");
    $(".modifyGoodsPollBtn").addClass("d-none");
    $(".saveGoodsPollBtn").removeClass("d-none");
  });

  // 상품만족도 저장하기
  $(".saveGoodsPollBtn").click(function () {
    $(".saveGoodsPollBtn").addClass("d-none");
    $(".modifyGoodsPollBtn").removeClass("d-none");
    $(".goods_question").attr('readonly', true);
    $(".goods_answer_1").attr('readonly', true);
    $(".goods_answer_2").attr('readonly', true);
    $(".goods_answer_3").attr('readonly', true);

    let goods_poll_seq = [];
    let goods_question = [];
    let goods_answer_1 = [];
    let goods_answer_2 = [];
    let goods_answer_3 = [];
    
    $('#table_goods_pool .tr_poll').each(function (index, item) {
      goods_poll_seq.push($(this).find(".goods_poll_seq").val());
      goods_question.push($(this).find(".goods_question").val());
      goods_answer_1.push($(this).find(".goods_answer_1").val());
      goods_answer_2.push($(this).find(".goods_answer_2").val());
      goods_answer_3.push($(this).find(".goods_answer_3").val());
    });
      
    let arrData = {
      "seq_list" : goods_poll_seq,
      "question" : goods_question,
      "answer_1" : goods_answer_1,
      "answer_2" : goods_answer_2,
      "answer_3" : goods_answer_3,
    };

    $.ajax({
      url: "/configs/update_goods_poll",
      dataType: 'json',
      type: "POST",
      async: true,
      data: arrData,
      beforeSend: function() {},
      success: function(json) {
        if(json.result=="OK"){
          alert("저장 되었습니다.");
          load_poll_list();
        }else{
          alert(json.msg);
        }
      },
      complete: function() {},
      statusCode: {404: function() {alert("page not found");}}
    });
  });


  // 만족도 항목 삭제
  $("tbody.poll_list_detail").off("click").on("click", "button.btn_delete_poll", function(e){
      e.preventDefault();
      if(!confirm("만족도 항목을 삭제하시겠습니까?")) return;
          var seq = $(this).data("seq");
          var data = {"seq" : seq}
          $.ajax({
            url: "/configs/delete_goods_poll",
            dataType: 'json',
            type: "POST",
            async: false,
            data: data,
            beforeSend: function() {$('body').css('opacity', '0.5');},
            success: function(json, textStatus, jqXHR) {
              if(json.result=="OK"){
                load_poll_list();
                return;
              }else{
                alert(json.msg);
                return;
              }
            },
            complete: function() {$('body').css('opacity', '1.0');},
            statusCode: {404: function() {alert("page not found");}}
          });    
  });

  // 상품만족도 항목추가 모달 띄우기
  $(".btn_add_poll").click(function(){
    $("#poll_question").val('');
    $("#poll_answer_1").val('');
    $("#poll_answer_2").val('');
    $("#poll_answer_3").val('');

    $(".question_byte").html('');
    $(".answer_1_byte").html('');
    $(".answer_2_byte").html('');
    $(".answer_3_byte").html('');
  });

  // 상품만족도 항목 추가하기
  $(".btn_insert_poll").click(function(){
    var question = $.trim($("#poll_question").val());
    var answer_1 = $.trim($("#poll_answer_1").val());
    var answer_2 = $.trim($("#poll_answer_2").val());
    var answer_3 = $.trim($("#poll_answer_3").val());
    
    // if(!confirm("만족도 항목을 추가하시겠습니까?")) return;

    var data = {"question" : question, "answer_1" : answer_1, "answer_2" : answer_2, "answer_3" : answer_3}
    $.ajax({
      url: "/configs/add_poll",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          $('.modal').modal("hide"); //모달 닫기 
          load_poll_list();
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });

  });


  // 상품만족도 항목 관리하기 
  $(".btn_goods_poll_list").click(function(e){
    load_poll_list();
    $("div.tab-3_1").fadeOut();
    $("div.tab-3_2").removeClass("visually-hidden");
    $("div.tab-3_2").fadeIn();
  });

  // 상품만족도 복귀하기 
  $(".btn_goods_poll_back").click(function(e){
    $("div.tab-3_2").addClass("visually-hidden");
    $("div.tab-3_2").fadeOut();
    $("div.tab-3_1").fadeIn();
    load_poll_list_main();
  });


  var now_id = '0';
  var now_parents_id = '';

  // ---------------------------------------------------------------------------
  // jstree
  // ---------------------------------------------------------------------------
  $('#jstree').jstree({
      'core' : {
          "check_callback" : function (op, node, par, pos, more) {
              if (op === "move_node" && more.ref === undefined) {
                  return true;
              }
              return true;
          },
          data: {url: "/configs/get_category_tree",dataType: "json"}  
      },
      'plugins' : ["dnd","contextmenu"]

  }).on("changed.jstree", function (e, data) {
      if(data.selected.length) {
          now_id = data.instance.get_node(data.selected[0]).id;
          // 카테고리 선택시 오른쪽에 정보 출력하기
          var data = {"now_id" : now_id}
          $.ajax({
            url: "/configs/get_category_info",
            dataType: 'json',
            type: "POST",
            async: false,
            data: data,
            beforeSend: function() {$('body').css('opacity', '0.5');},
            success: function(json, textStatus, jqXHR) {
              if(json.result=="OK"){

                var cate = json.cate_info;
                console.log(cate);
                if(cate.parents_kor == null) cate.parents_kor = "root";
                
                if(!$.trim(cate.cate_url)) cate.cate_url = "/products/lists?cate_id="+cate.seq;
                $(".hidden_now_id").val(now_id); // 선택된 카테고리 ID
                $(".parents_kor").val(cate.parents_kor); // 현재분류
                $(".cate_url").val(cate.cate_url); // 분류 URL
                $(".cate_kor").val(cate.cate_kor); // 분류명 (한글)
                $(".cate_eng").val(cate.cate_eng); // 분류명 (영문)
                $("input:radio[name ='view_yn']:input[value='"+cate.view_yn+"']").prop("checked", true); // 표시형태
                $(".order_by").val(cate.order_by).prop("selected", true); // 상품진열 정렬 기준
                $("input:radio[name ='soldout_view']:input[value='"+cate.soldout_view+"']").prop("checked", true); // 품절 상품 진열
                $("input:radio[name ='soldout_except']:input[value='"+cate.soldout_view+"']").prop("checked", true); // 품절 제외 보기
                $(".list_type").val(cate.list_type).prop("selected", true); // 리스트 형식
                $("input:radio[name ='size_review']:input[value='"+cate.size_review+"']").prop("checked", true); // 사이즈 리뷰
                return;
              }else{
                alert(json.msg);
                return;
              }
            },
            complete: function() {$('body').css('opacity', '1.0');},
            statusCode: {404: function() {alert("page not found");}}
          });
      }
      // 전체노드 오픈
      $('#jstree').jstree('open_all');
  }).on("delete_node.jstree", function(e, data) {
      $('#jstree').jstree('open_all');
  }).on("rename_node.jstree", function(e, data) {
      $('#jstree').jstree('open_all');
  }).on("loaded.jstree", function (e, data) {
      $('#jstree').jstree('open_all');
  });

  // treview 
  // treview 
  // treview 
  $('#jstree2').jstree({
    'core' : {
        "check_callback" : function (op, node, par, pos, more) {
            if (op === "move_node" && more.ref === undefined) {
                return true;
            }
            return true;
        },
        data: {url: "/configs/get_category_tree_poll",dataType: "json"}  
    },
    'plugins' : ["dnd","contextmenu"]

  }).on("changed.jstree", function (e, data) {
    if(data.selected.length) {
        now_id = data.instance.get_node(data.selected[0]).id;
        console.log("선택되었습니다.", data.instance.get_node(data.selected[0]).id);
        $(".selected_category_name").html(data.instance.get_node(data.selected[0]).text);
        $(".selected_category_id").val(now_id);
        load_poll_list_main(now_id);
    // 전체노드 오픈
    $('#jstree2').jstree('open_all');
    }
  }).on("delete_node.jstree2", function(e, data) {
  }).on("rename_node.jstree2", function(e, data) {
  }).on("loaded.jstree", function (e, data) {
    $('#jstree2').jstree('open_all');
  });


// treview 
// treview 
// treview 
$('#jstree3').jstree({
  'core' : {
      "check_callback" : function (op, node, par, pos, more) {
          if (op === "move_node" && more.ref === undefined) {
              return true;
          }
          return true;
      },
      data: {url: "/configs/get_category_tree_survey",dataType: "json"}  
  },
  'plugins' : ["dnd","contextmenu"]

}).on("changed.jstree", function (e, data) {
  if(data.selected.length) {
      now_id = data.instance.get_node(data.selected[0]).id;
      $(".selected_category_name").html(data.instance.get_node(data.selected[0]).text);
      $(".selected_category_id").val(now_id);
      
      // 저장된 이미지와 실측정보 가져오기
      load_survey_detail(now_id);

  // 전체노드 오픈
  $('#jstree3').jstree('open_all');
  }
}).on("delete_node.jstree3", function(e, data) {
}).on("rename_node.jstree3", function(e, data) {
}).on("loaded.jstree", function (e, data) {
  $('#jstree3').jstree('open_all');
});









  // 대분류 추가 - 엔터키 입력시
  $("#cate_kor").keydown(function(key) {
      if (key.keyCode == 13) {
          $(".btn_add_cate").click();        
      }
  });


  // 대분류 추가 - 클릭시
  $(".btn_add_cate").click(function(){
      var cate_kor = $("#cate_kor").val();
      if(!$.trim(cate_kor)){
          alert("대분류를 입력하여 주세요.");
          return;
      }
      var data = {"cate_kor" : cate_kor}
      $.ajax({
        url: "/configs/add_main_category",
        dataType: 'json',
        type: "POST",
        async: false,
        data: data,
        beforeSend: function() {$('body').css('opacity', '0.5');},
        success: function(json, textStatus, jqXHR) {
          if(json.result=="OK"){
            //alert(json.msg);
            window.location.reload();
            return;
          }else{
            alert(json.msg);
            return;
          }
        },
        complete: function() {$('body').css('opacity', '1.0');},
        statusCode: {404: function() {alert("page not found");}}
      });
  });

  // 분류설정 저장하기 
  $(".btn_update_cate").click(function(){
    var params = $(".form_cate_info").serialize();
    
    if(!now_id) {
      alert("먼저 카테고리를 선택해 주세요.");
      return;
    }
    // if(!confirm("저장하시겠습니까?")) return false;

    $.ajax({
      url: "/configs/update_category",
      dataType: 'json',
      type: "POST",
      async: false,
      data:params,
      contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          alert(json.msg);
          $('#jstree').jstree(true).refresh();
          return;
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  });

  // 아이콘 추가 등록
  $(".btn_modal_add").click(function(){
    $("form.form_icon_upload")[0].reset();
    $(".view_pc_1").html(" ");
    $(".view_pc_2").html(" ");
    $(".view_mobile_1").html(" ");
    $('input:radio[name=icon_gubun]').eq(0).click();
    $(".now_icon_seq").val("");

  });

  // 아이콘의 종류를 선택시
  $("input:radio[name=icon_gubun]").click(function()
  {
      var gubun = $(this).val();
      $(".div_icon_gubun").hide();
      $(".div_icon_gubun_"+gubun).removeClass("visually-hidden");
      $(".div_icon_gubun_"+gubun).show();
  });

  // 아이콘 추가 
  $(".btn_upload_icon").off('click').on('click', function() {
    var form = $('.form_icon_upload')[0];
    var formData = new FormData(form);
    $.ajax({
        url : '/configs/insert_icon',
        type : 'POST',
        data : formData,
        contentType : false,
        processData : false,
        beforeSend: function() {$('body').css('opacity', '0.5');},
        success: function(resonse, textStatus, jqXHR) {
          json = JSON.parse(resonse);
          if(json.result=="OK"){
            $("button.btn-close").click();
            alert(json.msg);
            load_icon_list();
            return;
          }else{
            alert(json.msg);
            return;
          }
        },
        complete: function() {$('body').css('opacity', '1.0');},
        statusCode: {404: function() {alert("page not found");}}       
    });
  });

  // 아이콘 리스트 가져오기
  function load_icon_list(){
    $.ajax({
      url : '/configs/get_icon_list ',
      type: "POST",
      dataType: 'json',
      async: false,
      data: '',
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          //alert(json.msg);
          console.log(json);
          
          // 테이블에 추가하기
          var html = "";
          $.each(json.list, function(idx, val){
            var icon_name = "";
            var icon_memo = "";
            if(val.icon_gubun == "BEST") {
              icon_name = '베스트';
              icon_memo = '해당 카테고리 랭킹 <strong>'+ val.icon_condition+ '위</strong> 이내인 경우 자동노출';
            }else if(val.icon_gubun == "NEW") {
              icon_name = '신상품';
              icon_memo = '신규등록 후 <strong>'+ val.icon_condition+ '일</strong> 동안 자동 노출';
            }else if(val.icon_gubun == "SALE") {
              icon_name = '세일';
              icon_memo = '정상가보다 판매가가 낮은 경우 자동 노출';
            }else if(val.icon_gubun == "COUPON") {
              icon_name = '쿠폰';
              icon_memo = '쿠폰 적용 상품인 경우 자동 노출';
            }else if(val.icon_gubun == "FUNDING") {
              icon_name = '펀딩';
              icon_memo = '펀딩 싱품인 경우 자동 노출';
            }else if(val.icon_gubun == "TIMESALE") {
              icon_name = '타임세일';
              icon_memo = '타임세일 상품인 경우 자동 노출';
            }else if(val.icon_gubun == "LIVE") {
              icon_name = '라이브';
              icon_memo = '라이브 상품인 경우 자동 노출';
            }else if(val.icon_gubun == "EVENT") {
              icon_name = '이벤트';
              icon_memo = '이벤트 진행 상품인 경우 자동 노출';
            }

            html += "<tr>";
            html += "<td class='text-center'>";
            if(val.pc_1 != null) html += "<img src='"+val.pc_1+"' title='PC 30*30'> &nbsp;";
            html += "</td>";
            html += "<td class='text-center'>";
            if(val.pc_2 != null) html += "<img src='"+val.pc_2+"' title='PC 20*20'> &nbsp;";
            html += "</td>";
            html += "<td class='text-center'>";
            if(val.mobile_1 != null) html += "<img src='"+val.mobile_1+"'  title='MOBILE 20*20'> &nbsp;";
            html += "</td>";
            html += "<td>"+icon_name+"</td>";
            html += "<td>"+icon_memo+"</td>";
            html += "<td class='text-center'>"+val.use_yn+"</td>";
            html += "<td class='table-action text-center'>";
            html += "<a href=''><i class='align-middle fas fa-fw fa-pen icon_edit' data-seq='"+val.seq+"' data-bs-toggle='modal' data-bs-target='#modalIconForm'></i></a> &nbsp;&nbsp;";
            html += "<a href=''><i class='align-middle fas fa-fw fa-trash  icon_delete' data-seq='"+val.seq+"'></i></a>";
            html += "</td>";
            html += "</tr>";
          });
          $("tbody.icon_list").empty().html(html);
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  }

  // 아이콘 탭 클릭시 리스트 가져오기
  $("li a.tab-2").click(function(){
    load_icon_list();
  });


  // 아이콘 수정 모달폼
  $(document).on("click", "i.icon_edit", function(e){
    e.preventDefault();
    $("form.form_icon_upload")[0].reset();
    $(".view_pc_1").html(" ");
    $(".view_pc_2").html(" ");
    $(".view_mobile_1").html(" ");
    $('input:radio[name=icon_gubun]').eq(0).click();
    
    var seq = $(this).data("seq");
    var data = {"seq" : seq}
    $.ajax({
      url: "/configs/get_icon_info",
      dataType: 'json',
      type: "POST",
      async: false,
      data: data,
      beforeSend: function() {$('body').css('opacity', '0.5');},
      success: function(json, textStatus, jqXHR) {
        if(json.result=="OK"){
          var row = json.row;
          var html = "";

          $(".now_icon_seq").val(seq); // 선택된 아이콘의 seq
          if(row.pc_1) {
            html = "<img src='"+row.pc_1+"'>";
            $(".view_pc_1").html(html);
          }
          if(row.pc_2) {
            html = "<img src='"+row.pc_2+"'>";
            $(".view_pc_2").html(html);
          }
          if(row.mobile_1) {
            html = "<img src='"+row.mobile_1+"'>";
            $(".view_mobile_1").html(html);
          }
          $("input:radio[name ='icon_gubun']:input[value='"+row.icon_gubun+"']").prop("checked", true);  // 제목
          $("input:radio[name ='use_yn']:input[value='"+row.use_yn+"']").prop("checked", true);  // 사용 유무


          if(row.icon_gubun=="BEST") {
            $(".ranking").val(row.icon_condition);

          }else if(row.icon_gubun=="NEW") {
            $(".after_regist").val(row.icon_condition);
          }

          // 선택한 아이콘의 내용 활성화
          $('input:radio[name="icon_gubun"][value="'+row.icon_gubun+'"]').click();
          
        }else{
          alert(json.msg);
          return;
        }
      },
      complete: function() {$('body').css('opacity', '1.0');},
      statusCode: {404: function() {alert("page not found");}}
    });
  });  

  // 아이콘 삭제 
  $(document).on("click", "i.icon_delete", function(e){
    e.preventDefault();
    if(!confirm("아이콘을 삭제하시겠습니까?")) return;
        var seq = $(this).data("seq");
        var data = {"seq" : seq}
        $.ajax({
          url: "/configs/delete_icon",
          dataType: 'json',
          type: "POST",
          async: false,
          data: data,
          beforeSend: function() {$('body').css('opacity', '0.5');},
          success: function(json, textStatus, jqXHR) {
            if(json.result=="OK"){
              $("button.btn-close").click();
              load_icon_list();
              return;
            }else{
              alert(json.msg);
              return;
            }
          },
          complete: function() {$('body').css('opacity', '1.0');},
          statusCode: {404: function() {alert("page not found");}}
        });
  });
 
});