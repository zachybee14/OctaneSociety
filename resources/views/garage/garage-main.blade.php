@include('includes/main-header', [ 'css' => [ 'garage-main' ] ])

<link rel="stylesheet" type="text/css" href="/assets/css/garage-main.css?{{ filemtime('assets/css/garage-main.css') }}">

	<div class="garage-main-wrap">
		<div class="car">
			<div class="title">
				<a href="http://octanesociety.dev/car/id">Vehicle title</a>
			</div>
			<div class="car-pic">
				CAR PIC
			</div>
		</div>
	</div>
	
@include('includes/main-footer', [ 'js' => [ 'garage-main' ] ])