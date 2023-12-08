jQuery(($)=> {
	$("#testTable").DataTable({
		// dom: 'Bfrtip',
		// buttons: [
		// 	{
		// 		extend: 'excel'
		// 		,text: '엑셀출력'
		// 		,filename: '엑셀파일명'
		// 		,title: '엑셀파일 안에 쓰일 제목'
		// 	},
		// 	{
		// 		extend: 'copy'
		// 		,text: '클립보드 복사'
		// 		,title: '클립보드 복사 내용'
		// 	},
		// 	{
		// 		extend: 'csv'
		// 		,text: 'csv출력'
		// 		,filename: 'utf-8이라서 ms엑셀로 바로 열면 글자 깨짐'
		// 	},
		// ],
		// buttons: [
		// 	'copy', 'excel', 'pdf', 'print'
		// ],
		// 표시 건수기능 숨기기
		lengthChange: true,
		// 검색 기능 숨기기
		searching: true,
		// 정렬 기능 숨기기
		ordering: true,
		// 정보 표시 숨기기
		info: true,
		// 페이징 기능 숨기기
		paging: true,
		// order:[],
		order:[ [ 0, "asc" ], [ 1, "desc"] ],
		// scrollX: true,
		// scrollY: 500,
		columnDefs: [
			// 2번째 항목 넓이를 200px로 설정
			{ targets: 0, width: 50, },
			// { targets: 1, visible:false }
		],
		// 기본 표시 건수를 50건으로 설정
		displayLength: 20,
		// 표시 건수를 10건 단위로 설정
		lengthMenu: [ 10 , 20 , 30 , 50 ],
		// 현재 상태를 보존
		stateSave: true,
		// responsive: true,
		"language": {
			"decimal":        "",
			"emptyTable":     "데이터가 없습니다.",
			"info":           "_START_ ~ _END_ ROWS ▼ TOTAL - _TOTAL_",
			"infoEmpty":      "0 개 항목 중 0 ~ 0 개 표시",
			"infoFiltered":   "(_MAX_ 총 항목에서 필터링 됨)",
			"infoPostFix":    "",
			"thousands":      ",",
			"lengthMenu":     "ROWS _MENU_",
			"loadingRecords": "로드 중 ...",
			"processing":     "처리 중 ...",
			"search":         "검색",
			"zeroRecords":    "일치하는 데이터가 없습니다.",
			"paginate": {
				"first":      "처음",
				"last":       "마지막",
				"next":       "다음",
				"previous":   "이전"
			},
			"aria": {
				"sortAscending":  ": 오름차순으로 정렬",
				"sortDescending": ": 내림차순으로 정렬"
			}
		}

	});
});

function deleteRow(key){

	console.log(`delete key : ${key}`);

}

$("#allCheck").change(()=>{
	if($("#allCheck").is(":checked")){
		$("input[name=checkOrNot]").prop("checked", true);
	}else{
		$("input[name=checkOrNot]").prop("checked", false);
	}
});
