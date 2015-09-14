<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Customer extends Eloquent {

    use SoftDeletingTrait;

    /**
     * Date cloumns in customers table
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * table name
     * @var string
     */
    protected $table = 'customers';

    /**
     * Fillable columns in customers table
     * @var array
     */
    protected $fillable = [
        'user_id',
        'facebook_user_id',
        'active_customer',
        'title',
        'first_name',
        'last_name',
        'dob',
        'region_id',
        'suburb_id',
        'postal_code_id',
        'address1',
        'address2',
        'phone',
        'mobile',
        'notify_deal',
        'gender'
    ];

    /**
     * Create rules for customer table
     * @var array
     */
    public $create_rules = [
        'user_id' => 'integer',
        'facebook_user_id' => 'integer',
        'active_customer' => 'boolean',
        'title' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'dob' => 'date_format:d/m/Y',
        'region_id' => 'integer',
        'suburb_id' => 'integer',
        'postal_code_id' => 'integer',
        'address1' => 'string',
        'address2' => 'string',
        'phone' => 'string',
        'mobile' => 'string',
        'notify_deal' => 'boolean',
        'gender' => 'string|in:male,female'
    ];

    /**
     * Update rules for customer table
     * @var array
     */
    public $update_rules = [
        'user_id' => 'integer',
        'facebook_user_id' => 'integer',
        'active_customer' => 'boolean',
        'title' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'dob' => 'date_format:d/m/Y',
        'region_id' => 'integer',
        'suburb_id' => 'integer',
        'postal_code_id' => 'integer',
        'address1' => 'string',
        'address2' => 'string',
        'phone' => 'string',
        'mobile' => 'string',
        'notify_deal' => 'boolean',
        'gender' => 'string|in:male,female'
    ];

    /**
     * Relationship method between Customer Model and User Model (one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo('User');
    }

//    Helper methods

    /**
     * return full name for customer
     * @param object $customer_object
     * @return string
     */
    public function getName() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * return full name with title for customer
     * @param object $customer_object
     * @return string
     */
    public function getNameWithTitle() {
        return $this->title . ' ' . $this->getName();
    }

}
