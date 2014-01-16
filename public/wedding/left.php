<div class="sidebar-left">
	<div class="sidebar-scroll-left">
    	<div class="sidebar-header-left">
			 <div class="sidebar_title">珍·有爱</div>
        	<a href="#" class="close-sidebar-left"></a>
    	</div>
    
       
       
               <a href="index.php?code=<?php echo $codeid;?>" class="nav-item home-nav">首页</a>
			  <a href="intruduction.php?code=<?php echo $codeid;?>" class="nav-item intruduction-nav">新娘新郎介绍</a>
		      <a href="address.php?code=<?php echo $codeid;?>" class="nav-item address-nav">婚宴时间地点</a>
		
        <a href="friend.php?code=<?php echo $codeid;?>" class="nav-item friend-nav">亲朋好友</a>
		<?php
	
		foreach($company as $val){
		
		?>
        <a href="company.php?code=<?php echo $codeid;?>&cid=<?php echo $val['id'];?>" id="submenu-two" class="nav-item photo-nav"><?php echo $val['name'];?></a>
      <?php 
	  
	   }
	  ?>
        <a href="aboutus.php?code=<?php echo $codeid;?>" id="submenu-two" class="nav-item about-nav">关于本网站</a>
       
        <div class="sidebar-decoration"></div>
        <p class="copyright-sidebar" style="margin-left:-20px">© 2013 珍有爱</p>
        
        
        <div class="sidebar-bottom-controls">
            <a href="#">
                <em class="weibo-bottom"></em>
                <p>微博</p>
            </a>
            <a href="#">
                <em class="email-bottom"></em>
                <p>邮件</p>
            </a>
            <a href="#">
                <em class="close-bottom-left"></em>
                <p>关闭</p>
            </a>
            <div class="clear"></div>
        </div>
    </div>
</div>