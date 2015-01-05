<?php
require_once("includes/application-top.php");
//session_destroy();
unset($_SESSION['session_admin_userid']);
unset($_SESSION['session_admin_username']);
unset($_SESSION['session_admin_password']);
unset($_SESSION['session_admin_type']);
if(@$_REQUEST['reset']=='yes'){
	$_SESSION['msg']='Password changed';
}
else
{
	$_SESSION['msg']='You have logged out successfully';

}

redirectURL(SITE_ADMIN_URL."profile-login.php");
?>