<?php
include("include/top-application.php");
include("include/header.php");


if($_REQUEST['cate_id']!="")
{
   $cate_id = $_REQUEST['cate_id'];
    $field_name = "cat_id";	 
	}


if($_REQUEST['cate_id']=="")
{
   $cate_id = 1;
    $field_name = "cat_id";	 
	}
 

$sqlSel_pages = "SELECT * FROM " . TABLE_SITE_PAGES ." WHERE  ".$field_name."='".$cate_id."'"; 
 $rsResult_pages = $dbObj->fun_db_query($sqlSel_pages);
 $catdatars_page = $dbObj->fun_db_fetch_rs_object($rsResult_pages);
?>
<title>Thankyou</title>



   
<style>
.leftPromo_homefull {
    background-image: url("images/ratesnote.png");
    background-position: 450px center;
    background-repeat: no-repeat;
    border-left: 0 solid #BFC4CD;
    color: #000000;
    float: left;
    margin-left: 30px;
    margin-right: 0;
    margin-top: 0;
    text-align: justify;
    width: 850px;
}
</style>

</head><body >
    <div id="outer">
       <div id="frame"><div class="bodycontb_header">
      <?php
	    include("include/mainbaner.php");	   
	   ?>    
       
         </DIV>



		 <div class="nav_nav">
  
  <?php
  include("include/main_nav.php")
  ?>
<div style="clear:both"></div>


		 </DIV>
         
		  <div class="nav_den">
      <div id="imageContainer"> <img src="images/slide1.jpg" width="900" height="300" /><img src="images/slide2.jpg" width="900" height="300" /><img src="images/slide3.jpg" width="900" height="300" /><img src="images/slide4.jpg" width="942" height="300"  /><img src="images/slide5.jpg" width="900" height="300"  /><img src="images/slide6.jpg" width="900" height="300"  /></div>
    </div>
    <div style="clear:both; "></div>	 
	
		 <div class="bodyContainerBorder">
		 		            <div id="pageBody">
<DIV class="white">		 
                            
                            
                     <DIV class="leftPromo_homefull">
                              <p style="padding-top:10px;"><span class="heading">Contact Us</span><br>
                                 <strong>Phone :</strong> (718) 854-5205<br>
                                <strong>E-mail :&nbsp; </strong><a href="mailto:rent@jwasser.com">rent@jwasser.com</a><br>
                                <br>
                                <span class="heading"><br>
                                Thank You</span><br>
                                <br>
                        <form name="form1" action="mail.php" method="post">
                          <p class="text13"><span class="font16"><em><strong>Thank you for  contacting J Wassor &amp; Co.</strong></em></span><span class="inner_text18"><span class="brown16"><strong><br />
Someone will be contacting you shortly. <br />
For faster response please call </strong><strong class="red16">(718) 854-5205</strong></span></span>.</p>
                          <br>
                          <br>
                          <br>
                        </form>      </p>

</div>       
                            
                            
	<?php
  include("include/footer.php");
?>	