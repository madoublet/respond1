// handles javascript for pages.php
var pages = {
	
	// page initialization function
	init:function(){
		
		$('#ShowAddDialog').live("click", function(){
			$('#Name').val('');
			$('#FriendlyId').val('');
			$('#Description').val('');
	
			$('#AddDialog').modal('show');

			return false;
		});
		
		$('a.remove').live("click", function(){
			var uid = this.id.replace('remove-', '');
	
			var name = $('#edit-'+uid).html();
			
			$('#DeleteId').val(uid);
			$('#removeName').html(name);

			$('#DeleteDialog').modal('show');
		
			return false;
		});
		
		$('#AddAction').live("click", function(){
			
			var name = jQuery.trim($('#Name').val());
			var friendlyId = jQuery.trim($('#FriendlyId').val());
			var description = jQuery.trim($('#Description').val());
			var pageTypeUniqId = $('#PageTypeUniqId').val();
			
			if(name==''){
				message.showMessage('error', 'You must add a name.');
				return;
			}
			
			if(friendlyId==''){
				message.showMessage('error', 'You must add a friendly URL.');
				return;
			}
			
		
			message.showMessage('progress', 'Adding page...');
		
			// removes a page
			$.post('pages.php', {
				Ajax: 'pages.add',
				PageTypeUniqId: pageTypeUniqId,
				Name: name,
				FriendlyId: friendlyId,
				Description: description,
			}, function(data){

				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
					
					// create html
					var html = '<div id="item-'+data.PageUniqId+'" class="listItem new">' +
							   '<a id="remove-'+data.PageUniqId+'" title="Remove '+data.Name+'" class="remove" href="#"></a>' +
							   '<h2>' +
							   '<a id="edit-'+data.PageUniqId+'" href="content.php?p='+data.PageUniqId+'" class="edit">'+data.Name+'</a>'+
							   '<span id="feature-'+data.PageUniqId+'" data-isfeatured="0" class="featured no"></span>';
				
					html += '</h2>';
					html += '<p id="description-'+data.PageUniqId+'">'+data.Description+'</p>';
					html += '<em>Just added</em>';
					html += '<span id="status-'+data.PageUniqId+'" data-isactive="0" class="status not-published"></span>';
					html += '</div>';
					
					var divs = $('#pagesList div');
					
					if(divs.length > 0){
						$(html).insertBefore(divs[0]);
					}
					else{
						$('#pagesList').html(html);
					}	
					
					$('#AddDialog').modal('hide');

					$('#item-'+data.PageUniqId).animate({
					    backgroundColor: "#ffffff"
					}, 'slow');
				}
			
			}, 'json');
			
		});
		
		
		$('#DeleteAction').live("click", function(){
			
			var pageUniqId = $('#DeleteId').val();

			$('#DeleteDialog').modal('hide');
			
			// removes a page
			$.post('pages.php', {
				Ajax: 'pages.remove',
				PageUniqId: pageUniqId
			}, function(data){

				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
				
	                $('#item-'+data.PageTypeUniqId).remove();
				}
			}, 'json');
			
		});

		// handle publish
		$('span.status').live("click", function(){
			
			var pageUniqId = $(this).attr('id').replace('status-', '');
			var isActive = $(this).attr('data-isactive');
			
			// removes a page
			$.post('pages.php', {
				Ajax: 'pages.publish',
				PageUniqId: pageUniqId,
				IsActive: isActive
			}, function(data){
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);

					$('#status-'+pageUniqId).removeClass('published');
					$('#status-'+pageUniqId).removeClass('not-published');

					if(isActive=='0'){
						$('#status-'+pageUniqId).addClass('published');
						$('#status-'+pageUniqId).attr('data-isactive', '1');
					}
					else{
						$('#status-'+pageUniqId).addClass('not-published');
						$('#status-'+pageUniqId).attr('data-isactive', '0');
					}
				}
			}, 'json');
			
		});

		// handle pager
		var pageSize = parseInt($('#PageSize').val());
		var showing = parseInt($('#showing').html());
		var total = parseInt($('#total').html());
		
		var noPages = parseInt(total/pageSize);
		var mod = total%pageSize;
		if(mod!=0)noPages++;
		
		var html='&nbsp;&nbsp;';
		
		for(var x=0; x<noPages; x++){
			var first = ((x*pageSize) + 1);
			var last = ((x+1)*pageSize);
			if(last>total)last=total;
			
			var pageTypeUniqId = $('#PageTypeUniqId').val();
	
			html += '<a href="pages.php?t=' + pageTypeUniqId + '&page=' + x + '">' + first + '-' + last + '</a>';
			
			if(x!=noPages-1)html += ',&nbsp;&nbsp;'
		}
		
		$('#pages').html(html);
	}
}


$(document).ready(function(){
	pages.init();
});
