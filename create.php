<?php	
	include 'global.php'; // import php files
	include 'actions/Create.php';
	
	$p = new Create($passcode, $siteurl); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Create Site</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include styles -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/login.css" rel="stylesheet">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/create.js"></script>

</head>

<body>

<p id="message">
  <span></span>
  <a class="close" href="#"></a>
</p>

<input type="hidden" id="HasError" value="<?php print $p->HasError ?>">
<input type="hidden" id="HasSuccess" value="<?php print $p->HasSuccess ?>">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		
	<!-- required for actions -->
	<input type="hidden" name="_submit_check" value="1"/>
	
<!-- begin content -->
<div class="content container-fluid">

  <div class="row-fluid">
    
    <div class="span4"></div>
	<!-- /.span4 -->

    <div class="span4">

	<h1><a href="http://respondcms.com"><img src="images/respond-logo.png"></a></h1>

	<div id="create-form">
	
	<fieldset>
		
		<p>Tell us your site name</p>

		<div class="control-group">
			<label for="Name">Site Name:</label>
			<input id="Name" type="text" value="" placeholder="Site Name">
			<p class="siteName"><?php print $siteurl; ?>/<span id="tempUrl" class="temp">your-site</span></p>
			<input id="FriendlyId" type="hidden" value="">
		</div>

		<p class="createLogin">Create a login</p>
		
		<div class="control-group">
			<label for="Email">Email:</label>
			<input id="Email" type="text" value="" placeholder="Email">
		</div>
		
		<div class="control-group">
			<label for="Password">Password:</label>
			<input id="Password" type="password" placeholder="Password">
		</div>

		<div class="control-group">
			<label for="Retype">&nbsp;</label>
			<input id="Retype" type="password" placeholder="Retype to verify">
		</div>

	<?php if($passcode != ''){ ?>
		<p>Key in the passcode</p>

		<div class="control-group">
			<label for="Passcode">&nbsp;</label>
			<input id="Passcode" type="text" placeholder="Type the passcode">
		</div>

	<?php }else{ ?>
		<input id="public $Ajax = '';" type="hidden" value="">
	<?php } ?>


		<span class="actions">
			<button id="Create" type="button" class="btn btn-primary">Create Site <i class="icon-chevron-right icon-white"></i></button>
		</span>

	</fieldset>
	
	</div>
	
	<div id="create-confirmation">

	<fieldset>
		<p>
			Account created! To get started, click on your login link below.
		</p>	


		<p>
			Login here to update your site:
		</p>
		<p>
			<a id="loginLink" href="<?php print $loginurl; ?>"><?php print $loginurl; ?></a>
		</p>
		
		<p>
			You can already view your site here: 
		</p>
		<p>	
			<a id="siteLink" href="<?php print $siteurl; ?>/{friendlyId}"><?php print $siteurl; ?>/{friendlyId}</a>
		</p>
		
		<p>
			Bookmark these links for easy access.
		</p>
		

	</fieldset>
	
	</div>

	<p class="footer">
		&copy; 2012 Respond CMS. Built in St. Louis, MO
	</p>

	</div>
	<!-- /.span4 -->

	<div class="span4"></div>
	<!-- /.span4 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->


</form>

</body>

</html>