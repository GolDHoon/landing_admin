$('.delete_ip').on('click', function() {
	let idx = $(this).data('idx');
	let reqeust_data = {
		'idx': idx,
	}

	$.ajax({
		url: '/setting/delete_ip',
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
