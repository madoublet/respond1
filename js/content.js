// js for content
var content = {
  
  isPremium:false,
  count: 2500,
  cropWidth:0,
  cropHeight:0,
  ias:null,
  selection:null,
  
  init:function(){

    $('#desc').respondEdit();

    prettyPrint();

    // handle edit link
    $('#newImage').click(function(){
      var moduleId = $('#moduleId').val();
      dialog.hide('CropDialog');
      imagesDialog.show('image', moduleId);
      return false;
    });
    
    // initialize image area select
    content.ias = $('#cropImage').imgAreaSelect({
      instance: true,
      handles: true,
      onSelectEnd: function (img, selection) {
              $('#c_width').val(selection.width);
              $('#c_height').val(selection.height);
              $('#c_x1').val(selection.x1);
              $('#c_y1').val(selection.y1);
              $('#crop-dimensions').html(selection.width+'px x '+selection.height+'px');
          }
    });
    
    $('#returnToEditor').click(function(){
      $('ul#previewMenu li a').removeClass('current');
      $('#actions').show();
      $('#editorContainer').show();
      $('#previewContainer').fadeOut();
      $('#previewMessage').slideUp('fast');
      $('#actions').show();
      return false;
    });
    
    // handle link dialog
    $('#PageUrl li').click(function(){
      document.getElementById('Existing').checked = true; 
      $('#PageUrl li').removeClass('selected');
      $(this).addClass('selected');
      
      var pageId = $(this).attr('data-pageid');
      var url = $(this).attr('data-url');
      
      $('#LinkPageId').val(pageId);
      $('#LinkExistingUrl').val(url);
      
      $('#Name').val($(this).html());           
    });
    
    $('#LinkUrl').click(function(){
      document.getElementById('CustomUrl').checked = true;  
    });
    
    $('#AddLinkAction').live("click", function(){
  
      var pageId = -1;
      var url = '';
      
      if(document.getElementById('CustomUrl').checked){
        url = $('#LinkUrl').val();
        pageId = -1;
      }
      else{
        url = $('#LinkExistingUrl').val();
        url = url.toLowerCase();
        pageId = $('#PageId').val();
      }
      
      // restore selection
      global.restoreSelection(content.selection);
      
      // add link
      document.execCommand("CreateLink", false, url);

      $('#AddLinkDialog').modal('hide');
    });
    
    
    $('#UpdatePreview').click(function(){
      
      $('ul#previewMenu li a').removeClass('current');
      $('#actions').show();
      $('#editorContainer').show();
      $('#previewContainer').fadeOut();
      $('#previewMessage').slideUp('fast');
      $('#actions').show();
      
      content.update(1);
      
      return false;
    });
    
    // handle editable areas
    $('span.editable').click(function(){
      var toedit = this.id.replace('edit-', '');
      $('#'+toedit).show();
      $(this).hide();
    });
    
    $(function() {
      $("#BeginDate").datepicker();
    });
    
    $(function() {
      $("#EndDate").datepicker();
    });
    
    $('#EditImage').click(function(){

      var url = $('#c_url').val();
      var uniqueName = $('#c_uniqueName').val();
      var width = $('#c_width').val();
      var height = $('#c_height').val();
      var x1 = $('#c_x1').val();
      var y1 = $('#c_y1').val();
      var scale = $('#c_scale').val();
      var overwrite = $('#overwrite').is(':checked');
      
      if(overwrite==true){ // set to 1 or 0 (still working on this)
        overwrite = 1;
      }
      else{
        overwrite = 0;
      }

      $.post('content.php', {
        Ajax: 'content.crop',
        UniqueName: uniqueName,
        Url: url,
        Width: width,
        Height: height,
        X1: x1,
        Y1: y1,
        Scale: scale,
        Overwrite: overwrite
      }, function(data){
        
        // modify existing image
        var moduleId = $('#moduleId').val();
        var type = $('#type').val();
        var src = data.Url;
        
        if(type=='image'){
          
          if(moduleId==-1){ // create a new image
            var uniqId = 'i-'+parseInt(new Date().getTime() / 1000);
            var i_uniqId = data.UniqueName;
            var url = data.Url;
            var position = $('#ImagePosition').val();
            var href = $('#ImageHREF').val();
            var lightbox = $('#lightbox').attr('checked');
            var width = $('#ImageWidth').val();
            var height = $('#ImageHeight').val();
            var constraints = '';
            if(width!=''&&height!=''){
              if(!isNaN(width)&&!isNaN(height)){ // set constraints
                constraints = ' data-width="'+width+'" data-height="'+height+'"';
              }
            }

            var html = content.getImageHtml(position, uniqId, i_uniqId, url, href, lightbox, constraints);
            
            var editor = $('#desc');
            $(editor).respondAppend(html);
          }
          else{
            var lightbox = $('div#'+moduleId+' img').attr('data-lightbox');
            var url = $('div#'+moduleId+' img').attr('data-url');
            
            if(lightbox==undefined)lightbox=false;
            if(url==undefined)url='';
    
            $('#'+moduleId+' div.img').removeClass('hasUrl');
            
            src = src + '?d=' + parseInt(new Date().getTime() / 1000); // add date time string
            
            if(url==''){
              html = '<img id="' + data.UniqueName + '" src="'+src+'">';
              $('#'+moduleId+' div.img').html(html);
            }
            else{
              $('#'+moduleId+' div.img').addClass('hasUrl'); // add the hasUrl back on
              html = '<img id="' + data.UniqueName + '" src="'+src+'" data-url="'+url+'" data-lightbox="'+lightbox+'">';
              $('#'+moduleId+' div.img').html(html);
            }
          }
        }
        else if(type=='slideshow'){
          var editor = $('#desc');
          var src = $('#FileUrl').val() + 't-'+data.UniqueName;
          
          var html = '<span class="image"><img id="' + data.UniqueName + '" src="'+src+'" title=""><span class="caption"><input type="text" value="" placeholder="Enter caption" maxwidth="140"></span><a class="remove" href="#"></a></span>';
          
          $(editor).find('div#'+moduleId+' div').append(
            html
          );
          
          $(editor).respondHandleEvents();
        }
        else if(type=='gallery'){
          var editor = $('#desc');
          var src = $('#FileUrl').val() + 't-'+data.UniqueName;
          
          var html = '<span class="image"><img id="' + data.UniqueName + '" src="'+src+'" title=""><span class="caption"><input type="text" value="" placeholder="Enter caption" maxwidth="140"></span><a class="remove" href="#"></a></span>';
          
          $(editor).find('div#'+moduleId+' div').append(
            html
          );
          
          $(editor).respondHandleEvents();
        }
        
        dialog.hide('CropDialog');
      
      }, 'json');
      
    });
    
    $('#Update').click(function(){
      content.update(1);
      return false;
    });

    // handle update settings
    $('#UpdateSettings').click(function(){

      var moduleId = $('#ConfigModuleId').val();
      var id = jQuery.trim($('#ElementId').val());
      var cssClass = jQuery.trim($('#ElementCssClass').val());

      if(id!=''){
        $('#'+moduleId).attr('data-id', id);
      }

      $('#'+moduleId).attr('data-cssclass', cssClass);

      $('#ElementConfigDialog').modal('hide');

    });

    // handle update block settings
    $('#UpdateBlockSettings').click(function(){

      var blockId = $('#ConfigBlockId').val();
      var newId = jQuery.trim($('#BlockId').val());
      var newCssClass = jQuery.trim($('#BlockClass').val());
      var newCssClass_readable = '.block.row-fluid';

      if(newCssClass!=''){
        newCssClass_readable = newCssClass_readable + '.' + newCssClass;
      }

      if(newId!=''){
        $('#'+blockId).attr('id', newId); 
        $('#'+blockId).attr('data-cssclass', newCssClass);
        $('#'+newId).find('.blockActions span').text('#'+newId+' '+newCssClass_readable);
      }

      $('#BlockConfigDialog').modal('hide');

    });
   
    // handle publish
    $('span.status').live("click", function(){
      
      var pageUniqId = $(this).attr('id').replace('status-', '');
      var isActive = $(this).attr('data-isactive');

      $(this).removeClass('published');
      $(this).removeClass('not-published');

      if(isActive=='0'){
        $(this).addClass('published');
        $(this).find('span.status-text').html('Published');
        $(this).attr('data-isactive', '1');
      }
      else{
        $(this).addClass('not-published');
        $(this).find('span.status-text').html('Not Published');
        $(this).attr('data-isactive', '0');
      }
      
      // removes a page
      $.post('pages.php', {
        Ajax: 'pages.publish',
        PageUniqId: pageUniqId,
        IsActive: isActive
      }, function(data){
        // do nothing
      });
      
    });
    
    $('#AddFieldType').change(function(){
      
      var fieldType = $('#AddFieldType').val();
      
      if(fieldType=='select' || fieldType=='checkboxlist' || fieldType=='radiolist'){
        $('#options').show();
      }
      else{
        $('#options').hide();
      }
      
      return false;
    });
    
    $('#AddField').click(function(){

      var fieldType = $('#AddFieldType').val();
      var required = $('#AddRequired').val();
      var fieldName = $('#AddFieldName').val().trim();
      var options = $('#AddOptions').val();
      var id = fieldName.toLowerCase();
      var id = id.replace(/ /g, '-');
      var id = id.replace(/:/g, '');
      var helperText = $('#AddHelperText').val().trim();
      
      var html = '<span class="field-container">';
      html += '<div class="control-group" data-type="'+ fieldType + '"';
    
      if(required=='yes'){
        html += ' data-required="true"';
      }
      
      html += '><label for="' + id + '"';
      html += ' class="control-label">' + fieldName + '</label><div class="controls">';
      
      if(fieldType=='text'){
        html += '<input id="' + id + '" type="text">';
      }
      
      if(fieldType=='textarea'){
        html += '<textarea id="' + id + '"></textarea>\n';
      }
      
      if(fieldType=='select'){
        html += '  <select id="' + id + '">\n';
        
        var arr = options.split(',');
        
        for(x=0; x<arr.length; x++){
          html += '<option>' + jQuery.trim(arr[x]) + '</option>\n';
        }
        
        html += '</select>'
      }
      
      if(fieldType=='checkboxlist'){
        html += '<span class="list">';
        
        var arr = options.split(',');
        
        for(x=0; x<arr.length; x++){
          html += '<label><input type="checkbox" value="' + jQuery.trim(arr[x]) + '">' + jQuery.trim(arr[x]) + '</label>';
        }
        
        html += '</span>';
      }
      
      if(fieldType=='radiolist'){
        html += '<span class="list">';
        
        var arr = options.split(',');
        
        for(x=0; x<arr.length; x++){
          html += '<label><input type="radio" value="' + jQuery.trim(arr[x]) + '" name="' + id + '">' + jQuery.trim(arr[x]) + '</label>';
        }
        
        html += '</span>';
      }
      
      if (helperText != '') {
        html += '<span class="help-block">' + helperText + '</span>';
      }
      
      html += '</div></div>';

      html += '<a class="remove-field" href="#"></a><span class="marker-field" title="Field"></span>';
      html += '</span>';
      
      var formId = $('#FormId').val();
      
      var editor = $('#desc');

      if($('div#'+formId+' span.field-container:last-child').get(0)){
        $(editor).find('div#'+formId+' span.field-container:last-child').after(html);
      }
      else{
        $(editor).find('div#'+formId+' div').html(html);
      }
      
      $(editor).respondHandleEvents();
      
      $('#AddEditFieldDialog').modal('hide');
      
      return false;
    });
    
    $('#AddList').click(function(){
 
      var pageTypeUniqId = $('#listPageType').val();
      var uniqId = 'l-'+parseInt(new Date().getTime() / 1000);
      var display = $('#listDisplay').val();
      var label = $('#listPageType option:selected').text();
      label = label.toLowerCase();

      if(pageTypeUniqId==-1){
        message.showMessage('error', 'You must select a type of list.');
        return;
      }
      var editor = $('#desc');
      
      var html = '<div id="'+uniqId+'" data-display="'+display+'" data-type="'+pageTypeUniqId+'" class="list"'
      html += ' data-length="'+$('#listLength').val()+'"';
      html += ' data-orderby="'+$('#listOrderBy').val()+'"';
      html += ' data-pageresults="'+$('#listPageResults').is(':checked')+'"';
      html += ' data-desclength="'+$('#listDescLength').val()+'"';
      html += ' data-label="'+label+'"><div>List '+label+'</div><span class="marker" title="Module"></span><a class="remove" href="#"></a><a class="config-list" href="#"></a></div>'

      $(editor).respondAppend(
        html
      );
      
      $(editor).respondHandleEvents();

      $('#AddEditListDialog').modal('hide');
    });
    
    
    $('#UpdateList').click(function(){
      
      var pageTypeUniqId = $('#ListPageTypeUniqId').val();
      var moduleId = $('#ListModuleId').val();
      
      var editor = $('#desc');

      var desclength = parseInt($('#listDescLength').val());
      
      $('div#'+moduleId+'.list').attr('data-display', $('#listDisplay').val());
      $('div#'+moduleId+'.list').attr('data-length', $('#listLength').val());
      $('div#'+moduleId+'.list').attr('data-orderby', $('#listOrderBy').val());
      $('div#'+moduleId+'.list').attr('data-pageresults', $('#listPageResults').val());
      $('div#'+moduleId+'.list').attr('data-desclength', desclength);
     
      $('#AddEditListDialog').modal('hide');
    });
    
    $('#AddSlideShow').click(function(){
      var editor = $('#desc');
      var moduleId = $('#moduleId').val();
      var width = $('#slideShowWidth').val();
      var height = $('#slideShowHeight').val();
      
      var html = '<div id="' + moduleId + '" class="slideshow" data-width="'+width+'" data-height="'+height+'"><div>' +
            '<button type="button" class="addImage"></button>' +
            '</div><span class="marker" title="Module"></span><a class="remove" href="#"></a>' +
            '<em class="size">'+
            width + 'px x ' + height + 'px' +
        '</em>'+
            '</div>';
      
      $(editor).respondAppend(
        html
      );
      
      $('#AddSlideShowDialog').modal('hide');
    });
    
  },
  
  // gets the image HTML
  getImageHtml:function(position, uniqId, i_uniqId, url, href, lightbox, constraints){
    if(position=='left'){
      if(href==''){
        html = '<div id="'+uniqId+'" class="i left"'+constraints+' data-id="'+uniqId+'" data-cssclass="">'+
        '<div class="img"><img id="' + i_uniqId + '" src="'+url+'"></div><div class="content" contentEditable="true">&nbsp;</div><span class="marker"></span><a class="remove" href="#"></a><a class="config" href="#"></a>'+
        '</div>';
      }
      else{
        html = '<div id="'+uniqId+'" class="i left"'+constraints+' data-id="'+uniqId+'" data-cssclass="">'+
        '<div class="img hasUrl"><img id="' + i_uniqId + '" src="'+url+'" data-url="'+href+'" data-lightbox="'+lightbox+'"></div><div class="content" contentEditable="true">&nbsp;</div><span class="marker"></span><a class="remove" href="#"></a><a class="config" href="#"></a>'+
        '</div>';
      }
    }
    else if(position=='right'){
      if(href==''){
        html = '<div id="'+uniqId+'" class="i right"'+constraints+' data-id="'+uniqId+'" data-cssclass="">'+
        '<div class="content" contentEditable="true">&nbsp;</div><div class="img"><img id="' + i_uniqId + '" src="'+url+'"></div><span class="marker"></span><a class="remove" href="#"></a><a class="config" href="#"></a>'+
        '</div>';
      }
      else{
        html = '<div id="'+uniqId+'" class="i right"'+constraints+' data-id="'+uniqId+'" data-cssclass="">'+
        '<div class="content" contentEditable="true">&nbsp;</div><div class="img hasUrl"><img id="' + i_uniqId + '" src="'+url+'" data-url="'+href+'" data-lightbox="'+lightbox+'"></div><span class="marker"></span><a class="remove" href="#"></a><a class="config" href="#"></a>'+
        '</div>';
      }
    }
    else{ // for no text
      if(href==''){
        html = '<div id="'+uniqId+'" class="i"'+constraints+' data-id="'+uniqId+'" data-cssclass="">'+
        '<div class="img"><img id="' + i_uniqId + '" src="'+url+'"></div><span class="marker"></span><a class="remove" href="#"></a><a class="config" href="#"></a>'+
        '</div>'; 
      }
      else{
        html = '<div id="'+uniqId+'" class="i"'+constraints+' data-id="'+uniqId+'" data-cssclass="">'+
        '<div class="img hasUrl"><img id="' + i_uniqId + '" src="'+url+'" data-url="'+href+'" data-lightbox="'+lightbox+'"></div><span class="marker"></span><a class="remove" href="#"></a><a class="config" href="#"></a>'+
        '</div>'; 
      }
    }
    
    return html;
  },
  
  // loads the categories
  loadCategories:function(pageTypeUniqId, categoryUniqId){
    var ajax = 'content.getCategories';

    $.post('content.php', {
      Ajax: ajax,
      PageTypeUniqId: pageTypeUniqId
    }, function(data){
    
      $('#contentCategory').find('option').remove();
      $('#contentCategory').append('<option value="None">None</option>');
      
      for (x in data.Categories){
        if(categoryUniqId==x){
          $('#contentCategory').append('<option value="' + x + '" selected>' + data.Categories[x] + '</option>');
        }
        else{
          $('#contentCategory').append('<option value="' + x + '">' + data.Categories[x] + '</option>');
        }
      }
      
    }, 'json'); 
  
    return false;
  },
  
  // updates the content
  update:function(isActive){
    var pageUniqId = $('#PageUniqId').val();
    var typeS = $('#TypeS').val();
    
    
    // get desc and content
    var domain = $('#Domain').val();
    var content = $('#desc').respondHtml();
    var imageId = $('#desc').respondGetPrimaryImage();
    var location = $('#desc').respondGetLocation();

    var pageTypeUniqId = $('#PageTypeUniqId').val();
    
    var successText = 'You have succesfully updated the ' + typeS.toLowerCase() + '.';
    
    message.showMessage('progress', 'Updating '+typeS.toLowerCase()+'...');

    $.post('content.php', {
      Ajax: 'content.update',
      PageUniqId: pageUniqId,
      Content: content,
      PageTypeUniqId: pageTypeUniqId,
      ImageId: imageId,
      Location: location
    }, function(data){  
      if(data.IsSuccessful=='false'){
        message.showMessage('error', data.Error);
      }
      else{
        message.showMessage('success', successText);
      }
    }, 'json');
    
  },
  
  // shows the addField dialog
  showAddFieldDialog:function(formId){
    
    $('#FormId').val(formId);
    $('#options').hide();
    $('#AddFieldName').val('');
    $('#AddFieldType').val('');
    $('#AddOptions').val('');
    $('#AddHelperText').val('');
  
    $('#AddEditFieldDialog').modal('show');
  },
  
  // shows the link dialog
  showLinkDialog:function(){
    content.selection = global.saveSelection();

    $('#AddLinkDialog').modal('show');

    $('#LinkUrl').val('');
    $('#PageUrl li').removeClass('selected');
    $('#Existing').attr('checked','checked');
  },
  
  // shows the list dialog
  showListDialog:function(mode, id){
    $('#ListModuleId').val(id); // will be -1 if new
    
    if(mode=='add'){
      $('#AddEditListDialog h3').html('Add List');
      $('#AddList').show();
      $('#UpdateList').hide();
      $('#showSelectOptions').show();
      $('#selectList li').removeClass('selected');
      $('#showCategoryOptions').hide();
      $('#showCategoryPageTypes').show();
      $('#ListPageTypeUniqId').val(-1);      

      $('#AddEditListDialog').modal('show');

      $('#listLength').val('10'); 
      $('#listOrderBy').val('Name');
      $('#listPageResults').val('false');
      $('#listDescLength').val(250);
      $('#listFeaturedOnly').val(0);
    }
    else{
      $('#AddEditListDialog h3').html('Edit List');
      $('#AddList').hide();
      $('#UpdateList').show();

      // get reference to list
      var node = $('div#'+id+'.list');
      var display = $(node).attr('data-display');
      var type = $(node).attr('data-type');
      var label = $(node).attr('data-label');
      var length = $(node).attr('data-length');
      var orderby = $(node).attr('data-orderby');
      var pageresults = $(node).attr('data-pageresults');
      var desclength = $(node).attr('data-desclength');

      if(desclength==undefined){
        desclength = 250;
      }

      // set current values
      $('#listDisplay').val(display); 
      $('#listLength').val(length); 
      $('#listOrderBy').val(orderby); 
      $('#listPageResults').val(pageresults);
      $('#listDescLength').val(desclength);
      
      $('#listPageTypeBlock').hide();
      $('#ListPageTypeUniqId').val(type);

      $('#AddEditListDialog').modal('show');
    }
  },
  
  // shows the edit images dialog
  showCropDialog:function(url, uniqueName, moduleId, type, isConstrained){

    $('#c_url').val(url);
    $('#c_uniqueName').val(uniqueName);
    $('#moduleId').val(moduleId);
    $('#c_type').val(type);
    $('#c_isConstrained').val(isConstrained);

    if(type=='slideshow'){
      $('#overwrite').prop("checked", true);
    }
    else{
      $('#overwrite').prop("checked", false);
    }
    
    $('#cropImage').attr('src', '');
    
    $('#cropImage') // Make in memory copy of image to avoid css issues
        .attr("src", url)
        .load(function() {
          dialog.show('CropDialog');
          $('#cropImage').width('auto');
          $('#cropImage').height('auto');
          content.cropWidth = $('#cropImage').width();
          content.cropHeight = $('#cropImage').height();
          
          if(type=='slideshow'){
            var editor = $('#desc');
            var slideshow = $(editor).find('div#'+moduleId);
            
            width = slideshow.attr('data-width');   // Note: $(this).width() will not
            height = slideshow.attr('data-height');

            // #debug alert('crop width='+width+' and crop height='+height);

            $('#crop-dimensions').html(width+'px x '+height+'px');
            $('#c_width').val(width);
            $('#c_height').val(height);
            $('#c_min_width').val(width);
            $('#c_min_height').val(height);
            $('#c_x1').val(0);
            $('#c_y1').val(0);
            $('#c_scale').val(1);

            $('#cropMessage').html('Drag the window to choose your selection, then click save.');

            // set selection
            content.ias.setSelection(0, 0, width, height, true);
            content.ias.setOptions({resizable:false,show:true});
            content.ias.update();
          }
          else if(type=='gallery'){
            var editor = $('#desc');
            var gallery = $(editor).find('div#'+moduleId);
            
            width = this.width;   // Note: $(this).width() will not
            height = this.height;
            c_min_width = 25;
            c_min_height = 25;
            $('#c_width').val(width);
            $('#c_height').val(height);
            $('#c_min_width').val(c_min_width);
            $('#c_min_height').val(c_min_height);
            $('#c_x1').val(0);
            $('#c_y1').val(0);
             $('#c_scale').val(1);

            $('#cropMessage').html('Drag the window to choose your selection, then click save.');

            // set selection
            content.ias.setSelection(0, 0, width, height, true);
            content.ias.setOptions({resizable:true,show:true});
            content.ias.update();
          }
          else{
            width = this.width;   // Note: $(this).width() will not
            height = this.height;
            c_resizable = true;
            c_min_width = 0;
            c_min_height = 0;
              
            if(moduleId==-1){ // for new images, check for constraints
              var c_width = $('#ImageWidth').val();
              var c_height = $('#ImageHeight').val();
                
              if(c_width!=undefined&&c_height!=undefined){
                if(!isNaN(width)&&!isNaN(height)){
                  width = c_width;
                  height = c_height;
                  c_min_width = c_width;
                  c_min_height = c_height;
                  c_resizable = false;
                  $('#c_isConstrained').val('true');
                }
              }
            }
            else{
              var c_width = $('#'+moduleId).attr('data-width');
              var c_height = $('#'+moduleId).attr('data-height');
              
              if(c_width!=undefined&&c_height!=undefined){
                if(!isNaN(width)&&!isNaN(height)){
                  width = c_width;
                  height = c_height;
                  c_min_width = c_width;
                  c_min_height = c_height;
                  c_resizable = false;
                  $('#c_isConstrained').val('true');
                }
              }
              }
            
              
              $('#c_width').val(width);
              $('#c_height').val(height);
              $('#c_min_width').val(c_min_width);
              $('#c_min_height').val(c_min_height);
              $('#c_x1').val(0);
              $('#c_y1').val(0);
              $('#c_scale').val(1);

              $('#cropMessage').html('Crop the image using the handles at each corner, then click save.');
              
              $('#crop-dimensions').html(width+'px x '+height+'px');
      
              // set selection
              content.ias.setSelection(0, 0, width, height);
              content.ias.setOptions({handles:c_resizable,resizable:c_resizable,show:true});
              content.ias.update();
          }

          var isConstrained  = $('#c_isConstrained').val();
          var min_width = $('#c_min_width').val();
          var min_height = $('#c_min_height').val();

          var min = 5; // determine the minimum for the slider based on the min_width and min_height
          var s1 = 5;
          var s2 = 5;

          if(min_width > 0){
            s1 = Math.ceil(100/(content.cropWidth / min_width));
          }
          if(min_height > 0){
            s2 = Math.ceil(100/(content.cropHeight / min_height));
          }

          // #debug alert('s1='+s1+', s2='+s2+', cropWidth='+content.cropWidth+', min_width='+min_width+', cropHeight='+content.cropHeight+', min_height='+min_height);

          if(s1>s2){ // determine the smallest scale
            min = s1;
          }
          else{
            min = s2;
          }   

          // create slider
          $('#slider').slider({
            value:100,
            min: min,
            max: 100,
            step: 1,
            slide: function(event, ui){
            
              var scale = ui.value/100;
              
              var n_width =  Math.floor(content.cropWidth*(scale));
              var n_height =  Math.floor(content.cropHeight*(scale));
              var isConstrained  = $('#c_isConstrained').val();
              var min_width = $('#c_min_width').val();
              var min_height = $('#c_min_height').val();
              var width = $('#c_width').val();
              var height = $('#c_height').val();
      
              $('#cropImage').width(n_width);
              $('#cropImage').height(n_height);
              $('#c_scale').val(scale);
                
              if(isConstrained!='true'){
                $('#crop-dimensions').html(n_width+'px x '+n_height+'px');
              }

              dialog.recenter('CropDialog');
              
              $('#slider-value').html(ui.value);
              
              // reset value
              if(isConstrained=='true'){ 
                // set selection
                content.ias.setSelection(0, 0, width, height, true);
                content.ias.setOptions({resizable:false,show:true});
                content.ias.update();
              }
              else{
                // re-set selection
                content.ias.setSelection(0, 0, n_width, n_height);
                content.ias.setOptions({resizable:true,show:true});
                content.ias.update();
                $('#c_width').val(n_width);
                $('#c_height').val(n_height);
              }
                
            }
          });
          
      });
  },
  
  // shows the slide show dialog
  showSlideShowDialog:function(slideshowId){
    $('#slideshowId').val(slideshowId);
    $('#slideShowWidth').val('1024');
    $('#slideShowHeight').val('768');

    $('#AddSlideShowDialog').modal('show');
  },

  // shows a preview pane
  preview:function(){

      var pageUniqId = $('#PageUniqId').val();
      var draft = $('#desc').respondHtml();
      
      $.post('content.php', {
        Ajax: 'content.saveDraft',
        PageUniqId: pageUniqId,
        Draft: draft
      }, function(data){
        
        var url = 'preview.php?p='+pageUniqId+'&m=false';

        $('#preview').attr('src', url);
        $('#editorContainer').hide();
        $('#actions').hide();
        $('#previewContainer').fadeIn();
        $('#previewMessage').slideDown('fast');
        
      });
      
      return false;
  }

}

$(document).ready(function(){
  content.init();
});
