<?php

/**
 * Description of MerchantFilters
 *
 * @author mohamed
 */
class MerchantFilters extends BaseFilter {

    /**
     * Merchant Show All filter
     * @return type
     */
    public function showAll() {
        $check_permission = $this->ruleCheck( 'merchant_show_all' );

        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError( "Error with permission!" );
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
    }

    /**
     * Merchant Show one filter
     * @return type
     */
    public function showOne(  ) {
        $check_permission = $this->ruleCheck( 'merchant_show_one' );

        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError( "Error with permission!" );
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
        
        if ( 'franchisor' == $this->auth_user->user_type ) {
            $merchant_id = (int)Request::segment(2);
            $merchant_object = Merchant::find($merchant_id);
            $franchisor_object = Franchisor::where('user_id', '=', $this->auth_user->id)->first();
            if ( !is_object( $merchant_object ) || ($merchant_object->franchisor_id != $franchisor_object->id) ) {
                return Redirect::back()->withError('Error with permissions!');
            }//if ( !is_object( $merchant_object ) || ($merchant_object->franchisor_id != $franchisor_object->id) )
            else{
                return;
            }
        }//if ( 'franchisor' == $this->auth_user->user_type )

//        check if authenticated user is not in admin group that is account owner
        if ( !$this->check_admin ) {
//            get merchant id from url(segment(2))
            $merchant_id = (int)Request::segment(2);
            $merchant_object = Merchant::findOrFail($merchant_id);
            
            if (!is_object( $merchant_object ) || ($this->auth_user->id != $merchant_object->user_id) ) {
                return Redirect::back()->withError( "Error with permissions!" );
            }//if (!is_object( $merchant_object ) || ($this->auth_user->id != $merchant_object->user_id) )
        }//if ( !$this->check_admin )
    }

    /**
     * Merchant Create filter
     * @return type
     */
    public function create() {
        //opening for non registered users to register
        if ( is_null( $this->auth_user ) ) {
            return;
        }

//        check for permission on logged in users
        $check_permission = $this->ruleCheck( 'merchant_create' );

        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError( "Error with permission!" );
        }
    }

    /**
     * Merchant Update filter
     * @return type
     */
    public function update() {
        $check_permission = $this->ruleCheck( 'merchant_update' );

        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError( "Error with permission!" );
        }

//        check that if user is not developer, owner, admin then check if user is not merchant and targeted merchant is the same autenticated user
        if ( !$this->check_admin ) {
//            merchant id from url
            $merchant_id = (int)Request::segment(2);
            $merchant_object = Merchant::findOrFail($merchant_id);
            if ( 'merchant' != $this->auth_user->user_type || !is_object( $merchant_object ) || ($this->auth_user->id != $merchant_object->user_id) ) {
                return Redirect::back()->withError( "Error with permission!" );
            }//if ( 'merchant' != $this->auth_user->user_type || !is_object( $merchant_object ) || ($this->auth_user->id != $merchant_object->user_id) )
        }//if ( !$this->check_admin )
    }

    /**
     * Merchant Delete filter
     * @return type
     */
    public function delete() {
        $check_permission = $this->ruleCheck( 'merchant_delete' );

        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError( "Error with permission!" );
        }
    }

}
