<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersRulesLink
 *
 * @author mohamed
 */
class UsersRulesLink extends Eloquent {
    
    /**
     * Table name
     * @var string
     */
    protected $table = 'users_rules_link';
    
    /**
     * enable/diable timestamp
     * @var boolean
     */
    public $timestamps = false;

    /**
     * fillable columns in the database
     * @var array
     */
    protected $fillable = ['user_id', 'rule_id', 'active_rule' ];
    
    /**
     * Rules to create new link
     * @var array
     */
    protected $create_rules = [
        'user_id'     => 'integer',
        'rule_id'     => 'integer',
        'active_rule' => 'boolean'
    ];
    
    /**
     * Rules to update new link
     * @var array
     */
    protected $update_rules = [
        'user_id'     => 'integer',
        'rule_id'     => 'integer',
        'active_rule' => 'boolean'
    ];
    
    /**
     * Rleation method between UsersRulesLink Model and User Model ( many to one)
     * @return object
     */
    function user(  ) {
        return $this->belongsTo( 'User');
    }

}
