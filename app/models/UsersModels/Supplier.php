<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Supplier extends Eloquent {

    use SoftDeletingTrait;
    
    /**
     * Dates columns in the table
     * @var array
     */
    protected $dates    = ['deleted_at' ];
    
    /**
     * Table name
     * @var string
     */
    protected $table    = 'suppliers';
    
    /**
     * Fillable columns in the database
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'user_id', 'active_supplier' ];
    
    /**
     * Rules for creating new supplier
     * @var array
     */
    public $create_rules = [
        'first_name'      => 'string',
        'last_name'       => 'string',
        'user_id'         => 'integer',
        'active_supplier' => 'boolean'
    ];
    
    /**
     * Rules to update existing supplier
     * @var array
     */
    public $update_rules = [
        'first_name'      => 'string',
        'last_name'       => 'string',
        'user_id'         => 'integer',
        'active_supplier' => 'boolean'
    ];
    
    /**
     * Relationship method between Supplier Model and User Model ( one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }
    
    /**
     * Relationship method between Supplier Model and Merchant Model ( many to many)
     * @return object
     */
    public function merchant( ) {
        return $this->belongsToMany('Merchant', 'merchants_suppliers_link', 'supplier_id', 'merchant_id');
    }

}
