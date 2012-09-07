// handles JS for sites.php
var sites = {
	
	init:function(){
		
		$('a.switch').live("click", function(){
			
			var siteUniqId = this.id.replace('switch-', '');
			
			$.post('sites.php', {
				Ajax: 'sites.switch',
				SiteUniqId: siteUniqId
			}, function(data){
				
				$('#siteList tr').removeClass('active');
				$('#site-'+siteUniqId).addClass('active');
			
				message.showMessage('success', 'You have successfully switched sites.');
			
			});
			
			return false;
			
		});
	
	}
	
}

$(document).ready(function(){
	sites.init();
});