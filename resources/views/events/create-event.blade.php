@section('header')

<link rel="stylesheet" type="text/css" href="/assets/css/create-event.css?{{ filemtime('assets/css/create-event.scss') }}">

@stop

@include('includes/main-header', [ 'css' => [ 'create-event' ] ])

<div class="create-event-wrap">

</div>

@include('includes/main-footer', [ 'js' => [ 'create-event' ] ])