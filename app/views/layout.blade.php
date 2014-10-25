<!DOCTYPE html>
<html>
	<head>
		<title>Spendings Tracking Application</title>
		{{ HTML::script('js/jquery-1.10.1.min.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/custom.css') }}
		@yield('extended_header')
	</head>
	<body>
		<div class="container">
			@include('flash')
			@yield('content')
		</div>
	</body>
</html>