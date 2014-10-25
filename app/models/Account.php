<?php

class Account extends Eloquent {
	protected $fillable = array('name');

	public function users()
	{
		return $this->belongsToMany('User');
	}

	public function transactions()
	{
		return $this->hasMany('Transaction');
	}
}

