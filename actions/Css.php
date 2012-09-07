<?php

// CSS controller
class Css extends Actions
{
	public $Ajax = '';
	public $Device = 'default';
	public $layout;
	public $File;
	public $LayoutUniqId = -1;
	public $Content = '';
	public $Configs = '';
	public $IsConfigured = 0;
	public $LessDir;
	public $CssDir;
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;

		// get reference to site
		$site = Site::GetBySiteId($authUser->SiteId);
		$this->LessDir = 'sites/'.$site->FriendlyId.'/templates/'.$site->Template.'/less/';
		$this->CssDir = 'sites/'.$site->FriendlyId.'/css/';

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
			}
			else{
				$this->File = '';
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
		$name = str_replace('.less', '', $file);
		$content = html_entity_decode($this->GetPostDataFromTextarea("Content"));

		$less_file = $this->LessDir.$file;
		$css_file = $this->CssDir.$name.'.css';

		file_put_contents($less_file, $content); // save to file

		$less = new lessc;

		try{
		  $less->checkedCompile($less_file, $css_file);

		  $tojson = array (
	        "IsSuccessful"  => 'true',
	        "Message" => 'Stylesheet updated successfully'
		  );
		} 
		catch(exception $e){
		  $tojson = array (
	        "IsSuccessful"  => 'false',
		    "Error" => $e->getMessage()
		  );
		}

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

		$less_file = $this->LessDir.$file.'.less';
		$css_file = $this->CssDir.$name.'.css';

		file_put_contents($less_file, ''); // save to file
		file_put_contents($css_file, ''); // save to file
		
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Stylesheet added successfully',
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
		$file = str_replace('.less', '', $file);
		
		$less_file = $this->LessDir.$file.'.less';
		$css_file = $this->CssDir.$file.'.css';

		unlink($less_file);
		unlink($css_file);

		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'Stylesheet removed successfully',
			"File" => $file
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
		
}


?>