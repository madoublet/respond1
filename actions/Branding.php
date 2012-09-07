<?php

// Logo controller
class Branding extends Actions
{
	public $Ajax = '';
	public $LogoUrl = '';
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;

		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='branding.update'){
			$this->Update();
		}

		if($this->Ajax=='branding.upload'){
			$this->Upload();
		}

		if(!$this->IsPostBack){
			$site = Site::GetBySiteId($this->AuthUser->SiteId);
			
			$this->LogoUrl = $site->LogoUrl;
		}
	}
	
	function Update(){
		$siteId = $this->AuthUser->SiteId;
		
		$url = $this->GetPostData("Url");
		$file = $this->GetPostData("Url");
		$uniqueName = $this->GetPostData("UniqueName");
		$width = $this->GetPostData("Width");
		$height = $this->GetPostData("Height");
		$x1 = $this->GetPostData("X1");
		$y1 = $this->GetPostData("Y1");
		$scale = $this->GetPostData("Scale");
		$overwrite = $this->GetPostData("Overwrite");
		
		$site = Site::GetBySiteId($siteId);
		
		// Edit logo
		Site::EditLogo($siteId, $uniqueName); 
		
		// Crop logo
		$pos = stripos($file, "?");
		
		if($pos!=false){
			$file = substr($file, 0, $pos);
		}
		
		settype($overwrite, 'integer');
		
		$parts = explode(".", $uniqueName); 
		$ext = end($parts); // get extension
		$ext = strtolower($ext); // convert to lowercase
		$contentType = 'image/png';
		
		if($ext=='gif'){
			$contentType = 'image/gif';
		}
		else if($ext=='jpg'){
			$contentType = 'image/jpg';
		}
		
		$dir = $this->AuthUser->FileUrl;
		
		$o_size = Image::ResizeWithCrop($file, $dir, $uniqueName, $x1, $y1, $scale, $width, $height);
		
		list($width, $height, $type, $attr) = getimagesize($dir.$uniqueName); // get width and height
		
		$new_file = $dir.$uniqueName;
		
		$t_size = Image::ResizeWithCenterCrop($new_file, $dir, 't-'.$uniqueName, 200, 200); // create thumb
		
		$size = ($o_size + $t_size)/1024;
		
		$file = File::GetByUniqueName($uniqueName);
		$file->Edit($size, $width, $height);

		Publish::PublishAllPages($site->SiteUniqId); // republish pages to get the new logos
			
		// creates a response object
		$tojson = array (
		    "FileId"  => $file->FileId,
		    "UniqueId"  => $file->FileUniqId,
		    "UniqueName"  => $uniqueName,
		    "FileName" => $uniqueName,
			"Size" => $size,
			"ContentType" => $contentType,
			"IsImage" => $file->IsImage,
			"Url" => $dir.$uniqueName
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// uploads a logo
	function Upload(){

		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		// Get uploaded file info
		$isImage = 1;
		$isResized = 0;
		
		$fileName = $_FILES['uploadedFile']['name'];  
		$file = $_FILES['uploadedFile']['tmp_name'];
		$contentType = $_FILES['uploadedFile']['type'];
		$size = intval($_FILES['uploadedFile']['size']/1024);
		
		$parts = explode(".", $fileName); 
		$ext = end($parts); // get extension
		$ext = strtolower($ext); // convert to lowercase
		
		$dir = $this->AuthUser->FileUrl;
		
		$uniqueId = uniqid();
		
		// create a unique name from the filename
		$uniqueName = File::GetUniqName($this->AuthUser->SiteId, $fileName);
		
		$thumbnail = 't-'.$uniqueName;
		$width = 0;
		$height = 0;
		
		// upload logo
		if($ext=='png' || $ext=='jpg' || $ext=='gif'){ // upload image
			$size=Image::SaveImageWithThumb($dir, $uniqueName, $file);
			list($width, $height, $type, $attr) = getimagesize($dir.$uniqueName);
		}
		else{
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'File type is not supported'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}
		
		list($width, $height, $type, $attr) = getimagesize($dir.$uniqueName);
		
		// add logo
		$logo = File::Add($uniqueId, $uniqueName, $fileName, $size, $width, $height, 1, $thumbnail, $contentType, 'FS', $isImage, $isResized, $this->AuthUser->UserId, $this->AuthUser->SiteId);
		
		// creates a response object
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'File uploaded successfully',
			"UniqueId" => $uniqueId,
			"UniqueName" => $uniqueName,
		    "LogoUrl"  => $uniqueName
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
		
}


?>