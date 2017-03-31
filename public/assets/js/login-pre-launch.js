/*
 Property of Octane Society LLC
 */

var generateLoginComponent = (function() {
    return Vue.extend({
        template: window.os_templates.login_pre_launch,
        data: function() {
            return {
                signupState: 'car',

                email: null,
                password: null,

                years: _generateYearList(),
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

            loadMakes: _loadMakes,
            loadModels: _loadModels,
            loadStyles: _loadStyles
        }
    });

    function _generateYearList() {
        var maxYear = new Date().getFullYear() + 1;
        var yearList = [];
        for (var year = maxYear; year >= 1900; year--)
            yearList.push(year);
        return yearList;
    }

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

    function _loadMakes() {
        var vm = this;

        vm.vehicle.make = null;
        vm.vehicle.model = null;
        vm.vehicle.style = null;

        vm.makes = null;
        vm.models = false;
        vm.styles = false;

        _logVehicle.call(this);

        sendRequest({
            url: '/api/vehicle/makes',
            type: 'GET',
            data: {
                year: vm.vehicle.year
            },
            success: __handleSuccess
        });

        function __handleSuccess(result) {
            vm.makes = result.makes;
        }
    }

    function _loadModels() {
        this.vehicle.model = null;
        this.vehicle.style = null;

        this.models = this.vehicle.make.models;
        this.styles = false;

        _logVehicle.call(this);
    }

    function _loadStyles() {
        var vm = this;

        vm.vehicle.style = null;

        vm.styles = null;

        _logVehicle.call(this);

        sendRequest({
            url: '/api/vehicle/styles',
            type: 'GET',
            data: {
                year: vm.vehicle.year,
                make: vm.vehicle.make.id,
                model: vm.vehicle.model.id
            },
            success: __handleSuccess
        });

        function __handleSuccess(result) {
            vm.styles = result.styles;
        }
    }

    function _continueSignup() {
        _logVehicle.call(this);

        this.signupState = 'auth';
    }

    function _logVehicle() {
        sendRequest({
            url: '/api/vehicle/selection-log',
            type: 'POST',
            data: {
                year: this.vehicle.year,
                make: this.vehicle.make ? this.vehicle.make.name : undefined,
                model: this.vehicle.model ? this.vehicle.model.name : undefined,
                style_id: this.vehicle.style ? this.vehicle.style.id : undefined
            }
        });
    }
});