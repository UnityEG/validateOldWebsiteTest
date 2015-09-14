<?php

class MerchantManagerController extends \BaseController {

    /**
     * Instance of Merchant Manager model
     * @var object
     */
    protected $merchant_manager_model;

    /**
     * Instance of UserController controller
     * @var object
     */
    protected $user_controller;

    public function __construct( MerchantManager $merchant_manager_model, UserController $user_controller ) {
        parent::__construct();
        
        $this->merchant_manager_model = $merchant_manager_model;

        $this->user_controller = $user_controller;

        $this->beforeFilter( 'merchant_manager_show_all', array( 'only' => array( 'index' ) ) );

        $this->beforeFilter( 'merchant_manager_show_one', array( 'only' => 'show' ) );

        $this->beforeFilter( 'merchant_manager_create', array( 'only' => array( 'create' ) ) );

        $this->beforeFilter( 'merchant_manager_update', array( 'only' => array( 'edit' ) ) );

        $this->beforeFilter( 'merchant_manager_delete', array( 'only' => array( 'destroy' ) ) );

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
            
            return Response::json($data);
        }

        if ( 'merchant' == $this->auth_user->user_type ) {
            $merchant = Merchant::where( 'user_id', '=', $this->auth_user->id )->first();
            $group    = $this->merchant_manager_model->where( 'merchant_id', '=', $merchant->id )->paginate(5);
        }//if ( 'merchant' == $this->auth_user->user_type )
        else {
            $group = $this->merchant_manager_model->paginate( 10 );
        }
        return View::make( 'users.merchantManagers.indexMerchantManager', compact( 'group' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data = array();
        
        $default_assigned_rules = array();
        foreach ( $this->merchant_manager_default_rules as $rule_name) {
            $rule = Rule::where('rule_name', '=', $rule_name)->first();
            $default_assigned_rules[] = $rule->id;
        }
        
        $data['default_assigned_rules'] = $default_assigned_rules;
        return View::make( 'users.merchantManagers.createMerchantManager', compact( 'data') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->merchant_manager_model->create_rules );

        if ( $validation->passes() ) {
//                start transaction
            DB::beginTransaction();

//                get user id for the new merchant manager from users table
            $user_id = $this->user_controller->store( 'merchant_manager' );
            if ( !is_numeric( $user_id ) ) {
                if ( is_object( $user_id ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_id );
                }
                return Redirect::back()->withInput()->with( 'error', $user_id );
            }//if ( !is_numeric( $user_id ) )

            $this->merchant_manager_model->user_id = $user_id;

//            check if merchant is creating his own managers
            if ( 'merchant' == $this->auth_user->user_type ) {
                $merchant                                  = Merchant::where( 'user_id', '=', $this->auth_user->id )->first();
                $this->merchant_manager_model->merchant_id = $merchant->id;
            }//if ( 'merchant' == $this->auth_user->user_type ) 
            else {
                $this->merchant_manager_model->merchant_id = $inputs[ 'merchant_id' ];
            }

            $this->merchant_manager_model->first_name              = $inputs[ 'first_name' ];
            $this->merchant_manager_model->last_name               = $inputs[ 'last_name' ];
            $this->merchant_manager_model->active_merchant_manager = (isset( $inputs[ 'active_merchant_manager' ] )) ? TRUE : FALSE;

            if ( $this->merchant_manager_model->save() ) {
//                    db commit
                DB::commit();
                return Redirect::to( 'merchant_manager/' )->with( 'message', 'Merchant Manager ' . $this->merchant_manager_model->first_name . ' ' . $this->merchant_manager_model->last_name . ' created successfully.' );
            }//if ( $this->merchant_manager_model->save() )
            else {
//                    rollback if fail
                DB::rollBack();
                return Redirect::back()->withInput()->withError( 'Error while saving new Merchant Manager' );
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
        $item = $this->merchant_manager_model->findOrFail( $id );

        $item_info_user = $item->user()->first();

        $item_info_merchant = $item->merchant()->first();

        return View::make( 'users.merchantManagers.showMerchantManager', compact( 'item', 'item_info_user', 'item_info_merchant' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $item = $this->merchant_manager_model->findOrFail( $id );

        $data = array();

        $item_info_user = $item->user()->first();

        $item_info_merchant = $item->merchant()->first();

        $item_rules = $item_info_user->rules;

        $assigned_rules_ids = array();

        foreach ( $item_rules as $rule ) {
            if ( !is_null( $rule->pivot->active_rule ) && 0 != $rule->pivot->active_rule ) {
                $assigned_rules_ids[] = $rule->id;
            }//if ( !is_null( $rule->pivot->active_rule) && 0 != $rule->pivot->active_rule )
        }//foreach ( $item_rules as $rule)

        $data[ 'assigned_rules_ids' ] = $assigned_rules_ids;

        return View::make( 'users.merchantManagers.editMerchantManager', compact( 'item', 'item_info_user', 'item_info_merchant', 'data' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        $merchant_manager = $this->merchant_manager_model->findOrFail( $id );

        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->merchant_manager_model->update_rules );

        if ( $validation->passes() ) {

            $user_update = $this->user_controller->update( $merchant_manager->user_id, 'merchant_manager' );
            if ( $user_update != 'success' ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput()->withError( $user_update );
            }

            $merchant_manager->first_name  = $inputs[ 'first_name' ];
            $merchant_manager->last_name   = $inputs[ 'last_name' ];
            
            if ( isset($inputs['merchant_id']) || !empty($inputs['merchant_id']) ) {
                $merchant_manager->merchant_id = $inputs[ 'merchant_id' ];
            }//if ( isset($inputs['merchant_id']) || !empty($inputs['merchant_id']) )
            
            if ( $this->auth_user->hasRule('merchant_manager_activate') ) {
                $merchant_manager->active_merchant_manager = (isset( $inputs[ 'active_merchant_manager' ] )) ? TRUE : FALSE;
            }//if ( $this->auth_user->hasRule('merchant_manager_activate') )
            

            if ( $merchant_manager->save() ) {
                return Redirect::to( 'merchant_manager/' . $merchant_manager->id )->withMessage( 'Merchant Manager ' . $merchant_manager->first_name . ' ' . $merchant_manager->last_name . ' updated successfully.' );
            }//if ( $merchant_manager->save() )
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
        $item = $this->merchant_manager_model->findOrFail( $id );

//                start transaction
        DB::beginTransaction();

        $user_delete = $this->user_controller->destroy( $item->user_id );

        if ( $user_delete != 'success' ) {
            return Redirect::back()->withError( $user_delete );
        }

        if ( $item->delete() ) {
            DB::commit();
            return Redirect::to( 'merchant_manager/' )->withMessage( 'Merchant Manager ' . $item->first_name . ' ' . $item->last_name . ' has been deleted successfully.' );
        } else {
            DB::rollBack();
            return Redirect::back()->withError( 'Sorry couldn\'t delete Merchant Manager ' . $item->first_name . ' ' . $item->last_name );
        }
    }

//    Helper methods
    
    /**
     * Ajax search for merchant managers
     * @return array
     */
    public function ajaxSearch() {
        $input = Input::get('search');
        
        $data = array();
        
        $search_result = '';
        
        if ( 'merchant' == $this->auth_user->user_type ) {
            
            $merchant = Merchant::where('user_id', '=', $this->auth_user->id)->first(array('id'));
            
            $check = $this->merchant_manager_model->where('merchant_id', '=', $merchant->id)->where(function($query){
                $input = Input::get('search');
                
                $query->where('first_name', 'like', '%'.$input.'%')
                      ->orWhere('last_name', 'like', '%'.$input.'%');
            })->exists();
            
            if ($check ) {
                $merchant_managers = $this->merchant_manager_model->where('merchant_id', '=', $merchant->id)->where(function($query){
                    $input = Input::get('search');
                    $query->where('first_name', 'like', '%'.$input.'%')
                          ->orWhere('last_name', 'like', '%'.$input.'%');
                })->get(array('id', 'first_name', 'last_name'));
                
            foreach ( $merchant_managers as $merchant_manager ) {
                
                $search_result .= "<div>".link_to_route('merchant_manager.show', $merchant_manager->first_name.' '.$merchant_manager->last_name, array($merchant_manager->id))."</div>";
                
            }//foreach ( $merchant_managers as $merchant_manager )
            
            $data['search_result'] = $search_result;
            
            }//if ($check )
            else{
                $data['error'] = 'Merchant Manager not found!';
            }
            
        }//if ( 'merchant' == $this->auth_user->user_type )
        else{
            $check = $this->merchant_manager_model->where('first_name', 'like', '%'.$input.'%')->orWhere('last_name', 'like', '%'.$input.'%')->exists();
            if ( $check ) {
                $merchant_managers = $this->merchant_manager_model->where('first_name', 'like', '%'.$input.'%')->orWhere('last_name', 'like', '%'.$input.'%')->get(array('id', 'first_name', 'last_name'));
                
                foreach ( $merchant_managers as $merchant_manager) {
                    $search_result .= "<div>".link_to_route('merchant_manager.show', $merchant_manager->first_name." ".$merchant_manager->last_name, array($merchant_manager->id))."</div>";
                }//foreach ( $merchant_managers as $merchant_manager)
                
                $data['search_result'] = $search_result;
            }//if ( $check )
            else{
                $data['error'] = 'Merchant Manager not found!';
            }
        }
        
        return $data;
    }

}
