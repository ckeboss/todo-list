<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{ HTML::style('css/bootstrap.css'); }}
		{{ HTML::style('css/styles.css'); }}
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		{{ HTML::script('js/scripts.js'); }}
		{{ HTML::script('js/bootstrap/tab.js'); }}
		<title>Todo List</title>
		
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	
	<body>
		
		<div class="container">
			@if(Session::has('message'))
			<p class="alert alert-info">{{ Session::get('message') }}</p>
			@endif
			
			<h1 class="text-center">Todo List</h1>
			
			<ul class="nav nav-tabs" id="login-registation-tabs">
				<li class="active"><a href="#login" data-toggle="tab">Login</a></li>
				<li><a href="#register" data-toggle="tab">Register</a></li>
			</ul>
			
			<div class="tab-content login-registation">
				<div class="tab-pane active" id="login">{{ View::make('users.login'); }}</div>
				<div class="tab-pane" id="register">{{ View::make('users.register'); }}</div>
			</div>
		</div>
	</body>
</html>