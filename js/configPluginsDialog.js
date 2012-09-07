// create the plugin namespace
if(typeof plugin == "undefined" || !plugin) {
    var plugin = {};
}

// handles the plugin configurations dialog on content.php
var configPluginsDialog = {

  init:function(){},

  // shows the slide show dialog
  show:function(id, type){

    $('#PluginUniqId').val(id);
    $('#PluginType').val(type);

    $('#configurePluginForm').load('plugins/'+type+'/config.php', function(){ // load the config file

        if(typeof plugin[type] == "undefined" || !plugin[type]){ // check to see if the plugin has been loaded

            head.js('plugins/'+type+'/js/config.js', function(){ // load the js for the config file
                configPluginsDialog.setup(id, type);
            });

        }
        else{
            configPluginsDialog.setup(id, type);
        }

    });

  },

  // setup the plugin
  setup:function(id, type){

    var pageUniqId = $('#PageUniqId').val();
    var pluginUniqId = id;

    plugin[type].init(pageUniqId, pluginUniqId); // initialize the plugin

    // show the dialog
    $('#ConfigPluginsDialog').modal('show');  // show the dialog

    if(plugin[type].showUpdate==false){
        $('#UpdatePluginConfigs').hide();        
    }
    else{
        $('#UpdatePluginConfigs').show();
    }

    $('#UpdatePluginConfigs').unbind('click');

    $('#UpdatePluginConfigs').click(function(){
        plugin[type].update(this);

        $('#ConfigPluginsDialog').modal('hide');  // hide the dialog
    });
  }
}

$(document).ready(function(){
  configPluginsDialog.init();
});