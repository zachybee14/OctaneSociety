<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('shop', 'Shop@showMainView');
Route::get('shop/{category}', 'Shop@showCategoryView');
Route::get('shop/{category}/{product}', 'Shop@showProductView');
Route::get('checkout', 'Shop@showCheckoutView');
Route::get('/', 'Shop@showCheckoutView');*/

/*Route::get('/', function() {
	if (Auth::check())
		return Redirect::to('dashboard');
	else
		return Redirect::to('access');
});*/
Route::group(['prefix' => 'api'], function() {
    Route::post('login', 'Login@login');
    Route::post('login/facebook', 'Login@fbLogin');

    Route::post('forgot-password', 'Login@postForgotPassword');
    Route::post('reset-password', 'Login@postResetPassword');
    Route::get('logout', 'Login@logout');

	Route::group(['prefix' => 'vehicle'], function() {
		Route::get('makes', 'Vehicles@getMakes');
		Route::get('styles', 'Vehicles@getStyles');

		Route::post('selection-log', 'Vehicles@logVehicle');
	});

	//Route::group(['middleware' => 'auth'], function() {
		Route::group(['prefix' => 'admin'], function() {
			Route::get('join-requests', 'Users@getNonAcceptedUsers');
		});

		Route::group(['prefix' => 'events'], function() {
			Route::post('filtered', 'Events@getFilteredEvents');
			Route::post('create', 'Events@createEvent');
			Route::post('{id}/user-status', 'Events@addUserStatus');
			Route::post('{id}/comments', 'Events@addComment');
		});

		Route::group(['prefix' => 'cruises'], function() {
			Route::post('create', 'Cruises@createCruise');
			Route::post('users', 'Cruises@addCruiseUsers');
			Route::post('{id}/comment', 'Cruises@addComment');
		});

		Route::group(['prefix' => 'articles'], function() {
			Route::resource('/', 'Articles');
			Route::get('article-titles', 'Articles@getTitles');
			Route::post('{id}/comment', 'Articles@addComment');
		});
    //});
});

Route::get('', 'SPA@render');
Route::get('spa-templates.js', 'SPA@deliverTemplates');

