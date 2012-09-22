<?php 

class Publish
{
	// publishes the entire site
	public static function PublishSite($siteUniqId){
		
		// publish sitemap
		Publish::PublishSiteMap($siteUniqId);
		
		// publish all CSS
		Publish::PublishAllCSS($siteUniqId);	

		// publish all pages
		Publish::PublishAllPages($siteUniqId);

		// publish rss for page types
		Publish::PublishRssForPageTypes($siteUniqId);
		
		// publish menu
		Publish::PublishMenu($siteUniqId);
		
		// publish common js
		Publish::PublishCommonJS($siteUniqId);
		
		// publish common css
		Publish::PublishCommonCSS($siteUniqId);
		
		// publish common images
		Publish::PublishCommonImages($siteUniqId);
		
		// publish template images
		Publish::PublishTemplateImages($siteUniqId);
		
		// publish controller
		Publish::PublishController($siteUniqId);

		// publish plugins
		Publish::PublishPlugins($siteUniqId);
	}
	
	// publishes the controller
	public static function PublishController($siteUniqId){
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$src = 'sites/common/controller.php';
		$dest = 'sites/'.$site->FriendlyId.'/controller.php';
		
		copy($src, $dest); // copy the controller
		
		$src = 'sites/common/.htaccess';
		$dest = 'sites/'.$site->FriendlyId.'/.htaccess';
		
		copy($src, $dest); // copy the controller
	}

	// publishes plugins
	public static function PublishPlugins($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		// create plugin directory
		$dest = 'sites/'.$site->FriendlyId.'/plugins';
		
		// create dir if it doesn't exist
		if(!file_exists($dest)){
			mkdir($dest, 0777, true);	
		}
		
		$json = file_get_contents('plugins/plugins.json');
		$data = json_decode($json, true);

		foreach($data as &$item) {
			$type = $item['type'];

			$p_src = 'plugins/'.$type.'/deploy';

			if(file_exists($p_src)){

				$p_dest = 'sites/'.$site->FriendlyId.'/plugins/'.$type;

				if(!file_exists($p_dest)){
					mkdir($p_dest, 0777, true);	
				}

				Utilities::CopyDirectory($p_src, $p_dest);
			}

		}
		
	}

	// publishes a template
	public static function PublishTemplate($site, $template){

		$template_dir = 'sites/'.$site->FriendlyId.'/templates/';
		$src = 'templates/'.$template.'/';
		$dest = 'sites/'.$site->FriendlyId.'/templates/'.$template.'/';

		if(!file_exists($template_dir)){
			mkdir($template_dir, 0777, true);	
		}

		Utilities::CopyDirectory($src, $dest);
	}
	
	// publishes common folder (during enrollment)
	public static function PublishCommonForEnrollment($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		// publish files
		$src = 'templates/common/files';
		$dest = 'sites/'.$site->FriendlyId.'/files';
		
		// create dir if it doesn't exist
		if(!file_exists($dest)){
			mkdir($dest, 0777, true);	
		}
		
		// copies a directory
		Utilities::CopyDirectory($src, $dest);
		
	}
	
	// publishes common js
	public static function PublishCommonJS($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$src = 'sites/common/js';
		$dest = 'sites/'.$site->FriendlyId.'/js';
		
		// create dir if it doesn't exist
		if(!file_exists($dest)){
			mkdir($dest, 0777, true);	
		}
		
		// copies a directory
		Utilities::CopyDirectory($src, $dest);
	}
	
	// publishes common css
	public static function PublishCommonCSS($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$src = 'sites/common/css';
		$dest = 'sites/'.$site->FriendlyId.'/css';
		
		// create dir if it doesn't exist
		if(!file_exists($dest)){
			mkdir($dest, 0777, true);	
		}
		
		// copies a directory
		Utilities::CopyDirectory($src, $dest);
	}
	
	// publishes common images
	public static function PublishCommonImages($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$src = 'sites/common/images';
		$dest = 'sites/'.$site->FriendlyId.'/images';
		
		// create dir if it doesn't exist
		if(!file_exists($dest)){
			mkdir($dest, 0777, true);	
		}
		
		// copies a directory
		Utilities::CopyDirectory($src, $dest);
		
	}
	
	// publishes template images
	public static function PublishTemplateImages($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$src = 'templates/'.$site->Template.'/images';
		$dest = 'sites/'.$site->FriendlyId.'/images';
		
		// create dir if it doesn't exist
		if(!file_exists($dest)){
			mkdir($dest, 0777, true);	
		}
		
		// copies a directory
		Utilities::CopyDirectory($src, $dest);
	
	}
	
	// publishes all the pages in the site
	public static function PublishAllPages($siteUniqId){
	
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		// Get all pages
		$list = Page::GetPagesForSite($site->SiteId);
		
		while($row = mysql_fetch_array($list)){
			Publish::PublishPage($row['PageUniqId']);
		}
	}
	
	// publish menu
	public static function PublishMenu($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$list = MenuItem::GetMenuItems($site->SiteId);
		
		$menu = array();
		$count = 0;
		
		while($row = mysql_fetch_array($list)){

			if($row['PageId']!=-1){
			
				$page = Page::GetByPageId($row['PageId']);

				if($page != null){
					$friendlyId = $page->FriendlyId;
					$pageUniqId = $page->PageUniqId;

					if($page->PageTypeId!=-1){
						$pageType = PageType::GetByPageTypeId($page->PageTypeId);
						$typeS = $pageType->TypeS;
						$typeP = $pageType->TypeP;
					}
					else{
						$typeS = 'Home';
						$typeP = 'Home';
					}
				}
				else{
					$friendlyId = -1;
					$pageUniqId = -1;
					$typeS = 'External';
					$typeP = 'External';
				}
			}
			else{
				$friendlyId = -1;
				$pageUniqId = -1;
				$typeS = 'External';
				$typeP = 'External';
			}

			$item = array(
					'MenuItemUniqId' => $row['MenuItemUniqId'],
				    'Name'  => $row['Name'],
				    'CssClass'  => $row['CssClass'],
				    'Type' => $row['Type'],
					'Url' => $row['Url'],
					'PageId' => $row['PageId'],
					'FriendlyId' => $friendlyId,
					'PageUniqId' => $pageUniqId,
					'TypeS' => $typeS,
					'TypeP' => $typeP
				);
			$menu[$count] = $item;	
			$count = $count + 1;
		}
		
		// encode to json
		$encoded = json_encode($menu);

		$dest = 'sites/'.$site->FriendlyId.'/data/';
		
		Utilities::SaveContent($dest, 'menu.json', $encoded);
	}
	
	// publish json for all page types
	public static function PublishJsonForPageTypes($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$list = PageType::GetPageTypes($site->SiteId);
		
		while($row = mysql_fetch_array($list)){
			Publish::PublishJsonForPageType($siteUniqId, $row['PageTypeId']);
		}
	}
	
	// publish json for a page type
	public static function PublishJsonForPageType($siteUniqId, $pageTypeId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$dest = 'sites/'.$site->FriendlyId;
		
		$pageType = PageType::GetByPageTypeId($pageTypeId);
		
		// Get all pages
		$list = Page::GetPagesForPageType($site->SiteId, $pageTypeId);
		
		$pages = array();
		
		while($row = mysql_fetch_array($list)){
			
			$page = Page::GetByPageId($row['PageId']);
			$name = $row['FirstName'].' '.$row['LastName'];
			
	
			// get image url
			$thumbUrl = '';
			$imageUrl = '';

			if($page->ImageFileId!=-1){
				$file = File::GetByFileId($page->ImageFileId);
				
				$imageUrl = 'files/'.$file->UniqueName;
				$thumbUrl = 'files/t-'.$file->UniqueName;
			}
			
			$url = strtolower($pageType->TypeS).'/'.$page->FriendlyId;
			
			$item = array(
				    'PageUniqId'  => $page->PageUniqId,
					'Name' => $page->Name,
					'Description' => $page->Description,
					'Location' => $page->Location,
					'Callout' => $page->Callout,
					'Url' => $url,
					'Image' => $imageUrl,
					'Thumb' => $thumbUrl,
					'LastModified' => $page->LastModifiedDate,
					'Author' => $name
				);
				
			$pages[$page->PageUniqId] = $item;
		}
		
		$encoded = json_encode($pages);
		
		Utilities::SaveContent($dest.'/data/', strtolower($pageType->TypeP).'.json', $encoded);
	}
	
	// publish rss for all page types
	public static function PublishRssForPageTypes($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$list = PageType::GetPageTypes($site->SiteId);
		
		while($row = mysql_fetch_array($list)){
			Publish::PublishRssForPageType($siteUniqId, $row['PageTypeId']);
		}
	}
	
	// publish rss for pages
	public static function PublishRssForPageType($siteUniqId, $pageTypeId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$dest = 'sites/'.$site->FriendlyId;
		
		$pageType = PageType::GetByPageTypeId($pageTypeId);
		
		// generate rss
		$rss = Generator::GenerateRSS($site, $pageType);
		
		Utilities::SaveContent($dest.'/data/', strtolower($pageType->TypeP).'.xml', $rss);
	}
	
	// publish sitemap
	public static function PublishSiteMap($siteUniqId){
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$dest = 'sites/'.$site->FriendlyId;
		
		// generate default site map
		$content = Generator::GenerateSiteMap($site);
		
		Utilities::SaveContent($dest.'/', 'sitemap.xml', $content);
	}
	
	// publishes a specific css file
	public static function PublishCSS($site, $name){
	
		// get references to file
	    $lessDir = 'sites/'.$site->FriendlyId.'/templates/'.$site->Template.'/less/';
	    $cssDir = 'sites/'.$site->FriendlyId.'/css/';

	    $lessFile = $lessDir.$name.'.less';
	    $cssFile = $cssDir.$name.'.css';

	    // create css directory (if needed)
	    if(!file_exists($cssDir)){
			mkdir($cssDir, 0777, true);	
		}

	    if(file_exists($lessFile)){
	    	$content = file_get_contents($lessFile);

	    	$less = new lessc;

	    	try{
			  $less->checkedCompile($lessFile, $cssFile);

			  return true;
			} 
			catch(exception $e){
			  return false;
			}
    	}
    	else{
    		return false;
    	}

	}

	
	// publishes all css
	public static function PublishAllCSS($siteUniqId){

		$site = Site::GetBySiteUniqId($siteUniqId); // test for now

		$lessDir = 'sites/'.$site->FriendlyId.'/templates/'.$site->Template.'/less/';
		
		//get all image files with a .less ext
		$files = glob($lessDir . "*.less");

		//print each file name
		foreach($files as $file){
			$f_arr = explode("/",$file);
			$count = count($f_arr);
			$filename = $f_arr[$count-1];
			$name = str_replace('.less', '', $filename);

			Publish::PublishCSS($site, $name);
		}

	}

	// publishes a fragment
	public static function PublishFragment($siteFriendlyId, $pageUniqId, $status, $content){

		// clean content
		$content = str_replace( "&nbsp;", '', $content);

		$dir = 'sites/'.$siteFriendlyId.'/fragments/'.$status.'/';

		if(!file_exists($dir)){
			mkdir($dir, 0777, true);	
		}

		// create fragment
		$fragment = 'sites/'.$siteFriendlyId.'/fragments/'.$status.'/'.$pageUniqId.'.html';
		file_put_contents($fragment, $content); // save to file
	}

	// publishes a page
	public static function PublishPage($pageUniqId){
	
		$page = Page::GetByPageUniqId($pageUniqId);
		
		if($page!=null){
			
			$site = Site::GetBySiteId($page->SiteId); // test for now
			$dest = 'sites/'.$site->FriendlyId.'/';
			$imageurl = $dest.'files/';
			$siteurl = 'http://'.$site->Domain.'/';
			
			$friendlyId = $page->FriendlyId;
			
			$url = '';
			$file = '';
			
			// create a nice path to store the file
			if($page->PageTypeId==-1){
				$url = 'index.php';
				$file = 'index.php';	
				$path = '';
			}
			else{
				$pageType = PageType::GetByPageTypeId($page->PageTypeId);
				
				$path = 'uncategorized';
				
				if($pageType!=null){
					$path = strtolower($pageType->FriendlyId);
				}
					
		 	  	$url = $path.'/'.$page->FriendlyId.'.php';
		 	  	$file = $page->FriendlyId.'.php';
			}
		
			// generate default
			$html = Generator::GeneratePage($site, $page, $siteurl, $imageurl, false);
			
			$s_dest = $dest.$path.'/';
			
			Utilities::SaveContent($s_dest, $file, $html);
		}
	}
}

?>