{{ Form::open(array('url'=>'tasks/new', 'class'=>'new-task', 'autocomplete' => 'off', 'role' => 'form')) }}

	{{ Form::selectField('user_id', 'User', $users) }}
	{{ Form::textField('title', null, null, array('class'=>'form-control', 'placeholder'=>'Title')) }}
	{{ Form::textareaField('description', null, null, array('class'=>'form-control', 'placeholder'=>'Description', 'rows' => 3)) }}

	{{ Form::submit('Add Task', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}