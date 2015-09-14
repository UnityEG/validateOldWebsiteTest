<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before( function($request) {
    //
} );


App::after( function($request, $response) {
    //
} );

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter( 'auth', function() {
    
    $input_data = Input::all();
    
        if ( isset($input_data['api_key']) && !empty($input_data['api_key']) ) {
            $user = User::where('api_key', '=', $input_data['api_key'])->first();
            if ( Auth::login($user) ) {
                return Response::json('logged in');
            }
        }//if ( isset($input_data['api']) && !empty($input_data['api']) )
    
    if ( Auth::guest() ) {
        if ( Request::ajax() ) {
            return Response::make( 'Unauthorized', 401 );
        } else {
            return Redirect::guest( 'login' );
        }
    }
} );


Route::filter( 'auth.basic', function() {
    return Auth::basic();
} );

/*
  |--------------------------------------------------------------------------
  | Guest Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter( 'guest', function() {
    if ( Auth::check() )
        return Redirect::to( '/' );
} );

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

Route::filter( 'csrf', function() {
    if ( Session::token() != Input::get( '_token' ) ) {
        throw new Illuminate\Session\TokenMismatchException;
    }
} );

/**
 * Check developer filter
 */
Route::filter('check_developer', function ( ) {
    $user = Auth::user();
    
    if ( 'developer' != $user->user_type ) {
        return Redirect::to('/')->withError("You're not a Developer!");
    }
});

/**
 * Check owner filter
 */
Route::filter( 'check_owner', function ( ) {
    $user = Auth::user();
    
    if ( 'owner' != $user->user_type && 'developer' != $user->user_type ) {
        return Redirect::back()->withError( "You're not owner!" );
    }
} );



/**
 * Admin filters and permissions
 */

Route::filter( 'admin_show_all', 'AdminFilters@showAll' );

Route::filter('admin_show_one', 'AdminFilters@showOne');

Route::filter( 'admin_create', 'AdminFilters@create' );

Route::filter('admin_update', 'AdminFilters@update');

Route::filter( 'admin_delete', 'AdminFilters@delete' );

/**
 * Merchant Managers filters
 */
Route::filter( 'merchant_manager_show_all', 'MerchantManagerFilters@showAll');

Route::filter('merchant_manager_show_one', 'MerchantManagerFilters@showOne');

Route::filter('merchant_manager_create', 'MerchantManagerFilters@create');

Route::filter('merchant_manager_update', 'MerchantManagerFilters@update');

Route::filter('merchant_manager_delete', 'MerchantManagerFilters@delete');

/**
 * Franchisor filters
 */
Route::filter( 'franchisor_show_all', 'FranchisorFilters@showAll' );

Route::filter('franchisor_show_one', 'FranchisorFilters@showOne');

Route::filter('franchisor_create', 'FranchisorFilters@create');

Route::filter('franchisor_update', 'FranchisorFilters@update');

Route::filter('franchisor_delete', 'FranchisorFilters@delete');

/**
 * Supplier filters
 */
Route::filter( 'supplier_show_all', 'SupplierFilters@showAll' );

Route::filter('supplier_show_one', 'SupplierFilters@showOne');

Route::filter('supplier_create', 'SupplierFilters@create');

Route::filter('supplier_update', 'SupplierFilters@update');

Route::filter('supplier_delete', 'SupplierFilters@delete');
/**
 * Merchant filters
 */

Route::filter('merchant_show_all', 'MerchantFilters@showAll');

Route::filter('merchant_show_one', 'MerchantFilters@showOne');

Route::filter('merchant_create', 'MerchantFilters@create');

Route::filter('merchant_update', 'MerchantFilters@update');

Route::filter('merchant_delete', 'MerchantFilters@delete');

/**
 * Customer filters
 */

Route::filter('customer_show_all', 'CustomerFilters@showAll');

Route::filter('customer_show_one', 'CustomerFilters@showOne');

Route::filter('customer_create', 'CustomerFilters@create');

Route::filter('customer_update', 'CustomerFilters@update');

Route::filter('customer_delete', 'CustomerFilters@delete');