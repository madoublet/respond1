<!-- begin footer -->
<footer>
	<ul class="menu">
		<li<?php if($currpage=='site'){print ' class="selected"';} ?>><a href="pages.php">Pages</a></li>
		<li<?php if($currpage=='menu'){print ' class="selected"';} ?>><a href="menu.php">Menus</a></li>
		<li<?php if($currpage=='template'){print ' class="selected"';} ?>><a href="templates.php">Design</a></li>
		<li<?php if($currpage=='users' || $currpage=='profile'){print ' class="selected"';} ?>><a href="users.php">Users</a></li>
		<li<?php if($currpage=='settings'){print ' class="selected"';} ?>><a href="settings.php">Settings</a></li>
		<li<?php if($currpage=='files'){print ' class="selected"';} ?>><a href="files.php">Files</a></li>
		<li><a href="logout.php">Logout</a></li>
	</ul>
</footer>