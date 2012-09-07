<?php	
	include 'global.php'; // import php files
	include 'actions/Branding.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'template';
	
	$p = new Branding($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Settings&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/page.css" rel="stylesheet">
<link type="text/css" href="css/dialog.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">
<link type="text/css" href="css/imgareaselect/imgareaselect-default.css" rel="stylesheet">
<link type="text/css" href="css/cupertino/jquery-ui-1.8.1.custom.css" rel="stylesheet">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.1.js"></script>
<script type="text/javascript" src="js/branding.js"></script>
<script type="text/javascript" src="js/jquery.imgareaselect.min.js"></script>

</head>

<body id="profile">
	
<!-- required for actions -->
<input type="hidden" name="_submit_check" value="1"/>

<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>

<?php include 'modules/menu.php'; ?>

<div class="content container-fluid">

  <div class="row-fluid header-row">
    
    <div class="span12">
	
		<h1>Design</h1>
	
		<!-- begin submenu -->
		<ul id="settingsMenu" class="nav nav-tabs">
			<li><a href="templates.php">Template</a></li>
			<li class="active"><a href="branding.php">Branding</a></li>
			<li><a href="html.php?l=home">Layout</a></li>
			<li><a href="css.php?l=global">Styles</a></li>
		</ul>
		
	</div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">
	
	
		<input id="fileurl" type="hidden" value="<?php print $p->AuthUser->FileUrl; ?>">
		<input id="mobileurl" type="hidden" value="<?php print $p->MobileUrl; ?>">
		
		<form class="form-horizontal">

		<div class="control-group">
			<label for="LogoUrl"  class="control-label">Default Logo:</label>

			<div class="controls">
				<?php if($p->LogoUrl!=''){ ?>
				<span id="logo">
				<img src="<?php print $p->AuthUser->FileUrl; ?><?php print $p->LogoUrl ?>">
				</span>
				<?php }else{ ?>
				<span id="noneSelected">
					<span class="label">No logo selected.</span>
				</span>
				<?php }?>
			</div>
		</div>
		
		<input id="LogoUrl" value="<?php print $p->LogoUrl ?>" type="hidden">
		
		<div class="control-group">
			<label for="LogoUrl"  class="control-label">&nbsp;</label>
			<div class="controls">
				<input id="Upload" type="button" value="Change Logo" class="btn">
			</div>
		</div>
		
		</form>
		<!-- /.form-horizontal -->
		
	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

<div id="overlay"></div>

<div id="CropDialog" class="immersive">
  <div>
    
    <div class="editImgContainer">
    <img id="cropImage" src="">
    <p id="debug" style="color: #fff"></p>
    </div>
    
    <input id="c_uniqueName" value="" type="hidden">
    <input id="c_isSlideShow" value="" type="hidden">
    <input id="c_isConstrained" value="false" type="hidden">
    <input id="c_url" value="" type="hidden">
    <input id="c_mobileurl" value="" type="hidden">
    <input id="c_width" value="1024" type="hidden">
    <input id="c_height" value="768" type="hidden">
    <input id="c_min_width" value="1" type="hidden">
    <input id="c_min_height" value="1" type="hidden">
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

<?php include 'modules/footer.php'; ?>

</body>

</html>