// handles JS for templates.php
var templates = {
	
	init:function(){
	
		$('input.apply').click(function(){
			
			var template = $(this).attr('data-template');
			var node = this;

			message.showMessage('progress', 'Updating template...');
			
			$.post('templates.php', {
				Ajax: 'template.edit',
				Template: template
			}, function(data){

				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
					$('div.listItem').removeClass('active');
					$(node.parentNode).addClass('active');
				}

			}, 'json');
			
			return false;
		});

		$('.reset').click(function(){
			
			var template = $(this).attr('data-template');
			var node = this;
			message.showMessage('progress', 'Resetting template...');
			
			$.post('templates.php', {
				Ajax: 'template.reset',
				Template: template
			}, function(data){

				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
					$('div.listItem').removeClass('active');
					$(node.parentNode).addClass('active');
				}
				
			}, 'json');
			
			return false;
		});
	
	}
	
}

$(document).ready(function(){
	templates.init();
});