/*
	Property of Octane Society LLC
*/

var generateLoginComponent = (function() {
	return Vue.extend({
		template: window.os_templates.login,
		name: 'login',
		data: function() {
			return {
                state: 'login',
                signupState: 'car',

                email: null,
                password: null,

				years: generateYearList(),
				makes: false,
				models: false,
				styles: false,

				vehicle: {
                    year: null,
					make: null,
					model: null,
					style: null
				}
			};
		},
		methods: {
	 		loginFacebook: _loginFacebook,
	 		loginManually: _loginManually,

            continueSignup: _continueSignup,

            loadMakes: loadMakes,
			loadModels: loadModels,
			loadStyles: loadStyles
		}
	});

	function _loginFacebook() {
		var vm = this;

		FB.login(__handleFBLoginResponse, { scope: 'public_profile,email' });

		function __handleFBLoginResponse(response) {
			if (response.status != 'connected') return;

			var data = { facebook_data: response };
			if (vm.vehicle.style)
				data.vehicle_style_id = vm.vehicle.style.id;

			sendRequest({
				url: '/api/login/facebook',
				type: 'POST',
				data: data,
				success: ___handleLookupSuccess
			});
		}

		function ___handleLookupSuccess(result) {
            router.push('home');

			// if we just created their account and they did not select a car, go to the select car tab
			// else bring them to the home page
		}
	}

	function _loginManually() {
		var vm = this;

        var data = {
            email: vm.email,
            password: vm.password
        };

        if (vm.vehicle.style)
            data.vehicle_style_id = vm.vehicle.style.id;

        sendRequest({
            url: '/api/login',
            type: 'POST',
			data: data,
            success: __handleSuccess
        });

        function __handleSuccess(result) {
            vm.makes = result.makes;
        }
	}
});