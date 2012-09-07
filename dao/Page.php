<?php

// Page model
class Page{
	
	public $PageId;
	public $PageUniqId;
	public $FriendlyId;
	public $Name;
	public $Description;
	public $Keywords;
	public $Callout;
	public $Rss;
	public $Layout;
	public $Stylesheet;
	public $PageTypeId;
	public $SiteId;
	public $CreatedBy;
	public $LastModifiedBy;
	public $LastModifiedDate;
	public $IsActive;
	public $ImageFileId;
	public $Created;

	function __construct($pageId, $pageUniqId, $friendlyId, $name, $description, $keywords, $callout, $rss,
		$layout, $stylesheet, $pageTypeId,
		$siteId, $createdBy, $lastModifiedBy, $lastModifiedDate,
		$isActive, $imageFileId, $created){

		$this->PageId = $pageId;
		$this->PageUniqId = $pageUniqId;
		$this->FriendlyId = $friendlyId;
		$this->Name = $name;
		$this->Description = $description;
		$this->Keywords = $keywords;
		$this->Callout = $callout;
		$this->Rss = $rss;
		$this->Layout = $layout;
		$this->Stylesheet = $stylesheet;
		$this->PageTypeId = $pageTypeId;
		$this->SiteId = $siteId;
		$this->CreatedBy = $createdBy;
		$this->LastModifiedBy = $lastModifiedBy;
		$this->LastModifiedDate = $lastModifiedDate;
		$this->IsActive = $isActive;
		$this->ImageFileId = $imageFileId;
		$this->Created = $created;
	}
	
	// Adds a page
	public static function Add($name, $description, $layout, $stylesheet,
		$pageTypeId, $siteId, $createdBy, $lastModifiedBy,
		$isActive, $imageFileId){
		
		Connect::init();
		
		$pageUniqId = uniqid();
		$name = mysql_real_escape_string($name);
		$description = mysql_real_escape_string($description);
		$layout = mysql_real_escape_string($layout);
		$stylesheet = mysql_real_escape_string($stylesheet);
		$description = mysql_real_escape_string($description);
		$keywords = '';
		$callout = '';
		$rss = '';
		settype($pageTypeId, 'integer');
		settype($siteId, 'integer'); // clean
		settype($createdBy, 'integer');
		settype($lastModifiedBy, 'integer');
		settype($isActive, 'integer');
		settype($imageFileId, 'integer');
		
		$timestamp = gmdate("Y-m-d H:i:s", time());
	
		// a bit hacky, but need to ensure that begindate and enddate are null
		$q = "INSERT INTO Pages (PageUniqId, FriendlyId, Name, Description, Keywords, Callout, Rss,
				Layout, Stylesheet, PageTypeId, SiteId, CreatedBy, LastModifiedBy, LastModifiedDate,
				IsActive, ImageFileId, Created) 
			VALUES ('$pageUniqId', '$pageUniqId', '$name', '$description', '$keywords', '$callout', '$rss',
				'$layout', '$stylesheet', $pageTypeId, $siteId, $createdBy, $lastModifiedBy, '$timestamp', 
				 $isActive, $imageFileId, '$timestamp')";
	
	
		$result = mysql_query($q);
	
		if(!$result) {
		  die("Could not successfully run query Page->Add, error=".mysql_error());
		  exit;
		}
		
		return new Page(mysql_insert_id(), $pageUniqId, $pageUniqId, $name, $description, $keywords, $callout, $rss, 
			$layout, $stylesheet, $pageTypeId, $siteId, $createdBy, $lastModifiedBy, $timestamp,
			$isActive, $imageFileId, $timestamp); 
	}
	
	// determines whether a friendlyId is unique
	public static function IsFriendlyIdUnique($friendlyId, $siteId){

		Connect::init();
		
		$friendlyId = mysql_real_escape_string($friendlyId); // clean data
		settype($siteId, 'integer');
		
		$count=0;
	
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Count(*) as Count
			FROM Pages where FriendlyId='$friendlyId' AND SiteId=$siteId");
			
		if(mysql_num_rows($result) == 0){
		    return false;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
	
		if($count==0){
			return true;
		}
		else{
			return false;
		}
	}
	
	// Edits a friendly id
	public function EditFriendlyId($friendlyId){
		
		Connect::init();
		
		// cleanup friendlyid (escape, trim, remove spaces, tolower)
		$friendlyId = mysql_real_escape_string($friendlyId);
		$friendlyId = trim($friendlyId);
		$friendlyId = str_replace(' ', '', $friendlyId);
		$friendlyId = strtolower($friendlyId);
		
		$query = "UPDATE Pages 
					SET FriendlyId = '$friendlyId'
					WHERE PageId = $this->PageId";
		
		$result = mysql_query($query);
		
		if(!$result) {
		  die("Could not successfully run query Page->EditFriendlyId, error=".mysql_error());
		  exit;
		}
		
		$this->FriendlyId = $friendlyId;
		
		return;	
	}
	
	// Edits a page
	public function Edit($imageFileId, $lastModifiedBy){
		
		Connect::init();
		
		settype($lastModifiedBy, 'integer');  
		
		$timestamp = gmdate("Y-m-d H:i:s", time());
		
		$query = "UPDATE Pages SET
					ImageFileId = $imageFileId,
					LastModifiedBy = $lastModifiedBy,
					LastModifiedDate = '$timestamp'
					WHERE PageId = $this->PageId";
		
		$result = mysql_query($query);
		
		if(!$result) {
		  die("Could not successfully run query Page->Edit, error=".mysql_error());
		  exit;
		}
		
		$this->LastModifiedBy = $lastModifiedBy;
		$this->LastModifiedDate = $timestamp;
		
		return;	
	}

	// Edits the settings for a page
	public function EditSettings($name, $description, $keywords, $callout, $rss, $layout, $stylesheet, $lastModifiedBy){
		
		Connect::init();
		
		$name = mysql_real_escape_string($name); // clean data
		$description = mysql_real_escape_string($description);
		$keywords = mysql_real_escape_string($keywords);
		$callout = mysql_real_escape_string($callout);
		$rss = mysql_real_escape_string($rss);
		$layout = mysql_real_escape_string($layout);
		$stylesheet = mysql_real_escape_string($stylesheet);
		settype($lastModifiedBy, 'integer');  
		
		$timestamp = gmdate("Y-m-d H:i:s", time());
		
		$query = "UPDATE Pages 
					SET Name = '$name', 
					Description = '$description',
					Keywords = '$keywords',
					Callout = '$callout',
					Rss = '$rss',
					Layout = '$layout',
					Stylesheet = '$stylesheet',
					LastModifiedBy = $lastModifiedBy,
					LastModifiedDate = '$timestamp'
					WHERE PageId = $this->PageId";
		
		$result = mysql_query($query);
		
		if(!$result) {
		  die("Could not successfully run query Page->EditSettings, error=".mysql_error());
		  exit;
		}
		
		$this->Name = $name;
		$this->Description = $description;
		$this->Keywords = $keywords;
		$this->Callout = $callout;
		$this->Rss = $rss;
		$this->Layout = $layout;
		$this->Stylesheet = $stylesheet;
		$this->LastModifiedBy = $lastModifiedBy;
		$this->LastModifiedDate = $timestamp;
		
		return;	
	}
	
	// Edits a page's name
	public static function EditName($pageId, $name){
		
		Connect::init();
		
		$name = mysql_real_escape_string($name); // clean data
		
		$timestamp = gmdate("Y-m-d H:i:s", time());
		
		$query = "UPDATE Pages 
					SET Name = '$name'
					WHERE PageId = $pageId";
		
		$result = mysql_query($query);
		
		if(!$result) {
		  die("Could not successfully run query Page->EditName, error=".mysql_error());
		  exit;
		}
		
		return;
	}

	// Edits IsActive
	public function EditIsActive($isActive){
		
		Connect::init();
		
		settype($isActive, 'integer'); 
		
		$query = "UPDATE Pages 
					SET IsActive = $isActive
					WHERE PageId = $this->PageId";
		
		$result = mysql_query($query);
		
		if(!$result) {
		  die("Could not successfully run query Page->EditIsActive, error=".mysql_error());
		  exit;
		}
		
		return;	
	}
	
	// Activates a page
	public function Activate(){
		
		Connect::init();
		
		$query = "UPDATE Pages 
					SET IsActive = '1'
					WHERE PageId = $this->PageId";
		
		$result = mysql_query($query);
		
		if(!$result) {
		  die("Could not successfully run query Page->Activate, error=".mysql_error());
		  exit;
		}
		
		return;	
	}
	
	// sets the primary image
	public function SetPrimaryImage($imageFileId){
		
		Connect::init();
		
		settype($imageFileId, 'integer');
		
		mysql_query("UPDATE Pages SET ImageFileId=$imageFileId WHERE PageId=$this->PageId");
		
	}
	
	// Deletes a page
	public static function Delete($pageUniqId){
		
		Connect::init();
	
		$delete = mysql_query("DELETE FROM Pages WHERE PageUniqId='$pageUniqId'");
	
		return;
	}
	
	// Gets all pages
	public static function GetPages($siteId, $pageTypeId, $pageSize, $pageNo, $orderBy){
		
		Connect::init();
		
		$next = $pageSize * $pageNo;
		
		$q = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, 
			Pages.Description, Pages.Keywords, Pages.Callout,
			Pages.Layout, Pages.Stylesheet,
			Pages.SiteId, Pages.CreatedBy, 
			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
			Pages.IsActive, Pages.ImageFileId, Pages.PageTypeId,
			Users.FirstName, Users.LastName
			FROM Pages LEFT JOIN Users ON Pages.LastModifiedBy = Users.UserId
			WHERE Pages.SiteId = $siteId AND Pages.PageTypeId = $pageTypeId";
		
		$q = $q." ORDER BY $orderBy LIMIT $next, $pageSize";
		
		// Pulls in the Name of the User too
		$result = mysql_query($q);

		if(!$result) {
		  die("Could not successfully run query Pages->GetPages. " . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}

	// Gets all pages for a given service
	public static function GetTotalPagesForService($siteId, $pageTypeId){
		
		Connect::init();
		
		// Pulls in the Name of the User too
		$sql = "SELECT Count(*) as Count
			FROM Pages, Users ";
		$sql = $sql."WHERE Pages.IsActive=1 AND "; 

		$sql = $sql."Pages.LastModifiedBy = Users.UserId AND Pages.SiteId = $siteId AND Pages.PageTypeId = $pageTypeId";

		$result = mysql_query($sql);
			
		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
		
		return $count;
	}

	// Gets all pages for a given service
	public static function GetPagesForService($siteId, $pageTypeId, $orderBy, $pageSize, $pageNo){
		
		Connect::init();
		
		$next = $pageSize * $pageNo;

		$orderByClause = 'Pages.'.$orderBy;
		
		// Pulls in the Name of the User too
		$sql = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Callout,
			Pages.SiteId, Pages.CreatedBy, 
			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
			Pages.IsActive, Pages.ImageFileId, Pages.PageTypeId,
			Users.FirstName, Users.LastName
			FROM Pages, Users ";
		$sql = $sql."WHERE Pages.IsActive=1 AND "; 

		$sql = $sql."Pages.LastModifiedBy = Users.UserId AND Pages.SiteId = $siteId AND Pages.PageTypeId = $pageTypeId
			ORDER BY $orderByClause LIMIT $next, $pageSize";
		
			//print $sql;

		$result = mysql_query($sql);
			
		if(!$result) {
		  die("Could not successfully run query Pages->GetPagesForService" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}

	// Gets the home page for the site
	public static function GetHome($siteId){
		
		Connect::init();
		
		$count = 0;
		
		$result = mysql_query("SELECT PageId
			FROM Pages
			WHERE Pages.SiteId = $siteId AND Pages.PageTypeId = -1");

		if(mysql_num_rows($result) == 0){
		    return -1;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$pageId = $row["PageId"];
		}
		
		return $pageId;
	}
	
	// Gets all 
	public static function GetPagesForSite($siteId){
		
		Connect::init();
		
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Pages.PageId, Pages.PageUniqId, Pages.PageTypeId, Pages.FriendlyId, Pages.Name, 
			Pages.LastModifiedDate, Pages.Created
			FROM Pages
			WHERE Pages.SiteId = $siteId
			ORDER BY Pages.Name ASC");

		if(!$result) {
		  die("Could not successfully run query Pages->GetPagesForSite" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all page for a given $site, pageTypeId
	public static function GetAllPages($siteId, $pageTypeId){
		
		Connect::init();
		
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name,
			Pages.SiteId, Pages.CreatedBy, 
			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
			Pages.IsActive, Pages.ImageFileId, Pages.PageTypeId,
			Users.FirstName, Users.LastName
			FROM Users, Pages
			WHERE Pages.CreatedBy = Users.UserId AND Pages.SiteId = $siteId AND Pages.PageTypeId = $pageTypeId
			ORDER BY Pages.Name ASC");

		if(!$result) {
		  die("Could not successfully run query Pages->GetAllPages" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}

	// Gets all page for a given $site, pageTypeId
	public static function GetPagesForFragments(){
		
		Connect::init();
		
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Pages.PageUniqId, Pages.Content, Pages.Draft, Sites.FriendlyId
			FROM Sites, Pages
			WHERE Pages.SiteId = Sites.SiteId");

		if(!$result) {
		  die("Could not successfully run query Pages->GetAllPages" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all page for a given $site, pageTypeId
	public static function GetPagesForPageType($siteId, $pageTypeId){
		
		Connect::init();
		
		$sql = "SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Callout,
			Pages.SiteId, Pages.CreatedBy, 
			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
			Pages.IsActive, Pages.ImageFileId, Pages.PageTypeId,
			Users.FirstName, Users.LastName
			FROM Users, Pages
			WHERE Pages.LastModifiedBy = Users.UserId AND Pages.SiteId = $siteId AND Pages.PageTypeId = $pageTypeId
			ORDER BY Pages.Name ASC";
		
		// Pulls in the Name of the User too
		$result = mysql_query($sql);

		if(!$result) {
		  die("Could not successfully run query Pages->GetPagesForPageType".mysql_error()." - ".$sql);
		  exit;
		}
		
		return $result;
	}
	
	// Gets all page for a given $site, pageTypeId
	public static function GetRSS($siteId, $pageTypeId){
		
		Connect::init();
		
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description,
			Pages.SiteId, Pages.CreatedBy, 
			Pages.LastModifiedBy, Pages.Created, Pages.LastModifiedDate, 
			Pages.IsActive, Pages.ImageFileId, Pages.PageTypeId,
			Users.FirstName, Users.LastName
			FROM Users, Pages
			WHERE Pages.CreatedBy = Users.UserId AND Pages.SiteId = $siteId AND Pages.PageTypeId = $pageTypeId
			ORDER BY Pages.Created DESC");

		if(!$result) {
		  die("Could not successfully run query Pages->GetRSS" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Get the total number of pages
	public static function GetPagesCount($siteId, $pageTypeId){
		
		Connect::init();
		
		$count=0;
		
		$q = "SELECT Count(*) as Count
			FROM Pages
			WHERE SiteId = $siteId AND PageTypeId = $pageTypeId";
		
		$result = mysql_query($q);

		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
		
		return $count;
	}
	
	
	// Gets a page for a specific $pageUniqId
	public static function GetByPageUniqId($pageUniqId){
		
		Connect::init();
		
		$result = mysql_query("SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Keywords, 
			Pages.Callout, Pages.Rss,
			Pages.Layout, Pages.Stylesheet,
			Pages.PageTypeId, Pages.SiteId, Pages.CreatedBy, Pages.LastModifiedBy, Pages.LastModifiedDate,  
			Pages.IsActive, Pages.ImageFileId, Pages.Created
		 	FROM Pages WHERE PageUniqId='$pageUniqId'");
		
		if(!$result) 
		{
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$pageId = $row["PageId"];
			$pageUniqId = $row["PageUniqId"];
			$friendlyId = $row["FriendlyId"];
			$name = $row["Name"];
			$description = $row["Description"];
			$keywords = $row["Keywords"];
			$callout = $row["Callout"];
			$rss = $row["Rss"];
			$layout = $row["Layout"];
			$stylesheet = $row["Stylesheet"];
			$pageTypeId = $row["PageTypeId"];
			$siteId = $row["SiteId"];
			$createdBy= $row["CreatedBy"];
			$lastModifiedBy= $row["LastModifiedBy"];
			$lastModifiedDate= $row["LastModifiedDate"];
			$isActive = $row["IsActive"];
			$imageFileId = $row["ImageFileId"];
			$created = $row["Created"];
				
			return new Page($pageId, $pageUniqId, $friendlyId, $name, $description, $keywords, $callout, $rss,
				$layout, $stylesheet, $pageTypeId, $siteId, $createdBy, $lastModifiedBy, $lastModifiedDate,
				$isActive, $imageFileId, $created);  
		}
	}
	
	// Gets a page for a specific $friendlyId and $siteId
	public static function GetByFriendlyId($friendlyId, $siteId){
		
		Connect::init();
		
		$result = mysql_query("SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Keywords, 
			Pages.Callout, Pages.Rss,
			Pages.Layout, Pages.Stylesheet,
			Pages.PageTypeId, Pages.SiteId, Pages.CreatedBy, Pages.LastModifiedBy, Pages.LastModifiedDate,  
			Pages.IsActive, Pages.ImageFileId, Pages.Created
		 	FROM Pages WHERE FriendlyId='$friendlyId' AND SiteId=$siteId");
		
		if(!$result) 
		{
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$pageId = $row["PageId"];
			$pageUniqId = $row["PageUniqId"];
			$friendlyId = $row["FriendlyId"];
			$name = $row["Name"];
			$description = $row["Description"];
			$keywords = $row["Keywords"];
			$callout = $row["Callout"];
			$rss = $row["Rss"];
			$layout = $row["Layout"];
			$stylesheet = $row["Stylesheet"];
			$pageTypeId = $row["PageTypeId"];
			$siteId = $row["SiteId"];
			$createdBy= $row["CreatedBy"];
			$lastModifiedBy= $row["LastModifiedBy"];
			$lastModifiedDate= $row["LastModifiedDate"];
			$isActive = $row["IsActive"];
			$imageFileId = $row["ImageFileId"];
			$created = $row["Created"];
				
			return new Page($pageId, $pageUniqId, $friendlyId, $name, $description, $keywords, $callout, $rss,
				$layout, $stylesheet, $pageTypeId, $siteId, $createdBy, $lastModifiedBy, $lastModifiedDate,
				$isActive, $imageFileId, $created);  
		}
	}
	
	// Gets a page for a specific $pageId
	public static function GetByPageId($pageId){
		
		Connect::init();
		
		$result = mysql_query("SELECT Pages.PageId, Pages.PageUniqId, Pages.FriendlyId, Pages.Name, Pages.Description, Pages.Keywords, 
			Pages.Callout, Pages.Rss,
			Pages.Layout, Pages.Stylesheet,
			Pages.PageTypeId, Pages.SiteId, Pages.CreatedBy, Pages.LastModifiedBy, Pages.LastModifiedDate,  
			Pages.IsActive, Pages.ImageFileId, Pages.Created
		 	FROM Pages WHERE PageId=$pageId");
			
		if(!$result) 
		{
		  return null;
		}
		
		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
		
			$pageId = $row["PageId"];
			$pageUniqId = $row["PageUniqId"];
			$friendlyId = $row["FriendlyId"];
			$name = $row["Name"];
			$description = $row["Description"];
			$keywords = $row["Keywords"];
			$callout = $row["Callout"];
			$rss = $row["Rss"];
			$layout = $row["Layout"];
			$stylesheet = $row["Stylesheet"];
			$pageTypeId = $row["PageTypeId"];
			$siteId = $row["SiteId"];
			$createdBy= $row["CreatedBy"];
			$lastModifiedBy= $row["LastModifiedBy"];
			$lastModifiedDate= $row["LastModifiedDate"];
			$isActive = $row["IsActive"];
			$imageFileId = $row["ImageFileId"];
			$created = $row["Created"];
				
			return new Page($pageId, $pageUniqId, $friendlyId, $name, $description, $keywords, $callout, $rss,
				$layout, $stylesheet, $pageTypeId, $siteId, $createdBy, $lastModifiedBy, $lastModifiedDate,
				$isActive, $imageFileId, $created); 
		}
	}
	
}

?>