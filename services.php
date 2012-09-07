<?php
	include 'global.php'; // import php files

	$type = Utilities::GetQueryString('type');

	if($type=='sendfeedback'){

		// get qs variables
		$body = Utilities::GetQueryString('body');
		$siteUniqId = Utilities::GetQueryString('siteUniqId');
		$pageUniqId = Utilities::GetQueryString('pageUniqId');

		// get page and site
		$page = Page::GetByPageUniqId($pageUniqId);
		$site = Site::GetBySiteUniqId($siteUniqId);

		// create subject
		$subject = $page->Name;

		// add the feedback
		$body = html_entity_decode($body);	

		$json = '{"subject":"'.$subject.'","email":"'.$site->PrimaryEmail.'"}';

		header('Content-type: application/json');
		echo $json;
	}

	if($type=='gettotalpages'){
		$siteUniqId = Utilities::GetQueryString('siteUniqId');
		$pageTypeUniqId = Utilities::GetQueryString('pageTypeUniqId');

		$site = Site::GetBySiteUniqId($siteUniqId);
		$pageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);

		// Get all pages
		$total = Page::GetTotalPagesForService($site->SiteId, $pageType->PageTypeId);
		
		$json = '{"total":"'.$total.'"}';

		header('Content-type: application/json');
		echo $json;
	}


	if($type=='getlist'){

		$siteUniqId = Utilities::GetQueryString('siteUniqId');
		$display = Utilities::GetQueryString('display');
		$pageTypeUniqId = Utilities::GetQueryString('pageTypeUniqId');
		$pageSize = Utilities::GetQueryString('pageSize');
		$orderBy = Utilities::GetQueryString('orderBy').' ASC';
		$page = Utilities::GetQueryString('page');
		$rootloc = Utilities::GetQueryString('rootloc');

		if($pageSize==''){
			$pageSize = 10;
		}

		$site = Site::GetBySiteUniqId($siteUniqId);
		$pageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);


		$dest = 'sites/'.$site->FriendlyId;
		
		// Get all pages
		$list = Page::GetPagesForService($site->SiteId, $pageType->PageTypeId, $orderBy, $pageSize, $page);
		
		$pages = array();
		
		while($row = mysql_fetch_array($list)){

			$page = Page::GetByPageId($row['PageId']);

			$name = $row['FirstName'].' '.$row['LastName'];
			
			// get category
			$categoryUniqId = '-1';
			$categoryName = $row['CategoryName'];
			
			// get image url
			$thumbUrl = '';
			$imageUrl = '';
			$mImageUrl = '';
			
			if($page->ImageFileId!=-1){
				$file = File::GetByFileId($page->ImageFileId);
				
				$imageUrl = 'files/'.$file->UniqueName;
				$mImageUrl = 'm/files/'.$file->UniqueName;
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
					'CategoryUniqId' => $categoryUniqId,
					'CategoryName' => $categoryName,
					'Image' => $imageUrl,
					'MobileImage' => $mImageUrl,
					'Thumb' => $thumbUrl,
					'LastModified' => $page->LastModifiedDate,
					'Author' => $name
				);
				
			if($display=='blog'){
				$item['Content'] = htmlentities(Generator::ParseBlogHTML($siteUniqId, $page->PageUniqId, $page->Content, $rootloc));
			}
				
			$pages[$page->PageUniqId] = $item;
		}

		$json = json_encode($pages);

		header('Content-type: application/json');
		echo $json;
	}

?>