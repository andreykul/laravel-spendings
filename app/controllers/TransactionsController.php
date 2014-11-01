<?php

class TransactionsController extends BaseController {

	public function index($account_id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Redirect::route('home')
				->withErrors(array("You don't own this account."));

		$this->data['account'] = $account;

		if (Input::has('month'))
			$month = Input::get('month');
		else $month = date('F');

		if (Input::has('year'))
			$year = Input::get('year');
		else $year = date('Y');

		// Find all years for which this account has transactions
		$this->data['years'] = array();
		$first_transaction = $account->transactions()->orderBy('date')->first();
		if (! $first_transaction)
			$date = new DateTime;
		else $date = new DateTime($first_transaction->date);
		$today = date('Y-m-t');
		while ($date->format("Y-m-d") <= $today) {
			array_unshift($this->data['years'], $date->format("Y"));
			$date->modify('next year');
		}

		if ($year == "All")
		{
			$start_year = end($this->data['years']);
			$end_year = $this->data['years'][0];
		}
		else
		{
			$start_year = $year;
			$end_year = $year;
		}

		// Find all months for which this account has transactions
		$this->data['months'] = array();
		$first_transaction = $account->transactions()->where('date','>',date('Y-m-d',strtotime("January 1st ".$start_year)))->orderBy('date')->first();
		if (! $first_transaction)
			$date = new DateTime;
		else $date = new DateTime($first_transaction->date);
		$today = date('Y-m-t');
		while ($date->format("Y-m-d") <= $today) {
			array_unshift($this->data['months'], $date->format("F"));
			$date->modify('next month');
		}

		if ($month == "All")
		{
			$start_month = end($this->data['months']);
			$end_month = $this->data['months'][0];
		}
		else
		{
			$start_month = $month;
			$end_month = $month;
		}

		$start_date = date('Y-m-01', strtotime($start_month." ".$start_year));
		$end_date = date('Y-m-t', strtotime($end_month." ".$end_year));

		$this->data['transactions'] = $account->transactions()->whereBetween("date",array($start_date,$end_date))->orderBy('date','desc')->get();
		$this->data['withdraws'] = 0;
		$this->data['deposits'] = 0;
		foreach ($this->data['transactions'] as $transaction)
			$this->data[ $transaction->withdraw?'withdraws':'deposits'] += $transaction->amount;

		
		$this->data['selected_month'] = $month;
		$this->data['selected_year'] = $year;

		return View::make("transactions.index")->with($this->data);
	}

	public function store($account_id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Redirect::route('home')
				->withErrors(array("You don't own this account."));

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
			return Redirect::route('home')
				->withErrors(array("You don't own this account."));

		$transaction = Transaction::find($id);

		if ($transaction->account_id == $account_id){
			if($transaction->withdraw)
				$account->balance += $transaction->amount;
			else $account->balance -= $transaction->amount;
			$transaction->delete();
			$account->save();
		}
		else return Redirect::back()->withErrors(array('You are not allowed to remove this transaction.'));

		return Redirect::back()->withStatus('Transaction has been removed.');
	}

}