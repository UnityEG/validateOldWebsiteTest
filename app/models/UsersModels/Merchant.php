<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Merchant extends Eloquent {

    use SoftDeletingTrait;
    
    /**
     * Dates columns in database
     * @var array
     */
    protected $dates     = ['deleted_at' ];
    
    /**
     * Table name
     * @var string
     */
    protected $table     = 'merchants';
    
    /**
     * Fillable columns in the table
     * @var array
     */
    protected $fillable  = [
        'first_name',
        'last_name',
        'user_id',
        'franchisor_id',
        'business_name',
        'trading_name',
        'industry_id',
        'region_id',
        'suburb_id',
        'postal_code_id',
        'address1',
        'address2',
        'phone',
        'website',
        'business_email',
        'contact_name',
        'payment_status',
        'active_merchant',
        'featured',
        'display',
        'facebook_page_id'
    ];
    
    /**
     * Create rules for Merchant table
     * @var array
     */
    public $create_rules = array(
        'user_id'         => 'integer',
        'franchisor_id'   => 'integer',
        'first_name'      => 'string',
        'last_name'       => 'string',
        'business_name'   => 'string',
        'trading_name'    => 'string',
        'industry_id'     => 'integer',
        'region_id'       => 'integer',
        'suburb_id'       => 'integer',
        'postal_code_id'  => 'integer',
        'address1'        => 'string',
        'address2'        => 'string',
        'phone'           => 'string',
        'website'         => 'url',
        'business_email'  => 'email',
        'contact_name'    => 'string',
        'payment_status'  => 'string',
        'active_merchant' => 'boolean',
        'featured'        => 'boolean',
        'display'         => 'boolean',
        'facebook_page_id' => 'string'
    );
    
    /**
     * Update rules for Merchant table
     * @var array
     */
    public $update_rules = array(
        'user_id'         => 'integer',
        'franchisor_id'   => 'integer',
        'first_name'      => 'string',
        'last_name'       => 'string',
        'business_name'   => 'string',
        'trading_name'    => 'string',
        'industry_id'     => 'integer',
        'region_id'       => 'integer',
        'suburb_id'       => 'integer',
        'postal_code_id'  => 'integer',
        'address1'        => 'string',
        'address2'        => 'string',
        'phone'           => 'string',
        'website'         => 'url',
        'business_email'  => 'email',
        'contact_name'    => 'string',
        'payment_status'  => 'string',
        'active'          => 'boolean',
        'featured'        => 'boolean',
        'display'         => 'boolean',
        'facebook_page_id' => 'string'
    );
    
    /**
     * Relationship method between User Model and Merchant Model (one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }
    
    /**
     * Relationship method between Merchant Model and MerchantManager Model (one to many)
     * @return object
     */
    public function merchantManagers() {
        return $this->hasMany( 'MerchantManager' );
    }
    
    /**
     * Relationship method between Merchant Model and Franchisor Model (many to one)
     * @return object
     */
    public function franchisor() {
        return $this->belongsTo( 'Franchisor' );
    }
    
    /**
     * Relationship method between Merchant Model and Supplier Model (many to many)
     * @return object
     */
    public function supplier() {
        return $this->belongsToMany( 'Supplier', 'merchants_suppliers_link', 'merchant_id', 'supplier_id' );
    }

}
