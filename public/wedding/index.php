<!DOCTYPE html>
<head>
<?php

include_once("function.php");
$codeid=$_REQUEST['code'];

if(!empty($codeid)){

$array=getInfo($codeid);
$ass=$array['ass'];
$id=$ass->results[0]->objectId;

$company=$array['company'];

$title=$ass->results[0]->couple->boy." & ".$ass->results[0]->couple->girl;
$category=getCategory($id);
$catecount=count($category->results);

}else{



}


$month=array("one","two","three","four","fine","","","","");



?>
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
<link href="styles/coach.css"				rel="stylesheet" 	type="text/css">
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
<script>
function empty(){

  alert("暂无数据！");
}
</script>
</head>

<body>
<?php 
  include_once("preloader.php");
?>
<div class="page-coach">
	<div class="portrait-coach">
        <span  style="top:-10px; left:50px;" class="coach-arrow arrow9">
            <p class="center-text" style="padding-top:20px; padding-left:75px;">Tap to deploy <br> sidebar menu!</p>
        </span>
        
        <span style="top:40px; right:10px;" class="coach-arrow arrow2">
            <p style="padding-top:85px; padding-left:105px;">Contact and subscribe from sidebar!</p>
        </span>
        
        <span style="top:180px; left:50px;" class="coach-arrow arrow1">
            <p class="center-text" style="padding-top:35px; padding-left:70px;">Touch enabled <br> image slider!</p>
        </span>
        
        <span style="top:280px; left:35%;" class="coach-arrow arrow16">
            <p class="center-text" style="padding-top:125px; margin-left:-100px;">Touch anywhere <br> to dismiss!</p>
        </span>

    </div>    
    <div class="landscape-coach">        
        <span style="left:47%; top:15%;" class="coach-arrow arrow12">
            <p style="text-align:center; padding-top:100px; margin-left:-160px;">
            	This page has a lot of responsive features!<br>
                That means they expand when the device is in landscape!<br>
				Flip your device back in portrait mode to continue the tutorial!<br>      
            </p>
        </span>
    </div>
</div>

<div class="header">
	<a href="#" class="deploy-left-sidebar"></a>

    <a href="#" class="top-logo"><?php echo $ass->results[0]->couple->boy;?><span> & </span><?php echo $ass->results[0]->couple->girl;?></a>
</div>

<div class="content-box shadow">
    <div class="content">

        <div class="container no-bottom">
            <div id="slider" class="swipe">
                <div class="swipe-wrap">
				    <?php 
					
					 for($i=0;$i<$catecount;$i++){
					 
					 ?>
                    <div  class="wrap">
				
					 <a href="photos.php?code=<?php echo $codeid;?>&cid=<?php echo $category->results[$i]->objectId;?>">
                      <img class="swipe-image responsive-image" src="<?php echo $category->results[$i]->cover->url?>" alt="img">
					  </a>
				
                      <p class="swipe-text"><?php echo $category->results[$i]->title?></p>
                    </div>
                  <?php } ?>
          
                </div> 
			  
                <ul id="position">
				    <?php 
					  for($i=0;$i<$catecount;$i++){
					   if($i==0){
					?>
                    <li id="<?php echo $month[$i];?>" class="on"></li>
					<?php }else{ ?>
					 <li id="<?php echo $month[$i];?>" class=" "></li>
					<?php }  }?>
                  
                </ul>   
                
                <a href="#" class="prev-but-swipe">NEXT</a>
                <a href="#" class="next-but-swipe">PREV</a>      
            </div>  
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



