@include('includes/outside-header')

<div class="account-body">

<h3 class="account-body-title">Forgot Password</h3>
<h5 class="account-body-subtitle">We'll email you a link to reset your password.</h5>

<form class="form account-form" id="login-form">
	
	<div class="form-group">
		<label for="login-email" class="placeholder-hidden">Email Address</label>
		<input type="text" class="form-control" id="login-email" placeholder="Email Address" tabindex="1" autofocus>
	</div>
	
	<div class="form-group">
		<button type="submit" class="btn btn-secondary btn-block btn-lg" tabindex="2"> Reset Password &nbsp; <i class="fa fa-refresh"></i> </button>
	</div>

	<div class="form-group">
		<a href="/login"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Login</a>
	</div>
	
</form>

</div>

<div class="account-footer">
<p> Don't have an account? &nbsp; <a href="/signup" class="">Sign up now!</a> </p>
</div>

@include('includes/outside-footer', [ 'js' => [ 'login/forgot-password' ] ])