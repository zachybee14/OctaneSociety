$(function() {
	// get the cover photo from facebook
	// get the attendees for the event from facebook 
	// get the comments from facebook 
});


function getFaceBookData() {
	// get the Facebook userID
	FB.getLoginStatus(function(response) {
		authResponse = response.authResponse;
	});

	function getFbCoverPhoto() {
		// call facebook for the cover photo
		FB.api(
			'/',
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
	}

	function getFbAttendess() {
		// call facebook for the going/maybe/not going info 
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
	}

	function getFbComments() {
		// call facebook for the comments
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