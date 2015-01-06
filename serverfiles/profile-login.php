<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xml:lang="en">
<head>

	<title><?php echo prefix." | Login";?></title>
<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1">	
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	   $(document).ready(
				function(){
 $(".menu-icon").click(function(){
    $(".top-menu").toggle();
  });
  $(".profile").click(function(){
                  $(".top-menu").hide();
                 });
 
});	
</script>

<script type="text/javascript">
      var count = 1;
	  var orderby = 'id';
	  var userid = 0;
      jQuery(document).ready(function($) {
		  loadArticle(count, orderby, userid);
          count++;
		  
          $(window).scroll(function(){
			  
                  if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                     loadArticle(count, orderby, userid);
                     count++;
                  }
          }); 
   
      });
	  
	  function loadArticle(pageNumber, order, userid){
		  
		  //alert(pageNumber);
                  $('a#inifiniteLoader').show('fast');
                  $.ajax({
                      url: "ajax-post.php",
                      type:'POST',
                      data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop&orderby=' + order + '&user_id=' + userid, 
                      success: function(html){
                          $('a#inifiniteLoader').hide('1000');
                          $("#con").append(html);    // This will be the div where our content will be loaded
                      }
                  });
              return false;
          }
	  
	  function mview(order,userid) {
		        //userid=0;
				orderby = order;
				$('#con').html('');
				count=1;
				loadArticle(count, orderby, userid);
			    count++;
		  }
		  
		  function forgotpwd(){
       $("#forgotdiv").show('slow');
	   $("#login").hide('slow');
	   $("#forgot12").show('slow');
    }
	
	function goBack() {
    $("#forgotdiv").hide('slow');
	   $("#login").show('slow');
	    $("#forgot12").hide('slow');
	   
    }
		  
		  

</script>

</head><body style="background:#cacaca;">
<div class="instagram"> 
<div class="mainmenu">
<!-- Start Top Menu -->
<div class="topbar">
<div class="homebg"><a href="index.php"><span>Home</span></a></div>
<div class="top-right-panel">
     <?php include("includes/left-menu.php");?>
        </div>

</div>
<!-- End Top Menu -->
</div>
<div class="profile">
<div class="pad5 viewimage">
<div class="contentbox">

<div class="pad5 clear"></div>
<div id="login" >
<strong style="font-size:14px;">Login Details</strong>
<div class="pad5"></div>
 <div style=" color:#FF0000;"><?php echo @$_SESSION['msg'];@$_SESSION['msg']=''?></div>  
<div class="pad5"></div>
<form action="login.php" method="post" name="form1" enctype="multipart/form-data">
<input type="text" name="username" class="instxt" placeholder="User Name" />
<div class="pad5"></div>
 <input type="password" name="password" class="instxt"  placeholder="Password" />
 <div class="pad5"></div>
<input type="submit" class="button" value="Login" />
 </form>
 <div id="forgot"><a onclick="forgotpwd();">Forgot Your Password</a></div>
 </div>
  
  
  <div id="forgotdiv" style="display:none;">
 <form action="send-forgot-pwd.php" method="post" name="form1" enctype="multipart/form-data">
 <input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>">
 <fieldset>
<legend><strong>Forgot Your Password</strong></legend>
 <div class="pad5"></div>
<input  type="email" name="email" value="" required class="instxt" placeholder="Enter Your Email" />
 <div class="pad5"></div>
<input type="submit" class="button"  value="Send"/>
  </fieldset>
 </form>
 <div id="forgot12"><a onclick="goBack();">Back</a></div>
 </div>
  
  
 
<div class="pad5 clear"></div>



 
<div class="pad10 clear"></div>
</div>

</div>
<div class="pad10 clear"><div class="pad10"></div></div>
<!-- Start Top Menu -->
<div class="footbar">
<ul class="footpanel">
<li><a href="index.php" class="allpost" title="All Post"><span>All Post</span></a></li>
<?php if(@$_SESSION['session_admin_userid']!=''){?><li><a href="add-post.php" class="addpost" title="Add Post"><span>Add Post</span></a></li><?php }?>
<li><a href="index.php" class="comment" title="Most Comment"  onClick="mview('total_comment',0);"><span>Most Commented</span></a></li>
<li><a href="index.php" class="like" title="Most Like"  onClick="mview('total_like',0);"><span>Most Liked</span></a></li>
<li><a href="index.php" class="mostviewed" title="Most View"  onClick="mview('total_view',0);"><span>Most Viewed</span></a></li>

</ul>
<div class="footpanel"></div>
<div class="footpanel"></div>
<div class="footpanel"></div>
<div class="footpanel"></div>
</div>
<!-- End Top Menu -->
</div>
</div>
</body>
</html>