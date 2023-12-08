<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="ko">

<head>

	<?php include_once ("assets/meta/meta.php");?>
	<script src="/assets/bootstrap/js/app.js"></script>
	<script src="/assets/editor/ckeditor4/ckeditor/ckeditor.js"></script>
	<?php if(!empty($include_js)){?>
		<script defer src="<?php echo $include_js;?>"></script>
	<?php }?>
	<?php if(!empty($include_css)){?>
		<link rel="stylesheet" href="<?php echo $include_css;?>">
	<?php }?>

	<!-- Bootstrap Datepicker CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
	<!-- Bootstrap Datepicker JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

	<!-- Bootstrap Date Range Picker CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1.0/daterangepicker.css">
	<!-- Bootstrap Date Range Picker JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1.0/daterangepicker.js"></script>
</head>
<body>
<!--<div class="splash active">-->
<!--	<div class="splash-icon"></div>-->
<!--</div>-->

<div class="wrapper">
	<nav id="sidebar" class="sidebar">
		<a class="sidebar-brand main_a_tag" href="/main">
			<svg>
				<use xlink:href="#ion-ios-pulse-strong"></use>
			</svg>
			<?=DRIVEN_NAME;?>
		</a>
		<div class="sidebar-content">
			<div class="sidebar-user">
				<div class="fw-bold">
					<?php
						if(!empty($_SESSION["user"])){
							echo $_SESSION["user"]." 님";
						}
					?>

						<div class="mt-3">
							<select class="form-select" id="select_domain">
								<? if(in_array($_SESSION['user'],DRIVEN_ADMIN_LIST)){ ?>
									<option data-lcode="all">선택</option>
								<? } ?>
								<? foreach ($_SESSION['arr_landing_list'] as $v){ ?>
									<option data-lcode="<?=$v['landing_code'];?>" <? if($v['landing_code'] == $_SESSION['target_landing_code']) echo 'selected'; ?> ><?=$v['domain'];?></option>
								<? } ?>
							</select>
						</div>


				</div>
			</div>

			<ul class="sidebar-nav">
				<li class="sidebar-header">
					Main
				</li>
				
				<li class="sidebar-item">
					<a class="sidebar-link" href="/main">
						<i class="align-middle me-2 fas fa-fw fa-home"></i> <span class="align-middle">대시보드</span>
					</a>
				</li>

<!--				<li class="sidebar-item">-->
<!--					<a data-bs-target="#view_menu" data-bs-toggle="collapse" class="sidebar-link collapsed">-->
<!--						<i class="align-middle me-2 fas fa-fw fa-window-restore"></i> <span class="align-middle">개발예정</span>-->
<!--					</a>-->
<!--					<ul id="view_menu" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">-->
<!--						<li class="sidebar-item"><a class="sidebar-link" href="">개발예정</a></li>-->
<!--						<li class="sidebar-item"><a class="sidebar-link" href="">개발예정</a></li>-->
<!--					</ul>-->
<!--				</li>-->

				<li class="sidebar-item">
					<a data-bs-target="#config_menu" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="align-middle me-2 fas fa-fw fa-cog"></i> <span class="align-middle">설정 관리</span>
					</a>
					<ul id="config_menu" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item"><a class="sidebar-link" href="/setting/ip_setting">IP 차단관리</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="/setting/sms_template">문자 템플릿 관리</a></li>
					</ul>
				</li>

			</ul>
		</div>
	</nav>
	<div class="main">
		<nav class="navbar navbar-expand navbar-theme">
			<a class="sidebar-toggle d-flex me-2">
				<i class="hamburger align-self-center"></i>
			</a>

<!--			<form class="d-none d-sm-inline-block">-->
<!--				<input class="form-control form-control-lite" type="text" placeholder="Search projects...">-->
<!--			</form>-->

			<div class="navbar-collapse collapse">
				<ul class="navbar-nav ms-auto">
<!--					<li class="nav-item dropdown active">-->
<!--						<a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown" data-bs-toggle="dropdown">-->
<!--							<i class="align-middle fas fa-envelope-open"></i>-->
<!--						</a>-->
<!--						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">-->
<!--							<div class="dropdown-menu-header">-->
<!--								<div class="position-relative">-->
<!--									4 New Messages-->
<!--								</div>-->
<!--							</div>-->
<!--							<div class="list-group">-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<img src="/assets/bootstrap/img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Michelle Bilodeau">-->
<!--										</div>-->
<!--										<div class="col-10 ps-2">-->
<!--											<div class="text-dark">Michelle Bilodeau</div>-->
<!--											<div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>-->
<!--											<div class="text-muted small mt-1">5m ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<img src="/assets/bootstrap/img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Kathie Burton">-->
<!--										</div>-->
<!--										<div class="col-10 ps-2">-->
<!--											<div class="text-dark">Kathie Burton</div>-->
<!--											<div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>-->
<!--											<div class="text-muted small mt-1">30m ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<img src="/assets/bootstrap/img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="Alexander Groves">-->
<!--										</div>-->
<!--										<div class="col-10 ps-2">-->
<!--											<div class="text-dark">Alexander Groves</div>-->
<!--											<div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>-->
<!--											<div class="text-muted small mt-1">2h ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<img src="/assets/bootstrap/img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Daisy Seger">-->
<!--										</div>-->
<!--										<div class="col-10 ps-2">-->
<!--											<div class="text-dark">Daisy Seger</div>-->
<!--											<div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>-->
<!--											<div class="text-muted small mt-1">5h ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="dropdown-menu-footer">-->
<!--								<a href="#" class="text-muted">Show all messages</a>-->
<!--							</div>-->
<!--						</div>-->
<!--					</li>-->
<!--					<li class="nav-item dropdown ms-lg-2">-->
<!--						<a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown" data-bs-toggle="dropdown">-->
<!--							<i class="align-middle fas fa-bell"></i>-->
<!--							<span class="indicator"></span>-->
<!--						</a>-->
<!--						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">-->
<!--							<div class="dropdown-menu-header">-->
<!--								4 New Notifications-->
<!--							</div>-->
<!--							<div class="list-group">-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<i class="ms-1 text-danger fas fa-fw fa-bell"></i>-->
<!--										</div>-->
<!--										<div class="col-10">-->
<!--											<div class="text-dark">Update completed</div>-->
<!--											<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>-->
<!--											<div class="text-muted small mt-1">2h ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<i class="ms-1 text-warning fas fa-fw fa-envelope-open"></i>-->
<!--										</div>-->
<!--										<div class="col-10">-->
<!--											<div class="text-dark">Lorem ipsum</div>-->
<!--											<div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>-->
<!--											<div class="text-muted small mt-1">6h ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<i class="ms-1 text-primary fas fa-fw fa-building"></i>-->
<!--										</div>-->
<!--										<div class="col-10">-->
<!--											<div class="text-dark">Login from 192.186.1.1</div>-->
<!--											<div class="text-muted small mt-1">8h ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--								<a href="#" class="list-group-item">-->
<!--									<div class="row g-0 align-items-center">-->
<!--										<div class="col-2">-->
<!--											<i class="ms-1 text-success fas fa-fw fa-bell-slash"></i>-->
<!--										</div>-->
<!--										<div class="col-10">-->
<!--											<div class="text-dark">New connection</div>-->
<!--											<div class="text-muted small mt-1">Anna accepted your request.</div>-->
<!--											<div class="text-muted small mt-1">12h ago</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="dropdown-menu-footer">-->
<!--								<a href="#" class="text-muted">Show all notifications</a>-->
<!--							</div>-->
<!--						</div>-->
<!--					</li>-->
					<li class="nav-item dropdown ms-lg-2">
						<a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown" data-bs-toggle="dropdown">
							<i class="align-middle fas fa-cog"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
<!--							<a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-user"></i> View Profile</a>-->
<!--							<a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-comments"></i> Contacts</a>-->
<!--							<a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-chart-pie"></i> Analytics</a>-->
<!--							<a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-cogs"></i> Settings</a>-->
<!--							<div class="dropdown-divider"></div>-->
							<a class="dropdown-item" href="/login/sign_out"><i class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i>로그아웃</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>



		<?php echo $content; ?>
		<?php echo $footer; ?>
	</div>
</div>



<svg width="0" height="0" style="position:absolute">
	<defs>
		<symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
			<path
				d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
			</path>
		</symbol>
	</defs>
</svg>
</body>
</html>
<script>
	$("#select_domain").on('change',function(){

		let landing_code = $("option:selected", this).data('lcode');
		console.log(landing_code);

		let request_data= {
			'landing_code' : landing_code,
		}

		$.ajax({
			url: '/main/change_session_value',
			method: 'POST',
			data : request_data,
			success: function(res) {
				location.reload();
			},
			error: function(xhr, status, error) {
				console.error('Ajax request failed:', status, error);
			}
		});

	});
</script>
