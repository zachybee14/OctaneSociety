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

Route::get('/', function() {
	if (Auth::check())
		return Redirect::to('dashboard');
	else
		return Redirect::to('access');
});

Route::group(['prefix' => 'access'], function() {
	Route::get('', 'AccessController@showView');
	
	Route::post('login', 'AccessController@postLogin');
	Route::post('fb-login', 'AccessController@postFbLogin');
	Route::post('register', 'AccessController@postRegister');

	Route::post('forgot-password', 'AccessController@postForgotPassword');

	Route::get('reset-password', 'AccessController@showResetPassword');
	Route::post('reset-password', 'AccessController@postResetPassword');

	Route::get('logout', 'AccessController@logout');
});

Route::group(['prefix' => 'vehicle'], function() {
	Route::get('makes', 'VehicleController@getMakes');
	Route::get('models', 'VehicleController@getModels');
	Route::get('styles', 'VehicleController@getStyles');
});

Route::group(['middleware' => 'auth'], function() {
	Route::get('dashboard', function() {
		return View::make('dashboard');
	});
	
	Route::get('profile', function() {
		return View::make('profile/profile');
	});
	Route::get('garage', function() {
		return View::make('garage/garage-main');
	});
	Route::get('create-article', function() {
		return View::make('pillars/articles-create');
	});
	Route::get('add-friend', function() {
		return View::make('add-freind');
	});
	Route::get('add-company', function() {
		return View::make('add-company');
	});

	Route::group(['prefix' => 'admin'], function() {
		Route::get('request-list', function() {
			return View::make('admin/request-list');
		});
		Route::get('join-requests', 'UsersController@getNonAcceptedUsers');
	});

	Route::group(['prefix' => 'events'], function() {
		Route::get('event/test', 'EventsController@showEventTestPage');
		Route::get('event/{id}', 'EventsController@showEventPage');
		Route::get('list', 'EventsController@getEvents');
		Route::post('create', 'EventsController@createEvent');
	});

	Route::resource('states', 'StatesController');
	Route::resource('cars', 'CarsController');
	Route::resource('bulletin/posts', 'BulletinController');
	Route::resource('articles', 'ArticleController');
	Route::get('article-titles', 'ArticleController@getTitles');
});