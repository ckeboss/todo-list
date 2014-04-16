jQuery(document).ready(function($) {
	//Registration
	$('form#registration-form').on('submit', function() {
	
		executePost($(this), function(data) {
			$('form#registration-form .form-group input').val('');
			$('#login-registation-tabs a[href="#login"]').tab('show');
			$('#login').prepend('<div class="alert alert-success">'+data.message+'</div>');
		});
		
		return false;
	});
	
	//Adding task
	$('#new-task form.new-task').on('submit', function() {
		
		executePost($(this), function(data) {
			$('#new-task form.new-task .form-group input, #new-task form.new-task .form-group textarea').val('');
			
			appendTask(data.task.finished, data.task.title, data.task.id);
		});
		
		return false;
	});
	
	$('#tasks .pagination').on('click', 'a', function() {
		if($(this).hasClass('next') || $(this).hasClass('prev')) {
			//handle
		} else {
			var clickedElement = this;
			loadTasks($(this).attr('href'), false, function() {
				$('li', $(clickedElement).parent().parent()).removeClass('active');
				$(clickedElement).parent().addClass('active');
			});
		}
		
		return false;
	});
	
	$('#tasks').on('click', 'input[type=checkbox]', function() {
		
	});
	
	//Load tasks
	loadTasks(app.url+'/tasks', true);
});

function markAsRead() {
	
}

function loadTasks(url_to_load, update_pages, callback) {
	
	$.getJSON(url_to_load, function(data) {
		$('#tasks>table>tbody').empty();
		$.each(data.tasks.data, function(key, task) {
			appendTask(task.finished, task.title, task.id);
		});
		
		if(update_pages) {
			for(var i=1; i<data.tasks.last_page+1; i++) {
				$('.pagination').append($('<li'+(i==1 ? ' class="active"' : '')+'><a href="'+app.url+'/tasks?page='+i+'">'+i+'</a></li>'));
			}
		}
		if (callback && typeof(callback) === "function") {
			callback();
		}
	});
}

function appendTask(finished, title, id) {
	checkbox = '<input type="checkbox" value="1" name="task['+htmlEncode(id)+']" />';
	if(finished == 1) {
		checkbox = '<input type="checkbox" value="1" name="task['+htmlEncode(id)+']" checked="checked" />';
	}
	$('#tasks>table>tbody').append('<tr><td class="text-center">'+checkbox+'</td><td>'+htmlEncode(title)+'</td></tr>');
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