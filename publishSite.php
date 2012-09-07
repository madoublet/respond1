<?php	
	include 'global.php'; // import php files
	include 'actions/PublishSite.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All'); // validate
	
	$currpage = 'settings';
	
	$p = new PublishSite($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Publish&mdash;<?php print $authUser->SiteName; ?></title>

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
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/publishSite.js"></script>

</head>

<body id="posts">

<!-- begin global messages -->
<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>
	
<?php include 'modules/menu.php'; ?>

<input id="IsFirstLogin" type="hidden" value="<?php print $p->IsFirstLogin; ?>">
		
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
		<li><a href="menuTypes.php">Menu Types</a></li>
		<li class="active"><a href="publishSite.php">Publish</a></li>
	</ul>

	</div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">

    <form class="form-horizontal">	
	<p>
		<input id="PublishSite" type="button" class="btn btn-primary" value="Publish Site"></input>
	</p>
	
	<p id="result">
	
	</p>
	</form>
	<!-- /.form-horizontal -->

	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->
</body>

</html>