
<?php 
require_once("includes/application-top.php");
error_reporting(0);
$dbObj = new DB();
$dbObj->fun_db_connect();
$page=$_REQUEST['page_no'];
$start=($page-1)*limit;
$end=limit;
if(@$_SESSION['session_admin_type']=="1"){
$sqlSel_post = "SELECT * FROM " . TABLE_COMMENT ;
}
else{
$sqlSel_post = "SELECT * FROM ".TABLE_COMMENT." WHERE post_id in (SELECT GROUP_CONCAT(id) as ids FROM ".TABLE_POST." GROUP BY user_id HAVING user_id= ".$_REQUEST['userid'].")";
	}
$sqlSel_post .= " order by `id` DESC limit $start,$end";

				    $rsResult_post = $dbObj->fun_db_query($sqlSel_post);
					while($user = $dbObj->fun_db_fetch_rs_object($rsResult_post)) :?>
                    
                    <tr>
			<td><?php echo $user->comment;?></td>
			<td><?php if($user->approved_by!='0'){
			$user1 = $dbObj->get_row(TABLE_USERS,"id=".$user->approved_by);
            
            echo $user1['name']; }else{}?>
            </td>
			<td ><?php if($user->status=='1'){echo '<a style="color:green;text-decoration:none;" href="javascript:void(o);" onClick="changestauts(0,'.$user->id.',0);">Approved</a>';}else{echo '<a href="javascript:void(o);"  style="color:red;text-decoration:none;" onClick="changestauts(1,'.$user->id.','.$_SESSION['session_admin_userid'].');">Unapproved</a>';}?></td>
			
		</tr>
        
        
        
        
        
        
       
       
        
      
 <?php endwhile; ?>
 



