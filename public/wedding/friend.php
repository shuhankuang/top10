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

<!-- Page Title -->
<title>亲朋好友-珍·有爱</title>

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
<style>
div, a, p, img, blockquote, form, fieldset, textarea, input, label, iframe, code, pre {
    display: block;
    overflow: hidden;
    position: relative;
	
}

.white-notification a {
    background-position: 0 5px;
    background-repeat: no-repeat;
    color: #3B4043;
    display: block;
    font-family: 'Lato',sans-serif;
    font-size: 10px;
    height: 28px;
    line-height: 12px;
    overflow: visible !important;
    padding-left: 40px;
    width: 100%;
}

.white-information {
    background-image:none;
}

table tr td {
    background: -moz-linear-gradient(center top , #FBFBFB, #FAFAFA) repeat scroll 0 0 transparent;
    border-bottom: 1px solid #E0E0E0;
    border-left: 1px solid #E0E0E0;
    border-top: 1px solid #FFFFFF;
    padding: 10px;
}
</style>
</head>

<body>

    <?php 
  include_once("preloader.php");
?>


<?php

$codeid=$_REQUEST['code'];

include_once("function.php");
$array=getInfo($codeid);
$ass=$array['ass'];
$company=$array['company'];


$users=$ass->results[0]->guests;

$usernums=count($users);

if($usernums>0){

$userarray=array();

for($i=0;$i<$usernums;$i++){
$userarray[$i]='"'.$users[$i].'"';
}

$userstr=implode(",",$userarray);



if(!empty($userstr)){

$header=init();
$ch = curl_init ('https://cn.avoscloud.com/1/classes/_User?where={"objectId":{"$in":['.$userstr.']}}');


curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch); 
$assf=json_decode($result);


$ucount=count($assf->results);


$userinfo=array();

if($ucount>0){

  for($i=0;$i<$ucount;$i++){
  
  $userinfo[$i]['nickname']=$assf->results[$i]->nickname;
    $userinfo[$i]['platform']=$assf->results[$i]->platform;
	   $userinfo[$i]['avatarURL']=$assf->results[$i]->avatarURL;
  }
  

 
}

}

}









?>
<div class="header">
	<a href="#" class="deploy-left-sidebar"></a>

    <a href="#" class="top-logo">亲朋好友</a>
</div>

<div class="content-box">
    <div class="content">
    
		
		  <div class="container">
		        <table cellspacing='0' class="table" style=" background: none;
    border:none;
    border-radius: 0px;;
    box-shadow: none;;
    font-size: 10px;
    margin-bottom: 0px;
    text-shadow: none">
        
                
             
               
            
		  <?php
		  $lens=count($userinfo);
		   for($i=0;$i<$lens;$i++){
		   
		   ?>
               <tr>
			     <?php if($i==0){ ?>
				      <td style="width:50px;border-right:0;border-radius: 3px 0px 0px 0px; border-right:0;border-top:0;font-size:14px;border-top: 1px solid #CCCCCC;border-left: 1px solid #CCCCCC;">
				  <?php }else{ ?>
				     <?php if($i==$lens-1){ ?>
<td style="width:50px;border-right:0; border-top:0; font-size:14px; border-bottom: 1px solid #CCCCCC;border-left: 1px solid #CCCCCC;">
					 
					 <?php }else{ ?>
					 
<td style="width:50px;border-right:0; border-top:0; font-size:14px; border-bottom: 1px solid #E0E0E0; border-left: 1px solid #CCCCCC;">
					 <?php } ?>
	
				 <?php } ?>
             
					<div style="-moz-border-radius: 2px;  -webkit-border-radius: 2px;border-radius: 2px;

    width: 36px;

    height: 36px;

    border: 1px solid #E0E0E0; 
	
	padding:2px;

">
<img src="<?php echo $userinfo[$i]['avatarURL'];?>" width="30px" height="30px">
</div>
				</td>
				  <?php if($i==0){ ?>
				       <td style="border-left:0; border-right:0;font-size:14px; padding:14px 14px 14px 0px;border-top: 1px solid #CCCCCC;" class="table-sub-info"><?php echo $userinfo[$i]['nickname'];?></td>
				  
				  <?php }else{?>
				    <?php if($i==$lens-1){ ?>
					  <td style="border-left:0; border-right:0;font-size:14px;border-bottom: 1px solid #CCCCCC; padding:14px 14px 14px 0px" class="table-sub-info"><?php echo $userinfo[$i]['nickname'];?></td>
					<?php }else{ ?>
					  <td style="border-left:0; border-right:0;font-size:14px; padding:14px 14px 14px 0px" class="table-sub-info"><?php echo $userinfo[$i]['nickname'];?></td>
					<?php } ?>
				     
				  
				  <?php } ?>
               
					<?php
					if($userinfo[$i]['platform']=="sina"){
		           ?>
				     <?php if($i==0){ ?>
					   <td style="border-left:0; border-bottom: 1px solid #E0E0E0;text-align:right;border-top:0;font-size:14px;border-radius: 0px 3px 0px 0px; #CCCCCC;border-top: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;"><img src="images/icon/misc/ico_signin_weibo@2x.png" width="30px" height="30px" style="float:right"></td>
					 <?php }else{ ?>
					  <?php if($i==$lens-1){ ?>
					     <td style="border-left:0; border-right:0;  text-align:right;border-bottom:0;border-top:0;border-bottom: 1px solid #CCCCCC;font-size:14px;border-right: 1px solid #CCCCCC;"><img src="images/icon/misc/ico_signin_weibo@2x.png" width="30px" height="30px" style="float:right"></td>
					  <?php }else{ ?>
					     <td style="border-left:0; border-right:0;  text-align:right;border-bottom:0;border-top:0;border-bottom: 1px solid #E0E0E0;font-size:14px;border-right: 1px solid #CCCCCC;"><img src="images/icon/misc/ico_signin_weibo@2x.png" width="30px" height="30px" style="float:right"></td>
					  <?php } ?>
					
					 <?php } ?>
                  
					
					<?php  }else{ ?>
					
					   <?php if($i==0){ ?>
					   <td style="border-left:0; border-bottom: 1px solid #E0E0E0;text-align:right;border-top:0;font-size:14px;border-radius: 0px 3px 0px 0px; #CCCCCC;border-top: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;"><img src="images/icon/misc/ico_signin_qq@2x.png" width="30px" height="30px" style="float:right"></td>
					 <?php }else{ ?>
					 
					   <?php if($i==$lens-1){ ?>
					    <td style="border-left:0; border-right:0;  text-align:right;border-bottom:0;border-top:0;border-bottom: 1px solid #CCCCCC;font-size:14px;border-right: 1px solid #CCCCCC;"><img src="images/icon/misc/ico_signin_qq@2x.png" width="30px" height="30px" style="float:right"></td>
					  <?php }else{ ?>
				  <td style="border-left:0; border-right:0;  text-align:right;border-bottom:0;border-top:0;border-bottom: 1px solid #E0E0E0;font-size:14px;border-right: 1px solid #CCCCCC;"><img src="images/icon/misc/ico_signin_qq@2x.png" width="30px" height="30px" style="float:right"></td>
					  <?php } ?>
					 
					 <?php } ?>
					
					
					<?php } ?>
                </tr>
  <?php } ?>
        </table>
          </div> 

        <div class="decoration" ></div>  
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



