@include('includes/main-header', [ 'css' => [ 'dashboard' ] ])

<link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css?{{ filemtime('assets/css/dashboard.css') }}">

<div class="column-wrap">
	<div class="column-articles">
		<div class="articles-title">
			Articles
			<div><a href="http://octanesociety.dev/create-article"><i class="fa fa-pencil-square-o fa-2x fa-fw"></i>New article</a></div>
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
			<div class="article-titles">

			</div>
		</div>
	</div>
	<div class="column-news">
		<div class="news-title">
			News
			<div class="news-filters">
				<div class="btn-group btn-group-sm">
					<button class="btn">All</button>
					<button class="btn">Events</button>
					<button class="btn">Vendors</button>
					<button class="btn">Industry</button>
				</div>
			</div>
		</div>
	</div>

	<div class="column-friends">
		<div class="friends-title">
			Friends
			<div><a href="http://octanesociety.dev/add-friend"><i class="fa fa-plus fa-fw"></i>Add friend</div></a></div>
		</div>
	</div>
</div>
@include('includes/main-footer', [ 'js' => [ 'home' ] ])