<?php

class CustomerController extends \BaseController {

    /**
     * Instance of Cusomer model class
     * @var object
     */
    protected $customer_model;

    /**
     * Instance of UserController class
     * @var object
     */
    protected $user_controller;

    public function __construct( Customer $customer, UserController $user_controller ) {
        parent::__construct();

        $this->customer_model = $customer;

        $this->user_controller = $user_controller;

        $this->beforeFilter( 'customer_show_all', array( 'only' => array( 'index' ) ) );

        $this->beforeFilter( 'customer_show_one', array( 'only' => array( 'show' ) ) );

//        $this->beforeFilter( 'customer_create', array( 'only' => array( 'create', 'store' ) ) );

        $this->beforeFilter( 'customer_update', array( 'only' => array( 'edit' ) ) );

        $this->beforeFilter( 'customer_delete', array( 'only' => array( 'destroy' ) ) );

        $this->beforeFilter( 'csrf', array( 'on' => 'post', 'on' => 'put', 'on' => 'delete' ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if ( Request::ajax() ) {
            $data = $this->ajaxSearch();
            return Response::json( $data );
        }

        $group = $this->customer_model->paginate( 5 );
        return View::make( 'users.customers.indexCustomer', compact( 'group' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data = array();
        
        $existing_user_id = (null !== Input::get('user_id'))? ( int ) Input::get( 'user_id' ): false;

        $check_customer = Customer::where( 'user_id', '=', $existing_user_id )->exists();

        if ( $check_customer ) {
            return Redirect::back()->withError( 'This user already has customer account!' );
        }//if ( $check_customer )

        $user_exist = false;
        if (!empty( $existing_user_id ) && ($existing_user_id != FALSE)) {
            $user_exist = $existing_user_id;
        }

        $default_assigned_rules = array();
        foreach ( $this->customer_default_rules as $rule_name ) {
            $rule= Rule::where( 'rule_name', '=', $rule_name )->first();
            $default_assigned_rules[] = $rule->id;
        }

        $data[ 'default_assigned_rules' ] = $default_assigned_rules;

        $data[ 'user_exist' ] = $user_exist;
        return View::make( 'users.customers.createCustomer', array( 'data' => $data ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();
        
        if ( is_null( $this->auth_user) || !$this->auth_user->isAdmin()) {
            if ( !isset($inputs['agreement']) || ($inputs['agreement'] == FALSE) ) {
                return Redirect::back()->withInput()->withError('You must accept the agreement!');
            }//if ( !isset($inputs['agreement']) || ($inputs['agreement'] == FALSE) )
        }//if ( is_null( $this->auth_user) || !$this->auth_user->isAdmin())

        $validation = Validator::make( $inputs, $this->customer_model->create_rules );

        if ( $validation->passes() ) {
            DB::beginTransaction();
//        get user id for new user from users table
            if ( isset( $inputs[ 'user_id' ] ) && !empty( $inputs[ 'user_id' ] ) ) {
                User::findOrFail( ( int ) $inputs[ 'user_id' ] );
                $user_id = (int)$inputs[ 'user_id' ];
                $this->user_controller->attachRule($user_id, 'customer');
            }//if ( isset($inputs['user_id']) && !empty($inputs['user_id']) )
            else {
                $user_id = $this->user_controller->store( 'customer' );
                if ( !is_numeric( $user_id ) ) {
                    if ( is_object( $user_id ) ) {
                        return Redirect::back()->withInput()->withErrors( $user_id );
                    }
                    return Redirect::back()->withInput()->with( 'error', $user_id );
                }//( !is_numeric( $user_id ) )
            }
//            prepare dob to store in database
            if(!$this->checkAge($inputs['dob'])){
                return Redirect::back()->withInput()->withError('You must be at least 18 years old to sign up to Validate!');
            }//if(!$this->checkAge($inputs['dob']))
            $dob = g::utcDateTime( $inputs['dob']);
            
            $this->customer_model->user_id        = $user_id;
            $this->customer_model->first_name     = $inputs[ 'first_name' ];
            $this->customer_model->last_name      = $inputs[ 'last_name' ];
            $this->customer_model->title          = $inputs[ 'title' ];
            $this->customer_model->dob            = $dob;
            $this->customer_model->region_id      = $inputs[ 'region_id' ];
            $this->customer_model->suburb_id      = $inputs[ 'suburb_id' ];
            $this->customer_model->postal_code_id = $inputs[ 'postal_code_id' ];
            $this->customer_model->address1       = $inputs[ 'address1' ];
            $this->customer_model->address2       = $inputs[ 'address2' ];
            $this->customer_model->phone          = $inputs[ 'phone' ];
            $this->customer_model->mobile         = $inputs[ 'mobile' ];
            $this->customer_model->gender         = $inputs[ 'gender' ];
            
            if ( !is_null( $this->auth_user) && $this->auth_user->hasRule('customer_activate')) {
                $this->customer_model->active_customer = (isset( $inputs[ 'active_customer' ] )) ? true : false;
            }//if ( !is_null( $this->auth_user) && $this->auth_user->hasRule('customer_activate'))
            else{
                $this->customer_model->active_customer = TRUE;
            }
            
            $this->customer_model->notify_deal     = (isset( $inputs[ 'notify_deal' ] )) ? true : false;

            if ( $this->customer_model->save() ) {
                DB::commit();
                if ( !is_null($this->auth_user) ) {
                    return Redirect::to( 'customer/'.$this->customer_model->id )->with( 'message', 'Customer created successfully' );
                }//if ( !is_null($this->auth_user) )
                
                return Redirect::to( 'login')->with('message', 'Your account is now setup, please login');
            } //( $this->merchant_model->save() )
            else {
                DB::rollBack();
                return Redirect::back()->withInput()->with( 'error', 'Error saving user!' );
            }
        }//if ( $validation->passes() )
        else {
            return Redirect::back()->withInput()->withErrors( $validation );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show( $id ) {

        $data = array();

        $item = $this->customer_model->findOrFail( $id );

        $item_info_user = $item->user()->first();

        $data[ 'item' ] = $item;

        $data[ 'item_info_user' ] = $item_info_user;

        return View::make( 'users.customers.showCustomer', array( 'data' => $data ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $data = array();

        $assigned_rules_ids = array();

        $item = $this->customer_model->findOrFail( $id );

        $item_information_from_user_table = $item->user()->first();
        
        if ( !is_null( $item_information_from_user_table ) ) {
            $item_user_rules = $item_information_from_user_table->rules;
            foreach ( $item_user_rules as $rule ) {
                if ( !is_null( $rule->pivot->active_rule ) && 0 != $rule->pivot->active_rule ) {
                    $assigned_rules_ids[] = $rule->id;
                }
            }//foreach ($item_user_rules as $rule)
        }//if($item_information_from_user_table)
        
//        prepare date of birth to show
        $dob = g::formatDate($item->dob);
        
        $data[ 'assigned_rules_ids' ] = $assigned_rules_ids;
        $data['dob'] = $dob;
        $data['customer_name'] = $item->getName();

        return View::make( 'users.customers.editCustomer', array( 'item' => $item, 'item_information_from_user_table' => $item_information_from_user_table, 'data' => $data ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        
        $customer = $this->customer_model->findOrFail( $id );

//        check if authenticated user is not in admin group that is the account owner
        if ( !$this->check_admin ) {
            if ( $this->auth_user->id != $customer->user_id ) {
                return Redirect::back()->withError( 'Error with permission!' );
            }//if ( $this->auth_user->id != $customer->user_id )
        }//if ( !$this->check_admin )

        $inputs = Input::all();
        
        $validation = Validator::make( $inputs, $this->customer_model->update_rules );

        if ( $validation->passes() ) {
            
//        get user id for new user from users table
            $user_update = $this->user_controller->update( $customer->user_id, 'customer' );
            if ( $user_update != 'success' ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput()->with( 'error', $user_update );
            }
            
//            prepare date of birth if exist
            if ( isset($inputs['dob']) && !empty($inputs['dob']) ) {
                $raw_dob = $inputs['dob'].' 00:00:00';
                $modified_dob = g::utcDateTime($raw_dob, 'd/m/Y H:i:s');
                $inputs['dob'] = $modified_dob;
            }
            
//            if ( isset($inputs['detach_facebook_user_id']) && $inputs['detach_facebook_user_id'] == TRUE ) {
//                $inputs['facebook_user_id'] = null;
//            }//if ( isset($inputs['detach_facebook_user_id']) && $inputs['detach_facebook_user_id'] === TRUE )
            
            if ( isset($inputs['facebook_user_id']) ) {
                $inputs['facebook_user_id'] = (int)$inputs['facebook_user_id'];
                
                if ( $inputs['facebook_user_id'] == 0 ) {
                    Session::forget('facebook_user');
                }//if ( $inputs['facebook_user_id'] == 0 )
            }//if ( isset($inputs['facebook_user_id']) )
            
            if ( $this->auth_user->hasRule( 'customer_activate' ) ) {
                $inputs['active_customer'] = ( isset( $inputs[ 'active_customer' ] ) ) ? true : false;
            }//if ( $this->auth_user->hasRule( 'customer_activate' ) )
            
            $inputs['notify_deal'] = ( isset( $inputs[ 'notify_deal' ] ) ) ? true : false;

            if ( $customer->update($inputs) ) {

                return Redirect::to( 'customer/' . $customer->id )->with( 'message', 'Customer ' . $customer->first_name . ' has been updated successfully' );
            } //( $customer->update($inputs) )
            else {
                return Redirect::back()->withInput()->with( 'error', 'Error updating user!' );
            }
        }//if ( $validation->passes() )
        else {
            return Redirect::back()->withInput()->withErrors( $validation );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( $id ) {
        $customer = $this->customer_model->findOrFail( $id );

//        begin transaction
        DB::beginTransaction();

        $user_delete = $this->user_controller->destroy( $customer->user_id );
        if ( $user_delete != 'success' ) {
            return Redirect::back()->with( 'error', $user_delete );
        }

        if ( $customer->delete() ) {
//            commit transaction
            DB::commit();
            return Redirect::to( '/' )->with( 'message', 'Customer ' . $customer->name . ' has been deleted!' );
        } else {
//            rollback transaction
            DB::rollBack();
            return Redirect::back()->with( 'error', 'Sorry, could not delete customer!' );
        }
    }

//    Helper Methods

    /**
     * Ajax search for customers
     * @return array
     */
    private function ajaxSearch() {
        $input = Input::get( 'search' );

        $data = array();

        $search_result = '';

        $check = Customer::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->exists();

        if ( $check ) {
            $customers = Customer::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->get( array( 'id', 'first_name', 'last_name' ) );
            foreach ( $customers as $customer ) {
                $search_result .= "<div>" . link_to_route( 'customer.show', $customer->first_name . ' ' . $customer->last_name, array( $customer->id ) ) . "</div>";
            }//foreach ( $customers as $customer)

            $data[ 'search_result' ] = $search_result;
        }//if ( $check )
        else {
            $data[ 'error' ] = 'Customer not found!';
        }

        return $data;
    }
    
    /**
     * check that min age is 18 years old
     * @param string $date_of_birth
     * @return boolean
     */
    private function checkAge($date_of_birth){
        $prepare_date_of_birth = explode('/', $date_of_birth);
        $iso_date_of_birth = $prepare_date_of_birth[2].'-'.$prepare_date_of_birth[1].'-'.$prepare_date_of_birth[0];
        $today = Carbon::now()->timezone('Pacific/Auckland');
        $date_of_birth_carbon = new Carbon($iso_date_of_birth, 'Pacific/Auckland');
        if( 18 <= $today->diffInYears($date_of_birth_carbon)){
            return TRUE;
        }
        return FALSE;
    }
    
    

}
