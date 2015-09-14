<?php

class Region extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'lu_regions';
	protected $guarded  = array('id');
//	protected $fillable = array('username', 'name', 'email');

	public static $rules = array(
		'region'		=> 'required|unique:lu_regions',
	);
}
