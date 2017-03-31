/*
    Property of Octane Society LLC
 */

(function() {
    var fn = function(el, binding) {
        binding.value ? $(el).attr('disabled', 'disabled') : $(el).removeAttr('disabled');
    };

    Vue.directive('disabled', {
        bind: fn,
        update: fn
    });
})();

var routes = [
    //{ path: '/events', redirect: '/events/list' },
    //{ path: '/articles', redirect: '/articles/list' },

    { path: '/', component: generateLoginComponent() },
    { path: '/home', component: generateHomeComponent() },
    { path: '/articles', component: generateArticlesListComponent() },
    { path: '/articles/view', component: generateArticlesViewComponent() },
    { path: '/articles/edit', component: generateArticlesEditComponent() },
    { path: '/events', component: generateEventsListComponent() },
    { path: '/events/view', component: generateEventsViewComponent() },
    { path: '/events/edit', component: generateEventsEditComponent() },
    { path: '/vehicles', component: generateVehcilesComponent() },
    { path: '/vehicles/view', component: generateVehiclesViewComponent() },
    { path: '/blogs', component: generateBlogsComponent() },
    { path: '/blogs/view', component: generateBlogsViewComponent() },
    { path: '/shop', component: generateShopComponent() },
    { path: '/profile', component: generateProfileComponent() }
];

var router = new VueRouter({
    routes: routes

});

var App = new Vue({
    router: router
}).$mount('#app');


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