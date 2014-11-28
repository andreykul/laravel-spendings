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
		else 
		{
			$date = new DateTime($first_transaction->date);
			if($first_transaction->balance == 0)
				$this->updateMissingBalance($account);
		}
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


		// Filter by selected tags, by default all are selected
		$this->data['tags'] = array('Income','Bills','Groceries','Going out','Household Items','Miscellaneous','Cloths/Makeup','Liza','Loans');
		$this->data['selected_tags'] = Input::has('tags') ? Input::get('tags') : $this->data['tags'];


		$this->data['transactions'] = $account->transactions()
			->whereBetween("date",array($start_date,$end_date))
			->whereIn('tag',$this->data['selected_tags'])
			->orderBy('date','desc')
			->orderBy('id','desc')
			->get();
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
		$transaction->balance = $account->balance;
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

	public function getNotes($account_id, $id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Response::json(
				array(
					"header" => "Error",
					"notes" => "You don't own this account."
				)
			);

		$transaction = Transaction::find($id);

		if ($transaction->account_id == $account_id)
		{
			return Response::json(
				array(
					"header" => $transaction->description,
					"notes" => $transaction->notes
				)
			);
		}
		else return Response::json(
			array(
				"header" => "Error",
				"notes" => "You are not allowed to modify this transaction."
			)
		);
	}

	public function postNotes($account_id, $id)
	{
		$account = Auth::user()->accounts()->find($account_id);

		if(! $account )
			return Response::json(
				array(
					"error" => true,
					"text" => "You don't own this account."
				)
			);

		$transaction = Transaction::find($id);

		if(! $transaction)
			return Response::json(
				array(
					"error" => true,
					"text" => "No such transaction."
				)
			);

		if ($transaction->account_id == $account_id)
		{
			$transaction->notes = Input::get('notes');
			$transaction->save();
			return Response::json(
				array(
					"error" => false
				)
			);
		}
		else return Response::json(
			array(
				"error" => true,
				"text" => "You are not allowed to modify this transaction."
			)
		);
	}	

	private function updateMissingBalance($account)
	{
		$transactions = $account->transactions()->orderBy('date')->get();

		$prev = 0;
		foreach ($transactions as $transaction) {
			if($transaction->withdraw)
				$transaction->balance = $prev - $transaction->amount;
			else $transaction->balance = $prev + $transaction->amount;
			$prev = $transaction->balance;
			$transaction->save();
		}
	}

}