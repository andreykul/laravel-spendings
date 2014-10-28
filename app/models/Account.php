<?php

class Account extends Eloquent {
	public $timestamps = false;
	protected $fillable = array('name');

	public function owns()
	{
		return $this->hasMany('Account');
	}

	public function users()
	{
		return $this->belongsToMany('User');
	}

	public function transactions()
	{
		return $this->hasMany('Transaction');
	}
}

