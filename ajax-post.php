<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
$page=$_REQUEST['page_no'];
$start=($page-1)*15;
$end=$start+15;
$sqlSel_post = "SELECT * FROM " . TABLE_POST ." limit $start,15";
				    $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($post = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
<div class="item">
						<div><?php $extension = end(explode('.', $post->image));
                            if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
								<a href="show-post.php?id=<?php echo $post->id;?>"><img src="<?php echo admin_path.$post->image;?>" width="220" height="220"/></a>
							<?php endif; ?>
						</div>
					</div>
                    <?php endwhile; ?>