<?php

/**
 * Description of MerchantManagersFilters
 *
 * @author mohamed
 */
class MerchantManagerFilters extends BaseFilter{
    
    /**
     * Merchant Manager show all filter
     * @return boolean | object
     */
    public function showAll(  ) {
        $check_permission = $this->ruleCheck('merchant_manager_show_all');
        
        if ( !is_bool( $check_permission ) || False == $check_permission ) {
            return Redirect::back()->withError("Error with permission!");
        }
    }
    
    /**
     * Merchant Manager show one filter
     * @return boolean|object
     */
    public function showOne( ) {
        $check_permission = $this->ruleCheck('merchant_manager_show_one');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }//if ( !is_bool( $check_permission ) || False == $check_permission )
        
//        check if authenticated user is merchant that the merchant manager belongs to him
        if ( 'merchant' == $this->auth_user->user_type ) {
            $merchant = Merchant::where('user_id', '=', $this->auth_user->id)->first();
            $merchant_manager_id = (int)Request::segment(2);
            $merchant_manager_object = MerchantManager::find($merchant_manager_id);
            if ( !is_object( $merchant_manager_object ) || !is_object( $merchant) || ($merchant->id != $merchant_manager_object->merchant_id) ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( !is_object( $merchant_manager_object ) || !is_object( $merchant) || ($merchant->id != $merchant_manager_object->merchant_id) )
            else{
                return;
            }
        }//if ( 'merchant' == $this->auth_user->user_type )
        
//        check if authenticated user is not in admin group that is account owner
        if ( !$this->check_admin ) {
            $merchant_manager_id = (int)Request::segment(2);
            $merchant_manager_object = MerchantManager::find($merchant_manager_id);
            if ( !is_object( $merchant_manager_object ) || ($this->auth_user->id != $merchant_manager_object->user_id) ) {
                return Redirect::back()->withError("Error with permission!");
            }//if ( !is_object( $merchant_manager_object ) || ($this->auth_user->id != $merchant_manager_object->user_id) )
        }//if ( !$this->check_admin )
    }
    
    /**
     * Merchant Manager Create filter
     * @return boolean|object
     */
    public function create( ) {
        $check_permission = $this->ruleCheck('merchant_manager_create');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permmissions!');
        }
    }
    
    /**
     * Merchant Manager update filter
     * @return boolean|object
     */
    public function update( ) {
        $check_permission = $this->ruleCheck('merchant_manager_update');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
        
//        check if authenticated user is not in admin group that is account owner
        if ( !$this->check_admin ) {
            $merchant_manager_id = (int)Request::segment(2);
            $merchant_manager_object = MerchantManager::find($merchant_manager_id);
            if ( !is_object( $merchant_manager_object ) || ($this->auth_user->id != $merchant_manager_object->user_id) ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( !is_object( $merchant_manager_object ) || ($this->auth_user->id != $merchant_manager_object->user_id) )
        }//if ( !$this->check_admin )
    }
    
    /**
     * Merchant Manager delete filter
     * @return boolean|object
     */
    public function delete( ) {
        $check_permission = $this->ruleCheck('merchant_manager_delete');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
    }
}
