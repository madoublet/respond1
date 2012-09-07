<?php

// Forgot controller
class Forgot extends Actions
{
	public $Ajax;
	public $UserUniqId = '';
	public $Email;
	
	function __construct(){
		
		parent::__construct(); // need to call parent constructor
		
		$this->Ajax = $this->GetPostData("Ajax"); // check for ajax calls
		
		if($this->Ajax=='user.requestReset'){
			$this->RequestReset();
		}
		
		if($this->Ajax=='password.reset'){
			$this->ResetPassword();
		}
		
		$token = urldecode($this->GetQueryString('t'));
		
		
		if($token!=''){
			$user = User::GetByToken($token);
			$this->Email = $user->Email;
			$this->UserUniqId = $user->UserUniqId;
		}
		
	}
	
	function RequestReset(){
		$email = $this->GetPostData("Email");
		
		$user = User::GetByEmail($email);
		
		if($user){
			
			$site = Site::GetBySiteId($user->SiteId);
			
			$token = urlencode($user->SetToken());
		
			// send email
			$this->SendEmail($user->Email, $user->UserUniqId, $site->Name, $token);
			
			// put together array
			$tojson = array (
			    'IsSuccessful'  => 'true',
				'Token' => $token,
				'Success' => 'We have sent an email to '.$user->Email.'. You can reset your password using the link in the email.'
			);
				
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
			
		}
		else{
			// put together array
			$tojson = array (
			    'IsSuccessful'  => 'false',
				'Error' => 'The email you typed in is invalid.'
			);
				
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		
	}
	
	function ResetPassword(){
		
		$userUniqId = $this->GetPostData("QueryString");
		$password = $this->GetPostData("Password");
		
		$user = User::GetByUserUniqId($userUniqId);
		
		$user->EditLogin($user->Email, $password);
		
		// put together array
		$tojson = array (
		    "IsSuccessful"  => 'true',
		    "Success"  => 'You have successfully reset your password.'
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);	
	}
	
	// sends the email
	function SendEmail($email, $userUniqId, $siteName, $token){
		
		// send an email to register the account
		$to = $email;
		$subject = 'Reset your password for your '.$siteName.' Account';
		$message = '<html>
			<head>
			  <title>Reset your password for your '.$siteName.' Account</title>
			</head>
			<body>
			  <p>
			  	To reset your password, click on the <br>
				<a href="http://app.respondcms.com/forgot.php?t='.$token.'">
					http://app.respondcms.com/forgot.php?u='.$token.'
				</a>
			  </p>
			</body>
			</html>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: no-reply@respondcms.com' . "\r\n" .
    				'Reply-To: no-reply@respondcms.com' . "\r\n";

		mail($to, $subject, $message, $headers);
	}
		
}


?>