// handles JS for settings.php
var branding = {
		
	au:null,
  ias:null,
  cropWidth:0,
  cropHeight:0,
  ias:null,
  selection:null,
	
	init:function(){

    // initialize image area select
    branding.ias = $('#cropImage').imgAreaSelect({
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
		
		$('#EditImage').live("click", function(){
		
		  var url = $('#c_url').val();
      var uniqueName = $('#c_uniqueName').val();
      var width = $('#c_width').val();
      var height = $('#c_height').val();
      var x1 = $('#c_x1').val();
      var y1 = $('#c_y1').val();
      var scale = $('#c_scale').val();
      var overwrite = 1;
			
			message.showMessage('progress', 'Updating logo...');
			
			// update the co info
			$.post('branding.php', {
				Ajax: 'branding.update',
				Url: url,
        UniqueName: uniqueName,
        Width: width,
        Height: height,
        X1: x1,
        Y1: y1,
        Scale: scale,
        Overwrite: overwrite
			}, function(data){

        var fileUrl = $('#fileurl').val();
        var logo = data.Url;

        $('#logo').html('<img src="'+logo+'">');
        $('#LogoUrl').val(data.Url);
				
				message.showMessage('success', 'Your logo has been updated successfully.');

        dialog.hide('CropDialog');
				
				$('div#progress').fadeOut();
			
      }, 'json');
		
			return false;	
		});
		
		// handle image uploading
		var button = $('#Upload');

		branding.au = new Ajax_upload(button,{
			action: 'branding.php', 
			data: {
			    Ajax : 'branding.upload'
			},
			name: 'uploadedFile',
			onSubmit : function(file, ext){
				message.showMessage('progress', 'Uploading logo...');
			},
			onComplete: function(file, response){
				
				// eval to convert json data
				eval('data='+response);

        if(data.IsSuccessful=='false'){
          message.showMessage('error', data.Error);
        }
        else{
          message.showMessage('success', data.Message);
        }
				
				var fileurl = $('#fileurl').val();
				
        branding.showCropDialog(fileurl+data.LogoUrl, data.UniqueName);
			}
		});
			
	},

   // shows the crop dialog
  showCropDialog:function(url, uniqueName){

    $('#c_url').val(url);
    $('#c_uniqueName').val(uniqueName);
    
    $('#cropImage').attr('src', '');
    
    $('#cropImage') // Make in memory copy of image to avoid css issues
        .attr("src", url)
        .load(function() {
          dialog.show('CropDialog');

          $('#cropImage').width('auto');
          $('#cropImage').height('auto');
          branding.cropWidth = $('#cropImage').width();
          branding.cropHeight = $('#cropImage').height();
            
          var isConstrained  = $('#c_isConstrained').val();
          var min_width = $('#c_min_width').val();
          var min_height = $('#c_min_height').val();

          var min = 5; // determine the minimum for the slider based on the min_width and min_height
          var s1 = 5;
          var s2 = 5;

          if(min_width > 0){
            s1 = Math.ceil(100/(branding.cropWidth / min_width));
          }
          if(min_height > 0){
            s2 = Math.ceil(100/(branding.cropHeight / min_height));
          }

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

              var n_width =  Math.floor(branding.cropWidth*(scale));
              var n_height =  Math.floor(branding.cropHeight*(scale));
              var isConstrained  = $('#c_isConstrained').val();
              var min_width = $('#c_min_width').val();
              var min_height = $('#c_min_height').val();
              var width = $('#c_width').val();
              var height = $('#c_height').val();
          
              // $('#debug').text('scale='+scale+' n_width='+n_width+' min_width='+min_width+' n_height='+n_height+' min_height='+min_height);

              $('#cropImage').width(n_width);
              $('#cropImage').height(n_height);
              $('#c_scale').val(scale);
              
              if(isConstrained!='1'){
                $('#crop-dimensions').html(n_width+'px x '+n_height+'px');
              }

              dialog.recenter('CropDialog');
              
              $('#slider-value').html(ui.value);

              // reset value
              if(isConstrained=='1'){ 
                var width = $('#c_width').val();
                var height = $('#c_height').val();

                // set selection
                branding.ias.setSelection(0, 0, width, height, true);
                //branding.ias.setOptions({resizable:false,show:true});
                branding.ias.update();
              }
              else{
                // re-set selection
                branding.ias.setSelection(0, 0, n_width, n_height);
                //branding.ias.setOptions({resizable:true,show:true});
                branding.ias.update();
                $('#c_width').val(n_width);
                $('#c_height').val(n_height);
              } 
            }
          });
          
          if(isConstrained=='1'){
            var width = $('#c_width').val();
            var height = $('#c_height').val();

            $('#crop-dimensions').html(width+'px x '+height+'px');
        
            branding.ias.setSelection(0, 0, width, height, true);     
            branding.ias.setOptions({
              resizable:false,
              show:true,
              handles:false,
              minWidth:width,
              minHeight:height,
              maxWidth:width,
              maxHeight:height});
            branding.ias.update();   
            
          }
          else{
            width = this.width;   // Note: $(this).width() will not
            height = this.height;

            $('#c_width').val(width);
            $('#c_height').val(height);

            $('#crop-dimensions').html(width+'px x '+height+'px');
        
            branding.ias.setSelection(0, 0, width, height, true);     
            branding.ias.setOptions({
              resizable:true,
              show:true,
              minWidth:min_width,
              minHeight:min_height});
            branding.ias.update();   
          } 

          if(this.width < min_width || this.height < min_height){
            var errorMsg = 'The picture you uploaded is not big enough. The min width is '+min_width+'px and '+
                            'the min height is '+min_height+'px.';
            message.showMessage('error', errorMsg); 
            dialog.hide('CropDialog');
            return;
          }
          else{
            message.showMessage('success', 'Logo uploaded successully.');
          }
      });
  }
	
}

$(document).ready(function(){
	branding.init();
});

