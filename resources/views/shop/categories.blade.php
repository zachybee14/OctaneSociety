@section('shop/header')

<link rel="stylesheet" type="text/css" href="/assets/css/shop/categories.css?{{ filemtime('assets/css/shop/categories.scss') }}">

@stop

@include('shop/includes/header', [ 'css' => [ 'shop/categories' ] ])

<div class="title">
	<span>{{ $category }}</span>
</div>
	
<div class="categories-container">
	This is where the {{ $category }} are
</div>

<script type="text/javascript" src="/assets/lib/jquery/jquery.js"></script>
<script type="text/javascript" src="/assets/lib/bootstrap/js/bootstrap.js"></script> 
<script type="text/javascript" src="/assets/lib/vue/vue.js"></script>

<div class="loading hidden">
	<i class="fa fa-spinner fa-pulse fa-5x"></i>
</div>

@include('shop/includes/footer')