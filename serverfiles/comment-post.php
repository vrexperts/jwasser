<?php 
require("PHPMailer/class.phpmailer.php");
require_once("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();

if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			 $lastID1 = $dbObj->insert_data(TABLE_COMMENT,$arr);
			 
			 /* mail for new comment*/
			 if($_SESSION['session_admin_userid']==''){
			 if($lastID1){$_SESSION['msg']='<span style=" color:green;font-size:13px;">Your comment is successfully posted. Will display after approval.</span>';}
		     $user_name='jwasser.com';//site url		 
			 $mail = new PHPMailer();
             $mail->From     = "admin@jwasser.com";
			 $mail->FromName = $user_name;
			 $mail->AddReplyTo($_POST['email']);
             $mail->AddAddress("shallu.47@gmail.com");
             $mail->Subject  = "Comment Posted On Post";
			 $mail->IsHTML(true);
			 $mail->Body = "<b><font  style='font-size:14px;'>Below comment posted on post for approvel</font></b><br><br>
		                    <br>".$arr['comment']."<br>";
             $mail->send();
			 //if(!$mail->send()){echo "Not Send";die;}else{echo "Mail Send";die;}
			 }
			$sqlSel_com1 = "SELECT * FROM " . TABLE_COMMENT." where post_id=".$arr['post_id'] ;
			$rsResult_com1 = $dbObj->fun_db_query($sqlSel_com1);
			$total_comment = $dbObj->fun_db_get_num_rows($rsResult_com1);
			
			$arr['total_comment']=$total_comment;
            $lastID = $dbObj->update_data(TABLE_POST,'id',$arr,md5($arr['post_id']));
			
			if($lastID){ redirectURL("show-post.php?id=".$arr['post_id']);}
	}?>