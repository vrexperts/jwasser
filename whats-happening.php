<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_SESSION);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>What Happening</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link rel="stylesheet" href="style.css">
  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  $(function() {
    $( "#tabs1" ).tabs();
  });
  </script>
  <script type="text/javascript">
      jQuery(document).ready(function($) {
          var count = 2;
          $(window).scroll(function(){
                  if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                     loadArticle(count);
                     count++;
                  }
          }); 
 
          function loadArticle(pageNumber){    
                  $('a#inifiniteLoader').show('fast');
                  $.ajax({
                      url: "ajax-post.php",
                      type:'POST',
                      data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop', 
                      success: function(html){
                          $('a#inifiniteLoader').hide('1000');
                          $("#tabs-1").append(html);    // This will be the div where our content will be loaded
                      }
                  });
              return false;
          }
   
      });
      
  </script>
  
  <script>
function viewplus(post_id){
	//alert(post_id);
      $.ajax({url:"viewpost.php?post_id="+post_id,success:function(result){
		 // alert(result);
     //$(".abc").html(result);
   }});
}
</script>
<script>
function resetpwd(){
	
    $("#resetdiv").show('slow');
}
function forgotpwd(){
	
    $("#forgotdiv").show('slow');
}
function editprofile(){
	
    $("#editprofilediv").show('slow');
	$("#user_details").hide('slow');
	
}


</script>
  
  

</head>

<body>
  <?php if(@$_SESSION['session_admin_userid']!=''):?> <a href="logout.php"><img src="images/button_logout.png"></a><?php endif;?>
  <?php echo @$_SESSION['msg'];
  //print_r($_SESSION);

$_SESSION['msg']="";

?>
  <div id="tabs">
     <ul>
    <li><a href="#tabs-1">All Post</a></li>
    <?php if(@$_SESSION['session_admin_userid']!=''):?>
    <li><a href="#tabs-2">My Post</a></li>
    <li><a href="#tabs-3">MY Profile</a></li>
    <li><a href="#tabs-5">Post</a></li>
    <?php else :?>
    <li><a href="#tabs-4">Login</a></li>
    <li><a href="#tabs-6">Create Profile</a></li>
    <?php endif;?>
  </ul>
  <div id="tabs-1">
			<?php   $sqlSel_post = "SELECT * FROM " . TABLE_POST ." limit 0,15";
				    $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($post = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
			
		            <div class="item">
						<div><?php $extension = end(explode('.', $post->image));
                            if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
                             <!--href="show-post.php?id=<?php echo $post->id;?>" -->
								<a href="show-post.php?id=<?php echo $post->id;?>" onclick="viewplus(<?php echo $post->id;?>);"><img src="<?php echo admin_path.$post->image;?>" width="220" height="220"/></a>
							<?php endif; ?>
						</div>
					</div>
					<?php endwhile; ?>
                    
                    <a id="inifiniteLoader">Loading... <img src="images/ajax-loader.gif" /></a>
	</div>

 <?php if(@$_SESSION['session_admin_userid']!=''):?>
  <div id="tabs-2">
       <?php $sqlSel_post = "SELECT * FROM " . TABLE_POST ." where user_id=".$_SESSION['session_admin_userid'] ." limit 0,15" ;
			 $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($post = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
		            <div class="item">
						<div>
							<?php $extension = end(explode('.', $post->image));
							if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
								<a href="show-post.php?id=<?php echo $post->id;?>"><img src="<?php echo admin_path.$post->image;?>" width="220" height="220"/></a>								<?php else : ?>
							<?php endif; ?>
						</div>
					</div>
					<?php endwhile; ?>
                    <a id="inifiniteLoader">Loading... <img src="images/ajax-loader.gif" /></a>
                    
                    
   </div>
  <div id="tabs-3" style="height:350px;">
    <?php $user = $dbObj->get_row(TABLE_USERS,"id=".$_SESSION['session_admin_userid']);?>
    <div id="user_details"> <div style="float:left; width:220px;height:220px; border:1px solid #666; padding:10px;">
    
    <img src="<?php echo admin_path.$user['images'];?>" width="220" height="220"/>
    
    </div>  
     <div style="float:left; width:400px; border:1px solid #666; padding:10px; margin-left:20px;">
     Name :- <?php echo $user['name'];?><br/>
     Email :- <?php echo $user['email'];?><br/></div>
   
   
    <div id="editprofile" style="margin-left:268px;"><a onclick="editprofile();" style="font-size:10px; color:#09C;"><br/><br/><br/><br/><br/><br/>Edit Profile</a></div>
     <div id="reset" style="margin-left:268px;"><a onclick="resetpwd();" style="font-size:10px; color:#09C;">Reset Password</a></div>
    <div id="resetdiv" style="display:none; margin-left:268px;">
    
    <form action="reset-pwd.php" method="post" name="form1" enctype="multipart/form-data">
<input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>"><br/>
<table width="70%" border="1" cellspacing="0" cellpadding="5" >




    <tr>
    <td >Old Password :-</td>
    <td ><input type="o_password" name="o_password" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >New Password :-</td>
    <td ><input type="n_password" name="n_password" value="<?php echo @$_POST['n_password'];?>" required="required" style="width:750px;"><br/></td>
  </tr>
   <tr>
    <td >Password :-</td>
    <td ><input type="c_password" name="c_password" value="<?php echo @$_POST['c_password'];?>" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td ><input type="submit" value="Reset Password"  /></td>
  </tr>
</table>


</form>
    
    </div>
    </div>
    <div id="editprofilediv" style="display:none;">
    
    
    <div style="float:left; width:220px;height:220px; border:1px solid #666; padding:10px;">
    
    <form action="edit.php" method="post" enctype="multipart/form-data">
    <div id="fileInput">
    <label for="FileID"><img src="<?php echo admin_path.$user['images'];?>" width="220" height="220"/></label>
    <input type="file" id="FileID" style="display:none;" name="images"/>
</div>
    </div>  
     <div style="float:left; width:400px; border:1px solid #666; padding:10px; margin-left:20px;">
     Name :- <input type="text" value="<?php echo $user['name'];?>" name="name" required="required"\/><br/>
     Email :-<input type="email" value="<?php echo $user['email'];?>" name="email"  required="required"/>
     <input type="hidden" value="<?php echo $user['id'];?>" name="id" />
     <input type="submit" value="Update" name="sumbit" />
     
     <br/></div>
     </form>
    
    </div>
                    
 </div>
 <div id="tabs-5" >
    <form action="post.php" method="post" name="form1" enctype="multipart/form-data">
<table width="70%" border="1" cellspacing="0" cellpadding="5" >
  <tr>
    <td >Title :-</td>
    <td > <input type="text" name="title" value="" required="required" style="width:750px;"><input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>"><br/></td>
  </tr>


<tr>
    <td>Tag :-</td>
    <td><input type="text" name="tag" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td>Caption :-</td>
    <td><textarea name="description" value="" required="required" style="width:750px; height:100px;"></textarea><br/></td>
  </tr>
  <tr>
    <td>Images :-</td>
    <td><input type="file" name="image" value="" required="required"><br/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="create"  /></td>
  </tr>
  
 </table>
</form>           
 </div>
 <?php else :?>
 
 <div id="tabs-4" >
    

<form action="login.php" method="post" name="form1" enctype="multipart/form-data">
<table width="70%" border="1" cellspacing="0" cellpadding="5" >
    <tr>
    <td >User Name :-</td>
    <td > <input type="text" name="username" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >Password :-</td>
    <td ><input type="password" name="password" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td ><input type="submit" value="Login"  /></td>
  </tr>
</table></form>  
    <div id="forgot" style="margin-left:10px;"><a onclick="forgotpwd();" style="font-size:10px; color:#09C;">Forgot Your Password</a></div>
    <div id="forgotdiv" style="display:none; margin-left:10px;">
<form action="send-forgot-pwd.php" method="post" name="form1" enctype="multipart/form-data">
<input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>"><br/>
<table width="70%" border="1" cellspacing="0" cellpadding="5" >




    <tr>
    <td >Email :-</td>
    <td ><input type="email" name="email" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td ><input type="submit" value="Send"  /></td>
  </tr>
</table>


</form>
</div>

           
 </div>
 
 


<div id="tabs-6" >
   <form action="create-user.php" method="post" name="form1" enctype="multipart/form-data">
<table width="700px" border="1" cellspacing="0" cellpadding="5" >
  <tr>
    <td style="width:20%">Name :- </td>
    <td><input type="text" name="name" value="" required="required" style="width:350px;"><br/></td>
  </tr>
  <tr>
    <td>User Name :- </td>
    <td><input type="text" name="username" value="" required="required" style="width:350px;"><br/></td>
  </tr>
  <tr>
    <td>Password :- </td>
    <td><input type="text" name="password" value="" required="required" style="width:350px;"><br/></td>
  </tr>
  <tr>
    <td>Email :- </td>
    <td><input type="email" name="email" value="" required="required" style="width:350px;"><br/></td>
  </tr>
  <tr>
    <td>Images :- </td>
    <td><input type="file" name="images" value="" required="required" style="width:350px;"><br/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="create"  /></td>
  </tr>
</table>
</form>
            
 </div>
 <?php endif;?>
 


 



</div>




</body>
</html>