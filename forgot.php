<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

//print_r($_POST);
if(@$_GET['key']!=''){
$sql_pwd = "SELECT * FROM ".TABLE_USERS." where reset_key='".@$_GET['key']."'";
$rsResult_pwd = $dbObj->fun_db_query($sql_pwd);
 $total = $dbObj->fun_db_get_num_rows($rsResult_pwd);
if(count($_POST)>0){
if($total){
	if(@$_POST['n_password']==@$_POST['c_password']){
      $arr['password']=md5($_POST['n_password']);
	$update="UPDATE ".TABLE_USERS." SET password='".$arr['password']."' , reset_key='' WHERE reset_key='".$_GET['key']."'";
    $resule=mysql_query($update);
	  $_SESSION['msg']='<span style=" color:green;font-size:13px;">Password changed</span>';
	 redirectURL(SITE_ADMIN_URL."profile-login.php");
     }else{
     $_SESSION['msg']="New password and Comform password are not matching";
     }
}
else
	 {
	$_SESSION['msg']="Your key is expired";	 
	 }
}
}
else
{
	$_SESSION['msg']="Worng Process";	 
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xml:lang="en">
<head>

	<title>J Wasser &amp; Company</title>
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



</head><body style="background:#ffffff;">
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
<strong style="font-size:14px;">Set Password</strong>
<div class="pad5"></div>
<?php if(@$_SESSION['msg']!=''):?><div class="title" style="color:red;"> <?php echo @$_SESSION['msg'];?></div><?php endif;?>

<form action="forgot.php?key=<?php echo @$_GET['key']?>" method="post" name="form1" enctype="multipart/form-data">
<input type="password" class="instxt" placeholder="New Password"  name="n_password" value="" required/>
<div class="pad5"></div>
<input type="password" class="instxt" placeholder="Confirm Password" name="c_password" value="" required/>
 <div class="pad5"></div>
<input type="submit" class="button" />
 </form>
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