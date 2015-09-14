<?php

/**
 * user pictures table
 *
 * @author mohamed
 */
class UserPic extends Eloquent {

    /**
     * table name
     * @var string
     */
    protected $table = 'user_pics';
    
    /**
     * stop using default timestamps
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable columns in the table
     * @var array
     */
    protected $fillable = ['user_id', 'pic', 'extension', 'type', 'active_pic' ];

    /**
     * create rules
     * @var array
     */
    public $create_rules = array(
        'user_id'    => 'integer|exists:users,id',
        'pic'        => 'string|unique:user_pics',
        'image'      => 'image|max:2048',
        'extension'  => 'string',
        'type'       => 'string',
        'active_pic' => 'boolean'
    );
    public $update_rules = array(
        'user_id'    => 'integer',
        'pic'        => 'string|unique:user_pics',
        'image'      => 'image|max:2048',
        'extension'  => 'string',
        'type'       => 'string',
        'active_pic' => 'boolean'
    );

    /**
     * Relationship method between UserPic model and User model (many to one)
     * @return object
     */
    public function user() {
        return $this->belongsTo( 'User' );
    }
    
    /**
     * Relationship method between UserPic model and GiftVouchersParameter model(one to many)
     * @return object
     */
    public function giftVouchersParameter( ) {
        return $this->hasMany( 'GiftVouchersParameter', 'image_id', 'id');
    }

}
