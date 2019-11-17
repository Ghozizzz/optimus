<?php

Route::group(['middleware' => 'web', 'prefix' => 'logistic', 'namespace' => 'Modules\LOGISTIC\Http\Controllers'], function()
{
	// SignIn
	Route::get('/signin', ['as' => 'logistic.signin', 'uses' => 'loginController@loginIndex']);
	Route::post('/signin', ['as' => 'logistic.signin', 'uses' => 'loginController@loginProcess']);
	Route::get('/logout', ['as' => 'logistic.logout', 'uses' => 'loginController@loginLogout']);
	Route::get('/', 'LOGISTICController@index');
	Route::get('/portcharge', ['as' => 'logistic.portcharge', 'uses' => 'LOGISTICController@portCharge']);
	Route::post('/portcharge/save',['as' => 'logistic.portcharge.save', 'uses' => 'LOGISTICController@portChargeSave']);
	Route::get('/portcharge/view/{id}', ['as' => 'logistic.portcharge.view', 'uses' => 'LOGISTICController@portChargeView']);
	Route::post('/portcharge/update', ['as' => 'logistic.portcharge.update', 'uses' => 'LOGISTICController@portChargeUpdate']);
	Route::get('/portcharge/delete/{id}', 'LOGISTICController@portChargeDelete');
});