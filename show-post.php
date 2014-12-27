<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_SESSION);
if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			$lastID = $dbObj->insert_data(TABLE_COMMENT,$arr);
			$sqlSel_com1 = "SELECT * FROM " . TABLE_COMMENT." where post_id=".$arr['post_id'] ;
			$rsResult_com1 = $dbObj->fun_db_query($sqlSel_com1);
			$total_comment = $dbObj->fun_db_get_num_rows($rsResult_com1);
			
			$arr['total_comment']=$total_comment;
            $lastID = $dbObj->update_data(TABLE_POST,'id',$arr,md5($arr['post_id']));
			
			if($lastID){ redirectURL("show-post.php?id=".$arr['post_id']);}
	}
	
		 		$sqlSel_post = "SELECT * FROM " . TABLE_POST ." where id=".$_REQUEST['id'];
				$rsResult_post = $dbObj->fun_db_query($sqlSel_post);
				$post = $dbObj->fun_db_fetch_rs_object($rsResult_post);
 
                $sqlSel_post_like = "SELECT * FROM " . TABLE_LIKE." where post_id=".$post->id ;
				$rsResult_post_like = $dbObj->fun_db_query($sqlSel_post_like);
				$like = $dbObj->fun_db_get_num_rows($rsResult_post_like);
									 
				$sqlSel_post_comment = "SELECT * FROM " . TABLE_COMMENT." where post_id=".$post->id ;
				$rsResult_post_comment = $dbObj->fun_db_query($sqlSel_post_comment);
				$comment = $dbObj->fun_db_get_num_rows($rsResult_post_comment); 
				?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Show Post</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function liked(post_id,user_id){
      $.ajax({url:"like.php?post_id="+post_id+"&user_id="+user_id,success:function(result){
      $(".abc").html(result);
	  location.reload();
    }});
}
function unlike(post_id,user_id){
      $.ajax({url:"unlike.php?post_id="+post_id+"&user_id="+user_id,success:function(result){
		  //alert(result);
      $(".abc").html(result);
	  location.reload();
    }});
}

</script>

</head>

<body>



<div class="instagram"> 
 <?php if(@$_SESSION['session_admin_userid']!=''):?><div class="title">Welcome <?php echo @$_SESSION['session_admin_username'];?> <a href="logout.php" class="logout fltr">Logout</a></div><div class="pad5"></div><?php endif;?>
   
  
 <div class="content contentbg">
       <?php $extension = end(explode('.', $post->image));
		if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
		<img src="<?php echo admin_path.$post->image;?>" width="100%"/>
		<?php endif; ?>
      <div class="pad5">


<ul class="likeview">

<?php if(@$_SESSION['session_admin_userid']==''):?>
            <li><a href="#" title="Like"><img src="images/like.png" width="13" height="12" align="left" /><?php echo $like;?></a></li>
            <li><a href="#" title="View"><img src="images/like2.png" width="13" height="12" align="left" /><?php echo $post->total_view;?> </a></li>
            <li><a href="#" title="Comments"><img src="images/like4.png" width="13" height="12" align="left" /><?php echo $comment;?></a></li>
<?php else:?>

<?php $sql_like4 = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$post->id." and  user_id=".$_SESSION['session_admin_userid'];
       $rsResult_like4 = $dbObj->fun_db_query($sql_like4);
       $like4 = $dbObj->fun_db_get_num_rows($rsResult_like4);
	   if($like4==''):?>
          <li><a  onclick="liked(<?php  echo $post->id;?>,<?php echo $_SESSION['session_admin_userid'];?>)" title="likes"><img src="images/like.png" alt="" width="13" height="12" align="left" ></a>&nbsp;<span class="abc"><?php echo $like;?></span></li>
          <?php else:?>
          <li><a  onclick="unlike(<?php  echo $post->id;?>,<?php echo $_SESSION['session_admin_userid'];?>)" title="likes"><img src="images/dislike.png" width="13" height="12" align="left"  alt=""></a>&nbsp;<span class="abc"><?php echo $like;?></span></li>
       <?php endif;?>
         <li><a title="views"><img src="images/like2.png" alt="" width="13" height="12" align="left" ></a>&nbsp;<?php echo $post->total_view;?></li>
         <li><a title="comments"><img src="images/like4.png" alt="" width="13" height="12" align="left" ></a>&nbsp;<?php echo $comment;?></li>
<?php endif;?>
</ul>
<div class="clear"></div>  
<span class="fltl"><strong>Caption: </strong><span class="txtcolor"><?php echo $post->description;?></span></span>
<span class="fltl"><strong>By: </strong><span class="txtcolor"><?php $user1 = $dbObj->get_row(TABLE_USERS,"id=".$post->user_id);echo $user1['username'];?></span></span>
<div class="clear"></div>  
<span class="fltl"><strong>Time: </strong><span class="txtcolor"><em><?php echo $time = date("H:i:s",strtotime($post->add_date));?></em></span></span>
<span class="fltl"><strong>Date: </strong><span class="txtcolor"><em><?php echo fun_site_date_format($post->add_date)?></em></span></span>
<div class="clear"></div>


<?php while($com = $dbObj->fun_db_fetch_rs_object($rsResult_post_comment)):?>
  
<strong>Comments:- </strong><em><?php echo $com->comment;?></em>
<div class="clear"></div>  
<span class="fltl"><strong>Comments By: </strong><span class="txtcolor"><?php 
							if($com->user_id!='0'):
							$user = $dbObj->get_row(TABLE_USERS,"id=".$com->user_id);
							echo $user['username'];
							else : echo $com->user_name;
							endif;
							?></span></span> 
<span class="fltl"><strong>Time: </strong><span class="txtcolor"><em><?php echo $time = date("H:i:s",strtotime($com->add_date));?></em></span></span>
<span class="fltl"><strong>Date: </strong><span class="txtcolor"><em><?php echo fun_site_date_format($com->add_date)?></em></span></span>
<div class="clear pad5"></div>

<?php endwhile; ?>

<form action="" method="post" name="form1" enctype="multipart/form-data">
<textarea class="instxt"  placeholder="Comment" rows="3"  name="comment" required="required"/></textarea>
<?php if(@$_SESSION['session_admin_userid']!=''):?><input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>" />
<?php else :?>
<div class="pad5"></div>
<input type="text" class="instxt" placeholder="Name" name="user_name" value=""  required="required"/>
<div class="pad5"></div>
<input type="email" class="instxt" placeholder="Email" name="email" value=""  required="required"/>
<?php endif;?>
<div class="pad5"></div>
<input type="hidden" name="post_id" value="<?php echo $post->id;?>" />
<input type="submit" class="button" value="Submit" />
</form>



 </div>
 



 
 </div>	
 
 
 
 
 </div>













				
	
</body>
</html>