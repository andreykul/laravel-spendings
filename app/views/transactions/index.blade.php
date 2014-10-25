@extends("layout")
@section("content")
	<div class="page-header">
		<h1>
			Spendings Tracker
			<a href="logout" class="btn btn-warning col-xs-2 pull-right ">Logout</a>
		</h1>
	</div>

	<a href="{{ route('accounts.index') }}">
		<i class="glyphicon glyphicon-chevron-left"></i> Back to Accounts
	</a>
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Date</th>
				<th class="text-center">Tag</th>
				<th class="text-center">Description</th>
				<th class="text-center">Withdraws</th>
				<th class="text-center">Deposits</th>
				<th class="text-center col-xs-2">Added by</th>
				<th class="text-center">Options</th>
			</tr>
		</thead>
		<tbody>
			<tr class="text-center">
				{{ Form::open(array('route' => array('accounts.transactions.store',$account->id) )) }}
					<td>
						<!-- No Transaction number yet -->
					</td>
					<td>
						<input type="date" name="transaction[date]" class="form-control text-center">
					</td>
					<td>
						<select name="transaction[tag]" class="form-control text-center">
							<option value="Income">Income</option>
							<option value="Bills">Bills</option>
							<option value="Groceries">Groceries</option>
							<option value="Going out">Going out</option>
							<option value="Household">Household Items</option>
							<option value="Liza">Liza</option>
							<option value="Loans">Loans</option>
						</select>
					</td>
					<td>
						<input name="transaction[description]" type="text" placeholder="e.g. Sobeys" class="form-control text-center">
					</td>
					<td>
						<input type="number" step="0.01" name="transaction[withdraw]" class="form-control text-center" placeholder="X.XX">
					</td>
					<td>
						<input type="number" step="0.01" name="transaction[deposit]" class="form-control text-center" placeholder="X.XX">
					</td>
					<td>
						<input type="hidden" name="transaction[added_by]" value="{{ Auth::user()->name }}">
						{{ Auth::user()->name }}
					</td>
					<td>
						<button type="submit" class="btn btn-block btn-success">Add</button>
					</td>
				{{ Form::close() }}
			</tr>
			@foreach ($transactions as $index => $transaction)
				<tr class="text-center">
					<td>{{ count($transactions) - $index }}</td>
					<td>{{ $transaction->date }}</td>
					<td>{{ $transaction->tag }}</td>
					<td>{{ $transaction->description }}</td>
					@if ($transaction->withdraw)
						<td>{{ $transaction->amount }}</td>
						<td></td>
					@else
						<td></td>
						<td>{{ $transaction->amount }}</td>
					@endif
					<td>{{ $transaction->added_by }}</td>
					<td>
						{{ Form::open(array('route' => array('accounts.transactions.destroy',$account->id,$transaction->id), 'method' => 'delete')) }}
							<input type="hidden" name="id" value="{{ $transaction->id }}">
							<button type="submit" class="btn btn-danger btn-block">Remove</button>
						{{ Form::close() }}
					</td>
				</tr>
			@endforeach
			<tr class="text-center">
				<td>Total</td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					{{ $withdraws }}
				</td>
				<td>
					{{ $deposits }}
				</td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
@stop