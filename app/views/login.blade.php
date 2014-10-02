@extends("layout")
@section("content")
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center">
			<h2>Login</h2>
			{{ Form::open(array('route' => 'login')) }}
				<div class="form-group">
					<input type="email" name="email" class="form-control text-center" placeholder="Email" required autofocus>
					<input type="password" name="password" class="form-control text-center" placeholder="Password" required>
				</div>
				<div class="form-group">
					{{ link_to_route('register', "Register") }}
				</div>
				<button type="submit" class="btn btn-primary btn-block">Login</button>
			{{ Form::close() }}
		</div>
	</div>
@stop