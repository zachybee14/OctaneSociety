var generateShopComponent = (function() {
	return Vue.extend({
		template: window.os_templates.shop,
		data: function() {
			return {
				
			}
		},
		methods: {
		
		},
        created: handleVueReady
	});

	function handleVueReady() {
		console.log('shop.js is loaded ', this);
	}
});