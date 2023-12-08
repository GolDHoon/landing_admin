<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Modern, flexible and responsive Bootstrap 5 admin &amp; dashboard template">
	<meta name="author" content="Bootlab">

	<title><?php echo DRIVEN_NAME; ?></title>


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


	<!-- END SETTINGS -->
</head>
<!-- SET YOUR THEME -->

<body class="theme-blue">

<main class="main h-100 w-100">
	<div class="container h-100">
		<div class="row h-100">
			<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
				<div class="d-table-cell align-middle">

					<div class="text-center mt-4">
						<h1 class="h2">TEST</h1>
						<p class="lead"></p>
					</div>

					<div class="card">
						<div class="card-body">
							<div class="m-sm-4">
								<div class="text-center">
									상담신청
								</div>
								<div>
									<div class="mb-3">
										<input class="form-control form-control-lg" type="text" id="name" placeholder="성함"/>
									</div>
									<div class="mb-3">
										<input class="form-control form-control-lg" type="text" id="phone" placeholder="01012341234" />
									</div>
									<div class="text-center mt-3">
										<button type="button" id="btn_test" class="btn btn-lg btn-primary">신청하기</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<script>

	$(document).ready(function () {

		$("#btn_test").on('click',function(){
			let name = $('#name').val();
			let phone = $('#phone').val();
			let mode = 1;

			let currentUrl = window.location.href;
			let params = {};
			let urlParts = currentUrl.split('?');
			if (urlParts.length > 1) {
				let queryParams = urlParts[1].split('&');
				for (let i = 0; i < queryParams.length; i++) {
					let param = queryParams[i].split('=');
					params[param[0]] = param[1];
				}
			}

			// 원하는 파라미터만
			let utm_source = params['utm_source'];
			let utm_medium = params['utm_medium'];
			let utm_campaign = params['utm_campaign'];
			let utm_term = params['utm_term'];

			let request_data = {
				'name' : name,
				'phone' : phone,
				'mode' : mode,
				'utm_source' : utm_source,
				'utm_medium' : utm_medium,
				'utm_campaign' : utm_campaign,
				'utm_term' : utm_term,
			}

			$.ajax({
				url: '/test/test_member',
				method: 'GET',
				data : request_data,
				success: function(res) {
					console.log("1");
				},
				error: function(xhr, status, error) {
					console.error('Ajax request failed:', status, error);
				}
			});

		});


	});


</script>

