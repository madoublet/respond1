<?php

// PageTypes controller
class PageTypes extends Actions
{
	public $Ajax = '';
	public $List;
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='pageType.add'){
			$this->AddPageType();
		}
		
		if($this->Ajax=='pageType.edit'){
			$this->EditPageType();
		}
		
		if($this->Ajax=='pageType.remove'){
			$this->Remove();
		}

		$site = Site::GetBySiteId($this->AuthUser->SiteId);
	
		$this->List = PageType::GetPageTypes($authUser->SiteId);
	}
	
	function AddPageType(){
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
		
		$site = Site::GetBySiteId($siteId);
		
		$pageTypeUniqId = $this->GetPostData("PageTypeUniqId");
		$typeS = $this->GetPostData("TypeS");
		$typeP = $this->GetPostData("TypeP");
		$friendlyId = $this->GetPostData("FriendlyId");
		
		// cleanup friendly id
		$friendlyId = trim($friendlyId);
		$friendlyId = str_replace(' ', '', $friendlyId);
		$friendlyId = strtolower($friendlyId);
		
		// check if it unique
		$isUnique = PageType::IsFriendlyIdUnique($friendlyId, $siteId);
		
		if($isUnique==false){
				
			$tojson = array (
			    'IsSuccessful'  => 'false',
				'Type' => 'Edit',
				'Error' => 'This page type has already been added to your site.'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		else{
		
			// add page type
			$pageType = PageType::Add($friendlyId, $typeS, $typeP, $siteId, $userId, $userId);
		
			// create a page for the list
			$page = 'list';
			$name = $pageType->TypeP;
			$description = '';
			
			$tojson = array (
			    'IsSuccessful'  => 'true',
				'Type' => $pageType->PageTypeUniqId,
				'Message' => 'Page type added successfully'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		
		
	}
	
	function EditPageType(){
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
		
		$pageTypeUniqId = $this->GetPostData("PageTypeUniqId");
		$typeS = $this->GetPostData("TypeS");
		$typeP = $this->GetPostData("TypeP");
		$friendlyId = $this->GetPostData("FriendlyId");
		
		// edit page type
		$pageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);
		$pageType->Edit($friendlyId, $typeS, $typeP, $userId);
		
		// return PageTypeUniqId
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'Type' => $pageType->PageTypeUniqId,
			'Message' => 'Page type updated successfully'
		);
		
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// removes a page type
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

		$pageTypeUniqId = $this->GetPostData("PageTypeUniqId");
		
		$pageType = PageType::GetByPageTypeUniqId($pageTypeUniqId);
		
		$pageType->Delete();
		
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'Type' => $pageType->PageTypeUniqId,
			'Message' => 'Page type removed successfully'
		);
		
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
		
}


?>