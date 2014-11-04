@extends("layout")
@section("content")
	<div class="page-header">
		<h1>
			Accounts
			<a href="{{ route('logout') }}" class="btn btn-warning col-xs-2 pull-right ">Logout</a>
		</h1>
	</div>

	{{ Form::open(array('route' => 'accounts.store','class' => 'form-inline')) }}
		<input type="text" name="account[name]" class="form-control" placeholder="Account Name" autofocus>
		<button type="submit" class="btn btn-success">Create</button>
	{{ Form::close() }}

	<br>

	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Balance</th>
				<th>Owners</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($accounts as $account)
				<tr>
					<td>
						{{ link_to_route('accounts.transactions.index', $account->name, $account->id) }}
					</td>
					<td>{{ $account->balance }}</td>
					<td>
						@foreach($account->users()->get() as $owner)
							{{ $owner->name }}, 
						@endforeach

						{{ Form::open(array('route' => array('accounts.share',$account->id), 'class' => 'form-inline')) }}
							<input type="email" name="email" placeholder="Email" class="form-control">
							<button type="submit" class="btn btn-primary">Share</button>
						{{ Form::close() }}
					</td>
					<td>
						{{ Form::open(array('route' => array('accounts.destroy',$account->id), 'method' => 'delete', 'class' => 'form-inline')) }}
							<button type="submit" class="btn btn-danger">Delete</button>
						{{ Form::close() }}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop