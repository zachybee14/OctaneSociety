/*
	 Get event
	 Get event comments
	 Get sponsors
	 Add comment to event
	 Add votes to event
	 Add votes to comments
 */
var generateEventsViewComponent = (function() {
	return Vue.extend({
		template: window.os_templates.events_view,
		data: function() {
			return {
				cruise: {
					event_id: "19",
					name: null,
					start_time: null,
					estimated_duration: null,
					location: {
						street: null,
						city: null,
						state: null,
						zip: null,
					},
					stops: []
				}
			}
		},
		methods: {
			createCruise: createCruise,
			addStop: addStop
		},
        created: handleVueReady
	});

	function handleVueReady() {
	
	}

	function createCruise() {
		var cruise = this.cruise;

		console.log ('Name: ', cruise.name);
		console.log ('Start Time: ', cruise.start_time);
		console.log ('Start Location: ');
		console.log (cruise.location.address);
		console.log (cruise.location.city, ' ', cruise.location.state, ' ', cruise.location.zip);

		/*var stops = {
			one: {
				street: cruise.stops.one.street,
				city: cruise.stops.one.city,
				state: cruise.stops.one.state,
				zip: cruise.stops.one.zip,
				duration: cruise.stops.one.duration
			},
			two: {
				street: cruise.stops.two.street,
				city: cruise.stops.two.city,
				state: cruise.stops.two.state,
				zip: cruise.stops.two.zip,
				duration: cruise.stops.two.duration
			},
			three: {
				street: cruise.stops.three.street,
				city: cruise.stops.three.city,
				state: cruise.stops.three.state,
				zip: cruise.stops.three.zip,
				duration: cruise.stops.three.duration
			},
		};

		stops = null;*/
		
		var cruiseData = {
			event_id: cruise.event_id,
			name: cruise.name, 
			start_time: cruise.start_time,
			start_location: {
				street: cruise.location.street,
				city: cruise.location.city,
				state: cruise.location.state,
				zip: cruise.location.zip
			} ,
			stops: this.cruise.stops
		};

		console.log (this.cruise.stops[0]);

		sendRequest({
			url: 'api/cruises/create',
			type: 'POST',
			dataType: 'JSON',
			data: cruiseData,
			success: _handleSuccess,
			failure: __handleError
		});

		function _handleSuccess(response) {

			console.log('Response for adding a cruise to an event ', response);
		}

		function __handleError(e) 
		{
			alert('Soemthing went wrong while trying to add a cruise to the event', e);
		}
	}

	function addStop(e) {
		e.preventDefault();

		// get the parent element for the input
		// create a new div and append it to the parent div
		// create the inputs and append them to the new div

		// push a new stop array to vm.cruise.stops
		var stops = this.cruise.stops;

		var stop = 0;
		var i;
		for (i in stops) {
		    if (stops.hasOwnProperty(i)) {
		        stop++;
		    }
		}

		console.log(stop);

		stops[stop] = {
			street: null,
			city: null,
			state: null,
			zip: null,
			duration: null
		};

		$('<div/>', {
		    class: "stopInputs",
		    id: "stopInput" + stop,
		    html: "<span>Address:<\/span><br><input v-model=\"cruise.stops." + stop + ".street\" type\"text\" size=\"25\" name=\"street\" placeholder=\"Street\"><br><input v-model=\"cruise.stops." + stop + ".city\" type\"text\" size=\"16\" name=\"city\" placeholder=\"City\"><select v-model=\"cruise.stops." + stop +".state\"><option value=\"AL\">AL<\/option><option value=\"AK\">AK<\/option><option value=\"AZ\">AZ<\/option><option value=\"AR\">AR<\/option><option value=\"CA\">CA<\/option><option value=\"CO\">CO<\/option><option value=\"CT\">CT<\/option><option value=\"DE\">DE<\/option><option value=\"DC\">DC<\/option><option value=\"FL\">FL<\/option><option value=\"GA\">GA<\/option><option value=\"HI\">HI<\/option><option value=\"ID\">ID<\/option><option value=\"IL\">IL<\/option><option value=\"IN\">IN<\/option><option value=\"IA\">IA<\/option><option value=\"KS\">KS<\/option><option value=\"KY\">KY<\/option><option value=\"LA\">LA<\/option><option value=\"ME\">ME<\/option><option value=\"MD\">MD<\/option><option value=\"MA\">MA<\/option><option value=\"MI\">MI<\/option><option value=\"MN\">MN<\/option><option value=\"MS\">MS<\/option><option value=\"MO\">MO<\/option><option value=\"MT\">MT<\/option><option value=\"NE\">NE<\/option><option value=\"NV\">NV<\/option><option value=\"NH\">NH<\/option><option value=\"NJ\">NJ<\/option><option value=\"NM\">NM<\/option><option value=\"NY\">NY<\/option><option value=\"NC\">NC<\/option><option value=\"ND\">ND<\/option><option value=\"OH\">OH<\/option><option value=\"OK\">OK<\/option><option value=\"OR\">OR<\/option><option value=\"PA\">PA<\/option><option value=\"RI\">RI<\/option><option value=\"SC\">SC<\/option><option value=\"SD\">SD<\/option><option value=\"TN\">TN<\/option><option value=\"TX\">TX<\/option><option value=\"UT\">UT<\/option><option value=\"VT\">VT<\/option><option value=\"VA\">VA<\/option><option value=\"WA\">WA<\/option><option value=\"WV\">WV<\/option><option value=\"WI\">WI<\/option><option value=\"WY\">WY<\/option><\/select><br><input v-model=\"cruise.stops." + stop + ".zip\" type=\"number\" name=\"zip\" size=\"5\" placeholder=\"Zip\">",
		    css: {
	        fontWeight: 200,
	        color: 'black'
		    },
		}).appendTo(".stops-container");

		console.log("Stop added ", stops);
	}
});