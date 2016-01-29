var vm;

$(function() {
	Vue.directive('invisible', function(value) {
		$(this.el).toggleClass('invisible', !!value);
	});

	Vue.directive('disabled', function(value) {
		value ? $(this.el).attr('disabled', 'disabled') : $(this.el).removeAttr('disabled');
	});

	vm = new Vue({
		el: document.body,
		data: {
			v_state: null,

			fb_id: null,
			login: {
				email: null,
				password: null
			},
			personal: {
				first_name: null,
				last_name: null,
				email: null,
				password: null
			},
			car: {
				v_makes: null,
				v_models: null,
				v_years: null,
				v_styles: null,

				make: null,
				model: null,
				year: null,
				style: null
			}
		},
		methods: {
			showOverlay: showOverlay,
			hideOverlay: hideOverlay,
	 		loginWithFacebook: loginWithFacebook,
	 		loginWithCredentials: loginWithCredentials,
	 		showJoinForm: showJoinForm,
	 		proceedFromPersonalInfo: proceedFromPersonalInfo,
	 		proceedFromCarInfo: proceedFromCarInfo,

	 		loadCarMakes: loadCarMakes,
			loadCarModels: loadCarModels,
			loadCarYears: loadCarYears,
			loadCarStyles: loadCarStyles
		}
	});
	
	$('.loading').hide();
});

function showOverlay() {
	vm.v_state = 'login';
}

function hideOverlay(e) {
	/* TODO: sean should make the thing that the click is actually bound to
	   show up in the event, and then submit a pull request to Vue */
	if ($(e.target).is('#access-overlay'))
		vm.v_state = null;
}

function loginWithFacebook() {
	FB.login(__handleFBLoginResponse, { scope: 'public_profile,email' });

	function __handleFBLoginResponse(response) {
		if (response.status != 'connected') return;

		sendRequest({
			url: '/access/fb-login',
			type: 'POST',
			success: __handleLookupSuccess
		});
	}

	function __handleLookupSuccess(result) {
		console.log(result);

		vm.fb_id = fbId;
		vm.v_state = 'collect-car-info';
		loadCarMakes();
	}
}

function loginWithCredentials() {

}

function showJoinForm() {
	vm.v_state = 'collect-personal-info';

	Vue.nextTick(function() {
		vm.$els.firstNameInput.focus();
	});
}

function proceedFromPersonalInfo() {
	vm.v_state = 'collect-car-info';
	loadCarMakes();
}

function proceedFromCarInfo() {

}

function loadCarMakes() {
	sendRequest({
		url: '/vehicle/makes',
		type: 'GET',
		success: __handleSuccess
	});

	function __handleSuccess(result) {
		vm.car.v_makes = result.makes;
	}

	return true;
}

function loadCarModels() {
	vm.car.model = null;
	vm.car.year = null;
	vm.car.style = null;

	vm.car.v_models = null;
	vm.car.v_years = null;
	vm.car.v_styles = null;

	sendRequest({
		url: '/vehicle/models',
		data: { make: vm.car.make },
		success: __handleSuccess
	});

	function __handleSuccess(result) {
		vm.car.v_models = result.models;
	}
}

function loadCarYears() {
	vm.car.year = null;
	vm.car.style = null;

	vm.car.v_years = vm.car.model.years;
	vm.car.v_styles = null;
}

function loadCarStyles() {
	vm.car.style = null;

	sendRequest({
		url: '/vehicle/styles',
		data: {
			make: vm.car.make,
			model: vm.car.model.name,
			year: vm.car.year
		},
		success: __handleSuccess
	});

	function __handleSuccess(result) {
		vm.car.v_styles = result.styles;
	}
}

window.fbAsyncInit = function() {
	FB.init({
		appId: '720657848049692',
		cookie: true,
		xfbml: false,
		version: 'v2.5'
	});
};

(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));