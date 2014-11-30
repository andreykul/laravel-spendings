<?php

class TransactionsController extends BaseController {

	public function index($account_id)
	{
		if (! $account = $this->checkAccount($account_id) )
			return Redirect::route('home')
				->withErrors(array("You don't own this account."));

		$this->data['account'] = $account;

		$month = Input::get('month') ?: date('F');
		$year = Input::get('year') ?: date('Y');

		// Find all years available based on transactions
		$this->data['years'] = $this->findAvailableYears($account);

		$start_year = $year == "All" ? $this->data['years'][0] : $year;
		$end_year = $year == "All" ? end($this->data['years']) : $year;		

		// Find all months available, based on year
		$this->data['months'] = $this->findAvailableMonths($account, $year);

		$start_month = $month == "All" ? $this->data['months'][0] : $month;
		$end_month = $month == "All" ? end($this->data['months']) : $month;
		
		$start_date = date('Y-m-01', strtotime($start_month." ".$start_year));
		$end_date = date('Y-m-t', strtotime($end_month." ".$end_year));

		// Filter by selected tags, by default all are selected
		$this->data['tags'] = array('Income','Bills','Groceries','Going out','Household Items','Miscellaneous','Cloths/Makeup','Liza','Loans');
		$this->data['selected_tags'] = Input::get('tags') ?: $this->data['tags'];

		// Find all matching transactions
		$this->data['transactions'] = $account->transactions()
			->whereBetween("date",array($start_date,$end_date))
			->whereIn('tag',$this->data['selected_tags'])
			->orderBy('date','desc')
			->orderBy('id','desc')
			->get();

		// Prepare few last things for the views
		$this->data['withdraws'] = 0;
		$this->data['deposits'] = 0;
		foreach ($this->data['transactions'] as $transaction)
			$this->data[ $transaction->withdraw?'withdraws':'deposits'] += $transaction->amount;
		
		$this->data['years'][] = "All";
		$this->data['years'] = array_reverse($this->data['years']);
		$this->data['months'][] = "All";
		$this->data['months'] = array_reverse($this->data['months']);
		$this->data['selected_month'] = $month;
		$this->data['selected_year'] = $year;

		return View::make("transactions.index")->with($this->data);
	}

	public function store($account_id)
	{
		if (! $account = $this->checkAccount($account_id) )
			return Redirect::route('home')
				->withErrors(array("You don't own this account."));

		$transaction = new Transaction(Input::get('transaction'));
		$transaction->account_id = $account_id;

		$prev_transaction = $account->transactions()
			->where('date','<=',$transaction->date)
			->orderBy('date','desc')
			->orderBy('id','desc')
			->first();

		if (! $prev_transaction)
			$prev_transaction = new Transaction;

		$transaction->balance = $prev_transaction->balance;
		if ($transaction->withdraw)
			$transaction->balance -= $transaction->amount;
		else $transaction->balance += $transaction->amount;

		$transaction->save();
		$account->save();

		$this->updateBalances($account);

		return Redirect::back()
			->withStatus('Transcation has been added.');
	}

	public function destroy($account_id, $id)
	{
		if (! $account = $this->checkAccount($account_id) )
			return Redirect::route('home')
				->withErrors(array("You don't own this account."));

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

			$this->updateBalances($account);
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
					"error" => true,
					"text" => "You don't own this account."
				)
			);

		$transaction = Transaction::find($id);

		if ($transaction->account_id == $account_id)
		{
			return Response::json(
				array(
					"description" => $transaction->description,
					"notes" => $transaction->notes
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

	private function checkAccount($account_id)
	{
		$account = Auth::user()->accounts()->find($account_id);
		return $account;
	}

	private function findAvailableYears($account)
	{
		$years = array();
		$first_transaction = $account->transactions()
			->orderBy('date')
			->first();

		$current_year = idate('Y');
		if (! $first_transaction)
			$year = idate('Y');
		else $year = idate('Y',strtotime($first_transaction->date));
	
		while ($year <= $current_year)
			$years[] = $year++;

		return $years;
	}

	private function findAvailableMonths($account, $year)
	{
		$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		if ($year == "All" or intval($year) != idate('Y'))
			return $months;
		return array_slice($months, 0, idate('m'));
	}

	private function updateBalances($account)
	{
		$transactions = $account->transactions()
			->orderBy('date')
			->orderBy('id')
			->get();

		$prev = 0;
		foreach ($transactions as $transaction) {
			if($transaction->withdraw)
				$transaction->balance = $prev - $transaction->amount;
			else $transaction->balance = $prev + $transaction->amount;
			$prev = $transaction->balance;
			$transaction->save();
		}
		$account->balance = $prev;
		$account->save();
	}

}