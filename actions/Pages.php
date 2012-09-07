<?php

// Pages controller
class Pages extends Actions
{
	public $Name; /* properties */
	public $Description;
	public $Attachments;
	public $AuthUser;
	public $List;
	public $Category;
	public $Type = 'page';
	public $Ajax;
	public $Method;
	public $Count = 0;
	public $PageSize = 10;
	public $View = 'list';
	public $IsDefault = false;
	public $PageType;
	public $PageTypeUniqId;
	public $IsAdmin = true;
	public $TypeText = array('singular'=>'Page', 'plural'=>'Pages');
	public $SiteUrl;
	public $TypeS;
	public $PageTypes;
	public $HomeDesc;
	public $HomeLastModifiedDate;
	public $HomeLastModifiedName = '';
	
	function __construct($authUser){
		
		parent::__construct();
		
		$this->AuthUser = $authUser;
		
		$this->Ajax = $this->GetPostData("Ajax");
		
		if($this->Ajax=='pages.remove'){
			$this->Remove();
		}
		else if($this->Ajax=='pages.add'){
			$this->Add();
		}
		else if($this->Ajax=='pages.publish'){
			$this->Publish();
		}

		if($this->IsPostBack){ 
			// nothing here yet			
		}

		if(array_key_exists('t', $_GET)){
			$pageTypeUniqId = $this->GetQueryString("t");
		
			$this->PageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);
			$this->TypeS = $this->PageType->TypeS;
		}
		else{
			
			$this->PageType = PageType::GetDefaultPageType($authUser->SiteId);
			
			$this->TypeS = $this->PageType->TypeS;
		}
		
		if($this->PageType->FriendlyId=='page'){
			$this->IsDefault = true;
		}
		else{
			$this->IsDefault = false;
		}
		
		// get home
		$pageId = Page::GetHome($this->AuthUser->SiteId);
		
		if($pageId!=-1){
			$home = Page::GetByPageId($pageId);
			$this->HomeDesc = $home->Description;
			$this->HomeLastModifiedDate = $home->LastModifiedDate;
			
			
			$lastModifiedBy = User::GetByUserId($home->LastModifiedBy);
			if($lastModifiedBy!=null){
				$this->HomeLastModifiedName = $lastModifiedBy->FirstName.' '.$lastModifiedBy->LastName;
			}
		}
		
		$site = Site::GetBySiteId($authUser->SiteId);
		$this->SiteUrl = $site->Domain;
		
		
		$this->Fill();  // fills the pages list

	}
	
	function fill(){
		
		$pageNo = $this->GetQueryString("page");
		
		if($pageNo==''){
			$pageNo = 0;
		}
	
		$this->PageTypes = PageType::GetPageTypes($this->AuthUser->SiteId);
		$this->List = Page::GetPages($this->AuthUser->SiteId, $this->PageType->PageTypeId, $this->PageSize, $pageNo, 'Pages.Name ASC'); // get pages for the site	
		$this->Count = Page::GetPagesCount($this->AuthUser->SiteId, $this->PageType->PageTypeId);
		
	}
	
	// publish
	function Publish(){
		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}

		$pageUniqId = $this->GetPostData("PageUniqId");
		$isActive = $this->GetPostData("IsActive");
		settype($isActive, 'integer'); 

		if($isActive==1){
			$isActive=0;
			$message = 'Page un-published successfully';
		}
		else{
			$isActive=1;
			$message = 'Page published successfully';
		}

		$page = Page::GetByPageUniqId($pageUniqId);
		$page->EditIsActive($isActive);

		// delete the page
		if($isActive==0){ 
			$site = Site::GetBySiteId($page->SiteId);
			$pageType = PageType::GetByPageTypeId($page->PageTypeId);

			$filename = 'sites/'.$site->FriendlyId.'/';
			$m_filename = 'sites/'.$site->FriendlyId.'/m/';
			
			$path = 'uncategorized';
			
			if($pageType!=null){
				$path = strtolower($pageType->FriendlyId);
			}
				
	 	  	$filename = $filename.$path.'/'.$page->FriendlyId.'.php';
			$m_filename = $m_filename.$path.'/'.$page->FriendlyId.'.php';
	 	  	
			if(file_exists($filename)){
				unlink($filename);
			}
			
			if(file_exists($m_filename)){
				unlink($m_filename);
			}
		}
		else{ // publish the page
			Publish::PublishPage($page->PageUniqId);
		}

		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => $message
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}


	// adds a page
	function Add(){
		$userId=$this->AuthUser->UserId;
		$siteId=$this->AuthUser->SiteId;

		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		$site = Site::GetBySiteId($siteId);
		
		$pageTypeUniqId = $this->GetPostData("PageTypeUniqId");
		
		$pageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);
		$name = trim($this->GetPostData("Name"));
		$friendlyId = trim($this->GetPostData("FriendlyId"));
		$description = $this->GetPostData("Description");
		$description = strip_tags(html_entity_decode($description));
		$isActive = 0;
		$imageFileId = -1;
		
		// check to make sure we have a name and friendlyid
		if($name == '' && $friendlyId == ''){
			$tojson = array (
			    'IsSuccessful'  => 'false',
				'Type' => 'Edit',
				'Error' => 'The Name and Friendly URL are required.'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		
		// check for uniqueness
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
		
		// create the content
		$content = '<div class="block row-fluid"><div class="col span12"><h1>{name}</h1><p>{description}</p></div></div>';
		
		$content = str_replace('{name}', $name, $content); // set location
		$content = str_replace('{description}', $description, $content); // set description
	
		// type, layout, stylesheet
		$layout = 'content'; // default is home or content
		$stylesheet = 'content'; // default is layout or content
		
		// add the page		 
		$page = Page::Add($name, $description, $layout, $stylesheet,
			$pageType->PageTypeId, $this->AuthUser->SiteId, $this->AuthUser->UserId, $this->AuthUser->UserId,
			$isActive, $imageFileId);
		
		$page->EditFriendlyId($friendlyId);

		Publish::PublishFragment($site->FriendlyId, $page->PageUniqId, 'publish', $content);
		
		// put together array
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'PageUniqId' => $page->PageUniqId,
			'Name' => $page->Name,
			'Description' => $page->Description,
			'Message' => 'You have successfully added the '.$pageType->TypeS.'.'
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// removes a page
	function Remove(){
		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}

		$pageUniqId = $this->GetPostData("PageUniqId");
		
		$page = Page::GetByPageUniqId($pageUniqId);
		$pageType = PageType::GetByPageTypeId($page->PageTypeId);
		
		$site = Site::GetBySiteId($page->SiteId); // test for now
		$filename = 'sites/'.$site->FriendlyId.'/';
		$m_filename = 'sites/'.$site->FriendlyId.'/m/';
		
		$path = 'uncategorized';
		
		if($pageType!=null){
			$path = strtolower($pageType->FriendlyId);
		}
			
 	  	$filename = $filename.$path.'/'.$page->FriendlyId.'.php';
		$m_filename = $m_filename.$path.'/'.$page->FriendlyId.'.php';
 	  	
		Page::Delete($pageUniqId);
		
		if(file_exists($filename)){
			unlink($filename);
		}
		
		if(file_exists($m_filename)){
			unlink($m_filename);
		}

		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => $pageType->TypeS.' removed successfully',
			"PageUniqId" => $pageUniqId
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
		
		if($date!='' && $date!=null){
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