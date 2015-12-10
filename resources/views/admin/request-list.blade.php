@include('includes/main-header', [ 'css' => [ 'request-list' ] ])

<link rel="stylesheet" type="text/css" href="/assets/css/admin/request-list.css?{{ filemtime('assets/css/admin/request-list.css') }}">
<!-- This view is to show the admin all of the people that have the Status of  -->
<div class="list-wrap">
	<div class="list-background">
		<table class="table table-hover" id="mail-table">
			<thead>
				<tr>
					<th>Name</th>
					<th>FaceBook ID</th>
					<th>Car</th>
					<th>Company</th>
					<th>Sent at</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@include('includes/main-footer', [ 'js' => [ 'admin/request-list' ] ])