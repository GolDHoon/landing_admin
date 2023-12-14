$(document).ready(function () {
	$('#startDate, #endDate').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		// startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
		// endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
		// calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
		// clearBtn : false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
		// datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
		// daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
		// daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
		// disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
		// immediateUpdates: false,	//사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false
		// multidate : false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false
		// multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01
		// templates : {
		// 	leftArrow: '&laquo;',
		// 	rightArrow: '&raquo;'
		// }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징
		// showWeekDays : true ,// 위에 요일 보여주는 옵션 기본값 : true
		// title: "테스트",	//캘린더 상단에 보여주는 타이틀
		todayHighlight : true ,	//오늘 날짜에 하이라이팅 기능 기본값 :false
		toggleActive : true,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
		weekStart : 0 ,//달력 시작 요일 선택하는 것 기본값은 0인 일요일
		// language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.

	});

	$('#dateRange').daterangepicker({
		opens: 'left', // Adjust the position of the calendar
		autoApply: true, // Apply the selected range when clicking a date
		locale: {
			format: 'YYYY-MM-DD', // Set the date format
		},
	});

	$('.sms_btn').on('click',()=>{

		let checkboxes = document.querySelectorAll('.target_checkbox');

		let checkedValues = [];

		checkboxes.forEach((checkbox)=>{
			if (checkbox.checked) {
				checkedValues.push(checkbox.value);
			}
		});

		if(checkedValues.length === 0){
			alert('대상을 선택해주세요.');
			return;
		}

		$('#sms_modal').modal("show");

		$('#sms_lists').val(checkedValues);

	});

	$('.send_sms_btn').on('click',()=>{

		let seq_lists = $("#sms_lists").val();
		let sms_message = $("#sms_message").val();
		let title = $("#select_template").val();

		if(sms_message === ''){
			alert('전송 메세지를 선택해주세요.');
			return;
		}

		let reqeust_data = {
			'seq_lists' : seq_lists,
			'sms_message' : sms_message,
			'title' : title,
		}

		$.ajax({
			url: '/common/send_sms',
			method: 'POST',
			data : reqeust_data,
			success: function(response) {
				alert('발송되었습니다.');
				location.reload();
			},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});

	});

	$('.send_alim_btn').on('click',()=>{

		let alim_lists = $("#alim_lists").val();
		let alim_message = $("#alim_message").val();
		let alim_idx = $("#target_alim_idx").val();

		if(alim_message === ''){
			alert('전송 메세지를 선택해주세요.');
			return;
		}

		let reqeust_data = {
			'alim_lists' : alim_lists,
			'alim_idx' : alim_idx,
		}

		$.ajax({
			url: '/common/send_alim',
			method: 'POST',
			data : reqeust_data,
			success: function(response) {
				alert('발송되었습니다.');
				location.reload();
			},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});

	});

	$('.alim_btn').on('click',()=>{

		let checkboxes = document.querySelectorAll('.target_checkbox');

		let checkedValues = [];

		checkboxes.forEach((checkbox)=>{
			if (checkbox.checked) {
				checkedValues.push(checkbox.value);
			}
		});

		if(checkedValues.length === 0){
			alert('대상을 선택해주세요.');
			return;
		}

		$('#alim_modal').modal("show");

		$('#alim_lists').val(checkedValues);

	});

	$('.excel_btn').on('click',()=>{
		let condition = document.querySelector('#condition').value;
		let search_value = document.querySelector('#search_value').value;
		let created_at = document.querySelector('#dateRange').value;

		const excel_form = document.excel_download;

		excel_form.condition.value = condition;
		excel_form.search_value.value = search_value;
		excel_form.created_at.value = created_at;
		excel_form.submit();

	});

	let memo = $('.memo');
	memo.on('blur', function () {
		let textareaValue = $(this).val();
		let idx = $(this).data('idx');

		let request_data= {
			'memo' : textareaValue,
			'idx' : idx,
		}

		$.ajax({
			url: '/main/update_memo',
			method: 'POST',
			data : request_data,
			success: function(response) {},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});

	});

	let select_elements = document.querySelectorAll('.status_select');
	select_elements.forEach(function(select_element) {
		$(select_element).on('change', function() {
			let selected_value = $(this).val();
			let select_id = $(this).data('sidx');

			let request_data= {
				'status' : selected_value,
				'idx' : select_id,
			}

			$.ajax({
				url: '/main/update_status',
				method: 'POST',
				data : request_data,
				success: function(response) {},
				error: function(xhr, status, error) {
					console.error('Ajax request failed:', status, error);
				}
			});
		});
	});

	$("#select_template").on('change',function(){
		let selectedOption = $('option:selected', this);
		let idx = selectedOption.data('tidx');

		let request_data= {
			'idx' : idx,
		}

		$.ajax({
			url: '/setting/get_message',
			method: 'POST',
			data : request_data,
			success: function(res) {
				let data = JSON.parse(res);
				if(data.code){
					$("#sms_message").val(data.message);
					$("#target_alim_idx").val(idx);
				}else{
					alert(data.message);
				}
			},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});

	});

	$("#select_alim_template").on('change',function(){
		let selectedOption = $('option:selected', this);
		let idx = selectedOption.data('tidx');

		let request_data= {
			'idx' : idx,
		}

		$.ajax({
			url: '/setting/get_alim_message',
			method: 'POST',
			data : request_data,
			success: function(res) {
				let data = JSON.parse(res);
				if(data.code){
					$("#alim_message").val(data.message);
					$("#target_alim_idx").val(idx);
				}else{
					alert(data.message);
				}
			},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});

	});



	$("#all_check").change(function () {
		$(".target_checkbox").prop("checked", $(this).prop("checked"));
	});

	$(".target_checkbox").change(function () {
		let allChecked = $(".target_checkbox:checked").length === $(".target_checkbox").length;
		$("#all_check").prop("checked", allChecked);
	});

	$(".delete_btn").on('click',function(){
		let idx = $(this).data('didx');
		let request_data= {
			'idx' : idx,
		}

		$.ajax({
			url: '/main/delete_consult_member',
			method: 'POST',
			data : request_data,
			success: function(response) {
				alert('삭제되었습니다.');
				location.reload();
			},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});
	});

});
