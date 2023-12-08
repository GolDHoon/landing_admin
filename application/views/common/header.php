<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title><?=DRIVEN_NAME;?></title>

	<link rel="stylesheet" href="/assets/css/header.css">

	<!-- Bootstrap CSS CDN -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

	<!-- Font Awesome JS -->
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

	<!-- Popper.JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
	<!-- Bootstrap JS -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
	<script defer src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/js/bootstrap-datetimepicker.min.js"></script>
	<script defer src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

</head>
<body>

<div class="wrapper">
	<!-- Sidebar  -->
	<nav id="sidebar">
		<div class="sidebar-header">
			<h3><?php echo DRIVEN_ADMIN; ?></h3>
		</div>

		<ul class="list-unstyled">
			<li class="active">
				<a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">환경설정</a>
				<ul class="collapse list-unstyled" id="homeSubmenu">
					<li>
						<a href="javascript:void(0);">Home 1</a>
					</li>
					<li>
						<a href="javascript:void(0);">Home 2</a>
					</li>
					<li>
						<a href="javascript:void(0);">Home 31</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);">About</a>
			</li>
			<li>
				<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
				<ul class="collapse list-unstyled" id="pageSubmenu">
					<li>
						<a href="javascript:void(0);">Page 1</a>
					</li>
					<li>
						<a href="javascript:void(0);">Page 2</a>
					</li>
					<li>
						<a href="javascript:void(0);">Page 3</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);">Portfolio</a>
			</li>
			<li>
				<a href="javascript:void(0);">Contact</a>
			</li>
		</ul>
		<ul class="list-unstyled CTAs">
			<p>Test</p>
		</ul>
	</nav>

	<!-- Page Content  -->
	<div id="content">

		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">

				<button type="button" id="sidebarCollapse" class="btn btn-info">
					<i class="fas fa-align-left"></i>
				</button>
				<button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fas fa-align-justify"></i>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="nav navbar-nav ml-auto">
						<li class="nav-item active">
							<a class="nav-link" href="#">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Log out</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
