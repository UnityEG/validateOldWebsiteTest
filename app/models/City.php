<?php

class City extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'lu_nz_cities';
	protected $guarded  = array('id');
//	protected $fillable = array('username', 'name', 'email');

	public static $rules = array(
		'nz-city'		=> 'required|unique:lu_industries',
	);
}
