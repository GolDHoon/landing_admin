$('.delete_template').on('click', function() {
	let idx = $(this).data('idx');
	let code = $(this).data('code');
	let reqeust_data = {
		'idx': idx,
		'code': code,
	}

	$.ajax({
		url: '/setting/delete_alim_template',
		method: 'POST',
		data: reqeust_data,
		dataType: 'json',
		success: function (res) {
			// alert('삭제되었습니다.');
			// location.reload();
		},
		error: function (xhr, status, error) {
			console.error('Ajax request failed:', status, error);
		}
	});
});

$("#get_alim_template").on('click',function(){

	$.ajax({
		url: '/common/get_alim_template_list',
		method: 'POST',
		data : '',
		dataType: 'json',
		success: function (res) {
			alert('목록을 가져왔습니다.');
			location.reload();
		},
		error: function (xhr, status, error) {
			console.error('Ajax request failed:', status, error);
		}
	});
});

$(".show_template").on('click',function(){
	$("#show_template_modal").modal("show");
	$("#alim_title").val($(this).data("title"));
	$("#alim_message").val($(this).data("message"));
});

