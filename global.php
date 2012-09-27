<?php
	// the email of the super admin
	$sa = 'admin@respondcms.com'; 

	// passcode to create a site (leave blank to not require a passcode)
	$passcode = 'iloverespond'; 

	// email and password of the demo user that appears on the login page (you still need to create a demo user)
	$demoemail = 'demo@respondcms.com';
	$demopassword = 'demo';

	// login and site url for the app
	$loginurl = 'http://app.respondcms.com';
	$siteurl = 'http://app.respondcms.com/sites';
	
	include 'dao/Connect.php';
	include 'dao/User.php';
	include 'dao/Site.php';
	include 'dao/File.php';
	include 'dao/PageType.php';
	include 'dao/MenuType.php';
	include 'dao/Page.php';
	include 'dao/MenuItem.php';
	include 'helper/Utilities.php';
	include 'helper/Validator.php';
	include 'helper/Image.php';
	include 'helper/AuthUser.php';
	include 'helper/simple_html_dom.php';
	require "helper/lessc.inc.php";
	include 'helper/FeedParser.php';
	include 'helper/Generator.php';
	include 'helper/Publish.php';
	include 'helper/PasswordHash.php';
	include 'actions/Actions.php';
?>