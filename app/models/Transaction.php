<?php

class Transaction extends Eloquent {
	public $timestamps = false;

	protected $fillable = array('date', 'tag', 'description','amount','withdraw','notes');

	public function account()
	{
		return $this->belongsTo('Account');
	}
}

