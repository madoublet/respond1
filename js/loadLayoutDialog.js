// handles the load layout dialog on content.php
var loadLayoutDialog = {

  init:function(){
  	
    $('#selectPage li').click(function(){
      var page = $(this).attr('data-pageuniqid');
      
      $('#createPage').val(page);
      $('#selectPage li').removeClass('selected');
      $(this).addClass('selected');
    });

    $('#LoadLayout').click(function(){
      var createPage = $('#createPage').val();

      if(createPage==-1){
        message.showMessage('error', 'Please select a layout.');
        return;
      }

      // get the layout from the server
      $.post('content.php', {
        Ajax: 'content.getLayout',
        CreatePage: createPage
      }, function(data){

        $('#desc').removeClass('editor');
        $('#desc').html(data);

        // initialize editor
        $('#desc').respondEdit();

        $('#LoadLayoutDialog').modal('hide');

        $('div.editorMenu a.more').click();
      });

    });

  },

  // shows the slide show dialog
  show:function(){
    $('#selectPage').show();
    $('#createPage').val('-1');
    $('#LoadLayoutDialog').modal('show');
  }
}

$(document).ready(function(){
  loadLayoutDialog.init();
});