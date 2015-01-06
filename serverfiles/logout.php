<?php
require_once("includes/application-top.php");
//session_destroy();
unset($_SESSION['session_admin_userid']);
unset($_SESSION['session_admin_username']);
unset($_SESSION['session_admin_password']);
unset($_SESSION['session_admin_type']);
if(@$_REQUEST['reset']=='yes'){
	$_SESSION['msg']='<span style=" color:green;font-size:13px;">Password changed</span>';
}
else
{
	$_SESSION['msg']='<span style=" color:green;font-size:13px;">You have logged out successfully</span>';

}

redirectURL(SITE_ADMIN_URL."profile-login.php");
?>