// handles JS for settings.php
var layout = {
	
	types:null,
	
	init:function(){
		
		$('a.moveDown').live("click", function(){
			var uid = this.id.replace('moveDown-', '');
			
			jQuery('#item-'+uid).next().after(jQuery('#item-'+uid));
			
			layout.updateOrder();
			
			return false;
		});
		
		$('a.moveUp').live("click", function(){
			var uid = this.id.replace('moveUp-', '');
			
			jQuery('#item-'+uid).prev().before(jQuery('#item-'+uid));
			
			layout.updateOrder();
			
			return false;
		});
		
		$('a.remove').live("click", function(){
			var uid = this.id.replace('remove-', '');
			var name = $('#edit-'+uid).html();
			
			$('#DeleteId').val(uid);
			$('#removeName').html(name);
			dialog.show('DeleteDialog');
		
			return false;
		});
		
		$('input#DeleteAction').live("click", function(){
			
			var moduleUniqId = $('#DeleteId').val();
			
			dialog.hide('DeleteDialog');
			
			// removes a page
			$.post('layout.php', {
				Ajax: 'module.remove',
				ModuleUniqId: moduleUniqId
			}, function(data){
				$('#item-'+data).remove();
			
				message.showMessage('success', 'Module has been removed.');
			});
			
		});
		
		var pageTypeS = $('#PageTypeS').val();
		
		var types = $('span.config input.type');
		var names = $('span.config input.name');
		
		$('#Type').find('option').remove();
			
		for(var x=0; x<types.length; x++){
			$('#Type').append($("<option></option>").
		          attr("value", $(types[x]).val()).
		          text($(names[x]).val())); 
		}
					
		$('#ChangeType').show();
		
		$('a.edit').live("click", function(){
			
			$('#AddEditTitle').html('Edit Module');
			$('#AddEditAction').val('Update Module');
			
			var moduleUniqId = this.id.replace('edit-', '');
			
			$('#ModuleUniqId').val(moduleUniqId);
			var page = $('#Page').val();
		
			$('.config').hide();
			
			var type = $('#type-'+ moduleUniqId).val();
			
			$('#Type').val(type);
			
			var curr = '#config-' + type;
			
			var item = $('#item-'+moduleUniqId);
			
			var ids = item.find('.ids');
			var values = item.find('.values');
			
			for(x=0; x<ids.length; x++){
				var id = $(ids[x]).val();
				$('#'+type+'-'+id).val($(values[x]).val());
			}
		
			$('#ChangeType').hide();
			$(curr).show();
			
			dialog.show('AddEditModule');
			
			return false;
			
		});
	
		$('#AddModule').live("click", function(){
			
			$('#AddEditTitle').html('Add Module');
			$('#AddEditAction').val('Add Module');
			
			$('#ModuleUniqId').val('-1');
			var page = $('#Page').val();
		
			$('#AddEditModule input[type=text]').val('');
			$('#AddEditModule textarea').val('');
			$('#AddEditModule select').attr('selectedIndex', 0);

			$('.config').hide();
			
			var curr = '#config-' + $('#Type').val();
			
			$('#ChangeType').show();
			$(curr).show();
			
			dialog.show('AddEditModule');
			
			return false;
			
		});
		
		$('#Type').live("change", function(){
			
			$('.config').hide();
			
			var curr = '#config-' + $('#Type').val();
			
			$(curr).show();
			
			return false;
			
		});
		
		$('#AddEditAction').live("click", function(){
			
			var moduleUniqId = $('#ModuleUniqId').val();
			var pageTypeUniqId = $('#PageTypeUniqId').val();
			var type = $('#Type').val();
			var name = $("#Type option:selected").text();
			var page = $('#Page').val();
			var config = '';
			var priority = $('#layoutList div').length;
			var ids = [];
			var ltexts = [];
			var values = [];
			var texts = [];
			
			// add configs
			var curr = '#config-' + $('#Type').val();
			
			// get selects
			var fields = $(curr + ' span.field');
			var hasConfigs = false;
			
			for(x=0; x<fields.length; x++){
				
				var id = '';
				var label = '';
				var value = '';
				var text = '';
				
				var labels = $(fields[x]).find('label');
				var selects = $(fields[x]).find('select');
				var inputs = $(fields[x]).find('input[type=text]');
				var textareas =  $(fields[x]).find('textarea');
				
				if(selects.length>0){
					id = selects[0].id;
					label = $(labels[0]).html();
					label = label.replace(':', '');
					value = $(selects[0]).val();
					text = $("#" + selects[0].id + " option:selected").text();
					hasConfigs = true;
				}
				
				if(inputs.length>0){
					id = inputs[0].id;
					label = $(labels[0]).html();
					label = label.replace(':', '');
					value = $(inputs[0]).val();
					text = value.substr(0, 100);
					hasConfigs = true;
				}
				
				if(textareas.length>0){
					id = textareas[0].id;
					label = $(labels[0]).html();
					label = label.replace(':', '');
					value = $(textareas[0]).val();
					text = value.substr(0, 100);
					if(value.length>100)text += '...';
					hasConfigs = true;
				}
				
				ids[x] = id;
				ltexts[x] = label;
				values[x] = value;
				texts[x] = text;
			}
		
			var ajax = 'module.edit';
			var messageText = 'Updating module...';
			
			if(moduleUniqId==-1){
				ajax = 'module.add';
				messageText = 'Adding module...';
			}
			
			message.showMessage('progress', messageText);
			
			$.post('layout.php', {
				Ajax: ajax,
				ModuleUniqId: moduleUniqId,
				PageTypeUniqId: pageTypeUniqId,
				Type: type,
				Name: name,
				Page: page,
				Priority: priority,
				Ids:ids,
				Labels:ltexts,
				Values:values,
				Texts:texts
			}, function(data){
				
				moduleUniqId = data;
				
				var em = 'No Configurations';
				
				if(hasConfigs==true){
					em = '';
					
					for(x=0; x<ids.length; x++){
						em+=ltexts[x]+': '+layout.encodeHTML(texts[x]);
						if(x!=(ids.length-1)){
							em+=', ';
						}
					}
					
					for(x=0; x<ids.length; x++){
						em+='<input type="hidden" value="'+ids[x]+'" class="ids">';
						em+='<input type="hidden" value="'+layout.encodeHTML(values[x])+'" class="values">';
					}		
				}
				
				if(ajax=='module.edit'){ // set updated values
					$('#em-'+moduleUniqId).html(em);
					
					message.showMessage('success', 'You have successfully updated your configurations.');
				}
				else{ // add module
				
					$('#nolayout').remove();
				
					var html = '<div id="item-' + moduleUniqId + '" class="listItem">' +
						'<h2><a id="edit-' + moduleUniqId + '" class="edit">' + name + '</a></h2>' +
						'<input id="type-' + moduleUniqId + '" type="hidden" value="' + type + '">' +
						'<em>' + em + '</em>' +
						'<a id="remove-' + moduleUniqId + '" class="remove" href="#"></a>' +
						'<a id="moveUp-' + moduleUniqId + '" class="moveUp" href="#"></a>' +
						'<a id="moveDown-' + moduleUniqId + '" class="moveDown" href="#"></a>' +
						'</div>';

					var divs = $('#layoutList div');
					
					if(divs.length > 0){
						$(html).insertAfter(divs[(divs.length-1)]);
					}
					else{
						$('#layoutList').html(html);
					}	
					
					message.showMessage('success', 'You have successfully added your module.');
				
				}
				
				$('#AddEditModule').hide();
			
			});
			
			return false;
			
		});
	
	},
	
	// encodes the html
	encodeHTML:function(html){
		return html.replace(/&/g,'&amp;').replace(/\"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
	},
	
	// updates the module order
	updateOrder:function(){
		var moduleUniqIds = [];
			
		var items = $('div.listItem');
		
		for(var x=0; x<items.length; x++){
			moduleUniqIds[x] = items[x].id.replace('item-', '');
		}

  		message.showMessage('progress', 'Updating module order...');
		
		$.post('layout.php', {
			Ajax: 'module.changePriority',
			ModuleUniqIds: moduleUniqIds
		}, function(data){
			message.showMessage('success', 'Module order has been successfully updated.');	
		});
		
		return false;
	}
	
}

$(document).ready(function(){
	layout.init();
});