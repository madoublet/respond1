// js for forgot
var forgot = {

	init:function(){
		
		$('#request').live("click", function(){

			var email = jQuery.trim($('#Email').val());

			$.post('forgot.php', {
				Ajax: 'user.requestReset',
				Email: email
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Success);
				}
			
			});
			
			return false;
		});
		
		$('#reset').live("click", function(){
			
			var qs = jQuery.trim($('#qs').val());
			var password = jQuery.trim($('#Password').val());
			var retype = jQuery.trim($('#Retype').val());
			
			if(password==''){
				message.showMessage('error', 'You must enter a password.');
				return false;
			}
			
			if(password!=retype){
				message.showMessage('error', 'The password you enter must match what you have in the retype box.');
				return false;
			}
			
			$.post('forgot.php', {
				Ajax: 'password.reset',
				Password: password,
				QueryString: qs
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Success);
				}
			
			}, 'json');
			
			return false;
		});
			
	}
	
}

$(document).ready(function(){
	forgot.init();
});
