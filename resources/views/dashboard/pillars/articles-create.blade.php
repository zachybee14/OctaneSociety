@include('includes/main-header', [ 'css' => [ 'articles-create' ] ])
	
	<link rel="stylesheet" href="/assets/lib/font-awesome/css/font-awesome.css">	
	<link rel="stylesheet" type="text/css" href="/assets/css/articles-create.css?{{ filemtime('assets/css/articles-create.css') }}">
	 <div class="article-create-wrap">
        <h2>Create New Article</h2>
		<form class="article-form">
			<div class="form-group">
				<select type="text" class="form-control article-type" value="">
					<option selected disabled hidden value="">Select Type</option>
					<option value="how to" default="">How to</option>
					<option value="review">Review</option>
					<option value="help">Help</option>
					<option value="event">Event</option>
				</select>
			</div>
			<div class="form-group">
				<input required="true" type="text" class="form-control article-title-input" placeholder="Article Title">
			</div>
			<div class="form-group article-body">
				<h3>Article Body</h3>
				<textarea rows="4" cols="50"></textarea>
			</div>
			<div class="form-group article-summary">
				<h3>Article Summary</h3>
				<textarea rows="4" cols="50"></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Save">
			</div>
		</form>
		<div class="creating"> 
			<div><i class="fa fa-spinner fa-pulse fa-5x"></i></div>
		</div>
      </div>
@include('includes/main-footer', [ 'js' => [ 'articles-create' ] ])