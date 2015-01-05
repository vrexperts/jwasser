<?php include("includes/application-top.php");
$dbObj = new DB();
$dbObj->fun_db_connect();
if(count($_POST)>0){
         $arr=$_POST;
		 
		     $image_info = getimagesize($_FILES["images"]["tmp_name"]);
              $image_width = $image_info[0];
              $image_height = $image_info[1];
			  
			  if($_FILES['images']['name']!=''){
				  if($image_width>=400 && $image_height>=300)
				{
               $randnum = rand();
		       $str = str_replace(' ','_',$_FILES['images']['name']);
	           $Small_logo_ImgFName ="user/user_".$str;
		       $image = new SimpleImage();
		       $image->load($_FILES['images']['tmp_name']);
	           $image->resize(400,300);
	           $image->save($Small_logo_ImgFName);
		       $arr['images']= "user/user_".$str;
				}
				else
				{
					$_SESSION['msg']="Images Size To small";
					redirectURL(SITE_ADMIN_URL."profile-edit.php");
				}
		     }
			$lastID = $dbObj->update_data(TABLE_USERS,'id',$arr,md5($_POST['id']));
			if($lastID){$_SESSION['msg']="User Updated";}
			if($_REQUEST['action']=='edit'){redirectURL(SITE_ADMIN_URL."profile-edit.php?action=edit&id=".$_REQUEST['id']);}
			else {redirectURL(SITE_ADMIN_URL."instagram.php");}
				
				
				 

}

?>
