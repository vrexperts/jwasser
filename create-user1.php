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
	           $image->resize(100,100);
	           $image->save($Small_logo_ImgFName);
		       $arr['images']= "user/user_".$str;
		     }
			$existusername=$dbObj->fun_check_username_admin_existance1($arr['username']);
			$existemail=$dbObj->fun_check_email_admin_existance1($arr['email']);
			if($existusername){echo "Username already Exits";}
			elseif($existemail){echo "Email already Exits";}
			else{$lastID = $dbObj->insert_data(TABLE_USERS,$arr);
			if($lastID){ echo "User Inserted";}
			}
			
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="" method="post" name="form1" enctype="multipart/form-data">
Name :- <input type="text" name="name" value=""><br/>
User Name :- <input type="text" name="username" value=""><br/>
Password :- <input type="text" name="password" value=""><br/>
Email :- <input type="text" name="email" value=""><br/>
Images :- <input type="file" name="images" value=""><br/>
<input type="submit" value="create"  />


</form>


</body>
</html>