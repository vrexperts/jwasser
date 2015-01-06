<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
$sqlSel_post1 = "SELECT * FROM " . TABLE_POST ." where id=".$_REQUEST['post_id'];
$rsResult_post1 = $dbObj->fun_db_query($sqlSel_post1);
$post1 = $dbObj->fun_db_fetch_rs_object($rsResult_post1);
$arr['total_view']=$post1->total_view+1;
$lastID = $dbObj->update_data(TABLE_POST,'id',$arr,md5($_REQUEST['post_id']));
?>


