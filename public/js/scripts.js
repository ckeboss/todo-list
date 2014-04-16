jQuery(document).ready(function($) {
	$('form#registration-form').on('submit', function() {
	
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			error: function(jqxhr) {
				var errors = $.parseJSON(jqxhr.responseText).errors;
				
				$('form#registration-form .form-group').removeClass('has-error');
				$('form#registration-form .help-block').remove();
				
				for(var field_name in errors) {
					var the_field = errors[field_name];
					if(the_field) {
						$('<span class="help-block">'+the_field+'</span>').insertAfter($('#id-field-'+field_name));
						$('#id-field-'+field_name).parent().addClass('has-error');
					}
				}
			},
			success: function(data) {
				$('form#registration-form input').val('');
				$('#login-registation-tabs a[href="#login"]').tab('show');
				$('#login').prepend('<div class="alert alert-success">'+data.msg+'</div>');
			}
			
		});
			
		return false;
	});
});