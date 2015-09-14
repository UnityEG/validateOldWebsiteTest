<?php

class SupplierController extends \BaseController {

    /**
     * Instance of Supplier model
     * @var object
     */
    protected $supplier_model;

    /**
     * Instance of UserController controller
     * @var object
     */
    protected $user_controller;

    public function __construct( Supplier $supplier_model, UserController $user_controller ) {
        parent::__construct();
        
        $this->supplier_model = $supplier_model;

        $this->user_controller = $user_controller;
        
        $this->beforeFilter('supplier_show_all', array('only'=>array('index')));

        $this->beforeFilter( 'supplier_show_one', array( 'only' => array('show') ) );
        
        $this->beforeFilter('supplier_create', array('only'=>array('create')));
        
        $this->beforeFilter('supplier_update', array('only'=>array('edit')));
        
        $this->beforeFilter('supplier_delete', array('only'=>array('destroy')));

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
        }//if ( Request::ajax() )
        $group = $this->supplier_model->paginate( 10 );

        return View::make( 'users.suppliers.indexSupplier', compact( 'group' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data = array();
        
        $default_assigned_rules = array();
        foreach ( $this->supplier_default_rules as $rule_name) {
            $rule = Rule::where('rule_name', '=', $rule_name)->first();
            $default_assigned_rules[] = $rule->id;
        }
        
        $data['default_assigned_rules'] = $default_assigned_rules;
        return View::make( 'users.suppliers.createSupplier', compact('data') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->supplier_model->create_rules );

        if ( $validation->passes() ) {

//            start transaction
            DB::beginTransaction();

//        get user id for new user from users table
            $user_id = $this->user_controller->store( 'supplier' );
            if ( !is_numeric( $user_id ) ) {
                if ( is_object( $user_id ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_id );
                }
                return Redirect::back()->withInput()->with( 'error', $user_id );
            }//( !is_numeric( $user_id ) )

            $this->supplier_model->user_id    = $user_id;
            $this->supplier_model->first_name = $inputs[ 'first_name' ];
            $this->supplier_model->last_name  = $inputs[ 'last_name' ];


            if ( $this->supplier_model->save() ) {

//                get the id of the new supplier
                $new_supplier_id = $this->supplier_model->id;

//                get the new supplier object 
                $new_supplier = $this->supplier_model->find( $new_supplier_id );

//                add related merchants with the supplier in the pivot table
                $new_supplier->merchant()->sync( $inputs[ 'merchant_ids' ] );

//              If we reach here, then
//              data is valid and working.
//              Commit the queries!
                DB::commit();
                return Redirect::to( 'supplier/' )->with( 'message', 'Supplier created successfully' );
            } //( $this->supplier_model->save() )
            else {
                DB::rollBack();
                return Redirect::back()->withInput()->with( 'error', 'Error creating supplier!' );
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
        $item = $this->supplier_model->findOrFail( $id );

        $item_info_user = $item->user()->first();

        $item_info_merchants = $item->merchant;

        $merchants_list = "<ul>";

        foreach ( $item_info_merchants as $merchant ) {
            $merchants_list .= "<li>";
            $merchants_list .= link_to_route( 'merchant.show', $merchant->first_name, array( $merchant->id ) );
            $merchants_list .= "</li>";
        }
        $merchants_list .= "</ul>";

        return View::make( 'users.suppliers.showSupplier', compact( 'item', 'merchants_list', 'item_info_user' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $item = $this->supplier_model->findOrFail( $id );
        
        $data = array();
        
        $item_info_user = $item->user()->first();
        
//        get merchants ids
        $item_info_merchant = $item->merchant;
        
        $merchant_ids = array();
        foreach ( $item_info_merchant as $merchant ) {
            $merchant_ids[] = $merchant->id;
        }
        
//        get rules ids
        $item_rules = $item_info_user->rules;
        
        $assigned_rules_ids = array();
        
        foreach ( $item_rules as $rule) {
            if ( !is_null( $rule->pivot->active_rule) && 0 != $rule->pivot->active_rule ) {
                $assigned_rules_ids[] = $rule->id;
            }//if ( !is_null( $rule->pivot->active_rule) && 0 != $rule->pivot->active_rule )
        }// foreach ( $item_rules as $rule)
        
        $data['assigned_rules_ids'] = $assigned_rules_ids;


        return View::make( 'users.suppliers.editSupplier', compact( 'item', 'item_info_user', 'merchant_ids', 'data' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        $supplier = $this->supplier_model->findOrFail( $id );
        
//        check if authenticated user is in admin group or account owner
        if ( !$this->check_admin ) {
            if ( $this->auth_user->id != $supplier->user_id ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( $this->auth_user->id != $supplier->user_id )
        }//if ( !$this->check_admin )
        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->supplier_model->update_rules );

        if ( $validation->passes() ) {

            $user_update = $this->user_controller->update( $supplier->user_id, 'supplier' );
            if ( 'success' != $user_update ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput()->withError( $user_update );
            }

            $supplier->first_name      = $inputs[ 'first_name' ];
            $supplier->last_name       = $inputs[ 'last_name' ];
            if ( $this->auth_user->hasRule('supplier_activate')) {
                $supplier->active_supplier = (isset( $inputs[ 'active_supplier' ] )) ? TRUE : FALSE;
            }//if ( $this->auth_user->hasRule('activate_supplier'))
            
            if ( isset( $inputs[ 'merchant_ids' ] ) ) {
                $supplier->merchant()->sync( $inputs[ 'merchant_ids' ] );
            }//if ( isset( $inputs[ 'merchant_ids' ] ) )
            else{
                $supplier->merchant()->sync( array() );
            }

            if ( $supplier->save() ) {
                return Redirect::to( 'supplier/' . $supplier->id )->withMessage( 'Supplier ' . $supplier->first_name . ' updated successfully.' );
            }//if ( $supplier->save() )
            else {
                return Redirect::back()->withInput()->withError( 'Error while updating supplier!' );
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
        $supplier = $this->supplier_model->findOrFail($id);
        
//        start database transaction
        DB::beginTransaction();
        
        $user_delete = $this->user_controller->destroy($supplier->user_id);
        if ( 'success' != $user_delete ) {
            return Redirect::back()->withError($user_delete);
        }// if ( 'success' != $user_delete )
        
//        delete all relations from pivot table
        $supplier->merchant()->sync(array());
        
        if($supplier->delete()){
            
//            commit database transaction
            DB::commit();
            return Redirect::route('supplier.index')->withMessage('Supplier '.$supplier->first_name.' '.$supplier->last_name.' deleted successfully.');
        }//if($supplier->delete())
        else{
//            rollback database transaction
            DB::rollBack();
            return Redirect::back()->withError('Error during deleting supplier!');
        }
    }

    //Helper methods
    
    /**
     * Ajax search helper method
     * @return array
     */
    private function ajaxSearch() {
        $input = Input::get('search');
        
        $data = array();
        
        $search_result = '';
        
        $check = $this->supplier_model->where('first_name', 'like', '%'.$input.'%')->orWhere('last_name', 'like', '%'.$input.'%')->exists();
        
        if ( $check ) {
            $suppliers = $this->supplier_model->where('first_name', 'like', '%'.$input.'%')->orWhere('last_name', 'like', '%'.$input.'%')->get(array('id', 'first_name', 'last_name'));
            foreach ( $suppliers as $supplier) {
                $search_result .= "<div>".link_to_route('supplier.show', $supplier->first_name.' '.$supplier->last_name, array($supplier->id))."</div>";
            }//foreach ( $suppliers as $supplier)
            $data['search_result'] = $search_result;
        }//if ( $check )
        else{
            $data['error'] = '<div>Franchisor not found!</div>';
        }
        
        return $data;
    }

}
