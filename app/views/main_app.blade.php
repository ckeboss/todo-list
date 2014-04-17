<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		{{ HTML::style('css/bootstrap.css'); }}
		{{ HTML::style('css/styles.css'); }}
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript">
			app = {url: '{{ URL::to('/') }}'};
			@if(Auth::check())
			app.user_id = {{ Auth::user()->id }}
			@endif;
		</script>
		{{ HTML::script('js/scripts.js'); }}
		{{ HTML::script('js/bootstrap/tab.js'); }}
		{{ HTML::script('js/bootstrap/tooltip.js'); }}
		{{ HTML::script('js/bootstrap/popover.js'); }}
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
			
			@if(!Auth::check())
			<ul class="nav nav-tabs" id="login-registation-tabs">
				<li class="active"><a href="#login" data-toggle="tab">Login</a></li>
				<li><a href="#register" data-toggle="tab">Register</a></li>
			</ul>
			
			<div class="tab-content login-registation">
				<div class="tab-pane active" id="login">{{ View::make('users.login'); }}</div>
				<div class="tab-pane" id="register">{{ View::make('users.register'); }}</div>
			</div>
			@endif
			
			<div id="todo-main-app" @if(!Auth::check()) style="display: none;" @endif>
				<h3>Welcome <span id="username-welcome">@if(Auth::check()){{ Auth::user()->name }}@endif</span>! <small><a href="{{ Url::to('/users/logout') }}">Logout</a></small></h3>
				
				<ul class="nav nav-tabs" id="users-tasks">
					<li class="active"><a href="#tasks" data-toggle="tab">Your Tasks</a></li>
					<li><a href="#others-tasks" data-toggle="tab">Others Tasks</a></li>
				</ul>
				
				<div class="tab-content">
					<section id="tasks" class="tab-pane active">
						<table class="table table-hover">
							<thead>
								<tr>
									<th style="width: 20px;">Finished?</th>
									<th>Task</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						
						<ul class="pagination">
						</ul>
					</section>
					
					<section id="others-tasks" class="tab-pane">
						<table class="table table-hover">
							<thead>
								<tr>
									<th style="width: 20px;">Finished?</th>
									<th>Task</th>
									<th>User</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						
						<ul class="pagination">
						</ul>
					</section>
				</div>
				
				<section id="new-task">
					@if(Auth::check())
						{{ View::make('tasks/new')->with('users', $users) }}
					@endif
				</section>
			</div>
			
		</div>
	</body>
</html>