<?php

/**
 * SupplierFilters
 *
 * @author Mohamed Atef 
 */
class SupplierFilters extends BaseFilter{
    
    /**
     * Supplier Show All filter
     * @return boolean|object
     */
    public function showAll( ) {
        $check_permission = $this->ruleCheck('supplier_show_all');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }
    }
    
    /**
     * Supplier Show One filter
     * @return boolean|object
     */
    public function showOne( ) {
        $check_permission = $this->ruleCheck('supplier_show_one');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
//        check if authenticated user is not in admin group that is account owner
        if ( !$this->check_admin ) {
            $supplier_id = (int)Request::segment(2);
            $supplier_object = Supplier::find($supplier_id);
            if ( !is_object( $supplier_object ) || ($this->auth_user->id != $supplier_object->user_id) ) {
                return Redirect::back()->withError('Error with permissions!');
            }//if ( !is_object( $supplier_object ) || ($this->auth_user->id != $supplier_object->user_id) )
        }//if ( !$this->check_admin )
    }
    
    /**
     * Supplier create filter
     * @return boolean|object
     */
    public function create( ) {
        $check_permission = $this->ruleCheck('supplier_create');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permissions!');
        }//if ( !is_object( $check_permission ) || FALSE == $check_permission )
    }
    
    /**
     * Supplier Update filter
     * @return boolean|object
     */
    public function update( ) {
        $check_permission = $this->ruleCheck('supplier_update');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return \Illuminate\Support\Facades\Redirect::back()->withError('Error with permissions!');
        }//if ( !is_bool( $check_permission ) || FALSE == $check_permission )
        
//        check if authenticated user is not in admin group that is account owner
        if ( !$this->check_admin ) {
            $supplier_id = (int)Request::segment(2);
            $supplier_object = Supplier::find($supplier_id);
            
            if ( !is_object( $supplier_object ) || ($this->auth_user->id != $supplier_object->user_id) ) {
                return Redirect::back()->withError('Error with permissions!');
            }//if ( !is_object( $supplier_object ) || ($this->auth_user->id != $supplier_object->user_id) )
        }//if ( !$this->check_admin )
    }
    
    /**
     * Supplier Delete filter
     * @return boolean| object
     */
    public function delete( ) {
        $check_permission = $this->ruleCheck('supplier_delete');
        
        if ( !is_bool( $check_permission ) || FALSE == $check_permission ) {
            return Redirect::back()->withError('Error with permission!');
        }
    }
}
