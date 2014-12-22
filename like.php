<?php 
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
?>
 <?php 
$sql_like = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$_REQUEST['post_id']." and  user_id=".$_REQUEST['user_id'];
$rsResult_like = $dbObj->fun_db_query($sql_like);
$like = $dbObj->fun_db_get_num_rows($rsResult_like);
if($like=='')
{
$arr['post_id']= $_REQUEST['post_id'];
$arr['user_id']=$_REQUEST['user_id'];
$lastID = $dbObj->insert_data(TABLE_LIKE,$arr);
$sql_like1 = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$_REQUEST['post_id'];
$rsResult_like1 = $dbObj->fun_db_query($sql_like1);
$like1 = $dbObj->fun_db_get_num_rows($rsResult_like1);
echo $like1;
}
else
{
	$sql_like1 = "SELECT *  FROM  ".TABLE_LIKE." where post_id=".$_REQUEST['post_id'];
    $rsResult_like1 = $dbObj->fun_db_query($sql_like1);
    $like1 = $dbObj->fun_db_get_num_rows($rsResult_like1);
	echo $like1;
}
?>

                  