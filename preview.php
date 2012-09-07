<?php 
	
include 'global.php'; // import php files
	
// Preview controller
class Preview extends Actions
{
	function __construct($authUser){
		parent::__construct(); // need to call parent constructor
		
		$pageUniqId = $this->GetQueryString('p');	
		$template = $this->GetQueryString('t');
		
		$preview = true; // set preview
				
		 // generate page
		if($pageUniqId==null || $pageUniqId==''){
			die('Page not found. (error=0)');
		}
		
		$page = Page::GetByPageUniqId($pageUniqId);
		
		if($page==null){
			die('Page not found. (error=1)');
		}
		
		$site = Site::GetBySiteId($page->SiteId);
		
		if($site==null){
			die('Page not found.  (error=2)');
		}
		
		$dir = 'sites/'.$site->FriendlyId.'/';
		$imageurl = $dir.'files/';
		$siteurl = 'http://'.$site->Domain.'/';
		
		$html = Generator::GeneratePage($site, $page, $siteurl, $imageurl, $preview);
		
		die($html);
	}
	
}
	
$authUser = new AuthUser(); // get auth user
$authUser->Authenticate('All'); // validate

$p = new Preview($authUser); // setup controller
		
?>