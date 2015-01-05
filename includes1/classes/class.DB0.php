<?php
class DB{
	var $dbLink;
	var $dbHost;
	var $dbUsername;
	var $dbPassword;
	var $dbDatabase;
	var $dbConnectPersistant;
	function DB(){
			$this->dbHost = "localhost";
			$this->dbUsername = "vrexpert_chimney";
			$this->dbPassword = "EAvOi+h2G,Z)";
			$this->dbDatabase = "vrexpert_chimney";   
		    $this->dbConnectPersistant = false; 
		    $this->fun_db_connect();
	} 
	function fun_db_connect(){
		if($this->dbConnectPersistant){
			$this->dbLink = mysql_pconnect($this->dbHost, $this->dbUsername,  $this->dbPassword) or die("<font color='#ff0000' face='verdana' face='2'>Error: Could not connect to database server!</font> ". mysql_error());
		}else{
			$this->dbLink = mysql_connect($this->dbHost, $this->dbUsername,  $this->dbPassword) or die("<font color='#ff0000' face='verdana' face='2'>Error: Could not connect to database server!</font> " . mysql_error());
		}
		mysql_select_db($this->dbDatabase, $this->dbLink) or die(mysql_error());
	}
	function fun_db_query($sql){
		return @mysql_query($sql, $this->dbLink);
	}
	function fun_db_get_num_rows($result){
		return @mysql_num_rows($result);
	}
	function fun_db_get_affected_rows(){
		return @mysql_affected_rows($this->dbLink);
	}
	function fun_db_last_inserted_id(){
		return @mysql_insert_id($this->dbLink);
	}
	
	function fun_db_fetch_rs_array($result){
		return @mysql_fetch_array($result);
	}
	function fun_db_fetch_rs_object($result){
		return @mysql_fetch_object($result);
	}
	function fun_db_fetch_rs_row($result){
		return @mysql_fetch_row($result);
	}
	function fun_db_free_resultset($result){
		@mysql_free_result($result);
	}
	function fun_db_close_connection(){
		@mysql_close($this->dbLink);
	}
	function createRecordset($sql) {
		$rs = $this->mySqlSafeQuery($sql);
		return($rs);
	}
	function mySqlSafeQuery($query) {
		$this->lastSql = $query;
		$result = FALSE;
        if ($this->dumpSql === TRUE) {
            echo "$query<br>";
        }
		$rs = @mysql_query($query, $this->dbLink);
		$errno = mysql_errno($this->dbLink);
		if ($errno > 0) {
			$error_text = mysql_error($this->dbLink);
			@mysql_query("unlock tables");  # Clear any locked tables

			trigger_error($error_text . ": " . $query, E_USER_ERROR);
		} else {
			$result = $rs;
		}
		return $result;
	}
	function getRecordCount($rs) {
		return mysql_num_rows($rs);
	}
	function &fetchAssoc($rs) {
		$records = array();
		while ($row = mysql_fetch_assoc($rs)) {
			array_push($records, $row);
		}
		return $records;
	}
	function stripMagicQuotes($arr)
	{
	if(is_array($arr)){
		foreach ($arr as $k => $v) {
			$arr[$k] = is_array($v) ? stripMagicQuotes($v) : stripslashes($v);
		}}	
		return $arr;
	}
	function get_row($table_name,$condition)
	{
		$sql = "select * from ".$table_name." where ".$condition;
		$res=$this->fun_db_query($sql);
		$rec=$this->stripMagicQuotes(mysql_fetch_assoc($res));
	return $rec;
	}	
function insert_data($table_name,$data_array,$link='') 
{
	$fld_str='';$val_str='';
	if($table_name && is_array($data_array))
	{
		 $sql="SHOW COLUMNS FROM `$table_name`";
		$columns_query = $this->fun_db_query($sql,$link);
		while($coloumn_data = mysql_fetch_assoc($columns_query)){
				$column_name[]=$coloumn_data[Field];
		}
		foreach($data_array as $key=>$val)
		{
			 if(in_array($key,$column_name))
			 {
				$fld_str.="$key,";	
				   if($val=='now()')
				   {	
						$val_str.= addslashes(trim($val)).",";
				   }
					else
					{
						$val=preg_replace("/(\<script)(.*?)(script>)/si", "", "$val");
						$val=preg_replace("/(\&lt;script)(.*?)(script&gt;)/si", "", "$val");
						$val_str.="'".addslashes(trim($val))."',";
					}
			  }
		 }
		 $fld_str=substr($fld_str,0,-1);
		 $val_str=substr($val_str,0,-1);
		 $sql="INSERT INTO $table_name($fld_str) VALUES($val_str)";
		 $this->fun_db_query($sql,$link);
		return mysql_insert_id();
	}
}	
function update_data($table_name,$match_fld,$data_array,$rec_id,$link='') 
	{
	$fld_str='';$val_str='';
	if($table_name && is_array($data_array))
	{	
	 $sql="SHOW COLUMNS FROM `$table_name`";
		$columns_query = $this->fun_db_query($sql,$link);
	while($coloumn_data = mysql_fetch_assoc($columns_query))
		  $column_name[]=$coloumn_data['Field'];
	foreach($data_array as $key=>$val)
		{	
		if(in_array($key,$column_name))
			{
				$fld_str.="$key,";
				if($val=='now()')	
					$val_str.= "$key=".addslashes(trim($val)).",";
				else{
					$val=preg_replace("/(\<script)(.*?)(script>)/si", "", "$val");
					$val=preg_replace("/(\&lt;script)(.*?)(script&gt;)/si", "", "$val");
					$val_str.="$key='".addslashes(trim($val))."',";
					}
			 }
			 }
			 $val_str=substr($val_str,0,-1);
		 $sql="UPDATE `$table_name` SET $val_str WHERE md5(`$match_fld`)='$rec_id'";
		 $this->fun_db_query($sql,$link);
		 return mysql_affected_rows();
			}
		}
	function fun_verifyMember($emailID, $pass){
		$memFound = false;
		$sqlCheck = "SELECT email FROM " . TBL_PREFIX . " WHERE email='$userID'";
		$sqlCheck .= " AND password='".md5($pass)."'";
		$this->fun_db_query($sqlCheck,$link);
		if($this->fun_get_num_rows($sqlCheck) > 0){
			$memFound = true;
		}
		return $memFound;
	}
}
?>