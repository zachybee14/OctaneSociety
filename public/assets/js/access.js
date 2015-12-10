var facebookId;

$(function() {
	// main buttons
	$('#fb-login-button').click(checkLoginState);
	$('.enter-btn button').click(showAccessPopup);
	
	$('.loading').hide();
});

// rest all inputs and selects when the popup is shown 
function showAccessPopup() {
	var $accessPopup = $('#access-popup');
	var $loginWrap = $accessPopup.find('.login-wrap');
	var $infoWrap = $accessPopup.find('.info-wrap');
	var $carSelect = $accessPopup.find('.car-wrap');
	var $mainButtons = $('.wrapper').find('.buttons-wrap');

	//if (!$accessPopup.data('is-setup', true)) {
		$loginWrap.find('.login-form').off('submit').submit(__processLogin);
		$loginWrap.find('.join-btn').click(__showSignupForm);
		$loginWrap.find('.forgot-password-btn').click(__showForgotPasswordForm);
		$accessPopup.data('is-setup', true);
	//}

	$mainButtons.hide();
	$accessPopup.show();

	$accessPopup.click(function(e) {
		if (e.target != e.currentTarget) 
			return;

		// reset the elements states inside access-popup
		$carSelect.hide();
		$loginWrap.show();
		$infoWrap.hide();
		$loginWrap.hide();

		// show the main view 
		$accessPopup.hide();
		$mainButtons.show();
	});
	
	if (facebookId) {
		__showCarSelect();
	}
	else {
		$loginWrap.show();
	}

	function __showSignupForm(e) {
		e.preventDefault();

		$loginWrap.hide();
		$infoWrap.show();

		$infoWrap.find('.next-btn').click(__showCarSelect);
	}
	
	function __showCarSelect() {
		$infoWrap.hide();
		$carSelect.show();

		$carSelect.find('.request-btn').click(__processSignup);

		// find all the selects
		var $makeSelect = $carSelect.find('.make');
		var $modelSelect = $carSelect.find('.model');
		var $yearSelect = $carSelect.find('.year');
		var $styleSelect = $carSelect.find('.style');

		// these will be used in __getStyles
		var make;
		var model;

		// bind functions to the selects 
		$makeSelect.change(__getModels);
		$modelSelect.change(__getYears);
		$yearSelect.change(__getStyles);
		
		// get makes and add them to the makes select
		__getMakes();

		function __getMakes() {

			// if there are already makes then bail
			if ($makeSelect.find('option').length > 1)
				return;

			// send request to the server to get a list of makes
			sendRequest({
				url: '/vehicle/makes',
				type: 'GET',
				success: __handleSuccess,
				failure: __handleError
			});

			function __handleSuccess(response) {

				// loop through the makes and append the options to the #make select
				$.each(response.makes, function(index, make) {
					$('<option>').text(make).val(make).appendTo($makeSelect);
				});
			}

			function __handleError(e) {
				alert('Unable to retrieve vehicle makes', e);
			}
		}

		function __getModels() {
			var $option = $makeSelect.find('option:selected');
			make = $option.val();

			// send request to the server to get a list of models
			sendRequest({
				url: '/vehicle/models',
				type: 'POST',
				data: {
					make: make
				},
				success: __handleSuccess,
				failure: __handleError
			});

			function __handleSuccess(response) {

				// loop through the models and append the options to the #make select
				$.each(response.models, function(index, model) {
					$('<option>').text(model.name).val(model.name).data('years', model.years).appendTo($modelSelect);
				});
			}

			function __handleError(e) {
				alert('Unable to retrieve vehicle models', e);
			}
		}

		function __getYears() {
			$option = $modelSelect.find('option:selected');
			model = $option.val();
			var years = $option.data('years')

			// loop through the years and append the options to the year select
			$.each(years, function(index, year) {
				$('<option>').text(year).val(year).appendTo($yearSelect);
			});
		}

		function __getStyles() {
			var year = $yearSelect.find('option:selected').val();

			// send request to the server to get a list of styles
			sendRequest({
				url: '/vehicle/styles',
				type: 'POST',
				data: {
					make: make,
					model: model,
					year: year
				},
				success: __handleSuccess,
				failure: __handleError
			});

			function __handleSuccess(response) {
				var styles = response.styles;

				// seperate the style_names from the style_ids
				var stylesNames = [];
				$.each(styles, function(index, style) {
					stylesNames.push(style.style_name);
				});

				// sort the style names alphabetically
				stylesNames.sort();

				// then find the styleNames matching ID
				stylesList = [];
				$.each(stylesNames, function(index, styleName) {
					$.each(styles, function(index, style) {
						if (style.style_name == styleName)

							// push the sorted items to the new array
							stylesList.push({
								style_name: styleName,
								style_id: style.style_id
							});
					});
				});

				// loop through the styles and append the options to the style select
				$.each(stylesList, function(index, style) {
					$('<option>').data('styleid', style.style_id).val(style.style_name).text(style.style_name).appendTo($styleSelect);
				});
			}

			function __handleError(e) {
				alert('Unable to retrieve vehicle styles', e);
			}
		}

		function __processSignup() {
			/*var firstName = $signupForm.find('.first-name').val();
				var lastName = $signupForm.find('.last-name').val();
				var email = $signupForm.find('.email').val();
				var password = $signupForm.find('.password').val();*/

			var data = {
				style_id: $styleSelect.find('option:selected').data('styleid')
			};

			if (facebookId) {
				data.fb_id = facebookId;
			}
			else {
				$.extend(data, {
					first_name: $signupForm.find('.first-name').val(),
					last_name: $signupForm.find('.last-name').val(),
					email: $signupForm.find('.email').val(),
					password: $signupForm.find('.password').val()
				});
			}

			sendRequest({
				url: '/access/signup',
				type: 'POST',
				data: data,
				success: __handleSuccess,
				failure: __handleError
			});

			function __handleSuccess(response) {
				$accessPopup.hide();

				if (!response.approved) {
					
					// first hide the login-wrap
					$('.buttons-wrap').hide();

					// then show them the pending review message
					var $pendingMsgDiv = $('.pending-review');

					$pendingMsgDiv.show();
					$pendingMsgDiv.text('Thank you for requesting access to Octane Society. You will recieve an email upon approval of your account.');
				} 
				else {
					// load the home view
					window.location = '/dashboard';
				}
			}

			function __handleError(r) {
				alert(r);
			}
		}

		function __refreshSelects(make, model) {
			if (make) {
				__resetModels();
				__resetYears();
				__resetStyles();

				return;
			}
			else if (model) {
				__resetYears();
				__resetStyles();

				return;
			}
			else {
				__resetStyles();
			}

			function __resetModels() {
				$modelOptions.not(':first').remove();
				$modelOptions.find(':first').prop('selected', true);
			}

			function __resetYears() {
				$yearOptions.not(':first').remove();
				$yearOptions.find(':first').prop('selected', true);
			}

			function __resetStyles() {
				$styleOptions.not(':first').remove();
				$styleOptions.find(':first').prop('selected', true);
			}
		}
	}

	function __processLogin() {
		$('.loading').show();

		var $loginForm = $loginWrap.find('form');
		var email = $loginForm.find('.email').val();
		var password = $loginForm.find('.password').val();

		sendRequest({
			url: '/access/login',
			type: 'POST',
			data: {
				email: email,
				password: password
			},
			success: __handleSuccess,
			failure: __handleError
		});

		function __handleSuccess(response) {
			// hide loading div
			$('.loading').hide();

			// if they are not approved show "pending approval messgae"
			if (!response.approved) {
				
				// first hide the login-wrap
				$('.buttons-wrap').hide();

				// then show them the pending review message
				var $pendingMsgDiv = $('.pending-review');

				$pendingMsgDiv.show();
				$pendingMsgDiv.text('Your request is already in the que. You will recieve an email upon approval of your account.');
			} 
			else {
				// load the home view
				window.location = '/dashboard';
			}
		}

		function __handleError(r) {
			$('.loading').hide();
			alert(r);
		}
	}

	function __showForgotPasswordForm() {
		console.log('Please build me Zach :(');
	}
}

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
	// Full docs on the response object can be found in the documentation
	// for FB.getLoginStatus().
	if (response.status === 'connected' || response.status === 'not_authorized') {
	// Log User into Ocatane Society
		$.ajax({
			url: 'access/facebook/login',
			type: 'POST',
			success: __handleSuccess,
			failure: __handleError
		});

		function __handleSuccess(response) {
			// hide loading div
			$('.loading').hide();

			// if they are not approved show "pending approval messgae"
			if (!response.approved && !response.new_user) {
				// first hide the login-wrap
				$('.buttons-wrap').hide();

				// then show them the pending review message
				var $pendingMsgDiv = $('.pending-review');

				$pendingMsgDiv.show();
				$pendingMsgDiv.text('Your request is already in the que. You will recieve an email upon approval of your account.');
			}
			else if (response.new_user) {
				facebookId = response.fb_id;
				// if they user was just created then they need to add what car they have so show carSelect
				showAccessPopup();
			}
			else {
				// load the home view
				window.location = '/dashboard';	
			}
		}

		function __handleError(r) {
			$('.loading').hide();
			alert('Sorry you did it wrong internally. ' + r);
		}
	} 
	else {
	// The person is not logged into Facebook, so we're not sure if
	// they are logged into this app or not.
		alert("Please login to your Facebook account then try again.");
	}
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function checkLoginState() {
	$('.loading').show();
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function() {
	FB.init({
		appId			: '720657848049692',
		cookie		 : true,	// enable cookies to allow the server to access 
							// the session
		xfbml			: true,	// parse social plugins on this page
		version		: 'v2.2' // use version 2.2
	});
};

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
