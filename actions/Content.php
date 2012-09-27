<?php

// Content controller
class Content extends Actions
{
	public $AuthUser;
	public $Ajax;
	public $Page;
	public $Title = '';
	public $PageId = '-1';
	public $PageUniqId = '-1';
	public $PageTypeUniqId = '-1';
	public $FriendlyId = '-1';
	public $Filter = '';
	public $Name = '';
	public $Description = '';
	public $Rss = '';
	public $Layout = '';
	public $Stylesheet = '';
	public $Layouts;
	public $Stylesheets;
	public $Keywords = '';
	public $Content = '';
	public $SiteUrl;
	public $TypeS;
	public $IsActive = 0;
	public $IsFeatured = 0;
	public $LockUploads = 0;
	public $Site;
	public $PageType;
	public $IsAdmin = true;
	public $CategoryId = -1;
	public $CategoryName = '';
	public $Categories;
	public $PageTypes;
	
	function __construct($authUser){
		
		parent::__construct(); // need to call parent constructor
		
		$this->AuthUser = $authUser;
		
		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='content.update'){
			$this->Update();
		}
		
		if($this->Ajax=='content.upload'){
			$this->UploadImage();
		}
		
		if($this->Ajax=='content.crop'){
			$this->Crop();
		}
		
		if($this->Ajax=='content.getExisting'){
			$this->GetExisting();
		}
		
		if($this->Ajax=='content.setFeatured'){
			$this->SetFeatured();
		}
		
		if($this->Ajax=='content.saveDraft'){
			$this->SaveDraft();
		}
		
		if($this->Ajax=='content.getLayout'){
			$this->GetLayout();
		}

		if($this->Ajax=='content.getCategories'){
			$this->GetCategories();
		}

		if($this->Ajax=='content.updateSettings'){
			$this->UpdateSettings();
		}

		if(!$this->IsPostBack){
			$this->Site = Site::GetBySiteId($this->AuthUser->SiteId);
			$this->SiteUrl = $this->Site->Domain;
			
			if($this->AuthUser->Role=='Member' || $this->AuthUser->Role=='Contributor'){
				$this->IsAdmin = false;
			}
			
			if(array_key_exists('m', $_GET)){
				$mode = $this->GetQueryString('m');
				
				$pageId = Page::GetHome($this->Site->SiteId);
				
				if($pageId==-1){
	
				}
				else{
					// get the page
					$this->Page = Page::GetByPageId($pageId);
					
					if($this->Page){
						$this->SetupPage();	
					}
					
					$this->Title = 'Home';
					$this->TypeS = 'Home';
				}
			}
	
			if(array_key_exists('p', $_GET)){
				$pageUniqId = $this->GetQueryString('p');
				$this->Page = Page::GetByPageUniqId($pageUniqId);
				
				if($this->Page){
					$this->SetupPage();	
				}
				
				$this->Title = $this->PageType->TypeS.' '.'Details';
			}
			
		}
	
	}
	
	// sets up a page
	function SetupPage(){

		// get pagetype
		if($this->Page->PageTypeId!=-1){
			$this->PageType = PageType::GetByPageTypeId($this->Page->PageTypeId);
			$this->PageTypeUniqId = $this->PageType->PageTypeUniqId;
			$this->TypeS = $this->PageType->TypeS;
		}
		
		$this->PageId = $this->Page->PageId;
		$this->PageUniqId = $this->Page->PageUniqId;
		$this->FriendlyId = $this->Page->FriendlyId;
		$this->Name = $this->Page->Name;
		$this->Description = $this->Page->Description;
		$this->Rss = $this->Page->Rss;
		$this->Layout = $this->Page->Layout;
		$this->Stylesheet = $this->Page->Stylesheet;
		$this->Keywords = $this->Page->Keywords;

		// get the page fragment
		$p_content = '';
		$fragment = 'sites/'.$this->Site->FriendlyId.'/fragments/publish/'.$this->Page->PageUniqId.'.html';

        if(file_exists($fragment)){
          $p_content = file_get_contents($fragment);
        }

		$this->Content = $p_content;
		
		$this->IsActive = $this->Page->IsActive;
				
		// get offset
		$timeZone = $this->AuthUser->TimeZone;
		$offset = 0;
		
		if($timeZone=='EST'){
			$offset = -5 * (60 * 60);
		}
		else if($timeZone=='CST'){
			$offset = -6 * (60 * 60);
		}
		else if($timeZone=='MST'){
			$offset = -7 * (60 * 60);
		}
		else if($timeZone=='PST'){
			$offset = -8 * (60 * 60);
		}
		
		if($this->Page->PageTypeId!=-1){ // get pagetypes
			$this->PageType = PageType::GetByPageTypeId($this->Page->PageTypeId);
		}
		
		$this->PageTypes = PageType::GetPageTypes($this->AuthUser->SiteId);
	}
	
	function Update(){

		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}

		$userId=$this->AuthUser->UserId;
		$siteId=$this->AuthUser->SiteId;
		$siteUniqId=$this->AuthUser->SiteUniqId;
		$pageUniqId=$this->GetPostData("PageUniqId");
		
		$content = $this->GetPostDataFromTextarea("Content");
		$pageTypeUniqId=$this->GetPostData("PageTypeUniqId");

		$imageId = $this->GetPostData("ImageId");
		
		$imageFileId=-1;
		
		// get fileid
		if($imageId!=-1){ 
			$file = File::GetByUniqueName($imageId);

			if($file!=null){
				$imageFileId = $file->FileId;
			}
		}
		
		$site = Site::GetBySiteId($siteId);
		$page = Page::GetByPageUniqId($pageUniqId);
		
		$page->Edit($imageFileId, $this->AuthUser->UserId);

		Publish::PublishFragment($site->FriendlyId, $page->PageUniqId, 'publish', $content);

		if($page->IsActive == 1 || $page->PageTypeId == -1){
			Publish::PublishPage($page->PageUniqId);
		}

		// put together array
		$tojson = array (
		    "IsSuccessful"  => 'true',
			'Type' => 'Edit',
			'Message' => 'You have successfully updated the content.',
		    'PubResult' => 'Success',
			'ImageFileId' => $imageFileId
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	function SaveDraft(){
		$pageUniqId=$this->GetPostData("PageUniqId");
		$draft=$this->GetPostDataFromTextarea("Draft");
		
		$page = Page::GetByPageUniqId($pageUniqId);
		$site = Site::GetBySiteId($page->SiteId);

		Publish::PublishFragment($site->FriendlyId, $page->PageUniqId, 'draft', $draft);
		
		die('success');
	}
	
	function GetExisting(){
		$role = $this->AuthUser->Role;
		$type = $this->GetPostData("Type");
		$uniqueName = $this->GetPostData("UniqueName");
		$size = $this->GetPostData("Size");
		$width = $this->GetPostData("Width");
		$height = $this->GetPostData("Height");
		
		if($type=='slideshow'){
			if($role=='Admin'){
				$list = File::GetCompatibleImagesForSite($this->AuthUser->SiteId, $width, $height); // get images for current site
			}
			else{
				$list = File::GetCompatibleImagesForUser($this->AuthUser->UserId, $width, $height); // get images for current user
			}
		}
		else if($type=='image' || $type=='gallery'){
			if($role=='Admin'){
				$list = File::GetImagesForSite($this->AuthUser->SiteId); // get images for current site
			}
			else{
				$list = File::GetImagesForUser($this->AuthUser->UserId); // get images for current user
			}
		}
		else{
			if($role=='Admin'){
				$list = File::GetFilesForSite($this->AuthUser->SiteId);
			}
			else{
				$list = File::GetFilesForUser($this->AuthUser->UserId);
			}
		}
		
		$html = '<ul>';
		
		$site = Site::GetBySiteId($this->AuthUser->SiteId);

		while($row = mysql_fetch_array($list)){ 

			if($type=='image' || $type=='slideshow' || $type=='gallery'){
				if($uniqueName==$row['UniqueName']){
					$html = $html.'<li id="item-'.$row['FileUniqId'].'" data-uniquename="'.$row['UniqueName'].'" class="selected">';
				}
				else{
					$html = $html.'<li id="item-'.$row['FileUniqId'].'" data-uniquename="'.$row['UniqueName'].'" data-url="http://'.$site->Domain.'/files/'.$row['UniqueName'].'">';
				}
				$html = $html.'<span class="image"><img width="75" height="75" src="'.$this->AuthUser->FileUrl.'t-'.$row['UniqueName'].'"></span>';
				$html = $html.'<span class="filename">'.$row['FileName'].'</span>';
				$html = $html.'<span class="size">'.$row['Width'].'px x '.$row['Height'].'px</span>';
				$html = $html.'<input type="hidden" class="uniqueName" value="'.$row['UniqueName'].'">';
				$html = $html.'</li>';
			}
			else{
				$html = $html.'<li id="item-'.$row['FileUniqId'].'" class="file">';
			
				$parts = explode(".", $row['UniqueName']); 
				$ext = end($parts); // get extension
				$ext = strtolower($ext); // convert to lowercase
				
				if($ext=='pdf'){
					$html = $html.'<span class="icon filetype pdf"></span>';
				}
				else if($ext=='doc'){
					$html = $html.'<span class="icon filetype word"></span>';
				}
				else if($ext=='png' || $ext=='jpg' || $ext=='jpeg' || $ext='gif'){
					$html = $html.'<span class="icon filetype image"></span>';
				}
				else{
					$html = $html.'<span class="icon filetype"></span>';
				}
				
				$html = $html.'<span class="filename">'.$row['FileName'].'</span>';
				$html = $html.'<input type="hidden" class="uniqueName" value="'.$row['UniqueName'].'">';
				$html = $html.'</li>';
			}
		}
		
		$html = $html.'</ul>';
		$html = $html.'<input id="FileUrl" type="hidden" value="'.$this->AuthUser->FileUrl.'">';
		
		die($html);
	}
	
	function UploadImage(){
		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		// Get uploaded file info
		$resizeImage = $this->GetPostData("ResizeImage");
		$isResized = $resizeImage;
		$isImage = 0;
		
		$fileName = $_FILES['uploadedFile']['name'];  
		$file = $_FILES['uploadedFile']['tmp_name'];
		$contentType = $_FILES['uploadedFile']['type'];
		$size = intval($_FILES['uploadedFile']['size']/1024);
		
		$parts = explode(".", $fileName); 
		$ext = end($parts); // get extension
		$ext = strtolower($ext); // convert to lowercase
		
		$uniqueId = uniqid();
		
		$uniqueName = File::GetUniqName($this->AuthUser->SiteId, $fileName);
		
		//$uniqueName = $uniqueId.'.'.$ext; // create a unique name to be stored
		$thumbnail = 't-'.$uniqueName;
		$width = 0;
		$height = 0;
		
		if($ext=='png' || $ext=='jpg' || $ext=='gif'){ // upload image
			$isImage = 1;
			$dir = $this->AuthUser->FileUrl;
			
			$size=Image::SaveImageWithThumb($dir, $uniqueName, $file);
			
			list($width, $height, $type, $attr) = getimagesize($dir.$uniqueName);
		}
		else if($ext=='css' || $ext=='js' || $ext=='pdf' || $ext=='doc' || $ext=='docx'){ // upload file
			$dir = $this->AuthUser->FileUrl;
			
			// upload file
			$size=Utilities::SaveFile($dir, $uniqueName, $file);
		}
		else{
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'File type is not supported'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		// add file
		$file = File::Add($uniqueId, $uniqueName, $fileName, $size, $width, $height, 1, $thumbnail, $contentType, 'Local', $isImage, $isResized, $this->AuthUser->UserId, $this->AuthUser->SiteId);
		
		// creates a response object
		$tojson = array (
			"IsSuccessful"  => 'true',
			"Message" => $message,
		    "FileId"  => $file->FileId,
		    "UniqueId"  => $uniqueId,
		    "UniqueName"  => $uniqueName,
		    "FileName" => $fileName,
			"Size" => $size,
			"ContentType" => $contentType,
			"IsImage" => $file->IsImage,
			"ResizeImage" => $resizeImage
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// crops the image
	function Crop(){
		
		// get post data
		$file = $this->GetPostData("Url");
		$uniqueName = $this->GetPostData("UniqueName");
		$width = $this->GetPostData("Width");
		$height = $this->GetPostData("Height");
		$x1 = $this->GetPostData("X1");
		$y1 = $this->GetPostData("Y1");
		$scale = $this->GetPostData("Scale");
		$overwrite = $this->GetPostData("Overwrite");
		
		
		$pos = stripos($file, "?");
		
		if($pos!=false){
			$file = substr($file, 0, $pos);
		}
		
		settype($overwrite, 'integer');
		
		$site = Site::GetBySiteId($this->AuthUser->SiteId);
		
		$parts = explode(".", $uniqueName); 
		$ext = end($parts); // get extension
		$ext = strtolower($ext); // convert to lowercase
		$contentType = 'image/png';
		
		if($ext=='gif'){
			$contentType = 'image/gif';
		}
		else if($ext=='jpg'){
			$contentType = 'image/jpg';
		}
		
		if($overwrite==0){ // create a new unique name (if not overwriting)
			$uniqueId = uniqid();
			$uniqueName = File::GetUniqName($this->AuthUser->SiteId, $uniqueName);
		}
		
		$dir = $this->AuthUser->FileUrl;
		
		$o_size = Image::ResizeWithCrop($file, $dir, $uniqueName, $x1, $y1, $scale, $width, $height);
		
		list($width, $height, $type, $attr) = getimagesize($dir.$uniqueName); // get width and height
		
		$new_file = $dir.$uniqueName;
		
		$t_size = Image::ResizeWithCenterCrop($new_file, $dir, 't-'.$uniqueName, 200, 200); // create thumb
		
		$size = ($o_size + $t_size)/1024;
		
		// create a mobile version (in the mobile directory)
		$m_dir = str_replace('files/', 'm/files/', $dir);
		$mobile = Image::Resize($new_file, $m_dir, $uniqueName, 320, 480);
		
		if($overwrite==0){ // add file if not overwriting
			$file = File::Add($uniqueId, $uniqueName, $uniqueName, $size, $width, $height, 1, 't-'.$uniqueName, $contentType, 'Local', 1, 1, $this->AuthUser->UserId, $this->AuthUser->SiteId);
		}
		else{ // return existing file
			$file = File::GetByUniqueName($uniqueName);
			$file->Edit($size, $width, $height);
		}
			
		// creates a response object
		$tojson = array (
		    "FileId"  => $file->FileId,
		    "UniqueId"  => $uniqueId,
		    "UniqueName"  => $uniqueName,
		    "FileName" => $uniqueName,
			"Size" => $size,
			"ContentType" => $contentType,
			"IsImage" => $file->IsImage,
			"Url" => $dir.$uniqueName
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// get categories
	function GetCategories(){
		$pageTypeUniqId = $this->GetPostData("PageTypeUniqId");
		$pageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);
		
		$pages = array();
		
		$categoryList = Category::GetCategoriesByPageTypeId($this->AuthUser->SiteId, $pageType->PageTypeId, 1000, 0, 'Categories.Name');
		
		while($row = mysql_fetch_array($categoryList)){
			$categories[$row['CategoryUniqId']] = $row['Name'];
		}
		
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'Categories' => $categories
		);
		
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
		
	}

	// gets the layout
	function GetLayout(){
		$createPage = $this->GetPostData("CreatePage");
		$siteId = $this->AuthUser->SiteId;
		$site = Site::GetBySiteId($siteId);

		if($createPage!='-1'){
			
			if($createPage=='-2'){ // get home
				$pageId = Page::GetHome($siteId);
				$page = Page::GetByPageId($pageId);
			}
			else{
				$page = Page::GetByPageUniqId($createPage);
			}

			$content = '';
			$fragment = 'sites/'.$site->FriendlyId.'/fragments/publish/'.$page->PageUniqId.'.html';

	        if(file_exists($fragment)){
	          $content = file_get_contents($fragment);
	        }
		}
		
	
		die($content);
	}

	// updates the settings
	function UpdateSettings(){

		$userId=$this->AuthUser->UserId;
		$siteId=$this->AuthUser->SiteId;
		$siteUniqId=$this->AuthUser->SiteUniqId;
		$pageUniqId=$this->GetPostData("PageUniqId");
		$name = $this->GetPostData("Name");
		$keywords = $this->GetPostData("Keywords");
		$callout = $this->GetPostData("Callout");
		$rss = $this->GetPostData("Rss");
		$layout = $this->GetPostData("Layout");
		$stylesheet = $this->GetPostData("Stylesheet");
		$description = $this->GetPostData("Description");
		$friendlyId = $this->GetPostData("FriendlyId");
		$pageTypeUniqId=$this->GetPostData("PageTypeUniqId");
		
		// update the page
		$page = Page::GetByPageUniqId($pageUniqId);
		$pageType = PageType::GetByPageTypeId($page->PageTypeId);
		
		$republishMenu = false;
		
		// check if friendlyId is unique for the site
		if($page->FriendlyId!=$friendlyId){
			$isUnique = Page::IsFriendlyIdUnique($friendlyId, $siteId);
			
			if($isUnique==false){
				
				$tojson = array (
				    'IsSuccessful'  => 'false',
					'Type' => 'Edit',
					'Error' => 'The Friendly URL that you selected is not unique. Please update it and try again.'
				);
				
				// encode to json
				$encoded = json_encode($tojson);
				 
				die($encoded);
			}
			
			$page->EditFriendlyId($friendlyId);
			
			// update url in menu
			$republishMenu = true;
			
			$url = strtolower($pageType->FriendlyId).'/'.strtolower($friendlyId);
			
			MenuItem::UpdateUrl($page->PageId, $url);
		}
		
		$page->EditSettings($name, $description, $keywords, $callout, $rss, $layout, $stylesheet, $this->AuthUser->UserId);
		
		Publish::PublishPage($page->PageUniqId);
		
		if($republishMenu==true){ // re-publishes the menu
			Publish::PublishMenu($siteUniqId);	
		}
		
		Publish::PublishPage($page->PageUniqId); // republish page
		
		// put together array
		$tojson = array (
		    "IsSuccessful"  => 'true',
			'Type' => 'Edit',
			'Message' => 'You have successfully updated the content.',
		    'PubResult' => 'Success'
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// get readable time
	function GetReadable($date){
		// get offset
		$timeZone = $this->AuthUser->TimeZone;
		$offset = 0;
		
		if($timeZone=='EST'){
			$offset = -5 * (60 * 60);
		}
		else if($timeZone=='CST'){
			$offset = -6 * (60 * 60);
		}
		else if($timeZone=='MST'){
			$offset = -7 * (60 * 60);
		}
		else if($timeZone=='PST'){
			$offset = -8 * (60 * 60);
		}
		
		if($date!=''){
			$unixDate = (strtotime($date)+$offset);
			$readable = date('M d', $unixDate).' at '.date('g:i A', $unixDate);
			
			return $readable;
		}
		else{
			return '';
		}
	}
		
}


?>