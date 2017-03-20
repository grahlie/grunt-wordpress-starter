jQuery(document).ready(function($){

	// AJAX submit form function
	$('#grahlie-framework form').submit(function(){
		var form = $(this);
		var button = $('#grahlie-framework #save-button');
		var buttonVal = button.val();

		$(form).ajaxSubmit({
			dataType: 'json',
			beforeSubmit: function(formData){
				button.val('Saving settings');
				var formData = $('#grahlie-framework form').formSerialize();
			},
			success: function(data){
				button.val(buttonVal);
				$('#grahlie-messages #message p').html(data.message);
				$('#grahlie-messages').css('display', 'block');
			}
		});

		return false;
	});

	//AJAX reset form function
	$('#grahlie-framework #reset-button').click(function(){
		var form = $('#grahlie-framework form');
		var button = $(this);
		var buttonVal = button.val();
	
		$(button).val('Removing settings');
		$.post(ajaxurl, {action: 'grahlie_framework_reset', nonce:$('#grahlie_noncename').val()}, function(data){
			if(data.error){
				$('#grahlie-messages #message p').html(data.message);
				$('#grahlie-messages').css('display', 'block');
			} else {
				$(form).clearForm();
				$(button).val(buttonVal);
				window.location.reload(true);
			}
		}, 'json');

		return false;
	});
});
