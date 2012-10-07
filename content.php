<?php 
  include 'global.php'; // import php files
  include 'actions/Content.php';
  
  $authUser = new AuthUser(); // get auth user
  $authUser->Authenticate('All');
  
  $p = new Content($authUser); // setup controller
  
  $currpage = 'site';
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
  
<title><?php print $p->Title; ?>&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/content.css" rel="stylesheet">
<link type="text/css" href="css/editor.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/dialog.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">
<link type="text/css" href="css/prettify.css" rel="stylesheet">
<link type="text/css" href="css/imgareaselect/imgareaselect-default.css" rel="stylesheet">
<link type="text/css" href="css/cupertino/jquery-ui-1.8.1.custom.css" rel="stylesheet">

<!-- include js -->
<script src="js/head.min.js"></script>

</head>

<body>

<!-- begin global messages -->
<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>


<input type="hidden" name="_submit_check" value="1"/>

<input type="hidden" id="PageUniqId" value="<?php print $p->PageUniqId; ?>">
<input type="hidden" id="PageTypeUniqId" value="<?php print $p->PageTypeUniqId; ?>">
<input type="hidden" id="FileUrl" value="<?php print $authUser->FileUrl; ?>">
<input type="hidden" id="TypeS" value="<?php print $p->TypeS; ?>">
<input type="hidden" id="Domain" value="<?php print $p->SiteUrl; ?>">

<?php include 'modules/menu.php'; ?>

<div id="editorContainer">
  <div id="desc" class="container-fluid">
  <?php   
    if($p->Content==''){
      print '<div class="block row-fluid"><div class="col span12"><h1>'.strip_tags(html_entity_decode($p->Name)).'</h1><p>'.strip_tags(html_entity_decode($p->Description)).'</p></div></div>';
    } 
    else{
      print $p->Content;
    }
  ?>  
  </div>
</div>

<div id="previewMessage">
  <span>You are previewing this page, click save to publish it.</span> 
  <input id="UpdatePreview" class="btn" type="button" value="Save"> <span class="or">or
  <a id="returnToEditor" href="#">Return to Editor</a></span>
</div>

<div id="previewContainer">
  <iframe id="preview" src=""></iframe>
</div>  
      
<input id="IsActive" type="hidden" value="<?php print $p->Page->IsActive; ?>">

<div id="actions" class="container-fluid">
  <button id="Update" class="btn btn-primary" type="button">Save</button>
<?php if($p->Page->PageTypeId != -1){ ?>
  <a href="pages.php?t=<?php print $p->PageType->PageTypeUniqId; ?>" class="btn">Cancel</a>
<?php }else{ ?>
  <a href="pages.php" class="btn">Cancel</a>
<?php } ?>
</div>
  
<div id="overlay"></div>  

<div id="CropDialog" class="immersive">
  <div>
    
    <div class="editImgContainer">
    <img id="cropImage" src="">
    <span id="changeImage">
      <label><input id="overwrite" type="checkbox"> Overwrite existing image</label>
    </span>
    </div>
    
    <input id="c_uniqueName" value="" type="hidden">
    <input id="c_type" value="" type="hidden">
    <input id="c_isConstrained" value="" type="hidden">
    <input id="c_url" value="" type="hidden">
    <input id="c_width" v`alue="0" type="hidden">
    <input id="c_height" value="0" type="hidden">
    <input id="c_min_width" value="25" type="hidden">
    <input id="c_min_height" value="25" type="hidden">
    <input id="c_x1" value="0" type="hidden">
    <input id="c_y1" value="0" type="hidden">
    <input id="c_scale" value="1" type="hidden">
  </div>
</div>

<div id="CropDialog-Actions" class="immersive-actions">
  <div>
    <span id="cropMessage">Crop the image using the handles at each corner, then click save.</span> 
    <input id="EditImage" class="btn" type="button" value="Save"> <span>
    <a id="newImage" href="#">Change Image</a> or
    <a href="#" class="close-dialog">Return to Editor</a></span>
    
    <div id="slider-container">
      <div id="slider"></div>
      <div id="slider-display"><span id="slider-value">100</span>%</div>
    </div>
    
    <div id="crop-dimensions">500px x 200px</div>
    
  </div>
</div>

<div class="modal hide" id="AddImageDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Add Image</h3>
  </div>
  <div class="modal-body">
  
    <input id="type" type="hidden" value="image">
    <input id="slideshowId" type="hidden" value="-1">
    <input id="galleryId" type="hidden" value="-1">
    <input id="moduleId" type="hidden" value="-1">

    <ul id="imageTabs" class="nav nav-pills">
      <li id="showImageAndPosition" class="active"><a href="#">Image and Position</a></li>
      <li id="showImageLink"><a href="#">Image Link</a></li>
      <li id="showImageConstraints"><a href="#">Image Constraints</a></li>
    </ul>

    <div id="viewImageAndPosition">

    <form class="form-horizontal">
    
    <div class="control-group">
      <label class="radio"><input id="NewImage" type="radio" name="addImageRadio" checked> New Image</label>
    </div>

    <div class="control-group">
      <input id="Upload" type="button" value="Upload Image" class="btn">
      <div id="UploadedImage">
        
      </div>
    </div>

    <div class="control-group">
      <label class="radio"><input id="ExistingImage" type="radio" name="addImageRadio"> Existing Image</label>
    </div>
    
    <div class="control-group">
      <span id="addImageList" class="imageList">
        
      </span>
    </div>
    
    <div id="selectPosition" class="control-group">  
      <label>Image Position:</label>
      <ul id="pos1" class="imgposition">
        <input id="ImagePosition" type="hidden" value="left">
        <li id="left" title="Image on the left, text on right" class="selected"></li>
        <li id="none" title="Image only"></li>
        <li id="right" title="Image on the right, text on the left"></li>
      </ul>
    </div>

    </div>

    <div id="viewImageLink">
    
    <div class="control-group">
      <label>Link Image To:</label>
      <input id="ImageHREF" type="text" value="" placeholder="http://"> 
    </div>

    </form>

    </div>

    <div id="viewImageConstraints">
    
    <div class="control-group">
      <label>Image Width:</label>
      <input id="ImageWidth" type="number" value="" placeholder="width"> <span class="addtl">px</span>
    </div>

    <div class="control-group">
      <label>Image Height:</label>
      <input id="ImageHeight" type="number" value="" placeholder="height"> <span class="addtl">px</span>
    </div>
    
    </div>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" class="btn btn-primary" id="AddImageAction">Add Image</a>
    
    <input id="ExistingUrl" type="hidden" value="-1">
    <input id="ExistingUniqId" type="hidden" value="-1">
    <input id="UploadUrl" type="hidden" value="-1">
    <input id="UploadUniqId" type="hidden" value="-1">
  </div>  
</div>
<!-- /.modal -->

<div class="modal hide" id="AddSlideShowDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Add Slideshow</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

    <form class="form-horizontal">

    <p>
      Select the target width and height for the images in the slideshow.
    </p>

    <div class="control-group">
      <label for="slideShowWidth" class="control-label">Target Width:</label>
      <div class="controls">
        <input id="slideShowWidth" type="text" value="1024"> px
      </div>
    </div>

    <div class="control-group">
      <label for="slideShowHeight" class="control-label">Target Width:</label>
      <div class="controls">
        <input id="slideShowHeight" type="text" value="768"> px
      </div>
    </div>

    </form>
    <!-- /.form-horizontal -->
  
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="AddSlideShow" type="button" class="btn btn-primary" value="Add Slideshow">
  </div>  
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="ElementConfigDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Settings</h3>
  </div>
  <!-- /.modal-header -->

  <input id="ConfigModuleId" value="-1" type="hidden"></input>

  <div class="modal-body">

    <form class="form-horizontal">

    <div class="control-group">
      <label for="ElementId" class="control-label">Element Id:</label>
      <div class="controls">
        <input id="ElementId" type="text" maxlength="128" value="">
      </div>
    </div>

    <div id="cssClass" class="control-group">
      <label for="ElementCssClass" class="control-label">CSS Class:</label>
      <div class="controls">
        <input id="ElementCssClass" type="text" maxlength="128" value="">
      </div>
    </div>

    </form>
    <!-- /.form-horizontal -->
  
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="UpdateSettings" type="button" class="btn btn-primary" value="Update">
  </div>  
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="BlockConfigDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Settings</h3>
  </div>
  <!-- /.modal-header -->

  <input id="ConfigBlockId" value="-1" type="hidden"></input>

  <div class="modal-body">

    <form class="form-horizontal">

    <div class="control-group">
      <label for="BlockId" class="control-label">Block Id:</label>
      <div class="controls">
        <input id="BlockId" type="text" maxlength="128" value="">
      </div>
    </div>

    <div class="control-group">
      <label for="BlockClass" class="control-label">Block Class:</label>
      <div class="controls">
        <input id="BlockClass" type="text" maxlength="128" value="">
      </div>
    </div>

    </form>
    <!-- /.form-horizontal -->
  
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="UpdateBlockSettings" type="button" class="btn btn-primary" value="Update">
  </div>  
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="AddEditFieldDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Add Field</h3>
  </div>
  <!-- /.modal-header -->

  <input id="FormId" value="-1" type="hidden"></input>

  <div class="modal-body">

    <form class="form-horizontal">

    <div class="control-group">
      <label for="AddFieldName" class="control-label">Field Name:</label>
      <div class="controls">
        <input id="AddFieldName" type="text" maxlength="128" value="">
        <span class="help-block">e.g.: Phone Number, Name, etc.</span>
      </div>
    </div>

    <div class="control-group">
      <label for="AddFieldType" class="control-label">Field Type:</label>
      <div class="controls">
        <select id="AddFieldType">
          <option value="text">Text Box</option>
          <option value="textarea">Text Area</option>
          <option value="select">Dropdown List</option>
          <option value="checkboxlist">Checkbox List</option>
          <option value="radiolist">Radio button List</option>
        </select>
      </div>
    </div>

    <div class="control-group">
      <label for="AddRequired" class="control-label">Required:</label>
      <div class="controls">
        <select id="AddRequired">
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>
    </div>

    <div id="options" class="control-group">
      <label for="AddOptions" class="control-label">Options:</label>
      <div class="controls">
        <textarea id="AddOptions"></textarea>
        <span class="help-block">Separate each option with a comma</span>
      </div>
    </div>

    <div class="control-group">
      <label for="AddHelperText" class="control-label">Helper Text:</label>
      <div class="controls">
        <input id="AddHelperText" type="text" maxlength="256" value="">
        <span class="help-block">e.g.: (314) 444-2343</span>
      </div>
    </div>

    </form>
    <!-- /.form-horizontal -->
  
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="AddField" type="button" class="btn btn-primary" value="Add Field">
  </div>  
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="AddEditListDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Add List</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  <input id="ListModuleId" type="hidden" value="-1">
  <input id="ListEditMode" type="hidden" value="content">
  <input id="ListPageTypeUniqId" type="hidden" value="-1">
  <input id="ListType" type="hidden" value="-1">
  <input id="ListLabel" type="hidden" value="-1">

    <form class="form-horizontal">

        <div id="listPageTypeBlock" class="control-group">
          <label for="listPageType" class="control-label">Page Type:</label>
          <div class="controls">
              <select id="listPageType">
              <?php 
              mysql_data_seek($p->PageTypes, 0);
              while($row = mysql_fetch_array($p->PageTypes)){ ?>
              <option value="<?php print $row['PageTypeUniqId']; ?>"><?php print $row['TypeP']; ?></option>
              <?php } ?>
              </select>
          </div>
        </div>

        <div class="control-group">
          <label for="listDisplay" class="control-label">Display:</label>
          <div class="controls">
             <select id="listDisplay">
                <option value="list">List</option>
                <option value="blog">Blog</option>
             </select>
          </div>
        </div>

        <div class="control-group">
          <label for="listOrderBy" class="control-label">Order By:</label>
          <div class="controls">
             <select id="listOrderBy">
                <option value="Name">Name</option>
                <option value="Created">Date Created (newest first)</option>
              </select>
          </div>
        </div>

        <div class="control-group">
          <label for="listDescLength" class="control-label">Description Length:</label>
          <div class="controls">
             <input id="listDescLength" type="number" value="250">
          </div>
        </div>

        <div class="control-group">
          <label for="listLength" class="control-label">Page Size:</label>
          <div class="controls">
             <input id="listLength" type="number" value="10">
          </div>
        </div>

        <div class="control-group">
          <label for="listPageResults" class="control-label">Page Results:</label>
          <div class="controls">
            <select id="listPageResults">
              <option value="true">Yes</option>
              <option value="false">No</option>
            </select>
          </div>
        </div>

    </form>
    <!-- /.form-horizontal -->
  
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="AddList" type="button" class="btn btn-primary" value="Add List">
    <input id="UpdateList" type="button" class="btn btn-primary" value="Update List">
  </div>  
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="AddLinkDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Add Link</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  <input id="MenuItemUniqId" type="hidden" value="-1">
  <input id="LinkPageId" type="hidden" value="-1">
  <input id="LinkExistingUrl" type="hidden" value="-1">

  <p>
    <label><input id="Existing" type="radio" class="radio" name="content" checked> Existing Page</label>
  </p>  
  
  <?php 
    $prefix = '';
    if($p->Page->PageTypeId != -1){
      $prefix = '../';
      $home = '../';
    }
    else{
      $home = ' ';
    }
  
  ?>
  
  <div id="PageUrl" class="select small">
  <ul>
    <li data-pageid="-1" data-url="<?php print $home; ?>">Home</li>
    <?php 
    mysql_data_seek($p->PageTypes, 0);
    while($row = mysql_fetch_array($p->PageTypes)){
     
      $hlist = Page::GetPagesForPageType($p->AuthUser->SiteId, $row['PageTypeId']);
        
      while($hrow = mysql_fetch_array($hlist)){?>
        
      <li data-pageid="<?php print $hrow['PageId']?>" data-url="<?php print $prefix.strtolower($row['FriendlyId']); ?>/<?php print strtolower($hrow['FriendlyId']); ?>"><?php print $hrow['Name'] ?></li>
        
      <?php }  ?>
    <?php } ?>
  
  
    <?php 
    mysql_data_seek($p->PageTypes, 0);
    while($row = mysql_fetch_array($p->PageTypes)){ ?>
      <li id="<?php print $row['PageTypeUniqId']; ?>"><?php print $row['TypeP']; ?></li>
    <?php } ?>
  </ul>
  </div>
  
  <p>
    <label><input id="CustomUrl" type="radio" name="content" class="radio"> Custom URL</label>
  </p>
  
  <p>
    <input id="LinkUrl" type="text" class="span3">
  </p>

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="AddLinkAction" type="button" class="btn btn-primary" value="Add Link">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="LoadLayoutDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Load Existing Page</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  <div id="selectPage" class="select">
    <ul>
    <li data-pageuniqid="-2">Home</li>
    <?php 
    mysql_data_seek($p->PageTypes, 0);
    while($row = mysql_fetch_array($p->PageTypes)){
     
      $hlist = Page::GetPagesForPageType($p->AuthUser->SiteId, $row['PageTypeId']);
        
      while($hrow = mysql_fetch_array($hlist)){?>
        
      <li data-pageuniqid="<?php print $hrow['PageUniqId']?>" data-url="<?php print $prefix.strtolower($row['FriendlyId']); ?>/<?php print strtolower($hrow['FriendlyId']); ?>"><?php print $hrow['Name'] ?></li>
        
      <?php }  ?>
    <?php } ?>

    </ul>
  </div>

  <input id="createPage" type="hidden" value="-1">

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="LoadLayout" class="btn btn-primary" type="button" value="Load Layout">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="PluginsDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Select Plugin</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  <div id="selectPlugin" class="select">
    <ul>
    <?php 
  $json = file_get_contents('plugins/plugins.json');
  $data = json_decode($json, true);
  $slide = 0;
  
  foreach($data as &$item) {
    $type = $item['type'];
    $name = $item['name'];
    $desc = $item['desc'];
    $render = $item['render'];
    $config = $item['config'];
    
  ?>
    <li><a data-name="<?php print $name; ?>" data-type="<?php print $type; ?>" data-render="<?php print $render; ?>" data-config="<?php print $config; ?>" href="#"><?php print $name; ?></a> <em><?php print $desc; ?></em></li>
<?php } ?>
    </ul>
  </div>

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="AddPlugin" class="btn btn-primary" type="button" value="Add Plugin">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="ConfigPluginsDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Configure Plugin</h3>
  </div>
  <!-- /.modal-header -->

  <input type="hidden" id="PluginUniqId" value="-1">
  <input type="hidden" id="PluginType" value="-1">

  <div id="configurePluginForm" class="modal-body">

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a id="PluginClose" href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="UpdatePluginConfigs" class="btn btn-primary" type="button" value="Update">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="PageSettingsDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Page Settings</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

    <form class="form-horizontal">

      <div class="control-group">
        <label for="Name" class="control-label">Name:</label>
        <div class="controls">
          <input id="Name" type="text" maxlength="128" style="width:200px;" value="<?php print $p->Name; ?>">
        </div>
      </div>
      
  <?php if($p->Page->PageTypeId != -1){ ?>
      <div class="control-group">
        <label for="URL" class="control-label">URL:</label>
        <div class="controls">
          http://<?php print $p->SiteUrl; ?>/<?php print strtolower($p->TypeS); ?>/<input id="FriendlyId" type="text" maxlength="128" style="width:100px;" value="<?php print $p->FriendlyId; ?>">
          <input id="FullUrl" type="hidden" value="http://<?php print $p->SiteUrl; ?>/<?php print strtolower($p->TypeS); ?>/<?php print $p->FriendlyId; ?>.html">
        </div>
      </div>
  <?php }else{ ?>
    <input id="FullUrl" type="hidden" value="http://<?php print $p->SiteUrl; ?>/">
    <input id="FriendlyId" type="hidden" value="<?php print $p->FriendlyId; ?>">
  <?php } ?>

      <div class="control-group">
        <label for="Description" class="control-label">Description:</label>
        <div class="controls">
          <textarea id="Description" style="width: 80%; height: 100px;"><?php print $p->Description; ?></textarea>
          <span class="help-block">Added to the <code>head</code> of the page, used as the description in search engines and for lists</span>
        </div>
      </div>
      
      <div class="control-group">
        <label for="Keywords" class="control-label">Keywords:</label>
        <div class="controls">
          <textarea id="Keywords" placeholder="keyword1, keyword2, keyword3, etc." style="width: 80%; height: 50px;"><?php print $p->Keywords; ?></textarea>
        </div>
      </div>   
      
      <div class="control-group">
        <label for="Callout" class="control-label">Callout:</label>
        <div class="controls">
          <input id="Callout" type="text" maxlength="100" value="<?php print $p->Page->Callout; ?>" placeholder="Between $5-$8, On Sale Now">
          <span class="help-block">Shows below the page name in lists to call attention to the item</span>
        </div>
      </div>
      
      <div class="control-group">
        <label for="Keywords" class="control-label">RSS:</label>  
        <div class="controls">
          <span class="checklist">
          <?php 
          $a_rss = explode(',', $p->Page->Rss);
        
          mysql_data_seek($p->PageTypes, 0);
          while($row = mysql_fetch_array($p->PageTypes)){ ?>
            <label class="checkbox"><input type="checkbox" value="<?php print $row['FriendlyId']; ?>" class="rss" <?php if(in_array($row['FriendlyId'], $a_rss)==true){print 'checked';}?>> <?php print $row['TypeP']; ?></label>
          <?php } ?>
          </span>
          <span class="help-block">Adds a reference to the selected RSS feeds in the <code>head</code> of the page</span>
        </div>
      </div>
      
      <div class="control-group">
        <label for="Layout" class="control-label">Layout:</label>
        <div class="controls">
          <select id="Layout">
            <option value="home" <?php if($p->Layout=='home'){print 'selected';} ?>>Home</option>
            <option value="content" <?php if($p->Layout=='content'){print 'selected';} ?>>Content</option>
          <?php while($row = mysql_fetch_array($p->Layouts)){ ?>
            <option value="<?php print $row['Filter']; ?>" <?php if($p->Layout==$row['Filter']){print 'selected';} ?>><?php print $row['Name']; ?></option>
          <?php } ?>  
          </select>
          <span class="help-block">HTML used to render the page</span>
        </div>
      </div>
      
      <div class="control-group">
        <label for="Stylesheet" class="control-label">Styles:</label>
        <div class="controls">
          <select id="Stylesheet">
            <option value="home" <?php if($p->Stylesheet=='home'){print 'selected';} ?>>Home</option>
            <option value="content" <?php if($p->Stylesheet=='content'){print 'selected';} ?>>Content</option>
          <?php while($row = mysql_fetch_array($p->Stylesheets)){ ?>
            <option value="<?php print $row['Filter']; ?>" <?php if($p->Stylesheet==$row['Filter']){print 'selected';} ?>><?php print $row['Name']; ?></option>
          <?php } ?>  
          </select>
          <span class="help-block">CSS used to render the page</span>
        </div>
      </div>
    </form>

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="UpdatePageSettings" class="btn btn-primary" type="button" value="Update">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="CodeBlockDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Add Code Block</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

    <form class="form-horizontal">

        <p>Paste your code below:</p>
      
        <textarea id="Code" style="height: 300px; width: 100%; margin-right: 10px; box-sizing: border-box;"></textarea>

    </form>

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <input id="AddCode" class="btn btn-primary" type="button" value="Add Code Block">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->


<div id="overlay"></div>

<?php include 'modules/footer.php'; ?>
  
</body>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>

<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/jquery.respondEdit.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.1.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/loadLayoutDialog.js"></script>
<script type="text/javascript" src="js/pluginsDialog.js"></script>
<script type="text/javascript" src="js/configPluginsDialog.js"></script>
<script type="text/javascript" src="js/pageSettingsDialog.js"></script>
<script type="text/javascript" src="js/codeBlockDialog.js"></script>
<script type="text/javascript" src="js/prettify.js"></script>
<script type="text/javascript" src="js/content.js"></script>
<script type="text/javascript" src="js/imagesDialog.js"></script>
<script type="text/javascript" src="js/jquery.paste.js"></script>
<script type="text/javascript" src="js/jquery.imgareaselect.min.js"></script>
</html>