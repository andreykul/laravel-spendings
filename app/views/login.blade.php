@extends("layout")
@section("content")
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center">
			<h2>Login</h2>
			{{ Form::open(array('route' => 'login')) }}
				<div class="form-group">
					<input type="email" name="user[email]" class="form-control text-center" placeholder="Email" required autofocus>
					<input type="password" name="user[password]" class="form-control text-center" placeholder="Password" required>
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
				</div>
				<div class="form-group">
					{{ link_to_route('register', "Register", null, array('class' => 'btn btn-block btn-success')) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop