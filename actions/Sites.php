<?php

// Sites controller
class Sites extends Actions
{
	public $Ajax;
	public $List;
	
	function __construct($authUser){
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;
		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='sites.switch'){
			$this->SwitchSite();
		}
		
		$this->List = Site::GetSites();
	
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
			$readable = date('M d, Y', $unixDate);;
			
			return $readable;
		}
		else{
			return '';
		}
	}
	

	// switches to a specified site
	function SwitchSite(){
		$siteUniqId = $this->GetPostData("SiteUniqId");
		
		$site = Site::GetBySiteUniqId($siteUniqId);
		
		$_SESSION['SiteId'] = $site->SiteId;
		$_SESSION['SiteFriendlyId'] = $site->FriendlyId;
		$_SESSION['LogoUrl'] = $site->LogoUrl;
		$_SESSION['SiteName'] = $site->Name;
		$_SESSION['FileUrl'] = 'sites/'.$site->FriendlyId.'/files/';
		$_SESSION['TimeZone'] = $site->TimeZone;
		
		die($siteUniqId);
	}
}


?>