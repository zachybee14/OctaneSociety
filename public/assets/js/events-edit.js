var generateEventsEditComponent = (function() {
	return Vue.extend({
		template: window.os_templates.events_edit,
		data: function() {
			return {
				event: {
					fb_id: null,
					name: null,
					description: null,
					type: null,
					location: {
						address: null,
						city: null,
						state: null,
						zip: null,
					},
					days: [
						{
							date: null,
							start_time: null,
							end_time: null
						}
					],
				}
			}
		},
		methods: {
			createEvent: createEvent,
			addDay: addDay
		},
        created: handleVueReady
	});

	// Things to do...
	// event creation from a facebook event
	// manual event creation
	// edit event
	// add cruise to event

	function handleVueReady() {
		
	}

	function createEvent() {
		var event = this.event;

		var eventData = {
			fb_id: event.fb_id,
			name: event.name,
			description: event.description,
			days: this.event.days,
			type: event.type,
			location: {
				street: event.location.address,
				city: event.location.city,
				state: event.location.state,
				zip: event.location.zip
			}
		};

		sendRequest({
			url: 'api/events/create',
			type: 'POST',
			dataType: 'JSON',
			data: eventData,
			success: _handleSuccess,
			failure: __handleError
		});

		function _handleSuccess(response) {

			console.log('Response for create event function ', response);
		}

		function __handleError(e) 
		{
			alert('Soemthing went wrong while trying to create the event', e);
		}
	}

	function addDay() {
		var days = this.event.days;
		days.push({
			date: null,
			start_time: null,
			end_time: null
		});
	}
});