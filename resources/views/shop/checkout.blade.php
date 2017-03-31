@section('shop/header')

<link rel="stylesheet" type="text/css" href="/assets/css/shop/checkout.css?{{ filemtime('assets/css/shop/checkout.scss') }}">

@stop

@include('shop/includes/header', [ 'css' => [ 'shop/checkout' ] ])

<div class="checkout-container">
	<div class="form-wrap">
		<div class="checkout-container">
			<div class="shipping-container">
				<h2 class="section-title">Shipping</h2>
				<form>
					<div>
						<select></select>
					</div>
					<div class="section">
						<div class="content">
							<label>First Name</label>
							<input type="text">
						</div>
						<div class="content">
							<label>Last Name</label>
							<input type="text">
						</div>
					</div>
					<div class="section">
						<div class="content">
							<label>Address (Line 1)</label>
							<input class="address"></input>
						</div>
					</div>
					<div class="section">
						<div class="content">
							<label>Address (Line 2)</label>
							<input class="address"></input>
						</div>
					</div>
					<div class="section">
						<div class="content">
							<label>City</label>
							<input type="text">
						</div>
						<div class="content">
							<label>Postal Code</label>
							<input type="text">
						</div>
					</div>
					<div class="section">
						<div class="content">
							<label>Phone Number</label>
							<input type="text">
						</div>
						<div class="content">
							<label>Email</label>
							<input type="text">
						</div>
					</div>
					<div class="section">
						<input type="text">
					</div>
				</form>
			</div>
			<div class="payment-container">
				<label>Payment</label>
			</div>
		</div>
	</div>
</div>

@include('shop/includes/footer', [ 'js' => [ 'dashboard' ] ])