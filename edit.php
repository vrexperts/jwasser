<?php include("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
if(count($_POST)>0){
         $arr=$_POST;
		     if($_FILES['images']['name']!=''){
               $randnum = rand();
		       $str = str_replace(' ','_',$_FILES['images']['name']);
	           $Small_logo_ImgFName ="user/user_".$str;
		       $image = new SimpleImage();
		       $image->load($_FILES['images']['tmp_name']);
	           $image->resize(100,100);
	           $image->save($Small_logo_ImgFName);
		       $arr['images']= "user/user_".$str;
		     }
			//$existusername=$dbObj->fun_check_username_admin_existance1($arr['username']);
			//$existemail=$dbObj->fun_check_email_admin_existance1($arr['email']);
			//if($existusername){$_SESSION['msg']= "Username already Exits";header("Location: whats-happening.php#tabs-3");}
			//elseif($existemail){$_SESSION['msg']="Email already Exits";header("Location: whats-happening.php#tabs-3");}
			//else{ 
			
			$lastID = $dbObj->update_data(TABLE_USERS,'id',$arr,md5($_POST['id']));
			//if($lastID){
				
				
				 header("Location: whats-happening.php#tabs-3");
				 //}
			//}
			
}

?>
