<?php

// Home controller
class Home extends Actions
{
	public $Email; // properites
	public $Password;
	public $HasError = 'false';
	public $Url = 'index.php';
	public $SA;
	public $SiteUniqId = -1;
	public $SiteId = -1;
	public $List;
	public $ShowLogin = true;
	
	function __construct($sa){
		
		parent::__construct(); // need to call parent constructor
		
		$this->SA = $sa;
		
		if($this->IsPostBack){
			$this->Email = $this->GetPostData("Email");
			$this->Password = $this->GetPostData("Password");
			$this->SiteUniqId = $this->GetPostData("SiteUniqId");
			
			if($this->SiteUniqId!=-1){
				$site = Site::GetBySiteUniqId($this->SiteUniqId);
				$this->Url = 'http://'.$site->Domain;
				$this->SiteUniqId = $site->SiteUniqId;
				$this->SiteId = $site->SiteId;
			}
		
			$this->Validate();
			
			if($this->Errors=="")
			{
				$this->ShowLogin = true;
				$this->Process();
			}		
		}
		else{
			$site = $this->GetQueryString("s"); // get friendlyId
			$l = $this->GetQueryString("l"); // get friendlyId
			
			if($l=='true'){
				$this->ShowLogin = true;
			}
			
			if($site!=''){
				$this->ShowLogin = true;
				$site = Site::GetByFriendlyId($site);
				$this->Logo = 'sites/'.$site->FriendlyId.'/files/'.$site->LogoUrl;
				$this->Name = $site->Name;
				$this->Url = $site->Domain;
				$this->SiteUniqId = $site->SiteUniqId;
				$this->SiteId = $site->SiteId;
			}
		}
		
	}
	
	function Validate(){
		
		$validator = new Validator();
		
		if(!$validator->Required($this->Email)){
			$this->Errors = $this->Errors."Email is required. ";
			$this->HasError = 'true';
		}
		
		if(!$validator->Required($this->Password)){
			$this->Errors = $this->Errors."Password is required.";
			$this->HasError = 'true';
		}

	}
	
	function Process(){
		
		// get request data
		$user = User::Get($this->Email, $this->Password);

		if($user != null)
		{
			session_start();

			$site = Site::GetBySiteId($user->SiteId);
			
			$isSuperAdmin = 0;
			
			if($user->Email==$this->SA){ // set is superman
				$isSuperAdmin = 1;
			}
			
			$isFirstLogin = 0;
			
			if($site->LastLogin==null){
				$isFirstLogin = 1;
			}
			
			$site->SetLastLogin(); // set the last login
			
			$directory = 'sites/'.$site->FriendlyId.'/';
			
			$_SESSION['UserId'] = $user->UserId;
			$_SESSION['UserUniqId'] = $user->UserUniqId; 
			$_SESSION['Role'] = $user->Role;  
			$_SESSION['IsSuperAdmin'] = $isSuperAdmin;  
			$_SESSION['IsFirstLogin'] = $isFirstLogin; 
			$_SESSION['Email'] = $user->Email;
			$_SESSION['Name'] = $user->FirstName.' '.$user->LastName;
			$_SESSION['FirstName'] = $user->FirstName;
			$_SESSION['LastName'] = $user->LastName;
			$_SESSION['SiteId'] = $user->SiteId;
			$_SESSION['SiteUniqId'] = $site->SiteUniqId;
			$_SESSION['SiteFriendlyId'] = $site->FriendlyId;
			$_SESSION['Directory'] = $directory;
			$_SESSION['LogoUrl'] = $site->LogoUrl;
			$_SESSION['sid'] = session_id(); 
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['SiteName'] = $site->Name;
			$_SESSION['FileUrl'] = 'sites/'.$site->FriendlyId.'/files/';
			$_SESSION['TimeZone'] = $site->TimeZone;

				
			$pageType = PageType::GetDefaultPageType($site->SiteId);
			
			$_SESSION['Menu'] = '';
			$_SESSION['RolledUp'] = false;
			
			
			$_SESSION['HomeUrl'] = 'pages.php';
				
			header("location:".'pages.php');
		
		}
		else
		{
			$this->HasError = 'true';
			$this->Errors = 'Your login is incorrect.';
		}
	}
	
	function GetCurrentUrl() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
		
}


?>