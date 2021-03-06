<?php
class Admins{
	var $dbObj;
	
	function Admins(){ // class constructor
		$this->dbObj = new DB();
		$this->dbObj->fun_db_connect();
	}
	
	function processAdmins($usrID=0, $actionMode='ADD'){
		if($usrID==""){
			$usrID = 0;
		}
		
		$userArray = array(
										
						"username" => $_POST['username'],
						"password" => $_POST['password'],
						"email" => $_POST['email'],
						"name" => $_POST['name'],
						"status" => $_POST['status'],
						"add_date" => date("Y-m-d H:i:s")
					);
		
		if($actionMode=='EDIT'){
			
			$fields = "";
			$fieldsVal = "";
			foreach($userArray as $keys => $vals){
				$fields .= $keys . "='" . fun_db_input($vals). "', ";
			}
			$fields = trim($fields);
			if($fields!=""){
				$fields = substr($fields,0,strlen($fields)-1);
				 $sqlUpdate = "UPDATE " . TABLE_USERS . " SET " . $fields . " WHERE id='".(int)$usrID."'";
				
				$this->dbObj->fun_db_query($sqlUpdate) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request!<br>Invalid Query On Admin User table.</font>");
				return $this->dbObj->fun_db_get_affected_rows();
			}
		}
		
		if($actionMode=='ADD'){
			$fields = "";
			$fieldsVal = "";
			foreach($userArray as $keys => $vals){
				$fields .= $keys . ", ";
				$fieldsVal .= "'" . fun_db_input($vals). "', ";
			}
			$sqlInsert = "INSERT INTO " . TABLE_USERS . "(id, ".$fields." add_date) " ;
			$sqlInsert .= " VALUES(null, ".$fieldsVal." '".date("Y-m-d H:i:s")."')";		
			$this->dbObj->fun_db_query($sqlInsert) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request!<br>Invalid Query On Admin User table.</font>");
			return $this->dbObj->fun_db_get_affected_rows();
		}
	}
	
	function processAdminEmail($usrID=0, $actionMode='ADD'){
		if($usrID==""){
			$usrID = 0;
		}
		
		$userArray = array(
					
						"email " => $_POST['email'],
						"status" =>'1',
						"add_date" => date("Y-m-d H:i:s"),
					);
		
		if($actionMode=='EDIT'){
			
			$fields = "";
			$fieldsVal = "";
			foreach($userArray as $keys => $vals){
				$fields .= $keys . "='" . fun_db_input($vals). "', ";
			}
			$fields = trim($fields);
			if($fields!=""){
				$fields = substr($fields,0,strlen($fields)-1);
				  $sqlUpdate = "UPDATE " . TABLE_USERS . " SET " . $fields . " WHERE id='".(int)$usrID."'";
				
				
				$this->dbObj->fun_db_query($sqlUpdate) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request!<br>Invalid Query On Admin User table.</font>");
				return $this->dbObj->fun_db_get_affected_rows();
			}
		}
		
		
	}
	
	
	function fun_check_username_admin_existance1($username, $auID=''){ // this function checked checks wheather username exists or not
		$unameFound = false;
		$sqlCheck = "SELECT username FROM " . TABLE_USERS . " WHERE username='".fun_db_input($username)."' ";
		if($auID!=""){
			$sqlCheck .= " AND id<>'".(int)$auID."'";
		}
		if($this->fun_get_num_rows($sqlCheck) > 0){
			$unameFound = true;
		}
		return $unameFound;
	}
	
	
	
	
	function fun_check_username_admin_existance($username, $auID=''){ // this function checked checks wheather username exists or not
		$unameFound = false;
		$sqlCheck = "SELECT username FROM " . TABLE_USERS . " WHERE username='".fun_db_input($username)."' ";
		if($auID!=""){
			$sqlCheck .= " AND id<>'".(int)$auID."'";
		}
		if($this->fun_get_num_rows($sqlCheck) > 0){
			$unameFound = true;
		}
		return $unameFound;
	}
	function fun_check_email_admin_existance($email, $auID=''){ // this function checked checks wheather username exists or not
		$unameFound = false;
		$sqlCheck = "SELECT email FROM " . TABLE_USERS . " WHERE email='".fun_db_input($email)."' ";
		if($auID!=""){
			$sqlCheck .= " AND id<>'".(int)$auID."'";
		}
		if($this->fun_get_num_rows($sqlCheck) > 0){
			$unameFound = true;
		}
		return $unameFound;
	}

	function funDeleteUser($au_id){
		$sqlDelete = "DELETE FROM " . TABLE_USERS . " WHERE id='".(int)$au_id."'";
		$this->dbObj->fun_db_query($sqlDelete);
		return $this->dbObj->fun_db_get_affected_rows();
	}


	function fun_check_emailid_admin_existance($emailid, $auID=''){ // this function checked checks wheather email id exists or not
		$emailIDFound = false;
		$sqlCheck = "SELECT email FROM " . TABLE_USERS . " WHERE email='".fun_db_input($emailid)."' ";
		if($auID!=""){
			$sqlCheck .= " AND id<>'".(int)$auID."'";
		}
		$result = $this->dbObj->fun_db_query($sqlCheck) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request !</font>");
		if($this->fun_get_num_rows($result) > 0){
			$emailIDFound = true;
		}
		return $emailIDFound;
	}
	
	function fun_check_login_admins(){
		if(isset($_SESSION['session_admin_userid']) && isset($_SESSION['session_admin_username']) && isset($_SESSION['session_admin_password'])){
			if($this->fun_verify_admins($_SESSION['session_admin_username'], $_SESSION['session_admin_password'])){
				return true;
			}else{
				unset($_SESSION['session_admin_userid']);
				unset($_SESSION['session_admin_username']);
				unset($_SESSION['session_admin_password']);
				return false;
			}
		}else{
			return false;
		}
	}
	
	function fun_verify_admins($username, $password){
		$sqlCheck = "SELECT username, password FROM " . TABLE_USERS . " WHERE username='".fun_db_input($username)."'";
		
		$result = $this->dbObj->fun_db_query($sqlCheck) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request 222!</font>");
		if(!$result || $this->dbObj->fun_db_get_num_rows($result) < 1){
			return false; // ADMIN does not exists
		}
		$rowsPass = $this->dbObj->fun_db_fetch_rs_object($result);
		//$adminPass = md5(fun_db_output($rowsPass->au_password));
		
		$adminPass = $rowsPass->password;
		//echo "<br/>";
		//echo $password;
		//echo "<br/>";
		//print_r($_SESSION);
		//echo md5($password);
		//echo ">>>".$adminPass;
	
		$this->dbObj->fun_db_free_resultset($result);
		if($adminPass == $password){
			//echo "hi";
			//die;
			return true; // ADMIN exists
		}else{
			//echo "No";
			//die;
			return false; // ADMIN does not exists
		}
	}
	
	function forgo_password($Adminemailid)
	{
	    
		$sqlCheck = "SELECT email  FROM " . TABLE_USERS . " WHERE email='".fun_db_input($Adminemailid)."'";
		
		$result = $this->dbObj->fun_db_query($sqlCheck) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request!</font>");
		if($this->dbObj->fun_db_get_num_rows($result) < 1){
			return false; // ADMIN does not exists
		}
			else
				{
				 return true;
				}		
	}
	
	
	
	
function forgo_password_name($Adminemailid)
	{
	    
		 $sqlCheck = "SELECT *  FROM " . TABLE_USERS . " WHERE email='".fun_db_input($Adminemailid)."'";
		
	$result = $this->dbObj->fun_db_query($sqlCheck) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request!</font>");
		
		  $au_fname_Admin = $this->dbObj->fun_db_fetch_rs_object($result);
		   $au_fname_au_lname_Admin = array(
		   								"name" => fun_db_output($au_fname_Admin->name),
										"username" => fun_db_output($au_fname_Admin->username),
										"password" => fun_db_output($au_fname_Admin->password )
		   									);
											
		$this->dbObj->fun_db_free_resultset($result);
		return  $au_fname_au_lname_Admin;
		
				
	}
	
	
	
	
	function fun_getAdminUserInfo($auID=0, $auUsername=''){
		$sql = $sqlCheck = "SELECT * FROM " . TABLE_USERS;
		if($auUsername==""){
			$sql .= " WHERE id='".(int)$auID."'";
		}else{
			$sql .= " WHERE username='".fun_db_input($auUsername)."'";
		}
	$result = $this->dbObj->fun_db_query($sql) or die("<font color='#ff0000' face='verdana' size='2'>Error: Unable to execute request!</font>");
		$rowsAdmin = $this->dbObj->fun_db_fetch_rs_object($result);
		$adminArray = array(
							"id" => fun_db_output($rowsAdmin->id),
							"username" => fun_db_output($rowsAdmin->username),
							"password" => fun_db_output($rowsAdmin->password),
							"email" => fun_db_output($rowsAdmin->email),
							"name" => fun_db_output($rowsAdmin->name),
							"status" => fun_db_output($rowsAdmin->status),
							"add_date" => fun_db_output($rowsAdmin->add_date)
							
						);
		$this->dbObj->fun_db_free_resultset($result);
		return $adminArray;
	}
	
	function fun_authenticate_admin(){
		if(!$this->fun_check_login_admins()){
			$_SESSION['msg']='Your session has been expired!';
			//$msg = urlencode("Your session has been expired!");
			//redirectURL(SITE_ADMIN_URL."site-entry.php?msg=".urlencode($msg));
			echo "<script language=\"javascript\">parent.location.href=\"".SITE_ADMIN_URL."index.php\";</script>";
		}
	}
	
	function fun_check_user_permisstion($uID, $uType=2, $permis=''){
		$hasPermission = false;
		$usrDets = $this->fun_getAdminUserInfo($uID);
		if($uType==1){
			$hasPermission = true;
		}else{
			switch($permis){
				case 'canview':
					if($usrDets['au_can_view']){
						$hasPermission = true;
					}
				break;
				case 'canadd':
					if($usrDets['au_can_add']){
						$hasPermission = true;
					}
				break;
				case 'canedit':
					if($usrDets['au_can_edit']){
						$hasPermission = true;
					}
				break;
				case 'candelete':
					if($usrDets['au_can_delete']){
						$hasPermission = true;
					}
				break;
				case 'canactivate':
					if($usrDets['au_activate']){
						$hasPermission = true;
					}
				break;
				case 'candeactive':
					if($usrDets['au_deactive']){
						$hasPermission = true;
					}
				break;
			}
		}
		return $hasPermission;
	}
	
	function fun_get_num_rows($sql){
		$totalRows = 0;
		$selected = "";
		$sql = trim($sql);
		if($sql==""){
			die("<font color='#ff0000' face='verdana' face='2'>Error: Query is Empty!</font>");
			exit;
		}
		$result = $this->dbObj->fun_db_query($sql);
		$totalRows = $this->dbObj->fun_db_get_num_rows($result);
		$this->dbObj->fun_db_free_resultset($result);
		return $totalRows;
	}
	
	function fun_check_username_admin_password($oldpassword, $auID=''){ // this function checked checks wheather username exists or not
		$unameFound = 0;
		$sqlCheck = "SELECT * FROM " . TABLE_USERS . " WHERE password='".$oldpassword."' ";
		if($auID!=""){
			$sqlCheck .= " AND id='".(int)$auID."'";
		}
		
		if($this->fun_get_num_rows($sqlCheck) > 0){
			$unameFound = 1;
		}
		
		//echo ">>>>".$unameFound;
		return $unameFound;
	}
}
?>