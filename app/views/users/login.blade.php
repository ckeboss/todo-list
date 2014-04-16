{{ Form::open(array('url'=>'users/login')) }}
	
	<div class="form-group">
	{{ Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email')) }}
	</div>
	<div class="form-group">
	{{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password')) }}
	</div>
	
	{{ Form::submit('Login', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}