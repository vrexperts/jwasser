<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_SESSION);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>What Happening</title>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link rel="stylesheet" href="css/style.css">
  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  $(function() {
    $( "#tabs1" ).tabs();
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
	  
	  function mview(order) {
		        userid=0;
				orderby = order;
				$('#con').html('');
				count=1;
				loadArticle(count, orderby, userid);
			    count++;
		  }
		  
		  
		  function mpost(uid) {
			  	userid = uid;
				$('#con').html('');
				count=1;
				loadArticle(count, orderby, userid);
			    count++;
		  }
      

       function viewplus(post_id){
            $.ajax({url:"viewpost.php?post_id="+post_id,success:function(result){
           }});
        }

     function resetpwd(){
        $("#resetdiv").show('slow');
		 $("#editprofilediv").hide('slow');
	$("#user_details").hide('slow');
     }
    function forgotpwd(){
       $("#forgotdiv").show('slow');
	   $("#login").hide('slow');
	   
    }
   function editprofile(){
    $("#editprofilediv").show('slow');
	$("#user_details").hide('slow');
	$("#resetdiv").hide('slow');
	}
   function goBack() {
    $("#editprofilediv").hide('slow');
	$("#user_details").show('slow');
	$("#resetdiv").hide('slow');
    }
</script>
  
  

</head>

<body>


<div class="instagram"> 
 <?php if(@$_SESSION['session_admin_userid']!=''):?><div class="title">Welcome <?php echo @$_SESSION['session_admin_username'];?> <a href="logout.php" class="logout fltr">Logout</a></div><?php endif;?>
 <div class="pad5"></div>
 
  <?php if(@$_SESSION['msg']!=''):?><div class="title"> <?php echo @$_SESSION['msg'];$_SESSION['msg']="";?></div><?php endif;?>

   <div class="content" id="tabs">        
          
    <ul class="mainmenu">
	<li><a href="#tabs-1" class="off">All Post</a></li>
    <?php if(@$_SESSION['session_admin_userid']!=''):?>
    <!--<li><a href="#tabs-2">My Post</a></li>-->
    <li><a href="#tabs-3" class="off">Setting</a></li>
    <li><a href="#tabs-5" class="off">Post</a></li>
    <?php else :?>
    <li><a href="#tabs-4" class="off">Login</a></li>
    <li><a href="#tabs-6" class="off">Create Profile</a></li>
    <?php endif;?>    
</ul> 
<div class="clear pad5"></div>     
    <div id="tabs-1">
  
  <ul class="submenu">
	<li><a onclick="mview('total_view');" >Most Viewed</a></li>
    <li><a onclick="mview('total_comment');">Most Comment</a></li>
    <li><a onclick="mview('total_like');">Most Like</a></li>
    <li><?php if(@$_SESSION['session_admin_userid']!=''):?> <a onclick="mpost(<?php echo $_SESSION['session_admin_userid'];?>);" >My Post</a><?php endif;?></li>
</ul>
<div class="pad5 clear"></div>
 <ul class="imgview" id="con">
</ul>
             
                    <a id="inifiniteLoader">Loading... <img src="images/loading.gif" /></a>
                    
	</div>
    
    
    
   
   
   
   
   
   
   
   
   
   <?php if(@$_SESSION['session_admin_userid']!=''):?>
  <div id="tabs-3">
  
      <?php $user = $dbObj->get_row(TABLE_USERS,"id=".$_SESSION['session_admin_userid']);?>

  
  <div class="content" id="user_details">
 <form>
 <fieldset>
<legend><strong>Profile</strong></legend>
 <div class="pad5"></div>
<div class="profileimg"><img src="<?php echo admin_path.$user['images'];?>" width="220" height="220"/></div>
<div class="profiledetails">
<input type="text" name="name" value="<?php echo $user['name'];?>" class="instxt" disabled/>
<div class="pad5"></div>
<input type="text" name="email" value="<?php echo $user['email'];?>" class="instxt" disabled/>
<div class="pad5"></div>

<select name="post_status" class="instxt" disabled>
     <option value="0" <?php if($user['post_status']=='0'){echo 'selected="selected"';}?> >Public</option>
     <option value="1" <?php if($user['post_status']=='1'){echo 'selected="selected"';}?>>Private</option>
     </select>

 <div class="pad5"></div>
<a onclick="editprofile();" style="font-size:10px; color:#09C;" id="editprofile">Edit Profile</a>

<div class="pad5"></div>
<a onclick="resetpwd();" style="font-size:10px; color:#09C;" id="reset">Reset Password</a>
</div>

</fieldset>
</form>
</div>
  
  <div class="content" id="resetdiv" style="display:none;">
 <form action="reset-pwd.php" method="post" name="form1" enctype="multipart/form-data">
 <input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>">
 <fieldset>
<legend><strong>Reset Password</strong></legend>
 <div class="pad5"></div>
<input type="password" name="o_password" value="" required placeholder="Old Password" class="instxt">
 <div class="pad5"></div>
 <input type="password" name="n_password" value="<?php echo @$_POST['n_password'];?>" required placeholder="New Password" class="instxt">
 <div class="pad5"></div>
 <input type="password" name="c_password" value="<?php echo @$_POST['c_password'];?>" required placeholder="Conform Password" class="instxt">
 <div class="pad5"></div>
 <div class="pad5"></div>
<input type="submit" class="button" value="Reset Password" />
<div class="pad5"></div>
  </fieldset>
 </form>
 <div style="clear:both;"></div>
   <button onclick="goBack()">Go Back</button>
 </div>
  
 
 
  
    
    
    <div class="content"  id="editprofilediv" style="display:none;">
 <form action="edit.php" method="post" enctype="multipart/form-data">
 <fieldset>
<legend><strong>Edit Profile</strong></legend>
 <div class="pad5"></div>
<div class="profileimg"><div id="fileInput">
    <label for="FileID"><img src="<?php echo admin_path.$user['images'];?>" width="220" height="220"/></label>
    <input type="file" id="FileID" style="display:none;" name="images"/>
</div></div>
<div class="profiledetails">
<input type="text" value="<?php echo $user['name'];?>" name="name" required class="instxt" />
 <div class="pad5"></div>
<input type="email" value="<?php echo $user['email'];?>" name="email"  required="required" class="instxt" />
<input type="hidden" value="<?php echo $user['id'];?>" name="id" />
 <div class="pad5"></div>

 <select name="post_status" class="instxt">
     <option value="0" <?php if($user['post_status']=='0'){echo 'selected="selected"';}?> >Public</option>
     <option value="1" <?php if($user['post_status']=='1'){echo 'selected="selected"';}?>>Private</option>
     </select>

 <div class="pad5"></div>
<input type="submit" class="button" value="Update Profile" />
</div>
</fieldset>
</form>
<div style="clear:both;"></div>
   <button onclick="goBack()">Go Back</button>
</div>
    
    
    
    
    
    
    
    
    
   
    
                    
 </div>
 <div id="tabs-5" >
 
 
 
 
 
 
 
 <div class="content">
 <form action="post.php" method="post" name="form1" enctype="multipart/form-data">
 <fieldset>
<legend><strong>Post</strong></legend>
 <div class="pad5"></div>
<input type="text" class="instxt" placeholder="Post Title"  name="title" value="" required />
<input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>">
<input type="hidden" name="post_status" value="<?php echo @$user['post_status'];?>">
 <div class="pad5"></div>
 <input type="text" name="tag" value="" required class="instxt" placeholder="Tag" />
 <div class="pad5"></div>
 <textarea class="instxt" rows="5" placeholder="Comments" name="description" value="" required></textarea>
 <div class="pad5"></div>
<input type="file" name="image" required  class="instxt">
 <div class="pad5"></div>
<input type="submit" class="button" value="Submit" />
  </fieldset>
 </form>
 </div>
 
 
 
 
 
 
 
 
 
               
 </div>
 <?php else :?>
 
 





 <div id="tabs-4" >
   
   <div class="content" id="login">
 <form action="login.php" method="post" name="form1" enctype="multipart/form-data">
 <fieldset>
<legend><strong>Sign In</strong></legend>
 <div class="pad5"></div>
<input type="text" class="instxt" placeholder="User Name" name="username" value="" required/>
 <div class="pad5"></div>
<input type="password" class="instxt" placeholder="Password" name="password" value="" required/>
 <div class="pad5"></div>
<input type="submit" class="button"  value="Login"/>
 <div class="pad5"></div>
 <div id="forgot"><a onclick="forgotpwd();">Forgot Your Password</a></div>
  </fieldset>
 </form>
 </div>
 
 
 
 
 <div class="content" id="forgotdiv" style="display:none;">
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
 </div>
 
 
 
 
 
 
 
   
 

           
 </div>
 
 
 
 
 
 
 


<div id="tabs-6" >





<div class="content">
 <form action="create-user.php" method="post" name="form1" enctype="multipart/form-data">
 <fieldset>
<legend><strong>Create Profile</strong></legend>
 <div class="pad5"></div>
<input type="text" class="instxt" name="name" value="" required placeholder="Name" />
 <div class="pad5"></div>
 <input type="text" class="instxt" placeholder="User Name"  name="username" value="" required />
 <div class="pad5"></div>
 <input type="password" class="instxt"  placeholder="Password"  name="password" value="" required/>
 <div class="pad5"></div>
 <input type="email" name="email" value="" required class="instxt" placeholder="Email" />
 <div class="pad5"></div>
<input type="file" name="images" required  class="instxt">
 <div class="pad5"></div>
<input type="submit" class="button" value="Create" />
  </fieldset>
 </form>
 </div>







   
            
 </div>
 <?php endif;?>
   
   
   
   
   
   
   
    
    
    
    
          
          
  </div>        
  </div>
  
  
  
  
  
  
  
  
  
  
  




</body>
</html>