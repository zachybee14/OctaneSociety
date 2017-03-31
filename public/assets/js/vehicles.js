
var generateVehcilesComponent = (function() {
    return Vue.extend({
        template: window.os_templates.vehicles,
        name: 'vehicles',
        data: function() {
            return {
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
            }
        },
        methods: {

            loadMakes: loadMakes,
            loadModels: loadModels,
            loadStyles: loadStyles
        },
        created: handleVueReady
    });

    function handleVueReady() {

    }
});