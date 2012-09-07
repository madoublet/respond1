<?php	
	include 'global.php'; // import php files
	include 'actions/Settings.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'settings';
	
	$p = new Settings($authUser); // setup controller
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
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/settings.js"></script>

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
	
	<h1>Settings</h1>

	<ul class="nav nav-tabs">
		<li class="active"><a href="settings.php">Basic Settings</a></li>
    	<li><a href="pageTypes.php">Page Types</a></li>
		<li><a href="menuTypes.php">Menu Types</a></li>
		<li><a href="publishSite.php">Publish</a></li>
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
		
		<fieldset>
		
		<div class="control-group">
			<label for="Name" class="control-label">Site Name:</label>
			<div class="controls">
				<input id="Name" name="Name" type="text" value="<?php print $p->Name ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label for="Domain" class="control-label">Domain:</label>
			<div class="controls">
				<input id="Domain" name="Domain" type="text" value="<?php print $p->Domain ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label for="PrimaryEmail" class="control-label">Primary Email:</label>
			<div class="controls">
				<input id="PrimaryEmail" name="PrimaryEmail" type="text" value="<?php print $p->PrimaryEmail ?>">
				<span class="help-block">Forms submitted on your site will be sent to this email address</span>
			</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		
		<div class="control-group">
			<label for="TimeZone" class="control-label">Time Zone:</label>
			<div class="controls">
				<select id="TimeZone">
					<option value="EST" <?php if($p->TimeZone=='EST'){print 'selected';} ?>>Eastern (EST)</option>
					<option value="CST" <?php if($p->TimeZone=='CST'){print 'selected';} ?>>Central (CST)</option>
					<option value="MST" <?php if($p->TimeZone=='MST'){print 'selected';} ?>>Mountain (MST)</option>
					<option value="PST" <?php if($p->TimeZone=='PST'){print 'selected';} ?>>Pacific (PST)</option>
				</select>
			</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		<div class="control-group">
			<label for="AnalyticsId" class="control-label">Google Analytics ID:</label>
			<div class="controls">
				<input id="AnalyticsId" type="text" value="<?php print $p->AnalyticsId ?>">
				<span class="help-block">Google Analytics Web Property Id (adds analytics to all pages on your site)</span>
			</div>
		</div>	
		</fieldset>

		<fieldset>
		<div class="control-group">
			<label for="FacebookAppId" class="control-label">Facebook App ID:</label>
			<div class="controls">
				<input id="FacebookAppId" type="text" value="<?php print $p->FacebookAppId ?>">
				<span class="help-block">Allows you to moderate comments on your site, create here: <a href="https://developers.facebook.com/apps/">https://developers.facebook.com/apps/</a></span>
			</div>
		</div>	
		</fieldset>
		
		<fieldset>
		<div class="control-group">
			<label for="sitemap" class="control-label">Sitemap:</label>
			<div class="controls">
				<span class="readOnly">
					<?php print $p->SiteMap; ?> &nbsp; <a href="<?php print $p->SiteMap; ?>">view</a>
				</span>
			</div>
		</div>	
		<div class="control-group">
			<label for="mobilesitemap" class="control-label">Mobile Sitemap:</label>
			<div class="controls">
				<span class="readOnly">
					<?php print $p->MobileSiteMap; ?> &nbsp; <a href="<?php print $p->MobileSiteMap; ?>">view</a>
				</span>
			</div>
		</div>
		<div class="control-group">
			<label for="verification" class="control-label">Sitemap Verification:</label>
			<div class="controls">
				<span class="readOnly">
					<a id="ShowVerifyDialog" href="#">Generate Verification File</a>
				</span>
				<span class="help-block">Setup your sitemaps at google.com/webmasters</span>
			</div>
		</div>	
		</fieldset>
		
	</form>
	<!-- /.control-group -->
	

	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

<div id="actions">
  <button id="Update" class="btn btn-primary" type="button">Save</button>
</div>


<div class="modal hide" id="VerifyDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Generate Verification File</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

	<form class="form-horizontal">
	
	<div class="control-group">
		<label for="FileName" class="control-label">File Name:</label>
		<div class="controls">
			<input id="FileName" type="text" value="" class="text" maxlength="255">
			<span class="help-block">e.g. google12345678910abc123.html</span>
		</div>
	</div>
	
	<div class="control-group">
		<label for="FileContent" class="control-label">File Contents:</label>
		<div class="controls">
			<textarea id="FileContent"></textarea>
			<span class="help-block">e.g. google-site-verification: google12345678910abc123.html</span>
		</div>
	</div>
	
	</form>

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="AddAction" type="button" class="btn btn-primary" value="Add Verification File">
  </div>

</div>
<!-- /.modal -->
	

<?php include 'modules/footer.php'; ?>

</body>

</html>