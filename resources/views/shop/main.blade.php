@section('shop/header')

<link rel="stylesheet" type="text/css" href="/assets/css/shop/main.css?{{ filemtime('assets/css/shop/main.scss') }}">

@stop

@include('shop/includes/header', [ 'css' => [ 'shop/main' ] ])

<div class="shop-container">
	<div class="title">
		<span>Shop</span>
	</div>
	<div class="categories-container">
		<?php foreach ($categories as $category): ?>
			<a href="shop/{{ $category->key }}" class="category" style="background-image:url('/images/shop/{{ $category->key }}.jpg');">
				<h2>{{ $category->name }}</h2>
			</a>
		<?php endforeach; ?>
	</div>
</div>

@include('shop/includes/footer', [ 'js' => [ 'dashboard' ] ])