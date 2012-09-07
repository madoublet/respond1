// handles javascript for menuItems.php
var menuItems = {
	
	// page initialization function
	init:function(){
	
		$('div.list').sortable({handle:'span.hook', placeholder: 'placeholder', opacity:'0.6'});
		
		$('#Save').click(function(){
			var menuItemUniqIds = [];
			var names = [];
			var urls = [];
			var cssClasses = [];
			var pageIds = [];
			var type = $('#type').val();

			var items = $('div.listItem');
			
			for(var x=0; x<items.length; x++){
				var menuItemUniqId = items[x].id.replace('item-', '');
				menuItemUniqIds[x] = menuItemUniqId;
				names[x] = $('#name-'+menuItemUniqId).html();
				urls[x] = $('#link-'+menuItemUniqId).val();
				cssClasses[x] = $('#cssclass-'+menuItemUniqId).val();
				pageIds[x] = $('#pageId-'+menuItemUniqId).val();
			}
		
	  		message.showMessage('progress', 'Saving menu...');
			
			$.post('menu.php', {
				Ajax: 'menu.save',
				MenuItemUniqIds: menuItemUniqIds,
				Names: names,
				CssClasses: cssClasses,
				Type: type,
				Urls: urls,
				PageIds: pageIds
			}, function(data){
				
				if(data.IsSuccessful=='false'){
					message.showMessage('error', data.Error);
				}
				else{
					message.showMessage('success', data.Message);
				}
				
			}, 'json');
			
			return false;	
		});
	
		$('a.remove').live("click", function(){
			var uid = this.id.replace('remove-', '');
			var name = $('#name-'+uid).html();
			
			$('#item-'+uid).remove();
		
			return false;
		});
		
		$('#ShowAddDialog').live("click", function(){

			$('#MenuItemUniqId').val('-1');
			$('#AddEditTitle').html('Add Menu Item');
			$('#AddEditAction').val('Add Menu Item');
			$('#DialogMode').val('add');
			
			$('#editUrl').hide();
			$('#addUrl').show();
			$('#Name').val('');	
			$('#selectPage li').removeClass('selected');
			
			$('#CssClass').val('');
			$('#Url').val('');	
			$('#ExistingUrl').val('');
			$('#PageId').val('-1');
			$('#Existing').attr('checked','checked');

			$('#AddEditDialog').modal('show');
			
		});
		
		$('a.edit').live("click", function(){
			
			$('#AddEditTitle').html('Edit Menu Item');
			$('#AddEditAction').val('Update Menu Item');
			$('#DialogMode').val('edit');
			
			var menuItemUniqId = this.id.replace('name-', '');
			
			var name = $('#name-'+menuItemUniqId).html();
			var url = $('#link-'+menuItemUniqId).val();
			var cssClass = $('#cssclass-'+menuItemUniqId).val();
			var pageId = parseInt($('#pageId-'+menuItemUniqId).val());
			
			if(pageId==-1){
				$('#editUrl').show();
			}
			else{
				$('#editUrl').hide();
			}
			$('#addUrl').hide();
			
			$('#MenuItemUniqId').val(menuItemUniqId);
			$('#AddEditTitle').html('Edit Menu Item');
			$('#AddEditAction').val('Update Menu Item');	
			$('#Name').val(name);	
			$('#PageId').val(pageId);
			$('#EditUrl').val(url);
			$('#CssClass').val(cssClass);

			$('#AddEditDialog').modal('show');
			
			return false;
			
		});
		
		$('#selectPage li').click(function(){
			document.getElementById('Existing').checked = true;	
			$('#selectPage li').removeClass('selected');
			$(this).addClass('selected');
			
			var pageId = $(this).attr('data-pageid');
			var url = $(this).attr('data-url');
			
			$('#PageId').val(pageId);
			$('#ExistingUrl').val(url);
			
			$('#Name').val($(this).html());						
		});
		
		$('#Url').click(function(){
			document.getElementById('CustomUrl').checked = true;	
		});
		
		
		$('#AddEditAction').live("click", function(){
			
			var menuItemUniqId = $('#MenuItemUniqId').val();
			var name = $('#Name').val();
			var cssClass = $('#CssClass').val();
			
			if(name==''){
				message.showMessage('error', 'You must add a name');
				return;
			}
			
			var pageId = '';
			
			var mode = $('#DialogMode').val();
			var url = '';
			
			if(mode=='edit'){
				url = $('#EditUrl').val();
				pageId = $('#PageId').val();
			}
			else{
				if(document.getElementById('CustomUrl').checked){
					url = $('#Url').val();
					pageId = -1;
				}
				else{
					url = $('#ExistingUrl').val();
					url = url.toLowerCase();
					pageId = $('#PageId').val();
				}
			}
			
			var mode = 'edit';
			
			if(menuItemUniqId==-1){
				mode = 'add';
			}
			
			if(mode == 'add'){
				var uniqId = +parseInt(new Date().getTime() / 1000);
				var menuItemUniqId = 'new-'+uniqId;
				var html = '<div id="item-' + menuItemUniqId + '" class="listItem sortable">' +
					'<span class="hook"></span>' +
					'<a id="remove-' + menuItemUniqId + '" class="remove" href="#"></a>' +
					'<input id="pageId-' + menuItemUniqId + '" value="' + pageId + '" type="hidden">' +
					'<h2><a id="name-' + menuItemUniqId + '" href="#" class="edit">' + name + '</a></h2>' +
					'<input id="link-' + menuItemUniqId + '" value="' + url + '" type="hidden">' +
					'<input id="cssclass-' + menuItemUniqId + '" value="' + cssClass + '" type="hidden">' +
					'</div>';
		
				var divs = $('#menuItemsList div');
				
				if(divs.length > 0){
					$(html).insertAfter(divs[(divs.length-1)]);
				}
				else{
					$('#menuItemsList').html(html);
				}
			}
			else{
				$('#name-'+menuItemUniqId).html(name);
				$('#link-'+menuItemUniqId).val(url);
				$('#cssclass-'+menuItemUniqId).val(cssClass);
			}

			$('#AddEditDialog').modal('hide');
		});
			


	}
	
}


$(document).ready(function(){
	menuItems.init();
});