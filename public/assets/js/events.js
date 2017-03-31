/*
 	Get filtered articles
 */
var generateEventsListComponent = (function() {
	return Vue.extend({
		template: window.os_templates.events,
		data: function() {
			return {
				events: null
			}
		},
		methods: {
			
		},
        created: handleVueReady
	});

	function handleVueReady() {
	
	}

	// this code needs to be reviewed to make sure it is still valid and done efficiently
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
});