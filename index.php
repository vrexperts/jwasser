<?php
require_once("includes/application-top.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php echo @$_SESSION['msg'];?>
<form action="login.php" method="post" name="form1" enctype="multipart/form-data">
User Name :- <input type="text" name="username" value=""><br/>
Password :- <input type="password" name="password" value=""><br/>
<input type="submit" value="Login"  /> For New Registration <a href="create-user.php" style=" text-decoration:none;">Click Here</a> <a href="logout.php">Logout</a>


</form>
</body>
</html>