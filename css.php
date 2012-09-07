<?php	
	include 'global.php'; // import php files
	include 'actions/Css.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'template';
	
	$p = new Css($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Styles&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/page.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">
<link type="text/css" href="css/layouts.css" rel="stylesheet" media="screen">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.1.js"></script>
<script type="text/javascript" src="js/css.js"></script>

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
	
	<input id="IsConfigured" value="<?php print $p->IsConfigured; ?>" type="hidden">
	<input id="LayoutUniqId" value="<?php print $p->LayoutUniqId; ?>" type="hidden">
	
	<h1>
		Design
		<button id="ShowAddDialog" class="btn btn-primary">Add Stylesheet</button>
	</h1>
	
	<ul class="nav nav-tabs">
		<li><a href="templates.php">Template</a></li>
		<li><a href="branding.php">Branding</a></li>
		<li><a href="html.php?l=home">Layout</a></li>
		<li class="active"><a href="css.php?l=global">Styles</a></li>
	</ul>

	</div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">
		
	<div class="layout">

		<ul id="layoutMenu" class="nav nav-pills">
		<?php 

		//path to directory to scan
		$directory = $p->LessDir;
 
		//get all image files with a .less ext
		$files = glob($directory . "*.less");

		$i = 0;
		$s_file = '';
 
		//print each file name
		foreach($files as $file){
			$f_arr = explode("/",$file);
			$count = count($f_arr);
			$filename = $f_arr[$count-1];
			$curr = str_replace('.less', '', $filename);
			$s_class = '';

			if($p->File=='' && $i==0){
			  $s_class = ' active';
			  $s_file = $filename;
			}
			else if($p->File==$filename){
			  $s_class = ' active';
			  $s_file = $filename;
			}

			$s_remove = '';

			if($filename != 'global.less' && $filename != 'content.less' && $filename != 'home.less'){
				print '<li id="custom-'.$curr.'" class="custom'.$s_class.'"><a href="css.php?f='.$filename.'">'.$filename.'</a><a id="remove-'.$filename.'" class="remove" href="#"></a></li>';
			}
			else{
				print '<li class="'.$s_class.'""><a href="css.php?f='.$filename.'">'.$filename.'</a></li>';
			}

			$i = $i + 1;
		} ?>
		</ul>
			
		<input id="File" type="hidden" value="<?php print $s_file; ?>">
	
		<textarea id="Content" spellcheck="false"><?php 
		  if($s_file != ''){
		  	print file_get_contents($p->LessDir.$s_file);
		  }

		?></textarea>
	
	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

<div id="actions">
	<input id="UpdateLayout" class="btn btn-primary" type="button" value="Save">
</div>

<div class="modal hide" id="AddLayoutDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Add Stylesheet</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  	<form class="form-horizontal">

	<div class="control-group">
		<label for="LayoutName" class="control-label">Name:</label>
		<div class="controls">
			<input id="LayoutName" type="text"><span style="font-size: 16px; color: #aaa;">.less</span>
			<span class="help-block">Lowercase, no spaces</span>
		</div>
	</div>
	
	</form>
	<!-- /.form-horizontal -->

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="AddLayout" type="button" class="btn btn-primary" value="Add Stylesheet">
  </div>

</div>
<!-- /.modal -->

<div class="modal hide" id="DeleteDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Delete Stylesheet</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">
	
	<p>
		Are you sure that you want to delete <strong id="removeName">this stylesheet</strong>?
	</p>
	
	<input id="DeleteId" type="hidden" value="-1">

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="DeleteAction" type="button" class="btn btn-primary" value="Delete Stylesheet">
  </div>

</div>
<!-- /.modal -->

<?php include 'modules/footer.php'; ?>

</body>

</html>