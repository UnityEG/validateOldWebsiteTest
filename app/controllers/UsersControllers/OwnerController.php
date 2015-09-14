<?php

class OwnerController extends \BaseController {

    /**
     * instance from UserController
     * @var object
     */
    protected $user_controller;

    /**
     * instance from Owner model
     * @var object
     */
    protected $owner_model;

    /**
     * owner type to help in view
     * @var string
     */
    private $type = 'owner';

    public function __construct( UserController $user_controller, Owner $owner_model ) {
        parent::__construct();
        
//        Instance from Owner model
        $this->owner_model = $owner_model;

//        Instance from UserController class
        $this->user_controller = $user_controller;
        
//        $this->beforeFilter('csrf', array('on'=>'put'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
        if ( !$this->check_owner ) {
            return Redirect::to( '/' )->with( 'error', 'Restricted Area' );
        }
        $response_type = Input::get('type');
        $users = $this->user_controller->index();

        if ( isset($response_type) && !empty($response_type) ) {
            return $users;
        }
        return View::make( 'users.index', compact( 'users') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        return Response::json('creating new owner');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show( $id ) {
        
        if ( !$this->check_owner ) {
            return Redirect::to( '/' )->with( 'error', 'Restricted area' );
        }
        $owner = $this->owner_model->findOrFail( $id );

        $user_info = $owner->user()->first();
        
        $response_type = Input::get('type');
        
        if(isset($response_type) && !empty($response_type)){
            return $owner;
        }
        return View::make( 'users.owners.showOwner', compact( 'owner', 'user_info' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        
        $data = array();
        
        if ( !$this->check_owner ) {
            return Redirect::to( '/' )->with( 'error', 'Restricted area' );
        }
        $owner     = $this->owner_model->findOrFail( $id );
        $user_info = $owner->user()->first();
        
        $owner_rules = $user_info->rules;
        $active_owner_rules = array();
        foreach($owner_rules as $rule){
            if ( !is_null( $rule ) && (0 != $rule->pivot->active_rule) ) {
                $active_owner_rules[$rule->rule_name] = $rule->id;
            }
        }
        
        $data['active_owner_rules'] = $active_owner_rules;

        return View::make( 'users.owners.editOwner', compact( 'owner', 'user_info', 'data' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        $inputs = Input::all();
        
        $response_type = Input::get('type');
        
        $validation = Validator::make( $inputs, $this->owner_model->update_rules );
        if ( $validation->passes() ) {
            
            $owner = $this->owner_model->findOrFail( $id );

            $user_update = $this->user_controller->update( $owner->user_id, 'owner' );
            if ( $user_update != 'success' ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput() - with( 'error', $user_update );
            }//if ( $user_update != 'success' )

            $owner->first_name = $inputs[ 'first_name' ];
            $owner->last_name = $inputs[ 'last_name' ];
            $owner->active_owner = true;

            if ( $owner->save() ) {
                
                if ( isset($response_type) && !empty($response_type) ) {
                    return Response::json('owner updated successfully');
                }
                
                return Redirect::to( 'owner/' . $owner->id )->with( 'message', $owner->first_name . ' updated successfully.' );
            } else {
                
                if ( isset($response_type) && !empty($response_type) ) {
                    return Response::json('Error while saving owner!');
                }
                
                return Redirect::back()->withInput()->with( 'error', 'Error during updating owner' );
            }
        }//if ( $validation->passes() )
        else {
            
            if ( isset($response_type) && !empty($response_type) ) {
                return Response::json('Error with validation');
            }
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
        $response_type = Input::get('type');
        if ( isset($response_type) && !empty($response_type) ) {
            return Response::json('why you want to delete owner?');
        }
        return Redirect::back()->withError('Can not delete Owner');
    }

}
