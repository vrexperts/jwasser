<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_SESSION);
if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			$lastID = $dbObj->insert_data(TABLE_COMMENT,$arr);
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
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Show Post</title>
<link rel="stylesheet" href="style.css">
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

<div id="tabs-1">
	
		           
						
						<div>

							<?php $extension = end(explode('.', $post->image));

							if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
								<img src="<?php echo admin_path.$post->image;?>" width="420" />
								<?php else : ?>
								<!--<video width="200" height="200" controls>
									<source src="<?php echo admin_path.$post->image;?>" type="video/mp4">
									<source src="movie.ogg" type="video/ogg">
									Your browser does not support the video tag.
								</video>-->
							<?php endif; ?>
						</div>
                       
                         <div class="counting31">
                         
                         <?php if(@$_SESSION['session_admin_userid']==''){?>
                            <ul>
                                <li><span><a title="<?php echo $like;?> likes"><img src="images/like.png" alt=""></a>&nbsp;<?php echo $like;?></span></li>
                                <li><span><a title="<?php echo $post->total_view;?> views"><img src="images/like2.png" alt=""></a>&nbsp;<?php echo $post->total_view;?> </span></li>
                                <!--<li><span><a title="0 follows"><img src="images/like3.png" alt=""></a>&nbsp;0 </span></li>-->
                                <li><span><a title="<?php echo $comment;?> comments"><img src="images/like4.png" alt=""></a>&nbsp;<?php echo $comment;?></span></li>
                                <div class="clear"></div>
                            </ul>
                            <?php } else {?>
                           
                            <ul>
                            <?php $sql_like4 = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$post->id." and  user_id=".$_SESSION['session_admin_userid'];
                                  $rsResult_like4 = $dbObj->fun_db_query($sql_like4);
                                  $like4 = $dbObj->fun_db_get_num_rows($rsResult_like4);
								  if($like4==''){
								  ?>
                                <li><span><a  onclick="liked(<?php  echo $post->id;?>,<?php echo $_SESSION['session_admin_userid'];?>)" title="<?php echo $like;?> likes"><img src="images/like.png" alt=""></a>&nbsp;<span class="abc"><?php echo $like;?></span></span></li>
                                <?php } else {?>
                                <li><span><a  onclick="unlike(<?php  echo $post->id;?>,<?php echo $_SESSION['session_admin_userid'];?>)" title="<?php echo $like;?> likes"><img src="images/dislike.png" width="13" height="12" alt=""></a>&nbsp;<span class="abc"><?php echo $like;?></span></span></li>
                                <?php }?>
                                <li><span><a title="<?php echo $post->total_view;?> views"><img src="images/like2.png" alt=""></a>&nbsp;<?php echo $post->total_view;?> </span></li>
                                <!--<li><span><a title="0 follows"><img src="images/like3.png" alt=""></a>&nbsp;0 </span></li>-->
                                <li><span><a title="<?php echo $comment;?> comments"><img src="images/like4.png" alt=""></a>&nbsp;<?php echo $comment;?> </span></li>
                                <div class="clear"></div>
                            </ul>
                            
                            <?php }?>
                            <div>Caption:-<?php echo $post->description;?><br/>
                            <em>by:-<?php $user1 = $dbObj->get_row(TABLE_USERS,"id=".$post->user_id);
							echo $user1['username'];
							?><br/>
                            Time:- <?php echo $time = date("H:i:s",strtotime($post->add_date));?><br/>
							Date:-<?php echo fun_site_date_format($post->add_date)?>
                            </em>
                            </div>
                            
                            <?php while($com = $dbObj->fun_db_fetch_rs_object($rsResult_post_comment)):?>
                            
                            <div>Comment:-<?php echo $com->comment;?><br/>
                            <em>by:-<?php //echo $_SESSION['session_admin_userid'];?>
                            <?php 
							if($com->user_id!='0'){
							$user = $dbObj->get_row(TABLE_USERS,"id=".$com->user_id);
							echo $user['username'];
							}
							else{ echo $com->user_name;}
							?>
                            
                            
                            <br/>
                            Time:- <?php echo $time = date("H:i:s",strtotime($com->add_date));?><br/>
							Date:-<?php echo fun_site_date_format($com->add_date)?>
                            </em>
                            </div>
                            
                            <?php endwhile; ?>
                        
                        <div>
                        <form action="" method="post" name="form1" enctype="multipart/form-data">
                        <textarea name="comment" style=" width:400px; height:50px;" placeholder="Comment" required="required"></textarea>
                        <?php if(@$_SESSION['session_admin_userid']!=''){?>
                        <input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>" />
                        <?php } else {?>
                          <input type="text" name="user_name" value=""  required="required" placeholder="Name" style=" width:400px; margin-top:20px;"/>
                            <input type="email" name="email" value=""  required="required" placeholder="Email" style=" width:400px;margin-top:20px;"/>
                        <?php }?>
                        <input type="hidden" name="post_id" value="<?php echo $post->id;?>" />
                        <input type="submit" value="Send"  style=" margin-top:10px;"/>
                        </form>
                        </div>
                        </div>
                       
                        
                        
					</div>
				
	
</body>
</html>