<?php	
	include 'global.php'; // import php files
	include 'actions/MenuTypes.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'settings';
	
	$p = new MenuTypes($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Menu Types&mdash;<?php print $authUser->SiteName; ?></title

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
<script type="text/javascript" src="js/menuTypes.js"></script>

</head>

<body id="profile">
	
<!-- required for actions -->
<input type="hidden" name="_submit_check" value="1"/>

<p id="message">
  <span>Holds the message text</span>
  <a class="close" href="#"></a>
</p>

<?php include 'modules/menu.php'; ?>

<div class="content container-fluid">

  <div class="row-fluid header-row">
    
    <div class="span12">
	
	<h1>
		Settings
		<input id="AddMenuType" type="button" value="Add Menu Type" class="btn btn-primary">
	</h1>
	
	<ul class="nav nav-tabs">
		<li><a href="settings.php">Basic Settings</a></li>
		<li><a href="pageTypes.php">Page Types</a></li>
		<li class="active"><a href="menuTypes.php">Menu Types</a></li>
		<li><a href="publishSite.php">Publish</a></li>
	</ul>

	</div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">
		
	<div id="menuTypeList" class="list">

		<div id="item-0" class="listItem">
			<h2>Primary</h2>
			<em>System default</em>
		</div>

		<div id="item-1" class="listItem">
			<h2>Footer</h2>
			<em>System default</em>
		</div>
		
		<?php while($row = mysql_fetch_array($p->List)){ ?>
			<div id="item-<?php print $row['MenuTypeUniqId']; ?>" class="listItem">
				<h2><a id="edit-<?php print $row['MenuTypeUniqId']; ?>" class="edit" href="#"><?php print $row['Name']; ?></a></h2>
				<input id="name-<?php print $row['MenuTypeUniqId']; ?>" type="hidden" value="<?php print $row['Name']; ?>">
				<input id="friendlyId-<?php print $row['MenuTypeUniqId']; ?>" type="hidden" value="<?php print $row['FriendlyId']; ?>">
				<em>Created <?php print $p->GetFriendlyDate(strtotime($row['Created'])); ?></em>
				<a id="remove-<?php print $row['MenuTypeUniqId']; ?>" class="remove" href="#"></a>
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
    <h3>Add Menu Type</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  	<form class="form-horizontal">

		<input id="MenuTypeUniqId" type="hidden" value="-1">
		
		<div class="control-group">
			<label for="Name" class="control-label">Name:</label>
			<div class="controls">
				<input id="Name" value="" maxlength="50">
			</div>
		</div>

		<div class="control-group">
			<label for="FriendlyId" class="control-label">Friendly Id:</label>
			<div class="controls">
				<input id="FriendlyId" value="" maxlength="50">
				<span class="help-block">Lowercase, no spaces, must be unique</span>
			</div>
		</div>

	</form>
	<!-- /.form-horizontal -->

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="AddEditAction" type="button" class="btn btn-primary" value="Add Menu Type">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="DeleteDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Add Menu Type</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

	<p>
		Are you sure that you want to delete <strong id="removeName">this menu type</strong>?
	</p>

	<input id="DeleteId" type="hidden" value="-1">

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="DeleteAction" type="button" class="btn btn-primary" value="Delete Menu Type">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<?php include 'modules/footer.php'; ?>

</body>

</html>