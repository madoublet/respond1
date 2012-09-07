// provides a passthrough page for Battery Wholesellers to add custom data
var passthrough = {
		
	isCheckout:false,
	
	init:function(){
	
		if($('#isCheckout').get(0)){
			passthrough.isCheckout=true;
			$('#PPMiniCart').hide();
			
			$('p.actions button').unbind('click');
			
			$('p.actions button').click(function(){
				
				var html = '<input type="hidden" name="custom" value="'+$('#company').val()+'">' +
					'<input type="hidden" name="first_name" value="'+$('#first-name').val()+'">' +
					'<input type="hidden" name="last_name" value="'+$('#last-name').val()+'">' +
					'<input type="hidden" name="address1" value="'+$('#address-1').val()+'">' +
					'<input type="hidden" name="address2" value="'+$('#address-2').val()+'">' +
					'<input type="hidden" name="city" value="'+$('#city').val()+'">' +
					'<input type="hidden" name="state" value="'+$('#state').val()+'">' +
					'<input type="hidden" name="zip" value="'+$('#zip').val()+'">' +
					'<input type="hidden" name="country" value="US">' +
					'<input type="hidden" name="night_phone_a" value="'+$('#telephone').val()+'">' +
					'<input type="hidden" name="email" value="'+$('#e-mail').val()+'">';
				
				$('#PPMiniCart form').append(html);
				
				$('#PPMiniCart form').submit();
			});
		}
	
		$('#PPMiniCart form').submit(function(){
			
			if(passthrough.isCheckout==true){
				var isValid = passthrough.validate();
				
				if(isValid==false){ // validate the form
					message.showMessage('error', 'You are missing one or more required fields.');
					return false;
				}
			}
			
			if(passthrough.isCheckout==false){
			  location.href=root+'page/payment';
			  return false;
			}
		});
	
	
	},
	
	// validates the form
	validate:function(){
		
		// build body
		var fields = $('#paymentForm').find('span.field');
		
		for(var x=0; x<fields.length; x++){
			
			var type = $(fields[x]).attr('data-type');
			var required = false;
			var req = $(fields[x]).attr('data-required');
			var hasError = false;
			if(req){
				if(req=='true')required = true;
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
			}
		}

		return !hasError;
	}

}

$(document).ready(function(){
	passthrough.init();
});
