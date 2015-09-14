<?php

class Town extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'lu_nz_towns';
	protected $guarded  = array('id');
//	protected $fillable = array('username', 'name', 'email');

	public static $rules = array(
		'nz_town'		=> 'required|unique:lu_nz_towns',
	);
}
