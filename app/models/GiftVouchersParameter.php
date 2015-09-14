<?php

class GiftVouchersParameter extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table   = 'gift_vouchers_parameters';
    protected $guarded = array( 'id' );
//	protected $fillable = array('username', 'name', 'email');

    public static $rules = array(
        'MerchantID' => 'required|exists:merchants,id',
        'image_id'   => 'integer|exists:user_pics,id',
        'Title'      => 'required',
        'short_description'=>'string',
        'long_description' => 'string',
        'MinVal'     => 'numeric|required|min:20',
        'MaxVal'     => 'greater_than:MinVal|required'
    );
    
    /**
     * 
     * @return type
     */
    public function merchant() {
        return $this->belongsTo( 'Merchant', 'MerchantID' );
    }
    
    public function userPic(  ) {
        return $this->belongsTo('UserPic', 'image_id', 'id');
    }

}
