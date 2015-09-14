<?php

class GiftVoucher extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'giftvoucher';
	protected $guarded  = array('id');
//	protected $fillable = array('username', 'name', 'email');


	public static $rules = array(
		'qr_code'			=> 'required|numeric|unique:giftvoucher',
		'voucher_value'		=> 'required|numeric',
		'delivery_date'		=> 'date_format:d/m/Y',
		'recipient_email'	=> 'required|email',
	);

	public function customer()
	{
		return $this->belongsTo('Customer');
	}

	public function parameter()
	{
		return $this->belongsTo('GiftVouchersParameter', 'gift_vouchers_parameters_id');
	}

}