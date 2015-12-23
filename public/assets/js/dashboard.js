$(function() {
	getArticles();
	getEvents();
	$('.article-type').change(getArticles);
	$('.new-event-btn').click(showAddEventPopup);
	
	$('.new-article-btn').click(function() {
		window.location = 'articles/articles-create';
	});
});

function getArticles() {
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
		var $articlesDiv = $('.articles-list');
		var $articleTitleDiv;

		$articlesDiv.empty();

		$.each(response.articles_titles, function(index, article) {
			
			$articleTitleDiv = $('<div>').addClass('link').appendTo($articlesDiv);
			$('<a></a>').attr('href', '/articles/' + article.id + '"> - ' + article.title).text(article.title).appendTo($articleTitleDiv);
		});
	}

	function __handleError(e) {
		console.log('Failure ', e);
	}
}

function getEvents() {
	sendRequest({
		url: '/events/list',
		type: 'GET',
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(response) {
		var $eventsDiv = $('.events-list');
		var $eventTitleDiv;

		$eventsDiv.empty();

		$.each(response.events, function(index, eventData) {
			
			$eventTitleDiv = $('<div>').addClass('link').appendTo($eventsDiv);
			$('<a></a>').attr('href', '/events/' + eventData.id + '"> - ' + eventData.name).text(eventData.name).appendTo($eventTitleDiv);
		});
	}

	function __handleError(e) {
		console.log('Failure ', e);
	}
}

function showAddEventPopup() {
	var $popup = $('#popup');
	var $addressWrap = $popup.find('.address-wrap');
	var $eventsList = $popup.find('.fb-events-list');
	var $types = $popup.find('.event-types');
	var $addBtn = $popup.find('.add-btn button');
	var authResponse;

	$popup.show();
	$addBtn.click(__processEvent);

	$popup.click(function(e) {
		if (e.target != e.currentTarget) 
			return;

		// reset the elements states
		$popup.hide();
		$addressWrap.hide();
		$eventsList.show();
		$addBtn.show();
	});

	// reset the events select options
	var $select = $popup.find('.fb-events-list select');
	$select.find('option').not(':first').remove();

	// get the Facebook userID
	FB.getLoginStatus(function(response) {
		authResponse = response.authResponse;
	});

	// call facebook for the persons events that they have created
	FB.api(
		'/' + authResponse.userID + '/events',
		'GET',
		{
			type:'created'
		},
		function (response) {
			if (response && !response.error) {
				console.log(response);
				$.each(response.data, function(index, eventData) {
					$('<option>').data(eventData).val(eventData.id).text(eventData.name).appendTo($select);
				});
			}
		}
	);

	// add the event to our system
	function __processEvent() {
		var option = $eventsList.find('option:selected').data();
		var type = $types.find('option:selected').val();
		var location;

		$addressWrap.find('.create-btn').click(__createEvent);

		if (!option.place.location) {
			$addressWrap.show();
			$eventsList.hide();
			$addBtn.hide();

			__getStates();
		}
		else {
			location = option.place.location
			$addressWrap.find('.create-btn').click();
		}

		function __createEvent(e) {
			e.preventDefault();

			if (!location) {
				location = {
					street: $addressWrap.find('.street').val(),
					city: $addressWrap.find('.city').val(),
					state: $addressWrap.find('.state').val(),
					zip: $addressWrap.find('.zip').val()
				};
			}

			sendRequest({
				url: '/events/create',
				type: 'POST',
				data: {
					fb_id: option.id,
					name: option.name,
					type: type,
					description: option.description,
					start_time: option.start_time,
					location: location
				},
				success: __handleSuccess,
				failure: __handleError
			});

			function __handleSuccess(response) {
				window.location = '/event/test';

				// redirect to the events page
				console.log(response);
			}

			function __handleError(e) {
				console.log('Failure ', e);
			}
		}

		function __getStates() {
			$select = $addressWrap.find('.state');
			$select.find('option').not(':first').remove();

			sendRequest({
				url: '/states',
				type: 'get',
				success: __handleSuccess,
				failure: __handleError
			});

			function __handleSuccess(response) {
				
				$.each(response.states, function(index, state) {

					$('<option>').val(state.state_abbrev).text(state.state_abbrev).appendTo($select);
				});
			}

			function __handleError(e) {
				console.log('Failure could not retreive the States list', e);
			}
		}
	}
}

window.fbAsyncInit = function() {
	FB.init({
		appId			: '720657848049692',
		cookie		 : true,	// enable cookies to allow the server to access 
							// the session
		xfbml			: true,	// parse social plugins on this page
		version		: 'v2.5' // use version 2.5
	});
};

// Load Facebook SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));