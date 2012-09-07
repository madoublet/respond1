<?php	
	include 'global.php'; // import php files
	include 'actions/PageTypes.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'settings';
	
	$p = new PageTypes($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Page Types&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/page.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/pageTypes.js"></script>

</head>

<body>

<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>

<?php include 'modules/menu.php'; ?>

<div class="content container-fluid">

  <div class="row-fluid header-row">
    
    <div class="span12">
	
	<h1>
		Settings
		<input id="AddPageType" type="button" value="Add Page Type" class="btn btn-primary">
	</h1>
	
	<ul class="nav nav-tabs">
		<li><a href="settings.php">Basic Settings</a></li>
		<li class="active"><a href="pageTypes.php">Page Types</a></li>
		<li><a href="menuTypes.php">Menu Types</a></li>
		<li><a href="publishSite.php">Publish</a></li>
	</ul>

	</div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">
		
	<div id="pageTypeList" class="list">
		
		<?php while($row = mysql_fetch_array($p->List)){ ?>
			<div id="item-<?php print $row['PageTypeUniqId']; ?>" class="listItem">
			<?php if(strtolower($row['TypeS'])!='page'){?>
				<a id="remove-<?php print $row['PageTypeUniqId']; ?>" class="remove" href="#"></a>
			<?php } ?>
			<?php if(strtolower($row['TypeS'])=='page'){?>
				<h2><?php print $row['TypeP']; ?></h2>
				<em>System Default</em>
			<?php }else{ ?>
				<h2><a id="edit-<?php print $row['PageTypeUniqId']; ?>" class="edit" href="#"><?php print $row['TypeP']; ?></a></h2>
				<input id="typeS-<?php print $row['PageTypeUniqId']; ?>" type="hidden" value="<?php print $row['TypeS']; ?>">
				<input id="typeP-<?php print $row['PageTypeUniqId']; ?>" type="hidden" value="<?php print $row['TypeP']; ?>">
				<input id="friendlyId-<?php print $row['PageTypeUniqId']; ?>" type="hidden" value="<?php print $row['FriendlyId']; ?>">
				<em>Created <?php print $p->GetFriendlyDate(strtotime($row['Created'])); ?></em>
			<?php } ?>
			</div>
		<?php } ?>
	</div>
	<!-- /.list -->
		
	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

<div class="modal hide" id="AddEditDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3 id="AddEditTitle">Add Page Type</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  	<form class="form-horizontal">

		<input id="PageTypeUniqId" type="hidden" value="-1">
		
		<div class="control-group">
			<label for="TypeS" class="control-label">Name (singular):</label>
			<div class="controls">
				<input id="TypeS"  value="" maxlength="100">
				<span class="help-block">e.g.: Page, Blog, Product, etc.</span>
			</div>
		</div>
		
		<div class="control-group">
			<label for="TypeP" class="control-label">Name (Plural):</label>
			<div class="controls">
				<input id="TypeP"  value="" maxlength="100">
				<span class="help-block">e.g.: Pages, Blogs, Products, etc.</span>
			</div>
		</div>
		
		<div class="control-group">
			<label for="FriendlyId" class="control-label">Friendly URL:</label>
			<div class="controls">
				<input id="FriendlyId" value="" maxlength="50">
				<span class="help-block">e.g. http://respondcms.com/[friendly-url]/. Must be lowercase with no spaces.</span>
			</div>
		</div>

	</form>
	<!-- /.form-horizontal -->

	</div>
	<!-- /.modal-body -->

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<input id="AddEditAction" type="button" class="btn btn-primary" value="Add Page Type">
	</div>

</div>
<!-- /.modal -->

<div class="modal hide" id="DeleteDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Delete Page Type</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">
	
	<p>
		Are you sure that you want to delete <strong id="removeName">this page type</strong>?
	</p>
	
	<input id="DeleteId" type="hidden" value="-1">

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="DeleteAction" type="button" class="btn btn-primary" value="Delete Page Type">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<?php include 'modules/footer.php'; ?>

</body>

</html>