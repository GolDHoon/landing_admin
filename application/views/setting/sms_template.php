<main class="content">

	<div class="fw-bold fs-3">SMS 템플릿 관리</div>

	<hr class="color-darkblue">

	<div>
		<button type="button" class="btn btn-primary w-25 float-end mb-3" data-bs-toggle="modal" data-bs-target="#template_modal" data-bs-whatever="@mdo">템플릿 등록</button>
		<div class="modal fade" id="template_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">SMS 템플릿 추가</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form>
							<div class="mb-3">
								<label for="recipient-name" class="col-form-label">SMS 제목</label>
								<input type="text" class="form-control" id="title" required>
							</div>
							<div class="mb-3">
								<label for="message-text" class="col-form-label">메세지 내용</label>
								<textarea class="form-control" name="" id="message" cols="20" rows="10" required></textarea>
							</div>

						</form>
						<div>
							<div class="alert alert-success d-flex align-items-center" role="alert">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
									<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
								</svg>
								<div>
									Example <br>
									안녕하세요. @{name} 님 <br>
									좋은 하루 보내세요.
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal">취소</button>
						<button type="button" id="add_template" class="btn btn-primary">추가하기</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<table id="datatables-dashboard-projects" class="table table-striped my-0">
		<thead>
		<tr class="text-center">
			<th>NO</th>
			<th>제목</th>
			<th>메세지</th>
			<th>생성자</th>
			<th>추가일시</th>
			<th>수정</th>
			<th>삭제</th>
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
					<button class="btn btn-warning update_template" data-idx='<?=$row->idx;?>' data-message='<?=$row->message;?>' data-title='<?=$row->title;?>'>수정</button>
				</td>
				<td>
					<button class="btn btn-danger delete_template" data-idx=<?=$row->idx;?>>삭제</button>
				</td>
			</tr>
			<?php  $i++; }?>
		</tbody>
	</table>

	<div>
		<div class="modal fade" id="update_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">SMS 템플릿 수정</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form>
							<div class="mb-3">
								<label for="recipient-name" class="col-form-label">SMS 제목</label>
								<input type="hidden" id="update_idx">
								<input type="text" class="form-control" id="update_title" required>
							</div>
							<div class="mb-3">
								<label for="message-text" class="col-form-label">메세지 내용</label>
								<textarea class="form-control" name="" id="update_message" cols="20" rows="10" required></textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal">취소</button>
						<button type="button" id="update_template" class="btn btn-primary">수정하기</button>
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
