$(function() {
	// call function to build the list of users who have not been accepeted
	getJoinRequestsList();
});

function getJoinRequestsList() {
	// ajax request to the server for users
	sendRequest({
		url: 'join-requests',
		type: 'GET',
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(response) {
		createRequestList(response.requests)
	}

	function __handleError(e) {
		console.log(e);
		alert('There was a problem getting the users');
	}
}

function createRequestList(requests) {
	$requestTable = $(document.body).find('#mail-table tbody');

	$.each(requests, function(index, request) {
		$tr = $('<tr>').addClass('request-item').attr('id', request.id).appendTo($requestTable);
		$('<td>').addClass('name').text(request.first_name + ' ' + request.last_name).appendTo($tr);
		$('<td>').addClass('facebook-id').text(request.fb_id ? request.fb_id : 'None provided').appendTo($tr);
		$('<td>').addClass('car').text(request.company).appendTo($tr);
		$('<td>').addClass('company').text(request.company ? request.company : 'None provided').appendTo($tr);
		$('<td>').addClass('sent-at').text(request.company).appendTo($tr);

		console.log(request);	
	});
	
};

