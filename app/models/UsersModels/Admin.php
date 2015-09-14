<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Admin extends Eloquent{
    use SoftDeletingTrait;
    
    /**
     * Date columns in admins table
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Table name
     * @var string
     */
    protected $table = 'admins';
    
    /**
     * Fillable columns in admins table
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'user_id', 'active_admin'];
    
    /**
     * Create rules for admins table
     * @var array
     */
    public $create_rules = [
        'user_id'=>'integer',
        'active_admin'=>'boolean',
        'first_name' => 'string',
        'last_name'=>'string'
    ];
    
    /**
     * Update rules for admins table
     * @var array
     */
    public $update_rules = [
        'user_id'=>'integer',
        'active_admin'=>'boolean',
        'first_name' => 'string',
        'last_name'=>'string'
    ];

    /**
     * Relationship method between Admin Model and User Model (one to one)
     * @return object
     */
    public function user( ) {
        return $this->belongsTo( 'User');
    }
}
