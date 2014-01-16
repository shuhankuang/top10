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
<title>婚宴时间地点-珍·有爱</title>

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
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=26320052b68c111b062c9de50216ced5"> 
</script>
<style>
.table-sub-title{width:100px}

</style>
</head>

<body>

    <?php 
  include_once("preloader.php");
?>


<?php

include_once("function.php");
$codeid=$_REQUEST['code'];
$array=getInfo($codeid);
$ass=$array['ass'];
$company=$array['company'];
$title=$ass->results[0]->location->title;


$x=$ass->results[0]->location->latitude;

$y=$ass->results[0]->location->longitude;

$traffic="公交:".$ass->results[0]->location->traffic;

$time=$ass->results[0]->location->time;

$address=$ass->results[0]->location->address;

$businfo=$ass->results[0]->location->traffic;



?>
<div class="header">
	<a href="#" class="deploy-left-sidebar"></a>

    <a href="#" class="top-logo">婚宴时间地点</a>
</div>

<div class="content-box">
    <div class="content">
    
         <div class="container">
  
            <div id="container" class="maps-container">
      
            </div>
			
			  <script type="text/javascript">

// 百度地图API功能

var map = new BMap.Map("container");
var point = new BMap.Point(<?php echo $y;?>,<?php echo $x;?>);
map.centerAndZoom(point, 14);
var marker1 = new BMap.Marker(new BMap.Point(<?php echo $y;?>,<?php echo $x;?>));  // 创建标注
map.addOverlay(marker1); 

var opts = {
  width : 0,     
  height: 0,  
  title : "<?php echo $title;?>" , 
  enableMessage:false,
  message:""
}
var infoWindow = new BMap.InfoWindow("", opts);  // 创建信息窗口对象
map.openInfoWindow(infoWindow,point); //开启信息窗口
</script>
        </div>
		
		  <div class="container" >
         
           <table cellspacing='0' class="table" style="background: none;
    border:none;
    border-radius: 0px;
    box-shadow: none;
    font-size: 10px;
    margin-bottom: 0px;
    text-shadow: none;
	" >
                <tr>
                   <td class="table-sub-title" style="border-left:0; border-radius: 3px 0px 0px 0px; border-right:0;border-top:0;font-size:14px;border-top: 1px solid #CCCCCC;border-left: 1px solid #CCCCCC;">婚宴时间</td>
                    <td class="table-sub-info" style="border-right:0;border-top:0;font-size:14px;border-radius: 0px 3px 0px 0px;border-top: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC; "><?php echo $time;?></td>
           
                </tr>
                    
                <tr>
                    <td class="table-sub-title" style="border-left:0;border-top:0;font-size:14px;border-left: 1px solid #CCCCCC;">婚宴地点</td>
                    <td class="table-sub-info" style="border-right:0;border-top:0;font-size:14px;border-right: 1px solid #CCCCCC;"><?php echo $address;?></td>
                </tr>
				
				   <tr>
                    <td class="table-sub-title" style="border-left:0;border-top:0;font-size:14px;border-bottom: 1px solid #CCCCCC;border-left: 1px solid #CCCCCC;">交通信息</td>
                    <td class="table-sub-info" style="border-right:0;border-top:0;font-size:14px;border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;"><?php echo $businfo;?></td>
                </tr>
               
            </table>
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



