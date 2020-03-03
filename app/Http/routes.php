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
Route::get('/testemail', ['as' => 'front.testemail', 'uses' => 'HomeController@testemail']);
Route::get('/', ['as' => 'front.home', 'uses' => 'HomeController@index']);
Route::get('/about', ['as' => 'front.about', 'uses' => 'HomeController@about']);
Route::get('/gallery', ['as' => 'front.gallery', 'uses' => 'HomeController@gallery']);
Route::get('/review/{country_code?}/{make?}/{model?}', ['as' => 'front.review', 'uses' => 'HomeController@review']);
Route::get('/term', ['as' => 'front.term', 'uses' => 'HomeController@term']);
Route::get('/contact', ['as' => 'front.contact', 'uses' => 'HomeController@contact']);
Route::get('/product-detail/{id?}', ['as' => 'front.productdetail', 'uses' => 'HomeController@productDetail']);
Route::post('/wishlist', ['as' => 'front.wishlist', 'uses' => 'HomeController@wishlist']);
Route::get('/negotiate/{car_id?}', ['as' => 'front.negotiate', 'uses' => 'HomeController@negotiate']);

Route::post('/negotiation/cancel', ['as' => 'front.cancelNegotiation', 'uses' => 'HomeController@cancelNegotiation']);
Route::get('/notify/{car_id}', ['as' => 'front.notify', 'uses' => 'HomeController@notify']);

Route::post('/checklogin', ['as' => 'front.checklogin', 'uses' => 'HomeController@checkLogin']);
Route::post('/signup', ['as' => 'front.signup', 'uses' => 'HomeController@signup']);
Route::get('/signup-verification/{email?}', ['as' => 'front.signupVerification', 'uses' => 'HomeController@signupVerification']);

Route::post('/checkloginseller', ['as' => 'front.checkloginseller', 'uses' => 'HomeController@checkLoginseller']);
Route::post('/signupseller', ['as' => 'front.signupseller', 'uses' => 'HomeController@signupseller']);
Route::get('/signup-verification/{email?}', ['as' => 'front.signupVerificationseller', 'uses' => 'HomeController@signupVerificationseller']);

Route::get('/refresh-captcha', ['as' => 'front.refreshCaptcha', 'uses' => 'HomeController@refreshCaptcha']);
Route::post('/get-car-model', ['as' => 'front.getCarModel', 'uses' => 'HomeController@getCarModel']);
Route::post('/get-car', ['as' => 'front.getCar', 'uses' => 'HomeController@getCar']);

Route::post('/reset-password', ['as' => 'front.resetPassword', 'uses' => 'HomeController@resetPassword']);
Route::get('/reset-password-customer/{email}', ['as' => 'front.resetPasswordCustomer', 'uses' => 'HomeController@resetPasswordCustomer']);
Route::get('/reset-password-seller/{email}', ['as' => 'front.resetPasswordSeller', 'uses' => 'HomeController@resetPasswordSeller']);
Route::post('/update-password', ['as' => 'front.updatePassword', 'uses' => 'HomeController@updatePassword']);

Route::get('/customer/dashboard', ['as' => 'customer.customerDashboard', 'uses' => 'DashboardController@index']);
Route::get('/customer/negotiation/{id}', ['as' => 'customer.negotiation', 'uses' => 'DashboardController@negotiation']);
Route::get('/customer/logout', ['as' => 'customer.logout', 'uses' => 'HomeController@logout']);
Route::get('/customer/negotiationlist', ['as' => 'customer.negotiationlist', 'uses' => 'DashboardController@negotiationlist']);
Route::get('/customer/orderlist', ['as' => 'customer.orderlist', 'uses' => 'DashboardController@orderlist']);
Route::get('/customer/wishlist', ['as' => 'customer.wishlist', 'uses' => 'DashboardController@wishlist']);
Route::get('/customer/account', ['as' => 'customer.account', 'uses' => 'DashboardController@account']);
Route::post('/customer/save-account', ['as' => 'customer.saveAccount', 'uses' => 'DashboardController@saveAccount']);
Route::post('/customer/negotiate', ['as' => 'customer.negotiate', 'uses' => 'DashboardController@negotiate']);
Route::post('/customer/create-negotiate', ['as' => 'customer.createNegotiate', 'uses' => 'DashboardController@createNegotiate']);
Route::post('/customer/send-chat', ['as' => 'customer.sendChat', 'uses' => 'DashboardController@sendChat']);
Route::post('/customer/get-chat', ['as' => 'customer.getChat', 'uses' => 'DashboardController@getChat']);
Route::post('/customer/get-port', ['as' => 'front.getDestinationPort', 'uses' => 'HomeController@getDestinationPort']);
Route::post('/customer/save-comment', ['as' => 'customer.saveComment', 'uses' => 'DashboardController@saveComment']);
Route::post('/customer/get-tracking', ['as' => 'customer.getTracking', 'uses' => 'DashboardController@getTracking']);
Route::post('/customer/save-rating', ['as' => 'customer.saveRating', 'uses' => 'DashboardController@saveRating']);

Route::get('/customer/invoice/{id}', ['as' => 'customer.invoice', 'uses' => 'DashboardController@invoice']);
Route::get('/customer/create-invoice', ['as' => 'customer.createInvoice', 'uses' => 'DashboardController@createInvoice']);
Route::get('/customer/track-invoice', ['as' => 'customer.trackInvoice', 'uses' => 'DashboardController@trackInvoice']);
Route::get('/customer/document-copies', ['as' => 'customer.documentCopies', 'uses' => 'DashboardController@documentCopies']);
Route::get('/customer/original-document', ['as' => 'customer.originalDocument', 'uses' => 'DashboardController@originalDocument']);
  
  
Route::get('/customer/proforma-invoice/{id}', ['as' => 'customer.proformainvoice', 'uses' => 'DashboardController@proformainvoice']);
Route::get('/customer/edit-invoice/{id}', ['as' => 'customer.editproformainvoice', 'uses' => 'DashboardController@cancelProformaInvoice']);
Route::get('/customer/create-proforma-invoice/{negotiation_id}', ['as' => 'customer.save-proformainvoice', 'uses' => 'DashboardController@createProformaInvoice']);
Route::get('/customer/receive-item/{invoice_id}', ['as' => 'customer.receivedItem', 'uses' => 'DashboardController@receiveItem']);

Route::post('/customer/negotiation-status', ['as' => 'customer.negotiationstatus', 'uses' => 'DashboardController@negotiationStatus']);
Route::post('/customer/save-proforma-invoice', ['as' => 'customer.saveProformaInvoice', 'uses' => 'DashboardController@saveProformaInvoice']);
Route::post('/customer/update-invoice', ['as' => 'customer.updateInvoice', 'uses' => 'DashboardController@updateInvoice']);

Route::post('/customer/send-contact', ['as' => 'customer.sendContact', 'uses' => 'HomeController@sendContact']);
Route::post('/customer/get-data', ['as' => 'customer.getCustomerData', 'uses' => 'DashboardController@getCustomerData']);
Route::post('/customer/upload-photo', ['as' => 'customer.uploadPhoto', 'uses' => 'DashboardController@uploadPhoto']);

Route::post('/customer/payment-confirmation-save', ['as' => 'customer.paymentConfirmationSave', 'uses' => 'DashboardController@paymentConfirmationSave']);
Route::get('/customer/payment-confirmation/{invoice_id}', ['as' => 'customer.paymentConfirmation', 'uses' => 'DashboardController@paymentConfirmation']);

Route::get('/report/proforma-invoice', ['as'=>'report.proformaInvoice','uses'=>'PDFGeneratorController@proformaInvoiceReport']);
Route::get('/report/invoice', ['as'=>'report.invoice','uses'=>'PDFGeneratorController@invoiceReport']);
Route::get('/session', ['as'=>'session','uses'=>'DashboardController@session']);

Route::get('/test', ['as'=>'test','uses'=>'HomeController@test']);


