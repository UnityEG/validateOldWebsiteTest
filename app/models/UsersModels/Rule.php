<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Model of rules table
 *
 * @author mohamed
 */
class Rule extends Eloquent{
    use SoftDeletingTrait;
    
    /**
     * Dates columns in the table
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Table name
     * @var string
     */
    protected $table = 'rules';
    
    /**
     * the fillable columns in the database
     * @var array
     */
    protected $fillable = ['rule_name'];
    
    /**
     * Creating Rule rules
     * @var array
     */
    public $create_rules = ['rule_name'=>'required|string|unique:rules'];
    
    /**
     * Updating Rule rules
     * @var array
     */
    public $update_rules = ['rule_name'=>'required|string'];

    /**
     * Relation method between Rule Model and User Model ( many to many)
     * @return object
     */
    public function users( ) {
        return $this->belongsToMany( 'User', 'users_rules_link', 'rule_id', 'user_id')->withPivot( 'active_rule');
    }
}
