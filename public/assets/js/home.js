var generateHomeComponent = (function() {
	return Vue.extend({
		template: window.os_templates.home,
		data: function() {
			return {
				events: null,
				article: null
			}
		},
		methods: {
			getEvents: getEvents,
			getArticles: getArticles
		},
        created: handleVueReady
	});

	function handleVueReady() {
		getEvents.call(this);

		/*
		someFunction(); // calls with a context of 'window'
		someObject.someFunction(); // calls with a context of 'someObject' -- basically whatever object you called it on
		someFunction.call(someContext); // calls with a context of whatever object is assigned to someContext
		someFunction.call(someContext, 'arg1', 'arg2', 'arg3'); // calls like above, but with 3 arguments (as in: someFunction('arg1', 'arg2', 'arg3'))
		someFunction.apply(someContext, ['arg1', 'arg2', 'arg3']); // same as above, but arguments are provided as an array. use when you have a variable number of arguments
		*/
	}

	function getEvents()
	{
		var vm = this;

		var radius = 30;
		var limit = 10;
		var makeId = null;
		var showTrending = null;
	 	var showMutualFriends = null;

		sendRequest({
			url: 'api/events/filtered',
			type: 'POST',
			dataType: 'JSON',
			data: {
				radius: radius,
				limit: limit,
				make_id: makeId,
				trending: showTrending,
				mutual_friends: showMutualFriends
			},
			success: _addEventsToView,
			failure: __handleError
		});

		function _addEventsToView(response) {
			if (response.events != null) {
				vm.events = response.events;
			}

			console.log(vm.events);
		}

		function __handleError(e) 
		{
			alert('Soemthing went wrong while trying to get the events', e);
		}
	}

	function getArticles() 
	{
		sendRequest({
			url: 'article',
			type: 'GET',
			dataType: 'JSON',
			data: {
				filter: filterType
			},
			success: __handleSuccess,
			failure: __handleError
		});
	}

	function getNews() 
	{
		console.log('getting news....');
	}
});