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
			if($lastID){ header("Location: whats-happening.php#tabs-5");
}
		
			
}
?>
