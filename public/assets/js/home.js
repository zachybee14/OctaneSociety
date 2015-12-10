$(function() {
	getArticleTitles()
	$('.article-type').change(getArticleTitles);
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

	function __handleSuccess(r) {
		createArticleList(r);
	}

	function __handleError(r) {
		console.log('Failure ', r);
	}
}

function createArticleList(r) {
	var $articlesDiv = $('.article-titles');
	var article;

	$articlesDiv.empty();

	for (var i = 0; i < r.articles_titles.length; i++) {
			article = r.articles_titles[i];
		
		var $titleDiv = $('<div class"article-link"></div').appendTo($articlesDiv);
		$('<a href="http://octanesociety.dev/articles/' + article.id + '"> - ' + article.title + '</a>').appendTo($titleDiv);
	};
}