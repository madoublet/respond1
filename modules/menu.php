<?php
	$rolledup = false;
	
	$menu = $_SESSION['Menu'];
	$rolledup = $_SESSION['RolledUp'];
	
	if($authUser->Role=='Admin'){
		$types = PageType::GetPageTypes($authUser->SiteId);
	}
 ?>

<header>

	<img class="logo" src="images/respond-app-logo.png" alt="Respond">

	<ul class="menu">
	<?php if($authUser->IsSuperAdmin==true){ ?>
		<li<?php if($currpage=='sites'){print ' class="selected"';} ?>><a href="sites.php">Sites</a></li>
	<?php } ?>	
		<li<?php if($currpage=='site'){print ' class="selected"';} ?>><a href="pages.php">Pages</a></li>
		<li<?php if($currpage=='menu'){print ' class="selected"';} ?>><a href="menu.php">Menus</a></li>
		<li<?php if($currpage=='template'){print ' class="selected"';} ?>><a href="templates.php">Design</a></li>
		<li<?php if($currpage=='users' || $currpage=='profile'){print ' class="selected"';} ?>><a href="users.php">Users</a></li>
		<li<?php if($currpage=='settings'){print ' class="selected"';} ?>><a href="settings.php">Settings</a></li>
	</ul>

<?php if($authUser->Role=='Demo'){ ?>
	<span class="demo-mode">Demo Mode</span>
<?php } ?>

	<ul class="menu menu-secondary">
		<li<?php if($currpage=='files'){print ' class="selected"';} ?>><a href="files.php">Files</a></li>
		<li><a href="logout.php">Logout</a></li>
	</ul>
</header>