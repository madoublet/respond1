<?php	
	include 'global.php'; // import php files
	include 'actions/Menu.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'menu';
	
	$p = new Menu($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Menu&mdash;<?php print $authUser->SiteName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include css -->
<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link type="text/css" href="css/app.css" rel="stylesheet">
<link type="text/css" href="css/page.css" rel="stylesheet">
<link type="text/css" href="css/messages.css" rel="stylesheet">
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/pages.css" rel="stylesheet">
<link type="text/css" href="css/list.css" rel="stylesheet">
<link type="text/css" href="css/menuItems.css" rel="stylesheet">

<!-- include js -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/menuItems.js"></script>
</head>

<body>

	
<input type="hidden" id="type" name="type" value="<?php print $p->Type; ?>">

<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>
	
<?php include 'modules/menu.php'; ?>
			
<!-- begin content -->
<div class="content container-fluid">
  <div class="row-fluid header-row">
    <div class="span12">	

	<h1>
		Menus
		<button id="ShowAddDialog" class="btn btn-primary">Add Menu Item</button>
	</h1>
		
	<ul class="nav nav-tabs">
		<li <?php if($p->Type=='primary'){ ?>class="active"<?php } ?>><a href="menu.php">Primary</a></li>
		<li <?php if($p->Type=='footer'){ ?>class="active"<?php } ?>><a href="menu.php?t=footer">Footer</a></li>
	<?php while($row = mysql_fetch_array($p->MenuTypes)){ ?>
		<li <?php if($p->Type==$row['FriendlyId']){ ?>class="active"<?php } ?>><a href="menu.php?t=<?php print $row['FriendlyId']; ?>"><?php print $row['Name']; ?></a></li>
	<?php } ?>
	</ul>

	</div>
  </div>

  <div class="row-fluid">
    <div class="span12">

	<div id="menuItemsList" class="list">
			
	<?php while($row = mysql_fetch_array($p->List)){ ?>

		<!-- begin page -->
		<div id="item-<?php print $row['MenuItemUniqId']; ?>" class="listItem sortable">
			
			<a id="remove-<?php print $row['MenuItemUniqId']; ?>" class="remove" href="#"></a>
			<input id="pageId-<?php print $row['MenuItemUniqId']; ?>" value="<?php print $row['PageId']; ?>" type="hidden">
			<span class="hook"></span>
		
			<h2><a id="name-<?php print $row['MenuItemUniqId']; ?>" href="#" class="edit"><?php print $row['Name']; ?></a></h2>
		

			<input id="link-<?php print $row['MenuItemUniqId']; ?>" value="<?php print $row['Url']; ?>" type="hidden">
			<input id="cssclass-<?php print $row['MenuItemUniqId']; ?>" value="<?php print $row['CssClass']; ?>" type="hidden">
		</div>
		<!-- end pages -->
	<?php } ?>
		</div>
		
	</div>
		

	</div>		
</div>
<!-- end content -->

<div id="actions" class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
		<button id="Save" class="btn btn-primary" type="button">Save</button>
	</div>
  </div>
</div>

<!-- begin add/edit dialog -->
<div class="modal hide" id="AddEditDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3 id="AddEditTitle">Add Menu</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  	<form class="form-horizontal">

		<input id="MenuItemUniqId" type="hidden" value="-1">
		<input id="PageId" type="hidden" value="-1">
		<input id="ExistingUrl" type="hidden" value="-1">
		
		<div class="control-group">
			<label for="Name" class="control-label">Link Label:</label>
			<div class="controls">
				<input id="Name" type="text" value="" maxlength="140">
			</div>
		</div>

		<div class="control-group">
			<label for="CssClass" class="control-label">CSS Class:</label>
			<div class="controls">
				<input id="CssClass" type="text" value="" maxlength="140">
			</div>
		</div>
		
		<input id="DialogMode" type="hidden" value="edit">
		
		<div id="editUrl">
			
		<div class="control-group">
			<label for="EditUrl" class="control-label">Url:</label>
			<div class="controls">
				<input id="EditUrl" value="" maxlength="140">
			</div>
		</div>
			
		</div>
		<!-- /#editUrl -->
		
		<div id="addUrl">
		<div class="control-group radio-header">
			<label class="radio"><input id="Existing" type="radio" name="content" checked> Existing Page</label>
		</div>	
		
		<div class="control-group">
			<div class="controls">
				<div id="selectPage" class="select">
				<ul>
					<li data-pageid="-1" data-url="">Home</li>
					<?php 
					mysql_data_seek($p->PageTypes, 0);
					while($row = mysql_fetch_array($p->PageTypes)){
					 
						$hlist = Page::GetPagesForPageType($p->AuthUser->SiteId, $row['PageTypeId']);
							
						while($hrow = mysql_fetch_array($hlist)){?>
							
						<li data-pageid="<?php print $hrow['PageId']?>" data-url="<?php print strtolower($row['FriendlyId']); ?>/<?php print strtolower($hrow['FriendlyId']); ?>"><?php print $hrow['Name'] ?></li>
							
						<?php }  ?>
					<?php } ?>
				
				
					<?php while($row = mysql_fetch_array($p->PageTypes)){ ?>
						<li id="<?php print $row['PageTypeUniqId']; ?>"><?php print $row['TypeP']; ?></li>
					<?php } ?>
				</ul>
				</div>
			</div>
		</div>
		
		<div class="control-group radio-header">
			<label class="radio"><input id="CustomUrl" type="radio" name="content"> Custom URL</label>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<input id="Url" type="text">
			</div>
		</div>

		</div>
		<!-- /#addUrl -->

	</form>
	<!-- /.form-horizontal -->

  </div>
  <!-- /.modal-body -->

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
	<input id="AddEditAction" type="button" class="btn btn-primary" value="Add Menu Item">
  </div>
  <!-- /.modal-footer -->

</div>
<!-- /.modal -->
	
<?php include 'modules/footer.php'; ?>

</body>

</html>