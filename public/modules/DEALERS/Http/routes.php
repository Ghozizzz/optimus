<?php

Route::group(['middleware' => 'web', 'prefix' => 'dealers', 'namespace' => 'Modules\Dealers\Http\Controllers'], function()
{
	Route::get('/', 'DEALERSController@index');
	// SignIn
	Route::get('/signin', ['as' => 'dealers.signin', 'uses' => 'loginController@loginIndex']);
	Route::post('/signin', ['as' => 'dealers.signin', 'uses' => 'loginController@loginProcess']);
	Route::get('/logout', ['as' => 'dealers.logout', 'uses' => 'loginController@loginLogout']);
	//Car
	Route::get('/car', ['as' => 'dealers.car', 'uses' => 'DEALERSController@carIndex']);
	Route::get('/car/view/{id}', ['as' => 'dealers.car.view', 'uses' => 'DEALERSController@carView']);
	//Negotiation
	Route::get('/negotiation', ['as' => 'dealers.negotiation', 'uses' => 'DEALERSController@negotiationIndex']);
	Route::get('/negotiation-car/{car_id?}', ['as' => 'dealers.negotiationCar', 'uses' => 'DEALERSController@negotiationCar']);
});