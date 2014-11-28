@extends("layout")

@section("extended_header")
	{{ HTML::script('js/transactions.js') }}
	<base href="{{ route('accounts.transactions.index',$account->id) }}">
@stop

@section("content")
	<div class="page-header">
		<h1>
			Spendings Tracker
			<small>{{ $account->name }} : ${{ number_format($account->balance,2) }}</small>
			<a href="{{ route('logout') }}" class="btn btn-warning col-xs-2 pull-right ">Logout</a>
		</h1>
	</div>

	<a href="{{ route('accounts.index') }}">
		<i class="glyphicon glyphicon-chevron-left"></i> Back to Accounts
	</a>

	@include('transactions._notes')

	<hr>

	<div class="row">
		<div class="col-xs-2">
			{{ Form::open(array('route' => array('accounts.transactions.index',$account->id), 'method' => 'get')) }}
				<fieldset>
					<legend>Filter</legend>
					<div class="form-group">
						<label for="year">Year</label>
						<select name="year" id="year" class="form-control">
							<option value="All">All</option>
							@foreach($years as $year)
								<option value="{{ $year }}" @if ($selected_year == $year) selected @endif>{{ $year }}</option>
							@endforeach
						</select>	
					</div>
					
					<div class="form-group">
						<label for="month">Month</label>
						<select name="month" id="month" class="form-control">
							<option value="All">All</option>
							@foreach($months as $month)
								<option value="{{ $month }}" @if ($selected_month == $month) selected @endif>{{ $month }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label>Tags</label>
						<ul class="list-unstyled">
							@foreach($tags as $tag)
							<li class="checkbox">
								<label>
									<input name="tags[]" 
										value="{{ $tag }}" 
										type="checkbox"
										@if (in_array($tag,$selected_tags)) checked @endif>{{ $tag }}
								</label>
							</li>
							@endforeach
						</ul>
					</div>

					<br>
					<button type="submit" class="btn btn-primary btn-block">Update</button>	
					
				</fieldset>
			{{ Form::close() }}			
		</div>
		<div class="col-xs-10">
			<table class="table table-hover table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th></th>
						<th class="text-center">#</th>
						<th class="text-center">Date</th>
						<th class="text-center">Tag</th>
						<th class="text-center">Description</th>
						<th class="text-center">Withdraws</th>
						<th class="text-center">Deposits</th>
						<th class="text-center col-xs-1">Balance</th>
						<th class="text-center">Options</th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						{{ Form::open(array('route' => array('accounts.transactions.store',$account->id) )) }}
							<td></td>
							<td>
								<!-- No Transaction number yet -->
							</td>
							<td>
								<input id="date" type="date" name="transaction[date]" class="form-control text-center">
							</td>
							<td id="tag">
								<select name="transaction[tag]" class="form-control text-center">
									@foreach ($tags as $tag)
										<option value="{{ $tag }}">{{ $tag }}</option>
									@endforeach
								</select>
							</td>
							<td id="description">
								<input name="transaction[description]" type="text" placeholder="e.g. Sobeys" class="form-control text-center">
							</td>
							<td id="withdraw">
								<input type="number" step="0.01" name="transaction[withdraw]" class="form-control text-center" placeholder="X.XX">
							</td>
							<td id="deposit">
								<input type="number" step="0.01" name="transaction[deposit]" class="form-control text-center" placeholder="X.XX">
							</td>
							<td id="balance">
							</td>
							<td>
								<button type="submit" class="btn btn-xs btn-block btn-success">Add</button>
							</td>
						{{ Form::close() }}
					</tr>
					@foreach ($transactions as $index => $transaction)
						<tr class="text-center">
							<td>
								<input type="checkbox" class="amount-included" 
									data-type="{{ $transaction->withdraw ? 'withdraws' : 'deposits' }}"
									value="{{ $transaction->amount }}"
									checked>
							</td>
							<td>{{ count($transactions) - $index }}</td>
							<td>{{ $transaction->date }}</td>
							<td>{{ $transaction->tag }}</td>
							<td>
								@if ($transaction->description)
									<button class="notes btn btn-block btn-link" data-transaction-id="{{ $transaction->id }}" data-toggle="modal" data-target=".modal">
										{{ $transaction->description }}
									</button>
								@endif
							</td>
							@if ($transaction->withdraw)
								<td>{{ number_format($transaction->amount,2) }}</td>
								<td></td>
							@else
								<td></td>
								<td>{{ number_format($transaction->amount,2) }}</td>
							@endif
							<td>{{ number_format($transaction->balance,2) }}</td>
							<td>
								{{ Form::open(array('route' => array('accounts.transactions.destroy',$account->id,$transaction->id), 'method' => 'delete')) }}
									<input type="hidden" name="id" value="{{ $transaction->id }}">
									<button type="submit" class="btn btn-xs btn-danger btn-block">Remove</button>
								{{ Form::close() }}
							</td>
						</tr>
					@endforeach
					<tr class="text-center">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td id="withdraws">
							{{ number_format($withdraws,2) }}
						</td>
						<td id="deposits">
							{{ number_format($deposits,2) }}
						</td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
@stop