<!doctype html>
<html lang="en">
	<head>
		<title>OctaneSociety.com</title>
		<link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="/assets/lib/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/common.css?{{ filemtime('assets/css/common.css') }}">
		<link rel="stylesheet" type="text/css" href="/assets/css/main.css?{{ filemtime('assets/css/main.css') }}">
		<script type="text/javascript" src="/assets/lib/jquery/jquery.js?{{ filemtime('assets/lib/jquery/jquery.js') }}"></script>
		<script type="text/javascript" src="/assets/js/common.js?{{ filemtime('assets/js/common.js') }}"></script>

		@yield('header')
		
	</head>

	<div class="page-wrap">
		<div class="dashboard-link">
			<a href="http://octanesociety.dev/dashboard" class="image"></a>
		</div>
		<div class="profile-link">
			<a href="http://octanesociety.dev/profile"><i class="fa fa-user fa-2x"></i> {{ Auth::user()->first_name }} </a>
		</div>
		<div class="main-header-icons">
			<div>
				<a href="http://octanesociety.dev/garage"><i class="fa fa-car fa-fw"></i>Garage</a>
			</div>
			<div>
				<a href=""><i class="fa fa-envelope fa-fw"></i>Messages</a>
			</div>
			<div>
				<a href=""><i class="fa fa-exclamation-triangle"></i> Notifications</a>
			</div>
			<div>
				<a href="http://octanesociety.dev/admin/request-list"><i class="fa fa-child"></i> Requests </a>
			</div>
			<div class="logout-btn">
				<a href="#"><i class="fa fa-sign-out"></i> Sign-out </a>
			</div>
		</div>
			<!--<div class="input-group search-box">
				<span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
				<input type="text" class="form-control" id="search-email" placeholder=" cars, friends, articles..." tabindex="1">
			</div>-->
	</div>
    </div>
</div>