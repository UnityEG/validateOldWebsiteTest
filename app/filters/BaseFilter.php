<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseFilter
 *
 * @author mohamed
 */
class BaseFilter {

    /**
     * Instance of Authenticated user
     * @var object
     */
    protected $auth_user;
    
    /**
     * check if authenticated user is developer, owner
     * @var boolean
     */
    protected $check_owner;


    /**
     * check if authenticated user is developer, owner or admin
     * @var boolean
     */
    protected $check_admin;

    public function __construct() {
        
        if ( !is_null( Auth::user()) ) {
            $this->auth_user = Auth::user();
            
            $this->check_admin = ($this->auth_user->active == TRUE && (
                    ('developer' == $this->auth_user->user_type) ||
                    ('owner' == $this->auth_user->user_type) ||
                    ('admin' == $this->auth_user->user_type)
                    ))? TRUE : FALSE;
            
            $this->check_owner = (($this->auth_user->active == true) && (
                    ('developer' == $this->auth_user->user_type) || 
                    ('owner' == $this->auth_user->user_type)
                    ))? TRUE : FALSE;
        }//if($this->auth_user = Auth::user())
    }

    /**
     * check if the rule is associate to the logged in user and activated
     * @param string $rule_name
     * @return string|boolean
     */
    protected function ruleCheck( $rule_name ) {
        return $this->auth_user->hasRule( $rule_name );
    }

}
