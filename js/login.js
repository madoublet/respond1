// js for login
var login = {

	init:function(){

		
		if(document.getElementById('hasError')){
			
			if($('#hasError').val()=='true'){
				
				message.showMessage('error', $('p#message span').html());
			}
			
		}
		
		
			
	}
	
}

$(document).ready(function(){
	login.init();
});
