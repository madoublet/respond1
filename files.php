<?php	
	include 'global.php'; // import php files
	include 'actions/Files.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'files';
	
	$p = new Files($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Files&mdash;<?php print $authUser->SiteName; ?></title>

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
<script type="text/javascript" src="js/ajaxupload.3.1.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/pageTypes.js"></script>
<script type="text/javascript" src="js/files.js"></script>

</head>

<body>
		
<input type="hidden" name="_submit_check" value="1"/>

<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>

<?php include 'modules/menu.php'; ?>
		
<div class="content container-fluid">

  <div class="row-fluid header-row">
    
    <div class="span12">
	
	<h1>
		Files 
		
		<input id="ShowAddDialog" type="button" value="Add File" class="btn btn-primary">
	</h1>

	</div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">
	
	<?php if($p->Count==0){ ?>
		<p class="noResults">
			The are currently no files. Click <strong>Add File</strong> to begin.
		</p>
	<?php } ?>
	
		<input id="PageSize" name="PageSize" value="<?php print $p->PageSize ?>" type="hidden">
	
		<input id="FileUrl" type="hidden" value="<?php print $p->AuthUser->FileUrl; ?>">
		
		<div id="filesList" class="list">

		<?php while($row = mysql_fetch_array($p->List)){ ?>
			<div id="item-<?php print $row['FileUniqId']; ?>" class="listItem hasImage">
				
				<?php if($row['IsImage']==1){ ?>
				<span class="image"><img height="75" width="75" src="<?php print $p->AuthUser->FileUrl.'t-'.$row['UniqueName']; ?>"></span>
				<?php }else{ ?>
					<?php 
						$parts = explode(".", $row['UniqueName']); 
						$ext = end($parts); // get extension
						$ext = strtolower($ext); // convert to lowercase	
					
						if($ext=='pdf'){
							print '<span class="icon filetype pdf"></span>';
						}
						else if($ext=='doc'){
							print '<span class="icon filetype word"></span>';
						}
						else if($ext=='png' || $ext=='jpg' || $ext=='jpeg' || $ext='gif' || $ext='ico'){
							print '<span class="icon filetype image"></span>';
						}
						else{
							print '<span class="icon filetype"></span>';
						}
					?>
				<?php } ?>
				
				<h2 id="name-<?php print $row['FileUniqId']; ?>"><a class="target" href="<?php print $p->AuthUser->FileUrl.$row['UniqueName']; ?>"><?php print $row['UniqueName']; ?></a></h2>
			
				<p class="size">
					<?php print $row['Width'].'px x '.$row['Height'].'px '; ?><br>
					<?php print round(($row['Size']/1024), 2); ?> MB
				</p>
				
				<em>Added <?php print $p->GetFriendlyDate(strtotime($row['Created'])); ?> <?php if($row['FirstName']!=null){ ?>by <?php print $row['FirstName'].' '.$row['LastName']; ?><?php } ?></em>
				
				<a id="remove-<?php print $row['FileUniqId']; ?>" title="Remove <?php print $row['FileName']; ?>" class="remove" href="#"></a>
			</div>
		<?php } ?>
	</div>
	
	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

<div class="modal hide" id="DeleteDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Delete File</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

	<input id="DeleteId" name="DeleteId" value="-1" type="hidden" />
	
	<p>
		Are you sure that you want to delete<br><strong id="removeName"></strong>?
	</p>
	
	<p>
		This will completely remove it from the system.
	</p>
	
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="DeleteFile" type="button" class="btn btn-primary" value="Delete File">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->

<div class="modal hide" id="AddFileDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Add File</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">
	
	<a class="close" href="#"></a>
	
	<p>
		Choose the file you want to upload.  You can select .css, .js., .doc, .docx, .pdf, .png, .jpg, .ico, and .gif files.
	</p>

	<p>
		<input id="Upload" type="button" value="Upload File" class="btn btn-primary">
	</p>
	
  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->
<?php include 'modules/footer.php'; ?>

</body>

</html>