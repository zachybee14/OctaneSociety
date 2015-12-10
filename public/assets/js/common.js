$(function() {
	//http://octanesociety.dev/profile
	// profile dropdown: Profile, Edit Profile, Logout
	$('#profile-link').click();
	$('.logout-btn').click(processLogout);
});

function processLogout() {
	sendRequest({
		url: '/access/logout',
		type: 'POST',
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(response) {
		window.location = '/access';
	}

	function __handleError(r) {
		alert(r);
	}
}

function sendRequest(params) {
	params.maskBtn && params.maskBtn.disable('Please wait...');

	if (params.type && params.type != 'GET' && params.type != 'POST') {
		params.data || (params.data = {});
		params.data._method = params.type;
		params.type = 'POST';
	}

	var headers = {};
	if (params.type && params.type != 'GET' && params.data) {
		params.data = JSON.stringify(params.data);
		headers['Content-Type'] = 'application/json';
	}

	$.ajax({
		url: params.url || location.href,
		type: params.type || 'GET',
		headers: headers,
		data: params.data,
		dataType: 'json',
		success: __handleSuccess,
		error: __handleError
	});

	function __handleSuccess(result) {
		params.maskBtn && params.maskBtn.enable();
		if (!result.success)
			return params.failure && params.failure(result.error);
		params.success && params.success(result);
	}

	function __handleError() {
		params.maskBtn && params.maskBtn.enable();
		params.failure && params.failure();
	}
}