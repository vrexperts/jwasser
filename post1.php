<?php
require_once("includes/application-top.php");
$objAdmin = new Admins();
$objAdmin->fun_authenticate_admin();
$dbObj = new DB();
$dbObj->fun_db_connect();
if(count($_POST)>0){
         $arr=$_POST;
		     $arr['add_date']= date("Y-m-d H:i:s");
			 $arr['status']=1;
			 print_r($_FILES);
		     if($_FILES['image']['name']!=''){
				 $target_path = "post/";
                 $target_path = $target_path . basename( $_FILES['image']['name']);
				 
				 move_uploaded_file($_FILES["image"]["tmp_name"], $target_path);
				 
				 
               //$randnum = rand();
		      // $str = str_replace(' ','_',$_FILES['image']['name']);
	           //$Small_logo_ImgFName ="post/user_".$str;
		       //$image = new SimpleImage();
		      // $image->load($_FILES['image']['tmp_name']);
	           //$image->resize(100,100);
	           //$image->save($Small_logo_ImgFName);
		      // $arr['image']= "post/user_".$str;
			   $arr['image']= $target_path;
		     }
			$lastID = $dbObj->insert_data(TABLE_POST,$arr);
			if($lastID){ echo "Post Inserted";}
		
			
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php //print_r($_SESSION);?>

<form action="" method="post" name="form1" enctype="multipart/form-data">
Title :- <input type="text" name="title" value=""><input type="hidden" name="user_id" value="<?php echo @$_SESSION['session_admin_userid'];?>"><br/>
Tag :- <input type="text" name="tag" value=""><br/>
Description :- 


 <?php $sBasePath = $_SERVER['PHP_SELF'];
				              $sBasePath = admin_path."fckeditor/";
				              $oFCKeditor = new FCKeditor("description", @$text1['description']);
				              $oFCKeditor->InstanceName='description';
				              $oFCKeditor->BasePath	= $sBasePath;
				              $oFCKeditor->Value	= @$member['description'];
				              $oFCKeditor->Height =  340; 
				              $oFCKeditor->Width =  600; 
				              $oFCKeditor->Create();?>  

<br/>
Images :- <input type="file" name="image" value=""><br/>
<input type="submit" value="create"  />


</form>




post here
</body>
</html>