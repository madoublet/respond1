// handles JS for menuTypes.php
var menuTypes = {
	
	init:function(){
		
		$('a.remove').live("click", function(){
			var uid = this.id.replace('remove-', '');
			var name = $('#edit-'+uid).html();
			
			$('#DeleteId').val(uid);
			$('#removeName').html(name);
			$('#DeleteDialog').modal('show');

			return false;
		});
		
		$('input#DeleteAction').live("click", function(){
			
			var menuTypeUniqId = $('#DeleteId').val();

			$('#DeleteDialog').modal('hide');
			
			message.showMessage('progress', 'Removing Menu Type...');
			
			// removes a menu type
			$.post('menuTypes.php', {
				Ajax: 'menuType.remove',
				MenuTypeUniqId: menuTypeUniqId
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					$('#item-'+menuTypeUniqId).remove();
			
					message.showMessage('success', data.Message);
				}

			}, 'json');
			
		});
		
		$('a.edit').live("click", function(){

			$('#AddEditDialog').modal('show');
			
			var menuTypeUniqId = this.id.replace('edit-', '');
			
			$('#MenuTypeUniqId').val(menuTypeUniqId);
			
			$('#Name').val($('#name-'+menuTypeUniqId).val());
			$('#FriendlyId').val($('#friendlyId-'+menuTypeUniqId).val());
			
			$('#AddEditTitle').html('Edit Menu Type');
			$('#AddEditAction').val('Update Menu Type');
			
			return false;
			
		});
		
		$('#AddMenuType').click(function(){

			$('#AddEditAction').val('Add Menu Type');
			
			$('#MenuTypeUniqId').val('-1');
			
			$('#FriendlyId').val('');
			$('#Name').val('');
			
			$('#Profile').attr('selectedIndex', 0);
			
			$('#AddEditTitle').html('Add Menu Type');
			$('#AddEditAction').html('Add Menu Type');

			$('#AddEditDialog').modal('show');
			
			return false;
		});
		
		$('#AddEditAction').live("click", function(){
			
			var menuTypeUniqId = $('#MenuTypeUniqId').val();
			var name = $('#Name').val();
			var friendlyId = $('#FriendlyId').val();
			
			if(name=='' || friendlyId==''){
				message.showMessage('error', 'You must enter a Name (both plural and singular) and a Friendly Id.');
				return false;
			}
			
			var ajax = 'menuType.edit';
			
			var msgText = 'Updating Menu Type...';
			
			if(menuTypeUniqId==-1){
				ajax = 'menuType.add';
				msgText = 'Adding Menu Type...';
			}
	
			message.showMessage('progress', msgText);
			
			$.post('menuTypes.php', {
				Ajax: ajax,
				MenuTypeUniqId: menuTypeUniqId,
				Name: name,
				FriendlyId: friendlyId
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					if(ajax == 'menuType.add'){
						message.showMessage('success', data.Message);
						
						// add to table
						var html = '<div id="item-' + menuTypeUniqId + '" class="listItem new">' +
							'<a id="remove-' + menuTypeUniqId + '" class="remove" href="#"></a>' +
							'<h2><a id="edit-' + menuTypeUniqId + '" class="edit" href="#">' + name + '</a></h2>' +
							'<input id="name-' + menuTypeUniqId + '" type="hidden" value="' + name + '">' +
							'<input id="friendlyId-' + menuTypeUniqId + '" type="hidden" value="' + friendlyId + '">' +
							'<em>Created now</em>' +
							'</div>';
	
						var divs = $('#menuTypeList div');
						
						if(divs.length > 0){
							$(html).insertBefore(divs[0]);
						}
						else{
							$('#menuTypeList').html(html);
						}	
					}
					else if(ajax == 'menuType.edit'){
						message.showMessage('success', data.Message);
						
						// update table
						$('#edit-' + menuTypeUniqId).html(name);
						$('#name-' + menuTypeUniqId).val(name);
						$('#friendlyId-' + menuTypeUniqId).val(friendlyId);
					}
				}

				$('#AddEditDialog').modal('hide');
			
			}, 'json');
			
			return false;
			
		});
	
	}
	
}

$(document).ready(function(){
	menuTypes.init();
});