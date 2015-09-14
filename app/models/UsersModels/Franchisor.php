<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Franchisor extends Eloquent {

    use SoftDeletingTrait;
    
    /**
     * Date columns franchisor table
     * @var array
     */
    protected $dates     = ['deleted_at' ];
    
    /**
     * Table name
     * @var string
     */
    protected $table     = 'franchisors';
    
    /**
     * Fillable columns in franchisor table
     * @var array
     */
    protected $fillable  = ['franchisor_name', 'contact', 'phone', 'user_id', 'active_franchisor' ];
    
    /**
     * Create rules for franchisor table
     * @var array
     */
    public $create_rules = [
        'user_id'           => 'integer',
        'active_franchisor' => 'boolean',
        'franchisor_name'   => 'required|string',
        'contact'           => 'string',
        'phone'             => 'alpha_dash'
    ];
    
    /**
     * Update rules for franchisor table
     * @var array
     */
    public $update_rules = [
        'user_id'           => 'integer',
        'active_franchisor' => 'boolean',
        'franchisor_name'   => 'required|string',
        'contact'           => 'string',
        'phone'             => 'alpha_dash'
    ];

    /**
     * Relationship method between Franchisor Model and User Model (one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }
    
    /**
     * Relationship method between Franchisor Model and Merchant Model (one to many)
     * @return object
     */
    public function merchant() {
        return $this->hasMany( 'Merchant', 'franchisor_id' );
    }

}
