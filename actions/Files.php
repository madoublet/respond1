<?php

// Files controller
class Files extends Actions
{
	public $ShowDialog = 'false'; /* shows the dialog if necessary */
	public $Ajax;
	public $Method;
	public $List;
	public $Count;
	public $PageSize = 10;
	public $DialogTitle = 'Add User';
	public $IsAdmin = true;
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;
		
		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->AuthUser->Role=='Member' || $this->AuthUser->Role=='Contributor'){
			$this->IsAdmin = false;
		}
		
		if($this->Ajax=='file.remove'){  /* handle user.get ajax call */
			$this->Remove();
		}
		
		if($this->Ajax=='file.upload'){
			$this->UploadFile();
		}
		
		$this->Fill(); // fills the files list
		
	}
	
	// gets the list of files
	function fill(){
		
		if($this->IsAdmin==false){
			$this->Count = File::GetCountByUser($this->AuthUser->UserId);
			$this->List = File::GetFilesForUser($this->AuthUser->UserId); // get files for current user
		}
		else{
			$this->Count = File::GetCountBySite($this->AuthUser->SiteId);
			$this->List = File::GetFilesForSite($this->AuthUser->SiteId); // get files for current user
		}
		
	}
	
	// Removes a file
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

		$fileUniqId = $this->GetPostData("FileUniqId");
		
		$file = File::GetByFileUniqId($fileUniqId);
		
		$siteId = $this->AuthUser->SiteId;
		
		// Remove from s3
		$site = Site::GetBySiteId($siteId);
		
		// Delete file
		File::Delete($fileUniqId);
		
		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'File removed successfully'
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
		
	}
	
	// uploads a file
	function UploadFile(){
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
		$fileName = $_FILES['uploadedFile']['name'];  
		$file = $_FILES['uploadedFile']['tmp_name'];
		$contentType = $_FILES['uploadedFile']['type'];
		$size = intval($_FILES['uploadedFile']['size']/1024);
		
		$parts = explode(".", $fileName); 
		$ext = end($parts); // get extension
		$ext = strtolower($ext); // convert to lowercase
		
		$uniqueId = uniqid();
		
		// create a unique name from the filename
		$uniqueName = File::GetUniqName($this->AuthUser->SiteId, $fileName);
		
		$thumbnail = 't-'.$uniqueName;
		$isImage = 0;
		$width = 0;
		$height = 0;
		
		if($ext=='png' || $ext=='jpg' || $ext=='gif'){ // upload image
			$dir = $this->AuthUser->FileUrl;
			$isImage = 1;
			
			$size=Image::SaveImageWithThumb($dir, $uniqueName, $file);
			
			list($width, $height, $type, $attr) = getimagesize($dir.$uniqueName); // get width and height
		}
		else if($ext=='ico' || $ext=='css' || $ext=='js' || $ext=='pdf' || $ext=='doc' || $ext=='docx'){ // upload file
			$dir = $this->AuthUser->FileUrl;
			
			// upload file
			Utilities::SaveFile($dir, $uniqueName, $file);
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
		
		// add file
		$file = File::Add($uniqueId, $uniqueName, $fileName, $size, $width, $height, 1, $thumbnail, $contentType, 'FS', $isImage, 1, $this->AuthUser->UserId, $this->AuthUser->SiteId);
		
		// creates a response object
		$tojson = array (
		    'IsSuccessful'  => 'true',
			'Message' => 'File uploaded successfully',
		    "FileId"  => $file->FileId,
		    "UniqueId"  => $uniqueId,
		    "UniqueName"  => $uniqueName,
		    "FileName" => $fileName,
			"Size" => $size,
			"ContentType" => $contentType,
			"IsImage" =>$isImage
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
		
}


?>