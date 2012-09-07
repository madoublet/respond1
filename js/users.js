// handles javascript for users.php
var users = {
	
	// page initialization function
	init:function(){
		
		$('#ShowAddDialog').live("click", function(){
			$('#UserUniqId').val('-1');
			$('#AddEditTitle').html('Add User');
			$('#AddEditAction').val('Add User');
			
			$('#AddEditDialog input[type=text], AddEditDialog input[type=password]').val('');
			$('#Role').val('Admin');

			$('#AddEditDialog').modal('show');
		});
		
		$('a.remove').live("click", function(){
			var userUniqId = this.id.replace('remove-', '');
			
			var name = $('#name-'+ userUniqId).html();

			$('#DeleteId').val(userUniqId);
			$('#removeName').html(name);
			
			$('#DeleteDialog').modal('show');
			
			return false;
		});

		$('a.edit').click(function(){
			var userUniqId = this.id.replace('edit-', '');

			$('#AddEditTitle').html('Edit User');
			$('#AddEditAction').val('Update User');

			var firstName = $('#FirstName-'+userUniqId).val();
			var lastName = $('#LastName-'+userUniqId).val();
			var role = $('#Role-'+userUniqId).val();
			var email = $('#Email-'+userUniqId).val();
			var password = 'temppassword';

			$('#UserUniqId').val(userUniqId);
			$('#FirstName').val(firstName);
			$('#LastName').val(lastName);
			$('#Role').val(role);
			$('#Email').val(email);
			$('#Password').val(password);
			$('#Retype').val(password);

			$('#AddEditDialog').modal('show');

			return false;
		});
		
		$('#DeleteAction').live("click", function(){
			
			var userUniqId = $('#DeleteId').val();
			
			$('#DeleteDialog').modal('hide');

			// removes a page
			$.post('users.php', {
				Ajax: 'users.remove',
				UserUniqId: userUniqId
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{				
					$('#user-'+data.UserUniqId).remove();

					message.showMessage('success', data.Message);
				}

			}, 'json');
			
		});

		$('#AddEditAction').click(function(){
			
			var userUniqId = $('#UserUniqId').val();
			var email = $('#Email').val();
			var password = $('#Password').val();
			var retype = $('#Retype').val();
			var role = $('#Role').val();
			var firstName = $('#FirstName').val();
			var lastName = $('#LastName').val();

			var retype = $('#Retype').val();
		
			if(email==''){
				message.showMessage('error', 'You need to add an email for the user.');
				return;
			}
		
			if(firstName==''){
				message.showMessage('error', 'You need to add a first name for the user.');
				return;
			}
			
			if(lastName==''){
				message.showMessage('error', 'You need to add a last name for the user.');
				return;
			}
			
			if (password != retype) {
				message.showMessage('error', 'The password must equal the retype field.');
				return;
			}
			
			if(userUniqId == -1){
				message.showMessage('progress', 'Adding user...');
			}
			else message.showMessage('progress', 'Updating page...');
			
			$.post('users.php', {
				Ajax: 'users.update',
				UserUniqId: userUniqId,
				Email: email,
				Password: password,
				FirstName: firstName,
				LastName: lastName,
				Role: role
			}, function(data){

				if(data.IsSuccessful == 'true'){

					if(userUniqId==-1){ // add a user row

						userUniqId = data.UserUniqId;

						var html = '<div id="user-' + userUniqId + '" class="listItem">' +
										'<a id="remove-' + userUniqId + '" class="remove" href="#"></a>' +
										'<h2><a id="edit-' + userUniqId + '" href="#" class="edit">' + firstName + ' ' + lastName + '</a></h2>' +
										'<p>' + role + '</p>' +
										'<input id="UserUniqId-' + userUniqId + '" type="hidden" value="' + userUniqId + '">' +
										'<input id="FirstName-' + userUniqId + '" type="hidden" value="' + firstName + '">' +
										'<input id="LastName-' + userUniqId + '" type="hidden" value="' + lastName + '">' +
										'<input id="Role-' + userUniqId + '" type="hidden" value="' + role + '">' +
										'<input id="Email-' + userUniqId + '" type="hidden" value="' + email + '">' +
										'<em>Created Now</em>' +
										'</div>';

						$('#usersList').append(html);

						message.showMessage('success', data.Message);
					}		
					else{ // update the data
						$('#edit-'+userUniqId).html(firstName + ' ' + lastName);
						$('#FirstName-'+userUniqId).val(firstName);
						$('#LastName-'+userUniqId).val(lastName);
						$('#Email-'+userUniqId).val(email);
						$('#Role-'+userUniqId).val(role);

						message.showMessage('success', data.Message);
					}

					$('#AddEditDialog').modal('hide');
				}
				else{
					message.showMessage('error', data.Error);
				}
			
			}, 'json');
		
			return false;	
		});
	}
}

$(document).ready(function(){
	users.init();
});

	

