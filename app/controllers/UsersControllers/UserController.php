<?php

class UserController extends \BaseController {

    /**
     * Instance of User model
     * @var object
     */
    protected $user;

    /**
     * User types of the system
     * @var array
     */
    protected $user_types = array(
        "customer"         => 'Customer',
        "merchant"         => 'Merchant',
        "merchant_manager" => 'Merchant Manager',
        "supplier"         => 'Supplier',
        "franchisor"       => 'Franchisor'
    );
    protected $open_lock;

    function __construct( User $user ) {

        parent::__construct();

        $this->user = $user;

        $this->beforeFilter( 'csrf', array( 'on' => 'post', 'on' => 'put', 'on' => 'delete' ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if ( Request::ajax() ) {
            $data = $this->searchAllUsersAjax();
            return Response::json( $data );
        }

        if ( !$this->auth_user->isDeveloper() ) {
            $users = $this->user->where( 'user_type', '!=', 'developer' )->orderBy( 'user_type' )->paginate( 10 );
        } else {
            $users = $this->user->orderBy( 'user_type' )->paginate( 10 );
        }
        return $users;
        //return View::make( 'admin-area.admin_page', compact( 'users' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if ( !$this->open_lock ) {
            return Redirect::to( '/' );
        }
        if ( $this->auth_user->user_type == 'developer' ) {
            $this->user_types[ "developer" ] = 'Developer';
            $this->user_types[ "owner" ]     = 'Owner';
            $this->user_types[ "admin" ]     = 'Admin';
        } elseif ( $this->auth_user->user_type == 'owner' ) {
            $this->user_types[ "owner" ] = 'Owner';
            $this->user_types[ "admin" ] = 'Admin';
        }
        $user_types = $this->user_types;
        return View::make( 'admin-area.admin_user_create', compact( 'user_types' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store( $user_type = '' ) {
        $input = Input::all();

//        add user_type to the input array for validation purposes
        $input[ 'user_type' ] = $user_type;

        $validation = Validator::make( $input, $this->user->create_rules );

        if ( $validation->passes() ) {

//            default user credentials
            $this->user->email     = $input[ 'email' ];
            $this->user->password  = Hash::make( $input[ 'password' ] );
            $this->user->user_type = $input[ 'user_type' ];

            if ( !is_null( $this->auth_user ) && ($this->auth_user->hasRule( 'user_activate' )) ) {
                $this->user->active = (isset( $input[ 'active' ] )) ? true : false;
            }//if ( !is_null( $this->auth_user) && ($this->auth_user->hasRule('user_activate')) )
            else {
                $this->user->active = TRUE;
            }

            if ( $this->user->save() ) {
//                attach rules to new created user
                $this->attachRule( $this->user->id, $user_type );
//                return with last inserted user id
                return $this->user->id;
            } else {
                return 'Error saving user!';
            }
        } else {

            return $validation;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show( $id ) {
        if ( !$this->open_lock ) {
            return Redirect::to( '/' );
        }
        $user = $this->user->findOrFail( $id );

//        get meta data from outside tables
        $outside_info = $user->user_type;
        $user_info    = $user->$outside_info()->first();

        return View::make( 'admin-area.admin_user_single', compact( 'user', 'user_info' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        if ( !$this->open_lock ) {
            return Redirect::to( '/' );
        }
        $user = $this->user->findOrFail( $id );
        if ( $this->auth_user->user_type == 'developer' ) {
            $this->user_types[ "developer" ] = 'Developer';
            $this->user_types[ "owner" ]     = 'Owner';
            $this->user_types[ "admin" ]     = 'Admin';
        } elseif ( $this->auth_user->user_type == 'owner' ) {
            $this->user_types[ "owner" ] = 'Owner';
            $this->user_types[ "admin" ] = 'Admin';
        }
        $user_types = $this->user_types;
        return View::make( 'admin-area.admin_user_edit', compact( 'user', 'user_types' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id, $user_type = '' ) {

        $user = $this->user->findOrFail( $id );

        $input = Input::all();

//        add user_type to the input array for validation purposes        
        $input[ 'user_type' ] = $user_type;

        $validation = Validator::make( $input, $user->update_rules );

        if ( $validation->passes() ) {

            $user->email = $input[ 'email' ];

            if ( isset( $input[ 'password' ] ) && !empty( $input[ 'password' ] ) ) {
                if ( $input[ 'password' ] != $input[ 'password_confirmation' ] ) {
                    return 'Password and password confirmation not match';
                }
                $user->password = Hash::make( $input[ 'password' ] );
            }
//            stopping changing user type during update not to overwrite the existing type of user while creating additional profiles.
//            $user->user_type = $input[ 'user_type' ];
//            Just special powers can modify this option
            if ( $this->auth_user->hasRule( 'user_activate' ) ) {
                if ( 'owner' === $user_type ) {
                    $user->active = TRUE;
                }//if ( 'owner' === $user_type )
                else {
                    $user->active = (isset( $input[ 'active' ] )) ? TRUE : FALSE;
                }
            }//if ( $this->auth_user->hasRule('activate_user'))
//            Update or Reset Rules
            if ( $this->auth_user->hasRule( 'assign_rules' ) ) {
                if ( isset( $input[ 'reset_rules' ] ) && $input[ 'reset_rules' ] == TRUE ) {
                    $this->resetRules( $id, $user_type );
                }//if ( isset( $input[ 'reset_rules' ] ) && $input[ 'reset_rule' ] == TRUE ) 
                else {
                    $this->updateRules( $id );
                }
            }//if ( $this->auth_user->hasRule('assign_rules') )


            if ( $user->save() ) {

                return 'success';
            }//if ( $user->save() ) 
            else {
                return 'Error during saving user';
            }
        }//if ( $validation->passes() ) 
        else {
            return $validation;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( $id ) {

        $user = $this->user->findOrFail( $id );

        if ( $user->delete() ) {
            return 'success';
        }
        return 'could not be deleted';
    }

//    Helper Methods

    /**
     * Attach Rules to newly created user
     * @param integer $user_id
     * @param string $user_type
     */
    public function attachRule( $user_id, $user_type ) {

//        default rules for every group
        $default_rules = array(
            'admin'            => $this->admin_default_rules,
            'customer'         => $this->customer_default_rules,
            'merchant'         => $this->merchant_default_rules,
            'merchant_manager' => $this->merchant_manager_default_rules,
            'franchisor'       => $this->franchisor_default_rules,
            'supplier'         => $this->supplier_default_rules
        );

        $created_user = User::find( $user_id );

//        create specific rules to this user or let default be chosen
        if ( $this->auth_user && $this->auth_user->hasRule( 'assign_rules' ) && isset( Input::all()[ 'assign_rules_ids' ] ) ) {
            $rules_ids = Input::all()[ 'assign_rules_ids' ];
            foreach ( $rules_ids as $rule_id ) {
                $rule = Rule::findOrFail( $rule_id );
                $rule->users()->attach( $created_user->id );
                $rule->users()->updateExistingPivot( $created_user->id, array( 'active_rule' => 1 ) );
            }//foreach ( $rules_ids as $rule_id )
        }//if ( isset(Input::all()['assign_rules_ids']) )
        else {
            $rule_names = $default_rules[ $user_type ];
            foreach ( $rule_names as $rule_name ) {
                $rule = Rule::where( 'rule_name', '=', $rule_name )->first();
                $rule->users()->attach( $created_user->id );
                $rule->users()->updateExistingPivot( $created_user->id, array( 'active_rule' => 1 ) );
            }//foreach ( $rule_names as $rule_name )
        }
    }

    /**
     * Update Rules to existing user
     * @param integer $user_id
     */
    public function updateRules( $user_id ) {
        if ( !$this->auth_user->hasRule( 'assign_rules' ) ) {
            return FALSE;
        }//if ( !$this->auth_user->hasRule( 'assign_rules' ) )
        $updated_user = $this->user->find( $user_id );

        $rules = $updated_user->rules;

        if ( isset( Input::all()[ 'assign_rules_ids' ] ) ) {

            $rules_ids_from_input = Input::all()[ 'assign_rules_ids' ];

//            update existing rules that already attached with user
            foreach ( $rules as $rule ) {
                if ( in_array( $rule->id, $rules_ids_from_input ) ) {
                    $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 1 ) );
                }//if ( in_array( $rule->id, $rules_ids_from_input) )
                else {
                    $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 0 ) );
                }
            }//foreach ( $rules as $rule)
//          attach new rules to the user and activate it
            foreach ( $rules_ids_from_input as $rule_id ) {
                $rule = Rule::findOrFail( $rule_id );

                $rule_user = $rule->users()->where( 'user_id', '=', $user_id )->first();

                if ( isset( $rule_user ) && !is_null( $rule_user ) ) {
                    $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 1 ) );
                }//if ( isset( $rule_user ) && !is_null($rule_user))
                else {
                    $rule->users()->attach( $user_id );
                    $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 1 ) );
                }
            }// foreach ( $rules_ids_from_input as $rule_id )
        }//if($rules_ids_from_input = Input::all()['assign_rules_ids'])
        elseif (!isset( Input::all()[ 'assign_rules_ids' ] ) ) {
//            deactivate all the rules assigned to this user
            foreach ( $rules as $rule ) {
                $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 0 ) );
            }//foreach ( $rules as $rule )
        }//( $this->auth_user->hasRule( 'assign_rules' ) && !isset( Input::all()[ 'assign_rules_ids' ] ) )
        return TRUE;
    }

    /**
     * Reset rules to default rules for existing user
     * @param integer $user_id
     * @param string $user_type
     */
    public function resetRules( $user_id ) {
        if ( !Auth::user()->hasRule('assign_rules') ) {
            return false;
        }
        $reseted_user = $this->user->find( $user_id );
        
        $raw_default_rules = array();
//        check for different user profiles to allocate appropriate default rules
        $user_roles        = $reseted_user->getTypes();
        
        foreach ( $user_roles as $role ) {
            $role_name= ('MerchantManager' == $role) ? 'merchant_manager' : strtolower( $role );
            $default_rules_name   = $role_name . '_default_rules';
            $raw_default_rules [] = $this->$default_rules_name;
        }//foreach ( $user_roles as $role)
        $default_rules = array_flatten( $raw_default_rules );

        $current_reseted_user_rules = $reseted_user->rules;

        //deactivate all rules
        foreach ( $current_reseted_user_rules as $rule ) {
            $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 0 ) );
        }//foreach ( $current_reseted_user_rules as $rule)
        //activate default rules
        foreach ( $default_rules as $rule_name ) {
            $rule               = Rule::where( 'rule_name', '=', $rule_name )->first();
            $test_user_relation = $rule->users()->where( 'user_id', '=', $user_id )->first();
            if ( is_null( $test_user_relation ) || empty( $test_user_relation ) || !is_object( $test_user_relation ) ) {
                $rule->users()->attach( $user_id );
            }//if ( is_null( $test_user_relation ) || empty($test_user_relation) || !is_object( $test_user_relation ) )
            $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 1 ) );
        }//foreach ( $default_rules as $rule_name)

        return TRUE;
    }

    /**
     * Search for all users via Ajax
     * @return string
     */
    private function searchAllUsersAjax() {
        $input = Input::get( 'search' );
        $data  = array();

        $search_result = '';

//        search with email
        $email_exist = User::where( 'email', 'like', '%' . $input . '%' )->where( 'user_type', '!=', 'developer' )->exists();

        if ( $email_exist ) {
            $users = User::where( 'email', 'like', '%' . $input . '%' )->where( 'user_type', '!=', 'developer' )->get( array( 'id', 'email' ) );
            foreach ( $users as $user ) {
                $search_result .= '<div>' . link_to_route( 'rules.user', $user->email, array( $user->id ) ) . '</div>';
            }//foreach ($users as $user)
            $data[ 'search_result' ] = $search_result;
            return $data;
        }//if ( $email_exist )
//        search with first name and last name in customers
        $customer_exist = Customer::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->exists();

        if ( $customer_exist ) {
            $customers = Customer::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->get();

            foreach ( $customers as $customer ) {
                $user = $customer->user;
                $search_result .= '<div>' . link_to_route( 'rules.user', $user->email . ' ( ' . $customer->first_name . ' ' . $customer->last_name . ' )', array( $user->id ) ) . '</div>';
            }
        }//if ( $customer_exist )
//        search with first name, last name, business name in merchants
        $merchant_exist = Merchant::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->orWhere( 'business_name', 'like', '%' . $input . '%' )->exists();

        if ( $merchant_exist ) {
            $merchants = Merchant::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->orWhere( 'business_name', 'like', '%' . $input . '%' )->get();

            foreach ( $merchants as $merchant ) {
                $user = $merchant->user;
                $search_result .= '<div>' . link_to_route( 'rules.user', $user->email . ' ( ' . $merchant->first_name . ' ' . $merchant->last_name . ' ) ( ' . $merchant->business_name . ' )', array( $user->id ) ) . '</div>';
            }//foreach ($merchants as $merchant)
        }//if ( $merchant_exist )
//        search with first name and last name in merchant managers
        $merchant_manager_exist = MerchantManager::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->exists();

        if ( $merchant_manager_exist ) {
            $merchant_managers = MerchantManager::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->get();

            foreach ( $merchant_managers as $merchant_manager ) {
                $user = $merchant_manager->user;
                $search_result .= '<div>' . link_to_route( 'rules.user', $user->email . ' ( ' . $merchant_manager->first_name . ' ' . $merchant_manager->last_name . ' )', array( $user->id ) ) . '</div>';
            }//foreach ( $merchant_managers as $merchant_manager)
        }//if ( $merchant_manager_exist )
//        search with franchisor name in franchisors
        $franchisor_exists = Franchisor::where( 'franchisor_name', 'like', '%' . $input . '%' )->exists();

        if ( $franchisor_exists ) {
            $franchisors = Franchisor::where( 'franchisor_name', 'like', '%' . $input . '%' )->get();
            foreach ( $franchisors as $franchisor ) {
                $user = $franchisor->user;
                $search_result .= '<div>' . link_to_route( 'rules.user', $user->email . ' ( ' . $franchisor->franchisor_name . ' )', array( $user->id ) );
            }
        }//if ( $franchisor_exists )
//        search with first name and last name in suppliers
        $supplier_exist = Supplier::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->exists();

        if ( $supplier_exist ) {
            $suppliers = Supplier::where( 'first_name', 'like', '%' . $input . '%' )->orWhere( 'last_name', 'like', '%' . $input . '%' )->get();

            foreach ( $suppliers as $supplier ) {
                $user = $supplier->user;
                $search_result.= '<div>' . link_to_route( 'rules.user', $user->email . ' (  ' . $supplier->first_name . ' ' . $supplier->last_name . ' )', array( $user->id ) );
            }//foreach ( $suppliers as $supplier)
        }//if ( $supplier_exist )

        if ( !empty( $search_result ) ) {
            $data[ 'search_result' ] = $search_result;
        }//if ( empty($search_result) )
        else {
            $data[ 'error' ] = 'No users found!';
        }

        return $data;
    }

}
