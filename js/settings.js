// handles JS for settings.php
var settings = {
	
	init:function(){
		
		$('#Update').live("click", function(){
		
			var name = $('#Name').val();
			var domain = $('#Domain').val();
			var facebookAppId = $('#FacebookAppId').val();
			var analyticsId = $('#AnalyticsId').val();
			var primaryEmail = $('#PrimaryEmail').val();
			var timeZone = $('#TimeZone').val();
			
			if(name=='' || domain == ''){
				message.showMessage('error', 'You need to have both a name and domain.');	
				return;
			}

			message.showMessage('progress', 'Updating settings...');
			
			// update the co info
			$.post('settings.php', {
				Ajax: 'settings.updateBasic',
				Name: name,
				Domain: domain,
				AnalyticsId: analyticsId,
				FacebookAppId: facebookAppId,
				PrimaryEmail: primaryEmail,
				TimeZone: timeZone
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
				}

			}, 'json');
		
			return false;	
		});
		
		$('#AddAction').live("click", function(){
			
			var fileName = jQuery.trim($('#FileName').val());
			var fileContent = jQuery.trim($('#FileContent').val());
			
			if(fileName==''){
				message.showMessage('error', 'You must add a file name.');
				return;
			}
			
			if(fileContent==''){
				message.showMessage('error', 'You must add content for the file.');
				return;
			}
			
			// removes a page
			$.post('settings.php', {
				Ajax: 'settings.verify',
				FileName: fileName,
				FileContent: fileContent
			}, function(data){

				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
				}

				$('#VerifyDialog').modal('hide');
				
			}, 'json');
			
		});
		
		
		$('#ShowVerifyDialog').live("click", function(){
			$('#FileName').val('');
			$('#FileContent').val('');

			$('#VerifyDialog').modal('show');
			
			return false;
		});
		
	}
	
}

$(document).ready(function(){
	settings.init();
});

