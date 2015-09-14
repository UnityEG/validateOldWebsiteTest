<?php

/**
 * Description of AdminFilters
 *
 * @author mohamed
 */
class AdminFilters extends BaseFilter{
    

    /**
     * Admin show all filter
     * @return boolean|object
     */
    public function showAll() {
        $check_permission = $this->ruleCheck('admin_show_all');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission');
        }
    }
    
     /**
     * Check Admin filter
     * @return type
     */
    public function showOne( ) {
        $check_permission = $this->ruleCheck('admin_show_one');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
//        check if authenticated user is not in owner group or account owner
        if ( !$this->check_owner ) {
            $admin_id = (int)Request::segment(2);
            $admin_object = Admin::find($admin_id);
            if ( !is_object( $admin_object ) || ($this->auth_user->id != $admin_object->user_id) ) {
                return Redirect::back()->withError('Error with permissions!');
            }//if ( !is_object( $admin_object ) || ($this->auth_user->id != $admin_object->user_id) )
        }//if ( !$this->check_owner )
    }
    
    /**
     * Admin Create filter
     * @return boolean|object
     */
    public function create() {
        $check_permission = $this->ruleCheck('admin_create');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }
    }
    
    /**
     * Admin Update filter
     * @return boolean|object
     */
    public function update( ) {
        $check_permission = $this->ruleCheck('admin_update');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
//        check if authenticated user is not in owner group or account owner
        if ( !$this->check_owner ) {
            $admin_id = (int)Request::segment(2);
            $admin_object = Admin::find($admin_id);
            
            if ( !is_object( $admin_object ) || ($this->auth_user->id != $admin_object->user_id) ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( !is_object( $admin_object ) || ($this->auth_user->id != $admin_object->user_id) )
        }//if ( !$this->check_owner )
    }
    
    /**
     * Admin Delete filter
     * @return boolean|object
     */
    public function delete( ) {
        $check_permission = $this->ruleCheck('admin_delete');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }
    }

}
