// handles JS for html.php
var html = {
	
	types:null,
	
	init:function(){
	
		$('#ShowAddLayout').live("click", function(){
			$('#LayoutName').val('');
			$('#AddLayoutDialog').modal('show');
			return false;
		});
	
		
		var isConfigured = $('#IsConfigured').val();
		
		if(isConfigured==0){
			$('#isNotConfigured').show();
		}
	
		var labels = $('span.config input.label');
		var names = $('span.config input.name');
		
		$('#Name').find('option').remove();
			
		for(var x=0; x<names.length; x++){
			$('#Name').append($("<option></option>").
		          attr("value", $(names[x]).val()).
		          text($(labels[x]).val())); 
		}
					
		$('#ChangeName').show();

		$('a.remove').live("click", function(){
			var uid = this.id.replace('remove-', '');
	
			$('#DeleteId').val(uid);
			$('#removeName').html(uid);

			$('#DeleteDialog').modal('show');

			return false;
		});

		$('#DeleteAction').live("click", function(){
			
			var file = $('#DeleteId').val();

			$('#DeleteDialog').modal('hide');

			message.showMessage('progress', 'Removing layout...');
			
			// removes a page
			$.post('html.php', {
				Ajax: 'layout.remove',
				File: file
			}, function(data){

				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					var name = file.replace('.html', '');

					$('#custom-'+name).remove();
					message.showMessage('success', data.Message);
				}
				
			}, 'json');
			
		});
		
		$('#Name').live("change", function(){
			
			$('.config').hide();
			
			var curr = '#config-' + $('#Name').val();
			
			$(curr).show();
			
			html.createCode();
			
			return false;
			
		});
		
		
		$('#UpdateLayout').live("click", function(){
			
			var file = $('#File').val();
			var content = $('#Content').val();
			
			message.showMessage('progress', 'Updating layout...');
			
			$.post('html.php', {
				Ajax: 'layout.update',
				File: file,
				Content: content
			}, function(data){
				if(data.IsSuccessful=='true'){
					message.showMessage('success', data.Message);
				}
				else{
					message.showMessage('error', data.Error);
				}
				
			}, 'json');
			
			return false;
			
		});
		
		// adds a layout
		$('#AddLayout').live("click", function(){
			
			var name = jQuery.trim($('#LayoutName').val());
			
			if(name==''){
				message.showMessage('error', 'A name is required to add a layout');
				return false;
			}

			if(name.toLowerCase()=='home'){
				message.showMessage('error', 'Home is reserved for the home page layout');
				return false;
			}

			if(name.toLowerCase()=='content'){
				message.showMessage('error', 'Content is reserved for content layouts');
				return false;
			}
			
			// check existing names
			var links = $('#layoutMenu li a');
			
			for(var x=0; x<links.length; x++){
				var c_name = jQuery.trim($(links[x]).html());
				
				if(c_name.toLowerCase()==name.toLowerCase()){
					message.showMessage('error', 'The name of the layout must be unique.');
					return false;
				}
			
			}
	
			message.showMessage('progress', 'Adding layout...');
			
			$.post('html.php', {
				Ajax: 'layout.addLayout',
				Name: name
			}, function(data){

				if(data.IsSuccessful=='true'){
					message.showMessage('success', data.Message);

					// add layout to list
					var file = data.File;
					var name = file.replace('.html', '');

					var h = '<li id="custom-'+file+'" class="custom"><a href="html.php?f='+file+'.html">'+name+'.html</a><a id="remove-'+file+'.html" class="remove" href="#"></a></li>';

					$('#layoutMenu').append(h);
					
					message.showMessage('success', 'You have successfully added a stylesheet.');
				}
				else{
					message.showMessage('error', data.Error);
				}


				$('#AddLayoutDialog').modal('hide');

			}, 'json');
			
			return false;
		});
	
	}
	
}

$(document).ready(function(){
	html.init();
});