<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Owner extends Eloquent {
use SoftDeletingTrait;
    
    /**
     * Date columns in the database
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Table name
     * @var string
     */
    protected $table    = 'owners';
    
    /**
     * Fillable columns in the database
     * @var array
     */
    protected $fillable = ['user_id', 'first_name',  'last_name'];
    
    /**
     * Update rules for owner
     * @var array
     */
    public $update_rules = [
        'user_id'=>'integer',
        'first_name'=>'string',
        'last_name'=>'string',
        
    ];
    
    /**
     * Relation method between Owner Model and User Model (one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }

}
