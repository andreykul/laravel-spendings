<?php

class AuthenticationController extends \BaseController {

	public function getLogin()
	{
		return View::make('login');
	}

	public function postLogin()
	{
		
	}
}
