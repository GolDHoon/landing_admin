$('.key13').keypress(function (e) {
	e.preventDefault();
	if (e.which == 13) {
		$("#btn_login").click();
	}
});


$('#btn_login').on('click', (e)=> {

	e.preventDefault();

	if(!$.trim($("#userId").val()))
	{
		alert("ID를 입력해 주세요.");
		return false;
	}

	if(!$.trim($("#userPw").val()))
	{
		alert("패스워드를 입력하여 주세요.");
		return false;
	}

	let userId = $.trim($("#userId").val());
	let userPw = $.trim($("#userPw").val());

	let data = {
		"userId" : userId,
		"userPw" : userPw
	};

	$.ajax({
		url: "/login/check_login",
		dataType: 'json',
		type: "POST",
		async: true,
		data: data,
		success: function(json, textStatus, jqXHR) {

			if(json.code === 1){
				location.href="/main";
			}else if(json.code === 0){
				return alert(json.msg);
			}else{
				return alert(json.msg);
			}

		},
		complete: function() {
			// $('body').css('opacity', '1.0');
		},
		statusCode: {
			404: function() {
				alert("LOGIN CHECK ERROR");
			}
		}
	});

});
