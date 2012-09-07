// global js
var global = {
		
	events:null,
	
	init:function(){
		
		var maps = $('.map'); // setup maps
		
		for(var x=0; x<maps.length; x++){
			$(maps[x]).respondMap();
		}
		
		$('.carousel').carousel();
		
		var formgroups = $('.formgroup');
		
		for(var x=0; x<formgroups.length; x++){
			$(formgroups[x]).respondForm();
		}
		
		var lists = $('.list');
		
		for(var x=0; x<lists.length; x++){
			$(lists[x]).respondList();
		}
	}
}

$(document).ready(function(){global.init();});
