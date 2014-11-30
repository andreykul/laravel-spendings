<div id="new-transcation" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">New Transaction</h4>
			</div>
			{{ Form::open(array('route' => array('accounts.transactions.store',$account->id) )) }}
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="date" class="control-label">Date:</label>
							<input id="date" type="date" name="transaction[date]" class="form-control">	
						</div>
						
						<div class="form-group">
							<label for="tag" class="control-label">Tag:</label>
							<select name="transaction[tag]" id="tag" class="form-control">
								@foreach ($tags as $tag)
									<option value="{{ $tag }}">{{ $tag }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label for="amount" class="control-label">Amount:</label>
							<input id="amount" type="number" step="0.01" name="transaction[amount]" class="form-control">
						</div>

						<div class="form-group">
							<label class="control-label">Type:</label>
							<div class="radio">
								<label>
							    	<input type="radio" name="transaction[withdraw]" value="1" checked>
									Withdraw
								</label>
							</div>
							<div class="radio">
								<label>
							    	<input type="radio" name="transaction[withdraw]" value="0">
									Deposit
								</label>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="description" class="control-label">Description:</label>
							<input id="description" name="transaction[description]" type="text" placeholder="e.g. Sobeys" class="form-control">
						</div>				

						<div class="form-group">
							<label for="notes" class="control-label">Notes:</label>
							<textarea class="form-control" name="transaction[notes]" id="notes" cols="30" rows="10"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
			{{ Form::close() }}
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
