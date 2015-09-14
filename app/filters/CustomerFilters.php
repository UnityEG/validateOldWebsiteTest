<?php

/**
 * Description of CustomerFilters
 *
 * @author mohamed
 */
class CustomerFilters extends BaseFilter{
    
    /**
     * Customer Show All filter
     * @return string
     */
    public function showAll( ) {
        $check_permission = $this->ruleCheck('customer_show_all');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission) {
            return Redirect::back()->withError("Error with permissions!");
        }
    }
    
    /**
     * Customer Show One filter
     * @return object
     */
    public function showOne( ) {
        $check_permission = $this->ruleCheck('customer_show_one');
                
        if ( !is_bool( $check_permission ) || FALSE == $check_permission) {
            return Redirect::back()->withError("Error with permissions!");
        }
        
//        check if authenticated user is not in admin group is the owner of the account
        if ( !$this->check_admin ) {
            $customer_id = (int)Request::segment(2);
            $customer_object = Customer::find($customer_id);
            
            if ( !is_object( $customer_object ) || ($this->auth_user->id != $customer_object->user_id)) {
                return Redirect::back()->withError('Error with permission!');
            }
        }//if ( !$this->check_admin )
    }
    
    /**
     * Customer Create filter
     * @return object
     */
    public function create( ) {
        
        if ( is_null( $this->auth_user) ) {
            return;
        }
        $check_permission = $this->ruleCheck('customer_create');
        
        if ( !is_bool( $check_permission) || FALSE == $check_permission ) {
            return Redirect::back()->withError("Error with permissions!");
        }
    }
    
    /**
     * Customer Update filter
     * @return object
     */
    public function update( ) {
        $check_permission = $this->ruleCheck('customer_update');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission) {
            return Redirect::back()->withError("Error with permissions!");
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission)
        
//        check if authenticated user is not in admin group that is the owner account
        if ( !$this->check_admin ) {
            $customer_id = (int)Request::segment(2);
            $customer_object = Customer::find($customer_id);
            
            if ( !is_object( $customer_object ) || ($this->auth_user->id != $customer_object->user_id) ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( !is_object( $customer_object ) || ($this->auth_user->id != $customer_object->user_id) )
        }//if ( !$this->check_admin )
    }
    
    /**
     * Customer Delete filter
     * @return object
     */
    public function delete( ) {
        $check_permission = $this->ruleCheck('customer_delete');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission) {
            return Redirect::back()->withError("Error with permissions!");
        }
    }
}
