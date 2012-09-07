<?php 
$rootloc="../";
$siterootloc="../";
$dataloc="../";
$commonloc="../../common/";
$domain="app.respondcms.com/sites/matt";
$siteId=48;
$siteUniqId="5044383333cce";
$pageId=403;
$pageType="content";
$default_url="/page/contact.php";
include "../../common/Utilities.php";
include "../../common/API.php";
?><!doctype html>  <!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->  <!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->  <!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->  <!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->  <head>    <meta charset="utf-8">    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">    <title>Matt - Contact</title>    <meta name="description" content="">    <meta name="keywords" content="">    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">      <meta name="viewport" content="width=device-width, initial-scale=1.0">      <link href="../css/content.css" type="text/css" rel="stylesheet" media="screen"><link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet" media="screen"><link href="../css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet" media="screen">      </head>    <body id="contact" class="page">    <div id="wrapper">    <header class="navbar">
  <div>
<a id="logo" href="../" alt="Matt"><img src="../files/sample-logo.png"></a><h1>Matt</h1>
<?php include '../../common/menu.php'; ?>  </div>
</header>

<p id="message">
  <span></span>
  <a class="close" href="#"></a>
</p>            <div id="content" class="container-fluid">      <div id="block-1" class="block row-fluid">  	<div class="col span12">  		<h1 id="h1-1">Contact Us</h1>  		<p id="paragraph-1">Placeholder for the Contact Us page.</p>  		<form class="form-horizontal form-respond">
  <input class="siteUniqId" type="hidden" value="5044383333cce">
  <input class="pageUniqId" type="hidden" value="504438337eaa6">
    			<div class="control-group" data-type="text" data-required="true">  				<label for="first-name" class="control-label">First Name</label>  				<div class="controls"><input id="first-name" type="text"></div>  			</div>  			<div class="control-group" data-type="text" data-required="true">  				<label for="last-name" class="control-label">Last Name</label>  				<div class="controls"><input id="last-name" type="text"></div>  			</div>  			<div class="control-group" data-type="text" data-required="true">  				<label for="email" class="control-label">Email</label>  				<div class="controls"><input id="email" type="text"></div>  			</div>  			<div class="control-group" data-type="radiolist">  				<label for="preferred-contact-method" class="control-label">Preferred Contact Method</label>  				<div class="controls">  					<span class="list">  						<label><input type="radio" value="Email" name="preferred-contact-method">Email</label>  						<label><input type="radio" value="Phone" name="preferred-contact-method">Phone</label>  					</span>  				</div>  			</div>  			<div class="control-group" data-type="textarea">  				<label for="additional-information" class="control-label">Additional Information</label>  				<div class="controls"><textarea id="additional-information"></textarea></div>  			</div>  		
  <p>
    <button type="button" class="btn btn-primary">Submit</button>
  </p>
</form>

  	</div>  </div>        </div>  </div>      <!-- begin footer -->
<footer>
  <div>
  <p>
    &copy; Matt  </p>
  
  <p class="poweredBy">
    Powered by <a href="http://respondcms.com">Respond CMS</a>
  </p>
  </div>
</footer>

<!-- end footer -->    <!--[if lt IE 9]>
<script src="../js/html5.js"></script>
<![endif]-->


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript">var root = '../';</script>
<script type="text/javascript">var siteroot = '../';</script>
<script type="text/javascript" src="https://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2"></script>
<script type="text/javascript" src="../js/jquery.respondMap-1.0.1.js"></script>
<script type="text/javascript" src="../js/jquery.respondForm-1.0.1.js"></script>
<script type="text/javascript" src="../js/jquery.respondList-1.0.1.js"></script>
<script type="text/javascript" src="../js/messages.js"></script>
<script type="text/javascript" src="../js/global.js"></script>

  
    </body>    </html>