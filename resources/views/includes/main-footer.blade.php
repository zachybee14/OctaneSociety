</div>

<script src="/assets/lib/jquery/jquery.js"></script>
<script src="/assets/lib/bootstrap/js/bootstrap.js"></script> 

<script type="text/javascript" src="/assets/js/common.js"></script> 

@if (isset($js))
	@foreach ($js as $item)
		<script src="/assets/js/{{ $item }}.js?{{ fileatime('assets/js/' . $item . '.js') }}"></script> 
	@endforeach
@endif

@yield('footer')

</body>
</html>