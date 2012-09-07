// handles JS for publish.php
var publishSite = {
	
	init:function(){
		
		$('#PublishSite').live("click", function(){
		
			message.showMessage('progress', 'Publishing site...');
			$('#PublishSite').attr('disabled', 'disabled');
			
			// update the co info
			$.post('publishSite.php', {
				Ajax: 'publish.publishSite'
			}, function(data){

				$('#PublishSite').removeAttr('disabled');
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
				}

			}, 'json');
		
			return false;	
		});
		
	}
	
}

$(document).ready(function(){
	publishSite.init();
});

