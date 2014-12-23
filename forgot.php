<?php
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

//print_r($_POST);
if(@$_GET['key']!=''){
$sql_pwd = "SELECT * FROM ".TABLE_USERS." where reset_key='".@$_GET['key']."'";
$rsResult_pwd = $dbObj->fun_db_query($sql_pwd);
 $total = $dbObj->fun_db_get_num_rows($rsResult_pwd);
if(count($_POST)>0){
if($total){
	if(@$_POST['n_password']==@$_POST['c_password']){
      $arr['password']=md5($_POST['n_password']);
	echo $update="UPDATE ".TABLE_USERS." SET password='".$arr['password']."' , reset_key='' WHERE reset_key='".$_GET['key']."'";
    $resule=mysql_query($update);
	  $_SESSION['msg']='Password changed';
	 redirectURL(SITE_ADMIN_URL."whats-happening.php#tabs-4");
     }else{
     $_SESSION['msg']="New password and Comform password are not matching";
     }
}
else
	 {
	$_SESSION['msg']="Your key is expired";	 
	 }
}
}
else
{
	$_SESSION['msg']="Worng Process";	 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<form action="forgot.php?key=<?php echo @$_GET['key']?>" method="post" name="form1" enctype="multipart/form-data">
<input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>"><br/>
<table width="70%" border="1" cellspacing="0" cellpadding="5" >

<?php echo @$_SESSION['msg'];
//print_r($_SESSION);
//$_SESSION['msg']="";

?>
   <tr>
    <td >New Password :-</td>
    <td ><input type="n_password" name="n_password" value="<?php echo @$_POST['n_password'];?>" required="required" style="width:750px;"><br/></td>
  </tr>
   <tr>
    <td >Password :-</td>
    <td ><input type="c_password" name="c_password" value="<?php echo @$_POST['c_password'];?>" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td ><input type="submit" value="Login"  /></td>
  </tr>
</table>


</form>
</body>
</html>