<?php
require("PHPMailer/class.phpmailer.php");
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
//print_r($_POST);
if(count($_POST)>0){
$arr=$_POST;
$existemail=$dbObj->fun_check_email_admin_existance1($arr['email']);

if($existemail){
   
    function getRandomString($length = 6) {
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ123456789";
    $validCharNumber = strlen($validCharacters);
    $result = "";
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
    return $result;
}
      $arr['reset_key']=getRandomString();
	  $lastID = $dbObj->update_data(TABLE_USERS,'email',$arr,md5($_POST['email']));
	  $user_name='jwasser.com';//site url	
      $mail = new PHPMailer();
      $mail->FromName = $user_name;
      $mail->From     = "admin@jwasser.com";
      $mail->AddAddress($_POST['email']);
      $mail->AddReplyTo("admin@jwasser.com");
      $mail->Subject  = "To reset password";
      $mail->IsHTML(true);
      $mail->Body = "<b><font  size='+2' color='red'>Reset Password</font></b><br><br>
		             <br>Click the link below to reset your password<br>
		             http://".$_SERVER['HTTP_HOST']."/94/jwasser/forgot.php?key=".$arr['reset_key']."<br><br>";
 if(!$mail->Send()) {

 $_SESSION['msg']= '<span style=" color:green;font-size:13px;">Mail was not sent.</span>';
  redirectURL(SITE_ADMIN_URL."profile-login.php");

} else {

  $_SESSION['msg']="Check your email INBOX to reset your password";
  redirectURL(SITE_ADMIN_URL."profile-login.php");
}

	 
	 
	 
	 
}
else
	 {
	$_SESSION['msg']="Check your email ID to reset your password";
	redirectURL(SITE_ADMIN_URL."profile-login.php");	 
	 }

       

}
?>
