<?php

class UsersController extends \BaseController {

	public function getLogin()
	{
		return View::make('login');
	}

	public function postLogin()
	{
		$user_data = Input::get('user');

		$validator = Validator::make($user_data, User::$log_rules);

	    if ($validator->fails())
			return Redirect::back()->withErrors($validator->messages()->all());
		else {
			if (Auth::attempt($user_data))
				return Redirect::intended('/');
			else return Redirect::back()->withErrors(array("We could not find a record that matches the Username and Password provided."));
		}
	}

	public function getRegister()
	{
		return View::make('register');
	}

	public function postRegister()
	{
		$user_data = Input::get('user');

		$validator = Validator::make($user_data, User::$reg_rules);

		if ($validator->fails())
			return Redirect::back()->withErrors($validator->messages()->all());
		else {
			$user = new User;
			$user->nickname = $user_data['nickname'];
			$user->name = $user_data['name'];
			$user->password = Hash::make($user_data['password']);
			$user->save();

			Auth::login($user);
			return Redirect::route('home');
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('home');
	}
}
