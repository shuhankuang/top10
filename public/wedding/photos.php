<!DOCTYPE html>
<?php

include_once("function.php");
$codeid=$_REQUEST['code'];

 
 if(!empty($codeid)){
 $array=getInfo($codeid);
$ass=$array['ass'];
$company=$array['company'];
 $cid=$_REQUEST['cid'];


$title=$ass->results[0]->couple->boy." & ".$ass->results[0]->couple->girl;

$header=init();

$ch = curl_init ('https://cn.avoscloud.com/1/classes/Photo?where={"categoryId":"'.$cid.'"}');

curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$result = curl_exec($ch); 
   $photolist=json_decode($result);
  $phtotcount=count($photolist->results);
 
 }else{
 
 
 
 }
 
 


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
<title><?php echo $title;?></title>

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

    <a href="#" class="top-logo"><?php echo $ass->results[0]->couple->boy;?><span> & </span><?php echo $ass->results[0]->couple->girl;?></a>
</div>

<div class="content-box">
    <div class="content">
    
    	<div class="container no-bottom">
    
    	<!--<h5 class="uppercase"></h5>  
        <div class="decoration"></div> -->  
        
             <ul id="gallery" class="gallery">
			 
			  
			   <?php
			     if($phtotcount>0){
			     for($i=0;$i<$phtotcount;$i++){
			   
			   // ?>
                <li>
                	<a href="<?php echo $photolist->results[$i]->image->url; ?>" title="<?php echo $title;?>">
                    	<img class="image-decoration" src="<?php echo $photolist->results[$i]->thumbnail->url; ?>" alt="<?php echo $title;?>" />
                    </a>
                </li>
             <?php
			 
			   }  }else{
			 
			 ?>
			 
			         <li style="text-align:center; width:100%">
  
                     暂无数据!
                </li>
			 
			 <?php } ?>
            </ul> 
    	</div> 

        <div class="decoration"></div>             
        </div>
 
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



