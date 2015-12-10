@include('includes/outside-header')

<div class="account-body">

<h3 class="account-body-title">Reset Password</h3>
<h5 class="account-body-subtitle">Please choose a new password.</h5>

<form class="form account-form" id="login-form">

	<input type="hidden" name="v" value="{{{ Input::get('v') }}}">
	
	<div class="form-group">
		<label for="new-password" class="placeholder-hidden">New Password</label>
		<input type="password" class="form-control" id="new-password" placeholder="New Password" tabindex="1" autofocus>
	</div>

	<div class="form-group">
		<label for="confirm-password" class="placeholder-hidden">Confirm Password</label>
		<input type="password" class="form-control" id="confirm-password" placeholder="Confirm Password" tabindex="2">
	</div>
	
	<div class="form-group">
		<button type="submit" class="btn btn-secondary btn-block btn-lg" tabindex="3"> Reset Password &nbsp; <i class="fa fa-refresh"></i> </button>
	</div>

	<div class="form-group">
		<a href="/login"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Login</a>
	</div>
	
</form>

</div>

<div class="account-footer">
<p> Don't have an account? &nbsp; <a href="/signup" class="">Sign up now!</a> </p>
</div>

@include('includes/outside-footer', [ 'js' => [ 'login/reset-password' ] ])