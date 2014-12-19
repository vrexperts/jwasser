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
  </script>
  
  

</head>

<body>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">All Post</a></li>
    <li><a href="#tabs-2">My Post</a></li>
    <li><a href="#tabs-3">MY Profile</a></li>
  </ul>
  <div id="tabs-1">
			<?php $sqlSel_post = "SELECT * FROM " . TABLE_POST ;
				$rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($post = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
			
		            <div class="item">
						<!--<div><?php echo $post->title;?></div>-->
						<div><!--<img src="<?php echo admin_path.$post->image;?>" width="180"/>-->

							<?php $extension = end(explode('.', $post->image));

							if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
								<a href="show-post.php?id=<?php echo $post->id;?>"><img src="<?php echo admin_path.$post->image;?>" width="220" height="220"/></a>
								<?php else : ?>
								<!--<video width="200" height="200" controls>
									<source src="<?php echo admin_path.$post->image;?>" type="video/mp4">
									<source src="movie.ogg" type="video/ogg">
									Your browser does not support the video tag.
								</video>-->
							<?php endif; ?>
						</div>
                         <?php /*?><div class="counting3">
                            <ul>
                                <li><span><a title="likes"><img src="images/like.png" alt=""></a>&nbsp;
                                
                                <?php $sqlSel_post_like = "SELECT * FROM " . TABLE_LIKE." where post_id=".$post->id ;
				                      $rsResult_post_like = $dbObj->fun_db_query($sqlSel_post_like);
					                 echo $like = $dbObj->fun_db_get_num_rows($rsResult_post_like);?>
                                
                                
                                 </span></li>
                                <li><span><a title="views"><img src="images/like2.png" alt=""></a>&nbsp;<?php echo $post->total_view;?> </span></li>
                                <!--<li><span><a title="0 follows"><img src="images/like3.png" alt=""></a>&nbsp;0 </span></li>-->
                                <li><span><a title="0 comments"><img src="images/like4.png" alt=""></a>&nbsp;0 </span></li>
                                <div class="clear"></div>
                            </ul>
                        </div><?php */?>
					</div>
					<?php endwhile; ?>
                    <p>&nbsp;</p>
	</div>


  <div id="tabs-2">
       <?php $sqlSel_post = "SELECT * FROM " . TABLE_POST ." where user_id=".$_SESSION['session_admin_userid'] ;
				$rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($post = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
			
		            <div class="item">
						<!--<div><?php echo $post->title;?></div>-->
						<div><!--<img src="<?php echo admin_path.$post->image;?>" width="180"/>-->

							<?php $extension = end(explode('.', $post->image));

							if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
								<a href="show-post.php?id=<?php echo $post->id;?>"><img src="<?php echo admin_path.$post->image;?>" width="220" height="220"/></a>								<?php else : ?>
								<!--<video width="200" height="200" controls>
									<source src="<?php echo admin_path.$post->image;?>" type="video/mp4">
									<source src="movie.ogg" type="video/ogg">
									Your browser does not support the video tag.
								</video>-->
							<?php endif; ?>
						</div>
                        
                        
                       <!-- <div class="counting3">
                            <ul>
                                <li><span><a title="2 likes"><img src="images/like.png" alt=""></a>&nbsp;2 </span></li>
                                <li><span><a title="33 views"><img src="images/like2.png" alt=""></a>&nbsp;33 </span></li>
                                <li><span><a title="0 follows"><img src="images/like3.png" alt=""></a>&nbsp;0 </span></li>
                                <li><span><a title="0 comments"><img src="images/like4.png" alt=""></a>&nbsp;0 </span></li>
                                <div class="clear"></div>
                            </ul>
                        </div>-->
					</div>
					<?php endwhile; ?>
                    <p>&nbsp;</p>
                    
   </div>
  <div id="tabs-3">
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>



</div>



</body>
</html>