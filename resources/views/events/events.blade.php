@section('header')

<link rel="stylesheet" type="text/css" href="/assets/css/events.css?{{ filemtime('assets/css/events.css') }}">

@stop

@include('includes/main-header', [ 'css' => [ 'events' ] ])

<div class="event-wrap">
	<div class="event-header">
		<div class="title">
			Wicked Big Meet
		</div>
		<div>
			<img src="/images/wicked-big-meet.jpg">
		</div>
		<div class="attendees">
			<div>
				Attendees: 1,300
			</div>
			<div>
				Vendors: 20
			</div>
			
		</div>
		<div class="counter">
			105d : 18hr : 30min : 20sec
		</div>
	</div>
	<div class="event-info">
		<div class="description">
			<div class="event-type">
				Type: import
			</div>
			<div class="event-makes">
				Primary Make('s): Subaru
			</div>
			<div class="text-description">
				<div class="title">	Description </div>
				<div class="body">
					The biggest Subaru meet in New England. adsfa;ldshfaljfhasdf
					adslfjadslfadslfghalsdfgja'sdkfjadlkjf
				</div>
			</div>
		</div>
		<div class="info-buttons">
			<div>
				<button class="btn btn-primary">Get to event</button>
			</div>
				
			<div>
				<button class="btn btn-primary">Event site map</button>
			</div>
			<div>
				<button class="btn btn-primary">Hotels</button>
			</div>
		</div>
	</div>
	<div class="ticket-btn">
		<div>
			<button class="btn btn-success">Buy tickets</button>
		</div>
	</div>
	<div class="comments-wrap">
		<div class="title"> Comments </div>
		<div class="box">
			<div class="comment">
				<span class="user-name"> 
					<a href="#">Zach Beland </a> 
				</span>
				<span> 
					Hey guys would it be okay to bring my dog into the event? 
				</span>
			</div>
		</div>
	</div>
</div>

@include('includes/main-footer', [ 'js' => [ 'events' ] ])