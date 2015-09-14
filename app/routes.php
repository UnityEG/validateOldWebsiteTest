<?php

Validator::extend('greater_than', function($attribute, $value, $parameters) {
    $other = Input::get($parameters[0]);
    return isset($other) and intval($value) > intval($other);
});

Validator::extend('greater_than_zero', function($attribute, $value, $parameters) {
    return intval($value) > 0;
});

Validator::extend('greater_than_one', function($attribute, $value, $parameters) {
    return intval($value) > 1;
});

Route::get('test', array('as' => 'test', function() {
        return Auth::user()->timezone;
    }));

/* 
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

/* Route::get('/', function()
  {
  return View::make('hello');
  });
 */


Route::get('GiftVoucher/{id}/VirtualVoucher', array(
    'uses' => 'GiftVoucherController@showVirtualVoucher',
    'as' => 'GiftVoucher.showVirtualVoucher'
));

Route::get('GiftVoucher/{id}/sendMail', array(
    'uses' => 'GiftVoucherController@sendMail',
    'as' => 'GiftVoucher.sendMail'
));

Route::get('GiftVoucher/{id}/sendMailToCustomer', array(
    'uses' => 'GiftVoucherController@sendMailToCustomer',
    'as' => 'GiftVoucher.sendMailToCustomer'
));

Route::get('GiftVoucher/{id}/sendMailToBeneficiary', array(
    'uses' => 'GiftVoucherController@sendMailToBeneficiary',
    'as' => 'GiftVoucher.sendMailToBeneficiary'
));

Route::any('contact', array(
    'uses' => 'ContactController@index',
    'as' => 'ContactController.index'
));


Route::get('GiftVoucher/search', array(
    'uses' => 'GiftVoucherController@search',
    'as' => 'GiftVoucher.search'
));

Route::post('GiftVoucher/result', array(
    'uses' => 'GiftVoucherController@result',
    'as' => 'GiftVoucher.result'
));

Route::post('GiftVoucher/status/{GiftVoucher}', array(
    'uses' => 'GiftVoucherController@status',
    'as' => 'GiftVoucher.status'
));
Route::post('GiftVoucherValidation/create', array(
    'uses' => 'GiftVoucherValidationController@create',
    'as' => 'GiftVoucherValidation.create'
));
//
Route::get('GiftVoucher/create/{gift_vouchers_parameters_id}', array(
    'uses' => 'GiftVoucherController@create',
    'as' => 'GiftVoucher.create'
));
//
// routes for Cart =============================================================
Route::get('Cart/AddedToCart', array(
    'uses' => 'CartController@AddedToCart',
    'as' => 'Cart.AddedToCart'
));
//
Route::get('Cart/createGiftVoucherAndClearCart', array(
    'uses' => 'CartController@createGiftVoucherAndClearCart',
    'as' => 'Cart.createGiftVoucherAndClearCart'
));
//
// routes for processing PayPal checkout =======================================
/*
Route::controller('payment', 'PaypalController', array(
    'postPayment' => 'payment',
));
*/

Route::post('payment/post', array(
    'as' => 'payment.post',
    'uses' => 'PaypalController@postPayment',
));




// this is after make the payment, PayPal redirect back to your site
/*
Route::controller('payment/status', 'PaypalController', array(
    'getPaymentStatus' => 'payment.status',
));
*/

Route::get('payment/status', array(
    'as' => 'payment.status',
    'uses' => 'PaypalController@getPaymentStatus',
));

//
// =============================================================================

//GiftVouchersParameter search
Route::get('GiftVouchersParameters/ajaxSearch', array(
    'uses' => 'GiftVouchersParameterController@ajaxSearch',
    'as' => 'GiftVouchersParameters.ajaxSearch'
));

//GiftVouchersParameter gallery
Route::get('GiftVouchersParameters/gallery', array(
    'uses' => 'GiftVouchersParameterController@gallery',
    'as' => 'GiftVouchersParameters.gallery'
));

//GiftVouchersParameter gallery
Route::post('UseTerms/UpdateListOrder', array(
    'uses' => 'UseTermController@UpdateListOrder',
    'as'   => 'UseTermController.UpdateListOrder'
));

Route::resource('users', 'UserController');
Route::resource('GiftVouchersParameters', 'GiftVouchersParameterController');
Route::resource('GiftVoucherValidation', 'GiftVoucherValidationController');
Route::resource('GiftVoucher', 'GiftVoucherController',array('except' => array('create')));
Route::resource('UseTerms', 'UseTermController');
Route::resource('Industrys', 'IndustryController');
Route::resource('Cart', 'CartController');

Route::get('/', array(
    'uses' => 'HomeController@showWelcome',
    'as' => 'myHome'
));


/**
 * public routes
 */
//Login section
Route::get('facebook/login', array(
    'uses' => 'LoginSteps@facebookLogin',
    'as' => 'login.facebook'
));

Route::get('login/face', array(
    'uses' => 'LoginSteps@triggerFacebookLogin',
    'as' => 'facebook.trigger'
));

Route::controller('login', 'LoginSteps');

Route::get('logout', array('as' => 'logout', function() {
        Auth::logout();

        // logout users that have logged in with facebook by removing session credentials
        Session::forget('facebook_user');
        return Redirect::to('/login');
    }));

//    Facebook application
Route::resource('facebook', 'FacebookController');

//Merchant public section


Route::get('merchant/create', array(
    'uses' => 'MerchantController@create',
    'as' => 'merchant.create'
));

Route::get('merchant/{id}', array(
    'before' => 'auth',
    'uses' => 'MerchantController@show',
    'as' => 'merchant.show'
));

Route::post('merchant', array(
    'uses' => 'MerchantController@store',
    'as' => 'merchant.store'
));

// partner page ( part of merchants section)
Route::get('validatepartners', array(
    'uses' => 'MerchantController@partners',
    'as' => 'merchant.partners'
));



//Static Pages/////////////////////////////////////
Route::get('home', array(
    'uses' => 'HomeController@showWelcome',
    'as' => 'myHome'
));

Route::get('contact', function() {
    return View::make('contact');
});

// Vault Public Page
Route::get('vault', function() {
    return View::make('vault');
});

// Customer My Vault
Route::get('myvault', array(
    'uses' => 'GiftVoucherController@myVault',
    'as' => 'GiftVoucher.myVault'
));
//    return View::make('GiftVoucher/vault');

// Customer Vault History
Route::get('vaulthistory',array(
    'uses' => 'GiftVoucherController@vaultHistory',
    'as' => 'GiftVoucher.vaultHistory'
));

// NO NEED
////vault details
//Route::get('vaultdetails', function() {
//    return View::make('GiftVoucher/vaultdetails');
//});



//Customer public section
Route::get('customer/create', array(
    'uses' => 'CustomerController@create',
    'as' => 'customer.create'
));

Route::get('customer/{id}', array(
    'before' => 'auth',
    'uses' => 'CustomerController@show',
    'as' => 'customer.show'
));

Route::post('customer', array(
    'uses' => 'CustomerController@store',
    'as' => 'customer.store'
));

//Password reset route
Route::controller('password', 'RemindersController');


/**
 * private routes
 */
Route::group(['before' => 'auth|https'], function() {

    Route::get('user', array(
        'uses' => 'UserController@index',
        'as' => 'user.index'
    ));

    Route::resource('developer', 'DeveloperController');

    Route::resource('owner', 'OwnerController');

    Route::resource('admin', 'AdminController');

//    Rules Individual user
    Route::get('rules/user/{id}', array(
        'uses' => 'RulesController@rulesUser',
        'as' => 'rules.user'
    ));
    
//    Rules Update Individual user
    Route::put('rules/user/{id}', array(
        'uses' => 'RulesController@updateRulesUser',
        'as' => 'rules.user_update'
    ));

    Route::resource('rules', 'RulesController');

    Route::resource('merchant_manager', 'MerchantManagerController');

    Route::resource('franchisor', 'FranchisorController');

    Route::resource('supplier', 'SupplierController');

    Route::resource('userpics', 'UserPicController');

//    Merchant section


    Route::get('merchant', array(
        'uses' => 'MerchantController@index',
        'as' => 'merchant.index'
    ));

    Route::get('merchant/{id}/edit', array(
        'uses' => 'MerchantController@edit',
        'as' => 'merchant.edit'
    ));

    Route::put('merchant/{id}', array(
        'uses' => 'MerchantController@update',
        'as' => 'merchant.update'
    ));

    Route::delete('merchant/{id}', array(
        'uses' => 'MerchantController@destroy',
        'as' => 'merchant.destroy'
    ));
//    Customer section


    Route::get('customer', array(
        'uses' => 'CustomerController@index',
        'as' => 'customer.index'
    ));


    Route::get('customer/{id}/edit', array(
        'uses' => 'CustomerController@edit',
        'as' => 'customer.edit'
    ));



    Route::put('customer/{id}', array(
        'uses' => 'CustomerController@update',
        'as' => 'customer.update'
    ));

    Route::delete('customer/{id}', array(
        'uses' => 'CustomerController@destroy',
        'as' => 'customer.destroy'
    ));

//    Reports section
    
//    Owner reports
    
    Route::get('reports/owner', array(
        'uses' => 'ReportController@ownerReportIndex',
        'as' => 'reports.owner'
    ));
    
    Route::get('reports/owner/sales', array(
        'uses' => 'ReportController@ownerReportSales',
        'as' => 'reports.owner.sales'
    ));

    Route::get('reports/owner/sales/hourly', array(
        'uses' => 'ReportController@ownerReportSalesHourly',
        'as' => 'reports.owner.sales.hourly'
    ));
    
//    Merchant reports
    Route::get('reports/merchant/{id}', array(
        'uses'=>'ReportController@merchantReportIndex',
        'as' => 'reports.merchant'
    ));
    
    Route::get('reports/merchant/{id}/active_vouchers', array(
        'uses' => 'ReportController@merchantReportActiveVouchers',
        'as' => 'reports.merchant.active_vouchers'
    ));

    Route::get('reports/merchant/{id}/active_vouchers_json', array(
        'uses' => 'ReportController@merchantReportActiveVouchersJson',
        'as' => 'reports.merchant.active_vouchers_json'
    ));

    Route::get('reports/merchant/{id}/validations', array(
        'uses'=> 'ReportController@merchantReportValidations',
        'as' => 'reports.merchant.validations'
    ));

}); //Route::group( ['before' => 'auth|https' ], function() {


//test DealVouchersParameters views
Route::get('DealVouchersParameters/create', function()
  {
  return View::make('DealVouchersParameters/create');
  });
