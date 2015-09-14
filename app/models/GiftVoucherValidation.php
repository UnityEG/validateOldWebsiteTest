<?php

class GiftVoucherValidation extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'giftvouchervalidation';
	protected $guarded  = array('id');
	//protected $fillable = array('username', 'name', 'email');

/*
	public static $rules = array(
		'value'		=> 'required|numeric',
	);
*/
	public function voucher()
	{
		return $this->belongsTo('GiftVoucher', 'giftvoucher_id');
	}

}