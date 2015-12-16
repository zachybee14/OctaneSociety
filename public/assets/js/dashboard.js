$(function() {
	getArticleTitles();
	getEvents();
	$('.article-type').change(getArticleTitles);

	$('.new-event-btn').click(function() {
		window.location = '/events/new-event';
	});
	$('.new-article-btn').click(function() {
		window.location = 'articles/articles-create';
	});
});

function getArticleTitles() {
	var filterType = $('.article-type').val();

	sendRequest({
		url: 'article-titles',
		type: 'GET',
		dataType: 'JSON',
		data: {
			type: filterType
		},
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(response) {
		createArticleList(response);
	}

	function __handleError(e) {
		console.log('Failure ', e);
	}
}

function createArticleList(response) {
	var $articlesDiv = $('.article-titles');
	var article;

	$articlesDiv.empty();

	for (var i = 0; i < response.articles_titles.length; i++) {
			article = response.articles_titles[i];
		
		var $titleDiv = $('<div class"article-link"></div').appendTo($articlesDiv);
		$('<a href="/articles/' + article.id + '"> - ' + article.title + '</a>').appendTo($titleDiv);
	};
}

function getEvents() {
	sendRequest({
		url: '/events/list',
		type: 'GET',
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(response) {
		console.log(response);
	}

	function __handleError(e) {
		console.log('Failure ', e);
	}
}