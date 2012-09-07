// handles JS for create.php
var create = {
	
	init:function(){
	
		$('#Name').keyup(function(){
			var keyed = $(this).val().toLowerCase().replace(/[^a-zA-Z 0-9]+/g,'').replace(/\s/g, '');
			keyed = keyed.substring(0,25);
			$('#tempUrl').removeClass('temp');
			$('#tempUrl').html(keyed);
			$('#FriendlyId').val(keyed);
		});
		
		$('#Create').click(function(){

			// get passcode
			var passcode = $('#Passcode').val();
		
			// get info
			var name = jQuery.trim($('#Name').val());;
			var friendlyId = jQuery.trim($('#FriendlyId').val());
			
			// get admin
			var email = jQuery.trim($('#Email').val());
			var firstName = 'New';
			var lastName = 'User';
			var password = jQuery.trim($('#Password').val());
			var retype = jQuery.trim($('#Retype').val());
			
			if(name=='' || friendlyId=='' || email=='' || firstName=='' || lastName=='' || password=='' || retype==''){
				message.showMessage('error', 'All fields are required.');
				return;
			}
			
			if(password!=retype){
				message.showMessage('error', 'The password and retype fields must match.');
				return;
			}
			
			message.showMessage('progress', 'Creating site...');

			$.post('create.php', {
				Ajax: 'create.create',
				Name: name,
				FriendlyId: friendlyId,
				Email: email,
				FirstName: firstName,
				LastName: lastName,
				Password: password,
				Retype: retype,
				Passcode: passcode
			}, function(data){

				if (data.IsSuccessful == 'true') {
					message.showMessage('success', 'Site successfully created.  Welcome to Respond CMS!');
					$('#create-form').hide();
					$('#create-confirmation').slideDown();
					$('#create-title').html('Welcome to Respond CMS.');
					
					// update login link
					var href = $('a#loginLink').html();
					href = href.replace('{friendlyId}', friendlyId);
					$('a#loginLink').html(href);
					$('a#loginLink').attr('href', href);
					
					// update site link
					href = $('a#siteLink').html();
					href = href.replace('{friendlyId}', friendlyId);
					$('a#siteLink').html(href);
					$('a#siteLink').attr('href', href);
					
					// reset form
					$('#CompanyName').val('');
					$('#CompanyDomain').val('http://');
					$('#FriendlyId').val('');
					$('#Email').val('');
					$('#FirstName').val('');
					$('#LastName').val('');
					$('#Password').val('');
					$('#Retype').val('');
					$('#InviteCode').val('');
				}
				else{
					message.showMessage('error', data.Error);
				}
				
				$('div#progress').fadeOut();
			
			}, 'json');
		
			return false;	
		});
		
	}
	
}

$(document).ready(function(){
	create.init();
});

