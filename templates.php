<?php	
	include 'global.php'; // import php files
	include 'actions/Templates.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'template';
	
	$p = new Templates($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Template&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/page.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">
<link type="text/css" href="css/templates.css" rel="stylesheet" media="screen">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/templates.js"></script>

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
		<li class="active"><a href="templates.php">Template</a></li>
		<li><a href="branding.php">Branding</a></li>
		<li><a href="html.php?l=home">Layout</a></li>
		<li><a href="css.php?l=global">Styles</a></li>
	</ul>

	</div>
  </div>

  <div class="row-fluid">
    <div class="span12">

	<div id="templatesList" class="list">
		
<?php 
	$json = file_get_contents('templates/templates.json');
	$data = json_decode($json, true);
	$slide = 0;
	
	foreach($data as &$item) {
		$id = $item['id'];
		$name = $item['name'];
		$desc = $item['desc'];
		$isCurrent = false;
		
		if($p->Template==$id){
			$isCurrent = true;
		}
		
		$slide = $slide+1;

	?>
		<div class="listItem <?php if($isCurrent==true){ ?>active<?php } ?>">
			<h2>
				<?php print $name; ?> 
			</h2>
			<p><?php print $desc; ?></p>

			<input data-template="<?php print $id; ?>" type="button" value="Apply Template" class="btn btn-primary apply">
			<input data-template="<?php print $id; ?>" type="button" value="Reset Template" class="btn reset">
			
		</div>
		<!-- /.slide -->
<?php } ?>
		
   		</div>
   		<!-- /.slider -->

   </div>
   <!-- /.container -->


	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->


<?php include 'modules/footer.php'; ?>

</body>

</html>