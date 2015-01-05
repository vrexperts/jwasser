<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
?>
 <?php $sqlSel_post = "SELECT * FROM " . TABLE_POST;
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
	  var userid = 0;
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
		 // alert(pageNumber);
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
				orderby = order;
				$('#changeview').html('');
				count=1;
				loadArticle(count, orderby, userid,total_page);
			    count++;
		  }
		  
		  function viewplus(post_id){
            $.ajax({url:"viewpost.php?post_id="+post_id,success:function(result){
           }});
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


<div class="pad5">
<div class="profile">
<ul class="view-options">
<li><a class="grid" onClick="changeview1('listview')"><span>Grid View</span></a></li>
<li><a  class="list" onClick="changeview('gridview')"><span>List View</span></a></li>
</ul>
<div class="clear"></div>
</div>
</div>

<div class="pad5">
<ul class="gridview" id="changeview">

<a id="inifiniteLoader">Loading... <img src="images/loading.gif" /></a>

</ul>
 <div class="clear"></div>
</div>
<div class="pad10"><div class="pad10"></div></div>
<!-- Start Top Menu -->
<div class="footbar">
<ul class="footpanel">



<li><a  class="allpost" title="All Post"><span>All Post</span></a></li>
<li><a  class="comment" title="Most Comment"  onClick="mview('total_comment',0);"><span>Most Commented</span></a></li>
<li><a class="like" title="Most Like"  onClick="mview('total_like',0);"><span>Most Liked</span></a></li>
<li><a  class="mostviewed" title="Most View"  onClick="mview('total_view',0);"><span>Most Viewed</span></a></li>
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