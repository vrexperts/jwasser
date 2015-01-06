<?php
require("PHPMailer/class.phpmailer.php");
require_once("includes/application-top.php");
$objAdmin = new Admins();
$objAdmin->fun_authenticate_admin();
$dbObj = new DB();
$dbObj->fun_db_connect();
if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			 $arr['status']=1;
			 if($_FILES['image']['size']<1000000)
			 {
			   $image_info = getimagesize($_FILES["image"]["tmp_name"]);
               $image_width = $image_info[0];
               $image_height = $image_info[1];
				if($image_width>400 && $image_height>300)
				{
					if($_FILES['image']['name']!=''){
                      $randnum = rand();
		              $str =rand().str_replace(' ','_',$_FILES['image']['name']);
	                  $Small_logo_ImgFName ="post/post_".$str;
		              $image = new SimpleImage();
		              $image->load($_FILES['image']['tmp_name']);
	                  //$image->resize(800,600);
					  $image->resize(400,300);
	                  $image->save($Small_logo_ImgFName);
		              $arr['image']= "post/post_".$str;
		     }
			 
			 if($_FILES['image']['name']!=''){
                      $randnum = rand();
		              $str ="large".rand().str_replace(' ','_',$_FILES['image']['name']);
	                  $Small_logo_ImgFName ="post/post_".$str;
		              $image = new SimpleImage();
		              $image->load($_FILES['image']['tmp_name']);
	                  $image->resize(800,600);
					  //$image->resize(400,300);
	                  $image->save($Small_logo_ImgFName);
		              $arr['larg_image']= "post/post_".$str;
		     }
			 
			  $lastID = $dbObj->insert_data(TABLE_POST,$arr);
			  $adminInfo = $objAdmin->fun_getAdminUserInfo(0, $_SESSION['session_admin_username']);
			  if($lastID){
				  $user_name='jwasser.com';//site url		 
			      $mail = new PHPMailer();
                  $mail->From     = "admin@jwasser.com";
			      $mail->FromName = $user_name;
			      $mail->AddReplyTo($adminInfo['email']);
                  $mail->AddAddress("shallu.47@gmail.com");
				 $mail->Subject  = "New Post";
				 $mail->IsHTML(true);
				 $mail->Body = "<b><font  style='font-size:14px;'>New Post on jwasser.</font></b><br><br>
								<br> Posted By : ".$_SESSION['session_admin_username']."<br>";
				 $mail->send();
				 //if(!$mail->send()){echo "Not Send";die;}else{echo "Mail Send";}
 				$_SESSION['msg']="<span style=' color:green;font-size:13px;'>Post Insert Successfully</span>";
				redirectURL(SITE_ADMIN_URL."index.php");	 
              }
				}
				else
				{
					$_SESSION['msg']="Images Size To small";
					redirectURL(SITE_ADMIN_URL."add-post.php");
				}
				 
		     
			 }
			 else
			 {
				 $_SESSION['msg']="Plz check Images Size";
				 redirectURL(SITE_ADMIN_URL."add-post.php");
			 }
			
	}
?>
