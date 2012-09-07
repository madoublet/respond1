<?php 

class API{

	private static $ServiceUrl = 'http://localhost/respond/';

	// sends feedback
	public static function SendFeedback($body, $email, $siteUniqId, $pageUniqId){
		$url = 	self::$ServiceUrl.'services.php?type=sendfeedback'.
				'&body='.urlencode($body).
				'&siteUniqId='.urlencode($siteUniqId).
				'&pageUniqId='.urlencode($pageUniqId);

		
		$obj = Utilities::GetJsonData($url, false);

		$subject = $obj->{'subject'};
		$primaryEmail = $obj->{'email'};

		// send an email
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $site->PrimaryEmail . "\r\n" .
		    		'Reply-To: ' . $site->PrimaryEmail . "\r\n";
		
		mail($primaryEmail, $subject, html_entity_decode($email), $headers); // send email
		
		return true;
	}

	// gets the total # of pages for a list
	public static function GetTotalPages($siteUniqId, $pageTypeUniqId, $featuredOnly){
		$url =  self::$ServiceUrl.'services.php?type=gettotalpages'.
		        '&siteUniqId='.urlencode($siteUniqId).
		        '&pageTypeUniqId='.urlencode($pageTypeUniqId).
		        '&featuredOnly='.urlencode($featuredOnly);

  		$obj = Utilities::GetJsonData($url, false);
  		$total = (int)$obj->{'total'};

  		return $total;
	}
	
	// gets list items
	public static function GetList($siteUniqId, $pageTypeUniqId, $pageSize, $groupBy, $orderBy, $page, $featuredOnly, $rootloc){
		$url =  self::$ServiceUrl.'services.php?type=getlist'.
		        '&siteUniqId='.urlencode($siteUniqId).
		        '&pageTypeUniqId='.urlencode($pageTypeUniqId).
		        '&pageSize='.urlencode($pageSize).
		        '&groupBy='.urlencode($groupBy).
		        '&orderBy='.urlencode($orderBy).
		        '&page='.urlencode($page).
		        '&featuredOnly='.urlencode($featuredOnly).
		        '&rootloc='.urlencode($rootloc);


  		return Utilities::GetJsonData($url, true);
	}

	// gets blog
	public static function GetBlog($siteUniqId, $page, $rootloc){
		$url =  self::$ServiceUrl.'services.php?type=getblog'.
		        '&siteUniqId='.urlencode($siteUniqId).
		        '&page='.$page.
		        '&rootloc='.urlencode($rootloc);

  		return Utilities::GetJsonData($url, true);
	}
}

?>