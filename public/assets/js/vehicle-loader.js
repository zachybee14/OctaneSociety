
// Makes, Models, styles are loaded here and logged

function generateYearList() {
    var maxYear = new Date().getFullYear() + 1;
    var yearList = [];
    for (var year = maxYear; year >= 1900; year--)
        yearList.push(year);
    return yearList;
}

function loadMakes() {
    var vm = this;

    vm.vehicle.make = null;
    vm.vehicle.model = null;
    vm.vehicle.style = null;

    vm.makes = null;
    vm.models = false;
    vm.styles = false;

    console.log();

    if (this.$route.path == '/')
        logVehicle.call(this);

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

function loadModels() {
    this.vehicle.model = null;
    this.vehicle.style = null;

    this.models = this.vehicle.make.models;
    this.styles = false;

    if (this.$route.path == '/')
        logVehicle.call(this);
}

function loadStyles() {
    var vm = this;

    vm.vehicle.style = null;

    vm.styles = null;

    if (this.$route.path == '/')
        logVehicle.call(this);

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
    if (this.$route.path == '/')
        logVehicle.call(this);

    this.signupState = 'auth';
}

function logVehicle() {
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