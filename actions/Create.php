<?php

// Create controller
class Create extends Actions
{
	public $Ajax = '';
	public $Passcode = '';
	public $SiteUrl;

	function __construct($passcode, $siteurl){
		
		parent::__construct();
		
		$this->Ajax = $this->GetPostData("Ajax"); /* check for any ajax calls */
		$this->Passcode = $passcode;
		$this->SiteUrl = $siteurl;
		
		if($this->Ajax=='create.create'){
			$this->Create();
		}
		
		if($this->IsPostBack){
			
		}
	}
	
	function Create(){
		
		// setup basic settings
		$passcode = $this->GetPostData("Passcode");
		$name = $this->GetPostData("Name");
		$friendlyId = $this->GetPostData("FriendlyId");
		$domain = $this->SiteUrl.'/'.$friendlyId;
		$domain = str_replace('http://', '', $domain);
		$logoUrl = 'sample-logo.png';
		$template = 'simple';
	
		// get admin user
		$email = $this->GetPostData("Email");
		$firstName = $this->GetPostData("FirstName");
		$lastName = $this->GetPostData("LastName");
		$password = $this->GetPostData("Password");
		$retype = $this->GetPostData("Retype");
		$inviteCode = $this->GetPostData("InviteCode");		
		$isSiteUnique = Site::IsFriendlyIdUnique($friendlyId);

		if($passcode != $this->Passcode){
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'The passcode you provided is incorrect. Please contact the site admin to get the correct passcode'
			);
				
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		
		if($isSiteUnique==false){
			
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'The domain prefix you provided already exists in our system. Please select a new prefix.'
			);
				
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		
		$isUserUnique = User::IsLoginUnique($email);
		
		if($isUserUnique==false){
			
			$tojson = array (
			    "IsSuccessful"  => 'false',
				"Error" => 'The email you provided already exists in our system. Please select a new email.'
			);
				
			// encode to json
			$encoded = json_encode($tojson);
			 
			die($encoded);
		}
		
		// get the Site from the domain
		$site = Site::Add($domain, $name, $friendlyId, $logoUrl, $template, $email); // add the Site
		
		Site::EditTemplate($site->SiteId, $template); // add colors to template
		
		$storage = 0; // admins do not have allocated space
		
		// add the Admin for the domain
		$user = User::Add($email, $password, $firstName, $lastName, 'Admin', $site->SiteId);
		
		// create the home page
		$description = '';
		$content = '';
		$filename = 'layouts/home.html';
				
		if(file_exists($filename)){
			$content = file_get_contents($filename);
		}
		
		$homePage = Page::Add('Home', $description, 'home', 'home',
			-1, $site->SiteId, $user->UserId, $user->UserId,
			1, -1);

		Publish::PublishFragment($site->FriendlyId, $homePage->PageUniqId, 'publish', $content);
		
		// add the general page type and create a list
		$pageType = PageType::Add('page', 'Page', 'Pages', $site->SiteId, $user->UserId, $user->UserId);
		
		// create the sample page
		$description = '';
		$content = '';
		$filename = 'layouts/about.html';
				
		if(file_exists($filename)){
			$content = file_get_contents($filename);
		}
		
		$aboutUs = Page::Add('About', $description, 'content', 'content',
			$pageType->PageTypeId, $site->SiteId, $user->UserId, $user->UserId,
			1, -1);
		$aboutUs->EditFriendlyId('about');

		Publish::PublishFragment($site->FriendlyId, $aboutUs->PageUniqId, 'publish', $content);
			
		// create the contact us page
		$description = '';
		$content = '';
		$filename = 'layouts/contact.html';
				
		if(file_exists($filename)){
			$content = file_get_contents($filename);
		}
		
		$contactUs = Page::Add('Contact', $description, 'content', 'content',
			$pageType->PageTypeId, $site->SiteId, $user->UserId, $user->UserId,
			1, -1);
		$contactUs->EditFriendlyId('contact');

		Publish::PublishFragment($site->FriendlyId, $contactUs->PageUniqId, 'publish', $content);
			
		// create the error page
		$description = '';
		$content = '';
		$filename = 'layouts/error.html';
				
		if(file_exists($filename)){
			$content = file_get_contents($filename);
		}
		
		$pageNotFound = Page::Add('Page Not Found', $description, 'content', 'content',
			$pageType->PageTypeId, $site->SiteId, $user->UserId, $user->UserId,
			1, -1);
		$pageNotFound->EditFriendlyId('error');

		Publish::PublishFragment($site->FriendlyId, $pageNotFound->PageUniqId, 'publish', $content);
		
		// create the menu
		$homeUrl = '/';
		$aboutUsUrl = 'page/about';
		$contactUsUrl = 'page/contact';
		MenuItem::Add('Home', '', 'primary', $homeUrl, -1, 0, $site->SiteId, $user->UserId, $user->UserId);
		MenuItem::Add('About', '', 'primary', $aboutUsUrl, $aboutUs->PageId, 2, $site->SiteId, $user->UserId, $user->UserId);
		MenuItem::Add('Contact', '', 'primary', $contactUsUrl, $contactUs->PageId, 3, $site->SiteId, $user->UserId, $user->UserId);
		
		// publishes a template for a site
		Publish::PublishTemplate($site, $template);
		
		// publish the site
		Publish::PublishCommonForEnrollment($site->SiteUniqId);
		Publish::PublishSite($site->SiteUniqId);
		
		$this->SendEmail($email, $firstName, $lastName, $name, $domain);
		
		// put together array
		$tojson = array (
		    "IsSuccessful"  => 'true'
		);
			
		// encode to json
		$encoded = json_encode($tojson);
		 
		die($encoded);
	}
	
	// sends the email
	function SendEmail($email, $firstName, $lastName, $name, $siteUrl){
		
		$subject = '';
		
		$subject = 'New site created for '.$name;

			
		$message = '<html><head><title>'.$subject.'</title></head>';
		$message = $message.'<body><table><col width="200">';
		$message = $message.'<tr><td>Email:</td><td>'.$email.'</td></tr>';
		$message = $message.'<tr><td>Company Name:</td><td>'.$name.'</td></tr>';
		$message = $message.'<tr><td>Site Url:</td><td>'.$siteUrl.'</td></tr>';
		
		$message = $message.'</table></body></html>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: no-reply@respondcms.com' . "\r\n" .
    				'Reply-To: no-reply@respondcms.com' . "\r\n";

		//mail('admin@respondcms.com', $subject, $message, $headers);
		
		return;
	}
		
}


?>