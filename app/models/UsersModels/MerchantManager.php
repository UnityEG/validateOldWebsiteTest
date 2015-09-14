<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class MerchantManager extends Eloquent {

    use SoftDeletingTrait;
    
    /**
     * Date columns in the database
     * @var array
     */
    protected $dates     = ['deleted_at' ];
    
    /**
     * Table name
     * @var string
     */
    protected $table     = 'merchant_managers';
    
    /**
     * Fillable columns in the database
     * @var array
     */
    protected $fillable  = ['first_name', 'last_name', 'user_id', 'active_merchant_manager' ];
    
    /**
     * Create rules for new merchant managers
     * @var array
     */
    public $create_rules = [
        'first_name'              => 'string',
        'last_name'               => 'string',
        'user_id'                 => 'integer',
        'merchant_id'             => 'integer',
        'active_merchant_manager' => 'boolean'
    ];
    
    /**
     * Update rules for merchant manager
     * @var array
     */
    public $update_rules = [
        'first_name'              => 'string',
        'last_name'               => 'string',
        'user_id'                 => 'integer',
        'merchant_id'             => 'integer',
        'active_merchant_manager' => 'boolean'
    ];

    /**
     * Relationship method between MerchantManager Model and User Model (one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }
    
    /**
     * Relationship method between MerchantManager Model and Merchant Model (many to one)
     * @return object
     */
    public function merchant() {
        return $this->belongsTo( 'Merchant' );
    }

}
