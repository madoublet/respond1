// handles the images dialog on content.php
var imagesDialog = {

  au:null,

  init:function(){
  	$('#showImageAndPosition a').click(function(){
      $('#viewImageAndPosition').show();
      $('#viewImageLink').hide();
      $('#viewImageConstraints').hide();
      $('#imageTabs li').removeClass('active');
      $(this).parent().addClass('active');
      return false;
    });

    $('#showImageLink a').click(function(){
      $('#viewImageAndPosition').hide();
      $('#viewImageLink').show();
      $('#viewImageConstraints').hide();
      $('#imageTabs li').removeClass('active');
      $(this).parent().addClass('active');

      return false;
    });

    $('#showImageConstraints a').click(function(){
      $('#viewImageAndPosition').hide();
      $('#viewImageLink').hide();
      $('#viewImageConstraints').show();
      $('#imageTabs li').removeClass('active');
      $(this).parent().addClass('active');
      return false;
    });

    $('#pos1 li').live("click", function(){
      $(this.parentNode).find('li').removeClass('selected');
      $(this).addClass('selected');
      $('#ImagePosition').val(this.id);
    });

    $('#addImageList li').live("click", function(){
      
      var uniqId = $(this).find('.uniqueName').val();
      
      url = $('#FileUrl').val() + uniqId;
      
      $('#ExistingUrl').val(url);
      $('#ExistingUniqId').val(uniqId);
      $('#addImageList li').removeClass('selected');
      $(this).addClass('selected');
      $(this).find('.switch').removeClass('selected');
      $(this).find('a.switch:first-child').addClass('selected');
      $('#ExistingImage').attr('checked', 'checked');
    
    
      return false;
    });
    
    if(document.getElementById('Upload')){
      
      // handle image uploading
      var button = $('#Upload');
  
      imagesDialog.au = new Ajax_upload(button,{
        action: 'content.php', 
        data: {
          Ajax : 'content.upload',
          ResizeImage: 0
        },
        name: 'uploadedFile',
        onSubmit : function(file, ext){
          message.showMessage('progress', 'Uploading...');
        },
        onComplete: function(file, response){
          
          // eval to convert json data
          eval('data='+response);
          
          if(data.Error || data.error){
            message.showMessage('error', data.Error);

            return;
          }
          
          var fileUrl = $('#FileUrl').val();
          var url = '';
          var html = '';
          
          if(data.IsImage==1){
            html = '<img src="' + fileUrl + 't-' + data.UniqueName + '">';
            url = fileUrl + data.UniqueName;
            $('#UploadedSizes').hide();
          }
          else{
            url = fileUrl + data.UniqueNamae;
            $('#newLabel').html(data.UniqueName);
          }
  
          $('#Upload').hide();
          $('#UploadedImage').show();
          $('#UploadedImage').html(html);
          $('#UploadUrl').val(url);
          $('#UploadUniqId').val(data.UniqueName);
          $('#NewImage').attr('checked', 'checked');
          
          message.showMessage('success', 'Image uploaded successully.');
        }
      });
    }

    $('#AddImageAction').click(function(){
      
      var editor = $('#desc');
      var type = $('#type').val();
      var moduleId = $('#moduleId').val();

      var url = '';
      var i_uniqId = '';
      
      if(document.getElementById('NewImage').checked){
        url = $('#UploadUrl').val();
        i_uniqId = $('#UploadUniqId').val();
        if(i_uniqId==-1){
          message.showMessage('error', 'Please upload a file to continue.');
          return false;
        }
      }
      else{
        url = $('#ExistingUrl').val();
        i_uniqId = $('#ExistingUniqId').val();
        if(i_uniqId==-1){
          message.showMessage('error', 'Please select a file to continue.');
          return false;
        }
      }
      
      if(type=='image'){
        var uniqId = 'i-'+parseInt(new Date().getTime() / 1000);
        var position = $('#ImagePosition').val();
        var href = $('#ImageHREF').val();
        var lightbox = $('#lightbox').attr('checked');
        var width = jQuery.trim($('#ImageWidth').val());
        width = parseInt(width);
        var height = jQuery.trim($('#ImageHeight').val());
        height = parseInt(height);
        var constraints = '';
        
        if(isNaN(width)==false&&isNaN(height)==false){
          content.showCropDialog(url, i_uniqId, moduleId, type, true); // #wip
          return;
        }
        
        var html = '';
        
        if(moduleId==-1){  // for new images
          
          var html = content.getImageHtml(position, uniqId, i_uniqId, url, href, lightbox, constraints);

          $(editor).respondAppend(
            html
          );
        }
        else{  // for existing images
          $('#'+moduleId+' div.img').removeClass('hasUrl');
          
          if(width!=''&&height!=''){
            if(isNaN(width)||isNaN(height)){
              // do not set constraints
            }
            else{
              $('#'+moduleId).attr('data-width', width);
              $('#'+moduleId).attr('data-height', height);
            }
          }
          
          if(href==''){
            html = '<img id="' + i_uniqId + '" src="'+url+'">';
            $('#'+moduleId+' div.img').html(html);
          }
          else{
            $('#'+moduleId+' div.img').addClass('hasUrl'); // add the hasUrl back on
            html = '<img id="' + i_uniqId + '" src="'+url+'" data-url="'+href+'" data-lightbox="'+lightbox+'">';
            $('#'+moduleId+' div.img').html(html);
          }
        }
      }
      else if(type=='slideshow'){
        content.showCropDialog(url, i_uniqId, moduleId, type, true);
      }
      else if(type=='gallery'){
        content.showCropDialog(url, i_uniqId, moduleId, type, false);
      }
      else if(type=='file'){
        $(editor).respondAppend(
          '<div class="file"><div><em>'+i_uniqId+'</em><input type="text" value="" spellcheck="false" maxlength="256" placeholder="Description for the file"></div><span class="marker" title="Module"></span><a class="remove" href="#"></a></div>'
        );
      }
      
      $('#AddImageDialog').modal('hide');
    });

  },

  // shows the images dialog
  show:function(type, moduleId){
    
    var uniqueName = '';
    var size = '';
    var width = 0;
    var height = 0;

    // reset tabs
    $('#viewImageAndPosition').show();
    $('#viewImageLink').hide();
    $('#viewImageConstraints').hide();
    $('#imageTabs li').removeClass('selected');
    $('#showImageAndPosition').addClass('selected');

    // show default
    $('#viewImageAndPosition').show();
    $('#viewImageLink').hide();
    $('#viewImageConstraints').hide();
    
    $('#type').val(type);
    $('#moduleId').val(moduleId);

    $('#NewImage').attr('checked', 'checked');
    $('#UploadUrl').val('');
    $('#ExistingUrl').val('');
    $('#ImageWidth').val('');
    $('#ImageHeight').val('');
    $('#NewImage').attr('checked', 'checked');
    $('#Upload').show();
    $('#UploadedImage').hide();
    $('#UploadedSizes').hide();
    
    if(type=='file'){
      $('#imageTabs').hide();
      $('#selectPosition').hide();
      $('#AddImageDialog h3').html('Add File');
      $('#Upload').val('Upload File');
      $('#newLabel').html('New File');
      $('#AddImageAction').val('Add File');
      $('#existingLabel').html('Existing File');
    }
    else if(type=='slideshow'){
      $('#imageTabs').hide();
      $('#selectPosition').hide();
      width = $('div#'+moduleId).attr('data-width');
      height = $('div#'+moduleId).attr('data-height');
      $('#AddImageAction').val('Add Image'); 
    }
    else if(type=='gallery'){
      $('#imageTabs').hide();
      $('#selectPosition').hide();
      $('#AddImageAction').val('Add Image'); 
    }
    else{
      $('#pos1').find('li').removeClass('selected');
      $('#pos1 li#left').addClass('selected');
      $('#ImageHREF').val('');
      $('#selectPosition').show();
      $('#imageTabs').show();
      
      if(moduleId==-1){
        $('#AddImageDialog h3').html('Add Image');
        $('#AddImageAction').val('Add Image');
        $('#constrain').show();
      }
      else{
        $('#ExistingImage').attr('checked', 'checked');
        var href = $('div#'+moduleId+' img').attr('data-url');
        var lightbox = $('div#'+moduleId+' img').attr('data-lightbox');
        var width = $('div#'+moduleId).attr('data-width');
        var height = $('div#'+moduleId).attr('data-height');
        
        $('#ImageWidth').val(width);
        $('#ImageHeight').val(height);
        var id = $('div#'+moduleId+' img').attr('id');
        var src = $('div#'+moduleId+' img').attr('src');
        uniqueName = id; // set the current id
        $('#ExistingUrl').val(src);
        $('#ExistingUniqId').val(id);
        
        // get the size of the current image
        if(src.indexOf('/t-')!=-1){
          size = 't';
        }
        else{
          size = 'o';
        }

        if(href!=undefined){
          $('#ImageHREF').val(href);
        }
        
        if(lightbox!=undefined){
          if(lightbox=='true'){
            $('#lightbox').attr('checked', true);
          }
        }
        
        var pos = 'none';
        
        if($('div#'+moduleId).hasClass('left'))pos = 'left';
        if($('div#'+moduleId).hasClass('right'))pos = 'right';
        
        $('ul#pos1 li').removeClass('selected');
        $('ul#pos1 li#'+pos).addClass('selected');
        $('#selectPosition').hide(); // cannot switch positions right now
        //$('#constrain').hide(); // cannot constrain existing images
        $('#AddImageDialog h3').html('Change Image');
        $('#AddImageAction').val('Change Image');
      }
      
      $('#Upload').val('Upload Image');
      $('#newLabel').html('New Image');
      $('#existingLabel').html('Existing Image');
    }
    
    $('#AddImageDialog').modal('show');

    // # debug alert(type);
    
    // gets existing images
    $.post('content.php', {
      Ajax: 'content.getExisting',
      Type: type,
      UniqueName: uniqueName,
      Size: size,
      Width: width,
      Height: height
    }, function(data){
      $('#addImageList').html(data);
    });
  }
}

$(document).ready(function(){
  imagesDialog.init();
});