<!DOCTYPE html>
<?php
include_once("function.php");
$codeid=$_REQUEST['code'];
 $array=getInfo($codeid);
$ass=$array['ass'];
$company=$array['company'];


?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" 		content="Epsilon, mobile, framework, css3, html5, javascript, retina" />
<meta name="Description" 	content="Epsilon based mobile retina webpp template!" />
<!--Favicon shortcut link-->
<link type="image/x-icon"  rel="shortcut icon" href="images/splash/favicon.ico" />
<link type="image/x-icon"  rel="icon"		   href="images/splash/favicon.ico" />
<!--Declare page as mobile friendly --> 
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
<!-- Declare page as iDevice WebApp friendly -->
<meta name="apple-mobile-web-app-capable" content="yes"/>
<!-- iDevice WebApp Splash Screen, Regular Icon, iPhone, iPad, iPod Retina Icons -->
<link rel="apple-touch-icon" sizes="114x114" href="images/splash/splash-icon.png"> 
<link rel="apple-touch-startup-image" href="images/splash/splash-screen.png" media="screen and (max-device-width: 320px)" /> 
<link rel="apple-touch-startup-image" href="images/splash/splash-screen@2x.png" media="(max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2)" /> 
<link rel="apple-touch-startup-image" href="images/splash/splash-screen@3x.png" sizes="640x1096">

<!-- Page Title -->
<title>关于我们-珍.有爱</title>

<!-- Stylesheet Load -->
<link href="styles/style.css"				rel="stylesheet" 	type="text/css">
<link href="styles/framework-style.css" 	rel="stylesheet" 	type="text/css">
<link href="styles/framework.css" 			rel="stylesheet" 	type="text/css">
<link href="styles/icons.css"				rel="stylesheet" 	type="text/css">
<link href="styles/retina.css" 				rel="stylesheet" 	type="text/css" 	media="only screen and (-webkit-min-device-pixel-ratio: 2)" />

<!--Page Scripts Load -->
<script src="scripts/jquery.min.js"		type="text/javascript"></script>	

<script src="scripts/jquery-ui-min.js"  type="text/javascript"></script>
<script src="scripts/colorbox.js"		type="text/javascript"></script>
<script src="scripts/hammer.js"			type="text/javascript"></script>	
<script src="scripts/subscribe.js"		type="text/javascript"></script>
<script src="scripts/contact.js"		type="text/javascript"></script>
<script src="scripts/swipe.js"			type="text/javascript"></script>
<script src="scripts/swipebox.js"		type="text/javascript"></script>
<script src="scripts/retina.js"			type="text/javascript"></script>
<script src="scripts/custom.js"			type="text/javascript"></script>
<script src="scripts/framework.js"		type="text/javascript"></script>
</head>

<body>

    <?php 
  include_once("preloader.php");
?>



<div class="header">
	<a href="#" class="deploy-left-sidebar"></a>

    <a href="#" class="top-logo">关于本网站</a>
</div>

<div class="content-box shadow">
    <div class="content">

	<div class="container">
    	<img src="images/slider/epsilon2.png" alt="img" class="responsive-image image-decoration">
    	<h5 class="uppercase">Welcome to DuoDrawer</h5>
        <p>
        	DuoDrawer is an upgrade version of Drawer, including more features, faster loading, and using the same quality framework and documentation!
            We've included a new sidebar, with the ability to send inline messages right from the sidebar and subscribe to a newsletter as well!
        </p>
		
		  <a href="#" class="no-bottom demo-button button-minimal grey-minimal fullscreen-button no-bottom red">call us</a>
        	<a href="#" class="no-bottom demo-button button red fullscreen-button">sms us</a>
    </div>
	


    
    <div class="decoration"></div>      
 <?php 
  include_once("footer.php");
?>
    </div>
</div>
<?php 
  include_once("left.php");
?>

</body>
</html>



