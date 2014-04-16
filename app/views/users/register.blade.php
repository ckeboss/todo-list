{{ Form::open(array('url'=>'users/register', 'class'=>'form-signup', 'autocomplete' => 'off', 'id' => 'registration-form', 'role' => 'form')) }}

	{{ Form::textField('name', null, null, array('class'=>'form-control', 'placeholder'=>'Name')) }}
	{{ Form::textField('email', null, null, array('class'=>'form-control', 'placeholder'=>'Email')) }}
	{{ Form::passwordField('password', null, array('class'=>'form-control', 'placeholder'=>'Password')) }}
	{{ Form::passwordField('password_confirmation', null, array('class'=>'form-control', 'placeholder'=>'Confirm Password')) }}
 
	{{ Form::submit('Register', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}