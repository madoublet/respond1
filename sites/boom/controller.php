<?php 

include '../common/Utilities.php';
include '../common/API.php';

$ajax = Utilities::GetPostData('Ajax');

if($ajax=='form.send'){ // sends an email for a given form

	$pageUniqId = Utilities::GetPostData("PageUniqId");
	$siteUniqId = Utilities::GetPostData("SiteUniqId");	
	$body = Utilities::GetPostData("Body");		
	$email = Utilities::GetPostData("Email");	
	$subject = Utilities::GetPostData("Subject");

	API::SendFeedback($body, $email, $siteUniqId, $pageUniqId);
	
	die('success');
}
if($ajax=='list.page'){ // handles paging in a list
	
	$isAjax = true;
	$preview = false;
	$siteUniqId = Utilities::GetPostData("SiteUniqId");
	$typeid = Utilities::GetPostData("TypeId");
	$pageNo = Utilities::GetPostData("PageNo");
	$totalpages = Utilities::GetPostData("TotalPages");
	$desclength = Utilities::GetPostData("DescLength");
	$length = Utilities::GetPostData("Length");
	$orderby = Utilities::GetPostData("OrderBy");
	$groupby = Utilities::GetPostData("GroupBy");
	$featuredonly = Utilities::GetPostData("FeaturedOnly");
	$siteroot = Utilities::GetPostData("SiteRoot");
	$rootloc = Utilities::GetPostData("Root");
	$siterootloc = Utilities::GetPostData("SiteRoot");

	include '../common/list.php';
}

?>