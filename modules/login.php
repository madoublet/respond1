<form id="LoginForm" action="login.php" method="post">
		
<input type="hidden" name="_submit_check" value="1"/>

<?php if(isset($_SESSION['Name'])){ ?>

<div id="loggedIn">
	<em>Hello <a id="profileLink" href="profile.php?u=<?php print $authUser->UserUniqId; ?>"><?php print $_SESSION['FirstName']; ?> <?php print $_SESSION['LastName']; ?></a>.</em> <a href="files.php">Files</a> <a href="logout.php">Logout</a>
</div>

<?php } ?>

</form>
