/*
	Get article types
	Get vehicles
	Get Products
	Get Tools
	Get Businesses
	Create Article
*/

var generateArticlesEditComponent = (function() {
	return Vue.extend({
		template: window.os_templates.articles_edit,
		data: function() {
			return {
				article: {
					article_type_id: null,
					applicable_vehicles: [
						{
							id: null //vehicleID
						}
					],
					content: {
						position: null, //positionNumber,
						title: null, //articleTitle,
						body: null, //articleBod,
						sale: {
							target_id: null, //targetID,
							type: null, //typeID,
							price: null, //price,
							condition: null //itemCondition
						},
						review: {
							target_id: null, //targetID,
							type: null, //type,
							rating: null, //rating,
							pros: null, //pros,
							cons: null //cons
						},
						steps: []
					}
				}
			}
		},
		methods: {
			//createArticle: createArticle
		},
        created: handleVueReady
	});

	function handleVueReady()
	{
		//articleTypeID
		console.log('articles-edit.js is loaded ', this);
	}

	function createArticle()
	{
		sendRequest({
			url: 'api/articles/create',
			type: 'GET',
			dataType: 'JSON',
			data: {
				
			},
			success: _addEventsToView,
			failure: __handleError
		});
0
		function _addEventsToView(response) {
			vm.events = response.events;

			console.log('Response for getEvents funciton ', vm.events);
		}

		function __handleError(e) 
		{
			alert('Something went wrong while trying to get the articles', e);
		}
	}

	/* {
	 	article_type_id: articleTypeID,
	 	applicable_vehicles: {
	 		1: {
	 			id: 2
	 		}
	 	},
	 	content: {
			title: articleTitle,
			body: articleBody,
			if_steps: {
				position: position,
				title: stepTitle,
				body: stepBody,
				products: {
					1: {
						id: productID,
						name: productName
					},
					2: {
						id: productID2,
						name: productName2
					}
				},
				tools: {
					1: {
						id: productID,
						name: productName
					},
					2: {
					 id: productID2,
					 name: productName2
					}
				}
			},
			if_sale: {
				target_id: targetID,
				type: typeID,
				price: price,
				condition: condition ('new','low','medium','high')
			}
			if_review: {
				target_id: targetID,
				type: type,
				rating: rating,
				pros: pros,
				cons: cons
			}
		}
	} */
});