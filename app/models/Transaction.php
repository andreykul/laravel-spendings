<?php

class Transaction extends Eloquent {
	public $timestamps = false;

	public function account()
	{
		return $this->belongsTo('Account');
	}
}

