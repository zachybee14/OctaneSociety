/*
	Get article
	Get article comments
	Add comment to article
	Add votes to article
	Add votes to comments
	Add endorsements

 */
var generateArticlesViewComponent = (function() {
	return Vue.extend({
		template: window.os_templates.articles_view,
		data: function() {
			return {
				
			}
		},
		methods: {
			
		},
        created: handleVueReady
	});

	function handleVueReady() {
		console.log('articles-view.js is loaded ', this);
	}
});