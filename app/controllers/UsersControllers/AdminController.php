<?php

class AdminController extends \BaseController {

    /**
     * Instance of Admin model
     * @var object
     */
    protected $admin_model;

    /**
     * Instance of UserController controller
     * @var object
     */
    protected $user_controller;

    public function __construct( Admin $admin, UserController $user_controller ) {

        parent::__construct();

        $this->admin_model = $admin;

        $this->user_controller = $user_controller;



        $this->beforeFilter( 'admin_show_all', array( 'only' => array( 'index' ) ) );

        $this->beforeFilter( 'admin_show_one', array( 'only' => array( 'show' ) ) );

        $this->beforeFilter( 'admin_create', array( 'only' => array( 'create', 'store' ) ) );

        $this->beforeFilter( 'admin_update', array( 'only' => array( 'edit' ) ) );

        $this->beforeFilter( 'admin_delete', array( 'only' => array( 'destroy' ) ) );

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
        
        $admins = $this->admin_model->orderBy( 'first_name' )->paginate( 10 );
        return View::make( 'users.admins.indexAdmin', compact( 'admins' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data = array();
        
        $default_assigned_rules = array();
        foreach ( $this->admin_default_rules as $rule_name ) {
            $rule                     = Rule::where( 'rule_name', '=', $rule_name )->first();
            $default_assigned_rules[] = $rule->id;
        }//foreach ($this->admin_default_rules as $rule_name)
        
        $data[ 'default_assigned_rules' ] = $default_assigned_rules;

        return View::make( 'users.admins.createAdmin', compact( 'data' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->admin_model->create_rules );

        if ( $validation->passes() ) {
            DB::beginTransaction();
//        get user id for new user from users table
            $user_id = $this->user_controller->store( 'admin' );
            if ( !is_numeric( $user_id ) ) {
                if ( is_object( $user_id ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_id );
                }
                return Redirect::back()->withInput()->with( 'error', $user_id );
            }//( !is_numeric( $user_id ) )

            $this->admin_model->user_id      = $user_id;
            $this->admin_model->first_name   = $inputs[ 'first_name' ];
            $this->admin_model->last_name    = $inputs[ 'last_name' ];
            
            if ( $this->auth_user->hasRule('admin_activate') ) {
            $this->admin_model->active_admin = (isset( $inputs[ 'active_admin' ] )) ? true : false;
            }//if ( $this->auth_user->hasRule('admin_activate') )

            if ( $this->admin_model->save() ) {
                DB::commit();
                return Redirect::to( 'owner/' )->with( 'message', 'Admin created successfully' );
            } //( $this->admin_model->save() )
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
        $user_info = $this->admin_model->findOrFail( $id );

        $user = $user_info->user()->first();

        return View::make( 'users.admins.showAdmin', array( 'user' => $user, 'user_info' => $user_info ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $admin = $this->admin_model->findOrFail( $id );

        $data = array();

        $user = $admin->user()->first();

        $item_rules = $user->rules;

        $assigned_rules_ids = array();

        foreach ( $item_rules as $rule ) {
            if ( !is_null( $rule->pivot->active_rule ) && 0 != $rule->pivot->active_rule ) {
                $assigned_rules_ids[] = $rule->id;
            }//if ( !is_null( $rule->pivot->active_rule ) && 0 != $rule->pivot->active_rule )
        }//foreach ( $item_rules as $rule)

        $data[ 'assigned_rules_ids' ] = $assigned_rules_ids;

        return View::make( 'users.admins.editAdmin', compact( 'admin', 'user', 'data' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {

        $admin = $this->admin_model->findOrFail( $id );

        //        check if authenticated user is not in owner group or account owner
        if ( !$this->check_owner ) {
            if ( $this->auth_user->id != $admin->user_id ) {
                return Redirect::back()->withError( 'Error with permissons!' );
            }//if ( $this->auth_user->id != $admin->user_id )
        }//if ( !$this->check_owner )

        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->admin_model->update_rules );

        if ( $validation->passes() ) {

            $user_update = $this->user_controller->update( $admin->user_id, 'admin' );

            if ( $user_update != 'success' ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput()->with( 'error', $user_update );
            }

            if ( $this->auth_user->hasRule('admin_activate') ) {
                $admin->active_admin = (isset( $inputs[ 'active_admin' ] )) ? TRUE : FALSE;
            }//if ( $this->auth_user->hasRule('admin_activate')

            $admin->first_name = $inputs[ 'first_name' ];
            $admin->last_name  = $inputs[ 'last_name' ];

            if ( $admin->save() ) {
                return Redirect::to( 'admin/' . $admin->id )->with( 'message', 'Admin ' . $admin->name . ' updated successfully.' );
            } else {
                return Redirect::back()->withInput()->with( 'error', 'Error during saving admin' );
            }
        } else {
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

        $admin = $this->admin_model->findOrFail( $id );

//        start transaction
        DB::beginTransaction();

        $user_delete = $this->user_controller->destroy( $admin->user_id );
        if ( $user_delete != 'success' ) {
            return Redirect::back()->with( 'error', $user_delete );
        }

        if ( $admin->delete() ) {
            DB::commit();
            return Redirect::to( '/' )->with( 'message', 'Admin ' . $admin->name . ' has been deleted!' );
        } else {
            DB::rollBack();
            return Redirect::back()->with( 'error', 'Sorry, could not delete admin' );
        }
    }
    
//    Helper methods
    
    /**
     * Ajax search for admins
     * @return array
     */
    private function ajaxSearch() {
        $input = Input::get('search');
        
        $data = array();
        
        $search_result = '';
        
        $check = Admin::where('first_name', 'like', '%'.$input.'%')->orWhere('last_name', 'like', '%'.$input.'%')->exists();
        
        if ( $check ) {
            $admins = Admin::where('first_name', 'like', '%'.$input.'%')->orWhere('last_name', 'like', '%'.$input.'%')->get(array('id', 'first_name', 'last_name'));
            
            foreach ( $admins as $admin) {
                $search_result .= '<div>'.link_to_route('admin.show', $admin->first_name.' '.$admin->last_name, array($admin->id)).'</div>';
            }//foreach ( $admins as $admin)
            $data['search_result'] = $search_result;
        }//if ( $check )
        else{
            $data['error'] = 'Admin not found!';
        }
        
        return $data;
    }

}
