<?php
require_once("includes/application-top.php");
$objAdmin = new Admins();
$objAdmin->fun_authenticate_admin();
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_POST);
if(count($_POST)>0){
 $sql_pwd = "SELECT * FROM ".TABLE_USERS." where password='" .MD5($_POST['o_password'])."' and id=" .$_POST['user_id'] ;
$rsResult_pwd = $dbObj->fun_db_query($sql_pwd);
 $total = $dbObj->fun_db_get_num_rows($rsResult_pwd);
if($total){
	if(@$_POST['n_password']==@$_POST['c_password']){
      $arr['password']=md5($_POST['n_password']);
	  $lastID = $dbObj->update_data(TABLE_USERS,'id',$arr,md5($_POST['user_id']));
	  $_SESSION['msg']='<span style=" color:green;font-size:13px;">Password Changed</span>';
	  redirectURL(SITE_ADMIN_URL."logout.php?reset=yes");
     }else{
     $_SESSION['msg']="New password and Comform password are not matching";
     }
}
else
	 {
	$_SESSION['msg']="Old password incorrect";
	 }

       

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
<strong style="font-size:14px;">Reset Password</strong>
<div class="pad5"></div>
<?php if(@$_SESSION['msg']!=''):?><div class="title" style="color:red;"> <?php echo @$_SESSION['msg']; @$_SESSION['msg']='';?></div><?php endif;?>

<form action="" method="post" name="form1" enctype="multipart/form-data">
<input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>">
<input type="password" name="o_password" value="" required placeholder="Old Password" class="instxt">
<div class="pad5"></div>
 <input type="password" name="n_password" value="<?php echo @$_POST['n_password'];?>" required placeholder="New Password" class="instxt">
 <div class="pad5"></div>
 
 <input type="password" name="c_password" value="<?php echo @$_POST['c_password'];?>" required placeholder="Conform Password" class="instxt">
 <div class="pad5"></div>
<input type="submit" class="button" value="Reset Password" />
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
<li><a href="index.php" class="comment" title="Most Comment"  onClick="mview('total_comment',0);"><span>Most Commented</span></a></li>
<li><a href="index.php" class="like" title="Most Like"  onClick="mview('total_like',0);"><span>Most Liked</span></a></li>
<li><a href="index.php" class="mostviewed" title="Most View"  onClick="mview('total_view',0);"><span>Most Viewed</span></a></li>
<?php if(@$_SESSION['session_admin_userid']!=''){?><li><a href="add-post.php" class="addpost" title="Add Post"><span>Add Post</span></a></li><?php }?>


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