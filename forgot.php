<?php	
	include 'global.php'; // import php files
	include 'actions/Forgot.php';
	
	$p = new Forgot(); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Forgot Password</title>

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
<script type="text/javascript" src="js/app.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/forgot.js"></script>

</head>
<body id="forgot" class="external default">

<p id="message">
  <span></span>
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


	<?php if($p->UserUniqId==''){ ?>

	<fieldset id="loginForm">
		
		<p>
			Type your email address and we will send you a link to reset your password.
		</p>

		<div class="control-group">
			<label for="Email">Email:</label>
			<input id="Email" type="text" value="" placeholder="Email">	
		</div>
		
		<span class="actions">
			<button id="request" type="submit" class="btn btn-primary">Send Email <i class="icon-chevron-right icon-white"></i></button>
		</span>
		
	</fieldset>
	<!-- /#loginForm -->
	
	<?php }else{ ?>
	
		
	<fieldset id="loginForm">
		
			<p>
				Welcome back <?php print $p->Email; ?>. Type a new password for your account.
			</p>

			<div class="control-group">
				<label for="Password">Password:</label>
				<input id="Password" type="password" placeholder="New Password">
			</div>
			
			<div class="control-group">
				<label for="Retype">Retype Password:</label>
				<input id="Retype" type="password" placeholder="Retype New Password">
			</div>
			
			<span class="actions">
				<button id="reset" type="submit" class="btn btn-primary">Change Password <i class="icon-chevron-right icon-white"></i></button>
			</span>
			
		<input id="qs" type="hidden" value="<?php print $p->UserUniqId; ?>">
	</fieldset>
	<!-- /#loginForm -->
	
	<?php } ?>

	<p class="return">
		<a href="index.php">Return to Login</a>
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