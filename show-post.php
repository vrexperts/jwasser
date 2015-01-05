<?php
require("PHPMailer/class.phpmailer.php");
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			 $lastID1 = $dbObj->insert_data(TABLE_COMMENT,$arr);
			 /* mail for new comment*/
			 if($_SESSION['session_admin_userid']!=''){
			 $mail = new PHPMailer();
             $mail->From     = "admin@jwasser.com";
             $mail->AddAddress("shallu.47@gmiil.com");
             $mail->Subject  = "Comment Posted On Post";
			 $mail->IsHTML(true);
			 $mail->Body = "<b><font  style='font-size:14px;'>Below comment posted on post for approvel</font></b><br><br>
		                    <br>".$arr['comment']."<br>";
              if($lastID1) {$mail->send();}
			 }
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
									 
				$sqlSel_post_comment = "SELECT * FROM " . TABLE_COMMENT." where post_id=".$post->id." and status=1" ;
				$rsResult_post_comment = $dbObj->fun_db_query($sqlSel_post_comment);
				$comment = $dbObj->fun_db_get_num_rows($rsResult_post_comment); 
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

<div class="pad5 viewimage">
<div class="bigimg"><?php $extension = end(explode('.', $post->image));
		if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
		<img src="<?php echo admin_path.$post->larg_image;?>" width="100%"/>
		<?php endif; ?>
</div>
<div class="contentbox">
<div class="contenttext"><?php echo $post->description;?></div>

<div class="clear"></div>  

<span class="fltl"><span class="txtcolor"><em><span style=" color:#fd3001;"><?php $user1 = $dbObj->get_row(TABLE_USERS,"id=".$post->user_id);echo $user1['username'];?></span> | <?php echo fun_site_date_format($post->add_date)?> | <?php echo $time = date("H:i:s",strtotime($post->add_date));?></em></span></span>
<div class="clear pad5"></div>   

<?php if(@$_SESSION['session_admin_userid']==''):?>
            <div class="comments">Comments <?php echo $comment;?></div>
            <div class="imglike">Like <?php echo $like;?></div>
            <div class="viewed">Viewed <?php echo $post->total_view;?></div>
<?php else:?>

<?php $sql_like4 = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$post->id." and  user_id=".$_SESSION['session_admin_userid'];
       $rsResult_like4 = $dbObj->fun_db_query($sql_like4);
       $like4 = $dbObj->fun_db_get_num_rows($rsResult_like4);?>
       <div class="comments">Comments <?php echo $comment;?></div>

	  <?php  if($like4==''):?>
          <div class="imglike" onclick="liked(<?php  echo $post->id;?>,<?php echo @$_SESSION['session_admin_userid'];?>)" title="Like">Like <?php echo $like;?></div>

          <?php else:?>
         <div class="imglike" onclick="unlike(<?php  echo $post->id;?>,<?php echo @$_SESSION['session_admin_userid'];?>)" title="Unlike">Like <?php echo $like;?></div>
       <?php endif;?>
         <div class="viewed">Viewed <?php echo $post->total_view;?></div>
<?php endif;?>

<?php while($com = $dbObj->fun_db_fetch_rs_object($rsResult_post_comment)):?>
  <div class="clear pad5"></div>
  
      
      <div class="contenttext"><?php echo $com->comment;?></div>

<div class="clear"></div>  

<span class="fltl"><span class="txtcolor"><em><span style=" color:#fd3001;"><?php 
							if($com->user_id!='0'):
							$user = $dbObj->get_row(TABLE_USERS,"id=".$com->user_id);
							echo $user['username'];
							else : echo $com->user_name;
							endif;
							?></span> | <?php echo fun_site_date_format($com->add_date)?> | <?php echo fun_site_date_format($com->add_date)?></em></span></span>
<div class="clear pad5"></div> 
      
      
      
       


<?php endwhile; ?>     







<div class="pad10 clear"></div>
<form action="" method="post" name="form1" enctype="multipart/form-data">
<textarea class="instxt"  placeholder="Comment" rows="3"  name="comment" required/></textarea>
<?php if(@$_SESSION['session_admin_userid']!=''):?><input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>" />
<input type="hidden" name="status" value="1" />
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
<div class="pad5 clear"></div>
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
</body>
</html>