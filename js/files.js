// handles javascript for files.php
var files = {
	
	page:1,
	au:null,
	
	init:function(){
	
		if(document.getElementById('Upload')){
			
			// handle image uploading
			var button = $('#Upload');
	
			files.au = new Ajax_upload(button,{
				action: 'files.php', 
				data: {
				    Ajax : 'file.upload'
				},
				name: 'uploadedFile',
				onSubmit : function(file, ext){
					message.showMessage('progress', 'Uploading file...');
				},
				onComplete: function(file, response){
				
					// eval to convert json data
					eval('data='+response);
					
					 if(data.Error || data.error){
		            	message.showMessage('error', data.Error);

		            	$('#AddFileDialog').modal('hide');

			            return;
		          	}
					
					var fileUrl = $('#FileUrl').val();
					var url = '';
					var html = '';
					
					html = html + '<div id="item-'+ data.UniqueId + '" class="listItem hasImage">';
					if (data.IsImage == '1') {
						html = html + '<span class="image"><img width="75" height="75" src="' + fileUrl + '/t-' + data.UniqueName + '"></span>';
					}
					html = html + '<h2 id="name-' + data.UniqueId + '">' + '<a class="target" href="' + fileUrl + data.UniqueName + '">' + data.UniqueName + '</a>' + '</h2>';
					html = html + '<p class="fileUrls">';
					
					html = html + '</p>';
					html = html + '<p>';
					html = html + (data.Size/1024).toFixed(2) + ' MB';
					html = html + '</p>';
					html = html + '<em>Added Now</em>';
					html = html + '<a id="remove-' + data.UniqueId + '" title="Remove ' + data.FileName + '" class="remove" href="#"></a>';
					html = html + '</div>';
					
					var divs = $('#filesList div');
					
					if(divs.length > 0){
						$(html).insertBefore(divs[0]);
					}
					else{
						$('#filesList').html(html);
					}
					
					$('#AddFileDialog').modal('hide');
			
					message.showMessage('success', data.Message);
				}
			});
		}
		
		$('a.remove').live("click", function(){
			
			var fileUniqId = this.id.replace('remove-', '');
			
			var name = $('#name-'+ fileUniqId).html();

			$('#DeleteId').val(fileUniqId);
			$('#removeName').html(name);

			$('#DeleteDialog').modal('show');
			
			return false;
		});
		
		$('#ShowAddDialog').live("click", function(){
			
			$('#AddFileDialog').modal('show');

			return false;
		});
		
		
		$('a.switch').live("click", function(){
			
			var href = $(this).attr('href');
			
			var target = $(this.parentNode).find('.target')[0];
			
			$(target).attr('href', href);
			$(target).html(href.replace('view.php?f=files/', ''));
			$(this.parentNode).find('.switch').removeClass('selected');
			$(this).addClass('selected');
			
			return false;
		});
		
		$('input#DeleteFile').click(function(){
			
			message.showMessage('progress', 'Deleting file...');
			
			fileUniqId = $('#DeleteId').val();
			
			// create ajax call to remove the file
			$.post('files.php', {
				Ajax: 'file.remove',
				FileUniqId: fileUniqId
			}, function(data){
		
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					
					$('#item-' + fileUniqId).remove(); // remove file from the dom
			
					message.showMessage('success', data.Message);
				}
			
				$('#DeleteDialog').modal('hide');

			}, 'json');
          
          	return false;
      	});
		
		// handle paging
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
	
			html += '<a href="files.php?page=' + x + '">' + first + '-' + last + '</a>';
			
			if(x!=noPages-1)html += ',&nbsp;&nbsp;'
		}
		
		$('#pages').html(html);
		
	}
	
}

$(document).ready(function(){
	files.init();
});