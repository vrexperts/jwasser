<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
$page=$_REQUEST['page_no'];
$user_id=$_REQUEST['user_id'];
$start=($page-1)*15;
$end=$start+15;



if($user_id > 0) {
	$sqlSel_post = "SELECT * FROM " . TABLE_POST ." where 1=1";
	$sqlSel_post .= " AND user_id = $user_id";	
}
else
{
	$sqlSel_post = "SELECT * FROM " . TABLE_POST ." where post_status=0";
}

$sqlSel_post .= " order by `$_REQUEST[orderby]` DESC limit $start,15";


if($page > 1) {
 //echo $sqlSel_post; die;
}

				    $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					$i=1;
					while($post = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
                    
                    <li><?php $extension = end(explode('.', $post->image));
                            if($extension=='jpg' || $extension=='png' || $extension=='gif') :?>
								<a href="show-post.php?id=<?php echo $post->id;?>" onclick="viewplus(<?php echo $post->id;?>);"><img src="<?php echo admin_path.$post->image;?>"  /></a>
							<?php endif; ?>
                   </a>
<div class="contentbox">
<div class="contenttext"><?php echo $post->description;?></div>
<div class="comments">Comments(<?php echo $post->total_comment;?>)</div>
<div class="imglike">Like(<?php echo $post->total_like;?>)</div>
<div class="viewed">Viewed(<?php echo $post->total_view;?>)</div>
</div>
<div class="clear"></div>
</li>
 <?php endwhile; ?>
 