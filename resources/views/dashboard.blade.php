@section('header')

<link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css?{{ filemtime('assets/css/dashboard.scss') }}">
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrQSHUNIHEz2FbfCaAXb718DHfWJn5PtA"></script>
<script type="text/javascript" src="/assets/lib/vue/vue.js"></script>

@stop

@include('includes/main-header', [ 'css' => [ 'dashboard' ] ])

<div id="dashboard" class="column-wrap">
	<div class="center">

		<div id="news" class="column-news">
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

		<div id="events" class="column-events">
			<div class="events-title">
				Events
				<div class="new-event-btn"><a href="#"><i class="fa fa-pencil-square-o fa-2x fa-fw"></i>Add event</a></div>
			</div>
			<div class="events-list">
				<table>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>

		<div id="articles" class="column-articles">
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
			<div class="articles-list">
				<table>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="popup" class="overlay"> 
	<div class="create-event-wrap">
		<div class="title-div">Add Facebook event</div>
		<div class="fb-events-list">
			<select class="form-group">
				 <option value="" disabled >Facebook events</option>
			</select>
		</div>
		<div class="event-types">
			<select class="form-group">
				 <option value="" disabled selected>Type</option>
				 <option value="Meet">Meet</option>
				 <option value="Track">Track</option>
				 <option value="Show">Show</option>
				 <option value="Track/Show">Track/Show</option>
			</select>
		</div>
		<div class="add-btn">
			<div>
				<button class="btn">Add event</button>
			</div>
		</div>
		<div class="address-wrap">
			<form>
				<div class="form-group">
					<input type="text" class="form-control street" placeholder="street">
				</div>
				<div class="form-group">
					<input type="text" class="form-control city" placeholder="city">
				</div>
				<div class="form-group">
					<select class="form-group state">
						 <option value="" disabled >state</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" required="true" class="form-control zip" placeholder="zip">
				</div>
				<div class="form-group">
					<input type="submit" class="btn create-btn" value="Create event &gt;">
				</div>
			</form>
		</div>
	</div>
</div>

@include('includes/main-footer', [ 'js' => [ 'dashboard' ] ])