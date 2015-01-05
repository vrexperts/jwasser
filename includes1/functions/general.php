<?php
function redirectURL($rurl){?>
	<script type="text/javascript">

window.location = "<?php echo $rurl;?>"

</script>
<?php }
function fun_db_output($str){
	return @stripslashes($str);
}
function fun_db_input($str){
	$str = trim($str);
	if(!get_magic_quotes_gpc()){
		return addslashes($str);
	}else{
		return $str;
	}
}

function getInvoiceNo($invPrefix, $postFixOrderNo){
	$invoiceNo = $invPrefix . date("mdy");
	$postFixOrderNoLen = strlen($postFixOrderNo);
	if($postFixOrderNoLen==1){
		$invoiceNo .= "00000" . $postFixOrderNo;
	}elseif($postFixOrderNoLen==2){
		$invoiceNo .= "0000" . $postFixOrderNo;
	}elseif($postFixOrderNoLen==3){
		$invoiceNo .= "000" . $postFixOrderNo;
	}elseif($postFixOrderNoLen==4){
		$invoiceNo .= "00" . $postFixOrderNo;
	}elseif($postFixOrderNoLen==5){
		$invoiceNo .= "0" . $postFixOrderNo;
	}else{
		$invoiceNo .= $postFixOrderNo;
	}
	return $invoiceNo;
}

function fun_get_commas_values($str){ // if ,4,2,3,6, will be converted to 4,2,3,6
	$newStr = "";
	$str = trim($str);
	if(str!="" && strlen($str) > 2){
		$newStr = substr($str,1,strlen($str)-2);
	}
	return $newStr;
}

function fun_site_date_format($strDate){
	$dateFormat = "";
	if($strDate!=""){
		$dateFormat = date("d M, Y", strtotime($strDate));
	}
	return $dateFormat;
}

function fun_currency_format($curr=0){
	return number_format($curr, 2);
}
function fun_check_date($yyyy, $mm, $dd){
	$dateCode = array();
	if($mm < 1 || $mm > 12){
		$dateCode['code'] = false;
		$dateCode['codemsg'] = "The month date must be between 1 and 12!";
		return $dateCode;
	}
	if($dd < 1 || $dd > 31){
		$dateCode['code'] = false;
		$dateCode['codemsg'] = "The day date must be between 1 and 31!";
		return $dateCode;
	}
	if($dd==31 && ($mm==4 || $mm==6 || $mm==9 || $mm==11)){
		$dateCode['code'] = false;
		$dateCode['codemsg'] = "The month for your date doesn't have 31 days!";
		return $dateCode;
	}
	if($mm==2){
		$learYear = false;
		if($yyyy % 4 == 0 && ($yyyy % 100 != 0 || $yyyy % 400 == 0)){
			$learYear = true;
		}
		if($dd > 29 || ($dd==29 && !$learYear)){
			$dateCode['code'] = false;
			$dateCode['codemsg'] = "The month for your date doesn't have ".$dd." days for year ".$yyyy."!";
			return $dateCode;
		}
	}
	$dateCode['code'] = true;
	$dateCode['codemsg'] = "";
	return $dateCode;
}
function fun_create_number_options($startVal=0, $endVal=0, $selVal=''){
	$selected = "";
	for($i=$startVal; $i <= $endVal; $i++){
		if($i == $selVal && $selVal!=''){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$i."\" ".$selected.">" . $i . "</option>\n";
	}
}
function fun_created_month_option($selVal=''){
	$monthsArray = array();
	$monthsArray['1'] = "January";
	$monthsArray['2'] = "February";
	$monthsArray['3'] = "March";
	$monthsArray['4'] = "April";
	$monthsArray['5'] = "May";
	$monthsArray['6'] = "June";
	$monthsArray['7'] = "July";
	$monthsArray['8'] = "August";
	$monthsArray['9'] = "September";
	$monthsArray['10'] = "October";
	$monthsArray['11'] = "November";
	$monthsArray['12'] = "December";
	foreach($monthsArray as $keys => $vals){
		if($keys == $selVal){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$keys."\" ".$selected.">" . $vals . "</option>\n";
	}
}

function fun_getFileContent($fileName){
	$fileContent = "";
	
	$fp = fopen($fileName, "r");
	if($fp){
		$fileContent = fread($fp, filesize($fileName));
	}
	fclose($fp);
	return $fileContent;
}

function trimBodyText($theText, $lmt=70, $s_chr="\n", $s_cnt=1){
	  $pos = 0;
	  $trimmed = FALSE;
	  for($i=0; $i <= $s_cnt; $i++){
		  if($tmp = strpos($theText, $s_chr, $pos)){
			  $pos = $tmp;
			  $trimmed = TRUE;
		  }else{
			  $pos = strlen($theText);
			  $trimmed = FALSE;
			  break;
		  }
	  }
	  $theText = substr($theText, 0, $pos);
	  if(strlen($theText) > $lmt){
		  $theText = substr($theText, 0, $lmt);
		  $theText = substr($theText, 0, strrpos($theText, ' '));
		  $trimmed = TRUE;
	  }
	  if($trimmed){
		  $theText .= "...";
	  }
	  return $theText;
  }

function paginate($limit=10, $tot_rows){
	
	$numrows = $tot_rows;
	$pagelinks = "<div class=\"pagelinks\">";
	if($numrows > $limit){
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}

		$currpage = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
		$currpage = str_replace("&page=".$page,"",$currpage);

		if($page == 1){
			$pagelinks .= "<span class=\"pagelinks\">&lt; PREV </span>";
		}else{
			$pageprev = $page - 1;
			$pagelinks .= "<a class=\"pagelinks\" href=\"" . $currpage . "&page=". $pageprev . "\">&lt; PREV </a>";
		}

		$numofpages = ceil($numrows / $limit);
		$range = 7;
		$lrange = max(1, $page-(($range-1)/2));
		$rrange = min($numofpages, $page+(($range-1)/2));
		if(($rrange - $lrange) < ($range - 1)){
			if($lrange == 1){
				$rrange = min($lrange + ($range-1), $numofpages);
			}else{
				$lrange = max($rrange - ($range-1), 0);
			}
		}
		
		if($lrange > 1){
			$pagelinks .= " .. ";
		}else{
			$pagelinks .= " &nbsp;&nbsp; ";
		}
		for($i = 1; $i <= $numofpages; $i++){
			if($i == $page){
				$pagelinks .= "<span class=\"currentpagelinks\">$i</span>";
			}else{
				if($lrange <= $i && $i <= $rrange){
					$pagelinks .= " <a class=\"pagelinks\" href=\"".$currpage."&page=".$i."\">" . $i . "</a>  ";
				}
			}
		}
		
		if($rrange < $numofpages){
			$pagelinks .= " .. ";
		}else{
			$pagelinks .= " &nbsp;&nbsp; ";
		}

		if(($numrows - ($limit * $page)) > 0){
			$pagenext = $page + 1;
			$pagelinks .= "<a class=\"pagelinks\" href=\"". $currpage . "&page=" . $pagenext . "\"> NEXT &gt;</a>";
		}else{
			$pagelinks .= " <span class=\"taxtxt\"> NEXT &gt;</span>";
		}
	}else{
		//$pagelinks .= "<span class=\"pagelinks\">&lt; PREV</span>&nbsp;&nbsp;";
		//$pagelinks .= "<span class=\"pagelinks\">&nbsp;&nbsp;&nbsp;NEXT &gt;</span>&nbsp;&nbsp;";
	}
return $pagelinks;
}




function paginatefront($limit=10, $tot_rows){
	
	$numrows = $tot_rows;
	 //$pagelinks = "<div class='page_buttonin'>";
	 $pagelinks.= "<div class='page_buttonin'>";
		$pagelinks.= "<ul id='menu'>";
	if($numrows > $limit){
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}

		$currpage = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
		$currpage = str_replace("&page=".$page,"",$currpage);

		if($page == 1){
			$pagelinks .= "<li ><a class='current' style='width:51px; hight:30px; color:#ffffff; background-image:url(./images/pre.jpg);' >&lt; PREV</a></li>";
		}else{
			$pageprev = $page - 1;
			$pagelinks .= "<li><a style='width:51px; hight:30px; color:#ffffff; background-image:url(./images/pre.jpg);' href=\"" . $currpage . "&page=". $pageprev . "\">&lt; PREV</a></li>";
		}
        //$pagelinks.= "</div>";
		//$pagelinks.= "<div class='page_buttonin'>";
		//$pagelinks.= "<ul id='menu'>";
		
		
		$numofpages = ceil($numrows / $limit);
		$range = 7;
		$lrange = max(1, $page-(($range-1)/2));
		$rrange = min($numofpages, $page+(($range-1)/2));
		if(($rrange - $lrange) < ($range - 1)){
			if($lrange == 1){
				$rrange = min($lrange + ($range-1), $numofpages);
			}else{
				$lrange = max($rrange - ($range-1), 0);
			}
		}
		
		if($lrange > 1){
			$pagelinks .= "<li><a href='#'> .. </a></li>";
		}else{
			//$pagelinks .= " &nbsp;";
		}
		
		
		for($i = 1; $i <= $numofpages; $i++){
			if($i == $page){
				$pagelinks .= "<li><a class='current' >$i</a></li>";
			}else{
				if($lrange <= $i && $i <= $rrange){
					$pagelinks .= " <li><a  href=\"".$currpage."&page=".$i."\">" . $i . "</a></li>";
				}
			}
		}
		
		
		//$pagelinks.= "</ul>";
		//$pagelinks.= "</div>";
		//$pagelinks.="<div class='page_buttonin'>";
		
		if($rrange < $numofpages){
			$pagelinks .= " <li><a href='#'>..</a></li> ";
		}else{
			//$pagelinks .= "pramod";
		}

		if(($numrows - ($limit * $page)) > 0){
			$pagenext = $page + 1;
			$pagelinks .= "<li><a style='width:51px; hight:30px; color:#ffffff; background-image:url(./images/pre.jpg);' href=\"". $currpage . "&page=" . $pagenext . "\"> NEXT &gt;</a></li>";
		}else{
			$pagelinks .= " <li><a class='current'  style='width:51px; hight:30px; color:#ffffff; background-image:url(./images/pre.jpg);' >NEXT &gt;</a></li>";
		}
		
	}else{
		//$pagelinks .= "<span class=\"pagelinks\">&lt; PREV</span>&nbsp;&nbsp;";
		//$pagelinks .= "<span class=\"pagelinks\">&nbsp;&nbsp;&nbsp;NEXT &gt;</span>&nbsp;&nbsp;";
	}
	$pagelinks.= "</ul>";
	$pagelinks .="</div>";
	
return $pagelinks;
}


function fun_product_price_range_array(){
	$priceRange = array(
					"1" => "US $1.00 - US $2.99 ",
					"2" => "US $3.00 - US $4.99",
					"3" => "US $5.00 - US $7.99",
					"4" => "US $8.00 - US $10.99",
					"5" => "US $11.00 - US $14.99",
					"6" => "US $15.00 - US $19.99",
					"7" => "US $20.00 and Over"
				);
	return $priceRange;
}		

function fun_product_price_range_option($pr=''){
	$priceRange = fun_product_price_range_array();
	foreach($priceRange as $keys => $vals){
		if($keys==$pr){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$keys."\" ".$selected.">";
		echo $vals;
		echo "</option>\n";
	}
}

function fun_admin_user_type_array(){
	$auType = array(
					"1" => "Law Firm",
					"2" => "Law Firm Admin ",
					"3" => "Law Firm Presenter ",
					"4" => "In House Lawyer"
				);
	return $auType;
}		

function fun_get_user_type_option($uTypeNo=''){
	$userTypeArray = fun_admin_user_type_array();
	foreach($userTypeArray as $keys => $vals){
		if($keys==$uTypeNo){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$keys."\" ".$selected.">";
		echo $vals;
		echo "</option>\n";
	}
}

function fun_get_user_type_name($uTypeNo=''){
	$userTypeArray = fun_admin_user_type_array();
	$userTypeName = "";
	foreach($userTypeArray as $keys => $vals){
		if($keys==$uTypeNo){
			$userTypeName = $vals;
		}
	}
	return $userTypeName;
}

function fun_cus_title_option($title){
	echo "<option value=\"Mr.\"";
	if($title=="Mr."){
		echo " selected";
	}
	echo ">Mr.</option>\n";
	
	echo "<option value=\"Mrs.\"";
	if($title=="Mrs."){
		echo " selected";
	}
	echo ">Mrs.</option>\n";
	
	echo "<option value=\"Ms.\"";
	if($title=="Ms."){
		echo " selected";
	}
	echo ">Ms.</option>\n";
	
	echo "<option value=\"Dr.\"";
	if($title=="Dr."){
		echo " selected";
	}
	echo ">Dr.</option>\n";
}

function funGetUSStatesArray(){
	$usStatesArray = array(
						"AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", "CO" => "Colorado",
						"CT" => "Connecticut", "DE" => "Delaware", "DC" => "District of Columbia", "FL" => "Florida", "GA" => "Georgia",
						"HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", "KS" => "Kansas",
						"KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts",
						"MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana",
						"NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", "NM" => "New Mexico",
						"NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma",
						"OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina", "SD" => "South Dakota",
						"TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington",
						"WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
					 );
	return $usStatesArray;
}

function funGetUSStatesOption($statesCode=''){
	$statesArray = funGetUSStatesArray();
	foreach($statesArray as $keys => $vals){
		if($keys==$statesCode){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$keys."\" ".$selected.">";
		echo $vals;
		echo "</option>\n";
	}
}

function funOrderStatusArray(){
	$osArray = array(
					OS_PENDING => "Not Started",
					OS_CONFIRM => "In Progress",
					OS_DELIVER => "Completed",
					//OS_SHIPPED => "Shipped",
					//OS_COMPLETE => "Completed",
					//OS_CANCELLED => "Cancelled",
				);
	return $osArray;
}
function funOrderStatusOption($osNo){
	$osArray = funOrderStatusArray();
	foreach($osArray as $keys => $vals){
		if((int)$keys==(int)$osNo){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$keys."\" ".$selected.">";
		echo $vals;
		echo "</option>\n";
	}
}
function funOrderStatusName($osNo){
	$osArray = funOrderStatusArray();
	$osName = "";
	foreach($osArray as $keys => $vals){
		if((int)$keys==(int)$osNo){
			$osName = $vals;
		}
	}
	return $osName;
}


function funPaymentStatusArray(){
	$psArray = array(
					PS_PENDING => "Not Paid",
					PS_CONFIRM => "Paid",
					//PS_INPROCESS => "In process",
					//PS_CLEAR => "Cleared",
					//PS_CANCELLED => "Cancelled",
				);
	return $psArray;
}
function funPaymentStatusOption($psNo){
	$psArray = funPaymentStatusArray();
	foreach($psArray as $keys => $vals){
		if((int)$keys==(int)$psNo){
			$selected = " selected";
		}else{
			$selected = "";
		}
		echo "<option value=\"".$keys."\" ".$selected.">";
		echo $vals;
		echo "</option>\n";
	}
}
function funPaymentStatusName($psNo){
	$psArray = funPaymentStatusArray();
	$psName = "";
	foreach($psArray as $keys => $vals){
		if((int)$keys==(int)$psNo){
			$psName = $vals;
		}
	}
	return $psName;
}

function funOrderStatusColorArray(){
	$osColorArray = array(
					OS_PENDING => "#ff0000",
					OS_CONFIRM => "#336600",
					OS_SHIPPED => "#0033CC",
					OS_COMPLETE => "#993399",
					OS_CANCELLED => "#000000",
					OS_DELIVER => "#993399"
	);
	return $osColorArray;
}
function funOrderStatusColor($osNo){
	$ocArray = funOrderStatusColorArray();
	$colorName = "";
	foreach($ocArray as $keys => $vals){
		if((int)$keys==(int)$osNo){
			$colorName = $vals;
		}
	}
	return $colorName;
}

function funPaymentStatusColorArray(){
	$psColorArray = array(
					PS_PENDING => "#ff0000",
					PS_CONFIRM => "#336600",
					PS_INPROCESS => "#0033CC",
					PS_CLEAR => "#993399",
					PS_CANCELLED => "#000000"
	);
	return $psColorArray;
}
function funPaymentStatusColor($psNo){
	$pcArray = funPaymentStatusColorArray();
	$colorName = "";
	foreach($pcArray as $keys => $vals){
		if((int)$keys==(int)$psNo){
			$colorName = $vals;
		}
	}
	return $colorName;
}

function funProductSizeOptions($sizeVals){
	if($sizeVals!=""){
		$sizeArry = explode(",",$sizeVals);
		if(is_array($sizeArry) && sizeof($sizeArry)){
			foreach($sizeArry as $keys => $vals){
				$tmpVal = trim($vals);
				if($tmpVal!=""){
					echo "<option value=\"".$tmpVal."\">";
					echo $tmpVal;
					echo "</option>\n";
				}
			}
		}
	}
	
}



function funGetGMTDateTimeDatebase(){

	return gmdate("Y-m-d H:i");

}



function funGetGMTDateTimeOutput($strDate){

	$dateFormat = "";

	if($strDate!="" && $strDate!="0000-00-00 00:00:00"){

		$dateFormat = gmdate("D, d M Y H:i", strtotime($strDate));

	}

	return $dateFormat;

}







?>