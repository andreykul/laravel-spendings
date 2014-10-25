<?php

class TransactionsController extends BaseController {

	public function index($account_id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Redirect::route('home');

		$this->data['account'] = $account;
		$this->data['transactions'] = $account->transactions()->orderBy('date','desc')->get();
		$this->data['withdraws'] = 0;
		$this->data['deposits'] = 0;
		foreach ($this->data['transactions'] as $transaction)
			$this->data[ $transaction->withdraw?'withdraws':'deposits'] += $transaction->amount;

		return View::make("transactions.index")->with($this->data);
	}

	public function store($account_id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Redirect::route('home');

		$transaction_info = Input::get('transaction');

		$transaction = new Transaction;
		$transaction->added_by = $transaction_info['added_by'];
		$transaction->account_id = $account_id;
		$transaction->date = $transaction_info['date'];
		$transaction->tag = $transaction_info['tag'];
		$transaction->description = $transaction_info['description'];
		$transaction->date = $transaction_info['date'];
		if (empty($transaction_info['withdraw'])){
			$transaction->amount = $transaction_info['deposit'];
			$account->balance += $transaction->amount;
			$transaction->withdraw = false;
		}
		else {
			$transaction->amount = $transaction_info['withdraw'];
			$account->balance -= $transaction->amount;
			$transaction->withdraw = true;
		}
		$transaction->save();
		$account->save();

		return Redirect::back();
	}

	public function destroy($account_id, $id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Redirect::route('home');

		$transaction = Transaction::find($id);

		if ($transaction->account_id == $account_id){
			if($transaction->withdraw)
				$account->balance += $transaction->amount;
			else $account->balance -= $transaction->amount;
			$transaction->delete();
			$account->save();
		}
		else return Redirect::back()->withErrors(array('You are not allowed to remove this transaction.'));

		return Redirect::back();
	}

}