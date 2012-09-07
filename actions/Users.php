<?php

// Users controller
class Users extends Actions
{
	public $Email; /* properties */
	public $FirstName;
	public $LastName;
	public $Password;
	public $Retype;
	public $Role;
	public $ShowDialog = 'false'; /* shows the dialog if necessary */
	public $Ajax;
	public $Method;
	public $Count = 0;
	public $PageSize = 10;
	public $DialogTitle = 'Add User';
	
	function __construct($authUser){
		
		parent::__construct(); /* need to call parent constructor */
		
		$this->AuthUser = $authUser;
		
		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		
		if($this->Ajax=='users.remove'){
			$this->Delete();
		}

		if($this->Ajax=='users.update'){
			$this->Update();
		}

		if($this->IsPostBack){ /* process the form submission */
			// nothing here yet			
		}

		$this->Fill(); /* fills the posts list */
	
	}
	
	function Fill(){
		
		$pageNo = $this->GetQueryString("page");
		
		if($pageNo=='')$pageNo = 0;
		
		$this->List = User::GetUsers($this->AuthUser->SiteId, $this->PageSize, $pageNo); /* get users for the current site */
		$this->Count = User::GetUsersCount($this->AuthUser->SiteId);
		
	}

	function Update(){
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
		$userUniqId = $this->GetPostData("UserUniqId");
		$email = $this->GetPostData("Email");
		$password = $this->GetPostData("Password");
		$firstName = $this->GetPostData("FirstName");
		$lastName = $this->GetPostData("LastName");
		$role = $this->GetPostData("Role");
		
		if($userUniqId==-1){

			$isUnique = User::IsLoginUnique($email);
			
			// first we need to validate that the login is unique
			if($isUnique==false){
				
				$tojson = array (
				    "IsSuccessful"  => 'false',
					"Error" => 'The email provided is not unique. Please update the email and try again.'
				);
					
				// encode to json
				$encoded = json_encode($tojson);
				 
				die($encoded);
			}
			
			$user = User::Add($email, $password, $firstName, $lastName, $role, $siteId);

			$message = 'User added successfully';
		}
		else{

			$user = User::GetByUserUniqId($userUniqId);
			
			if($user->Email != $email){
				$isUnique = User::IsLoginUnique($email);
		
				// first we need to validate that the login is unique
				if($isUnique==false){
					
					$tojson = array (
					    "IsSuccessful"  => 'false',
						"Error" => 'The email provided is not unique. Please update the email and try again.'
					);
						
					// encode to json
					$encoded = json_encode($tojson);
					 
					die($encoded);
				}
			}
			
			$user->Edit($firstName, $lastName, $role);

			$user->EditLogin($email, $password);

			$message = 'User updated successfully';
		}
	
		$tojson = array (
			    "IsSuccessful"  => 'true',
			    "Message" => $message,
				"UserUniqId" => $user->UserUniqId
			);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
		
	}
	
	function Delete(){
		if($this->AuthUser->Role=='Demo'){ // handle demo mode
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'Not available in demo mode'
			);

			// encode to json
			$encoded = json_encode($tojson);

			die($encoded);
		}

		$userUniqId = $this->GetPostData("UserUniqId");
		
		User::Delete($userUniqId); // deletes the user

		$tojson = array (
		    "IsSuccessful"  => 'true',
			"Message" => 'User deleted successfully',
			"UserUniqId" => $userUniqId
		);

		// encode to json
		$encoded = json_encode($tojson);

		die($encoded);
	}
		
}


?>