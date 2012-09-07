<?php

// User model
class User{
	public $UserId;
	public $UserUniqId;
	public $Email;
	public $Password;
	public $FirstName;
	public $LastName;
	public $Role;
	public $SiteId;
	public $Created;
	public $Token;
	
	function __construct($userId, $userUniqId, $email, $password, $firstName, $lastName, 
			$role, $siteId, 
			$created, $token){
		$this->UserId = $userId;
		$this->UserUniqId = $userUniqId;
		$this->Email = $email;
		$this->Password = $password;
		$this->FirstName = $firstName;
		$this->LastName = $lastName;
		$this->Role = $role;
		$this->SiteId = $siteId;
		$this->Created = $created;
		$this->Token = $token;
	}
	
	// adds a user
	public static function Add($email, $password, $firstName, $lastName, $role, $siteId){
		
		Connect::init();
		
		$userUniqId = uniqid();
		$email = mysql_real_escape_string($email); // clean data
		$password = mysql_real_escape_string($password);
		$firstName = mysql_real_escape_string($firstName);
		$lastName = mysql_real_escape_string($lastName);
		$role = mysql_real_escape_string($role);
		settype($siteId, 'integer');
		
		$token = null;
	
		$timestamp = gmdate("Y-m-d H:i:s", time());
		// $s_password = md5($password); /* create secure password */
		
		// create a more secure password (http://www.openwall.com/articles/PHP-Users-Passwords)
		$hash_cost_log2 = 8; // Base-2 logarithm of the iteration count used for password stretching
		$hash_portable = FALSE; // Not portable
		
		$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
		$s_password = $hasher->HashPassword($password);
		unset($hasher);
		
		$result = mysql_query(
			"INSERT INTO Users (UserUniqId, Email, Password, FirstName, LastName, 
				Role, SiteId, Created) 
			 VALUES ('$userUniqId', '$email', '$s_password', '$firstName', '$lastName', 
			 	'$role', $siteId, '$timestamp')");
		
		if(!$result) {
		  print "Could not successfully run query User->Add: " . mysql_error() . "<br>";
		  exit;
		}
		
		return new User(mysql_insert_id(), $userUniqId, $email, $s_password, $firstName, $lastName, 
			$role, $siteId, $timestamp, $token); 
	}
	
	// determines whether a login is unique
	public static function IsLoginUnique($login){

		Connect::init();
		
		$login = mysql_real_escape_string($login); // clean data
		
		$count=0;
	
		// Pulls in the Name of the User too
		$result = mysql_query("SELECT Count(*) as Count
			FROM Users where Email='$login'");
			
		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
	
		if($count==0){
			return true;
		}
		else{
			return false;
		}
	}
	
	// edit user
	public function Edit($firstName, $lastName, $role){
		
		Connect::init();
		
		$firstName = mysql_real_escape_string($firstName);
		$lastName = mysql_real_escape_string($lastName);
		$role = mysql_real_escape_string($role);
		
		$query = "UPDATE Users SET FirstName = '$firstName',
			LastName = '$lastName',
			Role = '$role'
			WHERE UserId = $this->UserId";
		
		mysql_query($query);
		
		return;
	}
	
	// edit login
	public function EditLogin($email, $password){
		
		Connect::init();
		
		$email = mysql_real_escape_string($email);
		$password = mysql_real_escape_string($password);
		
		$query = "UPDATE Users SET Email = '$email', Token = '',";
		
		if($password != "temppassword"){
			//$s_password = md5($password); /* create secure password */
			
			// create a more secure password (http://www.openwall.com/articles/PHP-Users-Passwords)
			$hash_cost_log2 = 8; // Base-2 logarithm of the iteration count used for password stretching
			$hash_portable = FALSE; // Not portable
			
			$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
			$s_password = $hasher->HashPassword($password);
			unset($hasher);
			
			$query = $query."Password = '$s_password', ";
		}
		
		$query = substr($query, 0, -2);
		
		$query = $query." WHERE UserId = $this->UserId";
		
		mysql_query($query);
		
		return;
	}
	
	// generate token
	public function SetToken(){
		
		Connect::init();
		
		// create a more secure password (http://www.openwall.com/articles/PHP-Users-Passwords)
		$hash_cost_log2 = 8; // Base-2 logarithm of the iteration count used for password stretching
		$hash_portable = FALSE; // Not portable
		
		$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
		$s_token = $hasher->HashPassword($this->UserUniqId);
		unset($hasher);
		
		$query = "UPDATE Users SET Token = '$s_token' WHERE UserId=$this->UserId";
		
		mysql_query($query);
		
		return $s_token;
	}
	
	// deletes a user
	public static function Delete($userUniqId){
		
		Connect::init();
		
		$userUniqId = mysql_real_escape_string($userUniqId);
		
		$delete = mysql_query("DELETE FROM Users WHERE UserUniqId='$userUniqId'");
	
		return;
	}
	
	// Gets users in an site
	public static function GetUsers($siteId, $pageSize, $pageNo){
		
		Connect::init();
		
		settype($siteId, 'integer'); // clean
		
		$next = $pageSize * $pageNo;
		
		$result = mysql_query("SELECT Users.UserId, Users.UserUniqId, Users.Email, Users.FirstName, Users.LastName, 
			Users.Role, Users.SiteId, Users.Created
			FROM Users
			WHERE Users.SiteId=$siteId ORDER BY Users.LastName ASC LIMIT $next, $pageSize");
		
		return $result;
		
	}
	
	// Gets count of users in an site
	public static function GetUsersCount($siteId){
		
		Connect::init();
		
		$count=0;
		
		$result = mysql_query("SELECT Count(*) as Count
			FROM Users
			WHERE SiteId = $siteId");

		if(mysql_num_rows($result) == 0){
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			$count = $row["Count"];
		}
		
		return $count;	
	}

	// Gets a user for a specific email and password
	public static function Get($email, $password){
		
		Connect::init();
		
		$email = mysql_real_escape_string($email); // clean data
		$password = mysql_real_escape_string($password);
		
		$result = mysql_query("SELECT UserId, UserUniqId, Email, Password, FirstName, LastName, 
			Role, SiteId, Created, Token 
			FROM Users WHERE Email='$email'");
		
		if(!$result) {
		  die("Could not successfully run query User->Get".mysql_error());
		  exit;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$userId = $row["UserId"];
			$userUniqId = $row["UserUniqId"];
			$email = $row["Email"];
			$hash = $row["Password"];
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$role = $row["Role"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			$token = $row["Token"];
			
			// need to check the password
			$hash_cost_log2 = 8; // Base-2 logarithm of the iteration count used for password stretching
			$hash_portable = FALSE; // Not portable
		
			$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
			
			if($hasher->CheckPassword($password, $hash)){ // success
				unset($hasher);
				return new User($userId, $userUniqId, $email, $hash, $firstName, $lastName, 
					$role, $siteId, 
					$created, $token);
			}
			else{ // failure
				unset($hasher);
				return null;
			}
			
		}
	}
	
	// Gets a user for a specific email
	public static function GetByEmail($email){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
		
		$result = mysql_query("SELECT UserId, UserUniqId, Email, Password, FirstName, LastName, 
			Role, SiteId, Created 
			FROM Users WHERE Email='$email'");
		
		if(!$result) 
		{
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$userId = $row["UserId"];
			$userUniqId = $row["UserUniqId"];
			$email = $row["Email"];
			$password = $row["Password"];
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$role = $row["Role"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			$token = $row["Token"];
			
			return new User($userId, $userUniqId, $email, $password, $firstName, $lastName, 
				$role, $siteId, 
				$created, $token);
		}
	}
	
	// Gets a user for a specific token
	public static function GetByToken($token){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
		
		$result = mysql_query("SELECT UserId, UserUniqId, Email, Password, FirstName, LastName, 
			Role, SiteId, Created 
			FROM Users WHERE Token='$token'");
		
		if(!$result) 
		{
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$userId = $row["UserId"];
			$userUniqId = $row["UserUniqId"];
			$email = $row["Email"];
			$password = $row["Password"];
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$role = $row["Role"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			$token = $row["Token"];
			
			return new User($userId, $userUniqId, $email, $password, $firstName, $lastName, 
				$role, $siteId, 
				$created, $token);
		}
	}
	
	// Gets a user for a specific userid
	public static function GetByUserUniqId($userUniqId){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
		
		$result = mysql_query("SELECT UserId, UserUniqId, Email, Password, FirstName, LastName, 
			Role, SiteId, Created, Token 
			FROM Users WHERE UserUniqId='$userUniqId'");
		
		if(!$result) 
		{
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$userId = $row["UserId"];
			$userUniqId = $row["UserUniqId"];
			$email = $row["Email"];
			$password = $row["Password"];
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$role = $row["Role"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			$token = $row["Token"];
			
			return new User($userId, $userUniqId, $email, $password, $firstName, $lastName, 
				$role, $siteId, 
				$created, $token);
		}
	}
	
	// Gets a user for a specific userid
	public static function GetByUserId($userId){
		
		Connect::init();
		
		settype($userId, 'integer'); // clean
		
		$result = mysql_query("SELECT UserId, UserUniqId, Email, Password, FirstName, LastName, 
			Role, SiteId, Created, Token 
			FROM Users WHERE UserId='$userId'");
		
		if(!$result) 
		{
		  return null;
		}

		if(mysql_num_rows($result) == 0) 
		{
		    return null;
		}
		else{
			$row = mysql_fetch_assoc($result);
			
			$userId = $row["UserId"];
			$userUniqId = $row["UserUniqId"];
			$email = $row["Email"];
			$password = $row["Password"];
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$role = $row["Role"];
			$siteId = $row["SiteId"];
			$created = $row["Created"];
			$token = $row["Token"];
			
			return new User($userId, $userUniqId, $email, $password, $firstName, $lastName, 
				$role, $siteId, 
				$created, $token);
		}
	}
	
}

?>