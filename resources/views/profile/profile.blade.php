@include('includes/main-header', [ 'css' => [ 'profile' ] ])

<link rel="stylesheet" type="text/css" href="/assets/css/profile.css?{{ filemtime('assets/css/profile.css') }}">

<div class="overview-wrap">
	<div class="profile-overview">
		<div class="profile-pic">
			Profile Picture
		</div>
		<div class="user-name">{{{ Auth::user()->first_name }}} {{{ Auth::user()->last_name }}}</div>
	</div>
	<div class="garage-overview">
		<div>
			<a href="http://octanesociety.dev/garage"> {{{Auth::user()->first_name}}}'s Garage</a>
		</div>
		<div class="garage-photos">

		</div>
	</div>
	<div class="favorite-sites">
		<div>
			Favorite Websites
		</div>

	</div>
</div>

<div class="bulletin-board">
	<div>
		Bulletin
	</div>
</div>



@include('includes/main-footer', [ 'js' => [ 'profile' ] ])