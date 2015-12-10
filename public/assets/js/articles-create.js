$(function() {
	$('.article-form').submit(processArticleCreate);
	$('.creating').hide();
});

function processArticleCreate(e) {
	e.preventDefault();
	$('.creating').show();

	var type = $('.article-type').val();
	var title = $('.article-title-input').val();
	var body = $('.article-body textarea').val();
	var summary = $('.article-summary textarea').val();

	sendRequest({
		url:'articles',
		type: 'POST',
		dataType: 'json',
		data: {
			type: type,
			title: title,
			body: body,
			summary: summary
		},
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(r) {
		$('.creating').hide();
		location.reload();
	}

	function __handleError(r) {
		alert('An unexpected error has occured. ' + r);
		console.log('Failure! ' + r);
	}
}