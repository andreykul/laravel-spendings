@extends("layout")
@section("content")
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center">
			<h2>Register</h2>
			{{ Form::open(array('route' => 'register')) }}
				<div class="form-group">
					<input type="email" name="user[email]" class="form-control text-center" placeholder="Email" required autofocus>
					<input type="text" name="user[name]" class="form-control text-center" placeholder="Name" required>
					<input type="password" name="user[password]" class="form-control text-center" placeholder="Password" required>
					<input type="password" name="user[password_confirmation]" class="form-control text-center" placeholder="Password Conformation" required>
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-lg btn-primary btn-block">Register</button>
				</div>
				<div class="form-group">
					{{ link_to_route('login', "Back", null, array('class' => 'btn btn-block btn-warning')) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop