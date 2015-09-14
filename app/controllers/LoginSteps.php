<?php

class LoginSteps extends \BaseController {

    public function __construct() {
        $this->beforeFilter( 'csrf|https', ['on' => 'post' ] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex() {

        if ( Auth::check() ) {

            $user = Auth::user();

            if ( ($user->active == true ) ) {
                return Redirect::intended( '/' );
            }//if ( ($user->active == true ) ) 
            elseif ( $user->active == false ) {
                return View::make( 'notactivated' )->with( 'error', 'sorry your account is not activated' );
            }//elseif ( $user->active == false )
        }//if ( Auth::check() )
        return View::make( 'login' );
    }

    /**
     * authenticate user with e-mail, password and just active users
     *
     * @return Response
     */
    public function postIndex() {

//        check about remember me button checked
        $remember_me = (Input::get( 'remember_me' ) == 'on') ? true : false;

        $email    = Input::get( 'email' );
        $password = Input::get( 'password' );

        if ( Auth::attempt( array( 'email' => $email, 'password' => $password ), $remember_me ) ) {

            $user = Auth::user();

            if ( ($user->active == true ) ) {

                return Redirect::intended( '/' );
            } //if ( ($user->active == true ) )
            elseif ( $user->active == false ) {
                return View::make( 'notactivated' )->with( 'error', 'sorry your account is not activated' );
            }//elseif ( $user->active == false )
        }//if ( Auth::attempt( array( 'email' => $email, 'password' => $password ), $remember_me ) ) 
        else {
            return Redirect::back()->withInput()->with( 'error', 'Invalid E-mail or Password.' );
        }
    }

    /**
     * start logging with facebook API
     * @return url
     */
    public function triggerFacebookLogin() {
        return Redirect::to( Facebook::getLoginUrl() );
    }

    /**
     * dealing with the object that return back from Facebook
     * It's the method that responsible for dealing with the information coming from facebook and the URI in the Facebook application settings in developer.facebook.com refer to here
     * @return object
     */
    public function facebookLogin() {

//        get token from redirected url
        try {
            $token = Facebook::getTokenFromRedirect();

            if ( !$token ) {
                return Redirect::to( '/' )->withError( 'Unable to obtain access token!' );
            }
        } catch ( FacebookQueryBuilderException $e ) {
            return Redirect::to( '/' )->with( 'error', $e->getPrevious()->getMessage() );
        }

        if ( !$token->isLongLived() ) {
            try {
                $token = $token->extend();
            } catch ( FacebookQueryBuilderException $e ) {
                return Redirect::to( '/' )->withError( $e->getPrevious()->getMessage() );
            }
        }//if ( !$token->isLongLived() )
//        set Access Token in Session as Session::put('facebook_access_token', (string)$token)
        Facebook::setAccessToken( $token );

//        get profile information from facebook account through object and store it in a session
        try {
            $facebook_user           = Facebook::object( 'me' )->fields( 'id', 'name' )->get();
            $user                    = new stdClass;
            $user->facebook_username = $facebook_user->get( 'name' );
            $user->facebook_userid   = $facebook_user->get( 'id' );

            Session::put( 'facebook_user', $user );
            if ( Auth::user() ) {
                return Redirect::route( 'customer.edit', Auth::user()->customer->id )->withMessage( 'Welcome, You have logged in with Facebook account.' );
            }//if ( Auth::user() )

            $customer_account = Customer::where( 'facebook_user_id', $user->facebook_userid )->first();
            if ( !is_null( $customer_account ) ) {
                Auth::login( $customer_account->user );
                return Redirect::intended( '/' )->withMessage( 'Welcome, You have logged in through Facebook account' );
            }//if ( !is_null( $customer_account ) )

            return Redirect::to( '/' )->withMessage( 'Welcome, You have logged in with Facebook account without Customer account.' );
        } catch ( SammyK\FacebookQueryBuilder\FacebookQueryBuilderException $e ) {
            return Redirect::to( '/' )->withError( $e->getPrevious()->getMessage() );
        }
    }

}
