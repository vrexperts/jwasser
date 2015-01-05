<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
$objAdmin = new Admins();
$objAdmin->fun_authenticate_admin();

?>
<?php echo @$_SESSION['session_admin_userid'];?>;
 <?php $user = $dbObj->get_row(TABLE_USERS,"id=".$_SESSION['session_admin_userid']);?>
 
 <?php $sqlSel_post = "SELECT * FROM " . TABLE_POST ." where user_id=".$_SESSION['session_admin_userid'];
       $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
       $total_Post = $dbObj->fun_db_get_num_rows($rsResult_post);
	   $total_pages=ceil($total_Post/limit);
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
         });
		 
		 function changeview(view) {
			  $( "#changeview" ).removeClass( view )
			  $( "#changeview" ).addClass( "listview" );
		  }
		  function changeview1(view) {
			  $( "#changeview" ).removeClass( view )
			  $( "#changeview" ).addClass( "gridview" );
		  }	
</script>
 <script type="text/javascript">
      var count = 1;
	  var orderby = 'id';
	  var userid = <?php echo @$_SESSION['session_admin_userid'];?>;
	  var total_page=<?php echo $total_pages;?> 
      jQuery(document).ready(function($) {
		  loadArticle(count, orderby, userid,total_page);
          count++;
		  
          $(window).scroll(function(){
			  
                  if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                     loadArticle(count, orderby, userid,total_page);
                     count++;
                  }
          }); 
   
      });
	  
	  function loadArticle(pageNumber, order, userid,total_page){
		  //alert(total_page);
		
		  if(total_page>=pageNumber){
                  $('a#inifiniteLoader').show('fast');
                  $.ajax({
                      url: "ajax-post.php",
                      type:'POST',
                      data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop&orderby=' + order + '&user_id=' + userid, 
                      success: function(html){
                          $('a#inifiniteLoader').hide('1000');
                          $("#changeview").append(html);    // This will be the div where our content will be loaded
                      }
                  });
              return false;
          }
	  }
	  
	  function mview(order,userid) {
		        //userid=0;
				orderby = order;
				$('#changeview').html('');
				count=1;
				loadArticle(count, orderby, userid,total_page);
			    count++;
		  }
		  
		  

      

       function viewplus(post_id){
		   //alert(post_id);
            $.ajax({url:"viewpost.php?post_id="+post_id,success:function(result){
           }});
        }

 
</script>

</head><body style="background:#ffffff;">
<?php //print_r($_SESSION);die;?>
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

<div class="pad5">
<div class="profile">
<div class="brdb">
<div class="pleft">

<?php if(@$user['images']!=''){?>
    <img src="<?php echo admin_path.$user['images'];?>" />
    <?php } else {?>
    <img src="<?php echo admin_path."user/no-images.png";?>" />
    <?php }?>



</div>
<div class="pright">
<div class="brdb pad5">
<div class="prt-panel">
<a href="#">
<span class="value">
<?php echo $total_Post;?>
</span>
<span class="label">My Posts</span>
</a>
</div>
<?php $sql="SELECT sum(`total_like`) as total_likes,sum(`total_view`) as total_view,sum(`total_comment`) as total_comment	 FROM `post` WHERE `user_id`=".$_SESSION['session_admin_userid']." group by user_id";
$rsResult_like = $dbObj->fun_db_query($sql);
$like = $dbObj->fun_db_fetch_rs_object($rsResult_like);?>
<div class="prt-panel">
<a href="#">
<span class="value"><?php if(@$like->total_comment==''){echo "0";}else {echo @$like->total_comment;}?></span>
<span class="label">Comments</span>
</a>
</div>
<div class="prt-panel">
<a href="#">
<span class="value"><?php if(@$like->total_likes==''){echo "0";}else {echo @$like->total_likes;}?></span>
<span class="label">Likes</span>
</a>
</div>
<div class="prt-panel">
<a href="#">
<span class="value"><?php if(@$like->total_view==''){echo "0";}else {echo @$like->total_view;}?></span>
<span class="label">Viewed</span>
</a>
</div>
<div class="clear"></div>
</div>

<div class="proedit"><a href="profile-edit.php">Edit Your Profile</a></div>

</div>
<div class="clear"></div>
</div>
<div class="pad10">
<div class="prof-name"><?php echo $user['name'];?></div>
<div class="prof-comment"><?php echo $user['email'];?></div>
<div class="prof-url">Status : <?php if($user['post_status']=='0'){echo "Public";} else {echo "Private";}?></div>

</div>

</div>
</div>





<div class="pad5">
<div class="profile">
<a name="show"></a>
<ul class="view-options">
<li><a  class="grid" onClick="changeview1('listview')"><span>Grid View</span></a></li>
<li><a  class="list" onClick="changeview('gridview')"><span>List View</span></a></li>
</ul>
<div class="clear"></div>
</div>
</div>


<div class="pad5" style=" min-height:250px;">

<ul class="gridview" id="changeview">

<a id="inifiniteLoader">Loading... <img src="images/loading.gif" /></a>

</ul>
 <div class="clear"></div>
</div>

<div class="pad10"><div class="pad10"></div></div>
<!-- Start Top Menu -->
<div class="footbar">
<ul class="footpanel">
<li><a href="index.php" class="allpost" title="All Post"><span>All Post</span></a></li>
<li><a  href="#show" class="comment" title="Most Comment"  onClick="mview('total_comment',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Commented</span></a></li>
<li><a href="#show" class="like" title="Most Like"  onClick="mview('total_like',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Liked</span></a></li>
<li><a  href="#show" class="mostviewed" title="Most View"  onClick="mview('total_view',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Viewed</span></a></li>
<?php if(@$_SESSION['session_admin_userid']!=''){?><li><a href="add-post.php" class="addpost" title="Add Post"><span>Add Post</span></a></li><?php }?>
</ul>
<div class="footpanel"></div>
<div class="footpanel"></div>
<div class="footpanel"></div>
<div class="footpanel"></div>
</div>
<!-- End Top Menu -->
</div>
</body>
</html>