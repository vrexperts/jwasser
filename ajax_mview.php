<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

?>

<?php   echo $sqlSel_post = "SELECT * FROM " . TABLE_POST ." where post_status=0  order by $_REQUEST[order] DESC limit 0,15";
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


