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
<style>
h2{ font-family: "Microsoft Yahei",Arial,sans-serif;}
.grid_box {
    background-color: #FFFFFF;
    border: 1px solid #CDCDCD;
    border-radius: 6px 6px 6px 6px;
    box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px 12px;
}
.grid_box h2 {
    color: #0C0C0C;
    font-weight: bold;
    line-height: 1;
  margin-bottom: 10px;
}
.grid_box p {
}
.grid_box .gd_img img {
    margin-bottom: 9px;
}
.grid_box .gd_qte {
  font-family: "Microsoft Yahei",Arial,sans-serif;
      color: #595959;
    font-size: 14px;
    font-weight: normal;

    line-height: 25px;
	 margin:0px;
}
.g2_box{
 margin:0px;
}
.g2_box li {
    border-bottom: 1px solid #EDECEA;
    margin-bottom: 12px;
    overflow: hidden;
    padding-bottom: 12px;
	list-style:none;
}
.g2_figure {
    display: inline;
    float: left;
    height: 58px;
    margin: 0 12px 0 0;
    width: 58px;
}
.g2_figure img {
    height: 58px;
    width: 58px;
}
.g2_text {
    overflow: hidden;
    padding-top: 0;
}
.g2_box li.last {
    border-bottom: 0 none;
    margin-bottom: 0;
}
.grid_box_2 h2 {
    margin-bottom: 10px;
}

.g2_box li {
    border-bottom: 1px solid #EDECEA;
    margin-bottom: 12px;
    overflow: hidden;
    padding-bottom: 12px;
	font-size: 11px;
    line-height: 18px;

    margin-bottom: 5px;
}

.cons{

    background-color: #FFFFFF;
    border: 1px solid #CDCDCD;
    border-radius: 6px 6px 6px 6px;
    box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px 12px;
}


.cons p{
font-family: 'Open Sans',sans-serif;
    color: #595959;
    font-size: 14px;
    font-weight: normal;
    line-height: 25px;

}


</style>
<?php
$codeid=$_REQUEST['code'];

include_once("function.php");
$arrays=getInfo($codeid);
$ass=$arrays['ass'];
$company=$arrays['company'];

$array=array();

$urls=$ass->results[0]->couple->introURL;
$result= file_get_contents($urls);
preg_match_all('/<div\sclass=\"g2_figure\"><a.*?(?: |\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?><img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?><\/a.*?><\/div>/sim',$result,$mache);

$menimg=$urls.$mache[2][0];
$menurl=$mache[1][0];
$womenimg=$urls.$mache[2][1];
$womenurl=$mache[2][1];

preg_match_all('/<div\sclass=\"g2_text\">([\s\S]*?)<\/div>/sim',$result,$mache);
preg_match('/<h2>([\s\S]*?)<\/h2>/',$mache[1][0],$macths);
$xinlang=$macths[1];
preg_match('/<p\sclass=\"gd_qte\">([\s\S]*?)<\/p>/',$mache[1][0],$macths);
$xinlangpreif=$macths[1];
preg_match('/<h2>([\s\S]*?)<\/h2>/',$mache[1][1],$macths);
$xinniang=$macths[1];
preg_match('/<p\sclass=\"gd_qte\">([\s\S]*?)<\/p>/',$mache[1][1],$macths);
$xinniangpreif=$macths[1];


preg_match('/<div\sclass=\"grid_box grid_box_1\">([\s\S]*?)<\/div>/',$result,$macth);
preg_match('/<h2>([\s\S]*?)<\/h2>/',$macth[1],$macths);
$title=$macths[1];
preg_match('/<a.*?(?: |\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?><img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?><\/a.*?>/',$macth[1],$maches);
$logourl=$maches[1];
$logoimg=$urls.$maches[2];

preg_match('/<p\sclass=\"gd_qte\">([\s\S]*?)<\/p>/',$macth[1],$macths);
$content=$macths[1];

?>
<!-- Page Title -->
<title>新娘新郎介绍-珍·有爱</title>

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

    <a href="#" class="top-logo">新郎新娘介绍</a>
</div>

<div class="content-box shadow">
    <div class="content">
	<div class="container grid_box">
<ul class="g2_box">
				<li>
					<div class="g2_figure"><a href="<?php echo $menurl;?>"><img src="<?php echo $menimg;?>" alt=""></a></div>
					<div class="g2_text">
						   <h2 style="padding-top: 0px;font-size:14px;color: #666666;"><?php echo $xinlang;?></h2>
						   <p class="gd_qte"><?php echo $xinlangpreif;?></p>
					</div>
				</li>
				<li class="last">
			<div class="g2_figure"><a href="<?php echo $womenurl;?>"><img src="<?php echo $womenimg;?>" alt=""></a></div>
					<div class="g2_text">
						   <h2  style="font-size:14px;color: #666666;"><?php echo $xinniang;?></h2>
						   <p class="gd_qte"><?php echo $xinniangpreif;?></p>
					</div>
				</li>
			  </ul>
	</div>
	<div class="container  cons" >
	<h2 class="uppercase" style="color: #666666;
    font-size: 14px;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 16px;">新郎新娘介绍</h2>
    	<a href="<?php echo $logourl;?>"><img src="<?php echo $logoimg;?>" alt="img" class="responsive-image image-decoration"></a>
    	
        <p>
        	<?php echo $content;?>
        </p>
		
		
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



