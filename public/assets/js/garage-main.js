$(function() {
	getCars();
	var cars;
});

function getCars() {
	sendRequest({
		url: 'cars',
		type: 'GET',
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(r) {
		cars = r.cars;
		console.log(cars);
	}

	function __handleError() {
		alert('Service is broken... probably like your car, so please bare with us.');
	}
}