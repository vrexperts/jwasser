<?php include("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

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
  $(".contentbox").click(function(){
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
<div id="tabs-4">
<strong style="font-size:14px;">Create User</strong>
<div class="pad5"></div>
<div style=" color:#FF0000;"><?php echo @$_SESSION['msg'];@$_SESSION['msg']='';?></div> 
<form action="create.php" method="post" enctype="multipart/form-data">

<div class="profiledetails">
<input type="text" name="name" value="" class="instxt"  required placeholder="Name"/>
 <div class="pad5"></div>
<input type="email" name="email" value="" class="instxt" required placeholder="Email"/>
 <div class="pad5"></div>
 <input type="text" name="username" value="" class="instxt" required placeholder="Username"/>
 <div class="pad5"></div>
<input type="text" name="password" value="" class="instxt" required placeholder="Password"/>

<div class="pad5"></div>
 <input type="file" id="FileID"  name="images"/>

 <div class="pad5"></div>
 <input type="hidden" name="post_status" value="0" />

 <div class="pad5"></div>
<input type="submit" class="button" value="Create Profile" />
</div>

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
<li><a href="profile.php" class="allpost" title="All Post"><span>All Post</span></a></li>
<?php if(@$_SESSION['session_admin_userid']!=''){?><li><a href="add-post.php" class="addpost" title="Add Post"><span>Add Post</span></a></li><?php }?>
<li><a href="profile.php" class="comment" title="Most Comment"  onClick="mview('total_comment',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Commented</span></a></li>
<li><a href="profile.php" class="like" title="Most Like"  onClick="mview('total_like',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Liked</span></a></li>
<li><a href="profile.php" class="mostviewed" title="Most View"  onClick="mview('total_view',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Viewed</span></a></li>
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