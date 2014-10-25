@if ( Session::has( 'status' ) )
	<div class="row">
		<div class="col-xs-12">
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				{{ Session::get('status') }}
			</div>
		</div>
	</div>
@endif


<div class="row">
	@foreach ( $errors->all() as $error)
		<div class="col-xs-12">
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				{{ $error }}
			</div>
		</div>
	@endforeach
</div>
