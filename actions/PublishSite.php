<?php

// Account controller
class PublishSite extends Actions
{
	public $CategoryList;
	public $AuthUser;
	public $Ajax;
	
	function __construct($authUser){
		
		parent::__construct(); // need to call parent constructor
		
		$this->AuthUser = $authUser;

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */

		if($this->Ajax=='publish.publishSite'){
			$this->PublishSite();
		}
		
		$site = Site::GetBySiteId($this->AuthUser->SiteId);
	}
	
	function PublishSite(){

		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		$site = Site::GetBySiteId($this->AuthUser->SiteId);
		
		Publish::PublishSite($site->SiteUniqId);
		
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'Message' => 'Site published successfully'
		);
		
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}

		
}


?>