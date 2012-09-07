<header>
	<h1><a href="<?php print $siteurl; ?>" id="logo" alt="<?php print $site->Name; ?>">
<?php 
	if($site->LogoUrl==''){
		print $site->Name;	
	}
	else{
		print '<img src="sites/'.$site->FriendlyId.'/files/'.$site->LogoUrl.'">';
	}
?>
	</a></h1>

	<?php include 'modules/preview/menu.php'; ?>
</header>

<p id="message">
  <span></span>
  <a class="close" href="#"></a>
</p>