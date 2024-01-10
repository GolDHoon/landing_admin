<link rel="stylesheet" href="/assets/css/content.css">
<main class="content">

	<div class="fw-bold fs-3">상담 신청 리스트</div>

	<hr class="color-darkblue">

	<form action="/main" method="get">
		<div>
			<div class="container mt-4">
				<div class="row">
					<div class="col-md-2 btn-group-vertical align-items-center">
						<label for="startDate">상담일</label>
					</div>
					<div class="col-md-4">
						<input type="text" id="dateRange" name="created_at" class="form-control" value="<?php if(!empty($params['created_at'])) echo $params['created_at'];?>" readonly/>
					</div>
				</div>
			</div>

			<div class="container mt-4">
				<div class="row">
					<div class="col-md-2 btn-group-vertical align-items-center">
						<label for="startDate">오름 / 내림차순</label>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="orderBy_condition" id="orderBy_condition">
							<option value="">선택</option>
							<option value="orderBy_name" <?php if($params['orderBy_condition'] === 'orderBy_name') echo 'selected'?> >이름</option>
							<option value="orderBy_phone" <?php if($params['orderBy_condition'] === 'orderBy_phone') echo 'selected'?> >전화번호</option>
<!--							<option value="orderBy_landing_code" --><?php //if($params['orderBy_condition'] === 'orderBy_landing_code') echo 'selected'?><!-- >랜딩코드</option>-->
							<option value="orderBy_status" <?php if($params['orderBy_condition'] === 'orderBy_status') echo 'selected'?> >상담상태</option>
							<option value="orderBy_created_at" <?php if($params['orderBy_condition'] === 'orderBy_created_at') echo 'selected'?> >날짜</option>
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="orderBy_value" id="orderBy_value">
							<option value="">선택</option>
							<option value="ASC" <?php if($params['orderBy_value'] === 'ASC') echo 'selected'?> >오름차순</option>
							<option value="DESC" <?php if($params['orderBy_value'] === 'DESC') echo 'selected'?> >내림차순</option>
						</select>
					</div>
				</div>
			</div>

			<div class="container mt-4">
				<div class="row">
					<div class="col-md-2 btn-group-vertical align-items-center">
						<label for="startDate">검색조건</label>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="condition" id="condition">
							<option value="">선택</option>
							<option value="name" <?php if($params['condition'] === 'name') echo 'selected'?> >이름</option>
							<option value="phone" <?php if($params['condition'] === 'phone') echo 'selected'?> >전화번호</option>
<!--							<option value="landing_code" --><?php //if($params['condition'] === 'landing_code') echo 'selected'?><!-- >랜딩코드</option>-->
							<option value="status" <?php if($params['condition'] === 'status') echo 'selected'?> >상태</option>
						</select>
					</div>
					<div class="col-md-4">
						<input type="text" class="form-control" id="search_value" name="search_value" value="<?php if($params['search_value']) echo $params['search_value'];?>">
					</div>
					<div class="col-md-4">
						<div>
							<button class="btn btn-primary w-25">검색</button>
						</div>
					</div>

				</div>
			</div>
			<div class="container mt-4">

			</div>
		</div>
	</form>
	<hr class="color-darkblue">

	<div class="btn_section">
		<div class="row">
<!--			<div class="col-md-6">-->
<!--			</div>-->
<!--			<div class="col-md-2">-->
<!--			</div>-->
			<div class="col-md-12">

				<? if($_SESSION['user_sms_sender'] != "" && $_SESSION['user_sms_api_key'] != "" && $_SESSION['user_sms_id'] != ""){ ?>
					<button type="button" class="btn btn-secondary sms_btn">문자발송</button>
				<? } ?>
				<div class="modal fade" id="sms_modal" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">문자 메세지</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body m-3">
								<div class="mb-3">
									<input type="hidden" id="target_sms_idx">
									<select class="form-select" id="select_template">
										<option selected>템플릿 선택</option>
										<? foreach ($templates as $v){ ?>
											<option data-tidx="<?=$v['idx']?>" data-title="<?=$v['title']?>"><?=$v['title']?></option>
										<? } ?>
									</select>
								</div>
								<div>
									<input type="hidden" id="sms_lists" value="">
									<textarea class="form-control" name="sms_message" id="sms_message" cols="100" rows="5" readonly></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-bs-dismiss="modal">취소</button>
								<button type="button" class="btn btn-primary send_sms_btn">전송하기</button>
							</div>
						</div>
					</div>
				</div>
				<? if($_SESSION['user_sms_sender'] != "" && $_SESSION['user_sms_api_key'] != ""
					&& $_SESSION['user_sms_id'] != "" && $_SESSION['user_alim_id'] != "" && $_SESSION['user_alim_sender_key']){ ?>
					<button type="button" class="btn btn-warning alim_btn">알림톡 발송</button>
				<? } ?>
				<div class="modal fade" id="alim_modal" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">문자 메세지</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body m-3">
								<div class="mb-3">
									<input type="hidden" id="target_alim_idx">
									<select class="form-select" id="select_alim_template">
										<option selected>템플릿 선택</option>
										<? foreach ($alim_templates as $v){ ?>
											<option data-tidx="<?=$v['idx']?>" data-title="<?=$v['title']?>"><?=$v['title']?></option>
										<? } ?>
									</select>
								</div>
								<div>
									<input type="hidden" id="alim_lists" value="">
									<textarea class="form-control" name="alim_message" id="alim_message" cols="100" rows="5" readonly></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-bs-dismiss="modal">취소</button>
								<button type="button" class="btn btn-primary send_alim_btn">전송하기</button>
							</div>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-info excel_btn">엑셀 다운로드</button>
			</div>
		</div>
	</div>

	<form name="excel_download" action="/common/excel_download" method="post">
		<input type="hidden" name="condition">
		<input type="hidden" name="search_value">
		<input type="hidden" name="created_at">
	</form>

	<table id="datatables-dashboard-projects" class="table table-striped my-0">
		<thead>
		<tr class="text-center">
			<th>
				<input class="form-check-input" type="checkbox" id="all_check">
			</th>
			<th>NO</th>
			<th>이름</th>
			<th>전화번호</th>
			<th>상담상태</th>
			<th>SOURCE</th>
			<th>MEDIUM</th>
			<th>CAMPAIGN</th>
			<th>TERM</th>
			<th>CONTENT</th>
			<? if(in_array($_SESSION['user'],array('csrental','ethan'))) { ?>
				<th>지역</th>
			<? } ?>
			<? if(in_array($_SESSION['user'],array('koreadental','ethan'))) { ?>
				<th>희망지역</th>
				<th>희망개수</th>
			<? } ?>
			<th>상담신청일</th>
			<th>메모</th>
			<th>삭제</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		foreach ($results as $row){ ?>
			<tr class="text-center">
				<td width="5">
					<input class="form-check-input target_checkbox" type="checkbox" value="<?=$row->idx;?>">
				</td>
				<td><?=$i;?></td>
				<td><?=$row->name;?></td>
				<td><?=$row->phone;?></td>
				<td>
					<select class="form-control status_select" data-sidx="<?=$row->idx;?>">
						<option value="0" <? if($row->status == '0') echo 'selected'; ?>>상담요청</option>
						<option value="1" <? if($row->status == '1') echo 'selected'; ?>>상담중</option>
						<option value="2" <? if($row->status == '2') echo 'selected'; ?>>상담완료</option>
						<option value="3" <? if($row->status == '3') echo 'selected'; ?>>상담보류</option>
					</select>
				</td>
				<td><?=$row->utm_source;?></td>
				<td><?=$row->utm_medium;?></td>
				<td><?=urldecode($row->utm_campaign);?></td>
				<td><?=$row->utm_term;?></td>
				<td><?=$row->utm_content;?></td>
				<? if(in_array($_SESSION['user'],array('csrental','ethan'))) { ?>
					<td><?=$row->region;?></td>
				<? } ?>
				<? if(in_array($_SESSION['user'],array('koreadental','ethan'))) { ?>
					<td><?=$row->koreadental_region;?></td>
					<td><?=$row->koreadental_cnt;?></td>
				<? } ?>
				<td><?=$row->created_at;?></td>
				<td>
					<p>
						<textarea name="" id="" class="memo form-control" data-idx="<?=$row->idx;?>" cols="20" rows="3"><?=$row->memo;?></textarea>
					</p>
				</td>
				<td>
					<button class="btn btn-danger delete_btn" data-didx="<?=$row->idx;?>">삭제</button>
				</td>
			</tr>
			<?php  $i++; }?>
		</tbody>
	</table>

	<!-- Display pagination links -->
	<div class="pagination main_pagination">
		<?php echo $links; ?>
	</div>
</main>
