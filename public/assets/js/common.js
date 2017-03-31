//http://octanesociety.dev/profile
// profile dropdown: Profile, Edit Profile, Logout

function processLogout() {
	sendRequest({
		url: '/access/logout',
		type: 'POST',
		success: __handleSuccess,
		failure: __handleError
	});

	function __handleSuccess(response) {
		window.location = '/access';
	}

	function __handleError(r) {
		alert(r);
	}
}

function sendRequest(params) {
    params.maskBtn && $(params.maskBtn).disable(params.maskBtnText || 'Please wait...');
    params.maskInput && $(params.maskInput).disable(params.maskInputText || 'Please wait...');
    params.maskForm && $(params.maskForm).find('input, select, textarea, button').not('[disabled]').attr('data-disabled-by-request', 'true').attr('disabled', 'disabled');
    params.waitEl && $(params.waitEl).show();
    params.maskEl && $(params.maskEl).mask(params.maskText || 'Please wait...');

    var headers = {};

    if (params.type && params.type != 'GET' && params.data) {
        params.data = JSON.stringify(params.data);
        headers['Content-Type'] = 'application/json';
    }

    params.data || (params.data = {});

    $.ajax({
        url: params.url || location.href,
        type: params.type || 'GET',
        headers: headers,
        data: params.data,
        dataType: 'json',
        success: __handleSuccess,
        error: __handleError,
        xhrFields: {
            withCredentials: true
        }
    });

    function __handleSuccess(result) {
        if (params.unmaskOnSuccess !== false || !result.success)
            __unmask();
        if (result.success)
            params.success && params.success(result);
        else
            params.failure && params.failure(result);
    }

    function __handleError(err) {
        __unmask();
        params.failure && params.failure(null, err);
    }

    function __unmask() {
        params.maskBtn  && $(params.maskBtn).enable();
        params.maskInput && $(params.maskInput).enable();
        params.maskForm && $(params.maskForm).find('input, select, textarea, button').filter('[data-disabled-by-request]').removeAttr('disabled').removeAttr('data-disabled-by-request');
        params.waitEl && $(params.waitEl)[params.removeWaitEl ? 'remove' : 'hide']();
        params.maskEl && $(params.maskEl).mask(false);
    }
}

/*
    rules = k:v object
    k = model key
    v = error string for failed requirement
        - or -
        object:
          { tests: [ ... ], test: ..., msg: ... }
        where tests (optional) is an array containing:
          required, integer, float, max:n, min:n, maxlen:n, minlen:n
          - or -
          a custom function, which should return a true/false, called with:
            value, field, vm
        where test (optional) is a single entry that would otherwise be in the tests array
        where msg (optional) is the message to display in the event of test failure
        - or -
        array of objects
        - or -
        function to test and return true/false
*/
function vueValidate(vm, rules) {
    var totalPasses = true;
    $.each(rules, function(key, ruleset) {
        var directive = findObject(vm._directives, function(directive) {
            return directive.name == 'model' && directive.expression == key;
        });
        if (!directive) return;
        var el = directive.el;
        var value = vm.$get(key);
        if (el.tagName == 'SELECT' && $(el).find('option:selected').is(':disabled'))
            value = null;
        if (!$.isArray(ruleset))
            ruleset = [ ruleset ];
        var fieldPasses = true;
        $.each(ruleset, function(index, rule) {
            if (typeof(rule) == 'string')
                rule = { tests: [ 'required' ], msg: ruleset };
            else if (typeof(rule) == 'function')
                rule = { tests: [ rule ] };
            else if (rule.test)
                rule.tests = [ rule.test ];
            findObject(rule.tests, function(test) {
                if (typeof(test) == 'function') {
                    fieldPasses = test.apply(window, [ value, el, vm ]);
                }
                else if (test == 'required') {
                    if (value == null)
                        fieldPasses = false;
                    else if (typeof(value) == 'string')
                        fieldPasses = value.trim().length > 0;
                }
                // ... todo: write the rest of the rules ...
                return !fieldPasses;
            });
            if (!fieldPasses && rule.msg) {
                $(el).addClass('invalid').tooltip({ title: rule.msg });
            }
        });
        fieldPasses && $(el).removeClass('invalid').tooltip('destroy');
        totalPasses = totalPasses && fieldPasses;
    });
    return totalPasses;
}

function findObject(obj, cb) {
	for (key in obj)
		if (cb(obj[key], obj) === true)
			return obj[key];
}