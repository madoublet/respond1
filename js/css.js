// handles JS for css.php
var css = {
	
	types:null,
	
	init:function(){
	
		$('#ShowAddDialog').live("click", function(){
			
			$('#LayoutName').val('');
			
			$('#AddLayoutDialog').modal('show');
			
			return false;
			
		});
					
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

			message.showMessage('progress', 'Removing stylesheet...');

			// removes a page
			$.post('css.php', {
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

		$('span.config select').live("change", function(){
			
			css.createCode();
			
			return false;
			
		});

		$('#UpdateLayout').live("click", function(){
			
			var file = $('#File').val();
			var content = $('#Content').val();
			
			message.showMessage('progress', 'Updating layout...');
			
			$.post('css.php', {
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
		
		$('#AddLayout').live("click", function(){
			
			var name = jQuery.trim($('#LayoutName').val());

			if(name==''){
				message.showMessage('error', 'A name is required to add a stylesheet');
				return false;
			}

			if(name.toLowerCase()=='global'){
				message.showMessage('error', 'Global is reserved for the global stylesheet');
				return false;
			}

			if(name.toLowerCase()=='home'){
				message.showMessage('error', 'Home is reserved for the home page stylesheet');
				return false;
			}

			if(name.toLowerCase()=='content'){
				message.showMessage('error', 'Content is reserved for content stylesheet');
				return false;
			}
			
			// check existing names
			var links = $('#layoutMenu li a');
			
			for(var x=0; x<links.length; x++){
				var c_name = jQuery.trim($(links[x]).html());
				
				if(c_name.toLowerCase()==name.toLowerCase()){
					message.showMessage('error', 'The name of the stylesheet must be unique.');
					return false;
				}
			
			}
			
			message.showMessage('progress', 'Adding Stylesheet...');
			
			$.post('css.php', {
				Ajax: 'layout.addLayout',
				Name: name
			}, function(data){

				if(data.IsSuccessful=='true'){
					message.showMessage('success', data.Message);

					// add layout to list
					var file = data.File;
					var name = file.replace('.less', '');

					var h = '<li id="custom-'+file+'" class="custom"><a href="css.php?f='+file+'.less">'+name+'.less</a><a id="remove-'+file+'.less" class="remove" href="#"></a></li>';

					$('#layoutMenu').append(h);	
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
	css.init();
});