<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
?>
 <?php
 $sql=mysql_query("DELETE FROM ".TABLE_LIKE." where post_id=".$_REQUEST['post_id']." and  user_id=".$_REQUEST['user_id']."");
	$sql_like1 = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$_REQUEST['post_id'];
    $rsResult_like1 = $dbObj->fun_db_query($sql_like1);
    $like1 = $dbObj->fun_db_get_num_rows($rsResult_like1);
	echo $like1;
	$arr['total_like']=$like1;
$lastID = $dbObj->update_data(TABLE_POST,'id',$arr,md5($_REQUEST['post_id']));
?>

                  