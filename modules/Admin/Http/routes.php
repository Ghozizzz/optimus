<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
// SignIn
	Route::get('/signin', ['as' => 'admin.signin', 'uses' => 'loginController@loginIndex']);
	Route::post('/signin', ['as' => 'admin.signin', 'uses' => 'loginController@loginProcess']);
	Route::get('/logout', ['as' => 'admin.logout', 'uses' => 'loginController@loginLogout']);
});

Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
	Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'AdminController@index']);
	/*Dealer*/
  Route::get('/dealer', ['as' => 'admin.dealer', 'uses' => 'AdminController@dealerIndex']);
	Route::post('/dealer/save',['as' => 'admin.dealer.save', 'uses' => 'AdminController@dealerSave']);
	Route::get('/dealer/view/{id}', ['as' => 'admin.dealer.view', 'uses' => 'AdminController@dealerView']);
	Route::post('/dealer/update/{id}', 'AdminController@dealerUpdate');
	Route::get('/dealer/delete/{id}', 'AdminController@dealerDelete');
  
  /* review */
  Route::get('/reviewlist', ['as' => 'admin.reviewlist', 'uses' => 'AdminController@reviewIndex']);
  Route::post('/review/update', ['as' => 'admin.reviewUpdate', 'uses' => 'AdminController@reviewUpdate']);
  
	/*Customer*/
	Route::get('/customer', ['as' => 'admin.customer', 'uses' => 'AdminController@customerIndex']);
	Route::post('/customer/save',['as' => 'admin.customer.save', 'uses' => 'AdminController@customerSave']);
	Route::get('/customer/view/{id}', ['as' => 'admin.customer.view', 'uses' => 'AdminController@customerView']);
	Route::post('/customer/update/{id}', 'AdminController@customerUpdate');
	Route::get('/customer/delete/{id}', 'AdminController@customerDelete');
  /*banner*/
  Route::get('/banner', ['as' => 'admin.banner', 'uses' => 'AdminController@bannerIndex']);
	Route::post('/banner/save',['as' => 'admin.banner.save', 'uses' => 'AdminController@bannerSave']);
	Route::get('/banner/view/{id}', ['as' => 'admin.banner.view', 'uses' => 'AdminController@bannerView']);  \
  Route::post('/banner/update/{id}', 'AdminController@bannerUpdate');
	Route::get('/banner/delete/{id}', 'AdminController@bannerDelete');
	/*Sales*/
	Route::get('/sales', ['as' => 'admin.sales', 'uses' => 'AdminController@salesIndex']);
	Route::post('/sales/save',['as' => 'admin.sales.save', 'uses' => 'AdminController@salesSave']);
	Route::get('/sales/view/{id}', ['as' => 'admin.sales.view', 'uses' => 'AdminController@salesView']);
	Route::post('/sales/update/{id}', 'AdminController@salesUpdate');
	Route::get('/sales/delete/{id}', 'AdminController@salesDelete');
	/*Logistic*/
	Route::get('/logistic', ['as' => 'admin.logistic', 'uses' => 'AdminController@logisticIndex']);
	Route::post('/logistic/save',['as' => 'admin.logistic.save', 'uses' => 'AdminController@logisticSave']);
	Route::get('/logistic/view/{id}', ['as' => 'admin.logistic.view', 'uses' => 'AdminController@logisticView']);
	Route::post('/logistic/update/{id}', 'AdminController@logisticUpdate');
	Route::get('/logistic/delete/{id}', 'AdminController@logisticDelete');
	/*Finance*/
	Route::get('/user', ['as' => 'admin.user', 'uses' => 'AdminController@userIndex']);
	Route::post('/user/save',['as' => 'admin.user.save', 'uses' => 'AdminController@userSave']);
	Route::get('/user/view/{id}', ['as' => 'admin.user.view', 'uses' => 'AdminController@userView']);
	Route::post('/user/update/{id}', 'AdminController@userUpdate');
	Route::get('/user/delete/{id}', 'AdminController@userDelete');
	/*Car*/
	Route::get('/car', ['as' => 'admin.car', 'uses' => 'AdminController@carIndex']);
	Route::post('/car/save',['as' => 'admin.car.save', 'uses' => 'AdminController@carSave']);
	Route::get('/car/view/{id}', ['as' => 'admin.car.view', 'uses' => 'AdminController@carView']);
	Route::post('/car/update/{id}', 'AdminController@carUpdate');
	Route::get('/car/delete/{id}', 'AdminController@carDelete');
	Route::get('/car/unavailable/{id}', 'AdminController@carUnavailable');
	Route::get('/car/available/{id}', 'AdminController@carAvailable');
  Route::post('/car-list', ['as' => 'admin.carList', 'uses' => 'AdminController@carList']);
  Route::post('/car-chasis-validation', ['as' => 'admin.chasisValidation', 'uses' => 'AdminController@checkChasis']);
  Route::get('/car-chasis-validation-test/{vin?}', ['as' => 'admin.chasisValidation2', 'uses' => 'AdminController@checkChasis2']);
  
  Route::post('car/upload', ['as' => 'dropzone.uploadfile', 'uses' =>'AdminController@dropzoneUploadFile']);
  Route::post('car/upload/delete', ['as' => 'dropzone.uploadRemove', 'uses' =>'AdminController@deleteUpload']);
  
  Route::post('car/delete-picture', ['as' => 'admin.carDelete', 'uses' =>'AdminController@deleteCarPicture']);  
  Route::post('upload', ['as' => 'upload-post', 'uses' =>'AdminController@postUpload']);
  Route::post('upload/delete', ['as' => 'upload-remove', 'uses' =>'AdminController@deleteUpload']);
  
  Route::post('rating/delete', ['as' => 'admin.deleteRating', 'uses' =>'AdminController@deleteRating']);
  Route::post('recommendation', ['as' => 'admin.recommendation', 'uses' =>'AdminController@recommendation']);

  Route::post('/submit-resi', ['as' => 'admin.submitResi', 'uses' => 'AdminController@submitResi']);
  
  /*port destination*/
  Route::get('/port-destination', ['as' => 'admin.portDestination', 'uses' => 'AdminController@portDestinationIndex']);
	Route::post('/port-destination/save',['as' => 'admin.portDestination.save', 'uses' => 'AdminController@portDestinationSave']);
	Route::get('/port-destination/view/{id}', ['as' => 'admin.portDestination.view', 'uses' => 'AdminController@portDestinationView']);
	Route::post('/port-destination/update/{id}', 'AdminController@portDestinationUpdate');
	Route::get('/port-destination/delete/{id}', 'AdminController@portDestinationDelete');
  
  /*port disharge*/
  Route::get('/port-discharge', ['as' => 'admin.portDischarge', 'uses' => 'AdminController@portDischargeIndex']);
	Route::post('/port-discharge/save',['as' => 'admin.portDischarge.save', 'uses' => 'AdminController@portDischargeSave']);
	Route::get('/port-discharge/view/{id}', ['as' => 'admin.customer.view', 'uses' => 'AdminController@portDischargeView']);
	Route::post('/port-discharge/update/{id}', 'AdminController@portDischargeUpdate');
	Route::get('/port-discharge/delete/{id}', 'AdminController@portDischargeDelete');
  
  /*country*/
  Route::get('/country', ['as' => 'admin.country', 'uses' => 'AdminController@countryIndex']);
	Route::post('/country/save',['as' => 'admin.country.save', 'uses' => 'AdminController@countrySave']);
	Route::get('/country/view/{id}', ['as' => 'admin.country.view', 'uses' => 'AdminController@countryView']);
	Route::post('/country/update/{id}', 'AdminController@countryUpdate');
	Route::get('/country/delete/{id}', 'AdminController@countryDelete');
  
		/*Car Model*/
		Route::get('/carmodel', ['as' => 'admin.carmodel', 'uses' => 'AdminController@carModelIndex']);
		Route::post('/carmodel/save',['as' => 'admin.carmodel.save', 'uses' => 'AdminController@carModelSave']);
		Route::get('/carmodel/view/{id}', ['as' => 'admin.carmodel.view', 'uses' => 'AdminController@carModelView']);
		Route::post('/carmodel/update/{id}', 'AdminController@carModelUpdate');
		Route::get('/carmodel/delete/{id}', 'AdminController@carModelDelete');
		/*Car Color*/
		Route::get('/carcolor', ['as' => 'admin.carcolor', 'uses' => 'AdminController@carColorIndex']);
		Route::post('/carcolor/save',['as' => 'admin.carcolor.save', 'uses' => 'AdminController@carColorSave']);
		Route::get('/carcolor/view/{id}', ['as' => 'admin.carcolor.view', 'uses' => 'AdminController@carColorView']);
		Route::post('/carcolor/update/{id}', 'AdminController@carColorUpdate');
		Route::get('/carcolor/delete/{id}', 'AdminController@carColorDelete');
    /*Car Color*/
		Route::get('/cartype', ['as' => 'admin.cartype', 'uses' => 'AdminController@carTypeIndex']);
		Route::post('/cartype/save',['as' => 'admin.cartype.save', 'uses' => 'AdminController@carTypeSave']);
		Route::get('/cartype/view/{id}', ['as' => 'admin.cartype.view', 'uses' => 'AdminController@carTypeView']);
		Route::post('/cartype/update/{id}', 'AdminController@carTypeUpdate');
		Route::get('/cartype/delete/{id}', 'AdminController@carTypeDelete');
    /*Car Accessories*/
		Route::get('/accessories', ['as' => 'admin.accessories', 'uses' => 'AdminController@accessoriesIndex']);
		Route::post('/accessories/save',['as' => 'admin.accessories.save', 'uses' => 'AdminController@accessoriesSave']);
		Route::get('/accessories/view/{id}', ['as' => 'admin.accessories.view', 'uses' => 'AdminController@accessoriesView']);
		Route::post('/accessories/update/{id}', 'AdminController@accessoriesUpdate');
		Route::get('/accessories/delete/{id}', 'AdminController@accessoriesDelete');
		/*Car Make*/
		Route::get('/carmake', ['as' => 'admin.carmake', 'uses' => 'AdminController@carMakeIndex']);
		Route::post('/carmake/save',['as' => 'admin.carmake.save', 'uses' => 'AdminController@carMakeSave']);
		Route::get('/carmake/view/{id}', ['as' => 'admin.carmake.view', 'uses' => 'AdminController@carMakeView']);
		Route::post('/carmake/update/{id}', 'AdminController@carMakeUpdate');
		Route::get('/carmake/delete/{id}', 'AdminController@carMakeDelete');
		/*Car Engine*/
		Route::get('/carengine', ['as' => 'admin.carengine', 'uses' => 'AdminController@carEngineIndex']);
		Route::post('/carengine/save',['as' => 'admin.carengine.save', 'uses' => 'AdminController@carEngineSave']);
		Route::get('/carengine/view/{id}', ['as' => 'admin.carengine.view', 'uses' => 'AdminController@carEngineView']);
		Route::post('/carengine/update/{id}', 'AdminController@carEngineUpdate');
		Route::get('/carengine/delete/{id}', 'AdminController@carEngineDelete');
	/*Negotiation*/
	Route::get('/negotiation', ['as' => 'admin.negotiation', 'uses' => 'AdminController@negotiationIndex']);
  Route::get('/orderlist', ['as' => 'admin.orderlist', 'uses' => 'AdminController@orderlistIndex']);
	Route::get('/negotiation/viewsales/{id}', ['as' => 'admin.negotiation.viewsales', 'uses' => 'AdminController@negotiationViewSales']);
	Route::get('/negotiation/view/{id}', ['as' => 'admin.negotiation.view', 'uses' => 'AdminController@negotiationView']);
  Route::get('/negotiation-status/{id}/{status}', ['as' => 'admin.changeNegotiationStatus', 'uses' => 'AdminController@negotiationChangeStatus']);
  Route::post('/negotiation-deal', ['as' => 'admin.negotiationDeal', 'uses' => 'AdminController@negotiationDeal']);
	Route::post('/negotiation/reply/{id_nego}/{id_customer}', 'AdminController@negotiationReplyChat');
	Route::post('/negotiation/update/{id}', 'AdminController@negotiationUpdate');
  Route::get('/negotiation/document-copies', ['as' => 'admin.documentCopies', 'uses' => 'AdminController@documentCopies']);
  Route::post('/negotiation/document-upload', ['as' => 'admin.documentUpload', 'uses' => 'AdminController@documentUpload']);
  Route::get('/negotiation/original-document', ['as' => 'admin.originalDocument', 'uses' => 'AdminController@originalDocument']);
  Route::post('/negotiation/original-document-submit', ['as' => 'admin.documentOriginalSubmit', 'uses' => 'AdminController@documentOriginalSubmit']);
  
  /* proforma invoice */
  Route::get('/negotiation/proforma-invoice/{id}', ['as' => 'admin.proformainvoice', 'uses' => 'AdminController@proformainvoice']);
  Route::get('/negotiation/edit-invoice/{id}', ['as' => 'admin.editproformainvoice', 'uses' => 'AdminController@cancelProformaInvoice']);
  Route::get('/negotiation/create-proforma-invoice/{negotiation_id}', ['as' => 'admin.save-proformainvoice', 'uses' => 'AdminController@createProformaInvoice']);
  Route::post('/negotiation/save-proforma-invoice', ['as' => 'admin.saveProformaInvoice', 'uses' => 'AdminController@saveProformaInvoice']);
  Route::post('/negotiation/update-invoice', ['as' => 'admin.updateInvoice', 'uses' => 'AdminController@updateInvoice']);

  Route::get('/negotiation/invoice/{id}', ['as' => 'admin.negotiationInvoice', 'uses' => 'AdminController@invoice']);
  Route::get('/negotiation/create-invoice', ['as' => 'admin.createInvoice', 'uses' => 'AdminController@createInvoice']);
  Route::get('/negotiation/track-invoice', ['as' => 'admin.trackInvoice', 'uses' => 'AdminController@trackInvoice']);

  Route::post('/negotiation/payment-confirmation-save', ['as' => 'admin.paymentConfirmationSave', 'uses' => 'AdminController@paymentConfirmationSave']);
  Route::get('/negotiation/payment-confirmation/{invoice_id}', ['as' => 'admin.paymentConfirmation', 'uses' => 'AdminController@paymentConfirmation']);
  Route::get('/negotiation/receive-item/{invoice_id}', ['as' => 'admin.receivedItem', 'uses' => 'AdminController@receiveItem']);
  Route::post('/negotiation/approve-proforma-invoice', ['as' => 'admin.approveProformaInvoice', 'uses' => 'AdminController@approveProformaInvoice']);
  
	/*Invoice*/
	Route::get('/invoice', ['as' => 'admin.invoice', 'uses' => 'AdminController@invoiceIndex']);
	Route::post('/invoice/save',['as' => 'admin.invoice.save', 'uses' => 'AdminController@invoiceSave']);
	Route::get('/invoice/view/{id}', ['as' => 'admin.invoice.view', 'uses' => 'AdminController@invoiceView']);
	Route::post('/invoice/update', 'AdminController@invoiceUpdate');
	Route::get('/invoice/delete/{id}', 'AdminController@invoiceDelete');
	Route::post('/invoice/extend-date', ['as' => 'admin.extendDate', 'uses' => 'AdminController@extendDate']);

	/*Config*/
	Route::get('/setting', ['as' => 'admin.setting', 'uses' => 'AdminController@settingIndex']);
	Route::get('/setting/view/{id}', ['as' => 'admin.setting.view', 'uses' => 'AdminController@settingView']);
	Route::post('/setting/update/{id}',['as' => 'admin.setting.save', 'uses' => 'AdminController@settingSave']);
	
		
}); 