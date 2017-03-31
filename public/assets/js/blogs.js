var generateBlogsComponent = (function() {
	return Vue.extend({
		template: window.os_templates.blogs,
		data: function() {
			return {
				
			}
		},
		methods: {
		
		},
        created: handleVueReady
	});

	function handleVueReady() {
		console.log('news.js is loaded ', this);
	}
});