<?php

// Html controller
class Html extends Actions
{
	public $Ajax = '';
	public $layout;
	public $File;
	public $LayoutUniqId = -1;
	public $Content = '';
	public $Configs = '';
	public $IsConfigured = 0;
	public $Custom;
	public $Dir;

	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;

		// get reference to site
		$site = Site::GetBySiteId($authUser->SiteId);
		$this->Dir = 'sites/'.$site->FriendlyId.'/templates/'.$site->Template.'/html/';

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='layout.update'){
			$this->UpdateLayout();
		}
		
		if($this->Ajax=='layout.addLayout'){
			$this->AddLayout();
		}

		if($this->Ajax=='layout.remove'){
			$this->RemoveLayout();
		}

		if($this->IsPostBack){
			
		}
		else{
			if(array_key_exists('f', $_GET)){
				$this->File = $this->GetQueryString("f");
				
				$siteId = $authUser->SiteId;
			}
		}
	}
	
	function UpdateLayout(){

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

		$site = Site::GetBySiteId($siteId);

		$file = $this->GetPostData("File");
		$name = str_replace('.html', '', $file);
		$content = html_entity_decode($this->GetPostDataFromTextarea("Content"));

		$html_file = $this->Dir.$file;

		file_put_contents($html_file, $content); // save to file

		Publish::PublishAllPages($site->SiteUniqId);

		$tojson = array (
			"IsSuccessful"  => 'true',
			"Message"=>'Layout updated successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);
		die($encoded);
	}
	
	function AddLayout(){
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
		
		$name = $this->GetPostData("Name");

		$file = trim($name); // remove spaces and convert to lower-case for the file name
		$file = str_replace(' ', '', $file);
		$file = strtolower($file);

		$html_file = $this->Dir.$file.'.html';

		file_put_contents($html_file, ''); // save to file
		
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Layout added successfully',
			"File" => $file
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}

	function RemoveLayout(){

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
		
		$file = $this->GetPostData("File");
		
		$html_file = $this->Dir.$file;

		unlink($html_file);

		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Layout removed successfully',
			"File" => $file
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
		
}


?>