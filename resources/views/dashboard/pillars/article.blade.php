@include('includes/main-header', [ 'css' => [ 'article' ] ])

<link rel="stylesheet" type="text/css" href="/assets/css/article.css?{{ filemtime('assets/css/article.css') }}">

<div class="article-wrap">
	<div class="article-container">
		<div class="title">{{{ $title }}}</div>
		<div class="type">Type: {{{ $type }}}</div>
		<div class="body">{{{ $body }}}</div>
		<div class="summary">{{{ $summary }}}</div>	
	</div>
</div>

@include('includes/main-footer', [ 'js' => [ 'article' ] ])