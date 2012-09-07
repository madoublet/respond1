<?php

// Settings controller
class Settings extends Actions
{
	public $Ajax = '';
	public $Name = '';
	public $Domain = '';
	public $MobileUrl = '';
	public $PrimaryEmail = '';
	public $AnalyticsId = '';
	public $FacebookAppId = '';
	public $TimeZone = '';
	public $SiteMap;
	public $MobileSiteMap;
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;
		
		$this->MobileUrl = str_replace('files/', 'm/files/', $authUser->FileUrl);

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='settings.updateBasic'){
			$this->UpdateBasic();
		}

		if($this->Ajax=='settings.verify'){
			$this->Verify();
		}
		
		if($this->Ajax=='settings.upload'){
			$this->Upload();
		}

		if(!$this->IsPostBack){
			$site = Site::GetBySiteId($this->AuthUser->SiteId);
			
			$this->Name = $site->Name;
			$this->Domain = $site->Domain;
			$this->AnalyticsId = $site->AnalyticsId;
			$this->FacebookAppId = $site->FacebookAppId;
			$this->PrimaryEmail = $site->PrimaryEmail;
			$this->TimeZone = $site->TimeZone;
			$this->SiteMap = 'http://'.$site->Domain.'/sitemap.xml';
			$this->MobileSiteMap = 'http://'.$site->Domain.'/m/sitemap.xml';
		}
	}
	
	function UpdateBasic(){
		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}

		$siteId = $this->AuthUser->SiteId;
		
		$name = $this->GetPostData("Name");
		$domain = $this->GetPostData("Domain");
		$analyticsId = $this->GetPostData("AnalyticsId");
		$facebookAppId = $this->GetPostData("FacebookAppId");
		$primaryEmail = $this->GetPostData("PrimaryEmail");
		$timeZone = $this->GetPostData("TimeZone");
		
		Site::EditBasic($siteId, $name, $domain, $analyticsId, 
			$facebookAppId, $primaryEmail, $timeZone);
			
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Settings updated successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
	
	function Verify(){
		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		$siteId = $this->AuthUser->SiteId;
		
		$fileName = $this->GetPostData("FileName");
		$fileContent = $this->GetPostData("FileContent");
		
		$site = Site::GetBySiteId($siteId);
		
		$dir = 'sites/'.$site->FriendlyId;
		
		Utilities::SaveContent($dir.'/', $fileName, $fileContent);
		
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Verification file updated successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
	
}


?>