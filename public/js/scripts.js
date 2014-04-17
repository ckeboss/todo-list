jQuery(document).ready(function($) {

	// this will send the headers on every ajax request you make via jquery... http://www.keltdockins.com/2/post/2013/09/laravel-4-csrf-token-and-ajax-using-jquery.html
	$(function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
			}
		});
	});

	//Registration
	$('form#registration-form').on('submit', function() {
	
		executePost($(this), function(data) {
			$('form#registration-form .form-group input').val('');
			$('#login-registation-tabs a[href="#login"]').tab('show');
			$('#login').prepend('<div class="alert alert-success">'+data.message+'</div>');
		});
		
		return false;
	});
	
	//Login
	$('#login>form').on('submit', function(){
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			error: function(jqxhr) {
				var data = $.parseJSON(jqxhr.responseText);
				
				//remove any prev. alerts
				$('#login>div.alert').remove();
				$('#login').prepend('<div class="alert alert-danger">'+data.message+'</div>');
			},
			success: function(data) {
				app.user_id = data.user_id;
				
				//remove login/register
				$('#login-registation-tabs').remove();
				$('.login-registation').remove();
				
				$('#username-welcome').text(data.name);
				
				$('#new-task').load(app.url+'/tasks/new', function() {
					initData();
					$('#todo-main-app').show();
				});
//{{ View::make('tasks.new')->with('users', $users); }}
			}
		});
		
		return false;
	});
	
	//Adding task
	$('#new-task').on('submit', 'form.new-task', function() {
		
		executePost($(this), function(data) {
			$('#new-task form.new-task .form-group input, #new-task form.new-task .form-group textarea').val('');
			
			if(data.task.user.id != app.user_id) {
				appendTask(data.task.finished, data.task.title, data.task.description, data.task.id, data.task.user.name, $('#others-tasks>table>tbody'));
			} else {
				appendTask(data.task.finished, data.task.title, data.task.description, data.task.id, false, $('#tasks>table>tbody'));
			}
		});
		
		return false;
	});
	
	//Manages paging to your tasks
	$('#tasks .pagination').on('click', 'a', function() {
		var clickedElement = this;
		loadTasks($(this).attr('href'), false, $('#tasks>table>tbody'), function() {
			$('li', $(clickedElement).parent().parent()).removeClass('active');
			$(clickedElement).parent().addClass('active');
		});
		
		return false;
	});

	//Manages paging to others tasks TODO combine
	$('#others-tasks .pagination').on('click', 'a', function() {
		var clickedElement = this;
		loadTasks($(this).attr('href'), false, $('#others-tasks>table>tbody'), function() {
			$('li', $(clickedElement).parent().parent()).removeClass('active');
			$(clickedElement).parent().addClass('active');
		});
		
		return false;
	});
	
	//Manages saving compleated/not compleated
	$('#tasks, #others-tasks').on('change', 'input[type=checkbox]', function() {
		if(this.checked) {
			//Checked
			markTask(true, $(this).val(), function(data){
				console.log(data);
			});
			
			return false;
		}
		
		//Unchecked
		markTask(false, $(this).val(), function(data){
			console.log(data);
		});
		
		return false;
	});
	
	//Manages deleting
	$('#tasks, #others-tasks').on('click', 'button', function(){
		var clickedButton = $(this);
		
		if(confirm('Are you sure you want to delete this?')) {
			$.ajax({
				type: 'DELETE',
				dataType: 'json',
				url: app.url+'/tasks/delete',
				data: {id: $('input[type=checkbox]', clickedButton.parent().parent()).val()},
				success: function(data) {
					clickedButton.parent().parent().remove();
				}
			})
		}
	});
	
	if(app.user_id) {
		initData();
	}
	
	$('#tasks, #others-tasks').popover({
		selector: '.popover-trigger',
		trigger: 'hover'
    });
});

function initData() {
	loadTasks(app.url+'/tasks', true, $('#tasks>table>tbody'));
	loadTasks(app.url+'/tasks/others', true, $('#others-tasks>table>tbody'));
}

function markTask(read, task_id, success_callback) {
	var post_url = (read ? app.url+'/tasks/done' : app.url+'/tasks/not-done');
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: post_url,
		data: {id: task_id},
		error: function(jqxhr) {
			//Should probably do something
		},
		success: success_callback
		
	});
}

function loadTasks(url_to_load, update_pages, container, callback) {
	
	$.getJSON(url_to_load, function(data) {
		container.empty();
		$.each(data.tasks.data, function(key, task) {
			if(task.user) {
				appendTask(task.finished, task.title, task.description, task.id, task.user.name, container);
			} else {
				appendTask(task.finished, task.title, task.description, task.id, false, container);
			}
		});
		
		if(update_pages) {
			for(var i=1; i<data.tasks.last_page+1; i++) {
				$('.pagination', container.parent().parent()).append($('<li'+(i==1 ? ' class="active"' : '')+'><a href="'+url_to_load+'?page='+i+'">'+i+'</a></li>'));
			}
		}
		if (callback && typeof(callback) === "function") {
			callback();
		}
	});
}

function appendTask(finished, title, description, id, name, container) {
	checkbox = '<input type="checkbox" value="'+htmlEncode(id)+'" name="task['+htmlEncode(id)+']" />';
	if(finished == 1) {
		checkbox = '<input type="checkbox" value="'+htmlEncode(id)+'" name="task['+htmlEncode(id)+']" checked="checked" />';
	}

	if(!name) {
		container.append('<tr><td class="text-center">'+checkbox+'</td><td><div class="popover-trigger" data-toggle="popover" data-placement="bottom" data-content="'+htmlEncode(description)+'">'+htmlEncode(title)+'</div></td><td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
	} else {
		container.append('<tr><td class="text-center">'+checkbox+'</td><td><div class="popover-trigger" data-toggle="popover" data-placement="bottom" data-content="'+htmlEncode(description)+'">'+htmlEncode(title)+'</div></td><td>'+name+'</td><td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
	}
}

function executePost(form_ele, success_callback) {
	$('.form-group', form_ele).removeClass('has-error');
	$('.help-block', form_ele).remove();
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: form_ele.attr('action'),
		data: form_ele.serialize(),
		error: function(jqxhr) {
			var errors = $.parseJSON(jqxhr.responseText).errors;
			
			for(var field_name in errors) {
				var the_field = errors[field_name];
				if(the_field) {
					$('<span class="help-block">'+the_field+'</span>').insertAfter($('#id-field-'+field_name));
					$('#id-field-'+field_name).parent().addClass('has-error');
				}
			}
		},
		success: success_callback
		
	});
}

function htmlEncode(value){
	return $('<div/>').text(value).html();
}