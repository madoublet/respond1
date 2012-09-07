<?php

// Templates controller
class Templates extends Actions
{
	public $Ajax = '';
	public $ColorArr;
	public $Template;
	public $type = 'general';
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='template.edit'){
			$this->EditTemplate();
		}
		else if($this->Ajax=='template.reset'){
			$this->ResetTemplate();
		}


		if(!$this->IsPostBack){
			
			$siteId = $authUser->SiteId;
			$site = Site::GetBySiteId($siteId);
			$this->Template = $site->Template;

			if(array_key_exists('t', $_GET)){
				$this->type = $this->GetQueryString("t");
			}
		}
	}
	
	function EditTemplate(){

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
		$userId = $this->AuthUser->UserId;
		
		$template = $this->GetPostData("Template");
		
		$site = Site::GetBySiteId($siteId);
		
		Site::EditTemplate($siteId, $template);
		
		// publishes a template for a site
		Publish::PublishTemplate($site, $template);
		
		// republish site with the new template
		Publish::PublishSite($site->SiteUniqId);
		
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Template published successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}

	function ResetTemplate(){

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
		$userId = $this->AuthUser->UserId;
		
		$template = $this->GetPostData("Template");
		
		$site = Site::GetBySiteId($siteId);

		// publishes a template for a site
		Publish::PublishTemplate($site, $template);
		
		// republish site with the new template
		Publish::PublishSite($site->SiteUniqId);

		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Template reset successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
		
}


?>