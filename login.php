<?php
require_once("includes/application-top.php");
	$objAdmin = new Admins();
	$adminUname = fun_db_output($_POST['username']);
	$adminPass = fun_db_output($_POST['password']);
	if($objAdmin->fun_verify_admins($adminUname, md5($adminPass))){
		$adminInfo = $objAdmin->fun_getAdminUserInfo(0, $adminUname);
		if(sizeof($adminInfo)){
			if($adminInfo['status']=="1"){
				$_SESSION['session_admin_userid'] =  $adminInfo['id'];
				$_SESSION['session_admin_username'] = $adminInfo['username'];
				 $_SESSION['session_admin_password'] = $adminInfo['password'];
				 $_SESSION['session_admin_type'] = $adminInfo['type'];
				redirectURL(SITE_ADMIN_URL."profile.php");
			}else{
				unset($_SESSION['session_admin_userid']);
				unset($_SESSION['session_admin_username']);
				unset($_SESSION['session_admin_password']);
				$_SESSION['msg']='You account has been suspended due to some reason!';
				redirectURL(SITE_ADMIN_URL."profile-login.php");
			}
		}else{
			$_SESSION['msg']='Invalid username or password!';
			redirectURL(SITE_ADMIN_URL."profile-login.php");
		}
	}else{
		$_SESSION['msg']='Invalid username or password!';
	    redirectURL(SITE_ADMIN_URL."profile-login.php");
	}
?>