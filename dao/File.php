<?php

// File model
class File{
	
	public $FileId;
	public $FileUniqId;
	public $UniqueName;
	public $FileName;
	public $Size;
	public $Width;
	public $Height;
	public $IsPublic;
	public $Thumbnail;
	public $ContentType;
	public $StorageType;
	public $IsImage;
	public $IsResized;
	public $UserId;
	public $SiteId;
	public $Created;
	
	function __construct($fileId, $fileUniqId, $uniqueName, $fileName, $size, $width, $height, $isPublic, $thumbnail, $contentType, $storageType, $isImage, $isResized, $userId, $siteId, $created){
		$this->FileId = $fileId;
		$this->FileUniqId = $fileUniqId;
		$this->UniqueName = $uniqueName;
		$this->FileName = $fileName;
		$this->Size = $size;
		$this->Width = $width;
		$this->Height = $height;
		$this->IsPublic = $isPublic;
		$this->FileName = $fileName;
		$this->Thumbnail = $thumbnail;
		$this->ContentType = $contentType;
		$this->StorageType = $storageType;
		$this->IsImage = $isImage;
		$this->IsResized = $isResized;
		$this->UserId = $userId;
		$this->SiteId = $siteId;
		$this->Created = $created;
	}
	
	// Adds a file
	public static function Add($fileUniqId, $uniqueName, $fileName, $size, $width, $height, $isPublic, $thumbnail, $contentType, $storageType, $isImage, $isResized, $userId, $siteId){
		
		Connect::init();
		
		$fileUniqId = mysql_real_escape_string($fileUniqId); // clean data
		$uniqueName = mysql_real_escape_string($uniqueName);
		$fileName = mysql_real_escape_string($fileName);
		settype($size, 'integer');
		settype($width, 'integer');
		settype($height, 'integer');
		settype($isPublic, 'integer');
		$thumbnail = mysql_real_escape_string($thumbnail);
		$contentType = mysql_real_escape_string($contentType);
		$storageType = mysql_real_escape_string($storageType);
		settype($userId, 'integer');
		settype($isImage, 'integer');
		settype($isResized, 'integer');
	
		$timestamp = gmdate("Y-m-d H:i:s", time());
		
		$result = mysql_query(
			"INSERT INTO Files (FileUniqId, UniqueName, FileName, Size, Width, Height, IsPublic, Thumbnail, ContentType, StorageType, IsImage, IsResized, UserId, SiteId, Created) 
			 VALUES ('$fileUniqId', '$uniqueName', '$fileName', $size, $width, $height, $isPublic, '$thumbnail', '$contentType', '$storageType', $isImage, $isResized, '$userId', '$siteId', '$timestamp')");
	
		if(!$result) {
		  print "Could not successfully run query File->Add" . mysql_error() . "<br>";
		  exit;
		}
			 
		return new File(mysql_insert_id(), $fileUniqId, $uniqueName, $fileName, $size, $width, $height, $isPublic, $thumbnail, $contentType, $storageType, $isImage, $isResized, $userId, $siteId, $timestamp);
	}
	
	// Edit a file
	public function Edit($size, $width, $height){
		
		Connect::init();
		
		settype($size, 'integer');
		settype($width, 'integer');
		settype($height, 'integer');
		
		$q = "UPDATE Files SET Size=$size, Width=$width, Height=$height  WHERE FileId = $this->FileId";
		
		$result = mysql_query($q);
		
		if(!$result){
		  print "Could not successfully run query File->Edit" . mysql_error() . "<br>";
		  exit;
		}
		
		return;
	}
	
	// Edit a file
	public static function GetUniqName($siteId, $fileName){
		
		Connect::init();
		
		$isUniq = false;
		$count = 0;
		$fileName = mysql_real_escape_string($fileName); // clean data
		settype($siteId, 'integer');
		
		while($isUniq==false){
	
			$result = mysql_query("SELECT FileId
				FROM Files WHERE UniqueName='$fileName' AND SiteId=$siteId");
				
			if(mysql_num_rows($result) == 0){
			    $isUniq=true;
			}
			
			if($isUniq==false){ // increment fileName
				$count = $count+1; 
				
				$parts = explode(".", $fileName); 
				$ext = end($parts); // get extension
				
				$replace = '-'.$count.'.'.$ext;
				
				if($count==1){
					$search = '.'.$ext;
				}
				else{
					$search = '-'.($count-1).'.'.$ext;
				}
				
				// replace last occurrence
				$fileName = substr_replace($fileName, $replace, strrpos($fileName, $search), strlen($search));
			}
		}
		
		return $fileName;
		
			
	}
	
	// Deletes a file
	public static function Delete($fileUniqId){
		
		Connect::init();
	
		$delete1 = mysql_query("DELETE FROM Files WHERE FileUniqId='$fileUniqId'");
	
		return;
	}
	
	// Gets all images for a given user
	public static function GetImagesForUser($userId){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
	
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, Files.FileName, Files.Width, Files.Height,
			Files.Size, Files.Width, Files.Height, Files.IsPublic, Files.Thumbnail, Files.UserId, Files.SiteId,
			Files.ContentType, Files.Created, Users.FirstName, Users.LastName,
			Files.IsImage, Files.IsResized
			FROM Files, Users
			WHERE Files.UserId = $userId AND Files.IsImage = 1
				AND Files.UserId = Users.UserId");
		
		if(!$result) {
		  die("Could not successfully run query File->GetListByPost" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all images for a given user
	public static function GetImagesForSite($siteId){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
	
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, Files.FileName, Files.Width, Files.Height,
			Files.Size, Files.Width, Files.Height, Files.IsPublic, Files.Thumbnail, Files.UserId, Files.SiteId,
			Files.ContentType, Files.Created, Users.FirstName, Users.LastName,
			Files.IsImage, Files.IsResized
			FROM Files, Users
			WHERE Files.SiteId = $siteId AND Files.IsImage = 1
				AND Files.UserId = Users.UserId");
		
		if(!$result) {
		  die("Could not successfully run query File->GetListByPost" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all images that are compatible for pages for a given user
	public static function GetCompatibleImagesForUser($userId, $width, $height){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
	
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, Files.FileName, Files.Width, Files.Height,
			Files.Size, Files.Width, Files.Height, Files.IsPublic, Files.Thumbnail, Files.UserId, Files.SiteId,
			Files.ContentType, Files.Created, Users.FirstName, Users.LastName,
			Files.IsImage, Files.IsResized
			FROM Files, Users
			WHERE Files.UserId = $userId AND Files.IsImage = 1 AND
				Files.Width >= $width Files.Height >= $height AND Files.UserId = Users.UserId");
		
		if(!$result) {
		  die("Could not successfully run query File->GetListByPost" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all images that are compatible for pages for a given user
	public static function GetCompatibleImagesForSite($siteId, $width, $height){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
	
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, Files.FileName, 
			Files.Size, Files.Width, Files.Height, Files.IsPublic, Files.Thumbnail, Files.UserId, Files.SiteId, Files.Width, Files.Height,
			Files.ContentType, Files.Created, Users.FirstName, Users.LastName,
			Files.IsImage, Files.IsResized
			FROM Files, Users
			WHERE Files.SiteId = $siteId AND Files.IsImage = 1 AND
				Files.Width >= $width AND Files.Height >= $height AND Files.UserId = Users.UserId");
		
		if(!$result) {
		  die("Could not successfully run query File->GetListByPost" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all files for the site
	public static function GetFilesForSite($siteId){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
	
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, Files.FileName, 
			Files.Size, Files.Width, Files.Height, Files.IsPublic, Files.Thumbnail, Files.UserId, Files.SiteId,
			Files.ContentType, Files.Created, Users.FirstName, Users.LastName,
			Files.IsImage, Files.IsResized
			FROM Files, Users
			WHERE Files.SiteId = $siteId AND Files.UserId = Users.UserId ORDER BY Files.Created DESC");
		
		if(!$result) {
		  die("Could not successfully run query File->GetListByPost" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets all files for a given user
	public static function GetFilesForUser($userId){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
	
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, Files.FileName, 
			Files.Size, Files.Width, Files.Height, Files.IsPublic, Files.Thumbnail, Files.UserId, Files.SiteId,
			Files.ContentType, Files.Created, Users.FirstName, Users.LastName,
			Files.IsImage, Files.IsResized
			FROM Files, Users
			WHERE Files.UserId = $userId AND Files.UserId = Users.UserId ORDER BY Files.Created DESC");
		
		if(!$result) {
		  die("Could not successfully run query File->GetListByPost" . mysql_error() . "<br>");
		  exit;
		}
		
		return $result;
	}
	
	// Gets a user for a specific userid
	public static function GetByFileId($fileId){
		
		Connect::init();
		
		settype($fileId, 'integer'); // clean
		
		$result = mysql_query("SELECT FileId, Files.FileUniqId, UniqueName, FileName, Size, Width, Height, IsPublic, Thumbnail, ContentType, StorageType, 
			IsImage, IsResized, UserId, SiteId, Created FROM Files WHERE FileId='$fileId'");
		
		if(!$result) {
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$fileId = $row["FileId"];
			$fileUniqId = $row["FileUniqId"];
			$uniqueName = $row["UniqueName"];
			$fileName = $row["FileName"];
			$size = $row["Size"];
			$width = $row["Width"];
			$height = $row["Height"];
			$isPublic = $row["IsPublic"];
			$thumbnail = $row["Thumbnail"];
			$contentType = $row["ContentType"];
			$storageType = $row["StorageType"];
			$isImage = $row["IsImage"];
			$isResized = $row["IsResized"];
			$userId = $row["UserId"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			
			return new File($fileId, $fileUniqId, $uniqueName, $fileName, $size, $width, $height, $isPublic, $thumbnail, $contentType, $storageType, $isImage, $isResized, $userId, $siteId, $created);
		}
	}
	
	// Gets a list of files for a specific user
	public static function GetListByUser($userId, $page){
		
		Connect::init();
		
		$next = 10 * $page;
		
		settype($siteId, 'integer'); // clean
		
		$result = mysql_query("SELECT FileId, FileUniqId, UniqueName, FileName, Size, Width, Height, IsPublic, Thumbnail, ContentType, StorageType, 
			IsImage, IsResized, UserId, SiteId, Created 
			FROM Files WHERE UserId='$userId'
			ORDER BY Files.Created DESC LIMIT $next, 10");
		
		return $result;
	}
	
	// Gets the count of files per site
	public static function GetCountBySite($siteId){
		
		Connect::init();
		
		$count=0;
	
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Count(*) as Count
			FROM Files WHERE SiteId='$siteId'");
			
		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
		
		return $count;
	}
	
	// Gets the count of files per user
	public static function GetCountByUser($userId){
		
		Connect::init();
		
		$count=0;
	
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Count(*) as Count
			FROM Files WHERE UserId='$userId'");
			
		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
		
		return $count;
	}
	
	// Gets the total size of files by user
	public static function GetTotalSizeBySite($siteId){
		
		Connect::init();
		
		$count=0;
	
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT SUM(Files.Size) as Size
			FROM Files WHERE SiteId='$siteId'");
			
		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$size = $row["Size"];
		}
		
		return $size;
	}
	
	// Gets the total size of files by user
	public static function GetTotalSizeByUser($userId){
		
		Connect::init();
		
		$count=0;
	
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT SUM(Files.Size) as Size
			FROM Files WHERE UserId='$userId'");
			
		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$size = $row["Size"];
		}
		
		return $size;
	}
	
	// Gets a list of files for a specific site, page
	public static function GetListBySite($siteId, $page){
		
		Connect::init();
		
		$next = 10 * $page;
		
		settype($siteId, 'integer'); // clean
		
		$result = mysql_query("SELECT Files.FileId, Files.FileUniqId, Files.UniqueName, 
			Files.FileName, Files.Size, Files.IsPublic, Files.Thumbnail, Files.ContentType, 
			Files.StorageType, 
			Files.IsImage, Files.IsResized,
			Files.UserId, Files.SiteId, Files.Created, Users.FirstName, Users.LastName
			FROM Files, Users WHERE Files.UserId = Users.UserId And Files.SiteId='$siteId'
			ORDER BY Files.Created DESC LIMIT $next, 10");
		
		return $result;
	}
	
	// Gets a file for a specific uniqueName
	public static function GetByUniqueName($uniqueName){
		
		Connect::init();
		
		$uniqueName = mysql_real_escape_string($uniqueName); // clean data
		
		$result = mysql_query("SELECT FileId, FileUniqId, UniqueName, FileName, Size, Width, Height, IsPublic, Thumbnail, ContentType, StorageType, 
			IsImage, IsResized, UserId, SiteId, Created FROM Files WHERE UniqueName='$uniqueName'");
	
		if(!$result){
		  return null;
		}

		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$fileId = $row["FileId"];
			$fileUniqId = $row["FileUniqId"];
			$uniqueName = $row["UniqueName"];
			$fileName = $row["FileName"];
			$size = $row["Size"];
			$width = $row["Width"];
			$height = $row["Height"];
			$isPublic = $row["IsPublic"];
			$thumbnail = $row["Thumbnail"];
			$contentType = $row["ContentType"];
			$storageType = $row["StorageType"];
			$isImage = $row["IsImage"];
			$isResized = $row["IsResized"];
			$userId = $row["UserId"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			
			return new File($fileId, $fileUniqId, $uniqueName, $fileName, $size, $width, $height, $isPublic, $thumbnail, $contentType, $storageType, $isImage, $isResized, $userId, $siteId, $created);
		}
	}
	
	// Gets a file for a specific $fileUniqId
	public static function GetByFileUniqId($fileUniqId){
		
		Connect::init();
		
		$uniqueName = mysql_real_escape_string($uniqueName); // clean data
		
		$result = mysql_query("SELECT FileId, FileUniqId, UniqueName, FileName, Size, Width, Height, IsPublic, Thumbnail, ContentType, StorageType, 
			IsImage, IsResized, UserId, SiteId, Created FROM Files WHERE FileUniqId='$fileUniqId'");
	
		if(!$result){
		  return null;
		}

		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$fileId = $row["FileId"];
			$fileUniqId = $row["FileUniqId"];
			$uniqueName = $row["UniqueName"];
			$fileName = $row["FileName"];
			$size = $row["Size"];
			$width = $row["Width"];
			$height = $row["Height"];
			$isPublic = $row["IsPublic"];
			$thumbnail = $row["Thumbnail"];
			$contentType = $row["ContentType"];
			$storageType = $row["StorageType"];
			$isImage = $row["IsImage"];
			$isResized = $row["IsResized"];
			$userId = $row["UserId"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			
			return new File($fileId, $fileUniqId, $uniqueName, $fileName, $size, $width, $height, $isPublic, $thumbnail, $contentType, $storageType, $isImage, $isResized, $userId, $siteId, $created);
		}
	}
}

?>