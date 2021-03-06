<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{ HTML::style('css/bootstrap.css'); }}
		<title>Todo List</title>
		
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	
	<body>
		<div class="navbar navbar-inverse">
			<div class="container">
						<ul class="nav navbar-nav">
							<li>{{ HTML::link('users/register', 'Register') }}</li>   
							<li>{{ HTML::link('users/login', 'Login') }}</li>   
						</ul>  
				</div>
			</div>
		</div>
		
		<div class="container">
			@if(Session::has('message'))
			<p class="alert alert-info">{{ Session::get('message') }}</p>
			@endif
			
			{{ $content }}
		</div>
	</body>
</html>