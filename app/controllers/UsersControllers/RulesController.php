<?php

class RulesController extends \BaseController {

    /**
     * Instance of Rule model
     * @var object
     */
    private $rule_model;

    /**
     * Instance of User model
     * @var object
     */
    private $user_model;

    /**
     * Instance of UserController controller
     * @var object
     */
    private $user_controller;

    /**
     * Instance of UsersRulesLink model
     * @var object
     */
    private $users_rules_link_model;

    public function __construct( Rule $rule_model, UsersRulesLink $users_rules_link_model, User $user_model, UserController $user_controller ) {

        parent::__construct();

        $this->rule_model = $rule_model;

        $this->users_rules_link_model = $users_rules_link_model;

        $this->user_model = $user_model;

        $this->user_controller = $user_controller;

        $this->beforeFilter( 'check_owner' );

        $this->beforeFilter( 'check_developer', array( 'only' => array( 'create', 'store', 'destroy' ) ) );

        $this->beforeFilter( 'csrf', array( 'on' => 'post', 'on' => 'put', 'on' => 'delete' ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $data = array();
        if ( Request::ajax() ) {
            $data = $this->ajaxSearch();

            return Response::json( $data );
        }

        $group = $this->rule_model->orderBy( 'rule_name' )->paginate( 50 );

        $delete_rule = FALSE;
        if ( 'developer' == $this->auth_user->user_type ) {
            $delete_rule = TRUE;
        }

        return View::make( 'users.rules.indexRules', compact( 'group', 'delete_rule', 'data' ) );
    }

    /**
     * Display rules for Individula user
     * @param integer $id
     * @return object
     */
    public function rulesUser( $id ) {
        $data = array();
        if ( Auth::user()->isDeveloper() ) {
            $user = User::find( $id );
        }//if ( Auth::user()->isDeveloper() )
        else{
            $user = User::where('id', $id)->where('user_type', '!=', 'developer')->first();
        }
        

        $rules = $user->rules;

        $assigned_rules_ids = array();

        foreach ( $rules as $rule ) {
            if ( $rule->pivot->active_rule == 1 ) {
                $assigned_rules_ids[] = $rule->id;
            }//if ( $rule->pivot->active_rule == 1 )
        }//foreach ( $rules as $rule)

        $data[ 'user' ] = $user;

        $data[ 'assigned_rules_ids' ] = $assigned_rules_ids;

        return View::make( 'users.rules.rulesUser', compact( 'data' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make( 'users.rules.createRules' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();


        $validation = Validator::make( $inputs, $this->rule_model->create_rules );

        if ( $validation->passes() ) {

            $this->rule_model->rule_name = $inputs[ 'rule_name' ];

            if ( $this->rule_model->save() ) {

                $created_rule = $this->rule_model->find( $this->rule_model->id );

                $this->activeRuleToDeveloper( $created_rule );

                return Redirect::route( 'rules.index' )->withMessage( 'Rule ' . $created_rule->rule_name . ' created successfully.' );
            }//if ( $this->rule_model->save() )
            else {
                return Redirect::back()->withInput()->withError( 'Error while creating new rule' );
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

        $rule = $this->rule_model->findOrFail( $id );

        $item_info_user = $rule->users()->where( 'user_type', '!=', 'developer' )->get();

        $users_list = "<ul style='list-style:none;'>";
        foreach ( $item_info_user as $user ) {
//            check active_rule first
            $active_rule = $user->pivot->active_rule;

            if ( 0 != $active_rule && null != $active_rule ) {
                $users_list .= "<li>";

                $type = $user->user_type;

                $user_info_type = $user->$type()->first();

                $users_list .= ' ( ' . link_to_route( $type . '.show', $user->email, array( $user_info_type->id ) ) . ' )';

                $users_list .= "</li>";
            }
        }
        $users_list .="</ul>";

        $delete_rule = FALSE;
        if ( 'developer' == $this->auth_user->user_type ) {
            $delete_rule = TRUE;
        }

        $data[ 'rule' ] = $rule;

        $data[ 'users_list' ] = $users_list;

        $data[ 'delete_rule' ] = $delete_rule;
        return View::make( 'users.rules.showRules', compact( 'data' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $data = array();

        $rule = $this->rule_model->findOrFail( $id );

        $user_types = ['owner', 'admin', 'customer', 'merchant', 'merchant_manager', 'franchisor', 'supplier' ];

        foreach ( $user_types as $user_type ) {
            $data[ $user_type ] = $this->user_model->where( 'user_type', '=', $user_type )->get();
        }//foreach($user_types as $user_type)

        $edit_rule_name = false;

        if ( 'developer' == $this->auth_user->user_type ) {
            $edit_rule_name = TRUE;
        }

        $data[ 'rule' ] = $rule;

        $data[ 'edit_rule_name' ] = $edit_rule_name;

        $data[ 'user_types' ] = $user_types;

        return View::make( 'users.rules.editRules', compact( 'data' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        $rule = $this->rule_model->findOrFail( $id );

        if ( 'developer' == $this->auth_user->user_type ) {
            $inputs = Input::all();

            $validation = Validator::make( $inputs, $this->rule_model->update_rules );

            if ( $validation->passes() ) {

                $rule->rule_name = $inputs[ 'rule_name' ];

                if ( !$rule->save() ) {
                    return Redirect::back()->withInput()->withError( 'Error while updating rule!' );
                }//if ( $rule->save() )
            }//if ( $validation->passes() )
            else {
                return Redirect::back()->withInput()->withErrors( $validation );
            }
        }//if ( 'developer' == $this->auth_user->user_type )
        $this->semiEdit( $rule, $id );

        return Redirect::route( 'rules.show', $id )->withMessage( 'Rule updated successfully.' );
    }

    /**
     * update rules for individual user
     * @param integer $id
     * @return object
     */
    public function updateRulesUser( $id ) {
        $user   = User::findOrFail( $id );
        $inputs = Input::all();
        if ( isset( $inputs[ 'reset_rules' ] ) && ($inputs[ 'reset_rules' ] == TRUE) ) {

            if ( $this->user_controller->resetRules( $user->id, $user->user_type ) == TRUE ) {
                return Redirect::route( 'rules.index' )->withMessage( 'Rules have been updated to the user.' );
            }//if ($this->user_controller->resetRules( $user->id, $user->user_type ) == TRUE )
        }//if ( isset($inputs['reset_rules']) && ($inputs['reset_rules'] == TRUE) )
        else {
            if ( $this->user_controller->updateRules( $user->id ) ) {
                return Redirect::route( 'rules.index' )->withMessage( 'Rules have been updated to the user.' );
            }//if($this->user_controller->updateRules($id))
        }
        return Redirect::back()->withInput()->withError( 'Error while updating user\'s rules' );
    }

    /**
     * method helper to update method in case of developer
     * @param object $rule
     * @param integer $id
     * @return object
     */
    private function semiEdit( $rule, $id ) {

        $records_from_pivot = $this->users_rules_link_model->where( 'rule_id', '=', $id )->get();

        $inputs = Input::all();
//        deactivate rule from all users except developers
        if ( !isset( $inputs[ 'users' ] ) || empty( $inputs[ 'users' ] ) ) {
            foreach ( $records_from_pivot as $record ) {
                $record->active_rule = 0;
                $record->save();
            }//foreach ( $records_from_pivot as $record)

            $this->activeRuleToDeveloper( $rule );

            return Redirect::route( 'rules.show', $id )->withMessage( 'Rule updated successfully.' );
        }//if ( !isset($inputs['users']) || empty($inputs['users']) )
//        update existing users associated with rule and not found in $input['users']
        foreach ( $records_from_pivot as $record ) {
            if ( !in_array( $record->user_id, $inputs[ 'users' ] ) ) {
                $record->active_rule = 0;
                $record->save();
            }
        }//foreach ( $records_from_pivot as $record)

        $this->activeRuleToDeveloper( $rule );

        foreach ( $inputs[ 'users' ] as $user_id ) {
            $user = $rule->users()->where( 'user_id', '=', $user_id )->first();

            if ( !is_object( $user ) ) {
                $rule->users()->attach( $user_id );
                $user = $rule->users()->where( 'user_id', '=', $user_id )->first();
            }//if ( !is_object( $user ) )

            $rule->users()->updateExistingPivot( $user_id, array( 'active_rule' => 1 ) );
        }//foreach ( $inputs['users'] as $user_id)

        return Redirect::route( 'rules.show', $id )->withMessage( 'Rule updated successfully.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( $id ) {
        $rule = $this->rule_model->findOrFail( $id );

        $users_related_with_rule = $rule->users()->get();

        if ( $rule->delete() ) {

            foreach ( $users_related_with_rule as $user ) {
                $user->rules()->UpdateExistingPivot( $rule->id, array( 'active_rule' => 0 ) );
            }

            return Redirect::route( 'rules.index' )->withMessage( 'Rule deleted successfully.' );
        } else {
            return Redirect::back()->withError( 'Error while deleting rule!' );
        }
    }

//    Helper methods

    /**
     * activate rules for developers and owners and adding relation if not exist
     * @param object $rule
     */
    private function activateRuleToDevelopersAndOwners( $rule ) {
        $developers_and_owners = $this->user_model->whereIn( 'user_type', array( 'developer', 'owner' ) )->get();

        foreach ( $developers_and_owners as $developer_or_owner ) {

            $check_relation_exist = $rule->users()->where( 'user_id', '=', $developer_or_owner->id )->first();

            if ( !is_object( $check_relation_exist ) ) {
                $rule->users()->attach( $developer_or_owner->id );
            }

            $rule->users()->updateExistingPivot( $developer_or_owner->id, array( 'active_rule' => 1 ) );
        }//foreach($developers_and_owners as $developer_or_owner)
    }

    /**
     * active rules to owners and add relation if not exist
     * @param object $rule
     */
    private function activateRuleToOwner( $rule ) {
        $owners = $this->user_model->where( 'user_type', '=', 'owner' )->get();

        foreach ( $owners as $owner ) {
            $check_owner_rule_relation = $rule->users()->where( 'user_id', '=', $owner->id )->first();

            if ( !is_object( $check_owner_rule_relation ) ) {
                $rule->users()->attach( $owner->id );
            }

            $rule->users()->updateExistingPivot( $owner->id, array( 'active_rule' => 1 ) );
        }//foreach ( $owners as $owner)
    }

    /**
     * active rules to developers and add relation if not exist
     * @param object $rule
     */
    private function activeRuleToDeveloper( $rule ) {
        $delelopers = $this->user_model->where( 'user_type', '=', 'developer' )->get();

        foreach ( $delelopers as $developer ) {

            $check_developer_rule_relation = $rule->users()->where( 'user_id', '=', $developer->id )->first();

            if ( !is_object( $check_developer_rule_relation ) ) {
                $rule->users()->attach( $developer->id );
            }//if ( !is_object( $check_developer_rule_relation ) )

            $rule->users()->updateExistingPivot( $developer->id, array( 'active_rule' => 1 ) );
        }//foreach ( $delelopers as $developer)
    }

    public function ajaxSearch() {
        $input = Input::get( 'search' );

        $data = array();

        $search_result = '';

        $rule_check = Rule::where( 'rule_name', 'like', '%' . $input . '%' )->exists();

        if ( $rule_check ) {
            $rules = Rule::where( 'rule_name', 'like', '%' . $input . '%' )->get( array( 'id', 'rule_name' ) );

            foreach ( $rules as $rule ) {
                $search_result .= "<div>" . link_to_route( 'rules.show', $rule->rule_name, array( $rule->id ) ) . "</div>";
            }//foreach ( $rules as $rule)
            $data[ 'search_result' ] = $search_result;
        }//if ( $rule_check )
        else {
            $data[ 'error' ] = '<div>No rule found!</div>';
        }


        return $data;
    }

}
