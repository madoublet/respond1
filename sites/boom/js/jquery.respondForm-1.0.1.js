(function($){  
	$.fn.respondForm = function(){
		
        var fields = $(this).find('div.control-group');
		
		for(var x=0; x<fields.length; x++){
			var req = $(fields[x]).attr('data-required');	
			
			var label = $(fields[x]).find('label:first');
			
			if(req=='true'){
				$(label).html('* '+$(label).html());
				$(fields[x]).addClass('required');
			}	
		}
		
		var context = this;
		
		$(this).find('button').click(function(){
			
			var siteUniqId = $(context).find('.siteUniqId').val();
			var pageUniqId = $(context).find('.pageUniqId').val();
			
			// build body
			var fields = $(context).find('div.control-group');
			
			var body = '<div class="form">';
			var email = '<table>';
			var subject = '';
			var hasError = false;
		
			for(var x=0; x<fields.length; x++){
				var label = $(fields[x]).find('label').html();
				var label = label.replace('* ', '');
				var text = '';
				
				var type = $(fields[x]).attr('data-type');
				var required = false;
				var req = $(fields[x]).attr('data-required');
				if(req){
					if(req=='true')required = true;
				}
				var mapping = $(fields[x]).attr('data-mapping');
				
				var span = '<span class="value"';
				if(mapping){
					span += ' data-mapping="'+mapping+'">'
				}
				else{
					span += '>';
				}
				
				if(type=='text'){
					text = $.trim($(fields[x]).find('input[type=text]').val());
					
					if(required==true && text==''){
						hasError = true;
						$(fields[x]).addClass('error');
					}
					else{
						$(fields[x]).removeClass('error');
					}
					
					text = span+text+'</span>';
				}
				else if(type=='textarea'){
					text = $.trim($(fields[x]).find('textarea').val());
					
					if(required==true && text==''){
						hasError = true;
						$(fields[x]).addClass('error');
					}
					else{
						$(fields[x]).removeClass('error');
					}
					
					text = span+text+'</span>';
				}
				else if(type=='select'){
					text = $(fields[x]).find('select').val();
					
					if(required==true && text==''){
						hasError = true;
						$(fields[x]).addClass('error');
					}
					else{
						$(fields[x]).removeClass('error');
					}
					
					text = span+text+'</span>';
				}
				else if(type=='radiolist'){
					text = $(fields[x]).find('input[type=radio]:checked').val();
					
					if(text==undefined)text = '';
					
					if(required==true && text==''){
						hasError = true;
						$(fields[x]).addClass('error');
					}
					else{
						$(fields[x]).removeClass('error');
					}
					
					text = span+text+'</span>';
				}
				else if(type=='checkboxlist'){
					var checkboxes = $(fields[x]).find('input[type=checkbox]:checked');
					
					for(var y=0; y<checkboxes.length; y++){
						text += '<span class="item">'+$(checkboxes[y]).val()+'</span>';
					}
					
					if(required==true && text==''){
						hasError = true;
						$(fields[x]).addClass('error');
					}
					else{
						$(fields[x]).removeClass('error');
					}
					
					text = span+text+'</span>';
				}
			
				body += '<span class="label">'+label+'</span>'+text+'';
				email += '<tr><td width="200" align="right" valign="top"><strong>'+label+'</strong></td><td valign="top">'+text+'</td>';
			}
			
			body += '</div>';
			email += '</table>'
			
			if(hasError == false){
	        	var url = root + 'controller.php';
	        	
	        	// alert(url);
			
				// add the post with ajax
				$.post(url, {
					Ajax: 'form.send',
					SiteUniqId: siteUniqId,
					PageUniqId: pageUniqId,
					Body: body,
					Email: email,
					Subject: subject
				}, function(data){
					// alert(data);
					
					$('span.field.error').removeClass('error');
					message.showMessage('success', 'You have successfully submitted the form.');
					
					$('div.formgroup input').val('');
					$('div.formgroup textarea').val('');
					$('div.formgroup select').val('');
					$('div.formgroup input[type=radio]').attr('checked', false);
					$('div.formgroup input[type=checkbox]').attr('checked', false);
				});
			}
			else{
				message.showMessage('error', 'You are missing one or more required fields.');
			}
		
		});
		
	}	
})(jQuery);