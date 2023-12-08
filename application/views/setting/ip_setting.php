<main class="content">

	<div class="fw-bold fs-3">차단 IP 관리</div>

	<hr class="color-darkblue">

	<form action="/setting/add_block_ip" method="get">
		<div>

			<div class="container mt-4">
				<div class="row">
					<div class="col-md-2 btn-group-vertical align-items-center">
						<label>차단 IP 추가</label>
					</div>
					<div class="col-md-4">
						<input type="text" class="form-control" id="ip_value" name="ip_value" value="">
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary w-25">추가</button>
					</div>
				</div>
			</div>
			<div class="container mt-4">
			</div>
		</div>
	</form>
	<hr class="color-darkblue">

	<table id="datatables-dashboard-projects" class="table table-striped my-0">
		<thead>
		<tr class="text-center">
			<th>NO</th>
			<th>IP</th>
			<th>추가일</th>
			<th>추가유저</th>
			<th>삭제</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		foreach ($results as $row){ ?>
			<tr class="text-center">
				<td><?=$i;?></td>
				<td><?=$row->ip;?></td>
				<td><?=$row->created_at;?></td>
				<td><?=$row->created_user;?></td>
				<td>
					<button class="btn btn-danger delete_ip" data-idx=<?=$row->idx;?>>삭제</button>
				</td>
			</tr>
		<?php  $i++; }?>
		</tbody>
	</table>



	<!-- Display pagination links -->
	<div class="pagination main_pagination float-end mt-3">
		<?php echo $links; ?>
	</div>
</main>
