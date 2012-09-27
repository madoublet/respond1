// global js
var global = {
		
	events:null,
	
	init:function(){
		
		var maps = $('.map'); // setup maps
		
		for(var x=0; x<maps.length; x++){
			$(maps[x]).respondMap();
		}
		
		$('.carousel').carousel();
		
		var forms = $('.form-respond');
		
		for(var x=0; x<forms.length; x++){
			$(forms[x]).respondForm();
		}
		
		var lists = $('.list');
		
		for(var x=0; x<lists.length; x++){
			$(lists[x]).respondList();
		}

		prettyPrint();
	}
}

$(document).ready(function(){global.init();});
