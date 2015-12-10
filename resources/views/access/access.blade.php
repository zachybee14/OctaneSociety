<!doctype html>
<html lang="en">
	<head>
		<title>OctaneSociety.com</title>
		<link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/access.css?{{ filemtime('assets/css/access.css') }}">
		<link rel="stylesheet" href="/assets/lib/font-awesome/css/font-awesome.css">
	</head>
	<body>
		<div id="fb-root"></div>
		<div class="wrapper">
			<div class="logo"></div>​
			<div class="buttons-wrap">
				<div class="enter-btn">
					<button class="btn">Enter</button>
				</div>
				<div class="or">
					<h2> or </h2>
				</div>
				<div class="facebook-btn" style="position:fixed;">
					<fb:login-button scope="public_profile,email" size="large" data-max-rows="1" onlogin="checkLoginState();">Login with Facebook</fb:login-button>
				</div>
			</div>
			<div class="pending-review">hi</div>
		</div>

		<div id="access-popup" class="overlay">
			<div class="login-wrap">
				<h2>Login</h2>
				<form class="login-form">
					<div class="form-group">
						<input type="text" required="true" class="form-control email" placeholder="E-mail">
					</div>
					<div class="form-group">
						<input type="password" required="true" class="form-control password" placeholder="Password">
					</div>
					<div class="form-group submit-btn">
						<input type="submit" class="btn btn-primary" value="Login">
					</div>
				</form>
				
				<a href="#" class="join-btn"> not a member? Request to join.</a>
				<a href="#" class="forgot-password-btn">Forgot password</a>
			</div>

			<div class="info-wrap">
				<form>
					<div class="form-group">
						<input type="text" class="form-control first-name" placeholder="first name">
					</div>
					<div class="form-group">
						<input type="text" class="form-control last-name" placeholder="last name">
					</div>
					<div class="form-group">
						<input type="text" required="true" class="form-control email" placeholder="E-mail">
					</div>
					<div class="form-group">
						<input type="password" required="true" class="form-control password" placeholder="Password">
					</div>
					<div class="form-group">
						<input type="submit" class="btn next-btn" value="Next &gt;">
					</div>
				</form>
			</div>
			<div class="car-wrap">
				<h2> Select a car </h2>
				<div>
					<select class="form-group make">
						 <option value="" disabled >Make</option>
					</select>
				</div>
				<div>
					<select class="form-group model">
						<option value="" disabled selected>Model</option>
					</select>
				</div>
				<div>
					<select class="form-group year">
						<option value="" disabled selected>Year</option>
					</select>
				</div>
				<div>
					<select class="form-group style">
						<option value="" disabled selected>Style</option>
					</select>
				</div>
				<div class="request-btn">
					<button class="btn"> Request </button>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="/assets/lib/jquery/jquery.js"></script>
		<script type="text/javascript" src="/assets/lib/bootstrap/js/bootstrap.js"></script> 
		<script type="text/javascript" src="/assets/js/common.js"></script>
		<script type="text/javascript" src="/assets/js/access.js"></script>
		<div class="loading"> 
			<div><i class="fa fa-spinner fa-pulse fa-5x"></i></div>
		</div>
	</body>
</html>
</html>	