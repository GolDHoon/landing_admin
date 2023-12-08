$('#add_template').on('click',function(){
	let title = $('#title').val();
	let message = $('#message').val();

	if(title === '' || message === ''){
		alert('제목과 내용을 입력해주세요.');
		return;
	}

	let request_data = {
		'title': title,
		'message': message,
	}

	$.ajax({
		url: '/setting/add_template',
		method: 'POST',
		data: request_data,
		dataType: 'json',
		success: function (res) {
			console.log(res.code);
			console.log(res.message);
			if(res.code){
				alert(res.message);
				location.reload();
			}else{
				alert(res.message);
			}
		},
		error: function (xhr, status, error) {
			console.error('Ajax request failed:', status, error);
		}
	});
});

$('.delete_template').on('click', function() {
	let idx = $(this).data('idx');
	let reqeust_data = {
		'idx': idx,
	}

	$.ajax({
		url: '/setting/delete_template',
		method: 'POST',
		data: reqeust_data,
		dataType: 'json',
		success: function (res) {
			if(res.code === 1){
				location.reload();
			}else{
				alert('DELETE ERROR.');
			}
		},
		error: function (xhr, status, error) {
			console.error('Ajax request failed:', status, error);
		}
	});
});

$(".update_template").on('click',function(){
	$("#update_modal").modal("show");
	$("#update_title").val($(this).data("title"));
	$("#update_message").val($(this).data("message"));
	$('#update_idx').val($(this).data("idx"));
});

$("#update_template").on('click',function(){
	let title = $('#update_title').val();
	let message = $('#update_message').val();
	let idx = $('#update_idx').val();

	if(title === '' || message === ''){
		alert('제목과 내용을 입력해주세요.');
		return;
	}

	let request_data = {
		'title': title,
		'message': message,
		'idx' : idx,
	}

	$.ajax({
		url: '/setting/update_template',
		method: 'POST',
		data: request_data,
		dataType: 'json',
		success: function (res) {
			console.log(res.code);
			console.log(res.message);
			if(res.code){
				alert(res.message);
				location.reload();
			}else{
				alert(res.message);
			}
		},
		error: function (xhr, status, error) {
			console.error('Ajax request failed:', status, error);
		}
	});
});
