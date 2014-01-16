<!DOCTYPE html>
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
<?php

$codeid=$_REQUEST['code'];
$cid=$_REQUEST['cid'];
include_once("function.php");
$arrays=getInfo($codeid);
$ass=$arrays['ass'];
$company=$arrays['company'];
$array=array();

$result= file_get_contents("http://121.199.36.29/company/".$cid."/");
$title=preg_match("/<h2[^>]*>([\s\S]*?)<\/h2>/",$result,$mache);
$title=$mache[1];


$img=preg_match('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/',$result,$mache);
$img="http://121.199.36.29/company/".$cid."/".$mache[1];
$array[0]['img']=$img;


$content=preg_match('/<p\sclass=\"gd_qte\">([\s\S]*?)<\/p>/',$result,$mache);
$content=$mache[1];
$array[1]['content']=$content;

$date=preg_match('/<p\sclass="gd_time"><span>([\s\S]*?)<\/span><\/p>/',$result,$mache);
$array[3]['content']=$mache[1];

$url=preg_match_all('/<a.*?(?: |\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?><\/a.*?>/sim',$result,$mache);

$url=$mache[1];
?>
<!-- Page Title -->
<title><?php echo $title;?>-珍·有爱</title>

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

    <a href="#" class="top-logo"><?php echo $title;?></a>
</div>

<div class="content-box shadow">
    <div class="content">

	<div class="container">
    	<a href="<?php echo $url[0];?>"><img src="<?php echo $img;?>" alt="img" class="responsive-image image-decoration"></a>
    	<h2 class="uppercase"><?php echo $title;?></h2>
        <p style=" color: #595959;
    font-family: 'Open Sans',sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: 25px;">
        	<?php echo $content;?>
        </p>
		
		  <a href="<?php echo $url[1];?>" class="no-bottom demo-button button-minimal grey-minimal fullscreen-button no-bottom red">查看详情</a>
        	<a href="<?php echo $url[2];?>" class="no-bottom demo-button button red fullscreen-button">联系我们</a>
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



