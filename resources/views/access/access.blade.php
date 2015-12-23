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
			<div class="buttons-container">
				<div class="enter-btn ghost-button">
					<a>Enter</a>
				</div>
				<div class="auto-login">
					<div class="facebook-btn">
						<fb:login-button scope="public_profile,email,user_events,rsvp_event" size="large" data-max-rows="1" onlogin="checkLoginState();">Login with Facebook</fb:login-button>
					</div>
				</div>
			</div>
			<div class="pending-review">hi</div>
		</div>

		<div id="access-popup" class="overlay">
			<div class="login-wrap">
				<div class="manual-login">
					<form class="login-form">
						<div class="form-group">
							<input type="text" required="true" class="form-control email" placeholder="E-mail">
						</div>
						<div class="form-group">
							<input type="password" required="true" class="form-control password" placeholder="Password">
						</div>
						<div class="form-group submit-btn">
							<input type="submit" value="Login">
						</div>
					</form>
				</div>

				<div class="forgot-password">
					<a href="#" >Forgot password</a>
				</div>

				<div class="divider">
					<div class="divider-left"></div>
					<div class="or"> or </div>
					<div class="divider-right"></div>
				</div>

				<div class="join-btn">
					<div>
						<button class="btn">Join the society</button>
					</div>
				</div>

				
			</div>

			<div class="info-wrap">
				<form>
					<div class="form-group">
						<input type="text" required="true" class="form-control first-name" placeholder="first name">
					</div>
					<div class="form-group">
						<input type="text" required="true" class="form-control last-name" placeholder="last name">
					</div>
					<div class="form-group">
						<input type="text" required="true" class="form-control email" placeholder="E-mail">
					</div>
					<div class="form-group">
						<input type="password" required="true" class="form-control password" placeholder="Password">
					</div>
					<div class="form-group">
						<input type="submit" class="btn next-btn ghost-button" value="Next &gt;">
					</div>
				</form>
			</div>
			<div class="car-wrap">
				<h2> Select your car </h2>
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