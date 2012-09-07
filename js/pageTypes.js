// handles JS for pageTypes.php
var pageTypes = {
	
	init:function(){
		
		$('a.remove').live("click", function(){
			var uid = this.id.replace('remove-', '');
			var name = $('#edit-'+uid).html();
			
			$('#DeleteId').val(uid);
			$('#removeName').html(name);
			$('#DeleteDialog').modal('show');
		
			return false;
		});
		
		$('#DeleteAction').live("click", function(){
			
			var pageTypeUniqId = $('#DeleteId').val();
			
			$('#DeleteDialog').modal('hide');
			
			message.showMessage('progress', 'Removing Page Type...');
			
			// removes a page
			$.post('pageTypes.php', {
				Ajax: 'pageType.remove',
				PageTypeUniqId: pageTypeUniqId
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					$('#item-'+pageTypeUniqId).remove();
			
					message.showMessage('success', data.Message);
				}

			}, 'json');
			
		});
		
		$('a.edit').live("click", function(){
			
			$('#AddEditDialog').modal('show');

			$('#instructions').hide();
			$('#selectPageType').hide();
			$('#custom').show();
			
			var pageTypeUniqId = this.id.replace('edit-', '');
			
			$('#PageTypeUniqId').val(pageTypeUniqId);
			
			$('#FriendlyId').val($('#friendlyId-'+pageTypeUniqId).val());
			$('#TypeS').val($('#typeS-'+pageTypeUniqId).val());
			$('#TypeP').val($('#typeP-'+pageTypeUniqId).val());
			
			$('#AddEditTitle').html('Edit Page Type');
			$('#AddEditAction').val('Update Page Type');
			
			return false;
			
		});
	
		$('#AddPageType').click(function(){

			$('#selectPageType li').removeClass('selected');
			$('#AddEditAction').val('Add Page Type');
			
			$('#PageTypeUniqId').val('-1');
			
			$('#FriendlyId').val('');
			$('#TypeS').val('');
			$('#TypeP').val('');
		
			$('#AddEditTitle').html('Add Page Type');
			$('#AddEditAction').html('Add Page Type');
			
			$('#AddEditDialog').modal('show');
			
			return false;
		});
		
		$('#AddEditAction').live("click", function(){
			
			var pageTypeUniqId = $('#PageTypeUniqId').val();
			var typeS = jQuery.trim($('#TypeS').val());
			var typeP = jQuery.trim($('#TypeP').val());
			var friendlyId = $('#FriendlyId').val();
			
			if(typeS=='' || typeP=='' || friendlyId==''){
				message.showMessage('error', 'You must enter a Name (both plural and singular) and a Friendly URL.');
				return false;
			}
			
			var ajax = 'pageType.edit';
			
			var msgText = 'Updating Page Type...';
			
			if(pageTypeUniqId==-1){
				ajax = 'pageType.add';
				msgText = 'Adding Page Type...';
			}
	
			message.showMessage('progress', msgText);
			
			$.post('pageTypes.php', {
				Ajax: ajax,
				PageTypeUniqId: pageTypeUniqId,
				TypeS: typeS,
				TypeP: typeP,
				FriendlyId: friendlyId
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					if(ajax == 'pageType.add'){
						message.showMessage('success', data.Message);
						
						// add to table
						var html = '<div id="item-' + pageTypeUniqId + '" class="listItem new">' +
							'<a id="remove-' + pageTypeUniqId + '" class="remove" href="#"></a>' +
							'<h2><a id="edit-' + pageTypeUniqId + '" class="edit" href="#">' + typeP + '</a></h2>' +
							'<input id="typeS-' + pageTypeUniqId + '" type="hidden" value="' + typeS + '">' +
							'<input id="typeP-' + pageTypeUniqId + '" type="hidden" value="' + typeP + '">' +
							'<input id="friendlyId-' + pageTypeUniqId + '" type="hidden" value="' + friendlyId + '">' +
							'<em>Created now</em>' +
							'</div>';
	
						var divs = $('#pageTypeList div');
						
						if(divs.length > 0){
							$(html).insertBefore(divs[0]);
						}
						else{
							$('#pageTypeList').html(html);
						}	
					}
					else if(ajax == 'pageType.edit'){
						message.showMessage('success', data.Message);
						
						// update table
						$('#edit-' + pageTypeUniqId).html(typeP);
						$('#typeS-' + pageTypeUniqId).val(typeS);
						$('#typeP-' + pageTypeUniqId).val(typeP);
						$('#friendlyId-' + pageTypeUniqId).val(friendlyId);
					}
				}

				$('#AddEditDialog').modal('hide');
			}, 'json');
			
			return false;
			
		});
	
	}
	
}

$(document).ready(function(){
	pageTypes.init();
});