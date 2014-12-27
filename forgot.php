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
	$update="UPDATE ".TABLE_USERS." SET password='".$arr['password']."' , reset_key='' WHERE reset_key='".$_GET['key']."'";
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Forgot Password</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="instagram">
 <div class="pad5"></div>
 
  <?php if(@$_SESSION['msg']!=''):?><div class="title"> <?php echo @$_SESSION['msg'];?></div><?php endif;?>

<form action="forgot.php?key=<?php echo @$_GET['key']?>" method="post" name="form1" enctype="multipart/form-data">


<div class="content">
 <fieldset>
<legend><strong>Set Password</strong></legend>
 <div class="pad5"></div>
<input type="password" class="instxt" placeholder="New Password"  name="n_password" value="" required="required"/>
 <div class="pad5"></div>
<input type="password" class="instxt" placeholder="Confirm Password" name="c_password" value="" required="required"/>
 <div class="pad5"></div>
<input type="submit" class="button" />
  </fieldset>
 </div>
</form>

</div>
</body>
</html>