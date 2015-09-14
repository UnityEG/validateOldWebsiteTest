<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
            $data = array();
	    if ( isset($this->auth_user) && !empty($this->auth_user->user_type)) {
               $user_type = $this->auth_user->user_type;
                $user_type_object = $this->auth_user->$user_type;
                $data[$user_type] = $user_type_object;
                
                $dashboard_name = 'users.'.$user_type.'s.dashboard';
                if ( View::exists( $dashboard_name ) ) {
                    return View::make($dashboard_name, compact( 'data'));
                }//if ( View::exists( $dashboard_name ) )
                
            }//if ( isset($this->auth_user) && !empty($this->auth_user->user_type))
		return View::make('home');
	}

}
