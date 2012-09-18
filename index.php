<?php	
	include 'global.php'; // import php files
	include 'actions/Home.php';
	
	$p = new Home($sa); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Login&mdash;Respond CMS</title>

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
<script type="text/javascript" src="js/login.js"></script>

</head>

<body>
	
<input id="hasError" value="<?php print $p->HasError; ?>" type="hidden">
	
<p id="message">
  <span><?php print $p->Errors; ?></span>
  <a class="close" href="#"></a>
</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		
	<input type="hidden" name="_submit_check" value="1"/>

<!-- begin content -->
<div class="content container-fluid">

  <div class="row-fluid">
    
    <div class="span4"></div>
	<!-- /.span4 -->

    <div class="span4">

	<h1><a href="http://respondcms.com"><img src="images/respond-logo.png"></a></h1>
	
	<fieldset>
		<p>Enter your email and password to login</p>

		<div class="control-group">
			<label for="Email" class="control-label">Email:</label>
			<input id="Email" name="Email" type="text" value="<?php print $p->Email ?>" autocomplete="off" placeholder="Email">
		</div>
		
		<div class="control-group">
			<label for="Password" class="control-label">Password:</label>
			<input name="Password" type="Password" autocomplete="off" placeholder="Password">
		</div>
		
		<input name="SiteUniqId" value="<?php print $p->SiteUniqId; ?>" type="hidden">
			
		<span class="actions">
			<button name="Login" type="submit" class="btn btn-primary">Login <i class="icon-chevron-right icon-white"></i></button>
		</span>
	</fieldset>
	<!-- end login -->

<?php if($demoemail!=''){ ?>
	<p class="demo">To access the demo, use <b><?php print $demoemail; ?></b> for your email and <b><?php print $demopassword; ?></b> for the password.
<?php } ?>


	<p class="forgot">
		Forgot your password?  <a href="forgot.php">Reset it here.</a>
	</p>
	
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