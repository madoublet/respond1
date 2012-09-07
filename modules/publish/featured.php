<?php 
	// get configurations
	$isvalid = false;
	
	if($type!=null){
		
		$pageType = PageType::GetByPageTypeUniqId($type);
		
		if($pageType!=null){
			$featuredPage = Page::GetFeatured($pageType->SiteId, $pageType->PageTypeId);
			$pageType = PageType::GetByPageTypeId($pageType->PageTypeId);
			$fileId = $featuredPage->ImageFileId;
			$isvalid = true;
		}
		else{
			$isvalid = false;
		}
	}
?>

<?php if($isvalid==true){ ?>
<!-- begin featured -->
<div class="featured<?php if($fileId!=-1){print ' hasImage';} ?>">

<?php if($featuredPage!=null){ ?> 	
	
  	<h2><?php print $featuredPage->Name; ?></h2>
	
		<?php
			$file = File::GetByFileId($fileId);
			
			if($fileId!=-1){
		?>
		
		<span class="featuredImage"><img src="<?php print $imageurl; ?>files/<?php print $file->UniqueName; ?>"></span>
	<?php } ?>
	 
  	<p>
   		<?php print substr(strip_tags(html_entity_decode($featuredPage->Description)), 0, 400); ?>...
	</p>
	
	<input type="button" class="positive" value="View <?php print $pageType->TypeS; ?>" onclick="window.location='<?php print strtolower($pageType->FriendlyId).'/'.$featuredPage->FriendlyId.'.html'; ?>'">
<?php } ?>
 </div>
<?php } ?>