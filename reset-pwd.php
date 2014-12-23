<?php
require_once("includes/application-top.php");
$objAdmin = new Admins();
$objAdmin->fun_authenticate_admin();
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_POST);
if(count($_POST)>0){
 $sql_pwd = "SELECT * FROM ".TABLE_USERS." where password='" .MD5($_POST['o_password'])."' and id=" .$_POST['user_id'] ;
$rsResult_pwd = $dbObj->fun_db_query($sql_pwd);
 $total = $dbObj->fun_db_get_num_rows($rsResult_pwd);
if($total){
	if(@$_POST['n_password']==@$_POST['c_password']){
      $arr['password']=md5($_POST['n_password']);
	  $lastID = $dbObj->update_data(TABLE_USERS,'id',$arr,md5($_POST['user_id']));
	  $_SESSION['msg']='Password changed';
	  redirectURL(SITE_ADMIN_URL."logout.php");
     }else{
     $_SESSION['msg']="New password and Comform password are not matching";
	 redirectURL(SITE_ADMIN_URL."whats-happening.php#tabs-3");
     }
}
else
	 {
	$_SESSION['msg']="old password incorrect";
	redirectURL(SITE_ADMIN_URL."whats-happening.php#tabs-3");	 
	 }

       

}
?>
