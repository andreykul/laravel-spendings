<!DOCTYPE html>
<html>
	<head>
		<title>Spendings Tracking Application</title>
		{{ HTML::script('js/jquery-1.10.1.min.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		{{ HTML::style('css/bootstrap.min.css') }}
	</head>

	<body>
		<div class="container">
			<div class="page-header">
				<h1>Spendings Tracker</h1>
			</div>

			<h3>New Spending</h3>
			
			{{ Form::open(array('route' => 'spendings.store','class' => 'form-horizontal')) }}
					<div class="form-group">
						<label for="date" class="col-xs-2 control-label">Date: </label>
						<div class="col-xs-3">
							<input type="date" id="date" name="spending[date]" class="form-control">
						</div>	
					</div>
					
					<div class="form-group">
						<label for="tag" class="col-xs-2 control-label">Tag:</label>
						<div class="col-xs-3">
							<select name="spending[tag]" id="tag" class="form-control">
								<option selected disabled>Please select a tag</option>
								<option value="bills">Bills</option>
								<option value="groceries">Groceries</option>
								<option value="goingout">Going out</option>
								<option value="household">Household Items</option>
								<option value="liza">Liza</option>
								<option value="loans">Loans</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="description" class="col-xs-2 control-label">Description: </label>
						<div class="col-xs-3">
							<input id="description" name="spending[description]" type="text" placeholder="Electricity / Sobeys ..." class="form-control">
						</div>	
					</div>
					
					<div class="form-group">
						<label for="amount" class="col-xs-2 control-label">Amount</label>
						<div class="col-xs-3">
							<input type="number" step="0.01" name="spending[amount]" id="amount" class="form-control" placeholder="X.XX">
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-offset-2 col-xs-3">
							<button type="submit" class="form-control btn btn-primary btn-block">Add</button>
						</div>	
					</div>
					
			{{ Form::close() }}

			<h3>Spendings</h3>

			<table class="table">
				<thead>
					<tr>
						<th>Date</th>
						<th>Tag</th>
						<th>Description</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($spendings as $spending)
						<tr>
							<td>{{ $spending->date }}</td>
							<td>{{ $spending->tag }}</td>
							<td>{{ $spending->description }}</td>
							<td>{{ $spending->amount }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</body>
</html>