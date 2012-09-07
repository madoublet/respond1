<?php	
	include 'global.php'; // import php files
	include 'actions/Site.php';
	
	$authUser = new AuthUser(); // get auth user
	$authUser->Authenticate('Admin');
	
	$p = new Site($authUser); // setup controller
	
	$currpage = 'details-'.$p->pageType->PageTypeUniqId;
?>
<!DOCTYPE html>
<html>

<head>
	
<title><?php print $p->pageType->TypeS; ?> Details&mdash;<?php print $authUser->OrgName; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- include styles -->
<link href="styles/form.css" type="text/css" rel="stylesheet" media="screen">
<link href="styles/cupertino/jquery-ui-1.8.1.custom.css" type="text/css" rel="stylesheet" media="screen">

<!--[if IE 7]>
<link type="text/css" href="styles/ie7.css" rel="stylesheet" media="screen">
<![endif]-->

<!--[if IE 8]>
<link type="text/css" href="styles/ie8.css" rel="stylesheet" media="screen">
<![endif]-->

<!-- include scripts -->
<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="scripts/global.js"></script>
<script type="text/javascript" src="scripts/menu.js"></script>
<script type="text/javascript" src="scripts/messages.js"></script>
<script type="text/javascript" src="scripts/site.js"></script>

</head>

<body id="details">

<!-- begin global messages -->
<p id="message">
  <span>Holds the message text.</span>
  <a class="close" href="#"></a>
</p>
	
<?php include 'modules/header.php'; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<input type="hidden" name="_submit_check" value="1"/>

<input type="hidden" id="PageId" value="<?php print $p->pageId; ?>">
<input type="hidden" id="PageTypeUniqId" value="<?php print $p->pageType->PageTypeUniqId; ?>">

<?php include 'modules/menu.php'; ?>

<!-- begin content -->
<div id="content">	
	
	<?php if($rolledup==false){ ?>
		<h2>
			<span id="title"><?php print $p->title; ?></span>
			<input id="BackToPages" class="positive back" type="button" value="Return to <?php print $p->pageType->TypeP; ?>" onclick="location.href='pages.php?t=<?php print $p->pageType->PageTypeUniqId; ?>'">
		</h2>
	
		<!-- begin submenu -->
		<ul id="pageMenu" class="submenu">
			<li><a href="details.php?h=<?php print $p->page->PageUniqId; ?>"><?php print $p->pageType->TypeS; ?> Information</a></li>
			<li <?php if($p->pageType->HasForm==0){print 'style="display:none;"';}?>><a href="form.php?h=<?php print $p->page->PageUniqId; ?>">Form</a></li>
			<li <?php if($p->pageType->HasLocations==0){print 'style="display:none;"';}?>><a href="locations.php?h=<?php print $p->page->PageUniqId; ?>">Locations</a></li>
			<li <?php if($p->pageType->HasImages==0){print 'style="display:none;"';}?>><a href="images.php?h=<?php print $p->page->PageUniqId; ?>">Images</a></li>
			<li class="selected"><a href="site.php?h=<?php print $p->page->PageUniqId; ?>">Site</a></li>
		</ul>
		
	<?php }else{ ?>
		<h2><span id="title"><?php print $p->pageType->TypeS; ?> Information</span></h2>
	<?php } ?>
	
	<div class="formGroup <?php if($rolledup==false){ ?>hasSubmenu<?php } ?>">
		
			<?php if($authUser->Role=='Admin'){ ?>
				<span class="field">
					<label for="FriendlyId">Friendly URL:</label>
					<input id="FriendlyId" maxlength="256" type="text" style="width:300px" class="text" value="<?php print $p->friendlyId; ?>">
					<em>e.g.: http://stlbusinessguide.com/company/friendlyurl</em>
				</span>
			
				<span class="field">
					<label for="IsFeatured">Is featured on site?</label>
					<select id="IsFeatured">
						<option value="0" <?php if($p->isFeatured==0){print 'selected';} ?>>No</option>
						<option value="1" <?php if($p->isFeatured==1){print 'selected';} ?>>Yes</option>
					</select>
				</span>
			
				<span class="field">
					<label for="IsActive">Is shown on site?</label>
					<select id="IsActive">
						<option value="1" <?php if($p->isActive==1){print 'selected';} ?>>Yes</option>
						<option value="0" <?php if($p->isActive==0){print 'selected';} ?>>No</option>
					</select>
				</span>
	
			<?php } ?>
	</div>
	
	<p class="actions">
		<input id="UpdateSite" class="positive" type="button" value="Update Site Settings">
	</p>
		
</div>
	
</form>

<?php include 'modules/footer.php'; ?>
	
</body>

</html>