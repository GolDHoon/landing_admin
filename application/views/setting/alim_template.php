<main class="content">

	<div class="fw-bold fs-3">알림톡 템플릿</div>

	<hr class="color-darkblue">

	<div class="mb-3">
		<button type="button" id="get_alim_template" class="btn btn-warning">템플릿 가져오기</button>
	</div>

	<table id="datatables-dashboard-projects" class="table table-striped my-0">
		<thead>
		<tr class="text-center">
			<th>NO</th>
			<th>제목</th>
			<th>메세지</th>
			<th>생성자</th>
			<th>추가일시</th>
			<th>상세보기</th>
<!--			<th>삭제</th>-->
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		foreach ($results as $row){ ?>
			<tr class="text-center">
				<td><?=$i;?></td>
				<td><?=$row->title;?></td>
				<td>
					<div class="sms_message">
						<?=$row->message;?>
					</div>
				</td>
				<td><?=$row->created_user;?></td>
				<td><?=$row->created_at;?></td>
				<td>
					<button class="btn btn-primary show_template" data-title='<?=$row->title;?>' data-message='<?=$row->message;?>'>상세보기</button>
				</td>
<!--				<td>-->
<!--					<button class="btn btn-danger delete_template" data-idx='--><?php //=$row->idx;?><!--' data-code='--><?php //=$row->template_code;?><!--'>삭제</button>-->
<!--				</td>-->
			</tr>
			<?php  $i++; }?>
		</tbody>
	</table>

	<div>
		<div class="modal fade" id="show_template_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">알림톡 템플릿</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form>
							<div class="mb-3">
								<label for="recipient-name" class="col-form-label">알림톡 제목</label>
								<input type="text" class="form-control" id="alim_title" readonly>
							</div>
							<div class="mb-3">
								<label for="message-text" class="col-form-label">메세지 내용</label>
								<textarea class="form-control" name="" id="alim_message" cols="20" rows="10" readonly></textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-bs-dismiss="modal">닫기</button>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Display pagination links -->
	<div class="pagination main_pagination float-end mt-3">
		<?php echo $links; ?>
	</div>
</main>
