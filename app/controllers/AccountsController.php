<?php

class AccountsController extends BaseController {

	public function index()
	{
		$this->data['accounts'] = Auth::user()->accounts()->get();

		return View::make('accounts.index')->with($this->data);
	}

	public function store()
	{
		$account_info = Input::get('account');
		$account = new Account;
		$account->name = $account_info['name'];
		$account->user_id = Auth::user()->id;
		$account->save();
		
		Auth::user()->accounts()->attach($account->id);

		return Redirect::back();
	}

	public function destroy($id)
	{
		$account = Auth::user()->accounts()->find($id);

		if (! $account )
			return Redirect::back()->withErrors(array('You are not allowed to delete this account.'));

		Auth::user()->accounts()->detach($account->id);
		if ( Auth::user()->owns()->find($id) )
			$account->delete();
		
		return Redirect::back()->withStatus("Accounts has been succesfully removed.");
	}

	public function share($id)
	{
		$account = Auth::user()->accounts()->find($id);

		if (! $account )
			return Redirect::back()->withErrors(array('You are not allowed to share this account.'));

		$user = User::where('email','=',Input::get('email'))->first();
		if(! $user)
			return Redirect::back()->withErrors(array('Sorry we could not find such email in our system.'));

		$user->accounts()->attach($account->id);
		return Redirect::back();

	}
}