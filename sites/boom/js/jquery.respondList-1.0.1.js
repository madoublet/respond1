(function($){  
	$.fn.respondList = function(){
		
		var id = $(this).attr('id');
		var type = $('#'+id+'-type').val();
		var container = null;
		
		// handles a successful Ajax call
		function handleSuccess(data){
			var total = parseInt($('#'+id+'-totalpages').val());
			var pageNo = $('#'+id+'-pageNo').val();
			
			$('#'+id+'-pageNo').val(pageNo+1);
			
			if(pageNo==total){
				$('#'+id+'-pager').hide();
			}
		
			var current =  parseInt($('#'+id+'-current').val())+1; // increment counter
			
			$('#'+id+'-current').val(current);
			
			var newid = 'new-'+(current);
			
			var html = '<div id="'+newid+'" class="newpage">'+data+'</div>';
			
			$('div#'+id).append(html);
			$('div#'+newid).slideDown();
			
			$('#pager-'+id).find('button.pager').removeAttr('disabled');
		}
		
	
		// handle the click of the pager button
        $('#'+id+'-pager').click(function(){
        	
        	this.disabled = true;
        	
        	var listid = this.id.replace('-pager', '');
        
        	// get setup variables
        	var siteuniqid = $('#'+listid+'-siteuniqid').val();
        	var typeid = $('#'+listid+'-typeid').val();
        	var pageno = $('#'+listid+'-pageno').val();
        	var totalpages = $('#'+listid+'-totalpages').val();
    		var desclength = $('#'+listid+'-desclength').val();
    		var length = $('#'+listid+'-length').val();
        	var orderby = $('#'+listid+'-orderby').val();
        	var groupby = $('#'+listid+'-groupby').val();
        	var url = siteroot + 'controller.php';
        	
        	message.showMessage('progress', 'Loading...');

        	// get the next page with ajax
			$.post(url, {
				Ajax: 'list.page',
				SiteUniqId: siteuniqid,
				TypeId: typeid,
				PageNo: pageno,
				TotalPages: totalpages,
				DescLength: desclength,
				Length: length,
				OrderBy: orderby,
				GroupBy: groupby,
				SiteRoot: siteroot,
				Root: root
			}, function(data){
	        	message.showMessage('success', 'Content loaded successfully.');
				handleSuccess(data);

				var n_pageno = parseInt(pageno)+1;
				
				$('#'+listid+'-pageno').val(n_pageno);

				if(((n_pageno-1)*length)>totalpages){
					$('#'+listid+'-pager').hide();
				}
			});

        });
        
        // handle map
        var containers = $(this).find('.map-container');
		
        if(containers.length>0){
        	var mc = containers[0];
        	var addresses = $(this).find('em.address');
        	
        	// create a map
        	var latitude = 38.646991;
    		var longitude = -90.224967;
        	container = new VEMap(mc.id);
    		container.SetDashboardSize(VEDashboardSize.Tiny);
    		container.LoadMap(new VELatLong(latitude, longitude), 10, 'r', false);
    		
        	
        }
		
	}	
})(jQuery);