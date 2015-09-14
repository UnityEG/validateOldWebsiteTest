<?php

class FranchisorController extends \BaseController {

    /**
     *  Instance of Frachisor model
     * @var object
     */
    protected $franchisor_model;

    /**
     * Instance of UserController controller
     * @var object
     */
    protected $user_controller;

    public function __construct( Franchisor $franchisor_model, UserController $user_controller ) {

        parent::__construct();

        $this->franchisor_model = $franchisor_model;

        $this->user_controller = $user_controller;

        $this->beforeFilter( 'franchisor_show_all', array( 'only' => array( 'index' ) ) );

        $this->beforeFilter( 'franchisor_show_one', array( 'only' => array( 'show' ) ) );

        $this->beforeFilter( 'franchisor_create', array( 'only' => array( 'create' ) ) );

        $this->beforeFilter( 'franchisor_update', array( 'only' => array( 'edit' ) ) );

        $this->beforeFilter( 'franchisor_delete', array( 'only' => array( 'destroy' ) ) );

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
        
        $group = $this->franchisor_model->paginate( 10 );

        return View::make( 'users.franchisors.indexFranchisor', compact( 'group' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data = array();

        $default_assigned_rules = array();
        foreach ( $this->franchisor_default_rules as $rule_name ) {
            $rule                     = Rule::where( 'rule_name', '=', $rule_name )->first();
            $default_assigned_rules[] = $rule->id;
        }

        $data[ 'default_assigned_rules' ] = $default_assigned_rules;
        return View::make( 'users.franchisors.createFranchisor', compact( 'data' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->franchisor_model->create_rules );

        if ( $validation->passes() ) {
//            begin transaction
            DB::beginTransaction();

            $user_id = $this->user_controller->store( 'franchisor' );
            if ( !is_numeric( $user_id ) ) {
                if ( is_object( $user_id ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_id );
                }
                return Redirect::back()->withInput()->withError( $user_id );
            }//if ( !is_numeric( $user_id ) )

            $this->franchisor_model->user_id         = $user_id;
            $this->franchisor_model->franchisor_name = $inputs[ 'franchisor_name' ];
            $this->franchisor_model->contact         = $inputs[ 'contact' ];
            $this->franchisor_model->phone           = $inputs[ 'phone' ];

            if ( !is_null( $this->auth_user ) && $this->auth_user->hasRule( 'franchisor_activate' ) ) {
                $this->franchisor_model->active_franchisor = (isset( $inputs[ 'active_franchisor' ] )) ? TRUE : FALSE;
            }//if (!is_null( $this->auth_user)&&$this->auth_user->hasRule('franchisor_activate')) 


            if ( $this->franchisor_model->save() ) {
//                commit transaction
                DB::commit();
                return Redirect::to( 'franchisor/' )->withMessage( 'Franchisor ' . $this->franchisor_model->first_name . ' ' . $this->franchisor_model->last_name . ' created successfully' );
            }//if ( $this->franchisor_model->save() )
            else {
//                rollback transaction
                DB::rollBack();
                return Redirect::back()->withInput()->withError( 'Error while creating new franchisor!' );
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
        $item = $this->franchisor_model->findOrFail( $id );

        $item_info_user = $item->user()->first();

        $item_info_merchant = $item->merchant;

        return View::make( 'users.franchisors.showFranchisor', compact( 'item', 'item_info_user', 'item_info_merchant' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $item = $this->franchisor_model->findOrFail( $id );

        $item_info_user = $item->user()->first();

        $item_info_merchant = $item->merchant;

        $item_rules = $item_info_user->rules;

        $item_rule_ids = array();
        foreach ( $item_rules as $rule ) {
            if ( !is_null( $rule->pivot->active_rule ) && 0 != $rule->pivot->active_rule ) {
                $item_rule_ids[] = $rule->id;
            }
        }


        return View::make( 'users.franchisors.editFranchisor', compact( 'item', 'item_info_user', 'item_info_merchant', 'item_rule_ids' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        $franchisor = $this->franchisor_model->findOrFail( $id );

//        check if authenticated user is not in admin group or account owner
        if ( $this->check_admin ) {
            if ( $this->auth_user->id == $franchisor->user_id ) {
                return Redirect::back()->withError( "Error with pemission" );
            }//if ( $this->auth_user->id == $franchisor->user_id )
        }//if ( $this->check_admin )

        $inputs = Input::all();

        $validation = Validator::make( $inputs, $this->franchisor_model->update_rules );
        if ( $validation->passes() ) {

            $user_update = $this->user_controller->update( $franchisor->user_id, 'franchisor' );
            if ( 'success' != $user_update ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput()->withError( $user_update );
            }//if ( 'success' != $user_update )

            $franchisor->franchisor_name = $inputs[ 'franchisor_name' ];
            $franchisor->contact         = $inputs[ 'contact' ];
            $franchisor->phone           = $inputs[ 'phone' ];

            if ( !is_null( $this->auth_user ) && $this->auth_user->hasRule( 'franchisor_activate' ) ) {
                $franchisor->active_franchisor = (isset( $inputs[ 'active_franchisor' ] )) ? TRUE : FALSE;
            }//if ( !is_null( $this->auth_user) && $this->auth_user->hasRule('franchisor_activate') )


            if ( $franchisor->save() ) {
                return Redirect::to( 'franchisor/' . $franchisor->id )->withMessage( 'Franchisor ' . $franchisor->first_name . ' ' . $franchisor->last_name . ' updated successfully!' );
            }//if ( $franchisor->save() )
            else {
                return Redirect::back()->withInput()->withError( 'Error while updating!' );
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
        $franchisor = $this->franchisor_model->findOrFail( $id );
//        begin transaction
        DB::beginTransaction();

        $user_delete = $this->user_controller->destroy( $franchisor->id );

        if ( 'success' != $user_delete ) {
            if ( is_object( $user_delete ) ) {
                return Redirect::back()->withErrors( $user_delete );
            }
            return Redirect::back()->withError( 'Error while deleting User!' );
        }//if ( 'success' != $user_delete )

        if ( $franchisor->delete() ) {
//            commit transaction
            DB::commit();
            return Redirect::to( 'franchisor/' )->withMessage( 'Franchisor ' . $franchisor->first_name . ' ' . $franchisor->last_name . ' has been deleted successfully.' );
        }//if ( $franchisor->delete() )
        else {
//            rollback transaction
            DB::rollBack();
            return Redirect::back()->withError( 'Error while deleting Franchishor!' );
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
        
        $check = $this->franchisor_model->where('franchisor_name', 'like', '%'.$input.'%')->exists();
        
        if ( $check ) {
            $franchisors = $this->franchisor_model->where('franchisor_name', 'like', '%'.$input.'%')->get(array('id', 'franchisor_name'));
            foreach ( $franchisors as $franchisor) {
                $search_result .= "<div>".link_to_route('franchisor.show', $franchisor->franchisor_name, array($franchisor->id))."</div>";
            }//foreach ( $franchisors as $franchisor)
            $data['search_result'] = $search_result;
        }//if ( $check )
        else{
            $data['error'] = '<div>Franchisor not found!</div>';
        }
        
        return $data;
    }

}
