<?php
require_once("includes/application-top.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php echo @$_SESSION['msg'];

$_SESSION['msg']="";

?>

<form action="login.php" method="post" name="form1" enctype="multipart/form-data">
<table width="70%" border="1" cellspacing="0" cellpadding="5" >
    <tr>
    <td >User Name :-</td>
    <td > <input type="text" name="username" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >Password :-</td>
    <td ><input type="password" name="password" value="" required="required" style="width:750px;"><br/></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td ><input type="submit" value="Login"  /></td>
  </tr>
</table>
For New Registration <a href="create-user.php" style=" text-decoration:none;">Click Here</a> <a href="logout.php">Logout</a>


</form>
</body>
</html>