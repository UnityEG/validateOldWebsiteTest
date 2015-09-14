<?php

class UseTerm extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table    = 'use_terms';
	protected $guarded  = array('id');
//	protected $fillable = array('username', 'name', 'email');

	public static $rules = array(
		'name'		=> 'required',
	);
}
