
<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
$page=$_REQUEST['page_no'];
$start=($page-1)*limit;
$end=limit;

$sqlSel_post = "SELECT * FROM " . TABLE_USERS ;

$sqlSel_post .= " order by `id` DESC limit $start,$end";

				    $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($user = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
                    
                    <tr>
			<!--<td><?php echo $user->name;?></td>-->
			<td><?php echo $user->email;?></td>
			<td><?php echo $user->username;?></td>
			<!--<td><?php echo $user->status;?></td>-->
			<td><a href="profile-edit.php?id=<?php echo $user->id;?>&action=edit"><img src="images/edit_icon.png" width="16"></a></td>
			
		</tr>
        
        
        
        
        
        
       
       
        
      
 <?php endwhile; ?>
 



