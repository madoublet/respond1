<?php 
$rootloc="";
$siterootloc="";
$dataloc="";
$commonloc="../common/";
$domain="sites.respondcms.com/test";
$siteId=38;
$siteUniqId="500f5f75c1a82";
$pageId=361;
$pageType="home";
$default_url="";
include "../common/Utilities.php";
include "../common/API.php";
?><!DOCTYPE html>    <html>  <head>    <title>Test</title>    <meta name="description" content="">    <meta name="keywords" content="">    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">      <link href="http://fonts.googleapis.com/css?family=Share" rel="stylesheet" type="text/css">       <link href="css/home.css" type="text/css" rel="stylesheet" media="screen"><link href="css/bootstrap.min.css" type="text/css" rel="stylesheet" media="screen"><link href="css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet" media="screen">        <!--[if lt IE 9]>
<script src="js/html5.js"></script>
<![endif]-->


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">var root = '';</script>
<script type="text/javascript">var siteroot = '';</script>
<script type="text/javascript" src="https://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2"></script>
<script type="text/javascript" src="js/jquery.respondMap-1.0.1.js"></script>
<script type="text/javascript" src="js/jquery.respondForm-1.0.1.js"></script>
<script type="text/javascript" src="js/jquery.respondList-1.0.1.js"></script>
<script type="text/javascript" src="js/messages.js"></script>
<script type="text/javascript" src="js/global.js"></script>

  </head>    <body id="home">    <header>
  <div>
<a id="logo" href="" alt="Test"><img src="files/respond-app-logo.png"></a><h1>Test</h1>
<?php include '../common/menu.php'; ?>  </div>
</header>

<p id="message">
  <span></span>
  <a class="close" href="#"></a>
</p>    <div id="content" class="container-fluid"><div id="1344308957" class="block row-fluid"><div class="col span12"><h1 id="h1-1343185321">Thank you for building your site with Respond CMS.</h1></div></div><div id="1344308957" class="block row-fluid"><div class="col span4"><h3 id="h3-1342578507">What should I do next?</h3><p id="p-1342578554">Placeholder for future text here.</p><div id="i-1343780899" class="l-image"><a href="http://www.google.com"><img id="screenshot.png" src="files/screenshot.png"></a><p>Hmm, what is up?</p></div></div><div class="col span4"><h3 id="h3-1342579563">Where can I learn more about Respond?</h3><p id="p-1342578841">Placeholder for future text here.</p></div><div class="col span4"><h3 id="h3-1342579026">How can I help?</h3><p id="p-1342579078">Placeholder for future text here.</p></div></div><div id="1344308957" class="block row-fluid"><div class="col span12"><h3>Sample Plugin (rendered when published)</h3>

<p>
	A plugin rendered at publish time will only update when you re-publish a page.  If you want it to render everytime a user views your page, 
	you need to set the <code>render</code> variable to <code>runtime</code>.  Of course, there are performance benefits to having a plugin render only
	at publish time.  But with Respond CMS, the decision is up to you.  See the <b>samplert</b> example for a runtime plugin.
</p>

<h4>Here are some passed variables</h4>

<table class="table table-striped table-bordered">
	<tbody>
		<tr>
			<td>Plugin Id:</td>
			<td>p-1344304585</td>
		</tr>
		<tr>
			<td>Plugin Type:</td>
			<td>samplepub</td>
		</tr>
		<tr>
			<td>Plugin Name:</td>
			<td>Sample Published</td>
		</tr>
		<tr>
			<td>Render At:</td>
			<td>publish</td>
		</tr>
		<tr>
			<td>Has Configurations:</td>
			<td>true</td>
		</tr>
				<tr>
			<td>Var 1 (custom):</td>
			<td>Test 1</td>
		</tr>
						<tr>
			<td>Var 2 (custom):</td>
			<td>Test 2</td>
		</tr>
			</tbody>
</table><?php $id="p-1344308795";$type="samplert";$name="Sample Runtime";$render="runtime";$config="true";$var1="Sample";$var2="Runtime! Boom!";include "plugins/samplert/render.php"; ?></div></div></div>    <!-- begin footer -->
<footer>
  <div>
  <p>
    &copy; Test  </p>
  
  <p class="poweredBy">
    Powered by <a href="http://respondcms.com">Respond CMS</a>
  </p>
  </div>
</footer>

<!-- end footer -->  
    </body>    </html>