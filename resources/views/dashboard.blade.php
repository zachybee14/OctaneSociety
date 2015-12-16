@section('header')

<link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css?{{ filemtime('assets/css/dashboard.scss') }}">

@stop

@include('includes/main-header', [ 'css' => [ 'dashboard' ] ])

<div class="column-wrap">
	<div class="center">

		<div class="column-news">
			<div class="news-title">
				News
				<div class="news-filters">
					<div class="btn-group btn-group-sm">
						<button class="btn">All</button>
						<button class="btn">Friends</button>
						<button class="btn">Vendors</button>
						<button class="btn">Industry</button>
					</div>
				</div>
			</div>
		</div>

		<div class="column-events">
			<div class="events-titles">
				Events
				<div class="new-event-btn"><a href="#"><i class="fa fa-pencil-square-o fa-2x fa-fw"></i>Create event</a></div>
			</div>
		</div>

		<div class="column-articles">
			<div class="articles-title">
				Articles
				<div class="new-article-btn"><a href="#"><i class="fa fa-pencil-square-o fa-2x fa-fw"></i>Create article</a></div>
				<div class="form-group">
					<select type="text" class="form-control article-type" value="">
						<option selected disabled hidden value="">Select Type</option>
						<option value="" default="">All</option>
						<option value="how to" default="">How to</option>
						<option value="review">Review</option>
						<option value="review">Mods</option>
						<option value="help">Help</option>
						<option value="event">Event</option>
					</select>
				</div>
			</div>
			<div class="article-titles">

			</div>
		</div>

	</div>
</div>

@include('includes/main-footer', [ 'js' => [ 'dashboard' ] ])