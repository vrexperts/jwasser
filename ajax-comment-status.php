<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
$arr['status']=$_REQUEST['status'];
$arr['id']=$_REQUEST['id'];
$arr['approved_by']=$_REQUEST['approved_by'];

$lastID = $dbObj->update_data(TABLE_COMMENT,'id',$arr,md5($_REQUEST['id']));

?>


