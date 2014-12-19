<?php
session_start();
//session_destroy();
unset($_SESSION['session_admin_userid']);
unset($_SESSION['session_admin_username']);
unset($_SESSION['session_admin_password']);
unset($_SESSION['session_admin_usertype']);
$_SESSION['msg']='You have logged out successfully';
header("Location: index.php");
?>