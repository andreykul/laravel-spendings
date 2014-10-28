<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	public $timestamps = false;
	protected $table = 'users';
	protected $hidden = array('password', 'remember_token');

	public static $reg_rules = array(
		'email' => 'required|email|unique:users',
		'password' => 'required|confirmed|min:6'
	);

	public static $log_rules = array(
		'email' => 'required|email',
		'password' => 'required'
	);	

	public function owns()
	{
		return $this->hasMany('Account');
	}

	public function accounts()
	{
		return $this->belongsToMany('Account');
	}
}
