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
 
});	
</script>
<script type="text/javascript">
      var count = 1;
	  var orderby = 'id';
	  var userid = <?php echo @$_SESSION['session_admin_userid'];?>;
      jQuery(document).ready(function($) {
		  loadArticle(count, orderby, userid);
          count++;
		  
          $(window).scroll(function(){
			  
                  if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                     loadArticle(count);
                     count++;
                  }
          }); 
   
      });
	  
	  function loadArticle(pageNumber){
		  
		  //alert(pageNumber);
                  $('a#inifiniteLoader').show('fast');
                  $.ajax({
                      url: "ajax-user.php",
                      type:'POST',
                      data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop', 
                      success: function(html){
                          $('a#inifiniteLoader').hide('1000');
                          $("#changeview").append(html);    // This will be the div where our content will be loaded
                      }
                  });
              return false;
          }
		  </script>
          
         <!-- <style>
	
	/* 
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	
		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
		tr { border: 1px solid #ccc; }
		
		td { 
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee; 
			position: relative;
			padding-left: 50%; 
		}
		
		td:before { 
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%; 
			padding-right: 10px; 
			white-space: nowrap;
		}
		
		/*
		Label the data
		*/
		td:nth-of-type(1):before { content: "Email"; }
		td:nth-of-type(2):before { content: "User Name"; }
		td:nth-of-type(3):before { content: "Option"; }
		
	}
	
	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		body { 
			padding: 0; 
			margin: 0; 
			width: 320px; }
		}
	
	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		body { 
			width: 495px; 
		}
	}
	
	</style>-->



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

<div class="pad5 viewimage">
<div class="contentbox">

<div class="pad5 clear"></div>
<div id="tabs-4">
<strong style="font-size:14px;">User List</strong>
<div class="pad5"></div>
<div style=" color:#FF0000;"><?php echo @$_SESSION['msg'];@$_SESSION['msg']='';?></div>



 
<table>
		<thead>
		<tr>
			<!--<th>Name</th>-->
			<th>Email</th>
			<th>User Name</th>
			<!--<th>Status</th>-->
			<th>Option</th>
			
		</tr>
		</thead>
		<tbody id="changeview">
		
		
		
		
		
		</tbody>
	</table>
<a id="inifiniteLoader">Loading... <img src="images/loading.gif" /></a>

 
 </div>
<div class="pad5 clear"></div>


<div class="pad10 clear"></div>
</div>

</div>
<div class="pad10 clear"><div class="pad10"></div></div>
<!-- Start Top Menu -->
<div class="footbar">
<ul class="footpanel">
<li><a href="instagram.php" class="allpost" title="All Post"><span>All Post</span></a></li>
<li><a href="instagram.php" class="comment" title="Most Comment"  onClick="mview('total_comment',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Commented</span></a></li>
<li><a href="instagram.php" class="like" title="Most Like"  onClick="mview('total_like',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Liked</span></a></li>
<li><a href="instagram.php" class="mostviewed" title="Most View"  onClick="mview('total_view',<?php echo @$_SESSION['session_admin_userid'];?>);"><span>Most Viewed</span></a></li>
<?php if(@$_SESSION['session_admin_userid']!=''){?><li><a href="add-post.php" class="addpost" title="Add Post"><span>Add Post</span></a></li><?php }?></ul>
<div class="footpanel"></div>
<div class="footpanel"></div>
<div class="footpanel"></div>
<div class="footpanel"></div>
</div>
<!-- End Top Menu -->
</div>
</body>
</html>