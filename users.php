<?php	
	include 'global.php'; // import php files
	include 'actions/Users.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('All');
	
	$currpage = 'users';
	
	$p = new Users($authUser); // setup controller
?>
<!DOCTYPE html>
<html>

<head>
	
<title>Users&mdash;<?php print $authUser->SiteName; ?></title>

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
<script type="text/javascript" src="js/users.js"></script>

</head>

<body>

<?php include 'modules/menu.php'; ?>

<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>
		
<div class="content container-fluid">

  <div class="row-fluid header-row">
    
    <div class="span12">	

	<h1>
		Users
		<button id="ShowAddDialog" class="btn btn-primary">Add User</button>	
	</h1>

    </div>
	<!-- /.span12 -->

  </div>
  <!-- /.row-fluid -->

  <div class="row-fluid">
    <div class="span12">

	<div id="usersList" class="list">
		<?php while($row = mysql_fetch_array($p->List)){ ?>
			<div id="user-<?php print $row['UserUniqId']; ?>" class="listItem">
				<a id="remove-<?php print $row['UserUniqId']; ?>" class="remove" href="#"></a>
				<h2><a id="edit-<?php print $row['UserUniqId']; ?>" href="#" class="edit"><?php print $row['FirstName']; ?> <?php print $row['LastName']; ?></a></h2>
				<p>
					<?php print $row['Role']; ?>
				</p>
				<input id="UserUniqId-<?php print $row['UserUniqId']; ?>" type="hidden" value="<?php print $row['UserUniqId']; ?>">
				<input id="FirstName-<?php print $row['UserUniqId']; ?>" type="hidden" value="<?php print $row['FirstName']; ?>">
				<input id="LastName-<?php print $row['UserUniqId']; ?>" type="hidden" value="<?php print $row['LastName']; ?>">
				<input id="Role-<?php print $row['UserUniqId']; ?>" type="hidden" value="<?php print $row['Role']; ?>">
				<input id="Email-<?php print $row['UserUniqId']; ?>" type="hidden" value="<?php print $row['Email']; ?>">
				<em>Created <?php print $p->GetFriendlyDate(strtotime($row['Created'])); ?></em>
			</div>
		<?php } ?>
	</div>
	
	</div>
	<!-- /.span12 -->	
	
  </div>
  <!-- /.row-fluid -->

</div>
<!-- /.content -->

<div class="modal hide" id="AddEditDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3 id="AddEditTitle">Add User</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">

  	<form class="form-horizontal">

		<input id="UserUniqId" type="hidden" value="-1">

		<div class="control-group">
			<label for="FirstName" class="control-label">First Name:</label>
			<div class="controls">
				<input id="FirstName" type="text" value="<?php print $p->FirstName ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label for="LastName" class="control-label">Last Name:</label>
			<div class="controls">
				<input id="LastName" type="text" value="<?php print $p->LastName ?>">
			</div>
		</div>

		<div class="control-group">
			<label for="Role" class="control-label">Role:</label>
			<div class="controls">
				<select id="Role">
					<option value="Admin" <?php if($p->Role=='Admin'){ print 'selected';} ?>>Administrator</option>
					<option value="Demo" <?php if($p->Role=='Demo'){ print 'selected';} ?>>Demo</option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="Email" class="control-label">Email:</label>
			<div class="controls">
				<input id="Email" type="text" value="<?php print $p->Email ?>">
				<span class="help-block">Also used as the login</span>
			</div>
		</div>
	
		<div class="control-group">
			<label for="Password" class="control-label">Password:</label>
			<div class="controls">
				<input id="Password" type="password" value="<?php print $p->Password ?>">
				<span class="help-block">More than 5 characters, 1 letter and 1 special character</span>
			</div>
		</div>
		
		<div class="control-group">
			<label for="Retype" class="control-label">Retype Password:</label>
			<div class="controls">
				<input id="Retype" type="password" value="<?php print $p->Password ?>">
			</div>
		</div>
		
	</form>
	<!-- /.form-horizontal -->

	</div>
	<!-- /.modal-body -->

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<input id="AddEditAction" type="button" class="btn btn-primary" value="Add User">
	</div>

</div>
<!-- /.modal -->

<div class="modal hide" id="DeleteDialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Delete User</h3>
  </div>
  <!-- /.modal-header -->

  <div class="modal-body">
	
	<p>
		Are you sure that you want to delete <strong id="removeName">this user</strong>?
	</p>
	
	<input id="DeleteId" type="hidden" value="-1">
	
	</div>
	<!-- /.modal-body -->

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<input id="DeleteAction" type="button" class="btn btn-primary" value="Delete User">
	</div>

</div>
<!-- /.modal -->

</form>

<?php include 'modules/footer.php'; ?>

</body>

</html>