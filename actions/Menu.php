<?php

// Menu controller
class Menu extends Actions
{
	public $Name; /* properties */
	public $Description;
	public $Attachments;
	public $AuthUser;
	public $List;
	public $Category;
	public $Type = 'primary';
	public $ShowDialog = 'false'; /* shows the dialog if necessary */
	public $Ajax;
	public $Method;
	public $Count = 0;
	public $View = 'list';
	public $PageTypes;
	public $MenuTypes;
	public $Url = 'http://';
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;
		
		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='menu.save'){
			$this->Save();
		}

		$site = Site::GetBySiteId($this->AuthUser->SiteId);
		$this->Url = 'http://'.$site->Domain;		

		$this->Type = 'primary';

		if(array_key_exists('t', $_GET)){
			$this->Type = $this->GetQueryString('t');
		}

		// fill
		$this->PageTypes = PageType::GetPageTypes($this->AuthUser->SiteId);
		$this->MenuTypes = MenuType::GetMenuTypes($this->AuthUser->SiteId);
		$this->List = MenuItem::GetMenuItemsForType($this->AuthUser->SiteId, $this->Type);
	}
	
	// changes the priority of the menuItem
	function Save(){

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
		
		$site = Site::GetBySiteId($siteId);
		
		$userId = $this->AuthUser->UserId;
		$menuItemUniqIds = $this->GetPostArray("MenuItemUniqIds");
		$names = $this->GetPostArray("Names");
		$cssClasses = $this->GetPostArray("CssClasses");
		$type = $this->GetPostData("Type");
		$urls = $this->GetPostArray("Urls");
		$pageIds = $this->GetPostArray("PageIds");
		
		$length = count($menuItemUniqIds);
		
		MenuItem::DeleteAll($siteId, $type);
		
		$menu = array();
		
		for($x=0; $x<$length; $x++){
			$menuItem = MenuItem::Add($names[$x], $cssClasses[$x], $type, $urls[$x], $pageIds[$x], $x, $siteId, $userId, $userId);
		}
		
		Publish::PublishMenu($site->SiteUniqId);
		
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Error" => 'Menu updated successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
		
}


?>