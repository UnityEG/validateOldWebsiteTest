<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Developer extends Eloquent {
use SoftDeletingTrait;
    
    /**
     * Date columns in developer table
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Table name
     * @var string
     */
    protected $table    = 'developers';
    
    /**
     * Fillable columns in developers table
     * @var array
     */
    protected $fillable = ['name', 'user_id' ];
    
    /**
     * Relationship method between Developer Model and User Model (one to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }

}
