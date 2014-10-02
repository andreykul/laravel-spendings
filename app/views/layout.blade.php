<!DOCTYPE html>
<html>
	<head>
		<title>Spendings Tracking Application</title>
		{{ HTML::script('js/jquery-1.10.1.min.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		{{ HTML::script('js/site.min.js') }}
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/site.min.css') }}
		{{ HTML::style('css/custom.css') }}
	</head>
	<body>
		<div class="container">	
			@include('navigation')
			@yield('content')
		</div>
	</body>
</html>