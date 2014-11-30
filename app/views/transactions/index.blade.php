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

	<!-- Modals -->
	@include('transactions._notes')
	@include('transactions._new')

	<hr>

	<div class="row">
		<div class="col-xs-2">
			{{ Form::open(array('route' => array('accounts.transactions.index',$account->id), 'method' => 'get')) }}
				<fieldset>
					<legend>Filter</legend>
					<div class="form-group">
						<label for="year">Year</label>
						<select name="year" id="year" class="form-control">
							@foreach($years as $year)
								<option value="{{ $year }}" @if ($selected_year == $year) selected @endif>{{ $year }}</option>
							@endforeach
						</select>	
					</div>
					
					<div class="form-group">
						<label for="month">Month</label>
						<select name="month" id="month" class="form-control">
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
			<fieldset>
				<legend>Summary</legend>
				<ul class="list-unstyled">
					<li>
						<label>Withdraws:</label> ${{ number_format($withdraws,2) }}
					</li>
					<li>
						<label>Deposits:</label> ${{ number_format($deposits,2) }}
					</li>
				</ul>
				
			</fieldset>
			

			<fieldset>
				<legend>Details</legend>

				<div class="form-group">
					<button class="btn btn-lg btn-success" data-toggle="modal" data-target="#new-transcation">Add Transaction</button>	
				</div>

				<table class="table table-hover table-condensed table-striped table-bordered">
					<thead>
						<tr>
							<th></th>
							<th class="text-center">#</th>
							<th class="text-center">Date</th>
							<th class="text-center">Tag</th>
							<th class="text-center">Description</th>
							<th class="text-center">Amount</th>
							<th class="text-center">Balance</th>
							<th class="text-center">Options</th>
						</tr>
					</thead>
					<tbody>
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
										<button class="notes btn btn-block btn-link" data-transaction-id="{{ $transaction->id }}" data-toggle="modal" data-target="#transaction-notes">
											{{ $transaction->description }}
										</button>
									@endif
								</td>
								<td>{{ number_format($transaction->amount,2) }}</td>
								<td class="{{ $transaction->withdraw ? 'danger' : 'success' }}">{{ number_format($transaction->balance,2) }}</td>
								<td>
									{{ Form::open(array('route' => array('accounts.transactions.destroy',$account->id,$transaction->id), 'method' => 'delete')) }}
										<input type="hidden" name="id" value="{{ $transaction->id }}">
										<button type="submit" class="btn btn-xs btn-danger btn-block">Remove</button>
									{{ Form::close() }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

			</fieldset>
		</div>
	</div>
@stop