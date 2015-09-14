<?php
/**
 * Franchisor Filters
 *
 * @author Mohamed Atef
 */
class FranchisorFilters extends BaseFilter{
    
    /**
     * Franchisor Show All filter
     * @return boolean|object
     */
    public function showAll( ) {
        $check_permission = $this->ruleCheck('franchisor_show_all');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }
    }
    
    /**
     * Franchisor Show One Filter
     * @return boolean|object
     */
    public function showOne( ) {
        $check_permission = $this->ruleCheck('franchisor_show_one');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
//        check if authenticated user is not in admin group or account owner
        if ( !$this->check_admin ) {
            $franchisor_id = (int)Request::segment(2);
            $franchisor_object = Franchisor::find($franchisor_id);
            if ( !is_object( $franchisor_object ) || ($this->auth_user->id != $franchisor_object->user_id) ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( !is_object( $franchisor_object ) || ($this->auth_user->id != $franchisor_object->user_id) )
        }//if ( !$this->check_admin )
    }
    
    /**
     * Franchisor Create filter
     * @return boolean|object
     */
    public function create( ) {
        $check_permission = $this->ruleCheck('franchisor_create');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }
    }
    
    public function update( ) {
        $check_permission = $this->ruleCheck('franchisor_update');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
//        check id authenticated user is not in admin gruop and is not account owner
        if ( !$this->check_admin ) {
            $franchisor_id = (int) Request::segment(2);
            $franchisor_object = Franchisor::find($franchisor_id);
            
            if ( !is_object( $franchisor_object ) || ($this->auth_user->id != $franchisor_object->user_id) ) {
                return Redirect::back()->withError('Error with permission!');
            }//if ( !is_object( $franchisor_object ) || ($this->auth_user->id != $franchisor_object->user_id)
        }//if ( !$this->check_admin )
    }
    
    public function delete(  ) {
        $check_permission = $this->ruleCheck('franchisor_delete');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }
    }
}
