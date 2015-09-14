<?php

/**
 * Description of MerchantManagersFilters
 *
 * @author mohamed
 */
class MerchantManagersFilters {
    
    /**
     * Instance of Authenticated user
     * @var object
     */
    private $auth_user;


    public function __construct( ) {
        $this->auth_user = Auth::user();
    }
    
    public function showAll(  ) {
        $check_with_error = $this->ruleCheck('merchant_manager_show_all');
        
        if ( is_string($check_with_error)) {
            return Redirect::back()->withError($check_with_error);
        }
    }
    
   /**
    * Check about rule for authenticated user and check if rule is active
    * @param string $rule_name
    * @return string|boolean
    */
    private function ruleCheck($rule_name){
        
        return $this->auth_user->hasRule($rule_name);
    }
}
