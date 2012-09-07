<?php	
	include 'global.php'; // import php files
	include 'actions/Pages.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$p = new Pages($authUser); // setup controller
	
	$currpage = 'site';
	
?>
<!DOCTYPE html>
<html>

<head>
	
<title><?php print $p->PageType->TypeP; ?>&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/page.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/pages.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">
<link type="text/css" href="css/templates.css" rel="stylesheet">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/pages.js"></script>
</head>

<body>
	
<input type="hidden" name="_submit_check" value="1"/>

<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>
	
<?php include 'modules/menu.php'; ?>
	

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			
<div class="content container-fluid">
  <div class="row-fluid header-row">
    <div class="span12">	
		
	<input id="PageTypeUniqId" type="hidden" value="<?php print $p->PageType->PageTypeUniqId ?>">
	<input id="PageTypeS" type="hidden" value="<?php print $p->PageType->TypeS; ?>">
	
	<h1>
		Pages
		<button id="ShowAddDialog" class="btn btn-primary">Add <?php print $p->PageType->TypeS; ?></button>
	</h1>
	
	<ul class="nav nav-tabs">
	<?php 
	$i = 0;
	while($row = mysql_fetch_array($p->PageTypes)){

		$name =  $row['TypeP'];

		 ?>

		<?php if($p->PageType->PageTypeUniqId==$row['PageTypeUniqId']){?>
			<li class="active">
		<?php }else{ ?>	
		  <li>
		<?php } ?>  
		<?php if($i==0){ ?>
		<a href="pages.php"><?php print $name; ?></a>
		<?php }else{ ?>
		<a href="pages.php?t=<?php print $row['PageTypeUniqId']; ?>"><?php print $name; ?></a>
		<?php } ?>
		</li>
	<?php 
		$i = $i+1;
	} ?>	
	</ul>
	<!-- /.nav -->
	</div>
  </div>

  <div class="row-fluid">
    <div class="span12">
	<div id="pagesList" class="list">
	<?php if($p->IsAdmin==true && $p->IsDefault==true){ ?>
		<div id="item-home" class="listItem">
			<h2><a href="content.php?m=home">Home</a></h2>
		<?php if($p->HomeDesc!=""){ ?>	
			<p id="home-desc"><?php print substr(strip_tags(html_entity_decode($p->HomeDesc)), 0, 300);?></p>
		<?php } ?>	
			<em>Last updated <?php print $p->GetFriendlyDate(strtotime($p->HomeLastModifiedDate)); ?> <?php if($p->HomeLastModifiedName!=''){ ?>by <?php print $p->HomeLastModifiedName; ?><?php } ?></em>
		</div>
	<?php } ?>
	<?php while($row = mysql_fetch_array($p->List)){ ?>
	
		<div id="item-<?php print $row['PageUniqId']; ?>" class="listItem">
			
			<a id="remove-<?php print $row['PageUniqId']; ?>" title="Remove <?php print $row['Name']; ?>" class="remove" href="#"></a>
			<h2>
				<a id="edit-<?php print $row['PageUniqId']; ?>" href="content.php?p=<?php print $row['PageUniqId']; ?>" class="edit"><?php print $row['Name']; ?></a>
			</h2>
	
		<?php if($row["Description"]!=""){ ?>
			<p id="description-<?php print $row['PageUniqId']; ?>"><?php print substr(strip_tags(html_entity_decode($row["Description"])), 0, 300); ?><?php if(strlen($row['Description'])>300){print '...';} ?></p>
		<?php } ?>

			<em>
				Last updated <?php print $p->GetFriendlyDate(strtotime($row['LastModifiedDate'])); ?> <?php if($row['FirstName']!=null){ ?>by <?php print $row['FirstName'].' '.$row['LastName']; ?><?php } ?>.
			</em>
			
			<?php if($p->IsAdmin==true){ ?>			
			<span id="status-<?php print $row['PageUniqId']; ?>" data-isactive="<?php print $row['IsActive']; ?>" class="status <?php if($row['IsActive']==1){print 'published';} else{print 'not-published';} ?>" title="Toggle publish status"></span>
			<?php } ?>
		</div>
		<!-- / .listItem -->
	<?php } ?>
	
	</div>
		
	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

</form>


<div class="modal hide" id="AddDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Add <?php print $p->PageType->TypeS; ?></h3>
  </div>
  <div class="modal-body">
  	<form class="form-horizontal">
	<fieldset>
	
	<div class="control-group">
		<label for="Name" class="control-label">Name:</label>
		<div class="controls">
			<input id="Name" type="text" value="" class="span3" maxlength="255">
		</div>
	</div>
	
	</fieldset>
	<fieldset>
	
	<div class="control-group">
		<label for="URL" class="control-label">Friendly URL:</label>
		<div class="controls">
			http://<?php print $p->SiteUrl; ?>/<?php print strtolower($p->TypeS); ?>/<input id="FriendlyId" type="text" maxlength="128" class="span2" value="" placeholder="page-name">
			<span class="help-block">No spaces, no special characters, dashes allowed.</span>
		<div>
	</div>

	</fieldset>

	<fieldset>

	<div class="control-group">
		<label for="Description" class="control-label">Description:</label>
		<div class="controls">
			<textarea id="Description" class="span3"></textarea>
		</div>
	</div>
	
	</fieldset>
		
	</form>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" class="btn btn-primary" id="AddAction">Add <?php print strtolower($p->PageType->TypeS); ?></a>
  </div>

</div>
<!-- /.modal -->


<div class="modal hide" id="DeleteDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Delete <?php print $p->PageType->TypeS; ?></h3>
  </div>
  <div class="modal-body">
	
	<p>
		Are you sure that you want to delete <strong id="removeName">this page</strong>?
	</p>
	
	<input id="DeleteId" type="hidden" value="-1">
	
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="#" class="btn btn-primary" id="DeleteAction">Delete <?php print strtolower($p->PageType->TypeS); ?></a>
	</div>

</div>
<!-- /.modal -->
	
<?php include 'modules/footer.php'; ?>

</body>

</html>