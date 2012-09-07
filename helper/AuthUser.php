<?php

class AuthUser{
	
	public $UserId;
	public $UserUniqId;
	public $Role;
	public $IsSuperAdmin;
	public $IsFirstLogin;
	public $Email;
	public $Name;
	public $FirstName;
	public $LastName;
	public $SiteId;
	public $SiteUniqId;
	public $SiteFriendlyId;
	public $Directory;
	public $LogoUrl;
	public $PageId;
	public $sid;
	public $ip;
	public $SiteName;
	public $FileUrl;
	public $HomeUrl;
	public $TimeZone;
	
	function __construct(){
		
		session_start();
		
		if(isset($_SESSION['UserId']))
		{
			$this->UserId = $_SESSION['UserId'];
			$this->UserUniqId = $_SESSION['UserUniqId'];
			$this->Role = $_SESSION['Role'];
			$this->IsSuperAdmin = $_SESSION['IsSuperAdmin'];
			$this->IsFirstLogin =  $_SESSION['IsFirstLogin'];
			$this->Email = $_SESSION['Email'];
			$this->Name = $_SESSION['Name'];
			$this->FirstName = $_SESSION['FirstName'];
			$this->LastName = $_SESSION['LastName'];
			$this->SiteId = $_SESSION['SiteId'];
			$this->SiteUniqId = $_SESSION['SiteUniqId'];
			$this->SiteFriendlyId = $_SESSION['SiteFriendlyId'];
			$this->Directory = $_SESSION['Directory'];
			$this->LogoUrl = $_SESSION['LogoUrl'];
			$this->sid = $_SESSION['sid'];
			$this->ip = $_SESSION['ip'];
			$this->SiteName = $_SESSION['SiteName'];
			$this->FileUrl = $_SESSION['FileUrl'];
			$this->HomeUrl = $_SESSION['HomeUrl']; 
			$this->TimeZone = $_SESSION['TimeZone']; 
		}
		else $this->Redirect();
	}
	
	private function Redirect(){
		header("location:index.php"); /* redirects to the login page */
	}
	
	public function Authenticate($auth){
		
		if($auth=='Admin'){
			if($this->Role != 'Admin'){
				die('You are not authorized to view this page.');
			}
		}
		
		if($auth=='SuperAdmin'){
			if($this->IsSuperAdmin!=true){
				die('You are not authorized to view this page.');
			}
		}
		
	}
}

?>