<?php

// MenuTypes controller
class MenuTypes extends Actions
{
	public $Ajax = '';
	public $List;
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='menuType.add'){
			$this->AddMenuType();
		}
		
		if($this->Ajax=='menuType.edit'){
			$this->EditMenuType();
		}
		
		if($this->Ajax=='menuType.remove'){
			$this->Remove();
		}

		$site = Site::GetBySiteId($this->AuthUser->SiteId);
	
		$this->List = MenuType::GetMenuTypes($authUser->SiteId);
	}
	
	function AddMenuType(){
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
		
		$name = $this->GetPostData("Name");
		$friendlyId = $this->GetPostData("FriendlyId");
		
		// cleanup friendly id
		$friendlyId = trim($friendlyId);
		$friendlyId = str_replace(' ', '', $friendlyId);
		$friendlyId = strtolower($friendlyId);
		
		// check if it unique
		$isUnique = MenuType::IsFriendlyIdUnique($friendlyId, $siteId);
		
		if($isUnique==false){
				
			$tojson = array (
			    'IsSuccessful'  => 'false',
				'Type' => 'Edit',
				'Error' => 'This menu type has already been added to your site.'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		else{
		
			// add menu type
			$menuType = MenuType::Add($friendlyId, $name, $siteId, $userId, $userId);
		
			$tojson = array (
			    'IsSuccessful'  => 'true',
				'Type' => $menuType->MenuTypeUniqId,
				'Message' => 'Menu type added successfully'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
	}
	
	function EditMenuType(){
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
		
		$menuTypeUniqId = $this->GetPostData("MenuTypeUniqId");
		$name = $this->GetPostData("Name");
		$friendlyId = $this->GetPostData("FriendlyId");

		// check if it unique
		$isUnique = MenuType::IsFriendlyIdUnique($friendlyId, $siteId);
		
		if($isUnique==false){
				
			$tojson = array (
			    'IsSuccessful'  => 'false',
				'Type' => 'Edit',
				'Error' => 'This menu type has already been added to your site.'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		else{
		
			// edit menu type
			$menuType = MenuType::GetByMenuTypeUniqId($menuTypeUniqId);
			$menuType->Edit($friendlyId, $name, $userId);
			
			// return MenuTypeUniqId
			$tojson = array (
			    'IsSuccessful'  => 'true',
				'Type' => $menuType->MenuTypeUniqId,
				'Message' => 'Menu type added successfully'
			);
			
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
	}
	
	// removes a menu type
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

		$menuTypeUniqId = $this->GetPostData("MenuTypeUniqId");
		
		$menuType = MenuType::GetByMenuTypeUniqId($menuTypeUniqId);
		
		$menuType->Delete();
		
		// return MenuTypeUniqId
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'MenuTypeUniqId' => $menuTypeUniqId,
			'Message' => 'Menu type removed successfully'
		);
		
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
		
}


?>