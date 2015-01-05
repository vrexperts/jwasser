<?php include("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			 $arr['password']=md5($arr['password']);
			 $arr['status']=1;
			 
		     if($_FILES['images']['name']!=''){
               $randnum = rand();
		       $str = str_replace(' ','_',$_FILES['images']['name']);
	           $Small_logo_ImgFName ="user/user_".$str;
		       $image = new SimpleImage();
		       $image->load($_FILES['images']['tmp_name']);
	           $image->resize(400,300);
	           $image->save($Small_logo_ImgFName);
		       $arr['images']= "user/user_".$str;
		     }
			$existusername=$dbObj->fun_check_username_admin_existance1($arr['username']);
			$existemail=$dbObj->fun_check_email_admin_existance1($arr['email']);
			if($existusername){$_SESSION['msg']= "Username already Exits";redirectURL(SITE_ADMIN_URL."create-profile.php");}
			elseif($existemail){$_SESSION['msg']="Email already Exits";redirectURL(SITE_ADMIN_URL."create-profile.php");}
			else{$lastID = $dbObj->insert_data(TABLE_USERS,$arr);
			if($lastID){ $_SESSION['msg']="Profile Created"; redirectURL(SITE_ADMIN_URL."create-profile.php");}
			}
			
}

?>