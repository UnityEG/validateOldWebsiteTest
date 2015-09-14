<?php

class Postcode extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'lu_regions_postcodes';
	protected $guarded  = array('id');
//	protected $fillable = array('username', 'name', 'email');

	public static $rules = array(
		'post_code'		=> 'required|unique:lu_industries',
	);
}
